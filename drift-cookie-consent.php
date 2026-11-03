<?php
/**
 * Plugin Name:       Drift: Cookie Consent
 * Plugin URI:        https://driftcreativesystems.co.uk/
 * Description:       Blocks YouTube and Vimeo embeds until marketing consent is given through CookieScript or Cookiebot, and shows a thumbnail with a consent message in their place.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Drift Creative Systems
 * Author URI:        https://driftcreativesystems.co.uk/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       drift-cookie-consent
 *
 * Copyright © 2026 Drift Creative Systems Ltd
 *
 * @package Drift_Cookie_Consent
 */

defined( 'ABSPATH' ) || exit;

/*
|--------------------------------------------------------------------------
| Duplicate install guard
|--------------------------------------------------------------------------
| A second copy (e.g. a GitHub "Source code" zip unpacked alongside the real
| folder) would fatal on redeclared functions. Bail and tell the admin instead.
*/
if ( defined( 'DRIFT_COOKIE_CONSENT_VERSION' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Drift: Cookie Consent is installed more than once. Please delete the duplicate plugin folder.', 'drift-cookie-consent' ) . '</p></div>';
		}
	);
	return;
}

define( 'DRIFT_COOKIE_CONSENT_VERSION', '1.0.0' );
define( 'DRIFT_COOKIE_CONSENT_FILE', __FILE__ );
define( 'DRIFT_COOKIE_CONSENT_PATH', plugin_dir_path( __FILE__ ) );
define( 'DRIFT_COOKIE_CONSENT_URL', plugin_dir_url( __FILE__ ) );
define( 'DRIFT_COOKIE_CONSENT_OPTION', 'drift_cookie_consent_settings' );
define( 'DRIFT_COOKIE_CONSENT_SLUG', 'drift-cookie-consent' );

require_once DRIFT_COOKIE_CONSENT_PATH . 'includes/settings.php';
require_once DRIFT_COOKIE_CONSENT_PATH . 'includes/admin.php';
require_once DRIFT_COOKIE_CONSENT_PATH . 'includes/iframe-blocker.php';
require_once DRIFT_COOKIE_CONSENT_PATH . 'includes/frontend.php';
