<?php
/**
 * エディタ：タイポグラフィ／スペーシング／シャドウ／枠線コントロールを常時表示。
 *
 * 旧 Ollie-Japan-Edition では editor-typography-controls.js（wp-blocks/wp-hooks 依存）
 * で `blocks.registerBlockType` フィルタを当てていた。同じことは register_block_type_args
 * の PHP フィルタで実現できるので、エディタ起動時の JS 1 本を丸ごと削減する。
 *
 * @package vip2026
 */

namespace VIP2026\EditorControls;

const DEFAULT_CONTROLS = array(
	'typography'           => array(
		'fontSize'       => true,
		'fontFamily'     => true,
		'fontStyle'      => true,
		'fontWeight'     => true,
		'lineHeight'     => true,
		'letterSpacing'  => true,
		'textDecoration' => true,
		'textTransform'  => true,
		'textColumns'    => true,
		'writingMode'    => true,
	),
	'spacing'              => array(
		'margin'   => true,
		'padding'  => true,
		'blockGap' => true,
	),
	'shadow'               => array(
		'shadow' => true,
	),
	'__experimentalBorder' => array(
		'color'  => true,
		'radius' => true,
		'style'  => true,
		'width'  => true,
	),
);

/**
 * 各ブロックの supports に基底機能フラグ + __experimentalDefaultControls を注入。
 *
 * 2 段階で適用する:
 *   1. 基底機能フラグ(例 fontStyle / fontWeight 等)を enable
 *   2. __experimentalDefaultControls を立ててインスペクタに常時表示
 *
 * 1 だけだと折りたたみの中、2 だけだと機能が無効でそもそも UI が出ない。
 * 両方揃わないと「外観」(fontStyle + fontWeight)のような複合 UI は表示されない。
 *
 * 既に `supports.<key>` が真偽値/オブジェクトで存在するブロックのみ対象。
 * `false` で明示的に opt-out しているブロックは尊重して触らない。
 * 個別の機能キー(例 fontStyle: false)が明示されているものも尊重。
 */
function force_default_controls( array $args ): array {
	foreach ( DEFAULT_CONTROLS as $support_key => $controls ) {
		if ( ! isset( $args['supports'][ $support_key ] ) ) {
			continue;
		}

		$current = $args['supports'][ $support_key ];

		// `true` は「全機能オン」の糖衣構文。明示配列に展開して個別キーで扱えるように。
		if ( true === $current ) {
			$current = array_fill_keys( array_keys( $controls ), true );
		} elseif ( false === $current ) {
			// 明示的 opt-out は尊重。
			continue;
		} elseif ( ! is_array( $current ) ) {
			$current = array();
		}

		// 基底機能フラグを enable(ブロックが explicit に値を設定していないキーのみ)。
		// これがないと __experimentalDefaultControls.fontStyle を立てても UI が出ない。
		foreach ( $controls as $feature_key => $enabled ) {
			if ( $enabled && ! array_key_exists( $feature_key, $current ) ) {
				$current[ $feature_key ] = true;
			}
		}

		$current['__experimentalDefaultControls'] = $controls;
		$args['supports'][ $support_key ]         = $current;
	}

	return $args;
}
add_filter( 'register_block_type_args', __NAMESPACE__ . '\force_default_controls', 20 );
