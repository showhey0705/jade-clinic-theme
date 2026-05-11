<?php
/**
 * VIP2026 — Ollie 子テーマ
 *
 * @package vip2026
 */

namespace VIP2026;

const VERSION       = '0.4.0';
// Adobe Fonts (Typekit) の Kit ID（starter デフォルト）。
// サイト固有の Kit ID は inc/{sitename}.php から `vip2026/typekit_kit` フィルタで返す。
// 空文字なら Typekit 読み込みをスキップ。
const TYPEKIT_KIT   = '';
const TYPEKIT_HOSTS = array( 'https://use.typekit.net', 'https://p.typekit.net' );

/**
 * 子テーマ初期設定。
 *
 * - textdomain ロード（i18n）
 * - エディタ用スタイル（front と同じ見た目をエディタにも）
 *
 * Adobe Fonts はこの口（CSS link）では読み込まない。Kit `bzy5pnl` は JS async
 * loader 専用構成で `.css` エンドポイントが全ドメインに対して 412 を返すため、
 * `enqueue_typekit()` で `<script>` 経由で読み込む。
 */
function setup(): void {
	load_child_theme_textdomain( 'vip2026', get_stylesheet_directory() . '/languages' );

	add_editor_style( array(
		'style.css',
		'assets/styles/japanese-typography.css',
	) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );

/**
 * フロント側スタイル enqueue。
 *
 * - 子テーマ style.css（親テーマ後に読まれるよう priority 20）
 * - 日本語タイポグラフィ補助 CSS
 */
function enqueue_styles(): void {
	wp_enqueue_style(
		'vip2026-style',
		get_stylesheet_uri(),
		array(),
		VERSION
	);

	wp_enqueue_style(
		'vip2026-japanese-typography',
		get_stylesheet_directory_uri() . '/assets/styles/japanese-typography.css',
		array(),
		VERSION
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles', 20 );

/**
 * Adobe Fonts（Typekit）の JS async loader を読み込む。
 *
 * 経緯: Adobe Fonts は `.css` エンドポイントを kit 設定で無効化できるため、
 * `.js` エンドポイント経由で動的に @font-face を注入する標準ローダーパターンを採用。
 *
 * Kit ID 解決: starter デフォルトは `TYPEKIT_KIT = ''`（空）。サイト固有の Kit ID は
 * inc/{sitename}.php から `vip2026/typekit_kit` フィルタで返す。
 *
 *   add_filter( 'vip2026/typekit_kit', static fn(): string => 'xxxxxxx' );
 *
 * 適用先:
 *   - フロント (wp_enqueue_scripts)
 *   - ブロックエディタ親 + iframe キャンバス (enqueue_block_assets, is_admin guard)
 */
function enqueue_typekit(): void {
	$kit = (string) apply_filters( 'vip2026/typekit_kit', TYPEKIT_KIT );
	if ( '' === $kit ) {
		return; // Kit ID 未設定なら何もしない。starter デフォルトの挙動。
	}
	wp_enqueue_script(
		'vip2026-typekit',
		'https://use.typekit.net/' . $kit . '.js',
		array(),
		null,
		false // head 配置でフォント取得を早める。FOUT 抑制のため async/defer は付けない。
	);
	wp_add_inline_script(
		'vip2026-typekit',
		'try { Typekit.load({ async: true }); } catch (e) {}'
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_typekit', 20 );

function enqueue_typekit_in_editor(): void {
	if ( ! is_admin() ) {
		return;
	}
	enqueue_typekit();
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_typekit_in_editor' );

/**
 * Adobe Fonts に preconnect ヒントを追加（CORS 必須なので crossorigin 付き）。
 */
function resource_hints( array $hints, string $relation ): array {
	if ( 'preconnect' !== $relation ) {
		return $hints;
	}

	foreach ( TYPEKIT_HOSTS as $host ) {
		$hints[] = array(
			'href'        => $host,
			'crossorigin' => 'anonymous',
		);
	}

	return $hints;
}
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\resource_hints', 10, 2 );

/**
 * 子テーマ独自のブロックスタイル登録（後続 Phase で実装）。
 */
require_once get_stylesheet_directory() . '/inc/block-styles.php';

/**
 * エディタ UX：タイポ・スペーシング・シャドウ・枠線コントロールを常時表示。
 */
require_once get_stylesheet_directory() . '/inc/editor-controls.php';

/**
 * パターン専用 CSS / JS の規約ベース自動ロード。
 * assets/styles/patterns/{block}--{class}.css をスキャンし、
 * is-style-{class} 出現時のみ条件付き enqueue する。
 */
require_once get_stylesheet_directory() . '/inc/pattern-styles.php';

/**
 * 書体（フォント）の遅延 enqueue。
 * theme.json で slug だけ登録した書体の @font-face を、
 * has-{slug}-font-family を含むブロックが描画された時のみ読み込む。
 */
require_once get_stylesheet_directory() . '/inc/fonts.php';

/**
 * Ollie Pro Responsive Controls の「Typography → カラム (column-count)」拡張。
 * Ollie Pro が無効/未導入なら自動 no-op。
 */
require_once get_stylesheet_directory() . '/inc/responsive-columns.php';

/**
 * jadeclinic.jp 専用：FB ドメイン認証 / JSON-LD / LP femcare のヘッダフッタ非表示。
 * 別サイト転用時はこの 1 行を消せば全部止まる。
 */
require_once get_stylesheet_directory() . '/inc/jadeclinic.php';
