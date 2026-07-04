<?php
/**
 * VIP2026 — Ollie 子テーマ
 *
 * @package vip2026
 */

namespace VIP2026;

// Adobe Fonts (Typekit) の Kit ID（starter デフォルト）。
// サイト固有の Kit ID は inc/{sitename}.php から `vip2026/typekit_kit` フィルタで返す。
// 空文字なら Typekit 読み込みをスキップ。
const TYPEKIT_KIT   = '';
const TYPEKIT_HOSTS = array( 'https://use.typekit.net', 'https://p.typekit.net' );

/**
 * 子テーマのバージョン。style.css の Version ヘッダを唯一の正とし、アセットの
 * cache-bust 文字列に使う。
 *
 * 以前は `const VERSION` を別途持っていたが style.css と二重管理になり乖離した
 * (style.css だけ bump され const が 0.4.0 のまま取り残された)。style.css ヘッダ
 * 参照に一本化し、二度と乖離しないようにしている。bump 時は style.css の Version
 * だけ更新すればよい。
 */
function version(): string {
	static $version = null;
	if ( null === $version ) {
		$version = (string) wp_get_theme()->get( 'Version' );
	}
	return $version;
}

/**
 * 子テーマ初期設定。
 *
 * - textdomain ロード（i18n）
 * - エディタ用スタイル（front と同じ見た目をエディタにも）
 *
 * Adobe Fonts はこの口（CSS link）では読み込まない。Kit `bzy5pnl` は JS async
 * loader 専用構成で `.css` エンドポイントが全ドメインに対して 412 を返すため、
 * `load_typekit()` で `<script>` 経由で読み込む（フロントは maybe_load_typekit で
 * その書体を使うページのみ遅延読み込み）。
 */
function setup(): void {
	load_child_theme_textdomain( 'vip2026', get_stylesheet_directory() . '/languages' );

	add_editor_style( array(
		'style.css',
		'assets/styles/japanese-typography.css',
	) );

	// View Transitions は BCP の View_Transitions モジュール (page-transitions 機能) が
	// `@view-transition { navigation: auto; }` / 固定要素の view-transition-name / duration /
	// reduced-motion をすべて出力する。第三者プラグイン依存の暫定 add_theme_support(
	// 'view-transitions', ... ) は撤去し、固定要素の申告は下の bcp_vt_persistent_names
	// フィルタ一本に集約する。
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );

/**
 * BCP の View Transitions モジュールへ「固定要素 (persistent element)」を申告する。
 *
 * `bcp_vt_persistent_names` フィルタは「CSS セレクタ => view-transition-name」のマップを
 * 受け取り、BCP 側が該当要素へ固定の `view-transition-name` を付与する想定。名前付き要素は
 * ページ遷移中も同一レイヤーとして据え置かれる (root スナップショットのクロスフェードに巻き込まれず、
 * ヘッダーが遷移のたびに消えて再描画されるのを防ぐ)。
 *
 * フロントの実ヘッダーは `<header class="site-header wp-block-template-part">` (Ollie 親テーマの
 * header テンプレートパート由来) なので `.site-header` で一致する。ヘッダー固定という意図を表す
 * `vip-header` を割り当てる。
 *
 * NOTE: View Transitions の有効化と `@view-transition` 等の CSS 出力は BCP モジュールが担う。
 * 本フィルタは「この要素は固定 (persistent)」という申告のみを BCP へ渡す唯一の口。
 */
function vt_persistent_names( array $map ): array {
	$map['.site-header'] = 'vip-header';
	return $map;
}
add_filter( 'bcp_vt_persistent_names', __NAMESPACE__ . '\vt_persistent_names' );

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
		version()
	);

	wp_enqueue_style(
		'vip2026-japanese-typography',
		get_stylesheet_directory_uri() . '/assets/styles/japanese-typography.css',
		array(),
		version()
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles', 20 );

/**
 * Typekit の JS loader が配信する唯一の Web フォント（theme.json slug）に対応する
 * ブロッククラス。このクラスを含むブロックが描画されたページでのみ Typekit を読み込む。
 *
 * Kit `bzy5pnl` は `fot-tsukuaoldmin-pr6n`（筑紫Aオールド明朝 Pr6N）1 書体構成で、
 * theme.json では slug `tsuku-a-old-mincho` として登録されている。
 */
const TYPEKIT_FONT_CLASS = 'has-tsuku-a-old-mincho-font-family';

/**
 * Adobe Fonts（Typekit）の JS loader を enqueue する。
 *
 * Kit `bzy5pnl` は `.css` エンドポイントが全ドメインに対して 412 を返す JS 専用構成の
 * ため `<script>` 経由でしか読み込めない。かつ JS embed は日本語フォントを全グリフ
 * 一括（数 MB）で配信し動的サブセットが効かない。そこで inc/fonts.php の Google Fonts
 * 遅延 enqueue と同じ方針で「その書体を実際に使うページだけ」読み込む（下記
 * maybe_load_typekit）。使わないページでは 1 バイトも読み込まない。
 *
 * Kit ID 解決: starter デフォルトは `TYPEKIT_KIT = ''`（空）。サイト固有の Kit ID は
 * inc/{sitename}.php から `vip2026/typekit_kit` フィルタで返す。
 *
 *   add_filter( 'vip2026/typekit_kit', static fn(): string => 'xxxxxxx' );
 */
function load_typekit(): void {
	$kit = (string) apply_filters( 'vip2026/typekit_kit', TYPEKIT_KIT );
	if ( '' === $kit ) {
		return; // Kit ID 未設定なら何もしない。starter デフォルトの挙動。
	}
	if ( wp_script_is( 'vip2026-typekit', 'enqueued' ) ) {
		return; // 二重 enqueue 防止。
	}
	wp_enqueue_script(
		'vip2026-typekit',
		'https://use.typekit.net/' . $kit . '.js',
		array(),
		null,
		true // フッター配置。描画をブロックしない。フォールバックはシステム明朝。
	);
	wp_add_inline_script(
		'vip2026-typekit',
		'try { Typekit.load({ async: true }); } catch (e) {}'
	);
}

/**
 * フロント：TYPEKIT_FONT_CLASS を含むブロックが描画された時だけ Typekit を enqueue。
 *
 * inc/fonts.php の Google Fonts 遅延 enqueue と同じ仕組み。render_block は wp_head 送出後に
 * 発火するため、スクリプトは wp_footer に出力される（＝レンダリング非ブロッキング）。
 * fontFamily スタックに Hiragino Mincho ProN 等のフォールバックがあるので、未読み込みでも
 * システム明朝で自然に描画される。
 */
function maybe_load_typekit( string $content ): string {
	static $loaded = false;
	if ( $loaded ) {
		return $content;
	}
	if ( str_contains( $content, TYPEKIT_FONT_CLASS ) ) {
		load_typekit();
		$loaded = true;
	}
	return $content;
}
add_filter( 'render_block', __NAMESPACE__ . '\maybe_load_typekit', 10, 1 );

/**
 * エディタ：ピッカー UI と iframe キャンバス用に常時読み込む（フロントの遅延読み込みとは別）。
 */
function enqueue_typekit_in_editor(): void {
	if ( ! is_admin() ) {
		return;
	}
	load_typekit();
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
 * "LINE 緑" ボタン variation 選択時に customIconSvg + iconPositionLeft を
 * 自動セットする HOC (assets/js/button-line-defaults.js) を block editor に enqueue。
 */
require_once get_stylesheet_directory() . '/inc/button-line-defaults.php';

/**
 * パフォーマンス最適化：Ollie 親テーマがフロントでも誘発するパターン全
 * ハイドレーションを、パターンを参照する文脈(エディタ / REST)に限定する。
 */
require_once get_stylesheet_directory() . '/inc/performance.php';

/**
 * 内部ブログカード(JADE Blog Card)。
 * 投稿エディタで「URL を単独段落として貼り付け」たとき、同サイト URL を
 * WP 標準の post-embed iframe ではなくサムネ + タイトル + 抜粋のカードに置換。
 * 詳細: inc/internal-blog-card.php
 */
require_once get_stylesheet_directory() . '/inc/internal-blog-card.php';

/**
 * core/table 拡張（横スクロール / 固定 / スマホ縦並び / セル記号・星評価・ボタン化 / 比較表スタイル）。
 * beauty-clinic-patterns の Table_Extension をテーマへ移植。BCP が有効な本サイトでは
 * 二重登録を避けるため起動せず BCP 側へ委譲する（inc 内 after_setup_theme ガード）。
 */
require_once get_stylesheet_directory() . '/inc/table-extension.php';

require_once get_stylesheet_directory() . '/inc/ollie-i18n.php';

/**
 * jadeclinic.jp 専用：FB ドメイン認証 / JSON-LD / LP femcare のヘッダフッタ非表示。
 * 別サイト転用時はこの 1 行を消せば全部止まる。
 */
require_once get_stylesheet_directory() . '/inc/jadeclinic.php';
