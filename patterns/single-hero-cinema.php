<?php
/**
 * Title: 投稿ヒーロー — シネマティック
 * Slug: vip2026/single-hero-cinema
 * Description: 上下のレターボックスとスローズームで映画のワンシーンのように見せるダークなヒーロー。パララックス対応。
 * Categories: vip2026-single-hero
 * Keywords: hero, cinema, dark, single, ヒーロー, シネマ
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--cinema","backgroundColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--cinema has-main-background-color has-background" style="padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium)">
	<!-- 全体 dim は 30 に抑えて画像を活かし、文字の可読性は CSS の下部グラデーション
	     (group--vip2026-hero--cinema.css の ::after)が担う。50+グラデの二重がけだと
	     画像全体が灰色にくすむ(実機確認 2026-07-17)。 -->
	<!-- wp:cover {"useFeaturedImage":true,"dimRatio":30,"minHeight":58,"minHeightUnit":"vh","contentPosition":"bottom left","align":"full","className":"vip2026-hero__screen is-style-ken-burns","style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-cover alignfull is-position-bottom-left vip2026-hero__screen is-style-ken-burns" style="padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);min-height:58vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container">
		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:post-terms {"term":"category","className":"is-style-cat-text vip2026-hero__overline","fontSize":"x-small","style":{"typography":{"fontWeight":"700","letterSpacing":"0.12em"}}} /-->
			<!-- wp:post-title {"level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"color":{"text":"#ffffff"},"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
		</div>
		<!-- /wp:group -->
	</div></div>
	<!-- /wp:cover -->
</div>
<!-- /wp:group -->
