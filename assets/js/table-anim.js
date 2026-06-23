/**
 * 出現アニメーション発火用の極小スクリプト（front のみ）。
 *
 * 方針（業界最速・最小コスト）:
 *  - prefers-reduced-motion: reduce なら即 return（一切動かさない）。
 *  - 対象 figure を IntersectionObserver で監視し、入った瞬間に .is-bcp-inview を付与。
 *  - 発火したら unobserve（メモリ/通知を残さない）。画面内・画面外どちらの表も確実に発火。
 *  - IntersectionObserver 非対応環境は全 reveal（内容を隠したままにしない安全側）。
 *  - 実 CSS アニメは table-extension.css 側（transform/opacity/clip-path のみ）。
 */
( function () {
	'use strict';

	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	var SEL = '.wp-block-table[data-bcp-animate]';

	function reveal( el ) {
		el.classList.add( 'is-bcp-inview' );
	}

	function init() {
		var tables = document.querySelectorAll( SEL );
		if ( ! tables.length ) {
			return;
		}
		if ( ! ( 'IntersectionObserver' in window ) ) {
			for ( var i = 0; i < tables.length; i++ ) {
				reveal( tables[ i ] );
			}
			return;
		}
		var io = new IntersectionObserver(
			function ( entries ) {
				for ( var i = 0; i < entries.length; i++ ) {
					var e = entries[ i ];
					if ( e.isIntersecting ) {
						reveal( e.target );
						io.unobserve( e.target );
					}
				}
			},
			{ threshold: 0.12, rootMargin: '0px 0px -6% 0px' }
		);
		for ( var j = 0; j < tables.length; j++ ) {
			io.observe( tables[ j ] );
		}
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init, { once: true } );
	} else {
		init();
	}
} )();
