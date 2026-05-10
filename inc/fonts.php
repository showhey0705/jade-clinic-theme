<?php
/**
 * 書体（フォント）の遅延 enqueue。
 *
 * theme.json の fontFamilies に slug だけ登録された書体について、
 * has-{slug}-font-family クラスを含むブロックがフロント描画された時のみ
 * @font-face / Google Fonts CSS を読み込む。エディタはピッカー UI と
 * iframe キャンバス両方のため常時読み込む。
 *
 * @package vip2026
 */

namespace VIP2026\Fonts;

/**
 * 登録対象の書体一覧。
 *
 * - slug は theme.json の fontFamilies[].slug と一致させる
 * - src はテーマ内 CSS の URL、または Google Fonts 等の絶対 URL
 * - ローカル CSS の場合は filemtime をバージョンにする（キャッシュバスター）
 *
 * @return array<int, array{slug:string, handle:string, src:string, ver:int|null}>
 */
function registry(): array {
	static $cache = null;
	if ( $cache !== null ) {
		return $cache;
	}

	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	$cache = array(
		array(
			'slug'   => 'geist-pixel-circle',
			'handle' => 'vip2026-font-geist-pixel-circle',
			'src'    => $uri . '/assets/styles/font-geist-pixel-circle.css',
			'ver'    => filemtime( $dir . '/assets/styles/font-geist-pixel-circle.css' ),
		),
		array(
			'slug'   => 'dot-gothic-16',
			'handle' => 'vip2026-font-dot-gothic-16',
			// Google Fonts CSS（unicode-range chunk 配信）。
			'src'    => 'https://fonts.googleapis.com/css2?family=DotGothic16&display=swap',
			'ver'    => null,
		),
	);

	return $cache;
}

/**
 * フロント：has-{slug}-font-family を含むブロック描画時のみ遅延 enqueue。
 *
 * static $loaded で 1 ページ 1 度だけに抑える。
 */
function maybe_enqueue( string $content ): string {
	static $loaded = array();

	foreach ( registry() as $info ) {
		if ( isset( $loaded[ $info['handle'] ] ) ) {
			continue;
		}
		if ( str_contains( $content, 'has-' . $info['slug'] . '-font-family' ) ) {
			wp_enqueue_style( $info['handle'], $info['src'], array(), $info['ver'] );
			$loaded[ $info['handle'] ] = true;
		}
	}

	return $content;
}
add_filter( 'render_block', __NAMESPACE__ . '\maybe_enqueue', 10, 1 );

/**
 * エディタ：管理画面親ドキュメント + iframe キャンバスの両方に届ける。
 *
 * `enqueue_block_assets` は WP 6.3+ で iframe 化されたキャンバス内でも発火する。
 * is_admin() でフロントを除外し、フロントは render_block の遅延 enqueue に任せる。
 */
function enqueue_in_editor(): void {
	if ( ! is_admin() ) {
		return;
	}
	foreach ( registry() as $info ) {
		wp_enqueue_style( $info['handle'], $info['src'], array(), $info['ver'] );
	}
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_in_editor' );
