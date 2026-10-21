/* Life Stages front-end: calculators and demo-mode sign-up forms. */
( function () {
	'use strict';

	function inr( n ) {
		n = Math.round( n );
		return '₹' + n.toLocaleString( 'en-IN' );
	}

	function words( n ) {
		var abs = Math.abs( n );
		if ( abs >= 1e7 ) {
			return ( n / 1e7 ).toFixed( 2 ) + ' crore';
		}
		if ( abs >= 1e5 ) {
			return ( n / 1e5 ).toFixed( 1 ) + ' lakh';
		}
		return '';
	}

	function val( tool, k ) {
		var el = tool.querySelector( '[data-k="' + k + '"]' );
		var v = parseFloat( el && el.value );
		return isFinite( v ) ? v : 0;
	}

	function out( tool, k, text, amount ) {
		var el = tool.querySelector( '[data-out="' + k + '"]' );
		if ( ! el ) {
			return;
		}
		el.textContent = text;
		var sub = el.parentNode.querySelector( 'small' );
		var w = typeof amount === 'number' ? words( amount ) : '';
		if ( w ) {
			if ( ! sub ) {
				sub = document.createElement( 'small' );
				el.parentNode.appendChild( sub );
			}
			sub.textContent = w;
		} else if ( sub ) {
			sub.textContent = '';
		}
	}

	var calc = {
		sip: function ( t ) {
			var p = val( t, 'amount' ), y = val( t, 'years' ), r = val( t, 'rate' ) / 100 / 12, n = y * 12;
			var fv = r > 0 ? p * ( ( Math.pow( 1 + r, n ) - 1 ) / r ) * ( 1 + r ) : p * n;
			var inv = p * n;
			out( t, 'invested', inr( inv ), inv );
			out( t, 'value', inr( fv ), fv );
			out( t, 'gain', inr( fv - inv ), fv - inv );
		},
		inflation: function ( t ) {
			var a = val( t, 'amount' ), y = val( t, 'years' ), r = val( t, 'rate' ) / 100;
			out( t, 'future', inr( a * Math.pow( 1 + r, y ) ), a * Math.pow( 1 + r, y ) );
			out( t, 'power', inr( a / Math.pow( 1 + r, y ) ), a / Math.pow( 1 + r, y ) );
		},
		rebalance: function ( t ) {
			var e = val( t, 'equity' ), d = val( t, 'debt' ), tg = Math.min( 100, Math.max( 0, val( t, 'target' ) ) ) / 100;
			var total = e + d;
			if ( total <= 0 ) {
				out( t, 'mix', '–' );
				out( t, 'action', 'Enter your holdings' );
				return;
			}
			var ep = Math.round( ( e / total ) * 100 );
			out( t, 'mix', ep + '% equity / ' + ( 100 - ep ) + '% debt' );
			var move = tg * total - e;
			if ( Math.abs( move ) < total * 0.005 ) {
				out( t, 'action', 'You’re on target. Nothing to do.' );
			} else if ( move > 0 ) {
				out( t, 'action', 'Move ' + inr( move ) + ' from debt to equity' );
			} else {
				out( t, 'action', 'Move ' + inr( -move ) + ' from equity to debt' );
			}
		}
	};

	document.querySelectorAll( '[data-ls-tool]' ).forEach( function ( tool ) {
		var fn = calc[ tool.getAttribute( 'data-ls-tool' ) ];
		if ( ! fn ) {
			return;
		}
		tool.addEventListener( 'input', function () {
			fn( tool );
		} );
		fn( tool );
	} );

	document.querySelectorAll( 'form[data-ls-demo="1"]' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var msg = form.parentNode.querySelector( '.ls-signup-msg' );
			if ( ! msg ) {
				msg = document.createElement( 'p' );
				msg.className = 'ls-signup-msg';
				msg.setAttribute( 'role', 'status' );
				form.parentNode.insertBefore( msg, form.nextSibling );
			}
			msg.textContent = 'Demo mode: connect your newsletter in Settings → Life Stages to start collecting real sign-ups.';
		} );
	} );
} )();
