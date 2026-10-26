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
	const ORIGIN = 'https://embed.brane.chat';
	const SRC    = self::ORIGIN . '/embed.js';

	/**
	 * Hooks into WordPress.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_filter( 'script_loader_tag', array( __CLASS__, 'script_tag' ), 10, 2 );
		add_shortcode( 'brane_chat_button', array( __CLASS__, 'shortcode' ) );
		add_shortcode( 'brane_chat_embed', array( __CLASS__, 'embed_shortcode' ) );
	}

	/**
	 * Whether the page being shown carries the page embed.
	 *
	 * @return bool
	 */
	private static function page_has_embed() {
		$post = get_post();
		return is_singular() && $post && has_shortcode( $post->post_content, 'brane_chat_embed' );
	}

	/**
	 * Enqueues the loader when the chat is switched on.
	 */
	public static function enqueue() {
		// A page that already shows the chat in its content does not need the button too.
		if ( ! BFSCW_Settings::is_live() || self::page_has_embed() ) {
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

		// Keeps optimisation plugins (LiteSpeed, WP Rocket, Cloudflare Rocket Loader)
		// from delaying, combining or rewriting the loader, which reads its own tag.
		$extra = ' defer data-no-optimize="1" data-no-defer="1" data-cfasync="false" nowprocket';
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

	/**
	 * The chat's own page, for an iframe: the same settings the loader passes it.
	 *
	 * @param array<string, mixed> $settings Saved settings.
	 * @param string               $origin   The page the chat sits on, without its query string.
	 * @return string
	 */
	public static function embed_url( $settings, $origin ) {
		$params = array(
			'inline'    => 'true',
			'primary'   => $settings['primary'],
			'secondary' => $settings['secondary'],
			'theme'     => $settings['theme'],
		);

		if ( '' !== $settings['welcome'] ) {
			$params['welcome'] = $settings['welcome'];
		}
		foreach ( array( 'ask_photo', 'ask_profile', 'ask_interests' ) as $key ) {
			if ( ! empty( $settings[ $key ] ) ) {
				$params[ $key ] = 'true';
			}
		}
		if ( '' !== $settings['interests'] ) {
			$params['interests'] = $settings['interests'];
		}
		if ( '' !== $origin ) {
			$params['origin'] = $origin;
		}

		return self::ORIGIN . '/u/' . rawurlencode( $settings['username'] ) . '?' . http_build_query( $params, '', '&', PHP_QUERY_RFC3986 );
	}

	/**
	 * Cleans a height: a number of pixels, or a number with px, vh, em or rem.
	 *
	 * @param string $value Raw shortcode value.
	 * @return string
	 */
	public static function clean_height( $value ) {
		$value = strtolower( trim( (string) $value ) );
		if ( preg_match( '/^(\d{2,4})(px|vh|em|rem)?$/', $value, $match ) ) {
			return $match[1] . ( isset( $match[2] ) && '' !== $match[2] ? $match[2] : 'px' );
		}
		return '640px';
	}

	/**
	 * [brane_chat_embed height="640"] puts the chat in the page, full width.
	 *
	 * Shown wherever it is pasted, whether or not the floating chat is switched on.
	 *
	 * @param array<string, string>|string $atts Shortcode attributes.
	 * @return string
	 */
	public static function embed_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'height' => '640',
			),
			$atts,
			'brane_chat_embed'
		);

		$settings = BFSCW_Settings::get();
		if ( '' === $settings['username'] ) {
			return '';
		}

		$post   = get_post();
		$origin = $post ? (string) get_permalink( $post ) : home_url( '/' );
		$origin = (string) strtok( $origin, '?#' );

		return sprintf(
			'<div class="brane-chat-embed" style="width:100%%;max-width:100%%;"><iframe src="%1$s" title="%2$s" style="display:block;width:100%%;height:%3$s;border:0;border-radius:12px;background:%4$s;" allow="clipboard-write; autoplay" loading="lazy"></iframe></div>',
			esc_url( self::embed_url( $settings, $origin ) ),
			esc_attr__( 'Chat with us', 'free-support-chat-widget-by-brane' ),
			esc_attr( self::clean_height( $atts['height'] ) ),
			'dark' === $settings['theme'] ? '#202124' : '#e9ecf0'
		);
	}
}
