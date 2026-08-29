<?php
/**
 * one est 専用カスタマイズ。
 *
 * 有効化方法（どちらか）:
 *   - wp-config.php に `define( 'VIP2026_SITE', 'one-est' );` を追加  ← 設定済み
 *   - オプション `vip2026_site_slug` に 'one-est' を設定
 *
 * one est は **美容サロン**（東京・三田／オーナー1名）。
 * BCP プラグインとテーマは美容クリニック向けに作られているため、
 * このファイルで「サロンとして自然に見える」ように上書きする。
 *
 * ここに書いてよいもの:
 *   - 管理画面 / フロントの文言の言い換え（プラグイン本体は絶対に編集しない）
 *   - 構造化データ・トラッキングなど、このサイト固有の head 出力
 *   - 使わない管理メニューの非表示
 *
 * サロン名・住所・電話番号・営業時間は **BCP の設定（クリニック情報）を正** とし、
 * ここでは読み取るだけにする。設定を直せばこのファイルを触らずに追従する。
 *
 * @package vip2026
 */

namespace VIP2026\OneEst;

defined( 'ABSPATH' ) || exit;

/**
 * 地図 iframe の title（inc/accessibility.php がこのフィルタで文言を受け取る）。
 */
add_filter( 'vip2026/a11y/map_iframe_title', static function (): string {
	return '地図: one est へのアクセス';
} );

/*
 * =============================================================
 * 1. 文言の言い換え（クリニック → サロン）
 * =============================================================
 *
 * BCP は医療機関向けなので「診療」「院長」「患者」「症例」といった言葉が
 * 数百箇所に散っている。サロンでこのまま出すと不自然なだけでなく、
 * 医療機関と誤認させる表現になりかねない。
 *
 * 方針は 2 段構え:
 *   (a) 画面に大きく出る短いラベルは EXACT で 1:1 に差し替える
 *   (b) 長い説明文は WORDS で単語単位に置換する
 *
 * どちらも gettext フィルタなので **プラグインのファイルは 1 行も変えない**。
 * プラグインを更新しても、この言い換えは効いたままになる。
 *
 * 注意: 「施術」はサロンでも普通に使う言葉なので置換しない
 *       （「施術前 / 施術後」を壊さないためでもある）。
 */

/** 完全一致で差し替えるラベル。左が BCP の原文、右が one est での表示。 */
const EXACT = array(
	// 管理メニュー / 投稿タイプ
	'美容クリニック'     => 'サロン管理',
	'美容クリニックパターン' => 'サロン設定',
	'施術'               => '施術メニュー',
	'症例'               => '施術例',
	'プラン'             => 'コース',
	'院長ブログ'         => 'ブログ',
	// 設定画面まわり
	'クリニック情報'     => 'サロン情報',
	'医院の基本情報'     => 'サロンの基本情報',
	'医院名'             => 'サロン名',
	'医院名 (カナ)'      => 'サロン名 (カナ)',
	'医院名 (英語)'      => 'サロン名 (英語)',
	'院長名'             => 'オーナー名',
	'診療時間'           => '営業時間',
	'診療時間の表記'     => '営業時間の表記',
	'休診日'             => '定休日',
);

/**
 * 部分一致で置換する語。長いものから順に並べること（strtr は最長一致）。
 *
 * 「治療 → 施術」は表記を整えるだけでなく、サロンが医療行為をうたっていると
 * 受け取られないようにする意味もある（薬機法）。
 */
const WORDS = array(
	'診療時間'   => '営業時間',
	'診療日'     => '営業日',
	'休診日'     => '定休日',
	'診療'       => '営業',
	'院長'       => 'オーナー',
	'医院'       => 'サロン',
	'クリニック' => 'サロン',
	'患者さま'   => 'お客さま',
	'患者様'     => 'お客様',
	'患者'       => 'お客様',
	'来院者'     => 'ご来店のお客さま',
	'来院'       => 'ご来店',
	'通院'       => 'ご来店',
	'症例'       => '施術例',
	'治療'       => '施術',
);

/**
 * BCP の文言を one est 向けに差し替える。
 *
 * @param string $translated 翻訳後の文字列。
 * @param string $original   原文。
 * @param string $domain     テキストドメイン。
 * @return string
 */
function relabel( string $translated, string $original, string $domain ): string {
	if ( 'beauty-clinic-patterns' !== $domain ) {
		return $translated;
	}

	if ( isset( EXACT[ $translated ] ) ) {
		return EXACT[ $translated ];
	}

	// 対象語を含まない文字列が大半なので、含むときだけ strtr を走らせる。
	foreach ( WORDS as $from => $_ ) {
		if ( false !== strpos( $translated, $from ) ) {
			return strtr( $translated, WORDS );
		}
	}

	return $translated;
}
add_filter( 'gettext', __NAMESPACE__ . '\relabel', 20, 3 );

/**
 * 文脈つき翻訳（_x）も同じ規則で差し替える。
 *
 * @param string $translated 翻訳後の文字列。
 * @param string $original   原文。
 * @param string $context    文脈。
 * @param string $domain     テキストドメイン。
 * @return string
 */
function relabel_with_context( string $translated, string $original, string $context, string $domain ): string {
	return relabel( $translated, $original, $domain );
}
add_filter( 'gettext_with_context', __NAMESPACE__ . '\relabel_with_context', 20, 4 );

/**
 * 使わない管理メニューを隠す。
 *
 * one est はオーナー 1 名なので「スタッフ」は登録しない。
 * 投稿タイプ自体は残す（消すと BCP のブロックが参照先を失うため）。
 */
function hide_unused_menus(): void {
	remove_menu_page( 'edit.php?post_type=clinic_staff' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\hide_unused_menus', 999 );

/*
 * =============================================================
 * 2. 構造化データ（JSON-LD）
 * =============================================================
 *
 * サロンなので schema.org の **BeautySalon** を使う。
 * MedicalClinic（jadeclinic 側）を流用すると、検索エンジンに医療機関として
 * 認識されるため使わない。
 *
 * サロン名 / 電話 / 住所は BCP の設定から読む。設定を直せばここも追従する。
 * 営業時間・座標・SNS だけはこのファイルで持つ（変わったらここを直す）。
 */

/**
 * BCP に登録されたサロン情報を取り出す。
 *
 * @return array<string,string>
 */
function salon_info(): array {
	$opts = (array) get_option( 'beauty_clinic_settings', array() );

	return (array) ( $opts['clinic'] ?? array() );
}

/**
 * BeautySalon 構造化データ。
 */
function structured_data(): void {
	$c = salon_info();

	$name = (string) ( $c['name'] ?? '' );
	if ( '' === $name ) {
		return; // 設定が空なら何も出さない。
	}

	$data = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'BeautySalon',
		'name'      => $name,
		'url'       => home_url( '/' ),
		'telephone' => (string) ( $c['tel'] ?? '' ),
		'address'   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '芝4丁目2-10 ラグーナ三田1102',
			'addressLocality' => '港区',
			'addressRegion'   => '東京都',
			'addressCountry'  => 'JP',
			// TODO: 郵便番号を確認して追加する（'postalCode' => '108-00xx'）。
		),
		'geo'       => array(
			// BCP 設定の Google マップ埋め込み URL に入っている座標。
			'@type'     => 'GeoCoordinates',
			'latitude'  => 35.6493293,
			'longitude' => 139.7477560,
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '10:00',
				'closes'    => '20:00',
			),
		),
		'priceRange' => '¥¥',
		'areaServed' => array(
			'@type' => 'City',
			'name'  => '東京都港区',
		),
		// TODO: Instagram / Google ビジネスプロフィール の URL が決まったら追加する。
		// 'sameAs' => array( 'https://www.instagram.com/...' ),
	);

	// アクセス説明が入っていれば添える。
	if ( ! empty( $c['access'] ) ) {
		$data['publicAccess'] = true;
		$data['description']  = (string) $c['access'];
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);
}
add_action( 'wp_head', __NAMESPACE__ . '\structured_data' );

/*
 * Facebook のドメイン認証 meta は one est では使わない。
 * 必要になったら Business Manager でコードを取得し、jadeclinic.php の
 * facebook_domain_verification() を参考に追加する。
 */
