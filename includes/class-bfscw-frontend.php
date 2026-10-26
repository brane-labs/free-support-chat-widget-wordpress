<?php
/**
 * Puts the chat on the public site.
 *
 * @package FreeSupportChatWidgetByBrane
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads the Brane chat loader with the saved settings as data attributes.
 */
class BFSCW_Frontend {

	const HANDLE = 'bfscw-brane-chat';
	const SRC    = 'https://embed.brane.chat/embed.js';

	/**
	 * Hooks into WordPress.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_filter( 'script_loader_tag', array( __CLASS__, 'script_tag' ), 10, 2 );
		add_shortcode( 'brane_chat_button', array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * Enqueues the loader when the chat is switched on.
	 */
	public static function enqueue() {
		if ( ! BFSCW_Settings::is_live() ) {
			return;
		}

		// The loader is versioned by its own host, so no ?ver= is added.
		wp_enqueue_script( self::HANDLE, self::SRC, array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

		// Any element with the class brane-open-chat, or a link to #brane-chat, opens the chat.
		wp_add_inline_script(
			self::HANDLE,
			"document.addEventListener('click',function(e){var t=e.target&&e.target.closest?e.target.closest('.brane-open-chat,a[href=\"#brane-chat\"]'):null;if(!t||!window.Brane)return;e.preventDefault();window.Brane.open();});"
		);
	}

	/**
	 * The data attributes the loader reads, from the saved settings.
	 *
	 * @param array<string, mixed> $settings Saved settings.
	 * @return array<string, string>
	 */
	public static function attributes( $settings ) {
		$attributes = array(
			'data-user'     => $settings['username'],
			'data-theme'    => $settings['theme'],
			'data-position' => $settings['position'],
			'data-primary'  => $settings['primary'],
			'data-secondary' => $settings['secondary'],
		);

		if ( 'none' === $settings['launcher'] ) {
			$attributes['data-launcher'] = 'none';
		}
		if ( '' !== $settings['label'] ) {
			$attributes['data-label'] = $settings['label'];
		}
		if ( '' !== $settings['welcome'] ) {
			$attributes['data-welcome'] = $settings['welcome'];
		}
		foreach ( array( 'ask_photo', 'ask_profile', 'ask_interests' ) as $key ) {
			if ( ! empty( $settings[ $key ] ) ) {
				$attributes[ 'data-' . str_replace( '_', '-', $key ) ] = 'true';
			}
		}
		if ( '' !== $settings['interests'] ) {
			$attributes['data-interests'] = $settings['interests'];
		}

		return $attributes;
	}

	/**
	 * Adds defer and the data attributes to the loader's script tag.
	 *
	 * The loader finds its settings on its own script tag, so they have to be
	 * attributes of that tag rather than a separate configuration object.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string
	 */
	public static function script_tag( $tag, $handle ) {
		if ( self::HANDLE !== $handle ) {
			return $tag;
		}

		$extra = ' defer';
		foreach ( self::attributes( BFSCW_Settings::get() ) as $name => $value ) {
			$extra .= sprintf( ' %s="%s"', $name, esc_attr( $value ) );
		}

		$src_marker = 'src="' . esc_url( self::SRC ) . '"';
		if ( false === strpos( $tag, $src_marker ) ) {
			$src_marker = "src='" . esc_url( self::SRC ) . "'";
		}

		return str_replace( $src_marker, $src_marker . $extra, $tag );
	}

	/**
	 * [brane_chat_button text="Chat with us"] prints a button that opens the chat.
	 *
	 * @param array<string, string>|string $atts Shortcode attributes.
	 * @return string
	 */
	public static function shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'text'  => __( 'Chat with us', 'free-support-chat-widget-by-brane' ),
				'class' => '',
			),
			$atts,
			'brane_chat_button'
		);

		if ( ! BFSCW_Settings::is_live() ) {
			return '';
		}

		$classes = trim( 'brane-open-chat wp-element-button ' . sanitize_html_class( $atts['class'] ) );

		return sprintf(
			'<button type="button" class="%s">%s</button>',
			esc_attr( $classes ),
			esc_html( $atts['text'] )
		);
	}
}
