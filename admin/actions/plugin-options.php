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
 * Updates plugin options.
 *
 * @param object $synergypress SynergyPress object.
 */
function update_options( $synergypress ) {
	$synergy_press = $synergypress->resources->Get( 'synergypress' );
	$request       = $synergypress->request;
	if ( ! $synergy_press ) {
		$synergypress->resources->Store( 'synergypress' )
			->Data(
				$synergypress->validate->sanitize_plugin_options( $request )
			);
	} else {
		$synergypress->resources->Update( 'synergypress' )
			->Data(
				$synergypress->validate->sanitize_plugin_options( $request )
			);
	}
}
