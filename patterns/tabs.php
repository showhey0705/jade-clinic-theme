<?php
/**
 * Title: Tabs
 * Slug: vip2026/tabs
 * Categories: featured
 * Keywords: tab, tabs, switcher
 * Description: クリックで切り替わるタブ UI（is-style-tabs ベース）
 * Inserter: true
 */
?>

<!-- wp:group {"className":"is-style-tabs","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-tabs">

	<!-- wp:group {"className":"tab-links tab-links-horizontal","layout":{"type":"flex","flexWrap":"nowrap"}} -->
	<div class="wp-block-group tab-links tab-links-horizontal">
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"tab-link is-active"} -->
			<div class="wp-block-button tab-link is-active"><a class="wp-block-button__link wp-element-button">タブ 1</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"tab-link"} -->
			<div class="wp-block-button tab-link"><a class="wp-block-button__link wp-element-button">タブ 2</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"tab-link"} -->
			<div class="wp-block-button tab-link"><a class="wp-block-button__link wp-element-button">タブ 3</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"tab-content-wrapper","layout":{"type":"constrained"}} -->
	<div class="wp-block-group tab-content-wrapper">

		<!-- wp:group {"className":"tab-content is-active","layout":{"type":"constrained"}} -->
		<div class="wp-block-group tab-content is-active">
			<!-- wp:paragraph -->
			<p>タブ 1 のコンテンツ。ここに任意のブロックを配置できます。</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"tab-content","layout":{"type":"constrained"}} -->
		<div class="wp-block-group tab-content">
			<!-- wp:paragraph -->
			<p>タブ 2 のコンテンツ。</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"tab-content","layout":{"type":"constrained"}} -->
		<div class="wp-block-group tab-content">
			<!-- wp:paragraph -->
			<p>タブ 3 のコンテンツ。</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
