<?php
/**
 * Created on Tue April 30 2019
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 */

// Make sure we don't expose any info if called directly.
if ( ! defined( 'SYNERGY_PRESS' ) ) :
	return;
endif;

/**
 * Enable session storage, required by the API for automated authentication.
 */
\FormSynergy\Session::enable();

/**
 * Import the Form Synergy API
 */
use \FormSynergy\Fs as FS;
/**
 * Add verification to wp_head
 */

add_action(
	'wp_head',
	function() {
		global $synergypress;
		$config = $synergypress->resources->Get( 'config' );

		if ( isset( $config->siteid ) ) :
			echo '<meta name="fs:siteid" content="' . esc_html( $config->siteid ) . '" />';
	endif;
	}
);

/**
 *  Prepare and localize script.
 */
function formsynergy_register_scripts() {
	global $synergypress;
	/**
	 * Enqueue scripts
	 */
	wp_enqueue_script(
		'formsynergy',
		plugin_dir_url( __FILE__ ) . 'js/formsynergy-2.1.3.js',
		array(),
		'2.1.3',
		true
	);

	wp_enqueue_script(
		'synergypress',
		plugin_dir_url( __FILE__ ) . 'js/synergypress.js',
		array( 'formsynergy' ),
		'1.6.0',
		true
	);

	/**
	 * Enqueue styles
	 */
	wp_enqueue_style(
		'synergy-press-styles',
		plugin_dir_url( __FILE__ ) . 'css/interactions.css',
		array(),
		'1.0',
		'all'
	);

	wp_enqueue_style(
		'synergy-press-light-theme',
		plugin_dir_url( __FILE__ ) . 'css/themes/light.css',
		array(),
		'1.0',
		'all'
	);
	wp_enqueue_style(
		'synergy-press-dark-theme',
		plugin_dir_url( __FILE__ ) . 'css/themes/dark.css',
		array(),
		'1.0',
		'all'
	);

	/**
	 * Localize script.
	 */
	$localize_script = array(
		'localMode' => array(
			'endPoint' => home_url() . '/wp-json/synergy-press/',
			'response' => array(
				'success' => 'Thank you',
				'dismiss' => 'Thank you for the visit.',
			),
		),
	);

	/**
	 * Get custom position offsets.
	 */
	$website       = $synergypress->resources->Get( 'website' );
	$localization  = $synergypress->resources->Get( 'localization' );
	$synergy_press = $synergypress->resources->Get( 'synergypress' );

	$localize_script['offsetPositions'] = array();

	if ( $website && isset( $website->offset ) ) :
		$localize_script['offsetPositions'] = $synergypress->validate->sanitize_offset_positions( $website->offset );
	endif;

	/**
	 * Import modules.
	 */
	$import_module     = array();
	$custom_placements = array();
	$advanced_mode     = $synergypress->resources->Get( 'advancedMode' );
	$modules           = ! $advanced_mode
						|| 'yes' !== $advanced_mode->status
						? array(
							'contactForm',
							'requestCallback',
							'newsLetterSubscription',
						)
						: $synergypress->resources->Get( 'modules' );
	/**
	 * Check if autoloading display options is dissabled for all modules.
	 */
	$autoload_options = true;

	if ( isset( $synergy_press->autoload->options )
		&& 'yes' !== $synergy_press->autoload->options ) :
		$autoload_options = false;
	endif;

	foreach ( $modules as $i => $module ) :
		$item = $synergypress->resources->Get(
			$synergypress->plugin->camel_case(
				is_object( $module ) ? $synergypress->validate->sanitize_text_field( $module->name ) : $synergypress->validate->sanitize_text_field( $module )
			),
			true
		);

		if ( $item ) :
			/**
			 * Check if autoloading display options is dissabled for this module.
			 */
			$autoload_option = ! isset( $item->autoload->options )
								|| 'yes' === $item->autoload->options
								? true
								: false;
			if ( $autoload_options && $autoload_option ) :
				$custom_placements[ $i ] = $synergypress->validate->sanitize_custom_placements( $synergypress->plugin->normalize_module( $item ) );
			endif;
			$import_module[ $i ] = $synergypress->validate->sanitize_import_modules( $synergypress->plugin->normalize_module( $item ) );
		endif;
	endforeach;

	if ( ! isset( $website->heartbeat->enable ) || 'yes' === $website->heartbeat->enable ) :
		$localize_script['heartbeat'] = isset( $website->heartbeat->frequency )
										&& intval( $website->heartbeat->frequency ) >= 7500
										? intval( $website->heartbeat->frequency )
										: 7500;
	endif;

	$localize_script['importModules']    = array_values( $import_module );
	$localize_script['customPlacements'] = array_values( $custom_placements );

	if ( $localization
		&& isset( $localization->debug )
		&& 'yes' === $localization->debug ) :
		$localize_script['debug'] = true;
	endif;

	/**
	 * Session token
	 */
	if ( ! \FormSynergy\Session::Get( 'formsynergy_api' ) ) :
		$token                              = $synergypress->plugin->token();
		$localize_script['formsynergy_api'] = $token;
		\FormSynergy\Session::Set( 'formsynergy_api', $token );
	else :
		$localize_script['formsynergy_api'] = \FormSynergy\Session::Get( 'formsynergy_api' );
	endif;

	/**
	 * Localize.
	 */
	wp_localize_script( 'synergypress', 'formSynergy', $localize_script );
}
add_action( 'wp_footer', 'formsynergy_register_scripts' );

/**
 * Print custom code in footer scripts.
 */
function formsynergy_print_custom_code() {
	global $synergypress;
	$java_script_code = '';
	$custom_code      = $synergypress->resources->Get( 'customCode' );

	if ( $custom_code && isset( $custom_code->javascript ) ) :
		$java_script_code = html_entity_decode( stripslashes( $custom_code->javascript ) );
	endif;
	/**
	 * Enable interactions and start engaging.
	 */
	$java_script_code .= 'FS.engage();';
		echo '<script type="text/javascript" id="fs-custom-code">' . $java_script_code . '</script>';
}
add_action( 'wp_print_footer_scripts', 'formsynergy_print_custom_code' );

/**
 * Handle custom endpoint requests.
 */
function formsynergy_request() {
	global $synergypress;
	$request = $synergypress->validate->sanitize_request(
		json_decode( file_get_contents( 'php://input' ) )
	);
	switch ( $request['api'] ) :
		case 'event':
			$strategy = $synergypress->resources->Get( 'strategy' );
			$module   = $synergypress->resources->Get( $synergypress->validate->sanitize_hash( $request['set']['trigger']['moduleid'] ) );
			if ( $module ) :
				return array(
					'dataType'  => 'DomObject',
					'el'        => '@' . $synergypress->validate->sanitize_hash( $request['set']['trigger']['moduleid'] ),
					'etag'      => ':' . str_replace( ' ', '_', $synergypress->validate->sanitize_text_field( $module->name ) )
									. '@' . str_replace( ' ', '_', $synergypress->validate->sanitize_text_field( $strategy->name ) ),
					'DomObject' => $synergypress->resources->Get( $synergypress->validate->sanitize_hash( $request['set']['trigger']['moduleid'] ) )->DomObject,
					'id'        => $synergypress->validate->sanitize_hash( $request['set']['trigger']['moduleid'] ),
					'opt'       => $synergypress->validate->sanitize_opt( $request['set']['opt'] ),
					'response'  => 'success',
					'trigger'   => $request['set']['trigger'],
					'target'    => $request['set']['target'],
					'rm'        => false,
					'display'   => false,
				);

			else :
				return array(
					'response' => 'error',
					'message'  => 'The requested module is not localized.',
				);
			endif;
			break;

		case 'interaction':
			$module    = $synergypress->resources->Get( $synergypress->validate->sanitize_hash( $request['set']['trigger']['moduleid'] ) );
			$objective = $synergypress->resources->Get( 'objective' . ucfirst( str_replace( ' ', '', $synergypress->validate->sanitize_text_field( $module->name ) ) ) );
			/**
			 * Prepare email message.
			 */

			$recipient = sanitize_email( wp_strip_all_tags( get_option( 'admin_email' ) ) );
			$is_email  = is_email( $recipient );
			if ( ! $is_email ) :
				return;
			endif;
			$headers  = 'From: ' . $recipient . "\r\n";
			$message  = "\r\n";
			$message .= 'This email was generated by ' . sanitize_text_field( get_bloginfo( 'name' ) ) . "\r\n\r\n";
			foreach ( $request['set']['form'] as $message_key => $message_value ) :
				$key      = sanitize_key( $message_key );
				$value    = sanitize_text_field( $message_value );
				$message .= "{$key}: {$value}\r\n";
			endforeach;
			$message .= "\r\n\r\n";
			$message .= "DISCLAIMER: Interaction API Email -- The delivery of this message was initiated and intended for '{$recipient}'.  \r\n\r\n";
			$message .= "This email and any attachments are intended for the use of the intended recipients only. If you are not the intended recipient of this email, please notify the recipient immediately, and delete all copies without reading, printing, or saving in any manner. --- Thank You.  \r\n";
			wp_mail( $recipient, sanitize_text_field( $module->name ), $message, $headers );

			return array(
				'objective' => $objective,
				'response'  => 'success',
				'message'   => 'onsubmit' === $request['set']['action_type'] ? 'Dismissed' : 'Notification sent',
				'dataType'  => 'fs-message',
				'fsMessage' => 'default',
				'rm'        => $synergypress->validate->sanitize_hash( $module->moduleid ),
				'close'     => true,
			);
			break;
	endswitch;
}

/**
 * Register custom route.
 * This route is required if localMode is enabled.
 */
add_action(
	'rest_api_init',
	function ( $server ) {
		$server->register_route(
			'synergy-press',
			'/synergy-press',
			array(
				'methods'  => 'POST',
				'callback' => function() {
					return formsynergy_request();
				},
			)
		);
	}
);


/**
 * Create the required elements an
 */
function formsynergy_create_elements() {
	global $synergypress;
	$autoload_params  = true;
	$autoload_options = true;
	$elements         = '';
	$plugin_options   = $synergypress->resources->Get( 'synergypress' );

	if ( isset( $plugin_options->autoload->params )
		&& 'no' === $plugin_options->autoload->params ) :
		$autoload_params = false;
	endif;

	if ( isset( $plugin_options->autoload->options )
		&& 'no' === $plugin_options->autoload->options ) :
		$autoload_options = false;
	endif;

	$advanced_mode = $synergypress->resources->Get( 'advancedMode' );
	$modules       = ! $advanced_mode
					|| 'yes' !== $advanced_mode->status
					&& $advanced_mode
					? array(
						'contactForm',
						'requestCallback',
						'newsLetterSubscription',
					)
					: $synergypress->resources->Get( 'modules' );

	foreach ( $modules as $module ) :
		$item         = $synergypress->resources->Get(
			$synergypress->plugin->camel_case(
				is_object( $module ) ? $module->name : $module
			)
		);
		$item_params  = true;
		$item_options = true;
		if ( $item ) :
			if ( isset( $item->autoload->params )
				&& 'no' === $item->autoload->params ) :
				$item_params = false;
			endif;

			if ( isset( $item->autoload->options )
				&& 'no' === $item->autoload->options ) :
				$item_options = false;
			endif;

			$allow_params_autoload = ( ! $advanced_mode
								? true
								: $autoload_params && $item_params )
								? true
								: false;

			$allow_options_autoload = $autoload_options && $item_options
								? true
								: false;

			if ( $allow_params_autoload ) :
				if ( isset( $item->params ) ) :
					$elements .= '<div style="display: none;"';
					foreach ( $item->params as $data_name => $data_value ) :
						$elements .= 'data-fs-' . $data_name . '=\'' . ( is_object( $data_value ) ? wp_json_encode( $data_value ) : $data_value ) . '\'';
					endforeach;
					$elements .= '></div>';
				endif;
			endif;

			if ( $advanced_mode && $allow_options_autoload ) :
				if ( isset( $item->options ) ) :
					$elements .= '<div style="display: none;"';
					foreach ( $item->options as $data_name => $data_value ) :
						$elements .= 'data-fs-' . $data_name . '=\'' . ( is_object( $data_value ) ? wp_json_encode( $data_value ) : $data_value ) . '\'';
					endforeach;
					$elements .= '></div>';
				endif;
			endif;
		endif;
	endforeach;
	echo $elements;
}
add_action( 'wp_footer', 'formsynergy_create_elements' );
