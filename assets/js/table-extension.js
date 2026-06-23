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
		element: { createElement: el, Fragment, useState, useEffect, useRef },
		compose: { createHigherOrderComponent },
		blockEditor: { InspectorControls, RichTextToolbarButton, useSetting },
		components: { PanelBody, SelectControl, ToggleControl, TextControl, Popover, Button, ButtonGroup, ColorPalette, Dropdown, ColorIndicator, __experimentalToggleGroupControl: ToggleGroupControl, __experimentalToggleGroupControlOption: ToggleGroupControlOption },
		richText: { registerFormatType, insertObject, remove, applyFormat, removeFormat, getActiveFormat },
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
			bcpBodyAlign: { type: 'string', default: '' },
			bcpLegend: { type: 'boolean', default: false },
			bcpAnimate: { type: 'boolean', default: false },
			bcpCol1Width: { type: 'string', default: '' },
			bcpCol1Bg: { type: 'string', default: '' },
			bcpCol1Text: { type: 'string', default: '' },
			bcpNote: { type: 'boolean', default: false },
			bcpNoteText: { type: 'string', default: '' },
			bcpTaxToggle: { type: 'boolean', default: false },
			bcpStickyCta: { type: 'boolean', default: false },
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
			// C-10/11/12（注釈/税込税抜/申込行追従）のトグルは BCP 有効サイトでのみ表示する。
			// コードは全サイト統一だが、UI は PHP が立てる window.bcpTableExtra=true のときだけ出す。
			// 属性・描画は常に有効なので、既存コンテンツに bcpNote 等があれば非表示サイトでも描画される。
			const showExtra = ( typeof window !== 'undefined' && window.bcpTableExtra === true );

			// 推し列（強調列）: className(is-bcp-recommend-N / is-bcp-recstyle-X)で管理する汎用機能。
			const recCn    = a.className || '';
			const recCol   = ( recCn.match( /is-bcp-recommend-([123])/ ) || [] )[ 1 ] || '';
			const recStyle = ( recCn.match( /is-bcp-recstyle-([a-z]+)/ ) || [] )[ 1 ] || 'card';
			const setRec = ( col, style ) => {
				const keep = recCn.split( /\s+/ ).filter( Boolean ).filter( ( c ) => ! /^is-bcp-(recommend|recstyle)-/.test( c ) );
				if ( col ) {
					keep.push( 'is-bcp-recommend-' + col );
					keep.push( 'is-bcp-recstyle-' + ( style || 'card' ) );
				}
				set( { className: keep.join( ' ' ) || undefined } );
			};
			// スタイルチップ（小さな見本付き）。見本は編集UI用の簡易表現。
			const REC_STYLES = [
				{ key: 'card', label: __( 'カード', 'beauty-clinic-patterns' ), chip: { background: '#f0ece8', border: '1px solid #d8cfc4', borderTop: '3px solid #8f6f52' } },
				{ key: 'solid', label: __( '塗り', 'beauty-clinic-patterns' ), chip: { background: '#8f6f52', border: '1px solid #8f6f52' } },
				{ key: 'outline', label: __( 'アウトライン', 'beauty-clinic-patterns' ), chip: { background: '#fff', border: '2px solid #8f6f52' } },
				{ key: 'ribbon', label: __( 'リボン', 'beauty-clinic-patterns' ), chip: { background: '#f0ece8', border: '1px solid #d8cfc4', boxShadow: 'inset 0 5px 0 -2px #8f6f52' } },
			];

			// 見出し列の色: 他のコントロールと同じ階層にフラットに並ぶコンパクト行
			// （色丸＋ラベル → クリックで ColorPalette ポップオーバー）。入れ子パネルにしないので左揃えが揃う。
			const colorRow = ( label, value, onChange ) => el( Dropdown, {
				popoverProps: { placement: 'left-start', offset: 8 },
				renderToggle: ( { isOpen, onToggle } ) => el( Button, {
					onClick: onToggle,
					'aria-expanded': isOpen,
					style: { width: '100%', justifyContent: 'flex-start', gap: '10px', height: '36px', padding: '0 8px', boxShadow: 'inset 0 0 0 1px #e0e0e0', borderRadius: '2px' },
				},
					ColorIndicator ? el( ColorIndicator, { colorValue: value || 'transparent' } ) : null,
					el( 'span', {}, label )
				),
				renderContent: () => el( 'div', { style: { padding: '8px', minWidth: '232px' } },
					el( ColorPalette, { colors: themePalette, value: value || undefined, onChange: ( v ) => onChange( v || '' ), clearable: true, enableAlpha: false } )
				),
			} );

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
						help: __( '1列目を「項目名」の列にします。太字になり、色も選べます。', 'beauty-clinic-patterns' ),
					} ),
					// 見出し列の色（ON のときだけ・テーブル設定内にフラットに統合）。
					showCol1Color && el(
						'div',
						{ style: { marginBottom: '16px' } },
						el( 'div', { style: { fontSize: '11px', textTransform: 'uppercase', fontWeight: 500, color: '#757575', margin: '2px 0 6px' } }, __( '見出し列の色', 'beauty-clinic-patterns' ) ),
						el(
							'div',
							{ style: { display: 'flex', flexDirection: 'column', gap: '6px' } },
							colorRow( __( '背景色', 'beauty-clinic-patterns' ), a.bcpCol1Bg, ( v ) => set( { bcpCol1Bg: v } ) ),
							colorRow( __( '文字色', 'beauty-clinic-patterns' ), a.bcpCol1Text, ( v ) => set( { bcpCol1Text: v } ) )
						)
					),
					el( ToggleControl, {
						label: __( 'スマホで縦並びにする', 'beauty-clinic-patterns' ),
						checked: !! a.bcpStack,
						onChange: ( v ) => set( { bcpStack: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '横長の表を、スマホでは行ごとのカードに積み替えます。', 'beauty-clinic-patterns' ),
					} ),
					el( SelectControl, {
						label: __( '列が狭くなりすぎないようにする', 'beauty-clinic-patterns' ),
						value: a.bcpMinCol || '',
						options: [
							{ label: __( 'しない', 'beauty-clinic-patterns' ), value: '' },
							{ label: __( '少し広げる', 'beauty-clinic-patterns' ), value: '10' },
							{ label: __( 'しっかり広げる', 'beauty-clinic-patterns' ), value: '20' },
							{ label: __( '大きく広げる', 'beauty-clinic-patterns' ), value: '30' },
						],
						onChange: ( v ) => set( { bcpMinCol: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '列が多い表で、各列が狭くなって文字が読みづらくなるのを防ぎます。', 'beauty-clinic-patterns' ),
					} ),
					el( SelectControl, {
						label: __( 'データ列の揃え', 'beauty-clinic-patterns' ),
						value: a.bcpBodyAlign || '',
						options: [
							{ label: __( '左（標準）', 'beauty-clinic-patterns' ), value: '' },
							{ label: __( '中央', 'beauty-clinic-patterns' ), value: 'center' },
							{ label: __( '右', 'beauty-clinic-patterns' ), value: 'right' },
						],
						onChange: ( v ) => set( { bcpBodyAlign: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '数値や記号の位置を左・中央・右でそろえます（1列目はそのまま）。', 'beauty-clinic-patterns' ),
					} ),
					el( ToggleControl, {
						label: __( '記号の凡例を表示', 'beauty-clinic-patterns' ),
						checked: !! a.bcpLegend,
						onChange: ( v ) => set( { bcpLegend: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '◎○△× などの意味を、表の下に自動でまとめて表示します。', 'beauty-clinic-patterns' ),
					} ),
					el( ToggleControl, {
						label: __( '出現アニメーション', 'beauty-clinic-patterns' ),
						checked: !! a.bcpAnimate,
						onChange: ( v ) => set( { bcpAnimate: v } ),
						__nextHasNoMarginBottom: true,
						help: __( '表が画面に入ると、星や記号が軽く動いて登場します。', 'beauty-clinic-patterns' ),
					} ),
					// 推し列（強調列）: セグメントで列、スタイルはビジュアルチップで選ぶ。汎用機能なので全サイト表示。
					ToggleGroupControl && el(
						'div',
						{ style: { marginTop: '4px', marginBottom: '8px' } },
						el( ToggleGroupControl, {
							label: __( '推しの列', 'beauty-clinic-patterns' ),
							value: recCol,
							isBlock: true,
							onChange: ( v ) => setRec( v, recStyle ),
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true,
						},
							el( ToggleGroupControlOption, { value: '', label: __( 'なし', 'beauty-clinic-patterns' ) } ),
							el( ToggleGroupControlOption, { value: '1', label: '1' } ),
							el( ToggleGroupControlOption, { value: '2', label: '2' } ),
							el( ToggleGroupControlOption, { value: '3', label: '3' } )
						),
						recCol && el(
							'div',
							{ style: { marginTop: '10px' } },
							el( 'div', { style: { fontSize: '11px', textTransform: 'uppercase', fontWeight: 500, color: '#757575', marginBottom: '6px' } }, __( '推しスタイル', 'beauty-clinic-patterns' ) ),
							el(
								'div',
								{ style: { display: 'flex', flexWrap: 'wrap', gap: '6px' } },
								REC_STYLES.map( ( s ) => el( Button, {
									key: s.key,
									label: s.label,
									onClick: () => setRec( recCol, s.key ),
									style: { height: 'auto', padding: '4px', flexDirection: 'column', gap: '3px', borderRadius: '6px', boxShadow: recStyle === s.key ? 'inset 0 0 0 2px #1e1e1e' : 'inset 0 0 0 1px #e0e0e0' },
								},
									el( 'span', { style: Object.assign( { display: 'block', width: '40px', height: '24px', borderRadius: '4px' }, s.chip ) } ),
									el( 'span', { style: { fontSize: '10px', lineHeight: 1.2 } }, s.label )
								) )
							)
						)
					),
					showExtra && el( ToggleControl, {
						label: __( '注釈（※書き）を表示', 'beauty-clinic-patterns' ),
						checked: !! a.bcpNote,
						onChange: ( v ) => set( { bcpNote: v } ),
						__nextHasNoMarginBottom: true,
					} ),
					showExtra && !! a.bcpNote && el( TextControl, {
						label: __( '注釈テキスト', 'beauty-clinic-patterns' ),
						value: a.bcpNoteText || '',
						onChange: ( v ) => set( { bcpNoteText: v } ),
						placeholder: __( '※効果・感じ方には個人差があります。', 'beauty-clinic-patterns' ),
						__nextHasNoMarginBottom: true,
					} ),
					showExtra && el( ToggleControl, {
						label: __( '税込/税抜の切替を表示', 'beauty-clinic-patterns' ),
						checked: !! a.bcpTaxToggle,
						onChange: ( v ) => set( { bcpTaxToggle: v } ),
						__nextHasNoMarginBottom: true,
					} ),
					showExtra && el( ToggleControl, {
						label: __( 'スマホで申込行を追従', 'beauty-clinic-patterns' ),
						checked: !! a.bcpStickyCta,
						onChange: ( v ) => set( { bcpStickyCta: v } ),
						__nextHasNoMarginBottom: true,
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

	// 既存のセル記号オブジェクト（の format）をセル内から探す。編集時の初期値復元に使う。
	const findExistingMark = ( val ) => {
		const reps = ( val && val.replacements ) || [];
		for ( let i = 0; i < reps.length; i++ ) {
			const r = reps[ i ];
			const f = Array.isArray( r )
				? r.find( ( x ) => x && x.type === FORMAT_NAME )
				: ( r && r.type === FORMAT_NAME ? r : null );
			if ( f ) return f;
		}
		return null;
	};

	// style 文字列（--bcp-mark-color:…）から色トークンを取り出す。
	const colorFromStyle = ( style ) => {
		const m = ( style || '' ).match( /--bcp-mark-color:\s*([^;]+)/ );
		return m ? m[ 1 ].trim() : '';
	};

	const CellMarkEdit = ( { value, onChange } ) => {
		const [ isOpen, setIsOpen ] = useState( false );
		const [ selected, setSelected ] = useState( null );
		const [ asBg, setAsBg ] = useState( false );
		const [ size, setSize ] = useState( 'l' );
		const [ color, setColor ] = useState( '' ); // '' = 記号ごとの標準色

		// Ollie / テーマパレット（theme.json の color.palette）。トークンで保存し配色変更に追従させる。
		const palette = ( typeof useSetting === 'function' && useSetting( 'color.palette' ) ) || [];

		// パネルを開くときの状態セット直後に、ライブ同期 effect を 1 回だけ空振りさせるフラグ。
		const skipSync = useRef( false );

		// パネルを開くとき、セル内に既存の記号があればその設定を復元（無ければ初期化）。
		const openPanel = () => {
			const f = findExistingMark( value );
			skipSync.current = true; // 復元/初期化が即 onChange を呼ばないように
			if ( f && f.attributes ) {
				const at = f.attributes;
				setSelected( at[ 'data-bcp-mark' ] || null );
				setAsBg( at[ 'data-bcp-mark-bg' ] === '1' );
				setSize( at[ 'data-bcp-mark-size' ] || 'l' );
				setColor( colorFromStyle( at.style ) );
			} else {
				setSelected( null );
				setAsBg( false );
				setSize( 'l' );
				setColor( '' );
			}
			setIsOpen( true );
		};

		// セル内の既存セル記号オブジェクトをすべて取り除いた value を返す。
		const stripMarks = ( val ) => {
			let v = val;
			const reps = v.replacements || [];
			const idx = [];
			reps.forEach( ( r, i ) => {
				const type = Array.isArray( r ) ? ( r[ 0 ] && r[ 0 ].type ) : ( r && r.type );
				if ( type === FORMAT_NAME ) idx.push( i );
			} );
			idx.sort( ( a, b ) => b - a ).forEach( ( i ) => { v = remove( v, i, i + 1 ); } );
			return v;
		};

		// 現在の選択内容から保存属性を組み立てる。
		const buildAttributes = () => {
			const attributes = { 'data-bcp-mark': selected };
			if ( asBg ) {
				attributes[ 'data-bcp-mark-bg' ] = '1';
				attributes[ 'data-bcp-mark-size' ] = size;
			}
			if ( color ) {
				attributes.style = '--bcp-mark-color:' + color;
			}
			return attributes;
		};

		// 既存マークの「属性だけ」差し替える（テキスト長・選択を変えないので Popover が閉じない）。
		// セル内にまだマークが無ければ false を返す。
		const updateMarkInPlace = ( attributes ) => {
			const reps = ( value.replacements || [] ).slice();
			for ( let i = 0; i < reps.length; i++ ) {
				const r = reps[ i ];
				if ( r && r.type === FORMAT_NAME ) {
					reps[ i ] = { ...r, attributes };
					onChange( { ...value, replacements: reps } );
					return true;
				}
				if ( Array.isArray( r ) ) {
					const j = r.findIndex( ( x ) => x && x.type === FORMAT_NAME );
					if ( j >= 0 ) {
						const nr = r.slice();
						nr[ j ] = { ...r[ j ], attributes };
						reps[ i ] = nr;
						onChange( { ...value, replacements: reps } );
						return true;
					}
				}
			}
			return false;
		};

		// ライブ同期: 形・色・背景・サイズが変わるたびにブロック側へ即反映。
		useEffect( () => {
			if ( ! isOpen ) return;
			if ( skipSync.current ) { skipSync.current = false; return; }
			if ( ! selected ) return;
			const attributes = buildAttributes();
			// 既存マークがあれば属性差し替え、無ければキャレット位置に新規挿入（選択は置換しない）。
			if ( ! updateMarkInPlace( attributes ) ) {
				const v = stripMarks( value );
				onChange( insertObject( v, { type: FORMAT_NAME, attributes }, v.end, v.end ) );
			}
		}, [ selected, asBg, size, color, isOpen ] );

		// 「適用」はライブ反映済みなので閉じるだけ。
		const apply = () => setIsOpen( false );

		const clear = () => {
			onChange( stripMarks( value ) );
			setSelected( null );
			setIsOpen( false );
		};

		// ColorPalette は親ドキュメント側に描画され、そこには --wp--preset--color--* が
		// 無いことがあるため、選択中スウォッチの判定用に解決済み hex（palette の color）を用意する。
		const previewColor = ( () => {
			if ( ! color ) return '';
			const m = color.match( /--wp--preset--color--([a-zA-Z0-9-]+)\)/ );
			if ( m ) {
				const p = palette.find( ( c ) => c.slug === m[ 1 ] );
				return p ? p.color : color;
			}
			return color;
		} )();

		return el(
			Fragment,
			{},
			el( RichTextToolbarButton, {
				icon: 'star-filled',
				title: __( 'セル記号', 'beauty-clinic-patterns' ),
				onClick: () => ( isOpen ? setIsOpen( false ) : openPanel() ),
				isActive: isOpen || !! findExistingMark( value ),
			} ),
			isOpen && el(
				Popover,
				{ position: 'bottom center', onClose: () => setIsOpen( false ), focusOnMount: true },
				el(
					'div',
					{ style: { padding: '16px', width: '300px' } },
					// 記号（クリックしても閉じない＝選び直し自由）。プレビューはエディタ側でリアルタイム反映。
					el(
						'div',
						{ style: { display: 'flex', flexWrap: 'wrap', gap: '2px', marginBottom: '8px' } },
						MARKS.map( ( m ) => el( Button, {
							key: m.key,
							label: m.label,
							showTooltip: true,
							onClick: () => setSelected( m.key ),
							style: { fontSize: '20px', minWidth: '30px', justifyContent: 'center', color: m.color, boxShadow: selected === m.key ? 'inset 0 0 0 2px #1e1e1e' : 'none', borderRadius: '4px' },
						}, m.glyph ) )
					),
					// 背景表示＋サイズ（記号グリッドの直後・色の前）
					el(
						'div',
						{ style: { marginBottom: '12px' } },
						el( ToggleControl, {
							label: __( '背景に大きく表示する', 'beauty-clinic-patterns' ),
							checked: asBg,
							onChange: setAsBg,
							__nextHasNoMarginBottom: true,
						} ),
						asBg && el(
							'div',
							{ style: { marginTop: '8px' } },
							ToggleGroupControl
								? el( ToggleGroupControl, {
									label: __( '記号サイズ', 'beauty-clinic-patterns' ),
									value: size,
									isBlock: true,
									onChange: ( v ) => setSize( v ),
									__nextHasNoMarginBottom: true,
									__next40pxDefaultSize: true,
								}, SIZES.map( ( s ) => el( ToggleGroupControlOption, { key: s.key, value: s.key, label: s.label } ) ) )
								: el( ButtonGroup, {}, SIZES.map( ( s ) => el( Button, {
									key: s.key,
									isPrimary: size === s.key,
									isSmall: true,
									onClick: () => setSize( s.key ),
								}, s.label ) ) )
						)
					),
					// 色: WordPress 標準の ColorPalette。クリア（未選択）= 記号ごとの標準色。
					// 保存はトークン化（パレット該当色は var(--wp--preset--color--slug)、カスタム色は hex）。
					el(
						'div',
						{ style: { marginBottom: '12px' } },
						el( 'div', { style: { fontSize: '11px', textTransform: 'uppercase', fontWeight: 500, color: '#757575', marginBottom: '8px' } }, __( '色', 'beauty-clinic-patterns' ) ),
						el( ColorPalette, {
							colors: palette,
							value: previewColor || undefined,
							onChange: ( c ) => {
								if ( ! c ) { setColor( '' ); return; }
								const hit = palette.find( ( p ) => p.color && p.color.toLowerCase() === c.toLowerCase() );
								setColor( hit ? 'var(--wp--preset--color--' + hit.slug + ')' : c );
							},
							clearable: true,
							enableAlpha: false,
							__experimentalIsRenderedInSidebar: false,
						} ),
						el( 'div', { style: { fontSize: '11px', color: '#757575', marginTop: '4px' } }, __( 'クリアで「記号ごとの標準色」に戻ります。', 'beauty-clinic-patterns' ) )
					),
					// 確定 / 解除
					el(
						'div',
						{ style: { display: 'flex', gap: '6px', marginTop: '10px' } },
						el( Button, { variant: 'primary', onClick: apply }, __( '閉じる', 'beauty-clinic-patterns' ) ),
						el( Button, { variant: 'tertiary', onClick: clear }, __( '解除', 'beauty-clinic-patterns' ) )
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
			style: 'style', // 色トークン（--bcp-mark-color:var(--wp--preset--color--…)）保持用
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
			// 選択範囲を置換せず（v.end, v.end）キャレット位置に挿入。
			onChange( insertObject( v, { type: STARS_FORMAT, attributes: { 'data-bcp-stars': String( n ) } }, v.end, v.end ) );
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
