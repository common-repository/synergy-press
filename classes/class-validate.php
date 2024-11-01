<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.5.9
 * @package synergy-press
 **/

namespace SynergyPress;

/**
 * \SynergyPress\Validate()
 *
 * This class and its methods are used to sanitize and validate data.
 * WordPress Plugin guidelines.
 * https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/
 */
class Validate {

	/**
	 * Store profileid
	 */
	public $profileid = false;

	/**
	 * Sets the profileid
	 *
	 * @param string $profileid The profileid.
	 */
	public function set_profileid( $profileid ) {
		$this->profileid = $profileid;
	}

	/**
	 * Retrieves the profileid
	 *
	 * @param string $profileid The profileid.
	 */
	public function get_profileid() {
		return $this->profileid;
	}

	/**
	 * Sanitize title.
	 *
	 * @param string $data Title.
	 * @return string
	 */
	public function sanitize_title( $data ) {
		return sanitize_title( $data );
	}

	/**
	 * Sanitizes notification method.
	 *
	 * @param string $data Notification method.
	 * @return string
	 */
	public function sanitize_notification_method( $data ) {
		$allowed = array(
			'email',
			'webhook',
			'sms',
			'leadboard',
		);
		return in_array( $data, $allowed, true ) ? $data : 'leadboard';
	}

	/**
	 * Sanitizes autoload options.
	 *
	 * @param array $data Autoload.
	 * @return array
	 */
	public function sanitize_autoload( $data ) {
		return array(
			'params'  => isset( $data['params'] )
						&& 'yes' === $data['params']
						? 'yes'
						: 'no',
			'options' => isset( $data['options'] )
						&& 'yes' === $data['options']
						? 'yes'
						: 'no',
		);
	}

	/**
	 * Sanitizes text area.
	 *
	 * @param string $data Textarea.
	 * @return string
	 */
	public function sanitize_textarea_field( $data ) {
		return sanitize_textarea_field( $data );
	}

	/**
	 * Sanitizes localization options.
	 *
	 * @param array $data Localization options.
	 * @return array
	 */
	public function sanitize_localize( $data ) {
		$localize = array(
			'enable' => isset( $data['enable'] )
					? $this->sanitize_text_field( $data['enable'] )
					: 'no',
			'debug'  => isset( $data['debug'] )
						? $this->sanitize_text_field( $data['debug'] )
						: 'no',
		);

		if( isset( $data['modules'] ) && ! empty( $data['modules'] ) ) {
			$localize['modules'] = $data['modules'];
		}

		return $localize;
	}

	/**
	 * Sanitizes hashes such as profileid, siteid, moduleid...
	 *
	 * @param string $data Hashes.
	 * @return string
	 */
	public function sanitize_hash( $data ) {
		return sanitize_title( $data );
	}

	/**
	 * Sanitizes text fields
	 *
	 * @param string $data Text field.
	 * @return string
	 */
	public function sanitize_text_field( $data ) {
		return sanitize_text_field( $data );
	}

	/**
	 * Sanitizes numbers using intval
	 *
	 * @param int $data Number.
	 * @return int
	 */
	public function sanitize_number( $data ) {
		return intval( $data );
	}

	/**
	 * Sanitizes html classes
	 *
	 * @param string $data Class name.
	 * @return string
	 */
	public function sanitize_html_class( $data ) {
		return sanitize_html_class( $data );
	}

	/**
	 * Sanitizes a domain name using filer_var.
	 *
	 * @param string $data Domain name.
	 * @return string
	 */
	public function sanitize_domain( $data ) {
		return filter_var( $data, FILTER_VALIDATE_DOMAIN );
	}

	/**
	 * Sanitizes email address
	 *
	 * @param string $data Email.
	 * @return string
	 */
	public function sanitize_email( $data ) {
		return sanitize_email( $data );
	}

	/**
	 * Sanitizes endpoint data
	 *
	 * @param array $data Endpoint.
	 * @return array
	 */
	public function sanitize_endpoint( $data ) {
		$endpoint = array(
			'url'   => isset( $data['url'] )
					&& ! empty( $data['url'] )
					? esc_url( $data['url'] )
					: '',
			'whkey' => isset( $data['whkey'] )
					&& ! empty( $data['whkey'] )
					? $this->sanitize_hash( $data['whkey'] )
					: '',
		);

		return array_filter( $endpoint );
	}

	/**
	 * Sanitizes colors: hex, rgb, hsl, rgba and hsla
	 *
	 * @param string $data Color.
	 * @return string
	 */
	public function sanitize_color( $data ) {
		return preg_match( '/^(\#[\da-f]{3}|\#[\da-f]{6}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2} ((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/', $data );
	}

	/**
	 * Sanitizes etags
	 *
	 * @param string $data Etag.
	 * @return string
	 */
	public function sanitize_etag( $data ) {
		$etag = isset( $data['etag'] )
			? $data['etag']
			: 'onclick:' . $this->sanitize_hash( $data['name'] );
		return preg_replace( '/[^A-Za-z0-9:@>]/', '', $etag );
	}

	/**
	 * Sanitizes interaction headings
	 *
	 * @param array $data Headings.
	 * @return array
	 */
	public function sanitize_headings( $data ) {
		return array(
			array(
				'subject' => isset( $data['headings'][0]['subject'] )
							&& ! is_null( $data['headings'][0]['subject'] )
							&& ! empty( $data['headings'][0]['subject'] )
							? $this->sanitize_text_field( $data['headings'][0]['subject'] )
							: '',
				'body'    => isset( $data['headings'][0]['body'] )
							&& ! is_null( $data['headings'][0]['body'] )
							&& ! empty( $data['headings'][0]['body'] )
							? $this->sanitize_textarea_field( $data['headings'][0]['body'] )
							: '',
			),
		);
	}

	/**
	 * Sanitizes the form synergy heartbeat variables.
	 * NOTE: This heartbeat is not related to the WordPress heartbeat.
	 *
	 * @param array $data Heartbeat.
	 * @return array
	 */
	public function sanitize_heartbeat( $data ) {
		return array(
			'enable'    => isset( $data['enable'] )
						&& ! is_null( $data['enable'] )
						&& ! empty( $data['enable'] )
						? $this->sanitize_text_field( $data['enable'] )
						: 'yes',
			'frequency' => isset( $data['frequency'] )
						&& ! is_null( $data['frequency'] )
						&& ! empty( $data['frequency'] )
						? intval( $data['frequency'] )
						: 7500,
		);
	}

	/**
	 * Sanitizes URLs
	 *
	 * @param string $data URL.
	 * @return string
	 */
	public function sanitize_url( $data ) {
		return esc_url( $data );
	}

	/**
	 * Sanitizes modules to be used as a list.
	 *
	 * @param array $data Module.
	 * @return array
	 */
	public function sanitize_module_list( $data ) {
		return array(
			'name'     => $this->sanitize_text_field( $data['name'] ),
			'moduleid' => $this->sanitize_hash( $data['moduleid'] ),
		);
	}

	/**
	 * Sanitizes fstrigger html class used as reference.
	 *
	 * @param array $data Module.
	 * @return array
	 */
	public function sanitize_fstrigger( $data ) {
		return '.fs-trigger-' . $data['moduleid'];
	}

	/**
	 * Sanitizes fsplacement html class used as reference.
	 *
	 * @param array $data Module.
	 * @return array
	 */
	public function sanitize_fsplacement( $data ) {
		return '.fs-placement-' . $data['moduleid'];
	}

	/**
	 * Sanitizes imported modules.
	 *
	 * @param array $data Module.
	 * @return array
	 */
	public function sanitize_import_modules( $data ) {
		return array(
			'profileid'   => $this->sanitize_hash( $this->get_profileid() ),
			'moduleid'    => $this->sanitize_hash( $data['moduleid'] ),
			'name'        => $this->sanitize_text_field( $data['name'] ),
			'heading'     => $this->sanitize_headings( $data ),
			'options'     => $this->sanitize_options( $data ),
			'params'      => $this->sanitize_params( $data ),
			'fstrigger'   => $this->sanitize_fstrigger( $data ),
			'fsplacement' => isset( $data['fsplacement'] )
							? $this->sanitize_fsplacement( $data )
							: '',
			'DomObject'   => isset( $data['DomObject'] )
							? $data['DomObject']
							: null,
			'related'     => isset( $data['related'] )
							? $this->sanitize_related( $data['related'] )
							: null,
		);
	}

	/**
	 * Sanitize javascript.
	 *
	 * @param array $data Custom data.
	 * @return string
	 */
	public function sanitize_javascript( $data ) {
		$java_script_code = stripslashes( $data );
		$java_script_code = html_entity_decode( $java_script_code );
		return $java_script_code;
	}

	/**
	 * Sanitizes custom placement object.
	 *
	 * @param array $data Module.
	 * @return array
	 */
	public function sanitize_custom_placements( $data ) {
		return array(
			'autoload'    => $this->sanitize_autoload( $data['autoload'] ),
			'fsplacement' => $this->sanitize_html_class( $data['fsplacement'] ),
			'fstrigger'   => $this->sanitize_fstrigger( $data ),
			'headings'    => $this->sanitize_headings( $data ),
			'fsplacement' => $this->sanitize_fsplacement( $data ),
			'moduleid'    => $this->sanitize_hash( $data['moduleid'] ),
			'modid'       => isset( $data['modid'] )
							? $this->sanitize_hash( $data['modid'] )
							: null,
			'name'        => $this->sanitize_text_field( $data['name'] ),
			'options'     => $this->sanitize_options( $data ),
			'params'      => $this->sanitize_params( $data ),
			'profileid'   => $this->sanitize_hash( $this->get_profileid() ),
			'moduleid'    => $this->sanitize_hash( $data['moduleid'] ),
			'related'     => isset( $data['related'] )
							? $this->sanitize_related( $data['related'] )
							: null,
		);
	}

	/**
	 * Sanitizes modules.
	 *
	 * @param array  $data Module.
	 * @param string $mode Plugin mode.
	 * @return array
	 */
	public function sanitize_modules( $data, $mode = 'default' ) {
		return array(
			'install'     => ( isset( $data['install'] )
								&& in_array( $data['install'], array( 'yes', 'no' ), true )
								? $this->sanitize_text_field( $data['install'] )
								: 'advanced' === $mode )
								? 'yes'
								: 'no',
			'profileid'   => $this->sanitize_hash( $this->get_profileid() ),
			'moduleid'    => $this->sanitize_hash( $data['moduleid'] ),
			'name'        => $this->sanitize_text_field( $data['name'] ),
			'form'        => isset( $data['form'] )
							&& ! is_null( $data['form'] )
							&& ! empty( $data['form'] )
							? $data['form']
							: array(),
			'headings'    => $this->sanitize_headings( $data ),
			'options'     => $this->sanitize_options( $data ),
			'params'      => $this->sanitize_params( $data ),
			'fstrigger'   => $this->sanitize_fstrigger( $data ),
			'fsplacement' => isset( $data['fsplacement'] )
							? $this->sanitize_fsplacement( $data )
							: '',
			'autoload'    => isset( $data['autoload'] )
							? $this->sanitize_autoload( $data['autoload'] )
							: array(
								'options' => 'yes',
								'params'  => 'yes',
							),
			'related'     => isset( $data['related'] )
							? $this->sanitize_related( $data['related'] )
							: array(),
		);
	}

	/**
	 * Sanitizes objectives.
	 *
	 * @param array $data Objective.
	 * @return array
	 */
	public function sanitize_objective( $data ) {
		return array(
			'profileid'          => $this->sanitize_hash( $this->get_profileid() ),
			'modid'              => $this->sanitize_hash( $data['modid'] ),
			'obsid'              => $this->sanitize_hash( $data['obsid'] ),
			'label'              => $this->sanitize_text_field( $data['label'] ),
			'notificationmethod' => $this->sanitize_notification_method( $data['notificationmethod'] ),
			'recipient'          => $this->sanitize_recipient( $data['recipient'] ),
			'install'            => 'yes',
		);
	}

	/**
	 * Sanitizes advanced mode status.
	 *
	 * @param array $data Mode status.
	 * @return array
	 */
	public function sanitize_advanced_mode( $data ) {
		return array(
			'status' => isset( $data['status'] )
						? $this->sanitize_text_field( $data['status'] )
						: 'no',
		);
	}

	/**
	 * Sanitizes offset postions.
	 *
	 * @param array $data offsets.
	 * @return array
	 */
	public function sanitize_offset_positions( $data ) {
		$allowed = array(
			'up'    => 0,
			'down'  => 0,
			'left'  => 0,
			'right' => 0,
		);

		if ( ! is_array( $data ) ) :
			return $allowed;
		endif;

		foreach ( $data as $key => $value ) :
			if ( array_key_exists( $key, $allowed ) ) :
				$allowed[ $key ] = intval( $value );
			endif;
		endforeach;
		return $allowed;
	}

	/**
	 * Sanitizes opt.
	 *
	 * @param array $data opt.
	 * @return array
	 */
	public function sanitize_opt( $data ) {
		$allowed = array(
			'display'         => isset( $data['display'] )
						? $this->sanitize_text_field( $data['display'] )
						: 'fixed',
			'placement'       => isset( $data['placement'] )
						? $this->sanitize_text_field( $data['placement'] )
						: 'upper',
			'size'            => isset( $data['size'] )
						? $this->sanitize_text_field( $data['size'] )
						: 'lg',
			'theme'           => isset( $data['theme'] )
						? $this->sanitize_text_field( $data['theme'] )
						: 'white',
			'backgroundColor' => isset( $data['backgroundColor'] )
						? $this->sanitize_color( $data['backgroundColor'] )
						: null,
			'className'       => isset( $data['className'] )
						? $this->sanitize_html_class( $data['className'] )
						: null,
			'addon'           => isset( $data['addon'] )
						? $this->sanitize_html_class( $data['addon'] )
						: null,
			'bodyAddon'       => isset( $data['addon'] )
						? $this->sanitize_html_class( $data['addon'] )
						: null,
			'up'              => isset( $data['up'] )
						? intval( $data['up'] )
						: null,
			'down'            => isset( $data['down'] )
						? intval( $data['down'] )
						: null,
			'left'            => isset( $data['left'] )
						? intval( $data['left'] )
						: null,
			'right'           => isset( $data['right'] )
						? intval( $data['right'] )
						: null,
		);

		return array_filter( $allowed );
	}

	/**
	 * Sanitizes options.
	 *
	 * @param array $data options.
	 * @return array
	 */
	public function sanitize_options( $data ) {
		$opt = array();
		if ( isset( $data['options'] ) && is_array( $data['options'] ) ) :
			$options = $data['options'];
		elseif ( isset( $data['options'] ) && is_object( $data['options'] ) ) :
			$options = (array) $data['options'];
		elseif ( isset( $data['options'] ) ) :
			$options = json_decode( stripslashes( $data['options'] ), true );
		else :
			return array(
				'el'  => '@' . $this->sanitize_hash( $data['moduleid'] ),
				'opt' => $this->sanitize_opt( array() ),
			);
		endif;
		if ( isset( $options['opt'] ) ) :
			$opt = $options['opt'];
		endif;

		return array(
			'el'  => '@' . $this->sanitize_hash( $data['moduleid'] ),
			'opt' => $this->sanitize_opt( $opt ),
		);
	}

	/**
	 * Sanitizes and filters telephone number int.
	 *
	 * @param string $data phone number.
	 * @return int
	 */
	public function sanitize_phone( $data ) {
		return filter_var( $data, FILTER_SANITIZE_NUMBER_INT );
	}

	/**
	 * Sanitizes params.
	 *
	 * @param array $data params.
	 * @return array
	 */
	public function sanitize_params( $data ) {
		if ( isset( $data['params'] ) && is_array( $data['params'] ) ) :
			$params = $data['params'];
		elseif ( isset( $data['params'] ) && is_object( $data['params'] ) ) :
			$params = (array) $data['params'];
		elseif ( isset( $data['params'] ) ) :
			$params = json_decode( stripslashes( $data['params'] ), true );
		else :
			return array(
				'etag'   => $this->sanitize_etag( $data ),
				'params' => array(),
			);
		endif;

		$parameters = array(
			'trigger' => array(
				'moduleid' => $this->sanitize_hash( $data['moduleid'] ),
			),
			'rel'     => isset( $params['params']['rel'] )
					? $this->sanitize_context( $params['params']['rel'] )
					: null,
			'delay'   => isset( $params['params']['delay'] )
					? intval( isset( $params['params']['delay'] ) )
					: null,
		);

		return array(
			'etag'   => $this->sanitize_etag( $params ),
			'params' => array_filter( $parameters ),
		);
	}

	/**
	 * Sanitizes contextual data.
	 *
	 * @param array $data rel.
	 * @return array
	 */
	public function sanitize_context( $data ) {
		$context = array();
		foreach ( $data as $main => $values ) :
			foreach ( $values as $key => $value ) :
				$context[ sanitize_key( $key ) ] = wp_filter_post_kses( $value );
			endforeach;
		endforeach;
		return array( sanitize_key( $main ) => $context );
	}

	/**
	 * Sanitizes the protocol.
	 *
	 * @param string $data Protocol.
	 * @return array
	 */
	public function sanitize_proto( $data ) {
		return in_array( $data, array( 'http', 'https' ), true )
				? $data
				: 'http';
	}

	/**
	 * Sanitizes related attributes.
	 *
	 * @param string $data Related.
	 * @return array
	 */
	public function sanitize_related( $data ) {
		return array(
			'attribute' => isset( $data['attribute'] )
						? $this->sanitize_html_class( $data['attribute'] )
						: '',
			'value'     => isset( $data['value'] )
						? $this->sanitize_html_class( $data['value'] )
						: '',
		);
	}

	/**
	 * Sanitizes recipients information.
	 *
	 * @param array $data Recipient.
	 * @return array
	 */
	public function sanitize_recipient( $data ) {
		$recipient = array(
			'fname' => isset( $data['fname'] )
					&& ! empty( $data['fname'] )
					? $this->sanitize_text_field( $data['fname'] )
					: '',
			'lname' => isset( $data['lname'] )
					&& ! empty( $data['lname'] )
					? $this->sanitize_text_field( $data['lname'] )
					: '',
			'email' => isset( $data['email'] )
					&& ! empty( $data['email'] )
					? $this->sanitize_email( $data['email'] )
					: '',
			'sms'   => isset( $data['sms'] )
					&& ! empty( $data['sms'] )
					? $this->sanitize_phone( $data['sms'] )
					: '',
		);
		return array_filter( $recipient );
	}

	/**
	 * Sanitizes strategies.
	 *
	 * @param array $data Strategy.
	 * @return array
	 */
	public function sanitize_strategy( $data ) {
		$allowed  = array(
			'profileid' => array( 'sanitize_hash', 'profileid' ),
			'modid'     => array( 'sanitize_hash', 'modid' ),
			'siteid'    => array( 'sanitize_hash', 'siteid' ),
			'name'      => array( 'sanitize_text_field', 'name' ),
		);
		$strategy = array();

		foreach ( $allowed as $key => $properties ) :
			if ( array_key_exists( $key, $allowed ) ) :
				$strategy[ sanitize_key( $key ) ] = $this->{$properties[0]}( $data[ $properties[1] ] );
			endif;
		endforeach;
		return array_filter( $strategy );
	}

	/**
	 * Sanitizes site configuration.
	 *
	 * @param array $data Site config.
	 * @return array
	 */
	public function sanitize_site_config( $data ) {
		$allowed     = array(
			'domain'    => array( 'sanitize_domain', 'domain' ),
			'profileid' => array( 'sanitize_hash', 'profileid' ),
			'siteid'    => array( 'sanitize_hash', 'siteid' ),
			'name'      => array( 'sanitize_text_field', 'name' ),
			'indexpage' => array( 'sanitize_url', 'indexpage' ),
			'proto'     => array( 'sanitize_proto', 'proto' ),
			'verified'  => array( 'sanitize_text_field', 'verified' ),
			'heartbeat' => array( 'sanitize_heartbeat', 'heartbeat' ),
			'offset'    => array( 'sanitize_offset_positions', 'offset' ),
		);
		$site_config = array();
		foreach ( $allowed as $key => $property ) :
			if ( isset( $data[ $key ] ) ) :
				$site_config[ sanitize_key( $key ) ] = $this->{$property[0]}( $data[ $property[1] ] );
			endif;
		endforeach;

		return array_filter( $site_config );
	}

	/**
	 * Sanitizes API configuration.
	 *
	 * @param array $data API config.
	 * @return array
	 */
	public function sanitize_api_config( $data ) {
		$api_config = array(
			'apikey'    => $this->sanitize_hash( $data['apikey'] ),
			'profileid' => $this->sanitize_hash( $data['profileid'] ),
			'siteid'    => $this->sanitize_hash( $data['siteid'] ),
			'secretkey' => $this->sanitize_hash( $data['secretkey'] ),
		);

		return array_filter( $api_config );
	}

	/**
	 * Sanitizes plugin options.
	 *
	 * @param array $data Options.
	 * @return array
	 */
	public function sanitize_plugin_options( $data ) {
		$plugin_options = isset( $data['autoload'] )
						? $this->sanitize_autoload( $data['autoload'] )
						: array(
							'options' => 'yes',
							'params'  => 'yes',
						);
		$plugin_options['synergypress_api_debug'] = isset( $data['synergypress_api_debug'] )
												? $data['synergypress_api_debug']
												: 'no';
		return $plugin_options;
	}

	/**
	 * Sanitizes localization.
	 *
	 * @param array $data Module.
	 * @return array
	 */
	public function sanitize_localization( $data ) {
		$modules = array();
		if ( isset( $data['modules'] ) && ! empty( $data['modules'] ) ) :
			foreach ( $data['modules'] as $module ) :
				$modules[] = array(
					'name'     => $this->sanitize_text_field( $module['name'] ),
					'moduleid' => $this->sanitize_hash( $module['moduleid'] ),
				);
			endforeach;
		endif;

		return array(
			'enable'  => isset( $data['enable'] )
						&& ! empty( $data['enable'] )
						? $data['enable']
						: 'no',
			'debug'   => isset( $data['debug'] )
						&& ! empty( $data['debug'] )
						? $data['debug']
						: 'no',
			'modules' => $modules,
		);
	}

	/**
	 * Sanitizes request array.
	 *
	 * @param array $data Request.
	 * @return array
	 */
	public function sanitize_request_array( $data ) {
		$request_array = array();
		foreach ( $data as $item ) :
			$request_array[] = ( is_array( $item )
							? $this->sanitize_request_array( $item )
							: is_object( $item ) )
							? $this->sanitize_request_array( (array) $item )
							: $this->sanitize_text_field( $item );
		endforeach;
		return $request_array;
	}

	/**
	 * Sanitizes the request.
	 *
	 * @param array $data Request.
	 * @return array
	 */
	public function sanitize_request( $data ) {
		$request = array();
		foreach ( $data as $key => $value ) :
			if ( 'javascript' === sanitize_key( $key ) ) :
				$request[ sanitize_key( $key ) ] = $this->sanitize_javascript( $value );
			else :
				$request[ sanitize_key( $key ) ] = ( is_array( $value )
												? $this->sanitize_request_array( $value )
												: is_object( $value ) )
												? $this->sanitize_request( $value )
												: $this->sanitize_text_field( $value );
			endif;
		endforeach;
		return $request;
	}

	/**
	 * Esc hash.
	 *
	 * @param string $data Hash.
	 * @return hash
	 */
	public function esc_hash( $data ) {
		return esc_attr( $this->sanitize_title( $data ) );
	}

	/**
	 * Tests for json data.
	 *
	 * @param mixed $data Data.
	 * @return bool
	 */
	public function is_json( $data ) {
		try {
			return json_decode( $data )
				? true
				: false;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Removes amps; from GET requests when using wp_safe_redirects with wp_nonce_url.
	 *
	 * @param array $data Data to clean.
	 * @return array
	 */
	public function unamp_array( $data ) {
		foreach ( $data as $key => $val ) :
			if ( strpos( $key, 'amp;' ) !== false ) :
				$data[ sanitize_key( str_replace( 'amp;', '', $key ) ) ] = sanitize_text_field( $val );
			endif;
		endforeach;
		return $data;
	}
}
