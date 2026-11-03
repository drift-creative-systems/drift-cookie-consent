<?php
/**
 * Removes the plugin's settings when it is deleted.
 *
 * @package Drift_Cookie_Consent
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

if ( is_multisite() ) {
	$drift_cookie_consent_site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $drift_cookie_consent_site_ids as $drift_cookie_consent_site_id ) {
		switch_to_blog( $drift_cookie_consent_site_id );
		delete_option( 'drift_cookie_consent_settings' );
		restore_current_blog();
	}
} else {
	delete_option( 'drift_cookie_consent_settings' );
}
