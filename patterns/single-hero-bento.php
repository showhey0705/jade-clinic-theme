<?php
/**
 * Title: 投稿ヒーロー — ベントー
 * Slug: vip2026/single-hero-bento
 * Description: タイトル・アイキャッチ・メタ・タグをカードに分割したベントーグリッド。
 * (PVIP 版は目次カードに pvip-blocks の目次ブロックを使うが、jadeclinic には
 *  無いためタグ一覧カードに置き換えている)
 * Categories: vip2026-single-hero
 * Keywords: hero, bento, grid, single, ヒーロー, ベントー
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--bento","backgroundColor":"tertiary","style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--bento has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large)">
	<!-- wp:group {"className":"vip2026-hero__bgrid","align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide vip2026-hero__bgrid">
		<!-- wp:group {"className":"vip2026-bcard vip2026-bcard--title","backgroundColor":"base","style":{"border":{"color":"var:preset|color|border-light","width":"1px","radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"},"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group vip2026-bcard vip2026-bcard--title has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:16px;padding-top:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large)">
			<!-- wp:post-terms {"term":"category","className":"is-style-cat-text","fontSize":"x-small"} /-->
			<!-- wp:post-title {"level":1,"fontFamily":"jp-sans","fontSize":"large","style":{"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"vip2026-bcard vip2026-bcard--eye","style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group vip2026-bcard vip2026-bcard--eye" style="border-radius:16px">
			<!-- wp:post-featured-image {"className":"vip2026-bcard__img"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"vip2026-bcard vip2026-bcard--meta","backgroundColor":"base","style":{"border":{"color":"var:preset|color|border-light","width":"1px","radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|large","right":"var:preset|spacing|large"},"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group vip2026-bcard vip2026-bcard--meta has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:16px;padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--large)">
			<!-- wp:paragraph {"className":"vip2026-bcard__k","textColor":"secondary","fontSize":"x-small"} -->
			<p class="vip2026-bcard__k has-secondary-color has-text-color has-x-small-font-size">INFO</p>
			<!-- /wp:paragraph -->
			<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by"} /-->
				<!-- wp:post-date {"textColor":"secondary"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"vip2026-bcard vip2026-bcard--toc","backgroundColor":"base","style":{"border":{"color":"var:preset|color|border-light","width":"1px","radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group vip2026-bcard vip2026-bcard--toc has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:16px;padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--large)">
			<!-- wp:paragraph {"className":"vip2026-bcard__k","textColor":"secondary","fontSize":"x-small"} -->
			<p class="vip2026-bcard__k has-secondary-color has-text-color has-x-small-font-size">TAGS</p>
			<!-- /wp:paragraph -->
			<!-- wp:post-terms {"term":"post_tag","className":"is-style-cat-text","fontSize":"x-small"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
