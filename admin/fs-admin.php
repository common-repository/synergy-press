<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

// Make sure we don't expose any info if called directly.
if ( ! defined( 'SYNERGY_PRESS' ) ) {
	return;
}

/**
 * If user is an administrator
 */
if ( ! current_user_can( 'administrator' ) ) {
	return;
}

/**
 * Enable session storage, required by the API
 * for automated authentication
 */
\FormSynergy\Session::enable();

/**
 * Import the Form Synergy API
 */
use \FormSynergy\Fs as FS;

/**
 * Storage configuration, using wp_options API
 */
FS::useStorage( 'Option_Storage' );

/**
 * Load Synergy admin
 */
function synergypress_load() {
	global $synergypress;
	$synergypress->action  = false;
	$synergypress->request = false;
	$synergypress->config  = false;

	if ( isset( $_POST['synergy-press-update-action'], $_GET['_wpnonce'] ) ) :
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'form_synergy_update_options' ) ) :
			die( 'Failed Security Check' );
		endif;

		/**
		 * NOTE: The sanitization process undergoes validation as well.
		 */
		$action                = sanitize_text_field( wp_unslash( $_POST['synergy-press-update-action'] ) );
		$synergypress->action  = $action;
		$synergypress->request = isset( $_POST[ $action ] )
							? wp_unslash( $synergypress->validate->sanitize_request( $_POST[ $action ] ) )
							: false;
	endif;
	if ( isset( $_GET['localize'] ) ) :
		$synergypress->action  = 'localize';
		$synergypress->request = array(
			'localization' => wp_unslash( $synergypress->validate->sanitize_localize( $_GET['localize'] ) ),
		);
	endif;
	return $synergypress;
}

/**
 * Verify wpnonce and retrieve $_GET request.
 *
 * @param mixed $all dd.
 */
function synergypress_get_request( $all = 'yes' ) {
	global $synergypress;
	$_GET = $synergypress->validate->unamp_array( $_GET );
	if ( isset( $_GET['page'] )
		&& 'synergy-press' === wp_unslash( sanitize_text_field( $_GET['page'] ) )
		&& 1 === count( $_GET ) ) :

		return 'yes' !== $all
			&& isset( $_GET[ $all ] )
			? wp_unslash( sanitize_text_field( $_GET[ $all ] ) )
			: array_map( 'sanitize_text_field', wp_unslash( $_GET ) );
	endif;
	if ( ! empty( $_POST ) && ! isset( $_GET['_wpnonce'] ) ) :
		die( 'Failed Security Not Implemented' );
	endif;

	if ( ! empty( $_POST )
		&& ! wp_verify_nonce( wp_unslash( sanitize_text_field( $_GET['_wpnonce'] ) ), 'form_synergy_update_options' ) ) :
		die( 'Failed Security Check' );
	endif;
	return 'yes' !== $all
		&& isset( $_GET[ $all ] )
		? wp_unslash( sanitize_text_field( $_GET[ $all ] ) )
		: array_map( 'sanitize_text_field', wp_unslash( $_GET ) );
}

/**
 * Form Synergy hook: synergypress_api
 */
function synergypress_api() {
	do_action( 'synergypress_api' );
}

/**
 * Configuration notice
 */
function synergypress_configuration_notice() {
	if ( get_transient( 'synergypress_config_incomplete' ) ) :
		echo '<div class="notice notice-error">The Synergy Press plugin configuration is incomplete. Please complete the required fields.</div>';
		delete_transient( 'synergypress_config_incomplete' );
	endif;
}

/**
 * Required API configuration.
 */
function synergypress_api_config() {
	global $synergypress;
	$config = $synergypress->resources->Get( 'config' );
	if ( ! isset( $config->apikey, $config->secretkey, $config->profileid ) ) :
		return;
	endif;
	FS::Config(
		array(
			'version'        => 'v1',
			'protocol'       => 'https',
			'endpoint'       => 'api.formsynergy.com',
			'apikey'         => $config->apikey,
			'secretkey'      => $config->secretkey,
			'max_auth_count' => 15,
		)
	);
	$synergypress->validate->set_profileid( $config->profileid );

	$synergypress->config = true;
}

/**
 * Plugin options page
 */
function synergypress_plugin_options() {
	include_once plugin_dir_path( __FILE__ ) . '/fs-options.php';
}

/**
 * Plugin custom code page
 */
function synergypress_plugin_custom_code() {
	include_once plugin_dir_path( __FILE__ ) . '/fs-custom-code.php';
}

/**
 * Create admin menu
 */
function synergypress_admin_menu() {
	add_action(
		'admin_menu',
		function() {
			add_menu_page(
				'Synergy Press',
				'Synergy Press',
				'manage_options',
				'synergy-press',
				'synergypress_plugin_options',
				plugin_dir_url( __FILE__ ) . '/img/fs-press-16x16.png', // <- Add icon to admin menu
				99
			);
		}
	);
}

/**
 * Create sub menu for custom code.
 */
function synergypress_admin_sub_menu() {
	global $synergypress;
	$advanced_mode = $synergypress->resources->Get( 'advancedMode' );
	if ( isset( $advanced_mode->status ) && 'yes' === $advanced_mode->status ) :
		add_submenu_page(
			'synergy-press',
			'Custom Code',
			'Custom Code',
			'manage_options',
			'synergypress-custom-code',
			'synergypress_plugin_custom_code'
		);
	endif;
}

/**
 * Update options.
 */
function synergypress_update_options_request() {
	global $synergypress;
	if ( ! $synergypress->action ) :
		return;
	endif;

	switch ( $synergypress->action ) :
		case 'config':
			$synergypress->resources->Update( 'config' )
				->Data(
					$synergypress->validate->sanitize_api_config( $synergypress->request )
				);
			break;

		case 'website':
			synergypress_api_config();
			include_once plugin_dir_path( __FILE__ ) . 'actions/site-config.php';

			if( isset( $synergypress->request['offset'] ) 
				|| isset( $synergypress->request['heartbeat'] ) ) :
				return \SynergyPress\update_site_options( $synergypress );
			endif;
			
			return \SynergyPress\update_site( $synergypress );
			break;

		case 'advancedMode':
			$synergypress->resources->Update( 'advancedMode' )
				->Data(
					$synergypress->validate->sanitize_advanced_mode( $synergypress->request )
				);
			break;

		case 'strategy':
			synergypress_api_config();
			include_once plugin_dir_path( __FILE__ ) . 'actions/strategy.php';
			include_once plugin_dir_path( __FILE__ ) . 'actions/objectives.php';
			\SynergyPress\update_strategy( $synergypress );
			\SynergyPress\sync_modules( $synergypress );
			$advanced_mode = $synergypress->resources->Get( 'advancedMode' );
			if ( ! isset( $advanced_mode->status ) || 'yes' !== $advanced_mode->status ) :
				\SynergyPress\sync_objectives( $synergypress );
			endif;
			return;
			break;

		case 'modules':
			synergypress_api_config();
			include_once plugin_dir_path( __FILE__ ) . 'actions/modules.php';
			return \SynergyPress\update_module( $synergypress );
			break;

		case 'objectives':
			synergypress_api_config();
			include_once plugin_dir_path( __FILE__ ) . 'actions/objectives.php';
			\SynergyPress\update_objective( $synergypress );
			$advanced_mode = $synergypress->resources->Get( 'advancedMode' );
			if ( ! isset( $advanced_mode->status ) || 'yes' !== $advanced_mode->status ) :
				return \SynergyPress\sync_objectives( $synergypress );
			endif;
			break;

		case 'localize':
			include_once plugin_dir_path( __FILE__ ) . 'actions/localize.php';
			if ( isset( $synergypress->request['debug'] ) ) :
				return \SynergyPress\debug_localization( $synergypress );
			endif;
			synergypress_api_config();
			return \SynergyPress\localize( $synergypress );
			break;

		case 'synergypress':
			include_once plugin_dir_path( __FILE__ ) . 'actions/plugin-options.php';
			return \SynergyPress\update_options( $synergypress );
			break;

		case 'upload':
			include_once plugin_dir_path( __FILE__ ) . 'actions/import-settings.php';
			return \SynergyPress\import_settings( $synergypress );
			break;

		case 'customCode':
			include_once plugin_dir_path( __FILE__ ) . 'actions/update-custom-code.php';
			\SynergyPress\update_custom_code( $synergypress );
			break;
	endswitch;
}
add_action( 'synergypress_api', 'synergypress_admin_menu' );
add_action( 'synergypress_api', 'synergypress_load' );
add_action( 'synergypress_api', 'synergypress_update_options_request' );
synergypress_api();
add_action( 'admin_menu', 'synergypress_admin_sub_menu' );

/**
 * Add interaction modules to Gutenberg.
 */
function synergypress_add_interactions_to_gutenberg() {

	wp_register_script(
		'synergy-press-block-js',
		plugins_url( '/admin/js/blocks.js', __DIR__ ),
		array(
			'wp-blocks',
			'wp-plugins',
			'wp-edit-post',
			'wp-element',
			'wp-editor',
			'wp-data',
			'wp-i18n',
		),
		'1.6.0'
	);

	register_block_type(
		'synergy-press/intertactions',
		array(
			'editor_script' => 'synergy-press-block-js',
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'synergypress_add_interactions_to_gutenberg' );

/**
 * Register admin script
 */
function synergypress_register_admin_scripts() {
	/**
	 * Enqueue Synergy Press Admin script
	 */
	wp_enqueue_script(
		'fs-admin',
		plugin_dir_url( __FILE__ ) . 'js/synergypress-admin.js',
		array(),
		'fs-admin.js',
		true
	);
	/**
	 * Enqueue Google code-prettify
	 *
	 * @see https://github.com/google/code-prettify
	 */
	wp_enqueue_script(
		'code-prettify',
		plugin_dir_url( __FILE__ ) . 'js/google-prettify.js',
		array(),
		'code-prettify.js',
		true
	);

	/**
	 * Enqueue styles
	 */
	wp_enqueue_style(
		'synergy-press-styles',
		plugin_dir_url( __FILE__ ) . 'css/synergypress-admin.css',
		array(),
		'1.0',
		'all'
	);
}
add_action( 'admin_enqueue_scripts', 'synergypress_register_admin_scripts' );
synergypress_api();

/**
 * Load codemirror, avoid plugin interference.
 *
 * @param string $hook Current page.
 */
function synergypress_codemirror_enqueue_scripts( $hook ) {

	if ( 'toplevel_page_synergy-press' === $hook ) :
		$type = 'application/json';
	elseif ( 'synergy-press_page_synergypress-custom-code' === $hook ) :
		$type = 'text/javascript';
	else :
		return;
	endif;

	$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => $type ) );
	wp_localize_script( 'jquery', 'cm_settings', $cm_settings );
	wp_enqueue_script( 'wp-theme-plugin-editor' );
	wp_enqueue_style( 'wp-codemirror' );
}
add_action( 'admin_enqueue_scripts', 'synergypress_codemirror_enqueue_scripts' );

/**
 * When allocated resources are depleted.
 *
 * Will create a transient to display an notice
 * to the administrator.
 */
function synergypress_api_resources_notice() {
	global $synergypress;
	$response_messages = get_transient( 'synergypress_api_notice' );
	if ( $response_messages ) :
		?>
	<div class="notice notice-error is-dismissible">
		<p>
			Synergy Press: <?php echo $synergypress->validate->sanitize_text_field( $response_messages ); ?>
			<a 
				href="<?php echo esc_url( 'https://formsynergy.com/console/products/' ); ?>" 
				target="_blank" 
				title="Open the Form Synergy Console in a new tab">
					Manage Resources
				</a>
			</p>
	</div>
		<?php
		delete_transient( 'synergypress_api_notice' );
	endif;
}
add_action( 'admin_notices', 'synergypress_api_resources_notice' );

/**
 * If Form Synergy debug is turned on.
 *
 * Will display API response message.
 */
function synergypress_api_response_messages() {
	global $synergypress;
	$response_messages = get_transient( 'synergypress_api_response_messages' );
	if ( $response_messages ) :
		?>
	<div class="notice notice-synergypress is-dismissible">
		<p>
			Synergy Press: <?php echo $synergypress->validate->sanitize_text_field( $response_messages ); ?>
		</p>
		<p>
			<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&iaa=dashboard&tab=plugin-options&card=synergypress-api-debug', 'form_synergy_update_options', '_wpnonce' ) ); ?>">Disable</a>
		</p>
	</div>
		<?php
		delete_transient( 'synergypress_api_response_messages' );
	endif;
}
add_action( 'admin_notices', 'synergypress_api_response_messages' );

add_action(
	'plugins_loaded',
	function() {
		add_action( 'admin_notices', 'synergypress_configuration_notice' );
		load_plugin_textdomain( 'synergy-press' );
	}
);



