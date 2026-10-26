<?php
/**
 * The shared "Brane" admin menu.
 *
 * Every Brane Labs plugin ships this same file. Whichever plugin loads first
 * defines the functions, the menu is registered once, and each plugin then adds
 * its own page beneath it with add_submenu_page( BRANE_LABS_MENU_SLUG, ... ) and
 * a card on the overview page through the brane_labs_menu_items filter.
 *
 * @package FreeSupportChatWidgetByBrane
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'BRANE_LABS_MENU_SLUG' ) ) {
	define( 'BRANE_LABS_MENU_SLUG', 'brane' );
}

if ( ! function_exists( 'brane_labs_app_links' ) ) {
	/**
	 * Store links for the Brane app.
	 *
	 * @return array{ios: string, android: string}
	 */
	function brane_labs_app_links() {
		return array(
			'ios'     => 'https://apps.apple.com/app/brane-find-your-people/id1525970435',
			'android' => 'https://play.google.com/store/apps/details?id=app.brane.messenger',
		);
	}
}

if ( ! function_exists( 'brane_labs_menu_icon' ) ) {
	/**
	 * The menu icon, as a data URI WordPress recolours to match the admin scheme.
	 *
	 * @return string
	 */
	function brane_labs_menu_icon() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="black" d="M4 2h12a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-3l-3 3-3-3H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm2.5 4.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm3.5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm3.5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/></svg>';
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Encodes the menu icon for a data URI.
		return 'data:image/svg+xml;base64,' . base64_encode( $svg );
	}
}

if ( ! function_exists( 'brane_labs_register_menu' ) ) {
	/**
	 * Registers the top level Brane menu and its Overview page, once.
	 */
	function brane_labs_register_menu() {
		global $admin_page_hooks;

		if ( ! empty( $admin_page_hooks[ BRANE_LABS_MENU_SLUG ] ) ) {
			return;
		}

		add_menu_page(
			__( 'Brane', 'free-support-chat-widget-by-brane' ),
			__( 'Brane', 'free-support-chat-widget-by-brane' ),
			'manage_options',
			BRANE_LABS_MENU_SLUG,
			'brane_labs_render_overview',
			brane_labs_menu_icon(),
			58
		);

		// Renames the automatic first submenu entry from "Brane" to "Overview".
		add_submenu_page(
			BRANE_LABS_MENU_SLUG,
			__( 'Brane', 'free-support-chat-widget-by-brane' ),
			__( 'Overview', 'free-support-chat-widget-by-brane' ),
			'manage_options',
			BRANE_LABS_MENU_SLUG,
			'brane_labs_render_overview'
		);
	}
}
add_action( 'admin_menu', 'brane_labs_register_menu', 9 );

if ( ! function_exists( 'brane_labs_render_overview' ) ) {
	/**
	 * Renders the Overview page: one card per installed Brane plugin.
	 */
	function brane_labs_render_overview() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/**
		 * Filters the cards on the Brane overview page.
		 *
		 * @param array[] $items Each item has title, description, url and an optional status.
		 */
		$items = apply_filters( 'brane_labs_menu_items', array() );
		$links = brane_labs_app_links();
		?>
		<div class="wrap brane-labs-overview">
			<h1><?php esc_html_e( 'Brane', 'free-support-chat-widget-by-brane' ); ?></h1>
			<p><?php esc_html_e( 'Tools from Brane Labs for talking to the people who visit your site.', 'free-support-chat-widget-by-brane' ); ?></p>

			<div class="brane-labs-cards">
				<?php foreach ( $items as $item ) : ?>
					<div class="brane-labs-card">
						<h2><?php echo esc_html( $item['title'] ); ?></h2>
						<?php if ( ! empty( $item['status'] ) ) : ?>
							<p class="brane-labs-status"><?php echo esc_html( $item['status'] ); ?></p>
						<?php endif; ?>
						<p><?php echo esc_html( $item['description'] ); ?></p>
						<p><a class="button button-primary" href="<?php echo esc_url( $item['url'] ); ?>"><?php esc_html_e( 'Open', 'free-support-chat-widget-by-brane' ); ?></a></p>
					</div>
				<?php endforeach; ?>
			</div>

			<h2><?php esc_html_e( 'Get the Brane app', 'free-support-chat-widget-by-brane' ); ?></h2>
			<p><?php esc_html_e( 'Every conversation lands in the Brane app on your phone.', 'free-support-chat-widget-by-brane' ); ?></p>
			<p>
				<a class="button" href="<?php echo esc_url( $links['ios'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'App Store', 'free-support-chat-widget-by-brane' ); ?></a>
				<a class="button" href="<?php echo esc_url( $links['android'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Google Play', 'free-support-chat-widget-by-brane' ); ?></a>
			</p>
		</div>
		<?php
	}
}
