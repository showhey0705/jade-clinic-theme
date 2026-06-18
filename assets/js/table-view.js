/**
 * フロント用ビュー（横スクロール表の演出を JS 駆動で制御）。
 *
 * - 左右フェードグラデーションをスクロール量に比例して滑らかに増減
 * - 「スクロールできます」ガイドは「実際に横オーバーフローがある時だけ」表示し、
 *   末尾に近づくと消える
 * - 1 列目固定時は左フェードを固定列の右端から開始するため、
 *   固定列の右端（figure 基準）を --bcp-cell1-right として渡す
 *
 * CSS scroll-timeline はブラウザ条件（container-type 併用・背景タブ等）で
 * 不安定だったため、scroll/resize イベントで明示制御する方式に変更。
 *
 * パフォーマンス方針:
 * - レイアウト依存の重い計測（getBoundingClientRect 等）は resize 時のみ実行（layout）。
 * - スクロールの毎フレーム処理は scrollLeft の読み取りと opacity 書き込みだけ（paint）。
 * - scroll は requestAnimationFrame で 1 フレーム 1 回に集約し、値が変わった時だけ書き込む。
 */
( function () {
	'use strict';

	function clamp( v, a, b ) {
		return Math.max( a, Math.min( b, v ) );
	}

	function attach( fig ) {
		var sc = fig.querySelector( '.bcp-table-scroll[data-bcp-scrollable]' );
		if ( ! sc ) {
			return;
		}
		var L = fig.querySelector( '.bcp-fade-left' );
		var R = fig.querySelector( '.bcp-fade-right' );
		var hint = fig.querySelector( '.bcp-table-scrollhint' );

		var max = 0; // 横オーバーフロー量（layout で更新）
		var lastL = -1, lastR = -1, lastH = -1; // 直近の opacity（無駄な書き込み回避）
		var ticking = false;

		// レイアウト確定時のみ走る重い処理（リサイズ / 初期化）。
		function layout() {
			// 固定列の右端を figure 基準で測り、左フェードの開始位置に渡す。
			// 固定中は scrollLeft に依らず一定なので、ここ（resize 時）で計算すれば十分。
			var firstCell = sc.querySelector( 'tr > :first-child' );
			if ( firstCell ) {
				var cr = firstCell.getBoundingClientRect();
				fig.style.setProperty( '--bcp-cell1-right', Math.round( cr.right - fig.getBoundingClientRect().left ) + 'px' );
			}
			// フェードの高さをスクロール領域に限定（figcaption に被らせない）。
			var top = sc.offsetTop, hgt = sc.offsetHeight;
			if ( L ) { L.style.top = top + 'px'; L.style.height = hgt + 'px'; L.style.bottom = 'auto'; }
			if ( R ) { R.style.top = top + 'px'; R.style.height = hgt + 'px'; R.style.bottom = 'auto'; }
			max = sc.scrollWidth - sc.clientWidth;
			paint();
		}

		// スクロールごとの軽量処理（rAF で 1 フレーム 1 回に集約）。
		function paint() {
			ticking = false;
			var lo = 0, ro = 0, ho = 0;
			if ( max > 1 ) {
				var p = sc.scrollLeft / max; // 0(先頭) 〜 1(末尾)
				lo = clamp( p, 0, 1 );             // 左: 右へ進むほど濃く
				ro = clamp( 1 - p, 0, 1 );         // 右: 末尾へ近づくほど薄く
				ho = clamp( ( max - sc.scrollLeft ) / 40, 0, 1 ); // ガイドは末尾手前まで
			}
			if ( L && lo !== lastL ) { L.style.opacity = lo.toFixed( 3 ); lastL = lo; }
			if ( R && ro !== lastR ) { R.style.opacity = ro.toFixed( 3 ); lastR = ro; }
			if ( hint && ho !== lastH ) { hint.style.opacity = ho.toFixed( 3 ); lastH = ho; }
		}

		function onScroll() {
			if ( ticking ) {
				return;
			}
			ticking = true;
			( window.requestAnimationFrame || function ( f ) { return setTimeout( f, 16 ); } )( paint );
		}

		layout();
		sc.addEventListener( 'scroll', onScroll, { passive: true } );
		if ( window.ResizeObserver ) {
			var obs = new ResizeObserver( layout );
			obs.observe( fig );
			obs.observe( sc );
		} else {
			window.addEventListener( 'resize', layout );
		}
	}

	function init() {
		document.querySelectorAll( '.wp-block-table[data-bcp-scrollable]' ).forEach( attach );
	}

	if ( document.readyState !== 'loading' ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
} )();
