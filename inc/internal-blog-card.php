<?php
/**
 * 内部ブログカード(JADE Blog Card)
 *
 * 投稿エディタで「URL を単独の段落として貼り付け」たときに、
 * 同サイト内 URL の場合は埋め込み iframe ではなく、アイキャッチ・タイトル・
 * 抜粋・日付を含む整形済みカード HTML に置換するフィルタ群。
 *
 * 対応エントリポイント:
 *   1. `embed_oembed_html` — Gutenberg の URL 自動埋め込みが生成する
 *      `wp-embedded-content` iframe を、内部 URL の場合だけカードに置換。
 *   2. `the_content` — `<p>https://stg-...</p>` の単独段落(URL が
 *      自動 oEmbed されず素のままになっているケース)も検出して置換。
 *   3. ショートコード `[jade_blogcard url="..."]` — 編集者が任意のリンクを
 *      明示的にカード表示したい場合の手動エントリ。
 *
 * デザイン: assets/styles/internal-blog-card.css を遅延 enqueue。
 *
 * パフォーマンス: 単一 post ID に対する card HTML は `vip2026_card_{ID}`
 * という transient(12 時間)にキャッシュ。投稿保存時に当該 post と
 * その投稿を引用しうる他投稿の transient を flush する(後者は厳密でない
 * ので、site_transient ベースの「最後に flush した時刻」で軽くハンドリング)。
 *
 * セキュリティ:
 *   - URL は esc_url、テキストは esc_html
 *   - 外部ドメインは絶対にカード化しない(URL の host を home_url と照合)
 *
 * @package vip2026
 */

namespace VIP2026\InternalBlogCard;

const STYLE_HANDLE = 'vip2026-internal-blog-card';
const STYLE_REL    = '/assets/styles/internal-blog-card.css';
const CACHE_PREFIX = 'vip2026_card_';
const CACHE_TTL    = 12 * HOUR_IN_SECONDS;

/**
 * 同サイトの URL かどうか判定。
 */
function is_internal_url( string $url ): bool {
	$host  = wp_parse_url( $url, PHP_URL_HOST );
	$mine  = wp_parse_url( home_url(), PHP_URL_HOST );
	return $host && $mine && strtolower( $host ) === strtolower( $mine );
}

/**
 * URL から post を解決。
 *
 * `url_to_postid()` は permalink 構造を解釈するが、director-blog のような
 * カスタム投稿タイプも対応する(WP 5.5+)。
 */
function resolve_post( string $url ): ?\WP_Post {
	if ( ! is_internal_url( $url ) ) {
		return null;
	}
	$id = url_to_postid( $url );
	if ( ! $id ) {
		return null;
	}
	$p = get_post( $id );
	if ( ! $p || 'publish' !== $p->post_status ) {
		return null;
	}
	return $p;
}

/**
 * Gutenberg 埋め込みブロックの sandbox iframe(srcDoc)向け REST 経路かを判定。
 * 該当時は inline <style> をカード HTML に prepend し、iframe 内でも
 * フロントと同じ見た目になるようにする。
 */
function is_oembed_proxy_request(): bool {
	if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
		return false;
	}
	$route = isset( $GLOBALS['wp']->query_vars['rest_route'] )
		? (string) $GLOBALS['wp']->query_vars['rest_route']
		: '';
	return false !== strpos( $route, '/oembed/1.0/proxy' );
}

/**
 * テーマの CSS ファイルを 1 度だけ読み、文字列で返す(static cache)。
 * sandbox iframe には外部 CSS が届かないため inline 注入用。
 */
function read_inline_css(): string {
	static $css = null;
	if ( null === $css ) {
		$path = get_stylesheet_directory() . STYLE_REL;
		$css  = is_readable( $path ) ? (string) file_get_contents( $path ) : '';
	}
	return $css;
}

/**
 * WP がテーマ/theme.json から生成するグローバル CSS(--wp--preset--* 定義を含む)。
 * iframe 内で var(--wp--preset--*) を解決させるために必要。
 */
function read_global_stylesheet_css(): string {
	static $global = null;
	if ( null === $global ) {
		$global = function_exists( 'wp_get_global_stylesheet' )
			? (string) wp_get_global_stylesheet()
			: '';
	}
	return $global;
}

/**
 * iframe デフォルトの body margin を相殺する最小リセット。
 */
const IFRAME_RESET_CSS = 'html,body{margin:0;padding:0;background:transparent;font-family:inherit;}';

/**
 * カード HTML を生成。post から featured image / title / excerpt / date を取り出す。
 */
function build_card_html( \WP_Post $post ): string {
	$is_oembed_proxy = is_oembed_proxy_request();
	$cache_key       = CACHE_PREFIX . $post->ID;
	// REST oembed proxy 経路では <style> 付き HTML を返すため transient を使わない。
	if ( ! $is_oembed_proxy ) {
		$cached = get_transient( $cache_key );
		if ( is_string( $cached ) && '' !== $cached ) {
			return $cached;
		}
	}

	$url      = get_permalink( $post );
	$title    = get_the_title( $post );
	$date     = mysql2date( 'Y.m.d', $post->post_date );
	$excerpt  = trim( wp_strip_all_tags( get_the_excerpt( $post ) ) );
	if ( '' === $excerpt ) {
		$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 40, '…' );
	}

	// アイキャッチ。CPT director-blog 用には webp サムネを優先表示。
	$thumb_html = '';
	if ( has_post_thumbnail( $post ) ) {
		$thumb_html = get_the_post_thumbnail(
			$post,
			'medium_large',
			array(
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => '',
				'class'    => 'vip2026-blogcard__img',
			)
		);
	}

	// クラスタ用ラベル(dr-tags の最初の term 名 or post_type 表示名)
	$label = '';
	$tax   = ( 'director-blog' === $post->post_type ) ? 'dr-tags' : '';
	if ( $tax ) {
		$terms = get_the_terms( $post, $tax );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$label = esc_html( $terms[0]->name );
		}
	}
	if ( '' === $label ) {
		$pto = get_post_type_object( $post->post_type );
		if ( $pto ) {
			$label = esc_html( $pto->labels->singular_name );
		}
	}

	$html  = '<aside class="vip2026-blogcard" data-post-type="' . esc_attr( $post->post_type ) . '">';
	$html .= '<a class="vip2026-blogcard__link" href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $title ) . '">';

	if ( $thumb_html ) {
		$html .= '<span class="vip2026-blogcard__thumb">' . $thumb_html . '</span>';
	} else {
		$html .= '<span class="vip2026-blogcard__thumb vip2026-blogcard__thumb--placeholder" aria-hidden="true"></span>';
	}

	$html .= '<span class="vip2026-blogcard__body">';
	$html .= '<span class="vip2026-blogcard__meta">';
	if ( $label ) {
		$html .= '<span class="vip2026-blogcard__label">' . $label . '</span>';
	}
	$html .= '<span class="vip2026-blogcard__date">' . esc_html( $date ) . '</span>';
	$html .= '</span>';
	$html .= '<span class="vip2026-blogcard__title">' . esc_html( $title ) . '</span>';
	if ( $excerpt ) {
		$html .= '<span class="vip2026-blogcard__excerpt">' . esc_html( $excerpt ) . '</span>';
	}
	$html .= '<span class="vip2026-blogcard__cta">この記事を読む <span aria-hidden="true">→</span></span>';
	$html .= '</span>'; // body
	$html .= '</a>';
	$html .= '</aside>';

	/**
	 * カスタマイズ用フィルタ。デザインを別途差し替えたい場合に使用。
	 */
	$html = (string) apply_filters( 'vip2026/blogcard_html', $html, $post );

	if ( $is_oembed_proxy ) {
		$inline_css = read_inline_css();
		$global_css = read_global_stylesheet_css();
		if ( '' !== $inline_css || '' !== $global_css ) {
			$html = '<style id="vip2026-blogcard-inline">'
				. IFRAME_RESET_CSS
				. $global_css
				. $inline_css
				. '</style>'
				. $html;
		}
		// REST 経路はキャッシュバイパス。フロント側のキャッシュは <style> 無しを保持。
		return $html;
	}

	set_transient( $cache_key, $html, CACHE_TTL );
	return $html;
}

/**
 * 1) `embed_oembed_html` — Gutenberg の自動埋め込み iframe を内部 URL の時だけ
 * カードに置き換える。フロント描画タイミングなのでフィルタコストは低い。
 */
function filter_oembed_html( string $cache, string $url, array $attr, int $post_id ): string {
	if ( is_admin() && ! wp_doing_ajax() ) {
		// エディタ側ではプレビューを崩さないため標準動作のまま。
		return $cache;
	}
	$post = resolve_post( $url );
	if ( ! $post ) {
		return $cache;
	}
	maybe_enqueue_style();
	return build_card_html( $post );
}
add_filter( 'embed_oembed_html', __NAMESPACE__ . '\filter_oembed_html', 10, 4 );

/**
 * 1b) `oembed_response_data` — Gutenberg エディタの `/oembed/1.0/proxy` 経由。
 * 内部 URL は WP_oEmbed_Controller::get_proxy_item() が
 * get_oembed_response_data_for_url() でショートカット返却するため
 * embed_oembed_html は通らない。代わりに `oembed_response_data` の `html`
 * を差し替えてサンドボックス iframe にカードを描画させる。
 *
 * 優先度 20 = 既定の get_oembed_response_data_rich(10) より後に発火。
 */
function filter_oembed_response_data( $data, $post, $width, $height ): array {
	if ( ! is_oembed_proxy_request() ) {
		return (array) $data;
	}
	if ( ! $post instanceof \WP_Post || 'publish' !== $post->post_status ) {
		return (array) $data;
	}
	$card = build_card_html( $post );
	if ( '' === $card ) {
		return (array) $data;
	}
	$data         = (array) $data;
	$data['type'] = 'rich';
	$data['html'] = $card;
	// iframe 初期高さの目安(100px サムネ + 内側余白)。
	$data['height'] = 160;
	return $data;
}
add_filter( 'oembed_response_data', __NAMESPACE__ . '\filter_oembed_response_data', 20, 4 );

/**
 * 2) `the_content` — 「<p>https://.../director-blog/.../</p>」のように
 * リンクすら付かない素の URL 段落をカード化する。oEmbed が走っていない
 * ケース(古いキャッシュ / 外部 oEmbed 失敗 等)の保険。
 */
function filter_the_content( string $content ): string {
	if ( false === stripos( $content, 'http' ) ) {
		return $content;
	}

	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( ! $site_host ) {
		return $content;
	}

	$pattern = '#<p[^>]*>\s*(https?://' . preg_quote( $site_host, '#' ) . '/[^\s<]+?)\s*</p>#i';

	return (string) preg_replace_callback(
		$pattern,
		function ( $m ) {
			$url  = $m[1];
			$post = resolve_post( $url );
			if ( ! $post ) {
				return $m[0];
			}
			maybe_enqueue_style();
			return build_card_html( $post );
		},
		$content
	);
}
add_filter( 'the_content', __NAMESPACE__ . '\filter_the_content', 9 ); // wpautop(10) より早く

/**
 * 3) ショートコード `[jade_blogcard url="..."]` / `[jade_blogcard id="123"]`
 */
function shortcode( $atts ): string {
	$atts = shortcode_atts(
		array(
			'url' => '',
			'id'  => 0,
		),
		$atts,
		'jade_blogcard'
	);

	$post = null;
	if ( ! empty( $atts['id'] ) ) {
		$post = get_post( (int) $atts['id'] );
	} elseif ( ! empty( $atts['url'] ) ) {
		$post = resolve_post( (string) $atts['url'] );
	}
	if ( ! $post || 'publish' !== $post->post_status ) {
		return '';
	}
	maybe_enqueue_style();
	return build_card_html( $post );
}
add_shortcode( 'jade_blogcard', __NAMESPACE__ . '\shortcode' );

/**
 * CSS は使われたページでだけ enqueue する。
 * カードビルド時にこのフラグを立て、wp_footer で wp_enqueue_style を遅延登録する。
 *
 * (シンプル実装: 1 リクエスト中に少なくとも 1 度カードが描画されたら、
 * 静的フラグを立てる。 wp_head 段階では確定しないため、印字位置は footer。
 * フロントは defer ロードのリンク要素1つで十分軽い。)
 */
function maybe_enqueue_style(): void {
	static $hooked = false;
	if ( $hooked ) {
		return;
	}
	$hooked = true;
	add_action( 'wp_footer', __NAMESPACE__ . '\print_style_link', 0 );
}

function print_style_link(): void {
	$src = get_stylesheet_directory_uri() . STYLE_REL;
	$ver = (string) wp_get_theme()->get( 'Version' );
	printf(
		'<link rel="stylesheet" id="%s" href="%s?ver=%s" media="all" />' . "\n",
		esc_attr( STYLE_HANDLE ),
		esc_url( $src ),
		esc_attr( $ver )
	);
}

/**
 * 投稿保存時に当該 post の transient を破棄する。
 *
 * 他投稿に貼られたカードのキャッシュは個別に破棄しないが、各カードは
 * 投稿保存タイミングで自然に更新される(自分の transient を消すため)。
 * 引用元側に貼られたカードはタイトル/抜粋変更後最大 12 時間ズレるが、
 * パフォーマンス trade-off として許容。
 */
function flush_cache( int $post_id ): void {
	delete_transient( CACHE_PREFIX . $post_id );
}
add_action( 'save_post', __NAMESPACE__ . '\flush_cache' );
add_action( 'deleted_post', __NAMESPACE__ . '\flush_cache' );
