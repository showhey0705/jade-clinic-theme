<?php
/**
 * Title: 投稿ヒーロー — ミニマル
 * Slug: vip2026/single-hero-minimal
 * Description: アイキャッチを使わず、ナロー幅でタイトルとメタのみを見せる最小構成。
 * Categories: vip2026-single-hero
 * Keywords: hero, minimal, single, ヒーロー, ミニマル
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--minimal","style":{"spacing":{"padding":{"top":"var:preset|spacing|xxx-large","bottom":"var:preset|spacing|x-large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--minimal" style="padding-top:var(--wp--preset--spacing--xxx-large);padding-bottom:var(--wp--preset--spacing--x-large)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","contentSize":"680px","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:post-terms {"term":"category","className":"is-style-cat-text","fontSize":"x-small"} /-->
		<!-- wp:post-title {"level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
		<!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary","fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group has-secondary-color has-text-color has-link-color has-small-font-size">
			<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
			<!-- wp:paragraph {"textColor":"secondary"} -->
			<p class="has-secondary-color has-text-color">·</p>
			<!-- /wp:paragraph -->
			<!-- wp:post-date /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:separator {"className":"is-style-separator-thin","backgroundColor":"border-light","style":{"spacing":{"margin":{"top":"var:preset|spacing|medium","bottom":"0"}}}} -->
		<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-separator-thin" style="margin-top:var(--wp--preset--spacing--medium);margin-bottom:0"/>
		<!-- /wp:separator -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
