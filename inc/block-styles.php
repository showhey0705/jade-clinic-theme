<?php
/**
 * 子テーマ専用ブロックスタイル登録。
 *
 * 二段構成:
 *   1. assets/styles/core-*.css を、対象コアブロックが使われた時のみ enqueue
 *      （Ollie 親テーマの enqueue_custom_block_styles と同じ仕組みを子テーマでも提供）
 *   2. 独立 CSS を持つ block style variation は WP 6.6+ の register_block_style() の
 *      style_handles 引数で「is-style-X が付いたブロックがレンダされた時のみ」CSS を enqueue
 *
 * これにより、旧 Ollie-Japan-Edition の `render_block` フィルタによる遅延 enqueue は不要。
 *
 * @package vip2026
 */

namespace VIP2026\BlockStyles;

/**
 * 子テーマ独自の core block 拡張 CSS を自動 enqueue。
 *
 * `assets/styles/core-{block}.css` を置けば、その core ブロックが
 * ページ内で使われた時にだけ CSS が読み込まれる。
 */
function enqueue_core_block_extensions(): void {
	$files = glob( get_stylesheet_directory() . '/assets/styles/core-*.css' );

	if ( ! $files ) {
		return;
	}

	foreach ( $files as $file ) {
		$filename   = basename( $file, '.css' );
		$block_name = str_replace( 'core-', 'core/', $filename );

		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => "vip2026-{$filename}",
				'src'    => get_stylesheet_directory_uri() . "/assets/styles/{$filename}.css",
				'path'   => $file,
				'ver'    => filemtime( $file ),
			)
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\enqueue_core_block_extensions' );

/**
 * 子テーマ専用ブロックスタイル variation の定義。
 *
 * `css` キーがあるものは `assets/styles/{css}` を style_handles で繋ぐ。
 * `css` 無しのものは UI 登録のみ。実 CSS は対応する core-*.css に同居している
 * （例: ken-burns / media-shine は core-image.css に書かれている）。
 *
 * @return array<string, array<int, array{name:string, label:string, css?:string}>>
 */
function get_block_style_variations(): array {
	return array(
		'core/paragraph' => array(
			array(
				'name'  => 'vertical-text',
				'label' => __( '縦書き', 'vip2026' ),
				'css'   => 'paragraph/paragraph-vertical-text.css',
			),
			array(
				'name'  => 'notebook',
				'label' => __( 'ノート風', 'vip2026' ),
				'css'   => 'paragraph/paragraph-notebook.css',
			),
		),
		'core/button'    => array(
			array(
				'name'  => 'shiny',
				'label' => __( 'キラッと', 'vip2026' ),
				'css'   => 'button/button-shiny.css',
			),
		),
		'core/image'     => array(
			array( 'name' => 'media-shine', 'label' => __( 'Shine', 'vip2026' ) ),
			array( 'name' => 'ken-burns',   'label' => __( 'Ken Burns', 'vip2026' ) ),
		),
		'core/cover'     => array(
			array( 'name' => 'circle-cover', 'label' => __( 'Circle', 'vip2026' ) ),
			array( 'name' => 'ken-burns',    'label' => __( 'Ken Burns', 'vip2026' ) ),
		),
		'core/column'    => array(
			array( 'name' => 'column-box-red', 'label' => __( 'Box RED', 'vip2026' ) ),
		),
		// core/group 系（stack-cards / horizontal-scroll / tabs）は variation 登録ではなく
		// inc/pattern-styles.php の規約ベース自動ロードに移行。パターン挿入時のみ動く。
	);
}

/**
 * ブロックスタイル variation を登録。
 *
 * 親 Ollie テーマの register_block_styles()（priority 10）より後に走らせるため
 * priority 20 を指定。
 */
function register_block_style_variations(): void {
	foreach ( get_block_style_variations() as $block => $styles ) {
		foreach ( $styles as $style ) {
			$args = array(
				'name'  => $style['name'],
				'label' => $style['label'],
			);

			if ( ! empty( $style['css'] ) ) {
				$rel    = $style['css'];
				$handle = 'vip2026-' . pathinfo( $rel, PATHINFO_FILENAME );
				$path   = get_stylesheet_directory() . '/assets/styles/' . $rel;

				if ( file_exists( $path ) ) {
					wp_register_style(
						$handle,
						get_stylesheet_directory_uri() . '/assets/styles/' . $rel,
						array(),
						filemtime( $path )
					);
					// register_block_style() は style_handle（単数）を取る。
					// 旧版で style_handles（複数）にしていたため CSS が紐付いていなかった。
					$args['style_handle'] = $handle;
				}
			}

			register_block_style( $block, $args );
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\register_block_style_variations', 20 );
