<?php
/**
 * Settings → Drift: Cookie Consent: the settings screen, its styles, the
 * admin footer and the Plugins-row link.
 *
 * @package Drift_Cookie_Consent
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Settings → Drift: Cookie Consent.
 *
 * @return void
 */
function drift_cookie_consent_add_admin_menu() {
	add_options_page(
		__( 'Drift: Cookie Consent', 'drift-cookie-consent' ),
		__( 'Drift: Cookie Consent', 'drift-cookie-consent' ),
		'manage_options',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_render_settings_page'
	);
}
add_action( 'admin_menu', 'drift_cookie_consent_add_admin_menu' );

/**
 * Whether the current admin screen is the settings screen.
 *
 * @return bool
 */
function drift_cookie_consent_is_screen() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	return $screen && 'settings_page_' . DRIFT_COOKIE_CONSENT_SLUG === $screen->id;
}

/**
 * Styles for the settings screen, on that screen only.
 *
 * @param string $hook_suffix Current admin page.
 * @return void
 */
function drift_cookie_consent_admin_assets( $hook_suffix ) {
	if ( 'settings_page_' . DRIFT_COOKIE_CONSENT_SLUG !== $hook_suffix ) {
		return;
	}

	wp_enqueue_style( 'drift-cookie-consent-admin', DRIFT_COOKIE_CONSENT_URL . 'assets/admin.css', array(), DRIFT_COOKIE_CONSENT_VERSION );
}
add_action( 'admin_enqueue_scripts', 'drift_cookie_consent_admin_assets' );

/**
 * Copyright and company details in the admin footer, on the settings screen only.
 *
 * @param string $text Footer text.
 * @return string
 */
function drift_cookie_consent_footer_text( $text ) {
	if ( ! drift_cookie_consent_is_screen() ) {
		return $text;
	}

	return 'Copyright &copy; ' . esc_html( wp_date( 'Y' ) ) . ' Drift Creative Systems Ltd &middot; Registered in England and Wales, company number 17548220. Registered office: 167-169 Great Portland Street, 5th Floor, London W1W 5PF';
}
add_filter( 'admin_footer_text', 'drift_cookie_consent_footer_text' );

/**
 * "Settings" link on the plugin's row on the Plugins screen.
 *
 * @param array $links Action links.
 * @return array
 */
function drift_cookie_consent_action_links( $links ) {
	if ( current_user_can( 'manage_options' ) ) {
		array_unshift(
			$links,
			'<a href="' . esc_url( admin_url( 'options-general.php?page=' . DRIFT_COOKIE_CONSENT_SLUG ) ) . '">' . esc_html__( 'Settings', 'drift-cookie-consent' ) . '</a>'
		);
	}

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( DRIFT_COOKIE_CONSENT_FILE ), 'drift_cookie_consent_action_links' );

/**
 * Drift monogram. Fixed markup; only the fill is variable.
 *
 * @param string $fill Fill colour.
 * @param int    $size Width and height in px.
 * @return string
 */
function drift_cookie_consent_mark_svg( $fill = 'currentColor', $size = 32 ) {
	$paths = array(
		'M17 12H54C77 12 91 29 91 50C91 72 76 88 54 88H45L55 76H54C70 76 79 64 79 50C79 35 69 24 54 24H29V44H17Z',
		'M17 55H29L17 67Z',
		'M41 55H54L25 88H12Z',
		'M55 76H70L59 88H43Z',
	);

	$svg = sprintf( '<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 100 100" aria-hidden="true" focusable="false">', (int) $size );
	foreach ( $paths as $d ) {
		$svg .= '<path fill="' . esc_attr( $fill ) . '" d="' . esc_attr( $d ) . '"/>';
	}

	return $svg . '</svg>';
}

/**
 * Print the settings screen.
 *
 * @return void
 */
function drift_cookie_consent_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap drift-cookie-consent">
		<h1 class="drift-cookie-consent__title">
			<span class="drift-cookie-consent__mark"><?php echo drift_cookie_consent_mark_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed markup, attributes escaped in drift_cookie_consent_mark_svg(). ?></span>
			<?php echo esc_html( get_admin_page_title() ); ?>
		</h1>

		<p class="drift-cookie-consent__lead">
			<?php esc_html_e( 'YouTube and Vimeo videos are blocked until the visitor accepts marketing cookies through CookieScript or Cookiebot. Until then, a thumbnail with a consent message shows in their place.', 'drift-cookie-consent' ); ?>
		</p>

		<form method="post" action="options.php">
			<section class="drift-cookie-consent__card">
				<?php
				settings_fields( 'drift_cookie_consent' );
				do_settings_sections( DRIFT_COOKIE_CONSENT_SLUG );
				?>
			</section>
			<?php submit_button(); ?>
		</form>

		<div class="drift-cookie-consent__help">
			<p>
				<?php
				printf(
					/* translators: %s: filter name */
					esc_html__( 'Developers: videos are blocked in the page HTML before it is sent. Return false from the %s filter to leave that to the front-end script only.', 'drift-cookie-consent' ),
					'<code>drift_cookie_consent_block_iframes</code>'
				);
				?>
			</p>
		</div>
	</div>
	<?php
}
