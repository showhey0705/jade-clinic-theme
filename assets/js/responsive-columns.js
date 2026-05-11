/**
 * Responsive Columns — エディタ拡張（vip2026 子テーマ）
 *
 * Ollie Pro Responsive Controls の Typography「カラム」(column-count) 拡張。
 * 既存の `ollieResponsive` 属性に `columnCount: { tablet, mobile }` をぶら下げ、
 * Tablet/Mobile デバイスプレビュー時にだけ Typography パネルへ NumberControl を出す。
 *
 * 依存: window.wp.{hooks, element, blockEditor, components, data, blocks, i18n, compose}
 * 設定: window.vip2026ResponsiveColumns.targetBlocks
 *       (PHP 側で Ollie Pro の SUPPORTED_BLOCKS をミラー注入)
 */
( function ( wp ) {
	if ( ! wp || ! wp.hooks || ! wp.element || ! wp.blockEditor || ! wp.components || ! wp.data || ! wp.blocks ) {
		return;
	}

	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment, useEffect, useState } = wp.element;
	const { InspectorControls } = wp.blockEditor;
	const { __experimentalNumberControl: NumberControl } = wp.components;
	const { useSelect } = wp.data;
	const { getBlockType } = wp.blocks;
	const { createHigherOrderComponent } = wp.compose;
	const __ = ( wp.i18n && wp.i18n.__ ) || ( ( s ) => s );

	if ( ! NumberControl ) {
		return; // 旧 WP では非対応。silently skip。
	}

	const TARGET_BLOCKS = ( window.vip2026ResponsiveColumns && window.vip2026ResponsiveColumns.targetBlocks ) || [];

	/**
	 * 現在のエディタプレビューデバイスを正規化して返す。
	 * Desktop -> null（パネル非表示）/ Tablet -> 'tablet' / Mobile -> 'mobile'。
	 * WP バージョンで store とセレクタ名が違うので fallback チェーンする。
	 */
	function useBreakpoint() {
		return useSelect( ( select ) => {
			const candidates = [
				[ 'core/editor', 'getDeviceType' ],
				[ 'core/edit-post', '__experimentalGetPreviewDeviceType' ],
				[ 'core/edit-site', '__experimentalGetPreviewDeviceType' ],
				[ 'core/editor', '__experimentalGetPreviewDeviceType' ],
			];
			let device = 'Desktop';
			for ( const [ store, method ] of candidates ) {
				const sel = select( store );
				if ( sel && typeof sel[ method ] === 'function' ) {
					const v = sel[ method ]();
					if ( v ) {
						device = v;
						break;
					}
				}
			}
			if ( device === 'Tablet' ) return 'tablet';
			if ( device === 'Mobile' ) return 'mobile';
			return null;
		}, [] );
	}

	/**
	 * ollieResponsive.columnCount.{breakpoint} の値を更新。空文字は当該キー削除。
	 */
	function updateColumnCount( attributes, setAttributes, breakpoint, value ) {
		const current = ( attributes.ollieResponsive && attributes.ollieResponsive.columnCount ) || {};
		const next = Object.assign( {}, current );
		if ( value === '' || value === undefined || value === null ) {
			delete next[ breakpoint ];
		} else {
			next[ breakpoint ] = value;
		}
		const newResponsive = Object.assign( {}, attributes.ollieResponsive || {} );
		if ( Object.keys( next ).length > 0 ) {
			newResponsive.columnCount = next;
		} else {
			delete newResponsive.columnCount;
		}
		setAttributes( { ollieResponsive: Object.keys( newResponsive ).length > 0 ? newResponsive : {} } );
	}

	/**
	 * デバイスが Tablet/Mobile のとき、対象ブロックの編集中 DOM へ column-count を
	 * inject してプレビューを反映する。Ollie Pro `ResponsivePreviewStyle` と同等手口。
	 */
	function PreviewStyle( props ) {
		const { clientId, value } = props;
		const [ headEl, setHeadEl ] = useState( null );

		useEffect( () => {
			let raf = 0;
			const find = () => {
				const el1 = document.getElementById( 'block-' + clientId );
				const iframe = document.querySelector( 'iframe[name="editor-canvas"]' );
				const el2 = iframe && iframe.contentDocument && iframe.contentDocument.getElementById( 'block-' + clientId );
				const found = el1 || el2;
				if ( found ) {
					setHeadEl( found.ownerDocument.head );
				}
			};
			find();
			raf = requestAnimationFrame( find );
			return () => cancelAnimationFrame( raf );
		}, [ clientId ] );

		if ( ! headEl || ! value ) return null;

		const css = '#block-' + clientId + ' { column-count: ' + value + ' !important; }';
		// ReactDOM.createPortal を element 経由で。
		const ReactDOM = wp.element && wp.element.createPortal ? wp.element : null;
		if ( ! ReactDOM ) return null;
		return ReactDOM.createPortal(
			el( 'style', { 'data-vip2026-responsive-columns': clientId }, css ),
			headEl
		);
	}

	/**
	 * Typography パネル末尾に追加する Responsive Columns コントロール。
	 * Tablet/Mobile プレビュー時のみマウントされる。
	 */
	function ResponsiveColumnsControl( props ) {
		const { attributes, setAttributes, clientId } = props;
		const breakpoint = useBreakpoint();
		if ( ! breakpoint ) return null;

		const value = ( attributes.ollieResponsive && attributes.ollieResponsive.columnCount && attributes.ollieResponsive.columnCount[ breakpoint ] ) || '';
		const labelSuffix = breakpoint === 'tablet' ? ' (Tablet)' : ' (Mobile)';

		// コア「カラム」コントロールを :has() で隠す inline style。
		// 同パネル内で input[type=number] を持ち、line-height / unit-control / font-size-picker
		// のいずれも持たない ToolsPanelItem.single-column が column-count コントロールに合致する。
		const hideCoreCss =
			'.typography-block-support-panel:has(.vip2026-responsive-columns) ' +
			'.components-tools-panel-item.single-column:has(input[type="number"]):not(:has(.block-editor-line-height-control)):not(:has(.components-unit-control)):not(:has(.components-font-size-picker)) ' +
			'{ display: none !important; }';

		// プレビュー時に effective value（Tablet→Mobile カスケード）を計算。
		const effective = (function () {
			const cc = ( attributes.ollieResponsive && attributes.ollieResponsive.columnCount ) || {};
			if ( breakpoint === 'mobile' ) return cc.mobile || cc.tablet || '';
			return cc.tablet || '';
		})();

		return el(
			'div',
			{ className: 'vip2026-responsive-columns' },
			el( 'style', null, hideCoreCss ),
			el( NumberControl, {
				label: __( 'Columns', 'vip2026' ) + labelSuffix,
				value: value,
				onChange: ( v ) => updateColumnCount( attributes, setAttributes, breakpoint, v ),
				min: 1,
				max: 6,
				__next40pxDefaultSize: true,
				__nextHasNoMarginBottom: true,
				spinControls: 'custom',
			} ),
			el( PreviewStyle, { clientId: clientId, value: effective } )
		);
	}

	/**
	 * 対象ブロックか判定。Ollie Pro の SUPPORTED_BLOCKS に含まれ、かつそのブロックが
	 * typography.textColumns supports を持つこと（vip2026 theme.json で true 宣言済み）。
	 */
	function isTargetBlock( name ) {
		if ( ! TARGET_BLOCKS.includes( name ) ) return false;
		const type = getBlockType( name );
		const support = type && type.supports && type.supports.typography && type.supports.typography.textColumns;
		// theme.json で textColumns: true を opt-in しているとブロックの supports.typography に
		// 直接乗らないケースもあるので、support が false でなければ通す（保守的に hidden 化しない）。
		return support !== false;
	}

	const withResponsiveColumns = createHigherOrderComponent(
		( BlockEdit ) => ( props ) => {
			if ( ! isTargetBlock( props.name ) ) {
				return el( BlockEdit, props );
			}
			return el(
				Fragment,
				null,
				el( BlockEdit, props ),
				el(
					InspectorControls,
					{ group: 'typography' },
					el( ResponsiveColumnsControl, {
						attributes: props.attributes,
						setAttributes: props.setAttributes,
						clientId: props.clientId,
					} )
				)
			);
		},
		'withResponsiveColumns'
	);

	addFilter( 'editor.BlockEdit', 'vip2026/responsive-columns', withResponsiveColumns );
} )( window.wp );
