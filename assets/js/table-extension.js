/**
 * Editor 拡張: core/table に「テーブル設定」「横スクロール設定」と
 * セル記号フォーマットを追加する。SWELL のテーブル設定を参考にテーマ非依存で再実装。
 *
 * 属性:
 *   bcpScrollable    : ''|'sp'|'pc'|'both'  横スクロール
 *   bcpScrollHint    : bool                 「スクロールできます」ガイド
 *   bcpTableWidth    : string(px)           テーブルの横幅（スクロール時）
 *   bcpFixedHead     : ''|'sp'|'both'        ヘッダー(thead)固定
 *   bcpFixedFirstCol : bool                 1 列目を左端に固定
 *   bcpFirstColTh    : bool                 1 列目の td を th 風に
 *   bcpStack         : bool                 スマホで縦並び表示
 *   bcpMinCol        : ''|'10'|'20'|'30'    各列で最低限維持する幅
 *
 * フロント出力差し替えは PHP の render_block_core/table（class-table-extension.php）。
 */
( function () {
	'use strict';

	const {
		hooks: { addFilter },
		element: { createElement: el, Fragment, useState },
		compose: { createHigherOrderComponent },
		blockEditor: { InspectorControls, RichTextToolbarButton, useSetting, PanelColorSettings },
		components: { PanelBody, SelectControl, ToggleControl, TextControl, Popover, Button, ButtonGroup },
		richText: { registerFormatType, insertObject, toggleFormat, remove, applyFormat, removeFormat, getActiveFormat },
		i18n: { __ },
	} = window.wp;

	const TARGET = 'core/table';

	/* ─────────────────────────────────────────────
	 * 1) 属性を core/table に追加 + スタイルプレビュー用 example
	 * ───────────────────────────────────────────── */
	addFilter( 'blocks.registerBlockType', 'bcp/table/attrs', ( settings, name ) => {
		if ( name !== TARGET ) return settings;

		const attributes = {
			...( settings.attributes || {} ),
			bcpScrollable: { type: 'string', default: '' },
			bcpScrollHint: { type: 'boolean', default: true },
			bcpTableWidth: { type: 'string', default: '' },
			bcpFixedHead: { type: 'string', default: '' },
			bcpFixedFirstCol: { type: 'boolean', default: false },
			bcpFirstColTh: { type: 'boolean', default: false },
			bcpStack: { type: 'boolean', default: false },
			bcpMinCol: { type: 'string', default: '' },
			bcpCol1Width: { type: 'string', default: '' },
			bcpCol1Bg: { type: 'string', default: '' },
			bcpCol1Text: { type: 'string', default: '' },
		};

		// ブロックスタイルのプレビューがブランクにならないよう example を補う。
		const example = settings.example || {
			viewportWidth: 480,
			attributes: {
				hasFixedLayout: true,
				head: [ { cells: [
					{ content: __( '項目', 'beauty-clinic-patterns' ), tag: 'th' },
					{ content: 'A', tag: 'th' },
					{ content: 'B', tag: 'th' },
				] } ],
				body: [
					{ cells: [
						{ content: __( '行1', 'beauty-clinic-patterns' ), tag: 'td' },
						{ content: '◎', tag: 'td' },
						{ content: '○', tag: 'td' },
					] },
					{ cells: [
						{ content: __( '行2', 'beauty-clinic-patterns' ), tag: 'td' },
						{ content: '△', tag: 'td' },
						{ content: '×', tag: 'td' },
					] },
				],
			},
		};

		return { ...settings, attributes, example };
	} );

	/* ─────────────────────────────────────────────
	 * 2) Inspector: 「テーブル設定」「横スクロール設定」2 パネル
	 * ───────────────────────────────────────────── */
	const withTableControls = createHigherOrderComponent( ( BlockEdit ) => {
		return ( props ) => {
			if ( props.name !== TARGET ) return el( BlockEdit, props );

			const { attributes: a, setAttributes: set } = props;
			const isScrollable = '' !== ( a.bcpScrollable || '' );
			const themePalette = ( typeof useSetting === 'function' && useSetting( 'color.palette' ) ) || [];

			const showCol1Color = !! a.bcpFirstColTh;

			const panels = el(
				InspectorControls,
				{ key: 'bcp-table' },
				el(
					PanelBody,
					{ title: __( 'テーブル設定', 'beauty-clinic-patterns' ), initialOpen: true },
					el( ToggleControl, {
						label: __( '1 列目を見出し列にする', 'beauty-clinic-patterns' ),
						checked: !! a.bcpFirstColTh,
						onChange: ( v ) => set( { bcpFirstColTh: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '行の見出しになる列。太字になり、下のパネルで色も選べます。', 'beauty-clinic-patterns' ),
					} ),
					el( ToggleControl, {
						label: __( 'スマホで縦並びにする', 'beauty-clinic-patterns' ),
						checked: !! a.bcpStack,
						onChange: ( v ) => set( { bcpStack: v } ),
						__nextHasNoMarginBottom: true,
						help: __( 'スマホでは各行をカードのように縦に並べます。', 'beauty-clinic-patterns' ),
					} ),
					el( SelectControl, {
						label: __( '列のつぶれを防ぐ', 'beauty-clinic-patterns' ),
						value: a.bcpMinCol || '',
						options: [
							{ label: __( 'なし', 'beauty-clinic-patterns' ), value: '' },
							{ label: __( '弱', 'beauty-clinic-patterns' ), value: '10' },
							{ label: __( '中', 'beauty-clinic-patterns' ), value: '20' },
							{ label: __( '強', 'beauty-clinic-patterns' ), value: '30' },
						],
						onChange: ( v ) => set( { bcpMinCol: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '長い文章があっても各列の幅を確保します。', 'beauty-clinic-patterns' ),
					} ),
					el( TextControl, {
						label: __( '1 列目の幅', 'beauty-clinic-patterns' ),
						value: a.bcpCol1Width || '',
						onChange: ( v ) => set( { bcpCol1Width: v } ),
						placeholder: 'auto',
						help: __( '例: 30% や 220px。空欄で自動。', 'beauty-clinic-patterns' ),
						__nextHasNoMarginBottom: true,
					} )
				),
				// 「見出し列にする」ON のときだけ表示する独立パネル（WP 標準の色UI / 余白を他と統一）。
				showCol1Color && PanelColorSettings && el( PanelColorSettings, {
					title: __( '見出し列の色', 'beauty-clinic-patterns' ),
					initialOpen: true,
					colors: themePalette,
					colorSettings: [
						{
							label: __( '背景色', 'beauty-clinic-patterns' ),
							value: a.bcpCol1Bg || undefined,
							onChange: ( v ) => set( { bcpCol1Bg: v || '' } ),
						},
						{
							label: __( '文字色', 'beauty-clinic-patterns' ),
							value: a.bcpCol1Text || undefined,
							onChange: ( v ) => set( { bcpCol1Text: v || '' } ),
						},
					],
				} ),
				el(
					PanelBody,
					{ title: __( '横スクロール設定', 'beauty-clinic-patterns' ), initialOpen: false },
					el( SelectControl, {
						label: __( '横スクロール', 'beauty-clinic-patterns' ),
						value: a.bcpScrollable || '',
						options: [
							{ label: __( 'なし', 'beauty-clinic-patterns' ), value: '' },
							{ label: __( 'スマホだけ', 'beauty-clinic-patterns' ), value: 'sp' },
							{ label: __( 'パソコンだけ', 'beauty-clinic-patterns' ), value: 'pc' },
							{ label: __( '常に', 'beauty-clinic-patterns' ), value: 'both' },
						],
						onChange: ( v ) => set( { bcpScrollable: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '列が多い表を、はみ出さず横スクロールで見せます。', 'beauty-clinic-patterns' ),
					} ),
					isScrollable && el( TextControl, {
						label: __( '表の横幅', 'beauty-clinic-patterns' ),
						type: 'number',
						value: a.bcpTableWidth || '',
						onChange: ( v ) => set( { bcpTableWidth: v } ),
						help: __( '空欄で自動。数値のみ（px）。', 'beauty-clinic-patterns' ),
						__nextHasNoMarginBottom: true,
					} ),
					isScrollable && el( ToggleControl, {
						label: __( '1 列目を左に固定する', 'beauty-clinic-patterns' ),
						checked: !! a.bcpFixedFirstCol,
						onChange: ( v ) => set( { bcpFixedFirstCol: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '横スクロール中も 1 列目を表示し続けます。', 'beauty-clinic-patterns' ),
					} ),
					isScrollable && el( ToggleControl, {
						label: __( '「スクロールできます」を表示', 'beauty-clinic-patterns' ),
						checked: a.bcpScrollHint !== false,
						onChange: ( v ) => set( { bcpScrollHint: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '横に動かせることを小さく案内します。', 'beauty-clinic-patterns' ),
					} ),
					el( SelectControl, {
						label: __( '見出し行を上に固定', 'beauty-clinic-patterns' ),
						value: a.bcpFixedHead || '',
						options: [
							{ label: __( 'なし', 'beauty-clinic-patterns' ), value: '' },
							{ label: __( 'スマホだけ', 'beauty-clinic-patterns' ), value: 'sp' },
							{ label: __( '常に', 'beauty-clinic-patterns' ), value: 'both' },
						],
						onChange: ( v ) => set( { bcpFixedHead: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '縦スクロール中も見出し行を表示し続けます。', 'beauty-clinic-patterns' ),
					} )
				)
			);

			return el( Fragment, {}, el( BlockEdit, props ), panels );
		};
	}, 'withBcpTableControls' );

	addFilter( 'editor.BlockEdit', 'bcp/table/controls', withTableControls );

	/* ─────────────────────────────────────────────
	 * 3) セル記号フォーマット（◎ 〇 △ × ？ ✓ ―）
	 * ───────────────────────────────────────────── */
	const FORMAT_NAME = 'bcp/cell-mark';

	const MARKS = [
		{ key: 'doubleCircle', label: __( '二重丸（最適）', 'beauty-clinic-patterns' ), glyph: '◎', color: '#e8883a' },
		{ key: 'circle', label: __( '丸（良い）', 'beauty-clinic-patterns' ), glyph: '〇', color: '#2f9e69' },
		{ key: 'triangle', label: __( '三角（条件付き）', 'beauty-clinic-patterns' ), glyph: '△', color: '#d8a200' },
		{ key: 'cross', label: __( 'バツ（非対応）', 'beauty-clinic-patterns' ), glyph: '×', color: '#d0463b' },
		{ key: 'question', label: __( 'はてな（不明）', 'beauty-clinic-patterns' ), glyph: '？', color: '#4f86d6' },
		{ key: 'check', label: __( 'チェック（対応）', 'beauty-clinic-patterns' ), glyph: '✓', color: '#2f9e69' },
		{ key: 'dash', label: __( '横棒（該当なし）', 'beauty-clinic-patterns' ), glyph: '―', color: '#b0b4ba' },
	];
	const SIZES = [
		{ key: 'l', label: __( '大', 'beauty-clinic-patterns' ) },
		{ key: 'm', label: __( '中', 'beauty-clinic-patterns' ) },
		{ key: 's', label: __( '小', 'beauty-clinic-patterns' ) },
	];

	const CellMarkEdit = ( { value, onChange } ) => {
		const [ isOpen, setIsOpen ] = useState( false );
		const [ asBg, setAsBg ] = useState( false );
		const [ size, setSize ] = useState( 'l' );

		const insertMark = ( key ) => {
			const attributes = { 'data-bcp-mark': key };
			if ( asBg ) {
				attributes[ 'data-bcp-mark-bg' ] = '1';
				attributes[ 'data-bcp-mark-size' ] = size;
			}
			// 再設定時の重複を防ぐため、セル内の既存セル記号オブジェクトを除去してから挿入。
			let v = value;
			const reps = v.replacements || [];
			const idx = [];
			reps.forEach( ( r, i ) => {
				const type = Array.isArray( r ) ? ( r[ 0 ] && r[ 0 ].type ) : ( r && r.type );
				if ( type === FORMAT_NAME ) idx.push( i );
			} );
			idx.sort( ( a, b ) => b - a ).forEach( ( i ) => { v = remove( v, i, i + 1 ); } );
			onChange( insertObject( v, { type: FORMAT_NAME, attributes } ) );
			setIsOpen( false );
		};

		return el(
			Fragment,
			{},
			el( RichTextToolbarButton, {
				icon: 'star-filled',
				title: __( 'セル記号', 'beauty-clinic-patterns' ),
				onClick: () => setIsOpen( ( v ) => ! v ),
				isActive: isOpen,
			} ),
			isOpen && el(
				Popover,
				{ position: 'bottom center', onClose: () => setIsOpen( false ), focusOnMount: true },
				el(
					'div',
					{ style: { padding: '8px', width: '244px' } },
					el(
						'div',
						{ style: { display: 'flex', flexWrap: 'wrap', gap: '2px', marginBottom: '8px' } },
						MARKS.map( ( m ) => el( Button, {
							key: m.key,
							label: m.label,
							showTooltip: true,
							onClick: () => insertMark( m.key ),
							style: { fontSize: '20px', minWidth: '30px', justifyContent: 'center', color: m.color },
						}, m.glyph ) )
					),
					el( ToggleControl, {
						label: __( '背景に大きく表示する', 'beauty-clinic-patterns' ),
						checked: asBg,
						onChange: setAsBg,
						__nextHasNoMarginBottom: true,
						help: __( '記号を入力テキストの背景に大きく表示します。', 'beauty-clinic-patterns' ),
					} ),
					asBg && el(
						'div',
						{ style: { marginTop: '6px' } },
						el( ButtonGroup, {}, SIZES.map( ( s ) => el( Button, {
							key: s.key,
							isPrimary: size === s.key,
							isSmall: true,
							onClick: () => setSize( s.key ),
						}, s.label ) ) )
					)
				)
			)
		);
	};

	registerFormatType( FORMAT_NAME, {
		title: __( 'セル記号', 'beauty-clinic-patterns' ),
		tagName: 'span',
		className: 'bcp-cell-mark',
		object: true,
		attributes: {
			'data-bcp-mark': 'data-bcp-mark',
			'data-bcp-mark-bg': 'data-bcp-mark-bg',
			'data-bcp-mark-size': 'data-bcp-mark-size',
		},
		edit: CellMarkEdit,
	} );

	/* ─────────────────────────────────────────────
	 * 4) ボタン化フォーマット（リンクをボタン表示）。
	 *    コアの「リンク」を付けたテキストに適用するとボタン見た目に。
	 *    予約 CTA / アフィリエイトボタンに使える（rel/target はコアのリンク UI で設定）。
	 * ───────────────────────────────────────────── */
	const BTN_FORMAT = 'bcp/cell-button';
	const ButtonEdit = ( { value, onChange } ) => {
		const [ open, setOpen ] = useState( false );
		const [ url, setUrl ] = useState( '' );
		const [ style, setStyle ] = useState( 'solid' );
		const [ newtab, setNewtab ] = useState( false );

		const openPanel = () => {
			const af = getActiveFormat( value, BTN_FORMAT );
			const at = ( af && af.attributes ) ? af.attributes : {};
			setUrl( at.url || '' );
			setStyle( at.dataBtn || 'solid' );
			setNewtab( at.target === '_blank' );
			setOpen( true );
		};
		const apply = () => {
			const attributes = { url: url, dataBtn: style };
			if ( newtab ) {
				attributes.target = '_blank';
				attributes.rel = 'noopener noreferrer';
			}
			onChange( applyFormat( value, { type: BTN_FORMAT, attributes } ) );
			setOpen( false );
		};
		const clear = () => {
			onChange( removeFormat( value, BTN_FORMAT ) );
			setOpen( false );
		};

		return el(
			Fragment,
			{},
			el( RichTextToolbarButton, {
				icon: 'megaphone',
				title: __( 'ボタン化（リンクボタン）', 'beauty-clinic-patterns' ),
				isActive: !! getActiveFormat( value, BTN_FORMAT ),
				onClick: () => ( open ? setOpen( false ) : openPanel() ),
			} ),
			open && el(
				Popover,
				{ position: 'bottom center', onClose: () => setOpen( false ), focusOnMount: true },
				el(
					'div',
					{ style: { padding: '10px', width: '262px' } },
					el( TextControl, {
						label: __( 'リンク URL', 'beauty-clinic-patterns' ),
						value: url,
						onChange: setUrl,
						placeholder: 'https://… / tel:… / line:…',
						__nextHasNoMarginBottom: true,
					} ),
					el(
						'div',
						{ style: { margin: '8px 0' } },
						el(
							ButtonGroup,
							{},
							el( Button, { isSmall: true, isPrimary: style === 'solid', onClick: () => setStyle( 'solid' ) }, __( '塗り', 'beauty-clinic-patterns' ) ),
							el( Button, { isSmall: true, isPrimary: style === 'outline', onClick: () => setStyle( 'outline' ) }, __( '白抜き', 'beauty-clinic-patterns' ) )
						)
					),
					el( ToggleControl, {
						label: __( '新しいタブで開く', 'beauty-clinic-patterns' ),
						checked: newtab,
						onChange: setNewtab,
						__nextHasNoMarginBottom: true,
					} ),
					el(
						'div',
						{ style: { display: 'flex', gap: '6px', marginTop: '10px' } },
						el( Button, { variant: 'primary', onClick: apply }, __( '適用', 'beauty-clinic-patterns' ) ),
						el( Button, { variant: 'tertiary', onClick: clear }, __( '解除', 'beauty-clinic-patterns' ) )
					)
				)
			)
		);
	};
	registerFormatType( BTN_FORMAT, {
		title: __( 'ボタン化', 'beauty-clinic-patterns' ),
		tagName: 'a',
		className: 'bcp-cell-button',
		attributes: {
			url: 'href',
			dataBtn: 'data-bcp-btn',
			target: 'target',
			rel: 'rel',
		},
		edit: ButtonEdit,
	} );

	/* ─────────────────────────────────────────────
	 * 5) 星評価フォーマット（おすすめ度 5 段階、0.5 刻み）。
	 *    object 形式で空 span を挿入し、★ は CSS で描画。
	 * ───────────────────────────────────────────── */
	const STARS_FORMAT = 'bcp/cell-stars';
	const STAR_VALUES = [ 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5 ];

	const StarsEdit = ( { value, onChange } ) => {
		const [ open, setOpen ] = useState( false );
		const insert = ( n ) => {
			// 再設定時の重複を防ぐため、セル内の既存★オブジェクトを除去してから挿入。
			let v = value;
			const reps = v.replacements || [];
			const idx = [];
			reps.forEach( ( r, i ) => {
				const type = Array.isArray( r ) ? ( r[ 0 ] && r[ 0 ].type ) : ( r && r.type );
				if ( type === STARS_FORMAT ) idx.push( i );
			} );
			idx.sort( ( a, b ) => b - a ).forEach( ( i ) => { v = remove( v, i, i + 1 ); } );
			onChange( insertObject( v, { type: STARS_FORMAT, attributes: { 'data-bcp-stars': String( n ) } } ) );
			setOpen( false );
		};
		return el(
			Fragment,
			{},
			el( RichTextToolbarButton, {
				icon: 'star-half',
				title: __( '星評価', 'beauty-clinic-patterns' ),
				onClick: () => setOpen( ( v ) => ! v ),
				isActive: open,
			} ),
			open && el(
				Popover,
				{ position: 'bottom center', onClose: () => setOpen( false ), focusOnMount: true },
				el(
					'div',
					{ style: { display: 'grid', gridTemplateColumns: 'repeat(3, auto)', gap: '4px', padding: '8px' } },
					STAR_VALUES.map( ( n ) =>
						el( Button, {
							key: n,
							isSmall: true,
							variant: 'secondary',
							onClick: () => insert( n ),
							style: { justifyContent: 'center' },
						}, n + '★' )
					)
				)
			)
		);
	};

	registerFormatType( STARS_FORMAT, {
		title: __( '星評価', 'beauty-clinic-patterns' ),
		tagName: 'span',
		className: 'bcp-cell-stars',
		object: true,
		attributes: { 'data-bcp-stars': 'data-bcp-stars' },
		edit: StarsEdit,
	} );
} )();
