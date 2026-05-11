<?php
/**
 * エディタ：タイポグラフィ／スペーシング／シャドウ／枠線コントロールを常時表示。
 *
 * 旧 Ollie-Japan-Edition では editor-typography-controls.js（wp-blocks/wp-hooks 依存）
 * で `blocks.registerBlockType` フィルタを当てていた。同じことは register_block_type_args
 * の PHP フィルタで実現できるので、エディタ起動時の JS 1 本を丸ごと削減する。
 *
 * supports の機能フラグと __experimentalDefaultControls は別キー体系であることに注意:
 *
 *  - 機能フラグ(例: `typography.__experimentalFontStyle`)は core の
 *    `__EXPERIMENTAL_STYLE_PROPERTY[*].support` で参照される。
 *    多くが `__experimental` プレフィックス付き。
 *    → これを true にしないと、そもそも UI が表示されない。
 *  - パネル既定表示(`__experimentalDefaultControls`)はプレフィックス無しの
 *    パネル内部キー(例: `fontAppearance` は `fontStyle` + `fontWeight` の合成)。
 *    → これを true にしないと「外観」など個別コントロールが折りたたみの中に隠れる。
 *
 * @package vip2026
 */

namespace VIP2026\EditorControls;

/**
 * 各 supports グループの「機能フラグキー(core が見るキー)」のリスト。
 * ブロックが明示的に false にしていなければ enable する。
 */
const FEATURE_FLAG_KEYS = array(
	'typography'           => array(
		'fontSize',
		'lineHeight',
		'textColumns',
		'__experimentalFontFamily',
		'__experimentalFontStyle',
		'__experimentalFontWeight',
		'__experimentalLetterSpacing',
		'__experimentalTextDecoration',
		'__experimentalTextTransform',
		'__experimentalWritingMode',
	),
	'spacing'              => array( 'margin', 'padding', 'blockGap' ),
	'__experimentalBorder' => array( 'color', 'radius', 'style', 'width' ),
);

/**
 * 各 supports グループの `__experimentalDefaultControls` に流し込むキー。
 * パネル側の `defaultControls.<key>` で `isShownByDefault` の判定に使われる。
 */
const DEFAULT_CONTROL_KEYS = array(
	'typography'           => array(
		'fontSize'       => true,
		'fontFamily'     => true,
		'fontAppearance' => true,
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
	'__experimentalBorder' => array(
		'color'  => true,
		'radius' => true,
		'style'  => true,
		'width'  => true,
	),
);

/**
 * 各ブロックの supports に機能フラグ + __experimentalDefaultControls を注入。
 *
 * 既に `supports.<key>` が真偽値/オブジェクトで存在するブロックのみ対象。
 * `false` で明示的に opt-out しているブロックは尊重して触らない。
 * 個別の機能キー(例 __experimentalFontStyle: false)が明示されているものも尊重。
 */
function force_default_controls( array $args ): array {
	foreach ( FEATURE_FLAG_KEYS as $support_key => $flags ) {
		if ( ! isset( $args['supports'][ $support_key ] ) ) {
			continue;
		}

		$current = $args['supports'][ $support_key ];

		// `true` は「全機能オン」の糖衣構文。明示配列に展開して個別キーで扱えるように。
		if ( true === $current ) {
			$current = array_fill_keys( $flags, true );
		} elseif ( false === $current ) {
			// 明示的 opt-out は尊重。
			continue;
		} elseif ( ! is_array( $current ) ) {
			$current = array();
		}

		// 機能フラグを enable(ブロックが explicit に値を設定していないキーのみ)。
		foreach ( $flags as $flag_key ) {
			if ( ! array_key_exists( $flag_key, $current ) ) {
				$current[ $flag_key ] = true;
			}
		}

		$current['__experimentalDefaultControls'] = DEFAULT_CONTROL_KEYS[ $support_key ];
		$args['supports'][ $support_key ]         = $current;
	}

	// shadow は単独のブール supports。`true` / object の差し替えだけで、内部キーを持たない。
	if ( isset( $args['supports']['shadow'] ) && false !== $args['supports']['shadow'] ) {
		$shadow = is_array( $args['supports']['shadow'] ) ? $args['supports']['shadow'] : array();

		$shadow['__experimentalDefaultControls'] = array( 'shadow' => true );
		$args['supports']['shadow']              = $shadow;
	}

	return $args;
}
add_filter( 'register_block_type_args', __NAMESPACE__ . '\force_default_controls', 20 );
