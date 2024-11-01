<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

namespace FormSynergy;

/**
 * FormSynergy Api PHP Client for SynergyPress plugin.
 *
 * This client api can be used to manage and administer FormSynergy api accounts.
 *
 * For fast and easy development this Api, simplifies the registration of domains, along with strategies and modules.
 * Take a quick look at the example-setup-php directory, this basic example can be improved to best fit your needs.
 *
 * @author     Joseph G. Chamoun <formsynergy@gmail.com>
 * @copyright  2019 FormSynergy.com
 * @licence    https://github.com/form-synergy/php-api/blob/master/LICENSE
 */

/**
 * Fs class
 *
 * @version 1.6.0
 */
class Fs {

	/**
	 * FormSynergy version constant.
	 */
	const FORMSYNERGY_VERSION = '1.6.0.2';

	/**
	 * self::$config
	 *
	 * Contains the necessary keys to gain access to the API service.
	 *
	 * @visibility public static
	 * @var array $config
	 */
	public static $config;

	/**
	 * Enables management over multiple accounts.
	 * self::$resellerid
	 *
	 * @visibility public static
	 * @var string $resellerid
	 */
	public static $resellerid;

	/**
	 * Unique identifier for an account or profile.
	 * self::$profileid
	 *
	 * @visibility public static
	 * @var string $profileid
	 */
	public static $profileid;

	/**
	 * Type of storage
	 * self::$useStorage
	 * Two options are available
	 *  - File_Storage: The default storage
	 *  - Option_Storage: For WP plugin, it uses the wp_options API
	 *
	 * @visibility public static
	 * @var sting $useStorage
	 */
	public static $useStorage;

	/**
	 * Directory to store details regarding resources.
	 * self::$storage
	 *
	 * @visibility public static
	 * @var sting $storage
	 */
	public static $storage;

	/**
	 * Load the reseller account, in order to manage multiple profiles.
	 * Resellerid()
	 *
	 * @visibility public static
	 * @param string $resellerid ResllerId.
	 * @uses self::$resellerid
	 * @throws FsException Throws new FsException.
	 * @returns void
	 */
	public static function Reseller( $resellerid = null ) {
		if ( ! self::$resellerid && is_null( $resellerid ) ) {
			throw new FsException( 'In order to return the reseller id, it must be defined first.' );
		}
		if ( ! is_null( $resellerid ) ) {
			self::$resellerid = $resellerid;
		}
		return self::$resellerid;
	}

	/**
	 * Load the profile, a new profile id can be set to load an different account.
	 * Load()
	 *
	 * @visibility public static
	 * @param string $profileid ProfileId.
	 * @throws FsException Throws new FsException.
	 * @returns void
	 */
	public static function Load( $profileid = null ) {
		if ( ! self::$profileid && is_null( $profileid ) ) {
			throw new FsException( 'In order to return the profile id, it must be defined first.' );
		}
		if ( ! is_null( $profileid ) ) {
			self::$profileid = $profileid;
		}
		return self::$profileid;
	}

	/**
	 * API configuration
	 * Config()
	 *
	 * @visibility public static
	 * @param array $config API config.
	 * @throws FsException Throws new FsException.
	 * @returns void
	 */
	public static function Config( $config = null ) {
		if ( ! self::$config && is_null( $config ) ) {
			throw new FsException( 'In order to return configuration variables, they myst be defined first.' );
		}
		if ( ! is_null( $config ) ) {
			self::$config = $config;
		}
		return self::$config;
	}

	public static function UseStorage( $storage ) {
		self::$useStorage = $storage;
	}

	/**
	 * Will set the default storage.
	 * Storage()
	 *
	 * @visibility public static
	 * @uses is_dir()
	 * @uses mkdir()
	 * @uses file_exists()
	 * @uses rtrim()
	 * @uses FsException()
	 * @uses self::$storage
	 * @param string $path Will set storage path.
	 * @param string $dir Will set storage dir.
	 * @return void
	 */
	public static function Storage( $path, $dir ) {
		is_dir( $path . '/' . $dir ) || mkdir( $path . '/' . $dir );
		if ( is_writable( $path . '/' . $dir ) ) {
			self::$storage = rtrim( $path . '/' . $dir, '/' );
		} elseif ( file_exists( $path . '/' . $dir ) ) {
			self::$storage = rtrim( $path . '/' . $dir, '/' );
		} else {
			self::$storage = false;
		}
	}

	/**
	 * Helper method to check if a needle exists in a haystack.
	 * Includes()
	 *
	 * @visibility public static
	 * @uses strpos()
	 * @uses strtolower()
	 * @param string $needle What to search for.
	 * @param string $haystack String to search within.
	 * @returns bool
	 */
	public static function Includes( $needle, $haystack ) {
		if ( ! $haystack ) {
			return false;
		}
		if ( ! $needle ) {
			return false;
		}
		$return = ( strpos( strtolower( $haystack ), strtolower( $needle ) ) !== false ) ? true : false;
		return $return;
	}

	/**
	 * Will instantiate the Resource class through static method
	 * Resources()
	 *
	 * @visibility public static
	 * @see class Resources
	 * @param string $package
	 * @returns object $resource
	 */
	public static function Resource( $package ) {
		if ( ! isset( self::$useStorage ) ) {
			throw new FsException( 'Storage type not defined!' );
		}
		switch ( self::$useStorage ) {
			case 'Option_Storage':
				$resource = new Option_Storage( $package );
				return $resource;
			break;
			/**
			 * This class is only used when implementing without WordPress.
			 */
			case 'File_Storage':
				$resource = new File_Storage( $package, self::$storage );
				return $resource;
				break;

		}
	}

	/**
	 * Will instantiate the API.
	 * Api()
	 *
	 * @visibility public static
	 * @see class Client
	 * @uses isset()
	 * @uses Client::Config()
	 * @uses Client::Reseller()
	 * @uses Client::Load()
	 * @return object
	 */
	public static function Api() {
		try {
			$api = new Client();
			$api->Config( self::$config );
			/**
			 * This feature is not available on WordPress.
			 */
			if ( isset( self::$resellerid ) ) {
				$api->Reseller( self::$resellerid );
			}

			if ( isset( self::$profileid ) ) {
				$api->Load( self::$profileid );
			}
			return $api;
		} catch ( FsException $e ) {
			echo $e->getMessage();
		}
	}

	/**
	 * Returns the FormSynergy version string.
	 * The FormSynergy version string always has the same format "X.Y.Z"
	 * where X is the major version number and Y is the minor version number
	 * and z for patch.
	 *
	 * @visibility public static
	 * @return string
	 */
	public static function Version() {
		return self::FORMSYNERGY_VERSION;
	}
}

/**
 * Form Synergy Client Class
 */
class Client {


	/**
	 * Configuration of the api client
	 * self::$config
	 *
	 * @visibility private
	 * @var array
	 */
	private $config = array();

	/**
	 * Internal array to manage variables
	 * self::$internal
	 *
	 * @visibility private
	 * @var array
	 */
	private $internal = array();

	/**
	 * Will temporary store response
	 * self::as
	 *
	 * @visibility private
	 * @var array
	 */
	private $as = array();

	/**
	 * Configuration function to ser config values
	 * Config()
	 *
	 * @visibility public
	 * @param array $config API configuration.
	 * @return void
	 */
	public function Config( $config ) {
		$this->config = $config;
		$this->_rel( 'max_auth_count', 0 );
		$this->_rel( 'max_auth_allowed', $config['max_auth_count'] );
	}

	/**
	 * Setter for rel
	 * _rel()
	 *
	 * @visibility public
	 * @param sting $name Key.
	 * @param mixed $value Value.
	 * @return self
	 */
	public function _rel( $name, $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				$this->internal[ $name ][ $k ] = $v;
			}
			return $this;
		}

		$this->internal[ $name ] = $value;
		return $this;
	}

	/**
	 * Setter and getter, if a value is passed, the function will assume
	 * the setter responsibilities.
	 * If no value is present, it will assume the getter responsibilities.
	 *
	 * If the value is an array, the key and it's values will be appended
	 * to the name.
	 *
	 * rel()
	 *
	 * @visibility public
	 * @uses Api::_rel()
	 * @param string $name Key.
	 * @param mixed  $value Value.
	 * @return mixed
	 */
	public function rel( $name, $value = null ) {
		if ( 'reset' == $value ) {
			$this->internal[ $name ] = is_string( $this->internal[ $name ] ) ? null : array();
			return;
		}

		if ( is_null( $value ) && isset( $this->internal[ $name ] ) ) {
			return $this->internal[ $name ];
		}

		$this->_rel( $name, $value );
		return $value;
	}

	/**
	 * Will remove data stored in the internal object
	 * unrel()
	 *
	 * @visibility public
	 * @uses Api::unrel()
	 * @param string $name Name.
	 * @param string $key Key.
	 * @return void
	 */
	public function unrel( $name, $key = null ) {
		if ( is_null( $key ) && isset( $this->internal[ $name ] ) ) {
			unset( $this->internal[ $name ] );
		} elseif ( isset( $this->internal[ $name ][ $key ] ) ) {
			unset( $this->internal[ $name ][ $key ] );
		}
	}

	/**
	 * The options will streamline the process of distributing variables
	 * to the rel function.
	 * options()
	 *
	 * @visibility public
	 * @uses Api::rel()
	 * @param array $options Options.
	 * @return void
	 */
	public function options( $options ) {
		foreach ( $options as $key => $values ) {
			$this->rel( $key, $values );
		}
	}

	/**
	 * Will set the reseller account as master.
	 * Reseller()
	 *
	 * @visibility public
	 * @uses Api::options()
	 * @param string $resellerid ResellerId.
	 * @return self
	 */
	public function Reseller( $resellerid ) {
		$this->options(
			array(
				'request' => array(
					'reseller' => array(
						'resellerid' => $resellerid,
					),
				),
			)
		);

		return $this;
	}

	/**
	 * Will load the profile associated with an account.
	 * Load()
	 *
	 * @visibility public
	 * @uses Api::options()
	 * @param string $profileid ProfileId.
	 * @return self
	 */
	public function Load( $profileid ) {
		$this->options(
			array(
				'request' => array(
					'load' => array(
						'profileid' => $profileid,
					),
				),
			)
		);

		return $this;
	}

	/**
	 * Our Api infrastructure utilizes dynamic access point,
	 * requiring automated authentication.
	 *
	 * This function will automatically provide the required
	 * credentials.
	 *
	 * Once authentication is successful, a new access point,
	 * will provide temporary access.
	 * Authenticate()
	 *
	 * @visibility private
	 * @param string $authenticate Authentication hash.
	 * @throws FsException Throws new FsException.
	 * @return void
	 */
	private function Authenticate( $authenticate = null ) {
		// Get stored access point from session.
		$accessPoint = Session::Get( 'AccessPoint' );

		// Access point exists, try again on the next request.
		if ( $accessPoint ) {
			return;
		}

		/**
		 * An other precaution, since authentication is generated automatically,
		 * if authentication was not successful due to network or connection issues,
		 * It will keep on trying.
		 *
		 * This block will limit this process to a set number.
		 *
		 * The max consecutive Authentication can be set in the config method.
		 */

		if ( $this->rel( 'max_auth_allowed' ) < $this->rel( 'max_auth_count', $this->rel( 'max_auth_count' ) + 1 ) ) {
			throw new FsException( 'Authorization Count exceeds the set limit of ' . $this->rel( 'max_auth_count' ) );
		}

		/**
		 * In certain cases an authenticate request disrupted ordinary requests,
		 * we will move the request details to a temporary storage.
		 */
		$this->rel(
			'temp',
			array(
				'resource' => $this->rel( 'resource' ),
				'method'   => $this->rel( 'method' ),
				'envelope' => $this->rel( 'envelope' ),
			)
		);

		/**
		 * !Important: Do not pass the secret key directly.
		 * The authentication process, will require the apikey, and a secret hash
		 */

		// 1: Create a timestamp.
		$timestamp = time();

		// Check for SHA3-512 in algos
		$algos = hash_algos();
		if( in_array( 'sha3-512', $algos ) ) {

			/**
			 * SHA3-512 is available
			 * 2: Combine and hash the timestamp and the secretkey.
			 * NOTE: md5 + SHA3-512 are required.
			 */
			$hash = md5( hash( 'SHA3-512', $timestamp . $this->config['secretkey'] ) );
		}
		else {
			/**
			 * SHA3-512 is not available
			 * using hash_hmac sha256
			 */
			$hash = hash_hmac( 'sha256', $timestamp, $this->config['secretkey'] );
		}

		if ( is_null( $authenticate ) ) {
			$authenticate = array(
				'apikey'     => $this->config['apikey'],
				'secrethash' => $hash,
				'timestamp'  => $timestamp,
			);
		}
		$this->options(
			array(
				'resource' => 'authenticate',
				'envelope' => 'authenticate',
				'request'  => array(
					'authenticate' => $authenticate,
				),
			)
		);

		$this->_transmit( true );
	}

	/**
	 * _close_authentification_request()
	 *
	 * The authentication process was successful, we can resume activities.
	 *
	 * We previously stored request details in a temporary storage to prevent
	 * service interruption, we can set the request back into position.
	 *
	 * @visibility private
	 * @param string $accessPoint Accesspoint.
	 * @return self
	 */
	private function _close_authentification_request( $accessPoint ) {
		$options = $this->rel( 'tem' );

		if( ! $options ) {
			return;
		}

		$this->options(
			array(
				'method'   => $options['method'],
				'envelope' => $options['envelope'],
				'resource' => $options['resource'],
			)
		);

		return $this;
	}

	/**
	 * Will prepare a Get request.
	 *
	 * NOTE: Get is used to set the resource we need to get.
	 * Next, we can apply a query using Where.
	 * This function can be used independently, however when updating or deleting
	 * an object, Get must pressed and Update and Delete requests.
	 *
	 * Get()
	 *
	 * @example:
	 *   $api->Get( 'modules' )
	 *
	 *           ->Where([
	 *                  'modid' => $modid
	 *           ])
	 *
	 *           ->Update([
	 *              'subject' => $subject,
	 *               ...
	 *           ]);
	 *
	 *   $response = $api->Response();
	 *
	 * @visibility public
	 * @see https://... for more examples
	 * @uses Api::Where()
	 * @uses Api::options()
	 * @param string $resource Resource type.
	 * @return self
	 */
	public function Get( $resource, $transmit = false ) {
		$this->options(
			array(
				'resource' => $resource,
				'method'   => 'GET',
				'envelope' => 'get',
			)
		);
		return $this;
	}

	/**
	 * Will create a post request.
	 *
	 * NOTE: When sending a create request, any attributes related to
	 * the create object must use the Attributes function.
	 * Create()
	 *
	 * @example:
	 *   $api->Create('leads')
	 *
	 *      ->Attributes([
	 *          'fname' => '',
	 *          'lname' => ''
	 *      ]);
	 *
	 *   $response = $api->Response();
	 *
	 * @visibility public
	 * @see https://... for more examples
	 * @uses Api::Attributes()
	 * @uses Api::options()
	 * @param string $resource Resource type.
	 * @return self
	 */
	public function Create( $resource ) {
		$this->options(
			array(
				'resource' => $resource,
				'method'   => 'POST',
				'envelope' => 'create',
			)
		);

		return $this;
	}

	/**
	 * Will create a delete request.
	 *
	 * Delete()
	 *
	 * NOTE: When deleting an object, first we need to get the resource.
	 *
	 * 1) Get the resource:
	 *      $api->Get( 'Resource name ')
	 *
	 * 2) Locate the object
	 *      ->Where( array )
	 *
	 * 3) Complete the delete process
	 *      ->Delete();
	 *
	 * @visibility public
	 * @see https://... for more examples
	 * @uses Api::Get()
	 * @uses Api::Where()
	 * @uses Api::options()
	 * @uses Api::_transmit()
	 * @return self
	 */
	public function Delete() {
		$this->options(
			array(
				'objid'    => $this->rel( 'objid' ),
				'method'   => 'DELETE',
				'envelope' => 'delete',
			)
		);
		$this->_transmit( true );
		return $this;
	}

	/**
	 * Find can be used to get multiple objects.
	 * It also supports model based queries.
	 *
	 * Find()
	 *
	 * It can be used in combination with:
	 *  With() and Where().
	 *
	 * @example :
	 *
	 *      $api->Find('leads')
	 *          ->With([
	 *              'label' => 'example scoring model'
	 *          ])
	 *
	 *          ->Where([
	 *              'fname'=> [
	 *                  'value' => 'joe',
	 *                  'confirmed' => 'yes'
	 *               ]
	 *          ]);
	 *
	 *      $data = $api->Response()['data'];
	 *
	 * @visibility public
	 * @see https://... for more examples
	 * @uses Api::With()
	 * @uses Api::Where()
	 * @uses Api::options()
	 * @param string $find Resource.
	 * @return self
	 */
	public function Find( $find ) {
		$this->options(
			array(
				'resource' => $find,
				'method'   => 'GET',
				'envelope' => 'find',
			)
		);

		return $this;
	}

	/**
	 * Defines the terms of a request.
	 * It is required when getting or finding object.
	 *
	 * Where()
	 *
	 * @visibility public
	 * @param array $where Search parameters.
	 * @uses Api::options()
	 * @uses Api::_transmit()
	 * @return self
	 */
	public function Where( $where ) {
		$this->options(
			array(
				'request' => array(
					'where' => $where,
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Once verification meta tag is included in the index page, verify the domain.
	 *
	 * Verify()
	 *
	 * @visibility public
	 * @return self
	 */
	public function Verify() {
		$this->options(
			array(
				'method'   => 'PUT',
				'envelope' => 'verify',
				'request'  => array(
					'verify' => true,
					'objid'  => $this->rel( 'objid' ),
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Once a domain has been verified, scan the domain in question to retrieve all etags.
	 *
	 * Scan()
	 *
	 * @visibility public
	 * @return self
	 */
	public function Scan() {
		$this->options(
			array(
				'method'   => 'PUT',
				'envelope' => 'scan',
				'request'  => array(
					'scan'  => true,
					'objid' => $this->rel( 'objid' ),
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Will return self in closure.
	 *
	 * Then()
	 *
	 * @visibility public
	 * @param callable $fn Callback function.
	 * @return self
	 */
	public function Then( $fn ) {
		return $fn( $this );
	}


	/**
	 * It store the response as a named keyword,
	 * when using the Find method, the response will
	 * consist of multiple results, use $index, to
	 * store one result.
	 *
	 * __As()
	 *
	 * Example:
	 *  ->As('name', 0);
	 *
	 * @visibility public
	 * @use __call()
	 * @use Api::_rel()
	 * @param string $name Callable named function.
	 * @param int    $index Index.
	 * @return self
	 */
	public function As( $name, $index = null ) {
		$response = $this->Response();
		$name     = str_replace( '-', ' ', $name );
		$this->rel( '_as', $name );
		$this->as[ $name ] = ! is_null( $index ) ? $response['data'][ $index ] : $response['data'];

		return $this;
	}


	/**
	 * Will return all selected resources.
	 *
	 * Export()
	 *
	 * @visibility public
	 * @param array $resources Resources to export.
	 * @return self
	 */
	public function Export( $resources ) {
		$this->options(
			array(
				'resource' => 'export',
				'method'   => 'GET',
				'envelope' => 'with',
				'request'  => array(
					'with'  => $resources,
					'where' => array(
						'profileid' => $this->rel( 'request' )['load']['profileid'],
					),
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Used to retrieve responses
	 *
	 * __call()
	 *
	 * @visibility public
	 * @param string $as_method Name of stored response.
	 * @param string $k Key within a stored response.
	 * @throws FsException Throws new FsException.
	 * @return mixed
	 */
	public function __call( $as_method, $k = false ) {
		$method = ltrim( stristr( $as_method, '_' ), '_' );

		// When allocated resources are depleted.
		if ( isset( $this->as[ $method ]['resourceCode'] ) ) {
			set_transient( 'synergypress_api_notice', sanitize_text_field( $this->as[ $method ]['resourceCode'] ) );
			return;
		}

		if ( 'all' == $method ) {
			return $this->as;
		} elseif ( isset( $this->as[ $method ] ) ) {
			return $k
			&& is_array( $this->as[ $method ] )
			&& isset( $this->as[ $method ][ $k[0] ] )
			? $this->as[ $method ][ $k[0] ]
			: $this->as[ $method ];
		} elseif ( $k ) {
			throw new FsException( 'Unable to locate information' );
		} else {
			return false;
		}
	}

	/**
	 * Defines the data of an object that is being created.
	 * It is required when creating an object.
	 *
	 * Attributes()
	 *
	 * @visibility public
	 * @param array $attributes Attributes.
	 * @uses options()
	 * @uses Api::_transmit()
	 */
	public function Attributes( $attributes ) {
		$this->options(
			array(
				'request' => array(
					'create' => array(
						'attributes' => $attributes,
					),
				),
			)
		);

		$this->_transmit();
		return $this;
	}

	/**
	 * Before updating an object, we must retrieve the object in question.
	 * Any updates will be contained in the update method.
	 * Update()
	 *
	 * NOTE: This method supports partial updates.
	 *      For partial updates it is necessary to provide an array
	 *      representing the data to update.
	 *      Once the request is received by the service the new changes
	 *      will be applied to the existing data.
	 *
	 * @example 1 :
	 *      $api->Get('leads')
	 *          ->Where([
	 *              'userid' => $userid
	 *          ])
	 *
	 *          ->Update([
	 *              'fname' => [
	 *                  'value' => 'Smith',
	 *                  'confirmed' => 'yes'
	 *              ]
	 *          ]);
	 *
	 * @example 2 :
	 *      $api->Get('strategies')
	 *          ->Where([
	 *              'modid' => $modid
	 *          ])
	 *          ->Update([
	 *              'onsubmit' => $useModuleId,
	 *              'triggeringevents' => [
	 *                  'eventcombo' => [
	 *                      0 => [
	 *                          'recurrence' => 25
	 *                      ]
	 *                  ]
	 *              ],
	 *              'message' => 'New message'
	 *          ]);
	 *
	 * @visibility public
	 * @see https://... for more examples
	 * @param array $update Attributes.
	 * @uses Api::options()
	 * @uses Api::_transmit()
	 */
	public function Update( $update ) {
		$this->options(
			array(
				'method'   => 'PUT',
				'envelope' => 'update',
				'request'  => array(
					'update' => array(
						'attributes' => $update,
					),
					'objid'  => $this->rel( 'objid' ),
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Will replace the attributes stored on the interaction service
	 *
	 * Replace()
	 *
	 * @visibility public
	 * @param array $update Attributes.
	 * @uses Api::_options()
	 * @uses Api::rel()
	 * @uses Api::_transmit()
	 * @return object
	 */
	public function Replace( $update ) {
		$this->options(
			array(
				'method'   => 'PUT',
				'envelope' => 'update',
				'request'  => array(
					'replace' => array(
						'attributes' => $update,
					),
					'objid'   => $this->rel( 'objid' ),
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Will renew Api and Secret key.
	 *
	 * Renew()
	 *
	 * @visibility public
	 * @uses Api::_options()
	 * @uses Api::rel()
	 * @uses Api::_transmit()
	 * @return object
	 */
	public function Renew() {
		$this->options(
			array(
				'method'   => 'PUT',
				'envelope' => 'renew',
				'request'  => array(
					'objid' => $this->rel( 'objid' ),
				),
			)
		);
		$this->_transmit();
		return $this;
	}

	/**
	 * Downloads all modules within a strategy.
	 * Each module contains a stringified DomObject.
	 * This method is used to localize interaction modules and will be displayed
	 * using virtual dom rendering.
	 * Download()
	 *
	 * @visibility public
	 * @uses Api::_options()
	 * @uses Api::_transmit()
	 * @return object
	 */
	public function Download( $resource ) {
		$this->options(
			array(
				'resource' => $resource,
				'method'   => 'GET',
				'envelope' => 'download',
				'request'  => array(
					'download' => true,
				),
			)
		);

		return $this;
	}

	/**
	 * Prepares the request URI.
	 * uri()
	 *
	 * Will prepare the request uri by gathering the following:
	 *  - version
	 *  - accessPoint
	 *  - resource
	 *
	 * @visibility private
	 * @uses Api::_options()
	 * @uses Api::rel()
	 * @uses Session::Get()
	 * @return string url
	 */
	private function uri() {
		$uri         = '/';
		$uri        .= $this->config['version'];
		$uri        .= '/';
		$accessPoint = Session::Get( 'AccessPoint' );

		$uri .= $accessPoint ? $accessPoint : '';
		$uri .= $accessPoint ? '/' : '';
		$uri .= $this->rel( 'resource' );
		$uri .= '/';

		$this->options(
			array(
				'request' => array(
					'uri' => $uri,
				),
			)
		);
		return $uri;
	}

	/**
	 * Will prepare and package the request into a payload.
	 *
	 * _prepared_request()
	 *
	 * @visibility private
	 * @uses Api::rel()
	 * @uses json_encode()
	 * @return array
	 */
	private function _prepared_request() {
		switch ( $this->rel( 'method' ) ) {

			case 'GET':
				return array(
					'query' => array(
						'payload' => json_encode( $this->rel( 'request' ) ),
					),
				);
				break;

			case 'POST':
			case 'PUT':
			case 'DELETE':
				return array(
					'form_params' => array(
						'payload' => json_encode( $this->rel( 'request' ) ),
					),
				);
				break;
		}
	}

	/**
	 * Will send a request to the Interactive Mod api service.
	 *
	 * _transmit()
	 *
	 * Since authentication occurs automatically,
	 * the "$auth" flag must be present to prevent
	 * to hook the authentication process, and prevent an infinit loop.
	 *
	 * @visibility private
	 * @uses Api::_response_handler()
	 * @uses Api::rel()
	 * @uses Api::uri()
	 * @uses Api::_prepared_request()
	 * @uses GuzzleHttp\Client()
	 * @uses GuzzleHttp\Exception\ClientException
	 * @param bool $auth Authorization.
	 * @throws FsException Throws new FsException.
	 */
	private function _transmit( $auth = false ) {
		// Check if the config exists.
		if ( ! $this->config ) {
			// Exit the config is required.
			throw new FsException( 'Configuration settings are missing' );
		}

		/**
		 * "$auth" will indicate whether the current transmission
		 * is used for authentication or regular request.
		 *
		 * If an "Authenticate" flag is sent, we must authenticate.
		 */
		if ( ! $auth && Session::Get( 'Authenticate' ) ) {
			$this->Authenticate();
		}

		/**
		 * Instantiate The Guzzle Client.
		 */
		$client = new \GuzzleHttp\Client(
			array(
				'base_uri' => $this->config['protocol'] . '://' . $this->config['endpoint'],
			)
		);

		try {
			$response = $client->request(
				$this->rel( 'method' ),
				$this->uri(),
				$this->_prepared_request()
			);

			// Will send the response to a response handler.
			$this->_response_handler( $response );

			/**
			 * Certain response codes be the api service will trigger a 500 response code the Guzzle client.
			 */
		} catch ( \GuzzleHttp\Exception\ClientException $e ) {

			/**
			 * Exceptions can be edited to whatever is needed.
			 *
			 * In this case, we will issue the same response code,
			 * and display the response phrase.
			 */
			http_response_code( $e->getResponse()->getStatusCode() );
			throw new FsException( 'Server responded with a: ' . $e->getResponse()->getStatusCode() . ', ' . $e->getResponse()->getReasonPhrase() . '.' );
		}
		return $this;
	}

	/**
	 * Will restructure the response and provide simple access to
	 * response data.
	 *
	 * _response_handler()
	 *
	 * In addition it handles authentication, and access point.
	 * The response data can be accessed by using $api->Response();
	 *
	 * @visibility private
	 * @uses json_decode()
	 * @uses GuzzleHttp\Client()::getBody()
	 * @uses GuzzleHttp\Client()::getStatusCode()
	 * @uses GuzzleHttp\Client()::getReasonPhrase()
	 * @uses GuzzleHttp\Client()::getHeaders()
	 * @uses Session::Set()
	 * @uses Session::Delete()
	 * @uses Api::_close_authentification_request()
	 * @uses Api::rel()
	 * @param object $response Response.
	 */
	private function _response_handler( $response ) {
		$data = json_decode( $response->getBody(), true );

		$this->rel(
			'response',
			array(
				'statusCode'      => $response->getStatusCode(),
				'responsePhrase'  => $response->getReasonPhrase(),
				'responseHeaders' => $response->getHeaders(),
				'responseBody'    => $response->getBody(),
				'data'            => $data,
			)
		);

		if ( isset( $data['AccessPoint'] ) && $data['AccessPoint'] ) {
			Session::Set( 'AccessPoint', $data['AccessPoint'] );
			Session::Delete( 'Authenticate' );
			$this->_close_authentification_request( $data['AccessPoint'] );
		}

		if ( isset( $data['Authenticate'] ) && $data['Authenticate'] && ! $data['AccessPoint'] ) {
			Session::Set( 'Authenticate', 'AccessPoint' );
		}

		if ( isset( $data['objid'] ) ) {
			$this->rel( 'objid', $data['objid'] );
		}
		$this->rel( 'response', $data );

		return $this;
	}

	/**
	 * Will return the response of a request.
	 *
	 * Response()
	 *
	 * @visibility public
	 * @uses Api::rel()
	 * @param bool $null Response switch.
	 * @return array $response [
	 *          'statusCode',
	 *          'responsePhrase',
	 *          'responseHeaders',
	 *          'responseBody',
	 *          'data'
	 *      ]
	 */
	public function Response( $null = false ) {
		$response = $this->rel( 'response' );
		if ( is_null( $null ) ) {
			$this->rel( 'response', array() );
		}
		return $response;
	}

	/**
	 * Handle messages.
	 *
	 * Display message using transients.
	 */
	public function getMessages() {
		$response = $this->Response();
		// When messages are available.
		if ( isset( $response['messages'] ) ) {

			global $synergypress;
			$messages       = implode( ', ', $response['messages'] );
			$plugin_options = $synergypress->resources->Get( 'synergypress' );

			// If API message responses is enabled.
			if ( isset( $plugin_options->synergypress_api_debug )
				&& 'yes' == $plugin_options->synergypress_api_debug ) {
					set_transient( 'synergypress_api_response_messages', sanitize_text_field( $messages ) );
			}
		}
	}
}

/**
 * Class FsException extends Exception
 */
class FsException extends \Exception {}

/**
 * Small class to handle and control sessions.
 */
class Session {

	/**
	 * Must be present to initialize sessions
	 *
	 * Enable()
	 *
	 * @visibility public static
	 * @return void
	 */
	public static function Enable() {
		// The default date_default_timezone_set is set by WordPress.
		global $_SESSION;
		if ( ! isset( $_SESSION ) || session_status() === PHP_SESSION_NONE ) {
			session_start();
		}
	}

	/**
	 * Will set or replace the value of an item store in session.
	 *
	 * Set()
	 *
	 * @visibility public static
	 * @param string $key Session key.
	 * @param mixed  $value Session value.
	 */
	public static function Set( $key, $value ) {
		$_SESSION[ $key ] = $value;
	}

	/**
	 * Will retrieve the value stored in a session key.
	 *
	 * Get()
	 *
	 * @visibility public static
	 * @param string $key Session key.
	 * @return mixed
	 */
	public static function Get( $key ) {
		return isset( $_SESSION[ $key ] ) ? $_SESSION[ $key ] : false;
	}

	/**
	 * Will permanently delete an item from the session.
	 *
	 * Delete()
	 *
	 * @visibility public static
	 * @param mixed $key Session key.
	 * @return void
	 */
	public static function Delete( $key ) {
		if ( isset( $_SESSION[ $key ] ) ) {
			unset( $_SESSION[ $key ] );
		}
	}
}
