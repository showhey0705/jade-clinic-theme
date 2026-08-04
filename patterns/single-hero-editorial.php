<?php
/**
 * Title: 投稿ヒーロー — エディトリアル
 * Slug: vip2026/single-hero-editorial
 * Description: 雑誌のトビラ風。左に見出しとリード、右にアイキャッチとメタを表組みで見せる。
 * Categories: vip2026-single-hero
 * Keywords: hero, editorial, magazine, single, ヒーロー, 雑誌
 * Block Types: core/template-part/single-hero
 * Viewport Width: 1400
 * Inserter: true
 *
 * @package vip2026
 */
?>
<!-- wp:group {"align":"full","className":"vip2026-hero vip2026-hero--editorial","style":{"border":{"top":{"color":"var:preset|color|main","width":"4px"}},"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull vip2026-hero vip2026-hero--editorial" style="border-top:4px solid var(--wp--preset--color--main);padding-top:var(--wp--preset--spacing--x-large);padding-bottom:var(--wp--preset--spacing--x-large)">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|x-large"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"62%","className":"vip2026-hero__main-cell"} -->
		<div class="wp-block-column vip2026-hero__main-cell" style="flex-basis:62%">
			<!-- wp:post-terms {"term":"category","className":"is-style-cat-text","fontSize":"x-small","style":{"typography":{"fontWeight":"700","letterSpacing":"0.1em"}}} /-->
			<!-- wp:post-title {"level":1,"fontFamily":"jp-serif","fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"}},"typography":{"fontWeight":"normal","lineHeight":"1.2"}}} /-->
			<!-- wp:post-excerpt {"excerptLength":60,"showMoreOnNewLine":false,"textColor":"secondary","fontSize":"small","style":{"typography":{"lineHeight":"2"}}} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"38%","className":"vip2026-hero__meta-col"} -->
		<div class="wp-block-column vip2026-hero__meta-col" style="flex-basis:38%">
			<!-- wp:post-featured-image {"aspectRatio":"4/3","style":{"border":{"radius":"6px"},"spacing":{"margin":{"bottom":"var:preset|spacing|small"}}}} /-->
			<!-- wp:group {"className":"vip2026-hero__meta-table","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group vip2026-hero__meta-table">
				<!-- wp:group {"className":"vip2026-hero__cell","style":{"border":{"bottom":{"color":"var:preset|color|border-light","width":"1px"}},"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"2px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group vip2026-hero__cell" style="border-bottom:1px solid var(--wp--preset--color--border-light);padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
					<!-- wp:paragraph {"className":"vip2026-hero__cell-k","textColor":"secondary","fontSize":"x-small"} -->
					<p class="vip2026-hero__cell-k has-secondary-color has-text-color has-x-small-font-size">AUTHOR</p>
					<!-- /wp:paragraph -->
					<!-- wp:post-author {"showAvatar":false,"showBio":false,"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} /-->
				</div>
				<!-- /wp:group -->
				<!-- wp:group {"className":"vip2026-hero__cell","style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"2px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group vip2026-hero__cell" style="padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
					<!-- wp:paragraph {"className":"vip2026-hero__cell-k","textColor":"secondary","fontSize":"x-small"} -->
					<p class="vip2026-hero__cell-k has-secondary-color has-text-color has-x-small-font-size">DATE</p>
					<!-- /wp:paragraph -->
					<!-- wp:post-date {"fontSize":"small","style":{"typography":{"fontWeight":"600"}}} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
