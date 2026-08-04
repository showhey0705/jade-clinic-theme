<?php
/**
 * View Transitions のテーマ側コンパニオン(BCP 連携版)。
 *
 * ── 役割分担 ────────────────────────────────────────────────────
 * `@view-transition { navigation: auto }` の宣言・アニメーション CSS・
 * Speculation Rules・persistent 要素への view-transition-name 付与は、すべて
 * beauty-clinic-patterns (BCP) の View_Transitions モジュールが担当する。
 * どの要素を persistent にするかの「申告」も functions.php の
 * `bcp_vt_persistent_names` フィルタ(vt_persistent_names)が既に行っている
 * (backdrop-filter 非互換ルールに配慮した申告になっている — functions.php 参照)。
 *
 * このファイルが足すのは photoshopvip2022 の同名ファイルから移植した
 * 「仕上げ」1 点のみ:
 *
 *   persistent 要素はクロスフェードさせない。
 *   名前を振られた要素は old/new の 2 スナップショットが重なって描画されるため、
 *   同じ見た目のヘッダーが遷移中に二重に重なり一瞬ちらつく。animation:none +
 *   mix-blend-mode:normal で「その場に留まるだけ」にする。
 *
 * `bcp_vt_styles` フィルタは BCP が CSS を出力するときにしか発火しないので、
 * BCP 未導入 / 機能 OFF の環境では 1 バイトも出力しない(自動 no-op)。
 *
 * NOTE: photoshopvip2022 版にあった「本文の持ち上げアニメーション
 * (::view-transition-new(root))」は移植しない。root のアニメーションは BCP の
 * ダッシュボード設定(animation_type / direction / duration)が管理しており、
 * テーマ側から上書きすると設定 UI と実挙動が食い違うため。
 *
 * @package vip2026
 */

namespace VIP2026\ViewTransitions;

defined( 'ABSPATH' ) || exit;

/**
 * `bcp_vt_styles` — persistent 要素の old/new スナップショットをアニメーションから外す。
 *
 * BCP が組み立てた CSS の末尾に、申告済みの各 persistent 名に対する
 * animation:none ルールを追記する。names は「セレクタ => 一意名」のマップ。
 *
 * @param string                $css              BCP が生成した CSS。
 * @param array<string, string> $persistent_names セレクタ => view-transition-name。
 * @param int                   $duration         animation-duration (ms)。未使用。
 * @return string
 */
function freeze_persistent_elements( $css, $persistent_names, $duration ): string {
	if ( ! is_array( $persistent_names ) || empty( $persistent_names ) ) {
		return (string) $css;
	}

	$selectors = array();
	foreach ( $persistent_names as $selector => $name ) {
		$name = trim( (string) $name );
		if ( '' === $name ) {
			continue;
		}
		$selectors[] = "::view-transition-old({$name})";
		$selectors[] = "::view-transition-new({$name})";
	}

	if ( empty( $selectors ) ) {
		return (string) $css;
	}

	$css .= "\n/* vip2026: persistent 要素はクロスフェードさせない(二重描画のちらつき防止) */\n";
	$css .= implode( ",\n", $selectors ) . " {\n";
	$css .= "\tanimation: none;\n";
	$css .= "\tmix-blend-mode: normal;\n";
	$css .= "}\n";

	return (string) $css;
}
add_filter( 'bcp_vt_styles', __NAMESPACE__ . '\freeze_persistent_elements', 10, 3 );
