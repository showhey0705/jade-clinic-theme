<?php
/**
 * 子テーマ専用ブロックスタイル登録。
 *
 * 二段構成:
 *   1. assets/styles/core-*.css を、対象コアブロックが使われた時のみ enqueue
 *      （Ollie 親テーマの enqueue_custom_block_styles と同じ仕組みを子テーマでも提供）
 *   2. 独立 CSS を持つ block style variation は register_block_style() の style_handle で
 *      エディタに繋ぎ、フロントは自前の render_block フィルタで
 *      「is-style-X が付いたブロックが描画された時のみ」CSS を enqueue
 *
 * フロントを自前にしている理由: ブロックテーマはテンプレート本文を wp_head より先に
 * 描画する（template-canvas.php）。コアが style_handle 用に enqueue_block_assets で
 * 仕掛ける render_block フィルタはその後に付くので、本文のブロックには間に合わず、
 * フロントに CSS が一切出ない（縦書き・ノート風・キラッと が効かなかった原因）。
 *
 * @package vip2026
 */

namespace VIP2026\BlockStyles;

/**
 * 子テーマ独自の core block 拡張 CSS を自動 enqueue。
 *
 * `assets/styles/core-{block}.css` を置けば、その core ブロックが
 * ページ内で使われた時にだけ CSS が読み込まれる。
 *
 * 重複配信の回避: 親 Ollie テーマの enqueue_custom_block_styles() は
 * get_theme_file_uri() を使うため、子テーマに同名ファイルがあると親の
 * ハンドル(ollie-block-*)が既に子テーマの CSS を指す。その場合に子側でも
 * enqueue すると同一 URL の <link> が 2 本出るため、親に同名ファイルが
 * 存在するもの(= 親ハンドル経由で配信済み)はスキップする。
 */
function enqueue_core_block_extensions(): void {
	$files = glob( get_stylesheet_directory() . '/assets/styles/core-*.css' );

	if ( ! $files ) {
		return;
	}

	$parent_dir = get_template_directory() . '/assets/styles/';

	foreach ( $files as $file ) {
		$filename   = basename( $file, '.css' );
		$block_name = str_replace( 'core-', 'core/', $filename );

		// 親テーマに同名ファイルがある場合、親の ollie-block-* ハンドルが
		// get_theme_file_uri() 経由で既に子テーマ版を配信している。
		if ( file_exists( $parent_dir . $filename . '.css' ) ) {
			continue;
		}

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
 * 親 Ollie ハンドル(ollie-block-core-*)が子テーマの CSS を配信する場合の
 * キャッシュバスティング。親は ver 未指定(= WP バージョン)のため、子テーマの
 * CSS 更新でキャッシュが破棄されない。子テーマ側ファイルの filemtime を ver に付与する。
 *
 * @param string $src    スタイル URL。
 * @param string $handle スタイルハンドル。
 * @return string
 */
function bust_parent_block_style_cache( string $src, string $handle ): string {
	if ( 0 !== strpos( $handle, 'ollie-block-core-' ) ) {
		return $src;
	}
	$filename = substr( $handle, strlen( 'ollie-block-' ) ); // core-group 等。
	$child    = get_stylesheet_directory() . '/assets/styles/' . $filename . '.css';
	if ( file_exists( $child ) ) {
		$src = add_query_arg( 'ver', (string) filemtime( $child ), $src );
	}
	return $src;
}
add_filter( 'style_loader_src', __NAMESPACE__ . '\bust_parent_block_style_cache', 10, 2 );

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
			// 本文リンクと同じ「左から引かれる 1px の下線」をボタンにも。
			// 塗りのボタンほど強くない導線（記事一覧の「すべて見る」等）向け。
			array(
				'name'  => 'underline',
				'label' => __( '下線リンク', 'vip2026' ),
				'css'   => 'button/button-underline.css',
			),
		),
		'core/image'     => array(
			array( 'name' => 'media-shine', 'label' => __( 'Shine', 'vip2026' ) ),
			array( 'name' => 'ken-burns',   'label' => __( 'Ken Burns', 'vip2026' ) ),
			array( 'name' => 'card-apple',  'label' => __( 'カード影（Apple）', 'vip2026' ) ),
		),
		'core/post-featured-image' => array(
			// カード影（Apple）。実 CSS は core-post-featured-image.css（規約ロード）。
			array( 'name' => 'card-apple', 'label' => __( 'カード影（Apple）', 'vip2026' ) ),
		),
		'core/cover'     => array(
			array( 'name' => 'circle-cover', 'label' => __( 'Circle', 'vip2026' ) ),
			array( 'name' => 'ken-burns',    'label' => __( 'Ken Burns', 'vip2026' ) ),
			array( 'name' => 'card-apple',   'label' => __( 'カード影（Apple）', 'vip2026' ) ),
		),
		// 投稿ヒーローパターン(single-hero-*)がカテゴリ/タグ表示に使う。
		// photoshopvip2022 から post-terms-cat.css とセットで移植。
		'core/post-terms' => array(
			array( 'name' => 'cat-plain',     'label' => __( 'カテゴリ: 標準', 'vip2026' ),       'css' => 'post-terms-cat.css' ),
			array( 'name' => 'cat-fill',      'label' => __( 'カテゴリ: 色背景', 'vip2026' ),     'css' => 'post-terms-cat.css' ),
			array( 'name' => 'cat-underline', 'label' => __( 'カテゴリ: 色下線', 'vip2026' ),     'css' => 'post-terms-cat.css' ),
			array( 'name' => 'cat-text',      'label' => __( 'カテゴリ: 色文字', 'vip2026' ),     'css' => 'post-terms-cat.css' ),
			array( 'name' => 'cat-dark',      'label' => __( 'カテゴリ: ダーク背景', 'vip2026' ), 'css' => 'post-terms-cat.css' ),
		),
		'core/column'    => array(
			array( 'name' => 'column-box-red', 'label' => __( 'Box RED', 'vip2026' ) ),
		),
		'core/group'     => array(
			// カード用の影 2 段（既定 / ホバー）。値は theme.json のプリセット
			// card-apple / card-apple-hover を参照する。実 CSS は core-group.css に同居
			// （core の register_block_style() はバリエーション単位ではなくブロック単位でしか
			// 出し分けないため、独立ファイルにしてもリクエストが増えるだけ）。
			array(
				'name'  => 'card-apple',
				'label' => __( 'カード影（Apple）', 'vip2026' ),
			),
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
	$front = array(); // block => [ style name => handle ]（フロントの遅延 enqueue 用）

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
					// エディタ（iframe キャンバス）へはこれで届く。フロントは下の render_block。
					$args['style_handle'] = $handle;

					$front[ $block ][ $style['name'] ] = $handle;
				}
			}

			register_block_style( $block, $args );
		}
	}

	if ( $front ) {
		add_filter(
			'render_block',
			static function ( $html, $block ) use ( $front ) {
				$name = (string) ( $block['blockName'] ?? '' );
				if ( '' === $name || empty( $front[ $name ] ) ) {
					return $html;
				}
				$class = (string) ( $block['attrs']['className'] ?? '' );
				if ( '' === $class ) {
					return $html;
				}
				foreach ( $front[ $name ] as $style_name => $handle ) {
					if ( false !== strpos( $class, 'is-style-' . $style_name ) ) {
						wp_enqueue_style( $handle );
					}
				}
				return $html;
			},
			10,
			2
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\register_block_style_variations', 20 );

/**
 * 親 Ollie の重複ブロックスタイルを外す。
 *
 * photoshopvip2022 では pvip-blocks の pvip-check / pvip-circled が Ollie の
 * Check / Check Circle と重複するため外している(同じ役割の選択肢が 2 つ並ぶ
 * のを防ぐ)。jadeclinic には代替を提供するプラグインが無いので、代替提供元
 * (pvip-blocks の Features_Handler)が存在するときだけ発動する = 現状は no-op。
 * starter へ還流したときに両サイトでファイルを共通化するためのガード。
 *
 * priority 30 = 親の register_block_styles()（10）と当テーマの登録（20）の後。
 */
function unregister_duplicate_block_styles(): void {
	if ( ! class_exists( '\\PVIP\\Blocks\\Features\\Features_Handler' ) ) {
		return; // 代替スタイルの提供元が無い環境では Ollie 標準を残す。
	}
	foreach ( array( 'list-check', 'list-check-circle' ) as $name ) {
		unregister_block_style( 'core/list', $name );
	}
}
add_action( 'init', __NAMESPACE__ . '\unregister_duplicate_block_styles', 30 );

/**
 * リストの項目間余白を全スタイルで統一する CSS を、
 * core/list が実際に描画されたページにだけ配信する。
 *
 * Ollie 親テーマ由来の 3 種（list-check / list-check-circle / list-boxed）は
 * blockGap を見ないため、スタイルを切り替えるたび項目間が変わる。
 * list-gap-unify.css はこれらを blockGap 変数(--vip2026-list-gap、無ければ 0.3em)
 * に揃える。pvip-blocks が無い環境ではフォールバック値がそのまま効く。
 *
 * ⚠️ 子テーマに `assets/styles/core-list.css` を置いてはいけない。
 * 親の enqueue_custom_block_styles() は get_theme_file_uri() を使うため、
 * 同名ファイルがあると親の core-list.css が**丸ごと差し替わり**、
 * list-check などのスタイル定義そのものが消える。だから別名のファイルにしている。
 *
 * wp_enqueue_block_style() は「そのブロックがページに描画された時だけ」読み込む。
 * path を渡すとコアがサイズ次第でインライン化してくれる。
 */
function enqueue_list_gap_unify(): void {
	$rel  = '/assets/styles/list-gap-unify.css';
	$path = get_stylesheet_directory() . $rel;

	if ( ! is_readable( $path ) ) {
		return;
	}

	$args = array(
		'handle' => 'vip2026-list-gap-unify',
		'src'    => get_stylesheet_directory_uri() . $rel,
		'path'   => $path,
		'ver'    => (string) filemtime( $path ),
	);

	wp_enqueue_block_style( 'core/list', $args );
}
add_action( 'init', __NAMESPACE__ . '\enqueue_list_gap_unify', 20 );

/**
 * エディタキャンバス（iframe）へも同じ CSS を届ける。
 *
 * enqueue_block_assets はフロントでも発火するため is_admin() でガードする
 * （フロントは上の条件付き配信のままにする）。
 */
function enqueue_list_gap_unify_editor(): void {
	if ( ! is_admin() ) {
		return;
	}
	$rel  = '/assets/styles/list-gap-unify.css';
	$path = get_stylesheet_directory() . $rel;

	if ( is_readable( $path ) ) {
		wp_enqueue_style(
			'vip2026-list-gap-unify-editor',
			get_stylesheet_directory_uri() . $rel,
			array(),
			(string) filemtime( $path )
		);
	}
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_list_gap_unify_editor' );
