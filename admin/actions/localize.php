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
 * Localize: If connection difficulties with the Form Synergy service are encountered,
 * this plugin can switch gears and function within WordPress resources by localizing
 * interaction modules, minimizing the loss of inquiries.
 *
 * This function will request, download and localize all modules within a given strategy.
 *
 * @param object $synergypress The synergypress object.
 */
function localize( $synergypress ) {
	if ( ! $synergypress->config ) :
		return;
	endif;

	$config       = $synergypress->resources->Get( 'config' );
	$strategy     = $synergypress->resources->Get( 'strategy' );
	$localization = $synergypress->resources->Get( 'localization' );
	$data         = array();

	if ( isset( $synergypress->request['enable'] )
		&& 'yes' === $synergypress->request['enable'] ) :

		// Test wp_mail first.
		$synergypress_wp_mail_has_no_errors = false;
		$email                              = wp_strip_all_tags( get_option( 'admin_email' ) );
		if ( ! is_email( $email ) ) :
			return;
		endif;

		$synergypress_wp_mail_has_no_errors = wp_mail(
			$email,
			'Testing Email Delivery',
			'Email delivery using wp_mail is successful.',
			'From: ' . $email
		);

		add_action(
			'wp_mail_failed',
			function ( $wp_error ) {
				set_transient( 'synergypress_wp_mail_error', $wp_error->errors['wp_mail_failed'][0] );
			},
			10,
			1
		);

		if ( $synergypress_wp_mail_has_no_errors && get_transient( 'synergypress_wp_mail_error' ) ) :
			delete_transient( 'synergypress_wp_mail_error' );
			endif;

			$api = FS::Api()->Load( $synergypress->validate->sanitize_hash( $config->profileid ) );
			$api->Download( 'strategy' )
			->Where(
				array(
					'modid' => $synergypress->validate->sanitize_hash( $strategy->modid ),
				)
			)
			->As( 'localization' );
			$api->getMessages();

			$localized_data    = $api->_localization();
			$localized_modules = array();
			$data['enable']    = $synergypress->validate->sanitize_text_field( $synergypress->request['enable'] );

		if ( isset( $synergypress->request['debug'] ) ) :
			$data['debug'] = $synergypress->validate->sanitize_text_field( $synergypress->request['debug'] );
			endif;

		if ( $localized_data ) :

			foreach ( $localized_data as $m => $module ) :

				$localized_modules[] = array(
					'name'     => $synergypress->validate->sanitize_text_field( $module['name'] ),
					'moduleid' => $synergypress->validate->sanitize_hash( $module['moduleid'] ),
				);

				$synergypress->resources->Update( $module['moduleid'] )
					->Data( $synergypress->validate->sanitize_import_modules( $module ) );

			endforeach;

			endif;

			$data['modules'] = $localized_modules;
			$synergypress->resources->Update( 'localization' )
			->Data( $synergypress->validate->sanitize_localization( $data ) );

		elseif ( isset( $synergypress->request['enable'] )
		&& 'no' === $synergypress->request['enable']
		&& $localization ) :

			foreach ( $synergypress->resources->Get( 'localization' )->modules as $module ) :
				$synergypress->resources->Delete( $synergypress->validate->sanitize_hash( $module->moduleid ) );
				endforeach;
			$synergypress->resources->Delete( 'localization' );
	endif;
}

/**
 * Enable or disable debug on localization.
 *
 * @param object $synergypress The Synergy Press object.
 */
function debug_localization( $synergypress ) {
	$localization = $synergypress->resources->Get( 'localization', true );
	$request = $synergypress->request;
	if( $localization && isset( $request['debug'] ) ) {
		$localization['debug'] = $request['debug'];
		$synergypress->resources->Update( 'localization' )
			->Data( $synergypress->validate->sanitize_localize( $localization ) );
	}
}

