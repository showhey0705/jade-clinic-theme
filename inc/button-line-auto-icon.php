<?php
/**
 * LINE 緑 (.is-style-button-line) ボタンに LINE 公式 SVG アイコンを自動付与する。
 *
 * Ollie Pro の Button Icon 拡張 (inc/extensions/loader/button-icons/button-icons.php) は
 * render_block 時に $block['attrs']['customIconSvg'] / ['icon'] を読んで SVG を埋め込む。
 * 本モジュールは render_block_data フィルタで attrs を上書きすることで「LINE 緑」スタイル
 * を選ぶだけでアイコン付きボタンになるようにする。
 *
 * 上書きルール:
 * - core/button かつ className に "is-style-button-line" を含むときだけ作用
 * - 既に Inspector 側で customIconSvg / icon が設定されている場合は尊重して上書きしない
 *
 * @package vip2026
 */

declare( strict_types=1 );

namespace VIP2026\ButtonLineAutoIcon;

const LINE_ICON_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" width="20" height="20" fill="currentColor" aria-hidden="true" focusable="false"><path d="M18 3C9.16 3 2 8.85 2 16.06c0 6.46 5.64 11.87 13.27 12.91.52.11 1.22.34 1.4.79.16.4.1 1.03.05 1.44l-.23 1.36c-.07.4-.32 1.58 1.39.86 1.71-.72 9.22-5.43 12.58-9.29C32.78 21.49 34 18.93 34 16.06 34 8.85 26.84 3 18 3zm-6.51 16.92h-3.18a.84.84 0 0 1-.84-.84v-6.36c0-.46.38-.84.84-.84.47 0 .85.38.85.84v5.52h2.33c.47 0 .85.38.85.84a.85.85 0 0 1-.85.84zm3.31-.84a.85.85 0 0 1-.85.84.84.84 0 0 1-.84-.84v-6.36c0-.46.38-.84.84-.84.47 0 .85.38.85.84v6.36zm7.66 0a.85.85 0 0 1-.58.8.84.84 0 0 1-.27.05.83.83 0 0 1-.68-.34l-3.26-4.44v3.93a.85.85 0 0 1-.85.84.84.84 0 0 1-.84-.84v-6.36c0-.36.23-.68.58-.79a.71.71 0 0 1 .26-.05c.26 0 .51.13.67.34l3.27 4.44v-3.94c0-.46.37-.84.84-.84.46 0 .85.38.85.84v6.36zm5.15-3.96c.47 0 .85.38.85.85a.85.85 0 0 1-.85.84h-2.33v1.43h2.33c.46 0 .85.38.85.84a.85.85 0 0 1-.85.84h-3.18a.85.85 0 0 1-.84-.84v-6.36c0-.46.38-.84.84-.84h3.18c.47 0 .85.38.85.84a.85.85 0 0 1-.85.85h-2.33v1.43h2.33z"/></svg>';

/**
 * "LINE 緑" スタイルのボタンに LINE SVG アイコンを注入する。
 *
 * @param array<string,mixed> $block 解析済みブロックデータ。
 * @return array<string,mixed>
 */
function inject_line_icon( array $block ): array {
	if ( 'core/button' !== ( $block['blockName'] ?? '' ) ) {
		return $block;
	}

	$attrs      = $block['attrs'] ?? array();
	$class_name = (string) ( $attrs['className'] ?? '' );

	if ( false === strpos( $class_name, 'is-style-button-line' ) ) {
		return $block;
	}

	// 手動で何かしらのアイコンが選ばれていれば尊重して上書きしない
	if ( ! empty( $attrs['customIconSvg'] ) || ! empty( $attrs['icon'] ) ) {
		return $block;
	}

	$attrs['customIconSvg'] = LINE_ICON_SVG;
	$block['attrs']         = $attrs;

	return $block;
}
add_filter( 'render_block_data', __NAMESPACE__ . '\inject_line_icon', 5 );
