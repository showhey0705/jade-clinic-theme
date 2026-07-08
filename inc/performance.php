<?php
/**
 * パフォーマンス最適化。
 *
 * Ollie 親テーマがフロント表示でも誘発してしまう「全テーマパターンの遅延
 * ハイドレーション」を、パターンを実際に参照する文脈(エディタ / REST)に
 * 限定する。
 *
 * @package vip2026
 */

namespace VIP2026\Performance;

/**
 * 現在のリクエストがブロックパターンを必要とするコンテキストか判定する。
 *
 * register_block_pattern() で登録したパターンを参照するのはブロックエディタと
 * REST `wp/v2/block-patterns` エンドポイントのみで、フロントのページ表示では
 * 一切参照されない。
 *
 * 注: `init` フック時点では REST_REQUEST 定数が未定義のため、REST リクエストは
 * REQUEST_URI が REST プレフィックスを含むかどうかで判定する。
 */
function is_pattern_request(): bool {
	if ( is_admin() || wp_doing_ajax() ) {
		return true;
	}
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return true;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}

	// init 時点では REST_REQUEST が未定義。REQUEST_URI で REST エンドポイントを判定する。
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	if ( '' !== $uri ) {
		$prefix = trim( rest_get_url_prefix(), '/' );
		if ( false !== strpos( $uri, '/' . $prefix . '/' )
			|| false !== strpos( $uri, 'rest_route=' ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Ollie 親テーマの `unregister_ollie_woocommerce_patterns()` をフロントで無効化する。
 *
 * Ollie は WooCommerce 無効時、`ollie/woo-*` パターンをエディタのインサーターから
 * 隠すために同関数を `init:999` へ登録する。だが関数内の
 * `WP_Block_Patterns_Registry::get_all_registered()` の初回呼び出しが、登録済み
 * 全テーマパターン(約 250 件)の遅延ハイドレーション(各パターン PHP の require)を
 * 誘発する。フロントのページ表示ではパターンは参照されないため、これは純粋な無駄で、
 * 実測で約 0.3〜0.4 秒/リクエストのオーバーヘッドになる。
 *
 * エディタ / REST / WP-CLI 文脈では Ollie 本来の掃除をそのまま動かし、フロント表示
 * でのみ callback を外す。WooCommerce が有効化されると Ollie はこの callback を
 * そもそも登録しないため、`remove_action` は安全な no-op になる。
 *
 * init:1 で動かすことで、Ollie の init:999 が実行される前に確実に外す。
 */
function skip_ollie_woo_pattern_cleanup_on_front(): void {
	// エディタ等パターンを参照する文脈では Ollie の掃除を維持する。
	if ( is_pattern_request() ) {
		return;
	}

	remove_action( 'init', 'Ollie\\unregister_ollie_woocommerce_patterns', 999 );
}
add_action( 'init', __NAMESPACE__ . '\skip_ollie_woo_pattern_cleanup_on_front', 1 );

// 動画の遅延読み込みは beauty-clinic-patterns の Video_Lazyload 機能へ一本化した
// (inc/features/video-lazyload/)。子テーマ側の実装は二重処理を避けるため撤去済み。

/**
 * ナビゲーションリンクの先読み (Speculation Rules) を強化する。
 *
 * BCP の View_Transitions モジュールは全同一オリジンリンクに eagerness=conservative
 * (pointerdown 時に先読み開始) の prerender ルールを出力する。ここに「ナビゲーション
 * ブロック内のリンクだけ eagerness=moderate」のルールを追加する。moderate はホバー
 * (約 200ms) の時点でプリレンダリングを開始するため、ナビ経由のページ遷移は
 * クリック時点でほぼ描画済み = 体感ゼロ ms になる。
 *
 * - ナビは行き先が少数 (ヘッダー 6 リンク程度) で誤爆コストが低く、ホバー = 遷移意図の
 *   確度が高い。全リンク moderate にするより「ナビだけ moderate」が転送量と体感の
 *   バランス最良。
 * - Chrome は moderate の prerender を同時 2 件・LRU で管理するため暴走しない。
 * - BCP 無効時はフィルタ自体が発火しない安全な no-op。
 * - 除外クラス (.no-vt / .no-prefetch / [data-no-vt]) は BCP 本体ルールと揃える。
 *
 * @param array  $spec BCP が生成した speculation rules
 * @param string $mode BCP 設定の preload_mode
 * @return array 拡張後の speculation rules
 */
function boost_nav_speculation( array $spec, string $mode ): array {
	// ログイン中はナビ moderate ルールを追加しない。
	// ログインユーザーへの応答はページキャッシュを素通りする生 PHP レンダリングのため、
	// ホバーのたびに prerender が走ると FPM ワーカーを食い潰し、本命の遷移リクエストが
	// 待たされる(ローカル DevKinsta で実測: ナビ遷移 TTFB が 2s → 48s に悪化)。
	// 非ログイン訪問者はキャッシュ配信なので prerender コストは実質ゼロ。
	if ( is_user_logged_in() ) {
		return $spec;
	}

	$nav_rule = array(
		'where'     => array(
			'and' => array(
				array( 'selector_matches' => '.wp-block-navigation a[href]' ),
				array( 'not' => array( 'href_matches' => '/wp-admin/*' ) ),
				array( 'not' => array( 'href_matches' => '/wp-login.php' ) ),
				array( 'not' => array( 'selector_matches' => '[rel~="nofollow"]' ) ),
				array( 'not' => array( 'selector_matches' => '.no-vt, .no-prefetch, [data-no-vt]' ) ),
			),
		),
		'eagerness' => 'moderate',
	);

	// BCP 側の設定 (preload_html) に合わせ、prerender 構成なら prerender へ、
	// prefetch 構成なら prefetch へ追加する。
	$key            = isset( $spec['prerender'] ) ? 'prerender' : 'prefetch';
	$spec[ $key ][] = $nav_rule;

	return $spec;
}
add_filter( 'bcp_vt_speculation_rules', __NAMESPACE__ . '\boost_nav_speculation', 10, 2 );
