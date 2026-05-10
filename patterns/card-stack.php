<?php
/**
 * Title: Card Stack
 * Slug: vip2026/card-stack
 * Categories: featured
 * Description: スクロール駆動アニメーションを使ったカードスタックエフェクト
 *
 * 構造（リストビューに表示される名前）:
 *   Card Stack（.is-style-stack-cards）
 *     ├ カード 1（.stack-card）
 *     │   └ 見た目（.stack-card__content - 背景色 / 角丸 / シャドウ）
 *     │       └ 中身（.stack-card__inner - padding）
 *     ├ カード 2 ...
 *     ├ カード 3 ...
 *     └ カード 4 ...
 *
 * カード幅は親に inline style で `--card-max-width: 800px` のように指定すると
 * 個別に絞り込み可能。デフォルトは親（contentSize）に追従。
 * 親グループのアラインを「広い (wide)」にすれば wideSize まで広がる。
 */
?>

<!-- wp:group {"metadata":{"name":"Card Stack"},"className":"is-style-stack-cards","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-stack-cards">

	<!-- wp:group {"metadata":{"name":"カード 1"},"className":"stack-card","layout":{"type":"constrained"}} -->
	<div class="wp-block-group stack-card">
		<!-- wp:group {"metadata":{"name":"見た目"},"className":"stack-card__content","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group stack-card__content has-tertiary-background-color has-background">
			<!-- wp:group {"metadata":{"name":"中身"},"className":"stack-card__inner","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","right":"var:preset|spacing|large","bottom":"var:preset|spacing|x-large","left":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group stack-card__inner" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--large)">
				<!-- wp:heading -->
				<h2 class="wp-block-heading">カード 1</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>美しいスクロールエフェクトでコンテンツを魅力的に表示します。</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button -->
					<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">詳しく見る</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"カード 2"},"className":"stack-card","layout":{"type":"constrained"}} -->
	<div class="wp-block-group stack-card">
		<!-- wp:group {"metadata":{"name":"見た目"},"className":"stack-card__content","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group stack-card__content has-tertiary-background-color has-background">
			<!-- wp:group {"metadata":{"name":"中身"},"className":"stack-card__inner","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","right":"var:preset|spacing|large","bottom":"var:preset|spacing|x-large","left":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group stack-card__inner" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--large)">
				<!-- wp:heading -->
				<h2 class="wp-block-heading">カード 2</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>次のカードが上にスタックされ、前のカードが縮小します。</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button -->
					<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">詳しく見る</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"カード 3"},"className":"stack-card","layout":{"type":"constrained"}} -->
	<div class="wp-block-group stack-card">
		<!-- wp:group {"metadata":{"name":"見た目"},"className":"stack-card__content","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group stack-card__content has-tertiary-background-color has-background">
			<!-- wp:group {"metadata":{"name":"中身"},"className":"stack-card__inner","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","right":"var:preset|spacing|large","bottom":"var:preset|spacing|x-large","left":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group stack-card__inner" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--large)">
				<!-- wp:heading -->
				<h2 class="wp-block-heading">カード 3</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>スクロールに応じて動的にアニメーションします。</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button -->
					<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">詳しく見る</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"カード 4"},"className":"stack-card","layout":{"type":"constrained"}} -->
	<div class="wp-block-group stack-card">
		<!-- wp:group {"metadata":{"name":"見た目"},"className":"stack-card__content","backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group stack-card__content has-tertiary-background-color has-background">
			<!-- wp:group {"metadata":{"name":"中身"},"className":"stack-card__inner","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","right":"var:preset|spacing|large","bottom":"var:preset|spacing|x-large","left":"var:preset|spacing|large"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group stack-card__inner" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--large)">
				<!-- wp:heading -->
				<h2 class="wp-block-heading">カード 4</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>最後のカードです。エディタで簡単にカスタマイズできます。</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button -->
					<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">詳しく見る</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
