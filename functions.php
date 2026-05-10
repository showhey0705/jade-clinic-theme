<?php
/**
 * VIP2026 — Ollie 子テーマ
 *
 * @package vip2026
 */

namespace VIP2026;

const VERSION       = '0.2.0';
const TYPEKIT_KIT   = 'bzy5pnl';
const TYPEKIT_HOSTS = array( 'https://use.typekit.net', 'https://p.typekit.net' );

/**
 * 子テーマ初期設定。
 *
 * - textdomain ロード（i18n）
 * - エディタ用スタイル（front と同じ見た目をエディタにも）
 *   `add_editor_style()` に配列で渡せば一括登録できる。Adobe Fonts も同じ口で OK。
 */
function setup(): void {
	load_child_theme_textdomain( 'vip2026', get_stylesheet_directory() . '/languages' );

	add_editor_style( array(
		'style.css',
		'assets/styles/japanese-typography.css',
		'https://use.typekit.net/' . TYPEKIT_KIT . '.css',
	) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );

/**
 * フロント側スタイル enqueue。
 *
 * - 子テーマ style.css（親テーマ後に読まれるよう priority 20）
 * - 日本語タイポグラフィ補助 CSS
 * - Adobe Fonts（Typekit）
 */
function enqueue_styles(): void {
	wp_enqueue_style(
		'vip2026-style',
		get_stylesheet_uri(),
		array(),
		VERSION
	);

	wp_enqueue_style(
		'vip2026-japanese-typography',
		get_stylesheet_directory_uri() . '/assets/styles/japanese-typography.css',
		array(),
		VERSION
	);

	wp_enqueue_style(
		'vip2026-adobe-fonts',
		'https://use.typekit.net/' . TYPEKIT_KIT . '.css',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles', 20 );

/**
 * Adobe Fonts に preconnect ヒントを追加（CORS 必須なので crossorigin 付き）。
 */
function resource_hints( array $hints, string $relation ): array {
	if ( 'preconnect' !== $relation ) {
		return $hints;
	}

	foreach ( TYPEKIT_HOSTS as $host ) {
		$hints[] = array(
			'href'        => $host,
			'crossorigin' => 'anonymous',
		);
	}

	return $hints;
}
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\resource_hints', 10, 2 );

/**
 * 子テーマ独自のブロックスタイル登録（後続 Phase で実装）。
 */
require_once get_stylesheet_directory() . '/inc/block-styles.php';

/**
 * エディタ UX：タイポ・スペーシング・シャドウ・枠線コントロールを常時表示。
 */
require_once get_stylesheet_directory() . '/inc/editor-controls.php';

/**
 * パターン専用 CSS / JS の規約ベース自動ロード。
 * assets/styles/patterns/{block}--{class}.css をスキャンし、
 * is-style-{class} 出現時のみ条件付き enqueue する。
 */
require_once get_stylesheet_directory() . '/inc/pattern-styles.php';

/**
 * jadeclinic.jp 専用：FB ドメイン認証 / JSON-LD / LP femcare のヘッダフッタ非表示。
 * 別サイト転用時はこの 1 行を消せば全部止まる。
 */
require_once get_stylesheet_directory() . '/inc/jadeclinic.php';
