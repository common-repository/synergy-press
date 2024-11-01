<?php
/**
 * Created on Tue April 30 2019
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

/*
Plugin Name:       Synergy Press
Plugin URI:        https://formsynergy.com/synergypress-wordpress-plugin/
Version:           1.6.0.5
Author:            Joseph G. Chamoun ( FormSynergy.com )
Author URI:        https://formsynergy.com/
Text Domain:       synergy-press
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Description:       Build and package your forms in a real time engagement strategy and maximize on every possible opportunity.
 */

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2017 FormSynergy.com
 **/

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SYNERGY_PRESS', '1.6.0.5' );

/**
 * Show a notice to anyone who has just installed the plugin for the first time.
 * This notice shouldn't display to anyone who has just updated this plugin.
 */
function synergypress_display_activation_notice() {
	if ( get_transient( 'synergypress_display_activation_notice' ) ) {
		echo '<div class="notice notice-success"><p>Thank you for installing the <strong>Synergy Press plugin</strong></p></div>';
		delete_transient( 'synergypress_display_activation_notice' );
	}
}
add_action( 'admin_notices', 'synergypress_display_activation_notice' );

/**
 * Show a notice to anyone who has just updated this plugin.
 * This notice shouldn't display to anyone who has just installed the plugin for the first time.
 */
function synergypress_display_update_notice() {
	if ( get_transient( 'synergypress_display_update_notice' ) ) {
		echo '<div class="notice notice-success"> <p>Synergy Press plugin has been <strong>Updated</strong></p></div>';
		delete_transient( 'synergypress_display_update_notice' );
	}
}
add_action( 'admin_notices', 'synergypress_display_update_notice' );

/**
 * Include required classes.
 */
function synergypress_load_plugin() {

	global $synergypress;
	$synergypress = (object) array();

	include_once plugin_dir_path( __FILE__ ) . 'admin/vendor/autoload.php';
	include_once plugin_dir_path( __FILE__ ) . 'classes/class-fs-api.php';
	include_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-plugin.php';
	include_once plugin_dir_path( __FILE__ ) . 'classes/class-option-storage.php';
	include_once plugin_dir_path( __FILE__ ) . 'admin/actions/deactivate-plugin.php';
	include_once plugin_dir_path( __FILE__ ) . 'classes/class-validate.php';

	/**
	 * Instantiate the storage class.
	 */
	$synergypress->resources = new \FormSynergy\Option_Storage( 'fs-wp' );

	/**
	 * Instantiate the validation class.
	 */
	$synergypress->validate = new \SynergyPress\Validate();

	/**
	 * Instantiate the plugin class.
	 */
	$synergypress->plugin = new \SynergyPress\WP_Plugin();

	/**
	 * Include plugin admin classes & initiation scripts.
	 */
	if ( is_admin() ) {
		include_once plugin_dir_path( __FILE__ ) . 'admin/actions/sync-modules.php';
		include_once plugin_dir_path( __FILE__ ) . 'admin/fs-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'admin/options/menu-editor.php';
	}

	/**
	 * Include plugin admin classes & initiation scripts.
	 */
	if ( wp_doing_cron() ) {
		include_once plugin_dir_path( __FILE__ ) . 'admin/actions/sync-modules.php';
		include_once plugin_dir_path( __FILE__ ) . 'admin/fs-admin.php';

		/**
		 * Schedule cron intervals to check if
		 * modules require synchronization.
		 */
		add_action( 'synergypress_cron_hook', 'synergypress_sync_interval' );

		/**
		 * Cron hook to run hourly.
		 */
		function synergypress_sync_interval() {
			global $synergypress;
			synergypress_api_config();
			\SynergyPress\sync_modules( $synergypress );
		}
		/**
		 * Reschedule sync.
		 */
		if ( ! wp_next_scheduled( 'synergypress_cron_hook' ) ) {
			wp_schedule_event( time(), 'hourly', 'synergypress_cron_hook' );
		}
	}

	/**
	 * Include public files & classes.
	 */
	if ( ! is_admin() ) {
		include_once plugin_dir_path( __FILE__ ) . 'public/fs-public.php';
	}

	/**
	 * Include Gutenberg file.
	 */
	include_once plugin_dir_path( __FILE__ ) . 'admin/fs-gutenberg.php';
}
add_action( 'wp_loaded', 'synergypress_load_plugin' );

/**
 * Add link to Synergy press plugin settings page once the plugin is active.
 *
 * @param array $links Add quick access to settings link in plugin management panel.
 * @return array $links
 */
function synergypress_add_setting_link( $links ) {
	$links = array_merge(
		$links,
		array(
			'<a href="' . esc_url( admin_url( wp_nonce_url( '?page=synergy-press', 'form_synergy_update_options', '_wpnonce' ) ) ) . '">' . __( 'Settings', 'synergy-press' ) . '</a>',
		)
	);
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'synergypress_add_setting_link' );

/**
 * Activate plugin
 */
function synergypress_activate_plugin() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
	check_admin_referer( "activate-plugin_{$plugin}" );
	set_transient( 'synergypress_display_activation_notice', 1 );
	set_transient( 'synergypress_load_dashboard', 'interactive-account-access' );
	flush_rewrite_rules();
}

/**
 * Run this on activation set a transient so that we know we've just activated the plugin.
 *
 * @param object $upgrader_object Upgrader object.
 * @param array  $options Upgrader options.
 */
function synergypress_update_plugin( $upgrader_object, $options ) {
	// The path to our plugin's main file.
	$our_plugin = plugin_basename( __FILE__ );
	// If an update has taken place and the updated type is plugins and the plugins element exists.
	if ( 'update' === $options['action'] && 'plugin' === $options['type'] && isset( $options['plugins'] ) ) {
		// Iterate through the plugins being updated and check if ours is there.
		foreach ( $options['plugins'] as $plugin ) {
			if ( $plugin === $our_plugin ) {
				// Set a transient to record that our plugin has just been updated.
				set_transient( 'synergypress_display_update_notice', 1 );
			}
		}
	}
}

/**
 * Deactivate plugin
 */
function synergypress_deactivate_plugin() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$plugin = isset( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';
	check_admin_referer( "deactivate-plugin_{$plugin}" );
	delete_transient( 'synergypress_load_dashboard' );

	// Delete any resource used by this plugin.
	global $synergypress;
	synergypress_api_config();
	\SynergyPress\deactivate_plugin( $synergypress );

	// Unregister corn registered by ths plugin.
	$timestamp = wp_next_scheduled( 'synergypress_cron_hook' );
	wp_unschedule_event( $timestamp, 'synergypress_cron_hook' );

	// Flush rewrite rules.
	flush_rewrite_rules();
}

/**
 * Register Hooks when plugins have loaded
 */
register_activation_hook( __FILE__, 'synergypress_activate_plugin' );
register_deactivation_hook( __FILE__, 'synergypress_deactivate_plugin' );
add_action( 'upgrader_process_complete', 'synergypress_update_plugin', 10, 2 );
