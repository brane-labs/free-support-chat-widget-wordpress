<?php
/**
 * Plugin Name:       Free Support Chat Widget by Brane
 * Plugin URI:        https://github.com/brane-labs/free-support-chat-widget-wordpress
 * Description:       A free live support chat widget. Visitors message you from any page and you answer from the Brane app on your phone, with instant notifications.
 * Version:           1.0.2
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Brane Labs
 * Author URI:        https://brane.app/business/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       free-support-chat-widget-by-brane
 * Domain Path:       /languages
 *
 * @package FreeSupportChatWidgetByBrane
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BFSCW_VERSION', '1.0.2' );
define( 'BFSCW_FILE', __FILE__ );
define( 'BFSCW_DIR', plugin_dir_path( __FILE__ ) );
define( 'BFSCW_URL', plugin_dir_url( __FILE__ ) );

require_once BFSCW_DIR . 'includes/brane-menu.php';
require_once BFSCW_DIR . 'includes/class-bfscw-settings.php';
require_once BFSCW_DIR . 'includes/class-bfscw-admin.php';
require_once BFSCW_DIR . 'includes/class-bfscw-frontend.php';

BFSCW_Admin::init();
BFSCW_Frontend::init();

/**
 * Adds a Settings link beside Deactivate on the Plugins screen.
 *
 * @param string[] $links Existing action links.
 * @return string[]
 */
function bfscw_action_links( $links ) {
	$settings = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'admin.php?page=' . BFSCW_Admin::PAGE ) ),
		esc_html__( 'Settings', 'free-support-chat-widget-by-brane' )
	);
	array_unshift( $links, $settings );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bfscw_action_links' );

/**
 * Clears page caches after the settings change, so a cached page cannot keep
 * showing the site as it was before the chat was switched on or off.
 */
function bfscw_purge_page_caches() {
	// LiteSpeed Cache.
	do_action( 'litespeed_purge_all' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- LiteSpeed Cache's own purge hook.
	// WP Rocket.
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	// W3 Total Cache.
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	// WP Super Cache.
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
	// SiteGround Optimizer.
	if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
		sg_cachepress_purge_cache();
	}
	// Cache Enabler.
	do_action( 'cache_enabler_clear_complete_cache' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Cache Enabler's own purge hook.
}
add_action( 'add_option_' . BFSCW_Settings::OPTION, 'bfscw_purge_page_caches' );
add_action( 'update_option_' . BFSCW_Settings::OPTION, 'bfscw_purge_page_caches' );
