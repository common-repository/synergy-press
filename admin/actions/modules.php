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
 * Update modules.
 *
 * @param object $synergypress The synergypress object.
 */
function update_module( $synergypress ) {
	if ( ! $synergypress->config ) {
		return;
	}
	$strategy = $synergypress->resources->Get( 'strategy' );
	$config   = $synergypress->resources->Get( 'config' );
	foreach ( $synergypress->request as $module => $data ) {
		$switch = $module;

		if ( isset( $data['initiator'] ) && 'advancedmode' === $data['initiator'] ) {
			$switch = 'advancedmode';
		}

		if ( ! isset( $data['autoload'] ) ) {
			$data['autoload'] = array(
				'params'  => 'yes',
				'options' => 'yes',
			);
		}

		switch ( $switch ) {

			case 'contactform':
				module_contact_form( $data, $strategy, $config, $synergypress );
				break;

			case 'requestcallback':
				module_request_callback( $data, $strategy, $config, $synergypress );
				break;

			case 'newslettersubscription':
				module_newsletter_subscription( $data, $strategy, $config, $synergypress );
				break;

			case 'advancedmode':
				$load_module = $synergypress->resources->Get( $module, true );
				if ( $load_module ) {
					$load_module['options']     = $data['options'];
					$load_module['params']      = $data['params'];
					$load_module['fstrigger']   = $data['fstrigger'];
					$load_module['fsplacement'] = $data['fsplacement'];
					$load_module['autoload']    = $data['autoload'];
					$synergypress->resources->Update( $module )->Data(
						$synergypress->validate->sanitize_modules( $load_module )
					);
				}
				wp_safe_redirect( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-' . sanitize_title( $load_module['name'] ), 'form_synergy_update_options', '_wpnonce' ) );
				exit;
				break;
		}
	}
}

/**
 * Updates the contact form module.
 *
 * @param array  $data Submitted updates regarding the contact request objective.
 * @param object $strategy The strategy object.
 * @param object $config The API configuration object.
 * @param object $synergypress The Synerg Press object.
 */
function module_contact_form( $data, $strategy, $config, $synergypress ) {
	$api    = FS::Api()->Load( $config->profileid );
	$module = $synergypress->resources->Get( 'contactForm' );

	if ( ! $module && isset( $data['install'] ) ) {
		$api->Create( 'module' )
			->Attributes(
				array(
					'siteid'   => $config->siteid,
					'modid'    => $strategy->modid,
					'name'     => 'Contact Form',
					'headings' => isset( $data['headings'] )
										? $data['headings']
										: array(),
					'form'     => array(
						array(
							'x'              => 1,
							'type'           => 'text',
							'system'         => 'fname',
							'label'          => 'First Name',
							'name'           => 'fname',
							'class'          => 'form-control',
							'validation'     => 'yes',
							'validationType' => 'fname',
						),
						array(
							'x'              => 2,
							'type'           => 'text',
							'system'         => 'lname',
							'label'          => 'Last Name',
							'name'           => 'lname',
							'class'          => 'form-control',
							'validation'     => 'yes',
							'validationType' => 'lname',
						),
						array(
							'x'              => 3,
							'type'           => 'email',
							'system'         => 'email',
							'label'          => 'Email Address',
							'name'           => 'email',
							'class'          => 'form-control',
							'validation'     => 'yes',
							'validationType' => 'email',
						),
						array(
							'x'              => 4,
							'type'           => 'tel',
							'system'         => 'mobile',
							'label'          => 'Phone Number',
							'name'           => 'mobile',
							'class'          => 'form-control',
							'validation'     => 'yes',
							'validationType' => 'mobile',
						),
						array(
							'x'              => 5,
							'type'           => 'textarea',
							'system'         => 'custom',
							'label'          => 'Message',
							'name'           => 'message',
							'class'          => 'form-control h-3',
							'validation'     => 'yes',
							'validationType' => 'paragraph',
						),
					),
				)
			)
			->As( 'contactForm' );

			$contact_form = $api->_contactForm();
			$api->getMessages();
		if ( ! $contact_form || ! isset( $contact_form['moduleid'] ) ) :
			return;
		endif;

			$contact_form['options']   = array(
				'el'  => '@' . esc_attr( $api->_contactForm( 'moduleid' ) ),
				'opt' => array(
					'display'   => 'fixed',
					'placement' => 'centered',
					'size'      => 'lg',
					'theme'     => 'white',
				),
			);
			$contact_form['params']    = array(
				'etag'   => 'onclick:contact-form',
				'params' => array(
					'trigger' => array(
						'moduleid' => esc_attr( $api->_contactForm( 'moduleid' ) ),
					),
				),
			);
			$contact_form['fstrigger'] = '.fs-trigger-' . esc_attr( $api->_contactForm( 'moduleid' ) );
			$synergypress->resources->Update( 'contactForm' )
				->Data( $synergypress->validate->sanitize_modules( $contact_form ) );
	}

	$contact_form = $synergypress->resources->Get( 'contactForm' );

	if ( isset( $contact_form->moduleid, $data['install'] )
		&& 'yes' === $data['install'] ) {
		$api->Get( 'module' )
			->Where(
				array(
					'modid'    => $strategy->modid,
					'moduleid' => $contact_form->moduleid,
				)
			)
			->Update(
				array(
					'name'     => 'Contact Form',
					'headings' => isset( $data['headings'] )
										? $data['headings']
										: array(),
				)
			)
			->As( 'contactForm' );

			$contact_form              = $api->_contactForm();
			$contact_form['options']   = array(
				'el'  => '@' . esc_attr( $api->_contactForm( 'moduleid' ) ),
				'opt' => array(
					'display'   => 'fixed',
					'placement' => 'centered',
					'size'      => 'lg',
					'theme'     => 'white',
				),
			);
			$contact_form['params']    = array(
				'etag'   => 'onclick:contact-form',
				'params' => array(
					'trigger' => array(
						'moduleid' => esc_attr( $api->_contactForm( 'moduleid' ) ),
					),
				),
			);
			$contact_form['fstrigger'] = '.fs-trigger-' . esc_attr( $api->_contactForm( 'moduleid' ) );
			$synergypress->resources->Update( 'contactForm' )
				->Data( $synergypress->validate->sanitize_modules( $contact_form ) );
	}

	if ( $contact_form
		&& isset( $data['install'] )
		&& 'no' === $data['install'] ) {
		$api->Get( 'module' )
			->Where(
				array(
					'modid'    => $strategy->modid,
					'moduleid' => $contact_form->moduleid,
				)
			)
			->Delete()
			->As( 'removed' );
		$synergypress->resources->Delete( 'contactForm' );
	}
}

/**
 * Updates the callback request module.
 *
 * @param array  $data Submitted updates regarding the contact request objective.
 * @param object $strategy The strategy object.
 * @param object $config The API configuration object.
 * @param object $synergypress The Synerg Press object.
 */
function module_request_callback( $data, $strategy, $config, $synergypress ) {
	$api    = FS::Api()->Load( $config->profileid );
	$module = $synergypress->resources->Get( 'requestCallback' );

	if ( ! $module && isset( $data['install'] ) ) {
		$api->Create( 'module' )
			->Attributes(
				array(
					'siteid'   => $config->siteid,
					'modid'    => $strategy->modid,
					'name'     => 'Request Callback',
					'headings' => isset( $data['headings'] )
										? $data['headings']
										: array(),
					'form'     => array(
						array(
							'x'          => 1,
							'type'       => 'tel',
							'system'     => 'mobile',
							'label'      => 'Phone Number',
							'name'       => 'mobile',
							'class'      => 'form-control',
							'validation' => 'yes',
						),
					),
				)
			)
			->As( 'requestCallback' );
			$api->getMessages();
			$request_callback = $api->_requestCallback();

		if ( ! $request_callback || ! isset( $request_callback['moduleid'] ) ) :
			return;
			endif;

			$request_callback['options']   = array(
				'el'  => '@' . esc_attr( $api->_requestCallback( 'moduleid' ) ),
				'opt' => array(
					'display'   => 'fixed',
					'placement' => 'centered',
					'size'      => 'lg',
					'theme'     => 'white',
				),
			);
			$request_callback['params']    = array(
				'etag'   => 'onclick:request-callback',
				'params' => array(
					'trigger' => array(
						'moduleid' => esc_attr( $api->_requestCallback( 'moduleid' ) ),
					),
				),
			);
			$request_callback['fstrigger'] = '.fs-trigger-' . esc_attr( $api->_requestCallback( 'moduleid' ) );
			$synergypress->resources->Update( 'requestCallback' )
				->Data( $synergypress->validate->sanitize_modules( $request_callback ) );
	}

	$request_callback = $synergypress->resources->Get( 'requestCallback' );

	if ( isset( $request_callback->moduleid, $data['install'] )
		&& 'yes' === $data['install'] ) {
		$api->Get( 'module' )
			->Where(
				array(
					'modid'    => $strategy->modid,
					'moduleid' => $request_callback->moduleid,
				)
			)
			->Update(
				array(
					'headings' => isset( $data['headings'] )
										? $data['headings']
										: array(),
				)
			)
			->As( 'requestCallback' );

			$request_callback              = $api->_requestCallback();
			$request_callback['options']   = array(
				'el'  => '@' . esc_attr( $api->_requestCallback( 'moduleid' ) ),
				'opt' => array(
					'display'   => 'fixed',
					'placement' => 'centered',
					'size'      => 'lg',
					'theme'     => 'white',
				),
			);
			$request_callback['params']    = array(
				'etag'   => 'onclick:request-callback',
				'params' => array(
					'trigger' => array(
						'moduleid' => esc_attr( $api->_requestCallback( 'moduleid' ) ),
					),
				),
			);
			$request_callback['fstrigger'] = '.fs-trigger-' . esc_attr( $api->_requestCallback( 'moduleid' ) );
			$synergypress->resources->Update( 'requestCallback' )
				->Data( $synergypress->validate->sanitize_modules( $request_callback ) );
	}

	if ( $request_callback
		&& isset( $data['install'] )
		&& 'no' === $data['install'] ) {
		$api->Get( 'module' )
			->Where(
				array(
					'modid'    => $strategy->modid,
					'moduleid' => $request_callback->moduleid,
				)
			)
			->Delete();
		$synergypress->resources->Delete( 'requestCallback' );
	}
}

/**
 * Updates the newsletter subscription module.
 *
 * @param array  $data Submitted updates regarding the contact request objective.
 * @param object $strategy The strategy object.
 * @param object $config The API configuration object.
 * @param object $synergypress The Synerg Press object.
 */
function module_newsletter_subscription( $data, $strategy, $config, $synergypress ) {
	$api    = FS::Api()->Load( $config->profileid );
	$module = $synergypress->resources->Get( 'newsLetterSubscription' );

	if ( ! $module && isset( $data['install'] ) ) {
		$api->Create( 'module' )
			->Attributes(
				array(
					'siteid'   => $config->siteid,
					'modid'    => $strategy->modid,
					'name'     => 'Newsletter Subscription',
					'headings' => isset( $data['headings'] )
										? $data['headings']
										: array(),
					'form'     => array(
						array(
							'x'      => 1,
							'type'   => 'email',
							'system' => 'email',
							'label'  => 'Email Address',
							'name'   => 'email',
							'class'  => 'form-control',
						),
					),
				)
			)
			->As( 'newsLetterSubscription' );
			$api->getMessages();
		$newsetter_subscription = $api->_newsLetterSubscription();

		if ( ! $newsetter_subscription || ! isset( $newsetter_subscription['moduleid'] ) ) :
			return;
		endif;

		$newsetter_subscription['options']   = array(
			'el'  => '@' . esc_attr( $api->_newsLetterSubscription( 'moduleid' ) ),
			'opt' => array(
				'display'   => 'fixed',
				'placement' => 'centered',
				'size'      => 'lg',
				'theme'     => 'white',
			),
		);
		$newsetter_subscription['params']    = array(
			'etag'   => 'onclick:news-letter-subscription',
			'params' => array(
				'trigger' => array(
					'moduleid' => esc_attr( $api->_newsLetterSubscription( 'moduleid' ) ),
				),
			),
		);
		$newsetter_subscription['fstrigger'] = '.fs-trigger-' . esc_attr( $api->_newsLetterSubscription( 'moduleid' ) );
		$synergypress->resources->Update( 'newsLetterSubscription' )
			->Data( $synergypress->validate->sanitize_modules( $newsetter_subscription ) );
	}

	$newsletter_subscription = $synergypress->resources->Get( 'newsLetterSubscription' );

	if ( isset( $newsletter_subscription->moduleid, $data['install'] )
		&& 'yes' === $data['install'] ) {
		$api->Get( 'module' )
			->Where(
				array(
					'modid'    => $strategy->modid,
					'moduleid' => $newsletter_subscription->moduleid,
				)
			)
			->Update(
				array(
					'headings' => isset( $data['headings'] )
										? $data['headings']
										: array(),
				)
			)
			->As( 'newsLetterSubscription' );
			$newsetter_subscription              = $api->_newsLetterSubscription();
			$newsetter_subscription['options']   = array(
				'el'  => '@' . esc_attr( $api->_newsLetterSubscription( 'moduleid' ) ),
				'opt' => array(
					'display'   => 'fixed',
					'placement' => 'centered',
					'size'      => 'lg',
					'theme'     => 'white',
				),
			);
			$newsetter_subscription['params']    = array(
				'etag'   => 'onclick:news-letter-subscription',
				'params' => array(
					'trigger' => array(
						'moduleid' => esc_attr( $api->_newsLetterSubscription( 'moduleid' ) ),
					),
				),
			);
			$newsetter_subscription['fstrigger'] = '.fs-trigger-' . esc_attr( $api->_newsLetterSubscription( 'moduleid' ) );
			$synergypress->resources->Update( 'newsLetterSubscription' )
			->Data( $synergypress->validate->sanitize_modules( $newsetter_subscription ) );
	}

	if ( $newsletter_subscription
		&& isset( $data['install'] )
		&& 'no' === $data['install'] ) {
		$api->Get( 'module' )
			->Where(
				array(
					'modid'    => $strategy->modid,
					'moduleid' => $newsletter_subscription->moduleid,
				)
			)
			->Delete();
		$synergypress->resources->Delete( 'newsLetterSubscription' );
	}
}
