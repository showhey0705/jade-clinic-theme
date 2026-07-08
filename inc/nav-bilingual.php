<?php
/**
 * バイリンガルナビゲーション(EN ラベル + 日本語サブラベル)。
 *
 * core/navigation-link・core/navigation-submenu が標準で持つ「説明 (description)」
 * 属性をサブラベルとして描画する。コアはこの属性を保存するだけでフロントに
 * 出力しないため、render_block フィルタで <a> 内へ <span> を 1 つ注入する。
 *
 * 設計方針(パフォーマンス):
 * - フロント JS ゼロ / エディタ拡張 JS ゼロ。編集者は Navigation ブロック標準 UI の
 *   「説明」欄に日本語を入れるだけ(リンク設定ポップオーバー → 説明)。
 * - 描画は純粋なサーバーサイド文字列操作 1 回のみ。ページキャッシュにそのまま乗る。
 * - description が空の項目は 1 バイトも変化しない(既存メニューへの影響ゼロ)。
 * - スタイルは style.css(既存 enqueue)に同梱。追加 HTTP リクエスト 0。
 *
 * @package vip2026
 */

namespace VIP2026\Nav_Bilingual;

defined( 'ABSPATH' ) || exit;

/**
 * description 属性を <a> 内のサブラベル <span> として注入する。
 *
 * 対象マークアップ(コア出力):
 *   <li><a class="wp-block-navigation-item__content">
 *     <span class="wp-block-navigation-item__label">News</span>
 *   </a>…</li>
 *
 * 最初の `</a>` 直前に挿入するため、submenu の <button> やドロップダウン内の
 * 子リンク(それぞれ独自に本フィルタを通る)には影響しない。
 *
 * lang="ja" を明示するのは、翻訳ツール・スクリーンリーダー・検索エンジンに
 * 「この部分だけ日本語」と正しく伝えるため(サイト既定 lang が ja でも、
 * ナビ全体を英語と誤判定されるのを防ぐ)。
 *
 * @param string $content ブロックの描画済み HTML
 * @param array  $block   パースされたブロック(attrs 含む)
 * @return string 加工後 HTML
 */
function inject_sublabel( string $content, array $block ): string {
	$sub = isset( $block['attrs']['description'] ) ? trim( (string) $block['attrs']['description'] ) : '';
	if ( '' === $sub ) {
		return $content;
	}

	$pos = strpos( $content, '</a>' );
	if ( false === $pos ) {
		return $content;
	}

	$span = '<span class="nav-sublabel" lang="ja">' . esc_html( $sub ) . '</span>';

	return substr( $content, 0, $pos ) . $span . substr( $content, $pos );
}
add_filter( 'render_block_core/navigation-link', __NAMESPACE__ . '\inject_sublabel', 10, 2 );
add_filter( 'render_block_core/navigation-submenu', __NAMESPACE__ . '\inject_sublabel', 10, 2 );
