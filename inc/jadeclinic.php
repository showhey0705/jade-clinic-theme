<?php
/**
 * jadeclinic.jp 専用カスタマイズ。
 *
 * vip2026 は汎用 Ollie 子テーマだが、現状は jadeclinic.jp 専用デプロイ。
 * クリニック固有の SEO/トラッキング/LP 制御をここに集約する。
 * 別サイトへ転用する際は functions.php の require_once を外せば全部止まる。
 *
 * @package vip2026
 */

namespace VIP2026\JadeClinic;

const FACEBOOK_DOMAIN_VERIFICATION = 'ipp97vpf9catcvoxwmfi8bnnzgszj8';

/**
 * Adobe Fonts Kit ID をスターターのデフォルト（空文字）から上書きする。
 *
 * `functions.php` の `enqueue_typekit()` が `vip2026/typekit_kit` フィルタを通すので、
 * サイト固有ファイルでは Kit ID を返すだけでよい。空文字を返せば Typekit 読み込みは
 * スキップされる。
 */
add_filter( 'vip2026/typekit_kit', static function (): string {
	return 'bzy5pnl';
} );

/**
 * Facebook Meta Pixel ドメイン認証 meta。
 */
function facebook_domain_verification(): void {
	printf(
		'<meta name="facebook-domain-verification" content="%s" />' . "\n",
		esc_attr( FACEBOOK_DOMAIN_VERIFICATION )
	);
}
add_action( 'wp_head', __NAMESPACE__ . '\facebook_domain_verification', 1 );

/**
 * MedicalClinic 構造化データ（JSON-LD）。
 *
 * サイト全体に効かせる院情報。営業時間や住所が変わる時はここを直す。
 */
function structured_data(): void {
	$data = array(
		'@context'                 => 'https://schema.org',
		'@type'                    => 'MedicalClinic',
		'name'                     => 'JADE CLINIC.',
		'url'                      => 'https://jadeclinic.jp/',
		'telephone'                => '092-753-5450',
		'address'                  => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '大濠公園2-35 THE APARTMENT 2A',
			'addressLocality' => '福岡市中央区',
			'addressRegion'   => '福岡県',
			'postalCode'      => '810-0051',
			'addressCountry'  => 'JP',
		),
		'geo'                      => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => 33.5900924,
			'longitude' => 130.3761308,
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '09:20',
				'closes'    => '18:00',
			),
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => 'Saturday',
				'opens'     => '09:00',
				'closes'    => '15:00',
			),
		),
		'medicalSpecialty'         => array( 'Dermatology', 'PlasticSurgery' ),
		'availableService'         => array(
			array( '@type' => 'MedicalProcedure', 'name' => 'しみ治療（ピコレーザー）' ),
			array( '@type' => 'MedicalProcedure', 'name' => 'ピコトーニング' ),
			array( '@type' => 'MedicalProcedure', 'name' => '糸リフト・フェイスリフト' ),
			array( '@type' => 'MedicalProcedure', 'name' => 'HIFU（ハイフ）' ),
			array( '@type' => 'MedicalProcedure', 'name' => 'ボトックス注射' ),
			array( '@type' => 'MedicalProcedure', 'name' => 'ヒアルロン酸注入' ),
			array( '@type' => 'MedicalProcedure', 'name' => '水光注射' ),
			array( '@type' => 'MedicalProcedure', 'name' => '幹細胞治療' ),
		),
		'priceRange'               => '¥¥',
		'paymentAccepted'          => 'クレジットカード, 現金',
		'areaServed'               => array(
			'@type' => 'City',
			'name'  => '福岡市',
		),
		'sameAs'                   => array(
			'https://www.instagram.com/jadeclinic.o/',
		),
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);
}
add_action( 'wp_head', __NAMESPACE__ . '\structured_data' );

/**
 * LP femcare ページではテーマ UI（ヘッダー/フッター/ナビ）を非表示。
 *
 * 単独 LP として使うため、テンプレートパート由来のヘッダー/フッターを CSS で
 * 強制非表示にする。/femcare/ 固定ページでのみ発火。
 */
function femcare_hide_chrome(): void {
	if ( ! is_page( 'femcare' ) ) {
		return;
	}

	echo '<style id="vip2026-femcare-hide-chrome">'
		. 'header,.site-header,.wp-site-header,'
		. 'footer,.site-footer,.wp-site-footer,'
		. 'nav,.wp-site-navigation,'
		. '.wp-block-template-part,'
		. '#masthead,#colophon,'
		. '.admin-bar #wpadminbar{display:none!important}'
		. 'html{margin-top:0!important}'
		. 'body{background:#faf9f7}'
		. '.wp-site-blocks>header,.wp-site-blocks>footer{display:none!important}'
		. '.entry-content,.wp-block-post-content{max-width:100%!important;padding:0!important;margin:0!important}'
		. '.wp-site-blocks{padding-top:0!important}'
		. '</style>' . "\n";
}
add_action( 'wp_head', __NAMESPACE__ . '\femcare_hide_chrome' );
