<?php
/**
 * Plugin Name:       Free Support Chat Widget by Brane
 * Plugin URI:        https://github.com/brane-labs/free-support-chat-widget-wordpress
 * Description:       A free live support chat widget. Visitors message you from any page and you answer from the Brane app on your phone, with instant notifications.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Brane Labs
 * Author URI:        https://brane.app
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

define( 'BFSCW_VERSION', '1.0.0' );
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
