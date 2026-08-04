<?php
/**
 * Title: 投稿ヒーロー — 中央タイトル
 * Slug: vip2026/single-hero-centered
 * Description: タイトルを中央に置き、その下にアイキャッチを大きく配置。読み物の王道。
 * Categories: vip2026-single-hero
 * Keywords: hero, centered, single, ヒーロー, 中央
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--centered","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"0"},"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--centered" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:0">
	<!-- wp:post-terms {"term":"category","textAlign":"center","className":"is-style-cat-text","fontSize":"x-small"} /-->
	<!-- wp:post-title {"textAlign":"center","level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
	<!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary","fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
	<div class="wp-block-group has-secondary-color has-text-color has-link-color has-small-font-size">
		<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
		<!-- wp:paragraph {"textColor":"secondary"} -->
		<p class="has-secondary-color has-text-color">·</p>
		<!-- /wp:paragraph -->
		<!-- wp:post-date /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:post-featured-image {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|large"}},"border":{"radius":"12px"}}} /-->
</div>
<!-- /wp:group -->
