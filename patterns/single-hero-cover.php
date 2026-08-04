<?php
/**
 * Title: 投稿ヒーロー — 全幅カバー
 * Slug: vip2026/single-hero-cover
 * Description: アイキャッチ画像にタイトルとメタを重ねる全幅カバー。高さ・パララックス対応。
 * Categories: vip2026-single-hero
 * Keywords: hero, cover, single, ヒーロー, カバー
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--cover","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--cover">
	<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"minHeight":66,"minHeightUnit":"vh","contentPosition":"bottom left","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-cover alignfull is-position-bottom-left" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);min-height:66vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-50 has-background-dim"></span><div class="wp-block-cover__inner-container">
		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:post-terms {"term":"category","className":"is-style-cat-fill","fontSize":"x-small"} /-->
			<!-- wp:post-title {"level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"color":{"text":"#ffffff"},"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
			<!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"base","fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group has-base-color has-text-color has-small-font-size" style="font-style:normal;font-weight:500">
				<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
				<!-- wp:paragraph {"textColor":"base"} -->
				<p class="has-base-color has-text-color">·</p>
				<!-- /wp:paragraph -->
				<!-- wp:post-date /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div></div>
	<!-- /wp:cover -->
</div>
<!-- /wp:group -->
