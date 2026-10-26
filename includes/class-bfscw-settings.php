<?php
/**
 * Stored settings and their sanitising.
 *
 * @package FreeSupportChatWidgetByBrane
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reads and sanitises the single settings option.
 */
class BFSCW_Settings {

	const OPTION = 'bfscw_settings';

	/**
	 * Default for every setting. Matches the defaults of the chat itself.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults() {
		return array(
			'enabled'       => false,
			'username'      => '',
			'theme'         => 'light',
			'position'      => 'right',
			'primary'       => '#111111',
			'secondary'     => '#e5484d',
			'launcher'      => 'bubble',
			'label'         => '',
			'welcome'       => '',
			'ask_photo'     => false,
			'ask_profile'   => false,
			'ask_interests' => false,
			'interests'     => '',
		);
	}

	/**
	 * The saved settings, with a default for anything missing.
	 *
	 * @return array<string, mixed>
	 */
	public static function get() {
		$saved = get_option( self::OPTION, array() );
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		return array_merge( self::defaults(), $saved );
	}

	/**
	 * Cleans a username as pasted: drops an @, a profile link and stray spaces.
	 *
	 * @param string $value Raw input.
	 * @return string
	 */
	public static function clean_username( $value ) {
		$value = trim( (string) $value );
		$value = preg_replace( '#^https?://[^/]+/u/#i', '', $value );
		$value = ltrim( $value, '@' );
		$value = preg_replace( '/[^A-Za-z0-9._-]/', '', $value );
		return substr( $value, 0, 64 );
	}

	/**
	 * Cleans a comma separated interest list.
	 *
	 * @param string $value Raw input.
	 * @return string
	 */
	public static function clean_interests( $value ) {
		$parts = array_map( 'trim', explode( ',', (string) $value ) );
		$parts = array_map( 'sanitize_text_field', $parts );
		$parts = array_filter(
			$parts,
			static function ( $part ) {
				return '' !== $part;
			}
		);
		return implode( ',', array_unique( $parts ) );
	}

	/**
	 * Sanitises the whole option on save.
	 *
	 * @param mixed $input Submitted values.
	 * @return array<string, mixed>
	 */
	public static function sanitize( $input ) {
		$defaults = self::defaults();
		$input    = is_array( $input ) ? $input : array();
		$clean    = array();

		$clean['username'] = self::clean_username( isset( $input['username'] ) ? $input['username'] : '' );
		$clean['enabled']  = ! empty( $input['enabled'] ) && '' !== $clean['username'];

		$clean['theme']    = ( isset( $input['theme'] ) && 'dark' === $input['theme'] ) ? 'dark' : 'light';
		$clean['position'] = ( isset( $input['position'] ) && 'left' === $input['position'] ) ? 'left' : 'right';
		$clean['launcher'] = ( isset( $input['launcher'] ) && 'none' === $input['launcher'] ) ? 'none' : 'bubble';

		foreach ( array( 'primary', 'secondary' ) as $key ) {
			$color         = isset( $input[ $key ] ) ? sanitize_hex_color( $input[ $key ] ) : '';
			$clean[ $key ] = $color ? $color : $defaults[ $key ];
		}

		$clean['label']   = isset( $input['label'] ) ? substr( sanitize_text_field( $input['label'] ), 0, 60 ) : '';
		$clean['welcome'] = isset( $input['welcome'] ) ? substr( sanitize_text_field( $input['welcome'] ), 0, 200 ) : '';

		foreach ( array( 'ask_photo', 'ask_profile', 'ask_interests' ) as $key ) {
			$clean[ $key ] = ! empty( $input[ $key ] );
		}

		$clean['interests'] = self::clean_interests( isset( $input['interests'] ) ? $input['interests'] : '' );

		if ( ! empty( $input['enabled'] ) && '' === $clean['username'] && function_exists( 'add_settings_error' ) ) {
			add_settings_error(
				self::OPTION,
				'bfscw_username_required',
				__( 'Add your Brane username before switching the chat on.', 'free-support-chat-widget-by-brane' )
			);
		}

		return $clean;
	}

	/**
	 * Whether the chat should be on the site right now.
	 *
	 * @return bool
	 */
	public static function is_live() {
		$settings = self::get();
		return ! empty( $settings['enabled'] ) && '' !== $settings['username'];
	}
}
