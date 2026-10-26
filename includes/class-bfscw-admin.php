<?php
/**
 * The Support Chat settings page under the Brane menu.
 *
 * @package FreeSupportChatWidgetByBrane
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the settings page.
 */
class BFSCW_Admin {

	const PAGE  = 'brane-support-chat';
	const GROUP = 'bfscw_settings_group';

	/**
	 * Hooks into WordPress.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
		add_filter( 'brane_labs_menu_items', array( __CLASS__, 'overview_card' ) );
	}

	/**
	 * Adds the page beneath the Brane menu.
	 */
	public static function menu() {
		add_submenu_page(
			BRANE_LABS_MENU_SLUG,
			__( 'Support Chat', 'free-support-chat-widget' ),
			__( 'Support Chat', 'free-support-chat-widget' ),
			'manage_options',
			self::PAGE,
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Registers the option.
	 */
	public static function register() {
		register_setting(
			self::GROUP,
			BFSCW_Settings::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'BFSCW_Settings', 'sanitize' ),
				'default'           => BFSCW_Settings::defaults(),
			)
		);
	}

	/**
	 * Loads the colour picker, styles and live preview on Brane pages only.
	 *
	 * @param string $hook The current admin page.
	 */
	public static function assets( $hook ) {
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only picks which screen loads assets.
		if ( BRANE_LABS_MENU_SLUG !== $page && self::PAGE !== $page ) {
			return;
		}

		wp_enqueue_style( 'bfscw-admin', BFSCW_URL . 'assets/css/admin.css', array(), BFSCW_VERSION );

		if ( self::PAGE !== $page ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'bfscw-admin', BFSCW_URL . 'assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), BFSCW_VERSION, true );
		wp_localize_script(
			'bfscw-admin',
			'bfscwAdmin',
			array(
				'defaultLabel'   => __( 'Open chat', 'free-support-chat-widget' ),
				'defaultWelcome' => __( 'This is not a chatbot, you are about to message a real person.', 'free-support-chat-widget' ),
				/* translators: %s: a Brane username. */
				'chatWith'       => __( 'Chat with %s', 'free-support-chat-widget' ),
				'yourUsername'   => __( 'yourbrand', 'free-support-chat-widget' ),
				'savedOn'        => BFSCW_Settings::is_live(),
				'on'             => __( 'On. The floating chat is live on your site.', 'free-support-chat-widget' ),
				'off'            => __( 'Off. The floating chat is not on your site.', 'free-support-chat-widget' ),
				'saveToTurnOn'   => __( 'Click Save changes to put the chat on your site.', 'free-support-chat-widget' ),
				'saveToTurnOff'  => __( 'Click Save changes to take the chat off your site.', 'free-support-chat-widget' ),
				'needsUsername'  => __( 'Add your username first.', 'free-support-chat-widget' ),
			)
		);
	}

	/**
	 * This plugin's card on the Brane overview page.
	 *
	 * @param array[] $items Cards so far.
	 * @return array[]
	 */
	public static function overview_card( $items ) {
		$items[] = array(
			'title'       => __( 'Support Chat', 'free-support-chat-widget' ),
			'status'      => BFSCW_Settings::is_live() ? __( 'Live on your site', 'free-support-chat-widget' ) : __( 'Switched off', 'free-support-chat-widget' ),
			'description' => __( 'A free chat widget. Visitors message you from any page, and you reply from the Brane app.', 'free-support-chat-widget' ),
			'url'         => admin_url( 'admin.php?page=' . self::PAGE ),
		);
		return $items;
	}

	/**
	 * Prints a field name for the option array.
	 *
	 * @param string $key Setting key.
	 */
	private static function name( $key ) {
		echo esc_attr( BFSCW_Settings::OPTION . '[' . $key . ']' );
	}

	/**
	 * Renders the page.
	 */
	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$s     = BFSCW_Settings::get();
		$links = brane_labs_app_links();
		?>
		<div class="wrap bfscw">
			<h1><?php esc_html_e( 'Free Support Chat Widget', 'free-support-chat-widget' ); ?></h1>
			<p class="bfscw-lead">
				<?php esc_html_e( 'Talk to your visitors from your phone. Three quick steps, pick your look, then switch it on.', 'free-support-chat-widget' ); ?>
				<?php if ( BFSCW_Settings::is_live() ) : ?>
					<span class="bfscw-pill bfscw-pill-on"><?php esc_html_e( 'Live', 'free-support-chat-widget' ); ?></span>
				<?php else : ?>
					<span class="bfscw-pill"><?php esc_html_e( 'Off', 'free-support-chat-widget' ); ?></span>
				<?php endif; ?>
			</p>

			<?php settings_errors(); ?>

			<form method="post" action="options.php" class="bfscw-form">
				<?php settings_fields( self::GROUP ); ?>

				<div class="bfscw-layout">
					<div class="bfscw-main">

						<section class="bfscw-card">
							<h2><span class="bfscw-step">1</span><?php esc_html_e( 'Get the Brane app', 'free-support-chat-widget' ); ?></h2>
							<p><?php esc_html_e( 'Your visitors\' messages arrive in the free Brane app on your phone, and you reply from there.', 'free-support-chat-widget' ); ?></p>
							<p class="bfscw-stores">
								<a class="bfscw-store" href="<?php echo esc_url( $links['ios'] ); ?>" target="_blank" rel="noopener noreferrer">
									<span class="bfscw-store-small"><?php esc_html_e( 'Download on the', 'free-support-chat-widget' ); ?></span>
									<span class="bfscw-store-big"><?php esc_html_e( 'App Store', 'free-support-chat-widget' ); ?></span>
								</a>
								<a class="bfscw-store" href="<?php echo esc_url( $links['android'] ); ?>" target="_blank" rel="noopener noreferrer">
									<span class="bfscw-store-small"><?php esc_html_e( 'Get it on', 'free-support-chat-widget' ); ?></span>
									<span class="bfscw-store-big"><?php esc_html_e( 'Google Play', 'free-support-chat-widget' ); ?></span>
								</a>
							</p>
							<p class="bfscw-tip">
								<span class="dashicons dashicons-bell" aria-hidden="true"></span>
								<?php esc_html_e( 'Allow notifications when the app asks. That is how you hear about a new message the moment it arrives, so nobody waits for an answer.', 'free-support-chat-widget' ); ?>
							</p>
						</section>

						<section class="bfscw-card">
							<h2><span class="bfscw-step">2</span><?php esc_html_e( 'Choose a username with your brand', 'free-support-chat-widget' ); ?></h2>
							<p><?php esc_html_e( 'Sign up in the app and pick a username your customers will recognise, for example yourbrand or yourbrandsupport (letters and numbers only). It is shown at the top of the chat.', 'free-support-chat-widget' ); ?></p>
							<p><?php esc_html_e( 'Add your logo as the profile photo so visitors know they are talking to you.', 'free-support-chat-widget' ); ?></p>
						</section>

						<section class="bfscw-card">
							<h2><span class="bfscw-step">3</span><label for="bfscw-username"><?php esc_html_e( 'Paste your username here', 'free-support-chat-widget' ); ?></label></h2>
							<div class="bfscw-username">
								<span class="bfscw-at" aria-hidden="true">@</span>
								<input type="text" id="bfscw-username" class="regular-text" name="<?php self::name( 'username' ); ?>" value="<?php echo esc_attr( $s['username'] ); ?>" placeholder="<?php esc_attr_e( 'yourbrand', 'free-support-chat-widget' ); ?>" autocomplete="off" spellcheck="false" />
							</div>
							<p class="description"><?php esc_html_e( 'Exactly as it appears in the app. You can paste your profile link too.', 'free-support-chat-widget' ); ?></p>
						</section>

						<section class="bfscw-card">
							<h2><span class="bfscw-step">4</span><?php esc_html_e( 'Make it yours', 'free-support-chat-widget' ); ?></h2>

							<table class="form-table" role="presentation">
								<tr>
									<th scope="row"><?php esc_html_e( 'Theme', 'free-support-chat-widget' ); ?></th>
									<td>
										<fieldset class="bfscw-segmented">
											<legend class="screen-reader-text"><?php esc_html_e( 'Theme', 'free-support-chat-widget' ); ?></legend>
											<label><input type="radio" name="<?php self::name( 'theme' ); ?>" value="light" <?php checked( $s['theme'], 'light' ); ?> /><span><?php esc_html_e( 'Light', 'free-support-chat-widget' ); ?></span></label>
											<label><input type="radio" name="<?php self::name( 'theme' ); ?>" value="dark" <?php checked( $s['theme'], 'dark' ); ?> /><span><?php esc_html_e( 'Dark', 'free-support-chat-widget' ); ?></span></label>
										</fieldset>
										<p class="description"><?php esc_html_e( 'Where the chat starts. Visitors can switch it themselves.', 'free-support-chat-widget' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Position', 'free-support-chat-widget' ); ?></th>
									<td>
										<fieldset class="bfscw-segmented">
											<legend class="screen-reader-text"><?php esc_html_e( 'Position', 'free-support-chat-widget' ); ?></legend>
											<label><input type="radio" name="<?php self::name( 'position' ); ?>" value="left" <?php checked( $s['position'], 'left' ); ?> /><span><?php esc_html_e( 'Bottom left', 'free-support-chat-widget' ); ?></span></label>
											<label><input type="radio" name="<?php self::name( 'position' ); ?>" value="right" <?php checked( $s['position'], 'right' ); ?> /><span><?php esc_html_e( 'Bottom right', 'free-support-chat-widget' ); ?></span></label>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="bfscw-primary"><?php esc_html_e( 'Brand colour', 'free-support-chat-widget' ); ?></label></th>
									<td>
										<input type="text" id="bfscw-primary" class="bfscw-color" name="<?php self::name( 'primary' ); ?>" value="<?php echo esc_attr( $s['primary'] ); ?>" data-default-color="#111111" />
										<p class="description"><?php esc_html_e( 'The chat button and the chat itself. Pick a colour or type a hex code like #ff0066.', 'free-support-chat-widget' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="bfscw-secondary"><?php esc_html_e( 'Unread badge colour', 'free-support-chat-widget' ); ?></label></th>
									<td>
										<input type="text" id="bfscw-secondary" class="bfscw-color" name="<?php self::name( 'secondary' ); ?>" value="<?php echo esc_attr( $s['secondary'] ); ?>" data-default-color="#e5484d" />
										<p class="description"><?php esc_html_e( 'The little count on the button when you have replied.', 'free-support-chat-widget' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="bfscw-welcome"><?php esc_html_e( 'Welcome message', 'free-support-chat-widget' ); ?></label></th>
									<td>
										<input type="text" id="bfscw-welcome" class="large-text" maxlength="200" name="<?php self::name( 'welcome' ); ?>" value="<?php echo esc_attr( $s['welcome'] ); ?>" placeholder="<?php esc_attr_e( 'Leave empty for the default', 'free-support-chat-widget' ); ?>" />
										<p class="description"><?php esc_html_e( 'Shown to a visitor before they start, above the email field.', 'free-support-chat-widget' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Chat button', 'free-support-chat-widget' ); ?></th>
									<td>
										<fieldset class="bfscw-segmented">
											<legend class="screen-reader-text"><?php esc_html_e( 'Chat button', 'free-support-chat-widget' ); ?></legend>
											<label><input type="radio" name="<?php self::name( 'launcher' ); ?>" value="bubble" <?php checked( $s['launcher'], 'bubble' ); ?> /><span><?php esc_html_e( 'Floating button', 'free-support-chat-widget' ); ?></span></label>
											<label><input type="radio" name="<?php self::name( 'launcher' ); ?>" value="none" <?php checked( $s['launcher'], 'none' ); ?> /><span><?php esc_html_e( 'My own button only', 'free-support-chat-widget' ); ?></span></label>
										</fieldset>
										<div class="bfscw-own-button">
											<p class="description"><?php esc_html_e( 'Any of these opens the chat, with or without the floating button:', 'free-support-chat-widget' ); ?></p>
											<ul>
												<li><?php esc_html_e( 'The shortcode', 'free-support-chat-widget' ); ?> <code>[brane_chat_button text="Chat with us"]</code></li>
												<li><?php esc_html_e( 'A menu link or button whose URL is', 'free-support-chat-widget' ); ?> <code>#brane-chat</code></li>
												<li><?php esc_html_e( 'Any element with the CSS class', 'free-support-chat-widget' ); ?> <code>brane-open-chat</code></li>
											</ul>
											<p class="description bfscw-warning" id="bfscw-own-button-warning"><?php esc_html_e( 'With no floating button, one of these must be on your pages, or nobody can open the chat.', 'free-support-chat-widget' ); ?></p>
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="bfscw-label"><?php esc_html_e( 'Button label', 'free-support-chat-widget' ); ?></label></th>
									<td>
										<input type="text" id="bfscw-label" class="regular-text" maxlength="60" name="<?php self::name( 'label' ); ?>" value="<?php echo esc_attr( $s['label'] ); ?>" placeholder="<?php esc_attr_e( 'Open chat', 'free-support-chat-widget' ); ?>" />
										<p class="description"><?php esc_html_e( 'Read out by screen readers and shown on hover.', 'free-support-chat-widget' ); ?></p>
									</td>
								</tr>
							</table>

							<details class="bfscw-advanced">
								<summary><?php esc_html_e( 'What visitors are asked when they start', 'free-support-chat-widget' ); ?></summary>
								<p class="description"><?php esc_html_e( 'By default a visitor gives an email address and a date of birth, and nothing else, so they are talking to you in about twenty seconds. Every step you add slows that down.', 'free-support-chat-widget' ); ?></p>
								<p><label><input type="checkbox" name="<?php self::name( 'ask_photo' ); ?>" value="1" <?php checked( $s['ask_photo'] ); ?> /> <?php esc_html_e( 'Ask for a profile photo', 'free-support-chat-widget' ); ?></label></p>
								<p><label><input type="checkbox" name="<?php self::name( 'ask_profile' ); ?>" value="1" <?php checked( $s['ask_profile'] ); ?> /> <?php esc_html_e( 'Ask for a name and short bio', 'free-support-chat-widget' ); ?></label></p>
								<p><label><input type="checkbox" name="<?php self::name( 'ask_interests' ); ?>" value="1" <?php checked( $s['ask_interests'] ); ?> /> <?php esc_html_e( 'Ask what they are into', 'free-support-chat-widget' ); ?></label></p>
								<p>
									<label for="bfscw-interests"><?php esc_html_e( 'Interests to save for new visitors (optional, comma separated)', 'free-support-chat-widget' ); ?></label><br />
									<input type="text" id="bfscw-interests" class="regular-text" name="<?php self::name( 'interests' ); ?>" value="<?php echo esc_attr( $s['interests'] ); ?>" placeholder="<?php esc_attr_e( 'running,cycling', 'free-support-chat-widget' ); ?>" />
								</p>
							</details>
						</section>

						<section class="bfscw-card bfscw-switch-card">
							<h2><span class="bfscw-step">5</span><?php esc_html_e( 'Switch it on', 'free-support-chat-widget' ); ?></h2>
							<label class="bfscw-switch">
								<input type="hidden" name="<?php self::name( 'enabled' ); ?>" value="0" />
								<input type="checkbox" id="bfscw-enabled" name="<?php self::name( 'enabled' ); ?>" value="1" <?php checked( $s['enabled'] ); ?> />
								<span class="bfscw-slider" aria-hidden="true"></span>
								<span class="bfscw-switch-label"><?php esc_html_e( 'Show the floating support chat on every page', 'free-support-chat-widget' ); ?></span>
							</label>
							<p class="bfscw-switch-state" id="bfscw-switch-state" aria-live="polite"></p>
						</section>

						<section class="bfscw-card">
							<h2><span class="bfscw-step"><span class="dashicons dashicons-editor-code" aria-hidden="true"></span></span><?php esc_html_e( 'Embed on a page', 'free-support-chat-widget' ); ?></h2>
							<p><?php esc_html_e( 'Put the chat right inside a page, full width, for example on your Contact or Support page. Paste this shortcode into the page, in a Shortcode block or any text block:', 'free-support-chat-widget' ); ?></p>
							<p class="bfscw-copy-row">
								<code id="bfscw-embed-code">[brane_chat_embed]</code>
								<button type="button" class="button" data-copy="#bfscw-embed-code" data-copied="<?php esc_attr_e( 'Copied', 'free-support-chat-widget' ); ?>"><?php esc_html_e( 'Copy', 'free-support-chat-widget' ); ?></button>
							</p>
							<p class="description"><?php esc_html_e( 'It uses your username, colours and theme from above. It is 640 pixels tall unless you set a height, for example [brane_chat_embed height="800"] or [brane_chat_embed height="80vh"].', 'free-support-chat-widget' ); ?></p>
							<p class="description"><?php esc_html_e( 'The page embed shows wherever you paste it, even with the floating chat switched off. On a page that has it, the floating button is left off so the chat is not there twice.', 'free-support-chat-widget' ); ?></p>
						</section>

						<?php submit_button( __( 'Save changes', 'free-support-chat-widget' ) ); ?>
					</div>

					<aside class="bfscw-side">
						<div class="bfscw-preview-card">
							<div class="bfscw-preview-head">
								<h2><?php esc_html_e( 'Preview', 'free-support-chat-widget' ); ?></h2>
								<button type="button" class="button button-small" id="bfscw-preview-toggle" aria-pressed="true"><?php esc_html_e( 'Open / close', 'free-support-chat-widget' ); ?></button>
							</div>
							<div class="bfscw-preview" id="bfscw-preview" data-open="1">
								<div class="bfscw-mock-page" aria-hidden="true">
									<div class="bfscw-mock-bar"></div>
									<div class="bfscw-mock-line w70"></div>
									<div class="bfscw-mock-line w90"></div>
									<div class="bfscw-mock-line w50"></div>
									<div class="bfscw-mock-own"><?php esc_html_e( 'Chat with us', 'free-support-chat-widget' ); ?></div>
								</div>
								<div class="bfscw-mock-panel" aria-hidden="true">
									<div class="bfscw-mock-panel-top"><span class="bfscw-mock-menu"></span></div>
									<div class="bfscw-mock-avatar"></div>
									<div class="bfscw-mock-name" id="bfscw-mock-name"></div>
									<div class="bfscw-mock-welcome" id="bfscw-mock-welcome"></div>
									<div class="bfscw-mock-input"><?php esc_html_e( 'Email', 'free-support-chat-widget' ); ?></div>
									<div class="bfscw-mock-button"><?php esc_html_e( 'Continue', 'free-support-chat-widget' ); ?></div>
								</div>
								<div class="bfscw-mock-launcher" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4l4 4 4-4h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="#ffffff" stroke-width="2" stroke-linejoin="round"/></svg>
									<span class="bfscw-mock-badge">1</span>
								</div>
							</div>
							<p class="description"><?php esc_html_e( 'A sketch of the chat on your site. Save, then visit your site to see the real thing.', 'free-support-chat-widget' ); ?></p>
							<?php if ( BFSCW_Settings::is_live() ) : ?>
								<p><a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View your site', 'free-support-chat-widget' ); ?></a></p>
							<?php endif; ?>
						</div>
					</aside>
				</div>
			</form>
		</div>
		<?php
	}
}
