<?php
/**
 * Title: 投稿ヒーロー — タイプファースト
 * Slug: vip2026/single-hero-type
 * Description: 超大型の明朝タイトルを主役に、上下にドット書体のキッカーとメタを配した誌面型。
 * Categories: vip2026-single-hero
 * Keywords: hero, typography, single, ヒーロー, タイポ
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--type","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|x-large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--type" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--x-large)">
	<!-- wp:group {"align":"wide","className":"vip2026-hero__toprow","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide vip2026-hero__toprow">
		<!-- wp:post-terms {"term":"category","className":"is-style-cat-text","fontFamily":"dot-gothic-16","fontSize":"x-small"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:post-title {"align":"wide","level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}},"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
	<!-- wp:group {"align":"wide","className":"vip2026-hero__bottomrow","style":{"border":{"top":{"color":"var:preset|color|border-light","width":"1px"}},"spacing":{"padding":{"top":"var:preset|spacing|small"}}},"textColor":"secondary","fontSize":"small","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
	<div class="wp-block-group alignwide vip2026-hero__bottomrow has-secondary-color has-text-color has-small-font-size" style="border-top:1px solid var(--wp--preset--color--border-light);padding-top:var(--wp--preset--spacing--small)">
		<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
		<!-- wp:post-date /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
