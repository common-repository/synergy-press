<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

namespace SynergyPress;

/**
 * Import Form Synergy API.
 */
use \FormSynergy\Fs as FS;

/**
 * Updates contact form module.
 *
 * @param object $synergypress The synergypress object.
 */
function import_settings( $synergypress ) {
	if ( ! isset( $synergypress->request['settings'] ) ) {
		return;
	}

	$settings = $synergypress->request['settings'];

	/**
	 * Update profile id and api keys.
	 */
	$config_data = array(
		'siteid'    => $synergypress->validate->sanitize_hash( $settings['siteid'] ),
		'profileid' => $synergypress->validate->sanitize_hash( $settings['profileid'] ),
		'apikey'    => $synergypress->validate->sanitize_hash( $settings['apikey'] ),
		'secretkey' => $synergypress->validate->sanitize_hash( $settings['secretkey'] ),
	);

	$config = $synergypress->resources->Get( 'config' );
	if ( $config && isset( $config->profileid ) ) :
		$synergypress->resources->Update( 'config' )
			->Data(
				$synergypress->validate->sanitize_api_config( $config_data )
			);
	else :
		$synergypress->resources->Store( 'config' )
			->Data(
				$synergypress->validate->sanitize_api_config( $config_data )
			);
	endif;

	/**
	 * Update website information.
	 */
	$site_data = array(
		'siteid'   => $synergypress->validate->sanitize_hash( $settings['siteid'] ),
		'domain'   => $synergypress->validate->sanitize_domain( $settings['domain'] ),
		'proto'    => sanitize_text_field( $settings['proto'] ),
		'label'    => sanitize_text_field( $settings['name'] ),
		'verified' => sanitize_text_field( $settings['verified'] ),
	);
	$website   = $synergypress->resources->Get( 'website' );
	if ( $website && isset( $website->siteid ) ) :
		$synergypress->resources->Update( 'website' )
			->Data(
				$synergypress->validate->sanitize_site_config( $site_data )
			);

	else :
		$synergypress->resources->Store( 'website' )
			->Data(
				$synergypress->validate->sanitize_site_config( $site_data )
			);
	endif;

	/**
	 * Update plugin default options.
	 */
	$synerypress_data = array(
		'autoload' => array(
			'params'  => 'yes',
			'options' => 'yes',
		),
	);

	if ( $synerypress_data ) :
		$synergypress->resources->Update( 'synerypress' )
			->Data(
				$synergypress->validate->sanitize_plugin_options( $synerypress_data )
			);

	else :
		$synergypress->resources->Store( 'synerypress' )
			->Data(
				$synergypress->validate->sanitize_plugin_options( $synerypress_data )
			);
	endif;

	delete_transient( 'synergypress_load_dashboard' );
	wp_safe_redirect( admin_url( '/admin.php?page=synergy-press' ) );
}
