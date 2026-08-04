<?php
/**
 * Title: 投稿ヒーロー — ズィーン
 * Slug: vip2026/single-hero-zine
 * Description: スタンプ・蛍光マーカー・ステッカーを散らしたレトロな紙面風ヒーロー。
 * Categories: vip2026-single-hero
 * Keywords: hero, zine, retro, single, ヒーロー, ジン
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--zine","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|x-large"},"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--zine" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--x-large)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:paragraph {"className":"vip2026-zine-stamp","fontFamily":"dot-gothic-16"} -->
		<p class="vip2026-zine-stamp has-dot-gothic-16-font-family">PICK UP</p>
		<!-- /wp:paragraph -->
		<!-- wp:post-title {"level":1,"className":"is-style-zine-marker","fontFamily":"jp-sans","fontSize":"large","style":{"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
		<!-- wp:group {"className":"vip2026-zine-meta","style":{"spacing":{"blockGap":"18px"}},"textColor":"secondary","fontFamily":"dot-gothic-16","fontSize":"small","layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group vip2026-zine-meta has-secondary-color has-text-color has-dot-gothic-16-font-family has-small-font-size">
			<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
			<!-- wp:post-date /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
