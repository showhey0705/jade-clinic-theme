<?php
/**
 * テーマの自動更新チェッカー。
 *
 * ── なぜテーマにこれを入れるのか ──────────────────────────────
 *
 * クリニックごとにテーマのリポジトリを分けると、共通の改修を入れるたびに
 * サイトの数だけ cherry-pick とデプロイが要る。サイトが 10 院、50 院と
 * 増えた時点で確実に破綻する(2026-08-18 に jade → one-est の手動同期を
 * 実際にやってみて確認済み。131 ファイル中 125 が完全一致だった)。
 *
 * BCP プラグインは既にこの問題を解いている ——
 * Supabase Storage に zip と plugin.json を置き、Update Checker が
 * 定期的に取りに行く。テーマも同じ経路に載せれば、リポジトリは 1 本で済み、
 * サイト側は「更新があります」を押すだけになる。
 *
 * ── 使っているライブラリ ────────────────────────────────
 *
 * BCP と同じ YahnisElsts Plugin Update Checker v5.6。
 * このライブラリはプラグインとテーマの両方に対応していて、
 * PucFactory::buildUpdateChecker() に**テーマディレクトリ**を渡すと
 * Theme\UpdateChecker が返る。
 *
 * vendor/ は BCP から丸ごとコピーして同梱する(バージョンを揃えるため。
 * 別々に落とすと片方だけ上がって挙動がズレる)。
 *
 * ── メタデータ URL の決定順 ──────────────────────────────
 *
 *   1. 定数 VIP2026_UPDATE_URL       … wp-config.php で完全上書き
 *   2. フィルタ vip2026/update_metadata_url
 *   3. 定数 VIP2026_SUPABASE_URL     … テーマ同梱
 *   4. 定数 BCP_SUPABASE_URL         … BCP が入っていれば流用
 *   5. どれも無ければ無効(黙って何もしない)
 *
 * **option を見ないこと。** BCP は v1.54.5 まで option だけを見ていたため、
 * 設定画面を一度も保存していないサイト(= 素直に入れただけの顧客サイト)へ
 * 更新が一切届かないという事故を起こしている。同じ轍を踏まない。
 *
 * @package vip2026
 */

namespace VIP2026\Updates;

defined( 'ABSPATH' ) || exit;

/*
 * Supabase の既定 URL。
 *
 * BCP プラグインが beauty-clinic-patterns.php で BCP_SUPABASE_URL を同梱しているのと
 * 同じ考え方で、テーマ側にも既定値を持たせる。ここを持たないと
 * 「BCP を入れていないサイトには更新が一切届かない」ことになり、
 * テーマ単体で配る道が塞がる。
 *
 * wp-config.php で先に定義すれば上書きできる。
 */
if ( ! defined( 'VIP2026_SUPABASE_URL' ) ) {
	define( 'VIP2026_SUPABASE_URL', 'https://jhbzbqsondftxlcsevpn.supabase.co' );
}

/** Supabase Storage のバケット。BCP と同じものを間借りする。 */
const BUCKET = 'plugin-releases';

/** テーマ配信物の置き場所(バケット内のプレフィックス)。 */
const OBJECT_PREFIX = 'themes/vip2026';

/**
 * 配信チャネル。
 *
 * 全クライアントが同時に更新されると、CSS の一手で全サイトの見た目が
 * 同時に壊れる。テーマの自動更新がプラグインより怖いのはここ。
 * そこで stable と beta の 2 系統を置き、自社サイトを beta にしておく。
 *
 *   - stable : themes/vip2026/theme.json          … 既定。顧客サイト
 *   - beta   : themes/vip2026/beta/theme.json     … 先行検証用
 *
 * サイト側で beta にするには wp-config.php に:
 *   define( 'VIP2026_UPDATE_CHANNEL', 'beta' );
 */
function channel(): string {
	$channel = defined( 'VIP2026_UPDATE_CHANNEL' ) ? (string) VIP2026_UPDATE_CHANNEL : 'stable';
	$channel = (string) apply_filters( 'vip2026/update_channel', $channel );

	return 'beta' === $channel ? 'beta' : 'stable';
}

/**
 * Supabase のベース URL を解決する。
 *
 * テーマ単体でも動くよう VIP2026_SUPABASE_URL を先に見るが、
 * BCP が同居しているサイトでは BCP_SUPABASE_URL をそのまま流用できる。
 * 顧客サイトはたいてい両方入っているので、実質どちらかは必ず埋まる。
 */
function supabase_url(): string {
	$url = '';

	if ( defined( 'VIP2026_SUPABASE_URL' ) && VIP2026_SUPABASE_URL ) {
		$url = (string) VIP2026_SUPABASE_URL;
	} elseif ( defined( 'BCP_SUPABASE_URL' ) && BCP_SUPABASE_URL ) {
		$url = (string) BCP_SUPABASE_URL;
	}

	return rtrim( (string) apply_filters( 'vip2026/supabase_url', $url ), '/' );
}

/** 更新メタデータ(theme.json)の URL を組み立てる。 */
function metadata_url(): string {
	if ( defined( 'VIP2026_UPDATE_URL' ) && VIP2026_UPDATE_URL ) {
		return (string) VIP2026_UPDATE_URL;
	}

	$filtered = (string) apply_filters( 'vip2026/update_metadata_url', '' );
	if ( $filtered ) {
		return $filtered;
	}

	$base = supabase_url();
	if ( ! $base ) {
		return '';
	}

	$prefix = OBJECT_PREFIX . ( 'beta' === channel() ? '/beta' : '' );

	return sprintf(
		'%s/storage/v1/object/public/%s/%s/theme.json',
		$base,
		rawurlencode( BUCKET ),
		$prefix
	);
}

/**
 * 更新チェッカーを登録する。
 *
 * 子テーマとして動いているので、対象は get_stylesheet_directory()。
 * スラッグはディレクトリ名(vip2026)で、配信 zip もこの名前で展開される
 * 必要がある。ズレると WP が「別のテーマ」として二重にインストールする。
 */
function register(): void {
	$url = metadata_url();
	if ( ! $url ) {
		return;
	}

	$loader = get_stylesheet_directory() . '/vendor/plugin-update-checker/plugin-update-checker.php';
	if ( ! file_exists( $loader ) ) {
		return;
	}

	require_once $loader;

	$factory = '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory';
	if ( ! class_exists( $factory ) ) {
		return;
	}

	$checker = $factory::buildUpdateChecker(
		$url,
		get_stylesheet_directory(),
		get_stylesheet()
	);

	/*
	 * 更新チェックの間隔。既定は 12 時間で BCP と揃えてある。
	 * 短くしても Supabase 側の負荷はたかが知れているが、
	 * 顧客サイトの管理画面が毎回 HTTP を叩くのは行儀が悪い。
	 */
	if ( method_exists( $checker, 'getScheduler' ) && $checker->getScheduler() ) {
		$checker->getScheduler()->checkPeriod = (int) apply_filters( 'vip2026/update_check_period_hours', 12 );
	}
}
add_action( 'init', __NAMESPACE__ . '\\register', 5 );
