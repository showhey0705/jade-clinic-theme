<?php
/**
 * Ollie Pro Responsive Controls を「Typography → カラム (column-count)」へ拡張。
 *
 * Ollie Pro 本体（inc/extensions/loader/responsive-controls/）は fontSize / padding /
 * margin / blockGap / minHeight / textAlign / justifyContent / orientation の 8 種を
 * Tablet/Mobile 別に上書きできるが、`textColumns` (CSS column-count) には未対応。
 * 本ファイルは Ollie Pro と同じ規約（`ollieResponsive` 属性 + CSS 変数 + マーカークラス
 * + 共通ブレイクポイント 768/480px）で column-count を片肺サポートする。
 *
 * Ollie Pro が無効/未導入のときは全フックを登録せず no-op。
 *
 * @package vip2026
 */

namespace VIP2026\ResponsiveColumns;

defined( 'ABSPATH' ) || exit;

/**
 * Ollie Pro が有効でない環境では何もしない。
 */
function is_active(): bool {
	return function_exists( 'ollie_responsive_get_target_blocks' );
}

if ( ! is_active() ) {
	return;
}

/**
 * エディタ用 JS（UI コントロール + プレビュー注入）。
 */
function enqueue_editor_assets(): void {
	$rel = '/assets/js/responsive-columns.js';
	$abs = get_stylesheet_directory() . $rel;
	if ( ! file_exists( $abs ) ) {
		return;
	}
	wp_enqueue_script(
		'vip2026-responsive-columns',
		get_stylesheet_directory_uri() . $rel,
		array( 'wp-hooks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-blocks', 'wp-i18n', 'wp-compose' ),
		(string) filemtime( $abs ),
		true
	);

	// Ollie Pro の SUPPORTED_BLOCKS を JS 側に渡す（PHP の filter 結果と同期）。
	wp_add_inline_script(
		'vip2026-responsive-columns',
		'window.vip2026ResponsiveColumns = ' . wp_json_encode( array(
			'targetBlocks' => array_values( \ollie_responsive_get_target_blocks() ),
		) ) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_assets' );

/**
 * フロント＆エディタ両方に当てるメディアクエリ CSS。
 */
function enqueue_block_assets(): void {
	$rel = '/assets/styles/responsive-columns.css';
	$abs = get_stylesheet_directory() . $rel;
	if ( ! file_exists( $abs ) ) {
		return;
	}
	wp_enqueue_style(
		'vip2026-responsive-columns',
		get_stylesheet_directory_uri() . $rel,
		array(),
		(string) filemtime( $abs )
	);
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_block_assets' );

/**
 * カラム数の妥当性チェック。空文字 / 非数値 / 0 以下は null を返す。
 *
 * @param mixed $raw
 * @return int|null
 */
function sanitize_count( $raw ): ?int {
	if ( ! is_scalar( $raw ) || '' === $raw ) {
		return null;
	}
	if ( ! is_numeric( $raw ) ) {
		return null;
	}
	$n = (int) $raw;
	return $n > 0 ? $n : null;
}

/**
 * render_block: Ollie Pro renderer (priority 10) の後に走り、columnCount 用の
 * CSS カスタムプロパティとマーカークラスをルートタグへ追加する。
 *
 * @param string $block_content
 * @param array  $block
 * @return string
 */
function render_block( $block_content, $block ): string {
	if ( empty( $block['blockName'] ) || ! in_array( $block['blockName'], \ollie_responsive_get_target_blocks(), true ) ) {
		return $block_content;
	}

	$responsive = $block['attrs']['ollieResponsive']['columnCount'] ?? null;
	if ( ! is_array( $responsive ) ) {
		return $block_content;
	}

	$tablet = sanitize_count( $responsive['tablet'] ?? null );
	// Mobile 未設定なら Tablet 値をカスケード。
	$mobile = sanitize_count( $responsive['mobile'] ?? null );
	if ( null === $mobile && null !== $tablet ) {
		$mobile = $tablet;
	}

	if ( null === $tablet && null === $mobile ) {
		return $block_content;
	}

	$processor = new \WP_HTML_Tag_Processor( $block_content );
	if ( ! $processor->next_tag() ) {
		return $block_content;
	}

	$style_additions = '';
	if ( null !== $tablet ) {
		$style_additions .= '--ollie-columns-tablet:' . $tablet . ';';
		$processor->add_class( 'has-ollie-columns-tablet' );
	}
	if ( null !== $mobile ) {
		$style_additions .= '--ollie-columns-mobile:' . $mobile . ';';
		$processor->add_class( 'has-ollie-columns-mobile' );
	}

	$existing = $processor->get_attribute( 'style' ) ?? '';
	$processor->set_attribute( 'style', $style_additions . $existing );

	return $processor->get_updated_html();
}
add_filter( 'render_block', __NAMESPACE__ . '\render_block', 11, 2 );
