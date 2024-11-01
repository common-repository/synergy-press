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
 * Updates objectives.
 *
 * @param object $synergypress SynergyPress object.
 */
function update_objective( $synergypress ) {
	if ( ! $synergypress->config ) {
		return;
	}

	$strategy = $synergypress->resources->Get( 'strategy' );
	$config   = $synergypress->resources->Get( 'config' );
	foreach ( $synergypress->request as $objective => $data ) {

		switch ( $objective ) {
			case 'contactrequests':
				objective_contact_requests( $data, $strategy, $config, $synergypress );
				break;
			case 'requestcallback':
				objective_request_callback( $data, $strategy, $config, $synergypress );
				break;
			case 'newslettersubscription':
				objective_newsletter_subscription( $data, $strategy, $config, $synergypress );
				break;
		}
	}
}

/**
 * Updates contact request objective.
 *
 * @param array  $data Submitted updates regarding the contact request objective.
 * @param object $strategy The strategy object.
 * @param object $config The API configuration object.
 * @param object $synergypress The Synerg Press object.
 */
function objective_contact_requests( $data, $strategy, $config, $synergypress ) {
	$api       = FS::Api()->Load( $config->profileid );
	$objective = $synergypress->resources->Get( 'objectiveContactRequests' );
	if ( ! $objective
		&& isset( $data['install'] ) ) {
		$api->Create( 'objective' )
			->Attributes(
				array(
					'siteid'             => $config->siteid,
					'modid'              => $strategy->modid,
					'label'              => $data['label'],
					'notificationmethod' => $data['notificationmethod'],
					'limittomodule'      => $synergypress->resources->Get( 'contactForm' )->moduleid,
					'recipient'          => $data['recipient'],
					'endpoint'           => $data['endpoint'],
					'leadboard'          => 'yes',
				)
			)
			->As( 'objectiveContactRequests' );
			$api->getMessages();
		$objective_contact_requests = $api->_objectiveContactRequests();
		if ( ! $objective_contact_requests || ! isset( $objective_contact_requests['obsid'] ) ) :
			return;
		endif;
		$synergypress->resources->Update( 'objectiveContactRequests' )
			->Data(
				$synergypress->validate->sanitize_objective( $objective_contact_requests )
			);
	}

	$objective_contact_requests = $synergypress->resources->Get( 'objectiveContactRequests' );

	if ( isset( $objective_contact_requests->obsid, $data['install'] )
		&& 'yes' === $data['install'] ) {
		$api->Get( 'objective' )
				->Where(
					array(
						'obsid' => $objective_contact_requests->obsid,
					)
				)
				->Update(
					array(
						'label'              => $data['label'],
						'notificationmethod' => $data['notificationmethod'],
						'recipient'          => $data['recipient'],
						'leadboard'          => 'yes',
					)
				)
				->As( 'objectiveContactRequests' );
				$api->getMessages();
		$synergypress->resources->Update( 'objectiveContactRequests' )
			->Data(
				$synergypress->validate->sanitize_objective(
					$api->_objectiveContactRequests()
				)
			);
	}

	if ( $objective_contact_requests
		&& isset( $data['install'] )
		&& 'no' === $data['install'] ) {
		$api->Get( 'objective' )
			->Where(
				array(
					'obsid' => $objective_contact_requests->obsid,
				)
			)
			->Delete();
			$api->getMessages();
		$synergypress->resources->Delete( 'objectiveContactRequests' );
	}
}

/**
 * Updates request callback objective.
 *
 * @param array  $data Submitted updates regarding the contact request objective.
 * @param object $strategy The strategy object.
 * @param object $config The API configuration object.
 * @param object $synergypress The Synerg Press object.
 */
function objective_request_callback( $data, $strategy, $config, $synergypress ) {
	$api       = FS::Api()->Load( $config->profileid );
	$objective = $synergypress->resources->Get( 'objectiveRequestCallback' );

	if ( ! $objective
		&& isset( $data['install'] ) ) {
		$api->Create( 'objective' )
			->Attributes(
				array(
					'siteid'             => $config->siteid,
					'modid'              => $strategy->modid,
					'label'              => $data['label'],
					'notificationmethod' => $data['notificationmethod'],
					'limittomodule'      => $synergypress->resources->Get( 'requestCallback' )->moduleid,
					'recipient'          => $data['recipient'],
					'endpoint'           => $data['endpoint'],
					'leadboard'          => 'yes',
				)
			)
			->As( 'objectiveRequestCallback' );
			$api->getMessages();
			$objective_request_callback = $api->_objectiveRequestCallback();
		if ( ! $objective_request_callback || ! isset( $objective_request_callback['obsid'] ) ) :
			return;
		endif;
		$synergypress->resources->Update( 'objectiveRequestCallback' )
			->Data(
				$synergypress->validate->sanitize_objective( $objective_request_callback )
			);
	}

	$objective_request_callback = $synergypress->resources->Get( 'objectiveRequestCallback' );

	if ( isset( $objective_request_callback->obsid, $data['install'] )
		&& 'yes' === $data['install'] ) {
		$api->Get( 'objective' )
				->Where(
					array(
						'obsid' => $objective_request_callback->obsid,
					)
				)
				->Update(
					array(
						'label'              => $data['label'],
						'notificationmethod' => $data['notificationmethod'],
						'recipient'          => $data['recipient'],
						'leadboard'          => 'yes',
					)
				)
				->As( 'objectiveRequestCallback' );
				$api->getMessages();
		$synergypress->resources->Update( 'objectiveRequestCallback' )
			->Data(
				$synergypress->validate->sanitize_objective(
					$api->_objectiveRequestCallback()
				)
			);
	}

	if ( $objective_request_callback
		&& isset( $data['install'] )
		&& 'no' === $data['install'] ) {
		$api->Get( 'objective' )
			->Where(
				array(
					'obsid' => $objective_request_callback->obsid,
				)
			)
			->Delete();
			$api->getMessages();
		$synergypress->resources->Delete( 'objectiveRequestCallback' );
	}
}

/**
 * Updates newsletter subscription objective.
 *
 * @param array  $data Submitted updates regarding the contact request objective.
 * @param object $strategy The strategy object.
 * @param object $config The API configuration object.
 * @param object $synergypress The Synerg Press object.
 */
function objective_newsletter_subscription( $data, $strategy, $config, $synergypress ) {
	$api       = FS::Api()->Load( $config->profileid );
	$objective = $synergypress->resources->Get( 'objectiveNewsLetterSubscription' );

	if ( ! $objective
		&& isset( $data['install'] ) ) {
		$api->Create( 'objective' )
			->Attributes(
				array(
					'siteid'             => $config->siteid,
					'modid'              => $strategy->modid,
					'label'              => $data['label'],
					'notificationmethod' => $data['notificationmethod'],
					'limittomodule'      => $synergypress->resources->Get( 'newsLetterSubscription' )->moduleid,
					'recipient'          => $data['recipient'],
					'endpoint'           => $data['endpoint'],
					'leadboard'          => 'yes',
				)
			)
			->As( 'objectiveNewsLetterSubscription' );
			$api->getMessages();
			$objective_newsletter_subscription = $api->_objectiveNewsLetterSubscription();
		if ( ! $objective_newsletter_subscription || ! isset( $objective_newsletter_subscription['obsid'] ) ) :
			return;
		endif;
		$synergypress->resources->Update( 'objectiveNewsLetterSubscription' )
			->Data(
				$synergypress->validate->sanitize_objective( $objective_newsletter_subscription )
			);
	}

	$objective_newsletter_subscription = $synergypress->resources->Get( 'objectiveNewsLetterSubscription' );

	if ( isset( $objective_newsletter_subscription->obsid, $data['install'] )
		&& 'yes' === $data['install'] ) {
		$api->Get( 'objective' )
				->Where(
					array(
						'obsid' => $objective_newsletter_subscription->obsid,
					)
				)
				->Update(
					array(
						'label'              => $data['label'],
						'notificationmethod' => $data['notificationmethod'],
						'recipient'          => $data['recipient'],
						'leadboard'          => 'yes',
					)
				)->As( 'objectiveNewsLetterSubscription' );
				$api->getMessages();
		$synergypress->resources->Update( 'objectiveNewsLetterSubscription' )
			->Data(
				$synergypress->validate->sanitize_objective(
					$api->_objectiveNewsLetterSubscription()
				)
			);
	}

	if ( $objective_newsletter_subscription
		&& isset( $data['install'] )
		&& 'no' === $data['install'] ) {
		$api->Get( 'objective' )
			->Where(
				array(
					'obsid' => $objective_newsletter_subscription->obsid,
				)
			)->Delete();
			$api->getMessages();
		$synergypress->resources->Delete( 'objectiveNewsLetterSubscription' );
	}
}

/**
 * Sync objectives, only when using easy mode.
 *
 * @param object $synergypress Synergy Press object.
 */
function sync_objectives($synergypress)
{
    $strategy = $synergypress->resources->Get('strategy');
    if (! $strategy) {
        return;
    }

    $config                  = $synergypress->resources->Get('config');
    $newsletter_subscription = $synergypress->resources->Get('newsLetterSubscription');
    $request_callback        = $synergypress->resources->Get('requestCallback');
    $contact_requests        = $synergypress->resources->Get('contactForm');

    $load_modules   = array(
        'objectiveNewsLetterSubscription' => $newsletter_subscription
                                        && isset($newsletter_subscription->moduleid)
                                        ? $newsletter_subscription->moduleid
                                        : false,
        'objectiveRequestCallback'        => $request_callback
                                        && isset($request_callback->moduleid)
                                        ? $request_callback->moduleid
                                        : false,
        'objectiveContactRequests'        => $contact_requests
                                    && isset($contact_requests->moduleid)
                                    ? $contact_requests->moduleid
                                    : false,
    );
    $linked_modules = array_flip(array_filter($load_modules));

    $api = FS::Api()->Load($config->profileid);
    $api->Find('objectives')
        ->Where(
            array( 'modid' => $strategy->modid )
        )
        ->As('objectives');

    $api->getMessages();
    $objectives = $api->_objectives();
    if (! $objectives || empty($objectives)) :
        return;
    endif;

    if (! empty($linked_modules) ) :
        foreach ($objectives as $objective) :
            if ('no' != $objective['limittomodule']
                && array_key_exists($objective['limittomodule'], $linked_modules)) :
                $synergypress->resources->Store($linked_modules[ $objective['limittomodule'] ])
                    ->Data($synergypress->validate->sanitize_objective($objective));
    		endif;
    	endforeach;
    endif;
}