<?php
/**
 * Palette extras for vip2026.
 *
 * vip2026/theme.json は palette に `line-green` / `line-green-fg` / `sale` / `sale-fg`
 * を含めているが、Ollie Pro は `wp_theme_json_data_theme` filter (priority 10) で
 * `wp_options.ollie['color_palette']` (Site Editor のカスタムパレット保存値) を
 * theme palette に**完全置換**するため、theme.json で増やした slug が消える。
 *
 * このファイルでは Ollie Pro の刈り取り後 (priority 20) に palette を再構築する。
 * Ollie Pro 通過後の `$theme_json->get_data()` は palette entry が破損して 1 件の
 * 空 entry に潰れているケースがあるため、信頼できる入力源として
 * `wp_options.ollie['color_palette']` を直接読み、足りない slug を append する。
 *
 * 役割の異なる palette は子テーマ側で「最小限の追加だけ」する方針:
 * - `line-green` / `line-green-fg`: LINE 公式ブランドカラー
 * - `sale` / `sale-fg`             : 商品販売想定の強調色
 *
 * 別サイト派生時はここを書き換えて自サイト用パレットを足す(vip2026-starter の慣習)。
 *
 * @package vip2026
 */

namespace VIP2026\PaletteExtras;

defined( 'ABSPATH' ) || exit;

/**
 * 不足分の palette だけを Ollie Pro 保存パレットに append する。
 *
 * @param \WP_Theme_JSON_Data $theme_json
 * @return \WP_Theme_JSON_Data
 */
function add_extra_colors( $theme_json ) {
	$extras = [
		[ 'name' => 'LINE Green',            'slug' => 'line-green',    'color' => '#06C755' ],
		[ 'name' => 'LINE Green Foreground', 'slug' => 'line-green-fg', 'color' => '#FFFFFF' ],
		[ 'name' => 'Sale',                  'slug' => 'sale',          'color' => '#E11D48' ],
		[ 'name' => 'Sale Foreground',       'slug' => 'sale-fg',       'color' => '#FFFFFF' ],
	];

	// Ollie Pro が wp_options.ollie['color_palette'] で theme palette を完全置換した後、
	// `$theme_json->get_data()` の戻り値内 palette が破損 entry に潰れているため、
	// option を直接参照して palette を再構築する。
	$ollie = get_option( 'ollie', [] );
	$base  = ( isset( $ollie['color_palette'] ) && is_array( $ollie['color_palette'] ) )
		? $ollie['color_palette']
		: [];

	$existing_slugs = array_column( $base, 'slug' );
	foreach ( $extras as $color ) {
		if ( ! in_array( $color['slug'], $existing_slugs, true ) ) {
			$base[] = $color;
		}
	}

	$data = $theme_json->get_data();
	$data['settings']['color']['palette'] = $base;

	return $theme_json->update_with( $data );
}
add_filter( 'wp_theme_json_data_theme', __NAMESPACE__ . '\add_extra_colors', 20 );
