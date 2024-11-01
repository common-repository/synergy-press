<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.6.0
 * @package synergy-press
 **/

// Make sure we don't expose any info if called directly.
if ( ! defined( 'SYNERGY_PRESS' ) ) {
	return;
}

/**
 * Keep track of newly added triggers.
 *
 * @param int $post_id The post id.
 */
function synergypress_update_nav_items( $post_id ) {
	global $synergypress;

	// Retrieve the post.
	$post = get_post( $post_id );

	if ( empty( $post->post_title ) ) :
		return;
	endif;

	// If nave menu item proceed.
	if ( 'nav_menu_item' === $post->post_type ) :
		$post_name     = sanitize_title( $post->post_title );
		$allowed_items = array();
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

		// Create an array and store allowed modules.
		foreach ( $modules as $module ) :
			$item = $synergypress->resources->Get(
				$synergypress->plugin->camel_case(
					is_object( $module ) ? $module->name : $module
				)
			);

			if ( isset( $item->name ) ) :
				$allowed_items[] = sanitize_title( $item->name );
			endif;
		endforeach;

		// If the menu item is a trigger.
		if ( in_array( $post_name, $allowed_items ) ) :
			// Retrieve trigger tracker.
			$trigger_tracker = $synergypress->resources->Get( 'trigger_tracker', true );
			if ( $trigger_tracker ) :
				// Add trigger to tracker.
				$trigger_tracker[ $post_name ] = true;
				// Update.
				$synergypress->resources->Update( 'trigger_tracker' )
					->Data( $trigger_tracker );
			else :
				// Create a new tracker.
				$synergypress->resources->Store( 'trigger_tracker' )
					->Data(
						array(
							$post_name => true,
						)
					);
			endif;
		endif;
	endif;
}
add_action( 'save_post', 'synergypress_update_nav_items' );

/**
 * Keep track of removed triggers.
 *
 * @param int $post_id The post id.
 */
function synergypress_remove_nav_items( $post_id ) {
	global $synergypress;

	// Retrieve the post.
	$item = get_post( $post_id );

	if ( 'nav_menu_item' === $item->post_type ) :

		// Retrieve trigger tracker.
		$trigger_tracker = $synergypress->resources->Get( 'trigger_tracker', true );

		// Sanitize the post title.
		$post_name = sanitize_title( $item->post_title );
		
		// If the trigger exists, remove it.
		if ( $trigger_tracker && array_key_exists( $post_name, $trigger_tracker ) ) :
			$trigger_tracker[ $post_name ] = false;
			//unset( $trigger_tracker[ $post_name ] );
		endif;
		
		// If the tracker ends up empty, remove the storage.
		if ( ! empty( $trigger_tracker ) ) :
			// Update tracker.
			$synergypress->resources->Update( 'trigger_tracker' )
				->Data( $trigger_tracker );
		else :
			$synergypress->resources->Delete( 'trigger_tracker' );
		endif;
	endif;
}
add_action( 'delete_post', 'synergypress_remove_nav_items' );

/**
 * Add menu meta box
 *
 * @param object $object The meta box object.
 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function synergypress_add_menu_meta_box( $object ) {
	add_meta_box( 'synergypress-menu-metabox', __( 'Interaction Triggers' ), 'synergypress_menu_meta_box', 'nav-menus', 'side', 'default' );
	return $object;
}
add_filter( 'nav_menu_meta_box_object', 'synergypress_add_menu_meta_box', 10, 1 );

/**
 * Add Synergy Press metabox.
 */
function synergypress_menu_meta_box() {
	global $nav_menu_selected_id, $synergypress;

	$walker      = new Walker_Nav_Menu_Checklist();
	$current_tab = 'all';
	/* set values to required item properties */
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

	$items = array();

	$trigger_tracker = $synergypress->resources->Get( 'trigger_tracker', true );

	// Filter trigger_tracker
	$trigger_tracker = array_filter( $trigger_tracker );

	$modules_count = count( $modules );
	foreach ( $modules as $module ) :
		$item = $synergypress->resources->Get(
			$synergypress->plugin->camel_case(
				is_object( $module ) ? $module->name : $module
			)
		);

		if ( $item 
			&& isset( $item->name ) 
			&& ! $trigger_tracker 
			|| ! array_key_exists( sanitize_title( $item->name ), $trigger_tracker ) ) :
			$item->classes          = array(
				'fs-trigger-' . $item->moduleid,
			);
			$item->type_label       = 'Interaction';
			$item->type             = 'interaction';
			$item->object_id        = $item->moduleid;
			$item->title            = $item->name;
			$item->object           = 'interaction';
			$item->url              = '';
			$item->xfn              = '';
			$item->target           = '';
			$item->db_id            = '';
			$item->menu_item_parent = '';
			$item->attr_title       = $item->name;
			$items[]                = $item;
		endif;
	endforeach;

	$removed_args = array(
		'action',
		'customlink-tab',
		'menu-item',
		'page-tab',
		'_wpnonce',
	);
	?>
	<div id="interactionsarchive" class="categorydiv">
		 
		<div id="tabs-panel-interactionsarchive-all" class="tabs-panel tabs-panel-view-all <?php echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ); ?>">
		<?php if ( ! empty( $items ) ) : ?>
			<ul id="interactionsarchive-checklist-all" class="categorychecklist form-no-clear">
			<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $items ), 0, (object) array( 'walker' => $walker ) ); ?>
			</ul>
			<?php else : ?>
				<p>You have a total of <?php echo $synergypress->validate->sanitize_number( $modules_count ); ?> module(s) already assigned.</p>
			<?php endif; ?>
		</div>
		<p class="button-controls wp-clearfix">
			<span class="list-controls">
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'interactionsarchive-tab' => 'all',
							'selectall'               => 1,
						),
						remove_query_arg( $removed_args )
					)
				);
				?>
				#interactionsarchive" class="select-all">Select All</a>
			</span>
			<span class="add-to-menu">
				<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu' ); ?>" name="add-interactionsarchive-menu-item" id="submit-interactionsarchive" />
				<span class="spinner"></span>
			</span>
		</p>
	</div> 
	<?php
}
