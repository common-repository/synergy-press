<?php
/**
 * Created on Tue February 4 2020
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.6.0
 * @package Synergy-press
 */

/**
 * Register meta fields.
 **/
function synergypress_register_meta_fields() {

	// Retrieve all post types.
	$post_types = get_post_types();

	// Exclude the following post types.
	$exclude = array(
		'attachment',
		'revision',
		'nav_menu_item',
	);

	foreach ( $post_types as $post_type ) :
		if ( ! in_array( $post_type, $exclude ) ) :
			register_meta(
				$post_type,
				'synergypress',
				array(
					'type'         => 'array',
					'single'       => true,
					'show_in_rest' => true,
				)
			);
		endif;
		endforeach;
}
add_action( 'init', 'synergypress_register_meta_fields' );

/**
 * Register meta fields.
 **/
function synergypress_register_rets_fields() {

	// Retrieve all post types.
	$post_types = get_post_types();

	// Exclude the following post types.
	$exclude = array(
		'attachment',
		'revision',
		'nav_menu_item',
	);

	foreach ( $post_types as $post_type ) :
		if ( ! in_array( $post_type, $exclude ) ) :

			register_rest_field(
				$post_type,
				'synergypress',
				array(
					'get_callback' => 'synergypress_rest_field_callback',
					'schema'       => null,
				)
			);
		endif;
		endforeach;
}
add_action( 'rest_api_init', 'synergypress_register_rets_fields' );

/**
 * Callback for registered api field.
 *
 * @param array $request Request initiated by the block editor.
 * @return array.
 */
function synergypress_rest_field_callback( $request ) {
	global $synergypress;
	// Parse blocks.
	$parsed_blocks = parse_blocks( $request['content']['raw'] );
	$class_blocks  = array();

	foreach ( $parsed_blocks as $index => $block ) :
		// Check if the block attributes contains a trigger.
		if ( isset( $block['attrs']['trigger'] ) ) :
			$class_blocks[ $synergypress->validate->sanitize_hash( $block['attrs']['trigger'] ) ] = true;
		endif;
	endforeach;

	// Combine extracted triggers with other triggers.
	return synergypress_track_triggers( $class_blocks );
}

/**
 * Will combine known triggers used in a page or post.
 *
 * @param array $class_blocks Trigger classes used in post blocks.
 * @return array.
 */
function synergypress_track_triggers( $class_blocks = array() ) {
	global $synergypress;
	$advanced_mode = $synergypress->resources->Get( 'advancedMode' );
	$modules       = array();
	$modules       = ! $advanced_mode
		|| 'yes' !== $advanced_mode->status
		? array(
			'contactForm',
			'requestCallback',
			'newsLetterSubscription',
		)
		: $synergypress->resources->Get( 'modules' );

	$prepare_module_triggers   = array();
	$prepare_module_placements = array();
	$prepare_module_events     = array();
	$existing_triggers         = array();
	$prepare_reference_classes = array();

	if ( ! empty( $modules ) ) :

		/**
		 * Having multiple triggers to one interaction
		 * is not good, and generats javaScript errors!
		 */
		$trigger_tracker = $synergypress->resources->Get( 'trigger_tracker', true );

		// Combine existing triggers.
		if ( $trigger_tracker ) :

			// Existing triggers in menu.
			if ( isset( $trigger_tracker )
				&& ! empty( $trigger_tracker ) ) :
				foreach ( array_keys( $trigger_tracker ) as $trigger_item ) :
					$existing_triggers[] = $trigger_item;
				endforeach;
			endif;
		endif;

		$prepare_module_triggers[] = array(
			'label' => 'Select an interaction',
			'value' => 'fs-trigger-none',
		);

		$prepare_module_placements[] = array(
			'label' => 'Select an interaction',
			'value' => 'fs-placement-none',
		);

		foreach ( $modules as $module ) :

			$item = $synergypress->resources->Get(
				$synergypress->plugin->camel_case(
					is_object( $module ) ? $synergypress->validate->sanitize_text_field( $module->name ) : $synergypress->validate->sanitize_text_field( $module )
				),
				true
			);

			if ( $item ) :
				$trigger_class = 'fs-trigger-' . $synergypress->validate->sanitize_hash( $item['moduleid'] );
				$module_name   = $synergypress->validate->sanitize_text_field( $item['name'] );

				// If the module classname exists in $class_blocks, add to existing triggers.
				if ( ! empty( $class_blocks ) && array_key_exists( $trigger_class, $class_blocks ) ) {
					$existing_triggers[] = $synergypress->validate->sanitize_hash( $module_name );
				}

				$prepare_module_events[ $trigger_class ]     = $synergypress->plugin->get_event_trigger( $item );
				$prepare_module_triggers[]                   = array(
					'label'    => $module_name,
					'value'    => $trigger_class,

					// If the module name exists int the $existing_triggers, disable this option.
					'disabled' => in_array( $synergypress->validate->sanitize_hash( $item['name'] ), $existing_triggers )
								? true
								: false,
				);
				$prepare_reference_classes[ $trigger_class ] = $module_name;
				$prepare_module_placements[]                 = array(
					'label' => $module_name,
					'value' => 'fs-placement-' . $synergypress->validate->sanitize_hash( $item['moduleid'] ),
				);
			endif;

		endforeach;
	endif;

	// Return options.
	return array(
		'triggers'   => $prepare_module_triggers,
		'reference'  => $prepare_reference_classes,
		'placements' => $prepare_module_placements,
		'events'     => $prepare_module_events,
		'advancedmode' => $advanced_mode,
	);
}
