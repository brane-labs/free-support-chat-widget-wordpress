/* Free Support Chat Widget by Brane: settings page live preview. */
( function ( $ ) {
	'use strict';

	var i18n = window.bfscwAdmin || {};
	var option = 'bfscw_settings';

	function field( key ) {
		return $( '[name="' + option + '[' + key + ']"]' );
	}

	function radio( key ) {
		return field( key ).filter( ':checked' ).val();
	}

	function color( id, fallback ) {
		var value = $( '#' + id ).val() || '';
		return /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test( value ) ? value : fallback;
	}

	function render() {
		var preview = $( '#bfscw-preview' );
		var username = ( $( '#bfscw-username' ).val() || '' ).trim().replace( /^@/, '' );
		var welcome = ( $( '#bfscw-welcome' ).val() || '' ).trim();
		var launcher = radio( 'launcher' ) || 'bubble';

		preview.attr( 'data-theme', radio( 'theme' ) || 'light' );
		preview.attr( 'data-position', radio( 'position' ) || 'right' );
		preview.attr( 'data-launcher', launcher );
		preview[ 0 ].style.setProperty( '--bfscw-primary', color( 'bfscw-primary', '#111111' ) );
		preview[ 0 ].style.setProperty( '--bfscw-secondary', color( 'bfscw-secondary', '#e5484d' ) );

		$( '#bfscw-mock-name' ).text(
			( i18n.chatWith || 'Chat with %s' ).replace( '%s', username || i18n.yourUsername || 'yourbrand' )
		);
		$( '#bfscw-mock-welcome' ).text( welcome || i18n.defaultWelcome || '' );
		$( '.bfscw-mock-launcher' ).attr( 'title', $( '#bfscw-label' ).val() || i18n.defaultLabel || '' );

		$( '#bfscw-own-button-warning' ).toggle( 'none' === launcher );

		var enabled = $( '#bfscw-enabled' ).is( ':checked' );
		var state = $( '#bfscw-switch-state' );
		if ( enabled && ! username ) {
			state.text( i18n.needsUsername || '' ).attr( 'data-state', 'warn' );
		} else {
			state.text( enabled ? i18n.on || '' : i18n.off || '' ).attr( 'data-state', enabled ? 'on' : 'off' );
		}
	}

	$( function () {
		$( '.bfscw-color' ).wpColorPicker( {
			change: function ( event, ui ) {
				$( event.target ).val( ui.color.toString() );
				render();
			},
			clear: function () {
				setTimeout( render, 0 );
			},
		} );

		$( '.bfscw-form' ).on( 'input change', 'input', render );

		$( '#bfscw-preview-toggle' ).on( 'click', function () {
			var preview = $( '#bfscw-preview' );
			var open = '1' !== preview.attr( 'data-open' );
			preview.attr( 'data-open', open ? '1' : '0' );
			$( this ).attr( 'aria-pressed', open ? 'true' : 'false' );
		} );

		$( '.bfscw-mock-launcher, .bfscw-mock-own' ).on( 'click', function () {
			$( '#bfscw-preview-toggle' ).trigger( 'click' );
		} );

		$( '[data-copy]' ).on( 'click', function () {
			var button = $( this );
			var text = $( button.data( 'copy' ) ).text();
			var done = function () {
				var label = button.text();
				button.text( button.data( 'copied' ) );
				setTimeout( function () {
					button.text( label );
				}, 1500 );
			};
			if ( navigator.clipboard && window.isSecureContext ) {
				navigator.clipboard.writeText( text ).then( done );
			} else {
				var field = $( '<textarea>' ).val( text ).appendTo( 'body' ).trigger( 'select' );
				document.execCommand( 'copy' );
				field.remove();
				done();
			}
		} );

		render();
	} );
} )( jQuery );
