<?php
/**
 * LINE 緑 ボタンスタイル variation のデフォルト属性自動セット (エディタ側 HOC enqueue)。
 *
 * core/button に「LINE 緑」(is-style-button-line) が選択されたとき、Ollie Pro の
 * Button Icon 属性 (customIconSvg + iconPositionLeft) を自動でセットする HOC を
 * assets/js/button-line-defaults.js で実装。本ファイルではエディタ側にだけ enqueue する。
 *
 * - エディタで属性を attribute に焼くため、フロントでも Ollie Pro が同じ icon を描画
 * - 手動で別 icon を Inspector からセットしている場合は HOC 側で尊重 (上書きしない)
 *
 * @package vip2026
 */

declare( strict_types=1 );

namespace VIP2026\ButtonLineDefaults;

function enqueue_editor_assets(): void {
	$rel = '/assets/js/button-line-defaults.js';
	$abs = get_stylesheet_directory() . $rel;
	if ( ! file_exists( $abs ) ) {
		return;
	}
	wp_enqueue_script(
		'vip2026-button-line-defaults',
		get_stylesheet_directory_uri() . $rel,
		array( 'wp-hooks', 'wp-element', 'wp-compose' ),
		(string) filemtime( $abs ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_assets' );
