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
 * 各ブロックの supports に __experimentalDefaultControls を注入。
 *
 * すでに supports.<key> がオブジェクト/配列で存在する場合のみ書き換える
 * （= ブロック側でその機能を有効にしているもののみ対象）。
 */
function force_default_controls( array $args ): array {
	foreach ( DEFAULT_CONTROLS as $support_key => $controls ) {
		if ( ! isset( $args['supports'][ $support_key ] ) ) {
			continue;
		}

		$current = $args['supports'][ $support_key ];

		if ( ! is_array( $current ) ) {
			$current = array();
		}

		$current['__experimentalDefaultControls'] = $controls;
		$args['supports'][ $support_key ]         = $current;
	}

	return $args;
}
add_filter( 'register_block_type_args', __NAMESPACE__ . '\force_default_controls', 20 );
