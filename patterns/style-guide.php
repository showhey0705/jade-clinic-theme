<?php
/**
 * Title: Jade Clinic Style Guide
 * Slug: vip2026/style-guide
 * Categories: featured
 * Keywords: style guide, tokens, palette, typography, styleguide
 * Description: Bronze/Forest スタイルバリエーションのスタイルガイド（11カラートークン / ボタン / 施術カード / タイポグラフィ）。jade-bronze-forest-preview.html 由来。
 * Inserter: true
 */
?>

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|xxx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--xxx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary-alt","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-alt-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">Aesthetic Dermatology · Ōhori, Fukuoka</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.2rem"} -->
<div style="height:1.2rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:heading {"level":1,"fontSize":"xxx-large","fontFamily":"cormorant-garamond","style":{"typography":{"fontStyle":"italic","lineHeight":"1.0"}}} -->
<h1 class="wp-block-heading has-cormorant-garamond-font-family has-xxx-large-font-size" style="font-style:italic;line-height:1.0">Stillness,<br>in your skin.</h1>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"1rem"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:heading {"level":2,"fontSize":"large","fontFamily":"shippori-mincho"} -->
<h2 class="wp-block-heading has-shippori-mincho-font-family has-large-font-size">大灷の光と、サンドの静けさ。</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"1.2rem"} -->
<div style="height:1.2rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"medium","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-medium-font-size has-shippori-mincho-font-family">余白と質感を大切にした、肌のためのクリニック。プロンズ #8F6F52 を Brand、フォレストグリーン #355F3B を差し色とした配色です。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.6rem"} -->
<div style="height:1.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-button-brand"} -->
<div class="wp-block-button is-style-button-brand"><a class="wp-block-button__link wp-element-button">カウンセリング予約</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-button-light"} -->
<div class="wp-block-button is-style-button-light"><a class="wp-block-button__link wp-element-button">施術メニュー</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"tertiary","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary-alt","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-alt-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">01 — Palette</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"x-large","fontFamily":"cormorant-garamond"} -->
<h2 class="wp-block-heading has-cormorant-garamond-font-family has-x-large-font-size">11 Color Tokens</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">スラッグは Ollie の settings.color.palette と一致。CSS では --wp--preset--color--{slug} として描画されます。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.6rem"} -->
<div style="height:1.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"13rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|medium"}}} -->
<div class="wp-block-group">
<!-- wp:group {"backgroundColor":"primary","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-primary-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Brand</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">primary</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#8F6F52 · --wp--preset--color--primary</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"primary-accent","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-primary-accent-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Brand Accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">primary-accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#DAD9D7 · --wp--preset--color--primary-accent</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"primary-alt","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-primary-alt-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Brand Alt</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">primary-alt</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#355F3B · --wp--preset--color--primary-alt</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"primary-alt-accent","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-primary-alt-accent-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Brand Alt Accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">primary-alt-accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#FFFFFF · --wp--preset--color--primary-alt-accent</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"main","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-main-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Contrast</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">main</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#262524 · --wp--preset--color--main</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"main-accent","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-main-accent-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Contrast Accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">main-accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#E4E2DD · --wp--preset--color--main-accent</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"base","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-base-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Base</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">base</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#F3EEE7 · --wp--preset--color--base</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"secondary","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-secondary-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Base Accent</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">secondary</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#615F5F · --wp--preset--color--secondary</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"tertiary","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-tertiary-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Tint</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">tertiary</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#F0EAE0 · --wp--preset--color--tertiary</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"border-light","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-border-light-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Border Base</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">border-light</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#DAD9D7 · --wp--preset--color--border-light</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"border-dark","textColor":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"radius":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-color has-border-dark-background-color has-text-color has-background" style="border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"small","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-small-font-size" style="font-weight:600">Border Contrast</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">border-dark</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">#AAA6A3 · --wp--preset--color--border-dark</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary-alt","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-alt-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">02 — Components</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"x-large","fontFamily":"cormorant-garamond"} -->
<h2 class="wp-block-heading has-cormorant-garamond-font-family has-x-large-font-size">Buttons</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">Ollie 標準の5スタイル。フォレスト（Brand Alt）は白文字で 7.36:1（AAA）、ブロンズも白文字で 4.6:1（AA）。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.4rem"} -->
<div style="height:1.4rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-button-brand"} -->
<div class="wp-block-button is-style-button-brand"><a class="wp-block-button__link wp-element-button">Brand</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-button-brand-alt"} -->
<div class="wp-block-button is-style-button-brand-alt"><a class="wp-block-button__link wp-element-button">Brand Alt</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-button-dark"} -->
<div class="wp-block-button is-style-button-dark"><a class="wp-block-button__link wp-element-button">Dark</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-button-light"} -->
<div class="wp-block-button is-style-button-light"><a class="wp-block-button__link wp-element-button">Light</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-secondary-button"} -->
<div class="wp-block-button is-style-secondary-button"><a class="wp-block-button__link wp-element-button">Tint</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
<!-- wp:spacer {"height":"3rem"} -->
<div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:heading {"level":2,"fontSize":"x-large","fontFamily":"cormorant-garamond"} -->
<h2 class="wp-block-heading has-cormorant-garamond-font-family has-x-large-font-size">Treatment Menu</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"1.4rem"} -->
<div style="height:1.4rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"16rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|large"}}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large)">
<!-- wp:paragraph {"fontSize":"medium","textColor":"primary","fontFamily":"cormorant-garamond"} -->
<p class="has-primary-color has-text-color has-medium-font-size has-cormorant-garamond-font-family">01</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large","fontFamily":"shippori-mincho"} -->
<h3 class="wp-block-heading has-shippori-mincho-font-family has-large-font-size">肌診断・カウンセリング</h3>
<!-- /wp:heading -->
<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">専用機器で肌状態を可視化し、計画を一緒に立てます。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"primary","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.06em"}}} -->
<p class="has-primary-color has-text-color has-small-font-size has-oswald-font-family" style="letter-spacing:0.06em">¥5,500 / 60min</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large)">
<!-- wp:paragraph {"fontSize":"medium","textColor":"primary","fontFamily":"cormorant-garamond"} -->
<p class="has-primary-color has-text-color has-medium-font-size has-cormorant-garamond-font-family">02</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large","fontFamily":"shippori-mincho"} -->
<h3 class="wp-block-heading has-shippori-mincho-font-family has-large-font-size">ハイドラフェイシャル</h3>
<!-- /wp:heading -->
<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">毛穴の洗歐から保湿までを一度の施術で。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"primary","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.06em"}}} -->
<p class="has-primary-color has-text-color has-small-font-size has-oswald-font-family" style="letter-spacing:0.06em">¥22,000 / 45min</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large)">
<!-- wp:paragraph {"fontSize":"medium","textColor":"primary","fontFamily":"cormorant-garamond"} -->
<p class="has-primary-color has-text-color has-medium-font-size has-cormorant-garamond-font-family">03</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"fontSize":"large","fontFamily":"shippori-mincho"} -->
<h3 class="wp-block-heading has-shippori-mincho-font-family has-large-font-size">医療アートメイク</h3>
<!-- /wp:heading -->
<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">骨格と表情に合わせた、自然な仕上がり。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"primary","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.06em"}}} -->
<p class="has-primary-color has-text-color has-small-font-size has-oswald-font-family" style="letter-spacing:0.06em">¥88,000〜</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"main","textColor":"main-accent","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-main-accent-color has-main-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">03 — Dark Surface</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"large","fontFamily":"cormorant-garamond","textColor":"main-accent"} -->
<h2 class="wp-block-heading has-main-accent-color has-text-color has-cormorant-garamond-font-family has-large-font-size">暗い面は main（チャコール）に main-accent の文字。</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"1rem"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"base","textColor":"main-accent","fontFamily":"shippori-mincho"} -->
<p class="has-main-accent-color has-text-color has-base-font-size has-shippori-mincho-font-family">フッターや帯に。下は11トークンの並び：</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.4rem"} -->
<div style="height:1.4rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"2.4rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|small"}}} -->
<div class="wp-block-group">
<!-- wp:group {"backgroundColor":"primary","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-primary-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"primary-accent","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-primary-accent-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"primary-alt","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-primary-alt-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"primary-alt-accent","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-primary-alt-accent-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"main","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"main-accent","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-main-accent-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"base","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"secondary","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-secondary-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"tertiary","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-tertiary-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"border-light","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-light-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
<!-- wp:group {"backgroundColor":"border-dark","style":{"dimensions":{"minHeight":"48px"},"border":{"radius":"3px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-dark-background-color has-background" style="border-radius:3px;min-height:48px">
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:spacer {"height":"2rem"} -->
<div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-button-brand-alt"} -->
<div class="wp-block-button is-style-button-brand-alt"><a class="wp-block-button__link wp-element-button">資料請求</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-button-brand"} -->
<div class="wp-block-button is-style-button-brand"><a class="wp-block-button__link wp-element-button">アクセス</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"primary-alt","textColor":"primary-alt-accent","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-primary-alt-accent-color has-primary-alt-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary-alt-accent","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-alt-accent-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">Accent Surface</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"large","fontFamily":"cormorant-garamond","textColor":"primary-alt-accent"} -->
<h2 class="wp-block-heading has-primary-alt-accent-color has-text-color has-cormorant-garamond-font-family has-large-font-size">アクセント面は primary-alt（forest green）に白の文字。</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"1rem"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"base","textColor":"primary-alt-accent","fontFamily":"shippori-mincho"} -->
<p class="has-primary-alt-accent-color has-text-color has-base-font-size has-shippori-mincho-font-family">ブロンズを主役に、フォレストグリーンは帯・罚・Altボタンなどの差し色に限定。緒＋白は 7.36:1（AAA）。</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"tertiary","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary-alt","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-alt-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">04 — Typography Tokens</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"x-large","fontFamily":"cormorant-garamond"} -->
<h2 class="wp-block-heading has-cormorant-garamond-font-family has-x-large-font-size">Font Families</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">theme.json の settings.typography.fontFamilies。見出しは欧文=Cormorant・和文=しっぽり明朝にフォールバックします。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.4rem"} -->
<div style="height:1.4rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"17rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|large"}}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large)">
<!-- wp:paragraph {"fontSize":"large","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-large-font-size has-shippori-mincho-font-family">Aa 明朝</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"medium","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-medium-font-size has-shippori-mincho-font-family">海と肌の時間</p>
<!-- /wp:paragraph -->
<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"fontSize":"small","textColor":"main","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-main-color has-text-color has-small-font-size" style="font-weight:600">Shippori Mincho</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-shippori-mincho-font-family">本文・和文見出し</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"primary-alt","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-primary-alt-color has-text-color has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">font-family | shippori-mincho</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large)">
<!-- wp:paragraph {"fontSize":"large","textColor":"main","fontFamily":"cormorant-garamond"} -->
<p class="has-main-color has-text-color has-large-font-size has-cormorant-garamond-font-family">Aa Stillness</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"medium","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-medium-font-size has-shippori-mincho-font-family">大灷</p>
<!-- /wp:paragraph -->
<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"fontSize":"small","textColor":"main","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-main-color has-text-color has-small-font-size" style="font-weight:600">Cormorant Garamond</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-shippori-mincho-font-family">欧文ディスプレイ見出し</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"primary-alt","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-primary-alt-color has-text-color has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">font-family | cormorant-garamond</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large","left":"var:preset|spacing|large","right":"var:preset|spacing|large"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color has-base-background-color has-background" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large)">
<!-- wp:paragraph {"fontSize":"large","textColor":"main","fontFamily":"oswald"} -->
<p class="has-main-color has-text-color has-large-font-size has-oswald-font-family">AESTHETIC</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"medium","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-medium-font-size has-shippori-mincho-font-family">¥22,000</p>
<!-- /wp:paragraph -->
<!-- wp:separator {"backgroundColor":"border-light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-border-light-color has-alpha-channel-opacity has-border-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->
<!-- wp:paragraph {"fontSize":"small","textColor":"main","style":{"typography":{"fontWeight": "600"}}} -->
<p class="has-main-color has-text-color has-small-font-size" style="font-weight:600">Oswald</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-shippori-mincho-font-family">ラベル・アイブロウ・数値</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"primary-alt","fontFamily":"oswald","style":{"typography":{"letterSpacing": "0.02em"}}} -->
<p class="has-primary-alt-color has-text-color has-x-small-font-size has-oswald-font-family" style="letter-spacing:0.02em">font-family | oswald</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:spacer {"height":"3rem"} -->
<div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:heading {"level":2,"fontSize":"x-large","fontFamily":"cormorant-garamond"} -->
<h2 class="wp-block-heading has-cormorant-garamond-font-family has-x-large-font-size">Fluid Type Scale</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"0.6rem"} -->
<div style="height:0.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"small","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-small-font-size has-shippori-mincho-font-family">vip2026 の型スケール。すべて clamp() で可変。fontSize スラッグで参照します。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.2rem"} -->
<div style="height:1.2rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">xxx-large  ·  9rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"xxx-large","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-xxx-large-font-size has-shippori-mincho-font-family">韓や（ヒーロ）</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">xx-large · h1  ·  4.39rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"xx-large","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-xx-large-font-size has-shippori-mincho-font-family">静けさを、肌に</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">x-large · h2  ·  3.5rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-large","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-x-large-font-size has-shippori-mincho-font-family">海と共に過ごす</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">large · h3  ·  2.75rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"large","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-large-font-size has-shippori-mincho-font-family">施術メニュー</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">medium · h4  ·  1.42rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"medium","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-medium-font-size has-shippori-mincho-font-family">ハイドラフェイシャル</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">base · body  ·  1.1rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"base","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-base-font-size has-shippori-mincho-font-family">余白と質感を大切にした、肌のためのクリニック。</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">small  ·  1.05rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"small","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-small-font-size has-shippori-mincho-font-family">補助テキスト・キャプション</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|small"},"border":{"top":{"width":"1px"}}},"borderColor":"border-light","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-top-width:1px;padding-top:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">x-small · h5/h6  ·  0.89rem</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"x-small","textColor":"primary-alt","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.18em"}}} -->
<p class="has-primary-alt-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.18em">EYEBROW LABEL</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|xx-large","bottom":"var:preset|spacing|xx-large","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}}}} -->
<section class="wp-block-group alignfull has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--xx-large);padding-bottom:var(--wp--preset--spacing--xx-large);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"oswald","textColor":"primary-alt","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.28em"}}} -->
<p class="has-primary-alt-color has-text-color has-oswald-font-family has-x-small-font-size" style="text-transform:uppercase;letter-spacing:0.28em">05 — In Context</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"fontSize":"large","fontFamily":"cormorant-garamond"} -->
<h2 class="wp-block-heading has-cormorant-garamond-font-family has-large-font-size">しっぽり明朝 × Cormorant Garamond</h2>
<!-- /wp:heading -->
<!-- wp:spacer {"height":"1rem"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"medium","textColor":"main","fontFamily":"shippori-mincho"} -->
<p class="has-main-color has-text-color has-medium-font-size has-shippori-mincho-font-family">海と共に過ごす、肌の時間。 A quiet luxury for your skin.</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"0.8rem"} -->
<div style="height:0.8rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:paragraph {"fontSize":"base","textColor":"secondary","fontFamily":"shippori-mincho"} -->
<p class="has-secondary-color has-text-color has-base-font-size has-shippori-mincho-font-family">和文はしっぽり明朝で静けさと品を、欧文見出しは Cormorant Garamond のイタリックで抑搋を、ラベル・数値は Oswald で引き締めます。補助テキスト（secondary）は白背景上 5.26:1 で可読性を確保。</p>
<!-- /wp:paragraph -->
<!-- wp:spacer {"height":"1.6rem"} -->
<div style="height:1.6rem" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
<!-- wp:group {"layout":{"type":"grid","minimumColumnWidth":"15rem"},"style":{"spacing":{"blockGap":"var:preset|spacing|medium"}}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-light","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-light-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">Border Base — border-light</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"border-dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-border-dark-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">Border Contrast — border-dark</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium","right":"var:preset|spacing|medium"}},"border":{"width":"1px","radius":"6px"}},"borderColor":"primary-alt","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-primary-alt-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium)">
<!-- wp:paragraph {"fontSize":"x-small","textColor":"secondary","fontFamily":"oswald","style":{"typography":{"textTransform": "uppercase", "letterSpacing": "0.06em"}}} -->
<p class="has-secondary-color has-text-color has-x-small-font-size has-oswald-font-family" style="text-transform:uppercase;letter-spacing:0.06em">Forest hairline — primary-alt</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
