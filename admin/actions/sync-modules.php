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
 * Retrieves the total module count.
 *
 * @param object $strategy The strategy object.
 * @param object $config The configuration object.
 */
function retrieve_module_count( $strategy, $config ) {
	$api = FS::Api()->Load( $config->profileid );
	$api->Find( 'modules' )
		->Where(
			array(
				'modid' => $strategy->modid,
			)
		)
		->As( 'allModules' );

		$all_modules = $api->_allModules();
		return $all_modules
			? count( $all_modules )
			: 0;
}

/**
 * Count and compare module count.
 * If count is off, trigger sync.
 *
 * @param object $synergypress Synergy Press object.
 * @param number $count_from_source Total count of modules from the source.
 */
function compare_modules_count( $synergypress, $count_from_source = 0 ) {
	if ( $count_from_source > 0 ) {
		$modules = $synergypress->resources->Get( 'modules' );
		if ( count( $modules ) !== $count_from_source ) {
			\sync_modules( $synergypress );
		}
	}
}

/**
 * Synchronize all modules.
 *
 * @param object $synergypress The synergypress object.
 */
function sync_modules( $synergypress ) {
	if ( ! $synergypress->config ) {
		return;
	}

	$strategy = $synergypress->resources->Get( 'strategy' );
	if ( ! $strategy ) {
		return;
	}

	$config         = $synergypress->resources->Get( 'config' );
	$website        = $synergypress->resources->Get( 'website' );
	$advanced_mode  = $synergypress->resources->Get( 'advancedMode' );
	$api            = FS::Api()->Load( $synergypress->validate->sanitize_hash( $config->profileid ) );
	$stored_modules = false;
	$stored_modules = ! $advanced_mode
						|| 'yes' !== $advanced_mode->status
						? array(
							array( 'name' => 'contactForm' ),
							array( 'name' => 'requestCallback' ),
							array( 'name' => 'newsLetterSubscription' ),
						)
						: $synergypress->resources->Get( 'modules', true );

	$api->Find( 'modules' )
		->Where(
			array(
				'modid' => $strategy->modid,
			)
		)
		->As( 'modules' );

	/**
	 * Store modules names.
	 */
	$new_modules = array();
	if ( $api->_modules() ) {
		foreach ( $api->_modules() as $module ) {
			$new_modules[] = $synergypress->validate->sanitize_text_field( $synergypress->plugin->camel_case( $module['name'] ) );

			/**
			 * Create a list for all imported modules.
			 * Sanitize each.
			 */
			$modules[ $synergypress->validate->sanitize_hash( $module['moduleid'] ) ] = array(
				'name'     => $synergypress->validate->sanitize_text_field( $synergypress->plugin->camel_case( $module['name'] ) ),
				'moduleid' => $synergypress->validate->sanitize_hash( $module['moduleid'] ),
			);

			/**
			 * Check if module is already stored, to prevent parameters and display options
			 * from being overwritten simply pass them along.
			 */
			$load_module = $synergypress->resources->Get( $synergypress->validate->sanitize_text_field( $synergypress->plugin->camel_case( $module['name'] ) ), true );
			if ( $load_module ) :
				$module['options'] = $load_module['options'];
				$module['params']  = $load_module['params'];
			endif;

			// Check if module already exists and retrieve autoload settings.
			if ( $load_module && ! isset( $load_module['autoload'] ) ) :
				$module['autoload'] = $load_module['autoload'];
			else :
				$module['autoload'] = array(
					'options' => 'yes',
					'params'  => 'yes',
				);
			endif;

			// Store module.
			$synergypress->resources->Update(
				$synergypress->validate->sanitize_text_field(
					$synergypress->plugin->camel_case( $module['name'] )
				)
			)
			->Data(
				$synergypress->validate->sanitize_modules( $module, 'advanced' )
			);
		}
	}

    if ( $stored_modules ) {
        foreach ($stored_modules as $stored_module) {
            if (! in_array($stored_module['name'], $new_modules)) {
                $synergypress->resources->Delete($stored_module['name']);
            }
        }
    }

	// Delete modules array.
	$synergypress->resources->Delete( 'modules' );
	// Update modules array with new information.
	$synergypress->resources->Update( 'modules' )
		->Data( $modules );
}
