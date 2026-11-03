<?php
/**
 * Front-end assets: the placeholder styles and script, with the settings
 * passed through as DRIFT_COOKIE_CONSENT.
 *
 * @package Drift_Cookie_Consent
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the front-end styles and script.
 *
 * @return void
 */
function drift_cookie_consent_enqueue_assets() {
	$options = drift_cookie_consent_get_options();

	wp_enqueue_style(
		'drift-cookie-consent',
		DRIFT_COOKIE_CONSENT_URL . 'assets/front.css',
		array(),
		DRIFT_COOKIE_CONSENT_VERSION
	);

	wp_enqueue_script(
		'drift-cookie-consent',
		DRIFT_COOKIE_CONSENT_URL . 'assets/front.js',
		array(),
		DRIFT_COOKIE_CONSENT_VERSION,
		true
	);

	wp_localize_script(
		'drift-cookie-consent',
		'DRIFT_COOKIE_CONSENT',
		array(
			'consentManager'  => $options['consent_manager'],
			'cookieCategory'  => $options['cookie_category'],
			'defaultBgImage'  => $options['default_bg_image'],
			'consentText'     => $options['consent_text'],
			'consentLinkUrl'  => $options['consent_link_url'],
			'consentLinkText' => $options['consent_link_label'],
		)
	);
}
add_action( 'wp_enqueue_scripts', 'drift_cookie_consent_enqueue_assets' );
