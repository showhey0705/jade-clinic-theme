/**
 * C-11 税込/税抜トグル — テーブルの税表示切替。
 *
 * 委譲リスナーで .bcp-tax-btn のクリックを捕捉し、最寄りの .wp-block-table の
 * data-bcp-tax-mode 属性をボタンの data-tax 値へ更新する。あわせて各ボタンの
 * is-active クラスと aria-pressed 属性を同期する。常時有効（IO 不要）。
 *
 * @package beauty-clinic-patterns
 */
( function () {
	'use strict';
	document.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest && e.target.closest( '.bcp-tax-btn' );
		if ( ! btn ) return;
		var fig = btn.closest( '.wp-block-table' );
		if ( ! fig ) return;
		var mode = btn.getAttribute( 'data-tax' ) || 'in';
		fig.setAttribute( 'data-bcp-tax-mode', mode );
		var btns = fig.querySelectorAll( '.bcp-tax-btn' );
		for ( var i = 0; i < btns.length; i++ ) {
			var on = btns[ i ] === btn;
			btns[ i ].classList.toggle( 'is-active', on );
			btns[ i ].setAttribute( 'aria-pressed', on ? 'true' : 'false' );
		}
	} );
} )();
