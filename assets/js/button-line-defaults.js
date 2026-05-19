/**
 * LINE 緑 ボタンスタイル variation のデフォルト属性自動セット。
 *
 * エディタで「LINE 緑」(is-style-button-line) スタイルが選択された core/button に対し、
 * Ollie Pro の Button Icon 属性 (customIconSvg + iconPositionLeft) を自動でセットする。
 *
 * - 既に customIconSvg または icon が手動設定されている場合は尊重して上書きしない
 * - エディタ側で属性を持たせる = フロントでも Ollie Pro が同じ icon を描画 = 表示の一貫性
 * - is-style-button-line を外したら属性は残存 (UX 上、ユーザーが明示的にクリアできる)
 */
( function () {
	'use strict';

	if ( ! window.wp || ! window.wp.hooks || ! window.wp.compose || ! window.wp.element ) {
		return;
	}

	var addFilter = window.wp.hooks.addFilter;
	var createHigherOrderComponent = window.wp.compose.createHigherOrderComponent;
	var useEffect = window.wp.element.useEffect;
	var createElement = window.wp.element.createElement;

	var LINE_ICON_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" width="20" height="20" fill="currentColor" aria-hidden="true" focusable="false">'
		+ '<path d="M18 3C9.16 3 2 8.85 2 16.06c0 6.46 5.64 11.87 13.27 12.91.52.11 1.22.34 1.4.79.16.4.1 1.03.05 1.44l-.23 1.36c-.07.4-.32 1.58 1.39.86 1.71-.72 9.22-5.43 12.58-9.29C32.78 21.49 34 18.93 34 16.06 34 8.85 26.84 3 18 3zm-6.51 16.92h-3.18a.84.84 0 0 1-.84-.84v-6.36c0-.46.38-.84.84-.84.47 0 .85.38.85.84v5.52h2.33c.47 0 .85.38.85.84a.85.85 0 0 1-.85.84zm3.31-.84a.85.85 0 0 1-.85.84.84.84 0 0 1-.84-.84v-6.36c0-.46.38-.84.84-.84.47 0 .85.38.85.84v6.36zm7.66 0a.85.85 0 0 1-.58.8.84.84 0 0 1-.27.05.83.83 0 0 1-.68-.34l-3.26-4.44v3.93a.85.85 0 0 1-.85.84.84.84 0 0 1-.84-.84v-6.36c0-.36.23-.68.58-.79a.71.71 0 0 1 .26-.05c.26 0 .51.13.67.34l3.27 4.44v-3.94c0-.46.37-.84.84-.84.46 0 .85.38.85.84v6.36zm5.15-3.96c.47 0 .85.38.85.85a.85.85 0 0 1-.85.84h-2.33v1.43h2.33c.46 0 .85.38.85.84a.85.85 0 0 1-.85.84h-3.18a.85.85 0 0 1-.84-.84v-6.36c0-.46.38-.84.84-.84h3.18c.47 0 .85.38.85.84a.85.85 0 0 1-.85.85h-2.33v1.43h2.33z"/>'
		+ '</svg>';

	function hasLineStyle( attributes ) {
		var className = ( attributes && attributes.className ) || '';
		return className.split( /\s+/ ).indexOf( 'is-style-button-line' ) !== -1;
	}

	var withButtonLineDefaults = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			if ( props.name !== 'core/button' ) {
				return createElement( BlockEdit, props );
			}

			var attributes = props.attributes || {};
			var setAttributes = props.setAttributes;
			var isLine = hasLineStyle( attributes );

			useEffect( function () {
				if ( ! isLine ) return;
				// 既に手動で icon / customIconSvg がセットされていれば尊重
				if ( attributes.customIconSvg || attributes.icon ) return;
				setAttributes( {
					customIconSvg: LINE_ICON_SVG,
					iconPositionLeft: true,
				} );
			}, [ isLine ] );

			return createElement( BlockEdit, props );
		};
	}, 'vip2026WithButtonLineDefaults' );

	addFilter(
		'editor.BlockEdit',
		'vip2026/button-line-defaults',
		withButtonLineDefaults
	);
} )();
