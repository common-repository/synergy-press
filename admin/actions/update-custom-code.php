<?php
/**
 * Created on Tue April 30 2019
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 */

namespace SynergyPress;

/**
 * This function will store custom javaScript code
 */

/**
 * Import Form Synergy API.
 */
use \FormSynergy\Fs as FS;

/**
 * Updates custom code.
 *
 * @param object $synergypress SynergyPress object.
 */
function update_custom_code( $synergypress ) {
	if ( isset( $synergypress->request['javascript'] ) ) {
		$custom_code = $synergypress->resources->Get( 'customCode' );
		if ( $custom_code ) {
			$synergypress->resources->Update( 'customCode' )->Data(
				array(
					'javascript' => $synergypress->request['javascript'],
				)
			);
		} else {
			$synergypress->resources->Store( 'customCode' )->Data(
				array(
					'javascript' => $synergypress->request['javascript'],
				)
			);
		}
	}
	wp_safe_redirect( esc_url( wp_nonce_url( '?page=synergypress-custom-code', 'form_synergy_update_options', '_wpnonce' ) ) );
	exit();
}
