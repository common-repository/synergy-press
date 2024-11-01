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
 * Updates site configuration.
 *
 * @param object $synergypress SynergyPress object.
 */
function update_site( $synergypress ) {
	if ( ! $synergypress->config ) {
		return;
	}
	$website = $synergypress->resources->Get( 'website' );
	$config  = $synergypress->resources->Get( 'config' );
	$api     = FS::Api()->Load( $config->profileid );
	$request = $synergypress->request;
	unset( $request['register'], $request['verify'] );
	$synergypress->resources->Update( 'website' )
		->Data( $synergypress->validate->sanitize_site_config( $request ) );
	if ( $website && isset( $config->siteid ) ) :

		$api->Get( 'website' )
		->Where(
			array(
				'siteid' => $config->siteid,
			)
		)
		->Update(
			array(
				'domain'    => isset( $synergypress->request['domain'] )
							? $synergypress->request['domain']
							: null,
				'name'      => isset( $synergypress->request['name'] )
							? $synergypress->request['name']
							: null,
				'proto'     => isset( $synergypress->request['proto'] )
							? $synergypress->request['proto']
							: null,
				'indexpage' => isset( $synergypress->request['indexpage'] )
							? $synergypress->request['indexpage']
							: null,
			)
		)
		->As( 'website' );
		$api->getMessages();
		$synergypress->resources->Update( 'website' )
			->Data( $synergypress->validate->sanitize_site_config( $api->_website() ) );
		else :

			$api->Create( 'website' )
				->Attributes(
					array(
						'domain'    => $synergypress->request['domain'],
						'name'      => $synergypress->request['name'],
						'proto'     => $synergypress->request['proto'],
						'indexpage' => $synergypress->request['indexpage'],
					)
				)
				->As( 'website' );
				$api->getMessages();
			$synergypress->resources->Update( 'config' )
				->Data(
					array(
						'siteid' => $synergypress->validate->sanitize_hash(
							$api->_website( 'siteid' )
						),
					)
				);

			$synergypress->resources->Update( 'website' )
				->Data( $synergypress->validate->sanitize_site_config( $api->_website() ) );
		endif;

		$website = $synergypress->resources->Get( 'website', true );

		if ( isset( $request['heartbeat'] ) ) :
			$website['heartbeat'] = $request['heartbeat'];
		endif;

		if ( isset( $request['offset'] ) ) :
			$website['offset'] = $request['offset'];
		endif;

		if ( isset( $synergypress->request['register'] ) && 'yes' === $synergypress->request['register'] ) :
			$api->Get( 'website' )
				->Where(
					array(
						'siteid' => $config->siteid,
					)
				)
				->verify()
				->As( 'website' );
				$api->getMessages();
		endif;

		$synergypress->resources->Update( 'website' )
			->Data( $synergypress->validate->sanitize_site_config( $api->_website() ) );
}

/**
 * Update website options
 * 
 * @param object $synergypress Synergy Press object.
 */
function update_site_options( $synergypress ) {
	$website = $synergypress->resources->Get( 'website', true );
	$request = $synergypress->request;
	if( isset( $request['heartbeat'] ) ) {
		$website['heartbeat'] = $request['heartbeat'];
	}

	if( isset( $request['offset'] ) ) {
		$website['offset'] = $request['offset'];
	}

	$synergypress->resources->Update( 'website' )
	->Data( $synergypress->validate->sanitize_site_config( $website ) );
}