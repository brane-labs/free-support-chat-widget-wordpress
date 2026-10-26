<?php
/**
 * Removes the plugin's settings when it is deleted.
 *
 * @package FreeSupportChatWidgetByBrane
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'bfscw_settings' );
