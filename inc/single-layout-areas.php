<?php
/**
 * 投稿レイアウトデザイナー — テンプレートパーツエリア登録。
 *
 * single.html をセクション単位のテンプレートパーツ（ヒーロー / 記事フッター /
 * 関連記事）に分割し、サイトエディタの「デザイン(置換)」UI でパターン一覧から
 * 選べるようにする。パターン側に `Block Types: core/template-part/{area}` を
 * 付けると、その area のパーツ編集時にライブプレビュー付き一覧が出る。
 *
 * inc/header-designs.php（header area + patterns/header-*.php）の水平展開。
 * このファイルは area を登録するだけで、CSS は pattern-styles.php / block-styles.php
 * の規約ベース配信に任せる。
 *
 * @package vip2026
 */

namespace VIP2026\SingleLayoutAreas;

/**
 * 投稿レイアウト用の独自テンプレートパーツエリアを登録する。
 *
 * area スラッグは theme.json の templateParts と parts/*.html、
 * および single*.html の wp:template-part 参照と一致させること。
 *
 * @param array<int, array<string, mixed>> $areas 既定エリア一覧。
 * @return array<int, array<string, mixed>>
 */
function register_areas( array $areas ): array {
	$areas[] = array(
		'area'        => 'single-hero',
		'label'       => __( '投稿ヒーロー', 'vip2026' ),
		'description' => __( '投稿ページ冒頭のヒーローセクション。カバー / 中央 / 分割などパターンで差し替え。', 'vip2026' ),
		'icon'        => 'cover',
		'area_tag'    => 'div',
	);

	$areas[] = array(
		'area'        => 'post-footer',
		'label'       => __( '記事フッター', 'vip2026' ),
		'description' => __( '本文末尾の著者ボックス・タグ・前後記事ナビなど。', 'vip2026' ),
		'icon'        => 'layout',
		'area_tag'    => 'div',
	);

	$areas[] = array(
		'area'        => 'related-posts',
		'label'       => __( '関連記事', 'vip2026' ),
		'description' => __( '記事下の関連記事セクション。', 'vip2026' ),
		'icon'        => 'grid',
		'area_tag'    => 'div',
	);

	return $areas;
}
add_filter( 'default_wp_template_part_areas', __NAMESPACE__ . '\register_areas' );

/**
 * ヒーローパターン用のブロックパターンカテゴリを登録する。
 *
 * patterns/single-hero-*.php の `Categories: vip2026-single-hero` に対応。
 * サイトエディタのパターンインサーターでヒーロー9種をまとめて表示する。
 */
function register_pattern_categories(): void {
	register_block_pattern_category(
		'vip2026-single-hero',
		array( 'label' => __( '投稿ヒーロー', 'vip2026' ) )
	);
}
add_action( 'init', __NAMESPACE__ . '\register_pattern_categories' );
