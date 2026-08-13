<?php
/**
 * one est 専用カスタマイズ（スターター）。
 *
 * 有効化方法（どちらか）:
 *   - wp-config.php に `define( 'VIP2026_SITE', 'one-est' );` を追加
 *   - オプション `vip2026_site_slug` に 'one-est' を設定
 *
 * inc/sites/jadeclinic.php を参考に、サイト固有の SEO / トラッキング /
 * 構造化データをここへ集約する。院情報（名称 / 住所 / 診療時間）は
 * BCP プラグインの設定（bcp_clinic）を正とする。
 *
 * @package vip2026
 */

namespace VIP2026\OneEst;

defined( 'ABSPATH' ) || exit;

// Adobe Fonts (Typekit) を使う場合は Kit ID を返す（未使用ならこのまま）。
// add_filter( 'vip2026/typekit_kit', static fn (): string => 'xxxxxxx' );

/**
 * 地図 iframe の title（inc/accessibility.php がこのフィルタで文言を受け取る）。
 */
add_filter( 'vip2026/a11y/map_iframe_title', static function (): string {
	return '地図: one est へのアクセス';
} );

// TODO: Facebook ドメイン認証 meta（Business Manager でコード取得後に有効化）。
// TODO: MedicalClinic JSON-LD（クリニック情報確定後、inc/sites/jadeclinic.php の
//       structured_data() を移植して書き換える。将来的には BCP 側での自動出力に移行予定）。
