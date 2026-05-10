<?php
/**
 * パターン専用 CSS / JS の自動ロード。
 *
 * 規約:
 *   assets/styles/patterns/{block-slug}--{class-suffix}.css
 *   assets/js/patterns/{block-slug}--{class-suffix}.js   （任意・同名で対応）
 *
 *   例: group--stack-cards.css
 *       → core/group ブロックに is-style-stack-cards が付いた時のみ読み込む
 *
 * 仕組み:
 *   1. 起動時に patterns/ 配下を 1 度だけスキャン → レジストリ作成
 *      （オブジェクトキャッシュ + リクエスト内静的キャッシュの 2 段）
 *   2. レジストリに含まれる各ブロックタイプに対して
 *      render_block_{block} フィルタを 1 つだけ取り付ける
 *   3. ブロック描画時に該当 variation を検出した場合のみ、
 *      その時点で wp_register_style + wp_enqueue_style（遅延登録）
 *   4. エディタ側はパターンプレビューのため全 unconditional enqueue
 *      （admin only なのでコスト無視可）
 *
 * 開発時は WP_DEBUG=true で wp_cache を bypass するので、
 * パターン CSS を追加すれば即反映される。本番は 1 時間 or theme バンプで invalidate。
 *
 * @package vip2026
 */

namespace VIP2026\PatternStyles;

const STYLES_DIR  = '/assets/styles/patterns/';
const SCRIPTS_DIR = '/assets/js/patterns/';
const CACHE_GROUP = 'vip2026';

/**
 * パターン用アセットのレジストリを返す。
 *
 * 構造:
 *   [
 *     'core/group' => [
 *       'stack-cards' => [
 *         'handle' => 'vip2026-pattern-group-stack-cards',
 *         'url'    => 'https://.../assets/styles/patterns/group--stack-cards.css',
 *         'ver'    => 1234567890,
 *         'js'     => null | [ 'handle' => ..., 'url' => ..., 'ver' => ... ],
 *       ],
 *       ...
 *     ],
 *   ]
 *
 * @return array<string, array<string, array{handle:string, url:string, ver:int, js:?array}>>
 */
function scan_registry(): array {
	static $cache = null;
	if ( $cache !== null ) {
		return $cache;
	}

	$dev_mode  = defined( 'WP_DEBUG' ) && WP_DEBUG;
	$cache_key = 'pattern_styles_registry_v' . wp_get_theme()->get( 'Version' );

	if ( ! $dev_mode ) {
		$cached = wp_cache_get( $cache_key, CACHE_GROUP );
		if ( false !== $cached ) {
			return $cache = $cached;
		}
	}

	$registry       = array();
	$stylesheet     = get_stylesheet_directory();
	$stylesheet_uri = get_stylesheet_directory_uri();

	foreach ( glob( $stylesheet . STYLES_DIR . '*.css' ) ?: array() as $path ) {
		$name = basename( $path, '.css' );

		// 規約: ファイル名は "{block-slug}--{class-suffix}" の形でなければスキップ。
		if ( ! str_contains( $name, '--' ) ) {
			continue;
		}

		[ $block_slug, $class_suffix ] = explode( '--', $name, 2 );
		$block = "core/{$block_slug}";

		$entry = array(
			'handle' => "vip2026-pattern-{$block_slug}-{$class_suffix}",
			'url'    => $stylesheet_uri . STYLES_DIR . "{$name}.css",
			'ver'    => filemtime( $path ),
			'js'     => null,
		);

		// 同名 JS があれば紐付ける。
		$js_path = $stylesheet . SCRIPTS_DIR . "{$name}.js";
		if ( file_exists( $js_path ) ) {
			$entry['js'] = array(
				'handle' => "vip2026-pattern-{$block_slug}-{$class_suffix}-js",
				'url'    => $stylesheet_uri . SCRIPTS_DIR . "{$name}.js",
				'ver'    => filemtime( $js_path ),
			);
		}

		$registry[ $block ][ $class_suffix ] = $entry;
	}

	if ( ! $dev_mode ) {
		wp_cache_set( $cache_key, $registry, CACHE_GROUP, HOUR_IN_SECONDS );
	}

	return $cache = $registry;
}

/**
 * レジストリに含まれる各ブロックタイプに render_block_{block} フィルタを取り付ける。
 *
 * register_block_style 系統と分離し、ピッカーには出さずに条件付きロードのみ行う。
 */
function attach_filters(): void {
	foreach ( array_keys( scan_registry() ) as $block ) {
		add_filter( "render_block_{$block}", __NAMESPACE__ . '\maybe_enqueue', 10, 2 );
	}
}
add_action( 'init', __NAMESPACE__ . '\attach_filters', 11 );

/**
 * ブロック描画時に該当 variation を検出して遅延登録 + enqueue。
 *
 * static $loaded で 1 ページ 1 度だけ enqueue するように抑える。
 * register_block_style コアの自動 enqueue 同等の挙動を自前で実現。
 */
function maybe_enqueue( string $content, array $block ): string {
	static $loaded = array();

	$class = $block['attrs']['className'] ?? '';
	if ( '' === $class ) {
		return $content;
	}

	// class 文字列を空白分割して厳密な単語マッチに使う（部分一致による
	// 誤検出を防ぐ。例: "tabs-vertical" を "tabs" として誤認しないように）。
	$tokens = preg_split( '/\s+/', trim( $class ) ) ?: array();

	$bucket = scan_registry()[ $block['blockName'] ] ?? array();
	foreach ( $bucket as $suffix => $info ) {
		if ( isset( $loaded[ $info['handle'] ] ) ) {
			continue;
		}
		// is-style-{slug} と bare {slug} の両方を許容。
		// bare は、variation 登録される前の旧マークアップ（Synced Pattern 等）
		// との互換性を残すため。
		if ( ! in_array( "is-style-{$suffix}", $tokens, true )
			&& ! in_array( $suffix, $tokens, true )
		) {
			continue;
		}

		ensure_style_registered( $info );
		wp_enqueue_style( $info['handle'] );

		if ( $info['js'] ) {
			ensure_script_registered( $info['js'] );
			wp_enqueue_script( $info['js']['handle'] );
		}

		$loaded[ $info['handle'] ] = true;
	}

	return $content;
}

/**
 * エディタはパターンプレビューのため全 unconditional enqueue。
 * admin only なのでコスト無視可。
 */
function enqueue_editor_assets(): void {
	foreach ( scan_registry() as $bucket ) {
		foreach ( $bucket as $info ) {
			ensure_style_registered( $info );
			wp_enqueue_style( $info['handle'] );

			if ( $info['js'] ) {
				ensure_script_registered( $info['js'] );
				wp_enqueue_script( $info['js']['handle'] );
			}
		}
	}
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_assets' );

/**
 * style ハンドルが未登録なら登録する。重複登録を避けるためのヘルパ。
 *
 * @param array{handle:string, url:string, ver:int} $info
 */
function ensure_style_registered( array $info ): void {
	if ( ! wp_style_is( $info['handle'], 'registered' ) ) {
		wp_register_style( $info['handle'], $info['url'], array(), $info['ver'] );
	}
}

/**
 * script ハンドルが未登録なら登録する。defer + footer 配置を既定とする。
 *
 * @param array{handle:string, url:string, ver:int} $info
 */
function ensure_script_registered( array $info ): void {
	if ( ! wp_script_is( $info['handle'], 'registered' ) ) {
		wp_register_script(
			$info['handle'],
			$info['url'],
			array(),
			$info['ver'],
			array( 'in_footer' => true, 'strategy' => 'defer' )
		);
	}
}
