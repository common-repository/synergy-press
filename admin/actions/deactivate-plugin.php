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
 * Clear resources on plugin deactivation.
 *
 * @param object $synergypress SynergyPress object.
 */
function deactivate_plugin( $synergypress ) {
	if ( ! $synergypress->config ) {
		return;
	}
	$website = $synergypress->resources->Get( 'website' );
	$config  = $synergypress->resources->Get( 'config' );
	$api     = FS::Api()->Load( $config->profileid );
	if ( $website && isset( $config->siteid ) ) :
        $synergypress->resources->delete_all();
		$api->Get( 'website' )
		->Where(
			array(
				'siteid' => $config->siteid,
			)
		)
		->Update(
			array(
				'clearresources'  => array(
					'resources' => 'all',
				),
			)
		)
		->As( 'clearresources' );
		$synergypress->resources->delete_all();
    endif;	
}