<?php
/**
 * アクセシビリティ補助（Lighthouse「ユーザー補助」対応）。
 *
 * 描画時（render_block）に、機械的に補える a11y 欠落だけを補完する。見た目はゼロ変化。
 *
 * - ② タイトル未指定の <iframe>（Google マップ埋め込み等）に title 属性を付与
 * - ③ 画像のみのリンク（<a><img alt=""></a>）に、リンク先の記事タイトル等から
 *      aria-label を補い、スクリーンリーダーで識別可能な名前を持たせる
 *
 * @package vip2026
 */

namespace VIP2026\A11y;

defined( 'ABSPATH' ) || exit;

/**
 * リンク先 URL からアクセシブルなラベル（記事タイトル）を解決する。
 *
 * 同一 URL はリクエスト内で 1 回だけ解決してメモ化する。トップ等では同じ遷移先への
 * 画像リンクが重複するため、url_to_postid() の DB クエリを URL の実数に抑える。
 *
 * @param string $url リンク先 URL
 * @return string aria-label に使うラベル
 */
function resolve_link_label( string $url ): string {
	static $cache = array();
	if ( isset( $cache[ $url ] ) ) {
		return $cache[ $url ];
	}
	$label = '';
	$pid   = url_to_postid( $url );
	if ( $pid ) {
		$label = (string) get_the_title( $pid );
	}
	if ( '' === $label ) {
		$label = '詳細を見る';
	}
	$cache[ $url ] = $label;
	return $label;
}

/**
 * ブロック描画 HTML に a11y 補完を施す。
 *
 * @param string               $content ブロックの描画済み HTML
 * @param array<string, mixed> $block   ブロック配列
 * @return string
 */
function fix_block( string $content, array $block ): string {
	if ( '' === $content || false === strpos( $content, '<' ) ) {
		return $content;
	}

	// ② title 未指定の iframe に title を付与。
	if ( false !== stripos( $content, '<iframe' ) ) {
		$content = (string) preg_replace_callback(
			'/<iframe\b[^>]*>/i',
			static function ( array $m ): string {
				$tag = $m[0];
				if ( preg_match( '/\btitle\s*=/i', $tag ) ) {
					return $tag; // 既に title があるので触らない。
				}
				$label = preg_match( '/google\.com\/maps|maps\.google|goo\.gl\/maps/i', $tag )
					? '地図: JADE CLINIC へのアクセス'
					: '埋め込みコンテンツ';
				return preg_replace( '/<iframe\b/i', '<iframe title="' . esc_attr( $label ) . '"', $tag, 1 );
			},
			$content
		);
	}

	// ③ 画像のみのリンクに aria-label を補う（alt 空・リンク名なしのときだけ）。
	if ( false !== stripos( $content, '<a ' ) && false !== stripos( $content, '<img' ) ) {
		$content = (string) preg_replace_callback(
			'/<a\b([^>]*)>(\s*<img\b[^>]*>\s*)<\/a>/i',
			static function ( array $m ): string {
				$attrs = $m[1];
				$inner = $m[2];
				// リンク側に既に名前があるなら触らない。
				if ( preg_match( '/\baria-label\s*=|\btitle\s*=/i', $attrs ) ) {
					return $m[0];
				}
				// img に非空の alt があれば、その alt がリンク名になるので触らない。
				if ( preg_match( '/\balt\s*=\s*(["\'])(?!\1)[^"\']*\1/i', $inner ) ) {
					return $m[0];
				}
				// href からリンク先タイトルを解決してラベルにする（重複 URL はメモ化で 1 回だけ解決）。
				$label = '';
				if ( preg_match( '/\bhref\s*=\s*"([^"]+)"/i', $attrs, $h ) ) {
					$label = resolve_link_label( $h[1] );
				} else {
					$label = '詳細を見る';
				}
				return '<a' . $attrs . ' aria-label="' . esc_attr( $label ) . '">' . $inner . '</a>';
			},
			$content
		);
	}

	return $content;
}
add_filter( 'render_block', __NAMESPACE__ . '\fix_block', 12, 2 );
