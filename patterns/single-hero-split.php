<?php
/**
 * Title: 投稿ヒーロー — 2カラム分割
 * Slug: vip2026/single-hero-split
 * Description: 左にタイトルとメタ(39%)、右にアイキャッチ(61%)を並べた分割レイアウト。
 * Categories: vip2026-single-hero
 * Keywords: hero, split, columns, single, ヒーロー, 分割
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--split","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--split" style="padding-top:var(--wp--preset--spacing--x-large);padding-bottom:var(--wp--preset--spacing--x-large)">
	<!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|xx-large"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"39%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:39%">
			<!-- wp:post-terms {"term":"category","className":"is-style-cat-text","fontSize":"x-small"} /-->
			<!-- wp:post-title {"level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"}},"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
			<!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary","fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group has-secondary-color has-text-color has-link-color has-small-font-size">
				<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
				<!-- wp:paragraph {"textColor":"secondary"} -->
				<p class="has-secondary-color has-text-color">·</p>
				<!-- /wp:paragraph -->
				<!-- wp:post-date /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"61%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:61%">
			<!-- wp:post-featured-image {"aspectRatio":"4/3","style":{"border":{"radius":"12px"}}} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
