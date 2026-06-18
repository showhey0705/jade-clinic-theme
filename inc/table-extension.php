<?php
/**
 * core/table の拡張（SWELL のテーブル設定を参考に、テーマ非依存で再実装）。
 *
 * 由来: beauty-clinic-patterns プラグインの BCP\Table_Extension を vip2026 子テーマへ
 *       移植したもの。新規ブロックは作らず、コア `core/table` に独自 attribute を足して
 *       `render_block_core/table` で出力 HTML を後加工する。CSS クラス（bcp-*）と
 *       data 属性（data-bcp-*）、リッチテキストのフォーマット名（bcp/cell-*）は、
 *       既存コンテンツとの互換のため**そのまま据え置く**（JS/CSS の契約を壊さない）。
 *
 * 競合回避: BCP プラグインが有効なサイト（jadeclinic）では BCP\Table_Extension が同じ
 *           フィルタ/スタイルを登録するため、本クラスは **BCP が無い時だけ** 起動する
 *           （末尾の after_setup_theme ガード参照）。これにより BCP 製品側を一切変更せず、
 *           BCP 非導入サイト（photoshopvip / starter 等）にも同じ機能を提供できる。
 *
 * 提供する設定（エディタ Inspector「テーブル設定」「横スクロール設定」）:
 *   - bcpScrollable     : ''|'sp'|'pc'|'both'  横スクロール
 *   - bcpScrollHint     : bool                 「スクロールできます」ガイド
 *   - bcpFixedHead      : ''|'sp'|'both'        thead 固定
 *   - bcpFixedFirstCol  : bool                 1 列目固定
 *   - bcpFirstColTh / bcpStack / bcpMinCol / bcpCol1Width / bcpCol1Bg / bcpCol1Text ほか
 * リッチテキスト: セル記号（bcp/cell-mark）/ ボタン化（bcp/cell-button）/ 星評価（bcp/cell-stars）
 *
 * 規約: 色・余白はハードコードせず theme.json トークン（var(--wp--preset--*)）に委譲。
 *
 * @package vip2026
 */

namespace VIP2026\TableExtension;

defined( 'ABSPATH' ) || exit;

final class Table_Extension {

	const HANDLE = 'vip2026-table-extension';

	private static $instance = null;

	/** 1 リクエストで front CSS を一度だけ enqueue するためのガード。 */
	private static $style_enqueued = false;

	/** view スクリプトを一度だけ enqueue するためのガード。 */
	private static $view_enqueued = false;

	/** テーマディレクトリの絶対パス（末尾スラッシュ付き）。 */
	private static function path(): string {
		return \trailingslashit( \get_stylesheet_directory() );
	}

	/** テーマディレクトリの URL（末尾スラッシュ付き）。 */
	private static function url(): string {
		return \trailingslashit( \get_stylesheet_directory_uri() );
	}

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		\add_filter( 'register_block_type_args', [ $this, 'register_attrs' ], 10, 2 );
		\add_filter( 'render_block_core/table', [ $this, 'render_table' ], 10, 2 );
		\add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
		\add_action( 'init', [ $this, 'register_front_style' ] );
		\add_action( 'init', [ $this, 'register_block_styles' ] );
	}

	/**
	 * core/table にブロックスタイル（シンプル / ダブルライン / 商品比較）を追加。
	 * ストライプはコア標準（is-style-stripes）があるため重複登録しない。
	 */
	public function register_block_styles(): void {
		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}
		\register_block_style( 'core/table', [
			'name'  => 'bcp-simple',
			'label' => \__( 'シンプル', 'vip2026' ),
		] );
		\register_block_style( 'core/table', [
			'name'  => 'bcp-double',
			'label' => \__( 'ダブルライン', 'vip2026' ),
		] );
		\register_block_style( 'core/table', [
			'name'  => 'bcp-product',
			'label' => \__( '商品比較（画像ヘッダー）', 'vip2026' ),
		] );
	}

	/**
	 * core/table に独自 attribute を登録する。
	 * これをしないと parse_blocks/serialize_blocks のラウンドトリップで attribute が失われる。
	 *
	 * @param array  $args block_type args.
	 * @param string $name block name.
	 * @return array
	 */
	public function register_attrs( $args, $name ) {
		if ( 'core/table' !== $name ) {
			return $args;
		}
		if ( ! isset( $args['attributes'] ) || ! is_array( $args['attributes'] ) ) {
			$args['attributes'] = [];
		}
		$args['attributes']['bcpScrollable']    = [ 'type' => 'string', 'default' => '' ];
		$args['attributes']['bcpScrollHint']    = [ 'type' => 'boolean', 'default' => true ];
		$args['attributes']['bcpTableWidth']    = [ 'type' => 'string', 'default' => '' ];
		$args['attributes']['bcpFixedHead']     = [ 'type' => 'string', 'default' => '' ];
		$args['attributes']['bcpFixedFirstCol'] = [ 'type' => 'boolean', 'default' => false ];
		$args['attributes']['bcpFirstColTh']    = [ 'type' => 'boolean', 'default' => false ];
		$args['attributes']['bcpStack']         = [ 'type' => 'boolean', 'default' => false ];
		$args['attributes']['bcpMinCol']        = [ 'type' => 'string', 'default' => '' ];
		$args['attributes']['bcpCol1Width']     = [ 'type' => 'string', 'default' => '' ];
		$args['attributes']['bcpCol1Bg']        = [ 'type' => 'string', 'default' => '' ];
		$args['attributes']['bcpCol1Text']      = [ 'type' => 'string', 'default' => '' ];
		return $args;
	}

	/**
	 * core/table の出力に data-* とスクロールガイドを差し込む。
	 *
	 * @param string $block_content ブロック出力 HTML。
	 * @param array  $block         パース済みブロック。
	 * @return string
	 */
	public function render_table( $block_content, $block ) {
		$attrs = $block['attrs'] ?? [];

		$scrollable = isset( $attrs['bcpScrollable'] ) ? (string) $attrs['bcpScrollable'] : '';
		$theadfix   = isset( $attrs['bcpFixedHead'] ) ? (string) $attrs['bcpFixedHead'] : '';
		$cell1fix   = ! empty( $attrs['bcpFixedFirstCol'] );
		$show_hint  = ! isset( $attrs['bcpScrollHint'] ) || (bool) $attrs['bcpScrollHint'];
		$firstcolth = ! empty( $attrs['bcpFirstColTh'] );
		$stack      = ! empty( $attrs['bcpStack'] );

		// 1 列目（見出し列）の幅（colgroup 用）。許可: 数値+(% / px / em / rem) または auto。
		$col1_width = isset( $attrs['bcpCol1Width'] ) ? trim( (string) $attrs['bcpCol1Width'] ) : '';
		if ( '' !== $col1_width && ! preg_match( '/^(auto|[0-9]+(\.[0-9]+)?(%|px|em|rem))$/', $col1_width ) ) {
			$col1_width = '';
		}
		$has_colw = '' !== $col1_width;

		// 1 列目（見出し列）の背景色・文字色。許可: hex / rgb(a) / var(--…)。
		$color_ok = static function ( $c ) {
			$c = trim( (string) $c );
			return ( '' !== $c && preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([0-9.,%\s]+\)|var\(--[a-zA-Z0-9-]+\))$/', $c ) ) ? $c : '';
		};
		$col1_bg     = $color_ok( $attrs['bcpCol1Bg'] ?? '' );
		$col1_text   = $color_ok( $attrs['bcpCol1Text'] ?? '' );
		$has_col1col = ( '' !== $col1_bg ) || ( '' !== $col1_text );

		$valid_scroll = [ 'sp', 'pc', 'both' ];
		$valid_thead  = [ 'sp', 'both' ];
		$valid_mincol = [ '10', '20', '30' ];
		$scrollable   = in_array( $scrollable, $valid_scroll, true ) ? $scrollable : '';
		$theadfix     = in_array( $theadfix, $valid_thead, true ) ? $theadfix : '';
		$mincol       = isset( $attrs['bcpMinCol'] ) ? (string) $attrs['bcpMinCol'] : '';
		$mincol       = in_array( $mincol, $valid_mincol, true ) ? $mincol : '';
		$table_w      = isset( $attrs['bcpTableWidth'] ) ? preg_replace( '/[^0-9]/', '', (string) $attrs['bcpTableWidth'] ) : '';

		$class_name    = isset( $attrs['className'] ) ? (string) $attrs['className'] : '';
		$has_bcp_style = ( false !== strpos( $class_name, 'is-style-bcp-' ) );
		$has_mark      = ( false !== strpos( $block_content, 'bcp-cell-mark' ) );

		// 何の拡張も無ければ完全素通り（他テーブルに無影響）。
		// 色は「見出し列にする」が ON の時だけ適用（UI と挙動を一致させる）。
		$apply_col1col = $has_col1col && $firstcolth;

		$has_feature = ( '' !== $scrollable ) || ( '' !== $theadfix ) || $firstcolth || $stack
			|| ( '' !== $mincol ) || $has_colw || $apply_col1col || $has_bcp_style || $has_mark;
		if ( ! $has_feature ) {
			return $block_content;
		}

		// 1 列目の背景色・文字色: figure に CSS 変数を付与（CSS が 1 列目セルに適用）。
		if ( $apply_col1col ) {
			$vars = '';
			if ( '' !== $col1_bg ) {
				$vars .= '--bcp-col1-bg:' . $col1_bg . ';';
			}
			if ( '' !== $col1_text ) {
				$vars .= '--bcp-col1-text:' . $col1_text . ';';
			}
			$block_content = preg_replace( '/<figure\b/', '<figure style="' . esc_attr( $vars ) . '"', $block_content, 1 );
		}

		// 1 列目の幅: <colgroup> に最初の <col> だけ width 指定（残り列は自動・均等）。
		if ( $has_colw ) {
			$block_content = preg_replace(
				'/(<table\b[^>]*>)/',
				'$1<colgroup><col style="width:' . esc_attr( $col1_width ) . '"></colgroup>',
				$block_content,
				1
			);
		}

		// figure に付与する属性を組み立て（スクロール非依存のものも含む）。
		$props = '';
		if ( '' !== $scrollable ) {
			$props .= ' data-bcp-scrollable="' . esc_attr( $scrollable ) . '"';
			if ( $cell1fix ) {
				$props .= ' data-bcp-cell1fixed="' . esc_attr( $scrollable ) . '"';
			}
		}
		if ( '' !== $theadfix ) {
			$props .= ' data-bcp-theadfix="' . esc_attr( $theadfix ) . '"';
		}
		if ( $firstcolth ) {
			$props .= ' data-bcp-firstcol-th="1"';
		}
		if ( $stack ) {
			$props .= ' data-bcp-stack="sp"';
		}
		if ( '' !== $mincol ) {
			$props .= ' data-bcp-mincol="' . esc_attr( $mincol ) . '"';
		}

		if ( '' !== $props ) {
			$block_content = preg_replace( '/<figure\b/', '<figure' . $props, $block_content, 1 );
		}

		// スマホ縦並び時に「どの列（プラン）の値か」を示すため、
		// 各ボディセルへ列見出しを data-bcp-label として付与する。
		if ( $stack ) {
			$block_content = $this->inject_stack_labels( $block_content );
		}

		// テーブル横幅（スクロール時のみ）。<table> にインライン CSS 変数を付与。
		if ( '' !== $scrollable && '' !== $table_w ) {
			$block_content = preg_replace( '/<table\b/', '<table style="--bcp-table-width:' . esc_attr( $table_w ) . 'px"', $block_content, 1 );
		}

		// 横スクロール時は <table> を内側ラッパーで包む。
		// ガイドはスクロールしない figure 側に置くため、スクロールするのはこのラッパーだけにする。
		if ( '' !== $scrollable ) {
			$block_content = preg_replace(
				'/(<table\b.*?<\/table>)/s',
				'<div class="bcp-table-scroll" data-bcp-scrollable="' . esc_attr( $scrollable ) . '">$1</div>',
				$block_content,
				1
			);
		}

		// スクロール時の付加要素（ガイド + 左右フェード）を figure 末尾に追加。
		if ( '' !== $scrollable ) {
			$tail = '';
			if ( $show_hint ) {
				$tail .= '<div class="bcp-table-scrollhint" data-bcp-scrollable="' . esc_attr( $scrollable ) . '" aria-hidden="true"><span class="bcp-table-scrollhint__inner">'
					. esc_html__( 'スクロールできます', 'vip2026' )
					. '<svg class="bcp-table-scrollhint__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
					. '</span></div>';
			}
			$tail .= '<span class="bcp-fade bcp-fade-left" aria-hidden="true"></span>'
				. '<span class="bcp-fade bcp-fade-right" aria-hidden="true"></span>';
			$block_content = preg_replace( '/<\/figure>/', $tail . '</figure>', $block_content, 1 );
		}

		$this->maybe_enqueue_front_style();

		// view スクリプト（フェード/ヒント制御・固定列幅測定）。
		if ( '' !== $scrollable ) {
			$this->maybe_enqueue_view();
		}

		return $block_content;
	}

	/** 1 列目固定の左フェード用に固定列幅を測る view スクリプトを遅延 enqueue。 */
	private function maybe_enqueue_view(): void {
		if ( self::$view_enqueued ) {
			return;
		}
		$path = self::path() . 'assets/js/table-view.js';
		if ( ! file_exists( $path ) ) {
			return;
		}
		\wp_enqueue_script(
			self::HANDLE . '-view',
			self::url() . 'assets/js/table-view.js',
			[],
			(int) filemtime( $path ),
			[ 'in_footer' => true, 'strategy' => 'defer' ]
		);
		self::$view_enqueued = true;
	}

	/**
	 * スマホ縦並び用に、各ボディセルへ列見出し（プラン名）を data-bcp-label として付与。
	 *
	 * @param string $html ブロック出力 HTML。
	 * @return string
	 */
	private function inject_stack_labels( string $html ): string {
		// ヘッダーの列ラベルを取得。
		if ( ! preg_match( '/<thead\b[^>]*>(.*?)<\/thead>/s', $html, $hm ) ) {
			return $html;
		}
		if ( ! preg_match_all( '/<(th|td)\b[^>]*>(.*?)<\/\1>/s', $hm[1], $cells ) ) {
			return $html;
		}
		$labels = array_map(
			static function ( $c ) {
				return trim( wp_strip_all_tags( $c ) );
			},
			$cells[2]
		);
		if ( empty( $labels ) ) {
			return $html;
		}

		// tbody / tfoot 内の各行のセル開始タグに data-bcp-label を付与（1 列目=行見出しは除外）。
		return preg_replace_callback(
			'/<(tbody|tfoot)\b[^>]*>.*?<\/\1>/s',
			static function ( $tbm ) use ( $labels ) {
				return preg_replace_callback(
					'/<tr\b[^>]*>.*?<\/tr>/s',
					static function ( $rm ) use ( $labels ) {
						$i = 0;
						return preg_replace_callback(
							'/<(td|th)\b[^>]*>/',
							static function ( $cm ) use ( &$i, $labels ) {
								$orig = $cm[0];
								$col  = $i;
								$i++;
								if ( 0 === $col ) {
									return $orig; // 1 列目は行見出しなのでラベル不要
								}
								$label = isset( $labels[ $col ] ) ? $labels[ $col ] : '';
								if ( '' === $label || false !== strpos( $orig, 'data-bcp-label' ) ) {
									return $orig;
								}
								return substr( $orig, 0, -1 ) . ' data-bcp-label="' . esc_attr( $label ) . '">';
							},
							$rm[0]
						);
					},
					$tbm[0]
				);
			},
			$html
		);
	}

	/** front 用スタイルを登録（enqueue は描画時に遅延）。 */
	public function register_front_style(): void {
		$path = self::path() . 'assets/css/table-extension.css';
		$ver  = file_exists( $path ) ? (int) filemtime( $path ) : '1.0.0';
		\wp_register_style( self::HANDLE, self::url() . 'assets/css/table-extension.css', [], $ver );
	}

	/** 該当 table が描画されたページでだけ front CSS を読む。 */
	private function maybe_enqueue_front_style(): void {
		if ( self::$style_enqueued ) {
			return;
		}
		if ( ! \wp_style_is( self::HANDLE, 'registered' ) ) {
			$this->register_front_style();
		}
		\wp_enqueue_style( self::HANDLE );
		self::$style_enqueued = true;
	}

	/** エディタ: 属性 UI（Inspector）+ 同じ CSS をプレビュー用に読む。 */
	public function enqueue_editor_assets(): void {
		$js_path = self::path() . 'assets/js/table-extension.js';
		$deps    = [ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-compose', 'wp-hooks', 'wp-i18n', 'wp-rich-text' ];

		if ( file_exists( $js_path ) ) {
			\wp_enqueue_script(
				self::HANDLE . '-editor',
				self::url() . 'assets/js/table-extension.js',
				$deps,
				(int) filemtime( $js_path ),
				true
			);
			\wp_set_script_translations( self::HANDLE . '-editor', 'vip2026' );
		}

		$css_path = self::path() . 'assets/css/table-extension.css';
		if ( file_exists( $css_path ) ) {
			\wp_enqueue_style(
				self::HANDLE . '-editor',
				self::url() . 'assets/css/table-extension.css',
				[],
				(int) filemtime( $css_path )
			);
		}
	}
}

/**
 * 起動。BCP プラグインが同機能を提供している環境（jadeclinic）では二重登録を避けるため
 * 起動しない（BCP\Table_Extension に委譲）。BCP 非導入サイトでのみテーマが提供する。
 */
\add_action( 'after_setup_theme', static function () {
	if ( ! \class_exists( '\\BCP\\Table_Extension' ) ) {
		Table_Extension::instance();
	}
} );
