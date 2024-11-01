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
 * Updates strategy.
 *
 * @param object $synergypress SynergyPress object.
 */
function update_strategy( $synergypress ) {
	if ( ! $synergypress->config ) {
		return;
	}

	if ( ! isset( $synergypress->request['actiontype'] ) ) {
		return;
	}

	$config   = $synergypress->resources->Get( 'config' );
	$strategy = $synergypress->resources->Get( 'strategy' );
	$website  = $synergypress->resources->Get( 'website' );
	$api      = FS::Api()->Load( $synergypress->validate->sanitize_hash( $config->profileid ) );

	switch ( $synergypress->request['actiontype'] ) {
		case 'default':
			if ( $strategy ) {
				$api->Get( 'strategy' )
				->Where(
					array(
						'modid' => $synergypress->validate->sanitize_hash( $strategy->modid ),
					)
				)
				->Update(
					array(
						'name' => $synergypress->validate->sanitize_text_field( $synergypress->request['name'] ),
					)
				)
				->As( 'strategy' );
				$synergypress->resources->Update( 'strategy' )
					->Data(
						$synergypress->validate->sanitize_strategy(
							$api->_strategy()
						)
					);
			} else {
				$api->Create( 'strategy' )
					->Attributes(
						array(
							'siteid' => $synergypress->validate->sanitize_hash( $config->siteid ),
							'name'   => 'Default',
						)
					)
				->As( 'strategy' );
				$strategy = $synergypress->validate->sanitize_strategy(
					$api->_strategy()
				);
				$synergypress->resources->Store( 'strategy' )
				->Data( $strategy );
				$api->Get('website')
					->Where(
						array(
							'siteid' => $config->siteid
						)
				)
				->Update(
					array(
						'activestrategy' => $strategy['modid']
					)
				)
				->As('updateSite');
			}
			break;

		case 'update_id':
			if ( ! isset( $synergypress->request['modid'] ) ) {
				return;
			}

			$api->Get( 'strategy' )
			->Where(
				array(
					'modid' => $synergypress->validate->sanitize_hash( $synergypress->request['modid'] ),
				)
			)
				->As( 'strategy' );

			$synergypress->resources->Store( 'strategy' )
				->Data(
					$synergypress->validate->sanitize_strategy(
						$api->_strategy()
					)
				);
			break;
	}
}
