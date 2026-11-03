<?php
/**
 * Settings: defaults, stored option and sanitising.
 *
 * Everything lives in one option, DRIFT_COOKIE_CONSENT_OPTION. It's read on
 * every front-end page, so it stays autoloaded.
 *
 * @package Drift_Cookie_Consent
 */

defined( 'ABSPATH' ) || exit;

/**
 * Supported consent managers, keyed by the value stored in the option.
 *
 * @return array<string, string>
 */
function drift_cookie_consent_get_consent_managers() {
	return array(
		'cookiescript' => __( 'CookieScript', 'drift-cookie-consent' ),
		'cookiebot'    => __( 'Cookiebot', 'drift-cookie-consent' ),
	);
}

/**
 * Default settings.
 *
 * @return array<string, string>
 */
function drift_cookie_consent_get_default_options() {
	return array(
		'consent_manager'    => 'cookiescript',
		'cookie_category'    => 'marketing',
		'default_bg_image'   => '',
		'consent_text'       => __( 'Please accept marketing cookies to view this video.', 'drift-cookie-consent' ),
		'consent_link_url'   => '',
		'consent_link_label' => __( 'Update cookie preferences', 'drift-cookie-consent' ),
	);
}

/**
 * Saved settings merged over the defaults.
 *
 * @return array<string, string>
 */
function drift_cookie_consent_get_options() {
	$options = get_option( DRIFT_COOKIE_CONSENT_OPTION, array() );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	return wp_parse_args( $options, drift_cookie_consent_get_default_options() );
}

/**
 * Sanitise settings before they're saved.
 *
 * @param array<string, mixed> $input Raw settings input.
 * @return array<string, string>
 */
function drift_cookie_consent_sanitize_options( $input ) {
	$defaults = drift_cookie_consent_get_default_options();

	if ( ! is_array( $input ) ) {
		return $defaults;
	}

	$consent_manager = isset( $input['consent_manager'] ) ? sanitize_key( $input['consent_manager'] ) : $defaults['consent_manager'];
	if ( ! array_key_exists( $consent_manager, drift_cookie_consent_get_consent_managers() ) ) {
		$consent_manager = $defaults['consent_manager'];
	}

	$cookie_category = isset( $input['cookie_category'] ) ? sanitize_key( $input['cookie_category'] ) : $defaults['cookie_category'];
	if ( '' === $cookie_category ) {
		$cookie_category = $defaults['cookie_category'];
	}

	$default_bg_image = isset( $input['default_bg_image'] ) ? esc_url_raw( wp_unslash( $input['default_bg_image'] ) ) : '';
	$consent_text     = isset( $input['consent_text'] ) ? sanitize_textarea_field( wp_unslash( $input['consent_text'] ) ) : '';
	$consent_link_url = isset( $input['consent_link_url'] ) ? esc_url_raw( wp_unslash( $input['consent_link_url'] ) ) : '';
	$consent_label    = isset( $input['consent_link_label'] ) ? sanitize_text_field( wp_unslash( $input['consent_link_label'] ) ) : '';

	if ( '' === $consent_text ) {
		$consent_text = $defaults['consent_text'];
	}

	if ( '' === $consent_label ) {
		$consent_label = $defaults['consent_link_label'];
	}

	return array(
		'consent_manager'    => $consent_manager,
		'cookie_category'    => $cookie_category,
		'default_bg_image'   => $default_bg_image,
		'consent_text'       => $consent_text,
		'consent_link_url'   => $consent_link_url,
		'consent_link_label' => $consent_label,
	);
}

/**
 * Register the option and the settings fields.
 *
 * @return void
 */
function drift_cookie_consent_register_settings() {
	register_setting(
		'drift_cookie_consent',
		DRIFT_COOKIE_CONSENT_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'drift_cookie_consent_sanitize_options',
			'default'           => drift_cookie_consent_get_default_options(),
		)
	);

	add_settings_section(
		'drift_cookie_consent_main',
		__( 'Display settings', 'drift-cookie-consent' ),
		'__return_false',
		DRIFT_COOKIE_CONSENT_SLUG
	);

	add_settings_field(
		'drift_cookie_consent_consent_manager',
		__( 'Consent manager', 'drift-cookie-consent' ),
		'drift_cookie_consent_render_consent_manager_field',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_main'
	);

	add_settings_field(
		'drift_cookie_consent_cookie_category',
		__( 'Cookie category key', 'drift-cookie-consent' ),
		'drift_cookie_consent_render_cookie_category_field',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_main',
		array( 'label_for' => 'drift-cookie-consent-cookie-category' )
	);

	add_settings_field(
		'drift_cookie_consent_default_bg_image',
		__( 'Default video background image URL', 'drift-cookie-consent' ),
		'drift_cookie_consent_render_default_bg_image_field',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_main',
		array( 'label_for' => 'drift-cookie-consent-default-bg-image' )
	);

	add_settings_field(
		'drift_cookie_consent_consent_text',
		__( 'Consent text', 'drift-cookie-consent' ),
		'drift_cookie_consent_render_consent_text_field',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_main',
		array( 'label_for' => 'drift-cookie-consent-consent-text' )
	);

	add_settings_field(
		'drift_cookie_consent_consent_link_url',
		__( 'Consent link URL (optional)', 'drift-cookie-consent' ),
		'drift_cookie_consent_render_consent_link_url_field',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_main',
		array( 'label_for' => 'drift-cookie-consent-consent-link-url' )
	);

	add_settings_field(
		'drift_cookie_consent_consent_link_label',
		__( 'Consent link label', 'drift-cookie-consent' ),
		'drift_cookie_consent_render_consent_link_label_field',
		DRIFT_COOKIE_CONSENT_SLUG,
		'drift_cookie_consent_main',
		array( 'label_for' => 'drift-cookie-consent-consent-link-label' )
	);
}
add_action( 'admin_init', 'drift_cookie_consent_register_settings' );

/**
 * Consent manager radios.
 *
 * @return void
 */
function drift_cookie_consent_render_consent_manager_field() {
	$options  = drift_cookie_consent_get_options();
	$managers = drift_cookie_consent_get_consent_managers();
	?>
	<fieldset>
		<legend class="screen-reader-text"><?php esc_html_e( 'Consent manager', 'drift-cookie-consent' ); ?></legend>
		<?php foreach ( $managers as $key => $label ) : ?>
			<label class="drift-cookie-consent__radio">
				<input type="radio" name="<?php echo esc_attr( DRIFT_COOKIE_CONSENT_OPTION ); ?>[consent_manager]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $options['consent_manager'], $key ); ?> />
				<?php echo esc_html( $label ); ?>
			</label>
		<?php endforeach; ?>
	</fieldset>
	<p class="description"><?php esc_html_e( 'The consent manager running on this site. It decides which blocking attributes are written to video iframes and which preferences popup the consent button opens.', 'drift-cookie-consent' ); ?></p>
	<?php
}

/**
 * Cookie category field.
 *
 * @return void
 */
function drift_cookie_consent_render_cookie_category_field() {
	$options = drift_cookie_consent_get_options();
	?>
	<input type="text" id="drift-cookie-consent-cookie-category" name="<?php echo esc_attr( DRIFT_COOKIE_CONSENT_OPTION ); ?>[cookie_category]" value="<?php echo esc_attr( $options['cookie_category'] ); ?>" class="regular-text" />
	<p class="description"><?php esc_html_e( 'The consent category videos are blocked under (default: marketing). On Cookiebot sites this must be marketing, statistics or preferences.', 'drift-cookie-consent' ); ?></p>
	<?php
}

/**
 * Default background image field.
 *
 * @return void
 */
function drift_cookie_consent_render_default_bg_image_field() {
	$options = drift_cookie_consent_get_options();
	?>
	<input type="url" id="drift-cookie-consent-default-bg-image" name="<?php echo esc_attr( DRIFT_COOKIE_CONSENT_OPTION ); ?>[default_bg_image]" value="<?php echo esc_url( $options['default_bg_image'] ); ?>" class="regular-text" placeholder="https://example.com/video-placeholder.jpg" />
	<p class="description"><?php esc_html_e( 'If set, this image replaces the YouTube and Vimeo thumbnails on every blocked video, and no thumbnail is requested from YouTube or Vimeo before consent.', 'drift-cookie-consent' ); ?></p>
	<?php
}

/**
 * Consent text field.
 *
 * @return void
 */
function drift_cookie_consent_render_consent_text_field() {
	$options  = drift_cookie_consent_get_options();
	$defaults = drift_cookie_consent_get_default_options();
	?>
	<textarea id="drift-cookie-consent-consent-text" name="<?php echo esc_attr( DRIFT_COOKIE_CONSENT_OPTION ); ?>[consent_text]" rows="4" class="large-text"><?php echo esc_textarea( $options['consent_text'] ); ?></textarea>
	<p class="description">
		<?php
		/* translators: %s: default consent text */
		echo esc_html( sprintf( __( 'Shown over blocked videos. Left empty, it falls back to: %s', 'drift-cookie-consent' ), $defaults['consent_text'] ) );
		?>
	</p>
	<?php
}

/**
 * Consent link URL field.
 *
 * @return void
 */
function drift_cookie_consent_render_consent_link_url_field() {
	$options = drift_cookie_consent_get_options();
	?>
	<input type="url" id="drift-cookie-consent-consent-link-url" name="<?php echo esc_attr( DRIFT_COOKIE_CONSENT_OPTION ); ?>[consent_link_url]" value="<?php echo esc_url( $options['consent_link_url'] ); ?>" class="regular-text" placeholder="https://example.com/privacy-policy" />
	<p class="description"><?php esc_html_e( 'Optional. Leave empty and the button opens the consent manager\'s preferences popup instead.', 'drift-cookie-consent' ); ?></p>
	<?php
}

/**
 * Consent link label field.
 *
 * @return void
 */
function drift_cookie_consent_render_consent_link_label_field() {
	$options = drift_cookie_consent_get_options();
	?>
	<input type="text" id="drift-cookie-consent-consent-link-label" name="<?php echo esc_attr( DRIFT_COOKIE_CONSENT_OPTION ); ?>[consent_link_label]" value="<?php echo esc_attr( $options['consent_link_label'] ); ?>" class="regular-text" />
	<?php
}
