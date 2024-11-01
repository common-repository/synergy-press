<?php
/**
 * SynergyPress WordPress plugin.
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 **/

if ( ! defined( 'SYNERGY_PRESS' ) ) :
	return;
endif;
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
	<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:bottom;" viewBox="0 0 960 960" height="24px" width="24px" x="0px" y="0px"> <g> <g> <path opacity="0.7" fill="#010101" enable-background="new" d="M703.1,241.8c-60.3-56.5-138.6-87.5-221.7-87.4 c-149.5,0.1-278.6,102.6-314.2,244.9c-2.6,10.3-11.8,17.7-22.5,17.7H34.1c-14.5,0-25.5-13.2-22.8-27.3C53.1,167.8,247.8,0,481.7,0 c128.2,0,244.7,50.4,330.7,132.6l68.9-68.9c29.2-29.2,79.1-8.5,79.1,32.8v258.7c0,25.6-20.7,46.3-46.3,46.3 M260.2,715.6 C320.6,772,398.9,803,482,802.9c149.4-0.1,278.5-102.6,314.2-244.8c2.6-10.3,11.8-17.7,22.5-17.7h110.6 c14.5,0,25.4,13.1,22.8,27.4c-41.8,221.7-236.5,389.5-470.4,389.5c-128.3,0-244.7-50.4-330.7-132.5l-68.9,68.9 C52.9,922.9,3,902.3,3,860.9V602.2c0-25.6,20.7-46.3,46.3-46.3"/> </g> </g> <path fill="#ACD147" d="M544,382.2V273.1c0-17.2,14-31.2,31.2-31.2s31.2,14,31.2,31.2v109.1H544z M653.1,397.8H310.2 c-8.6,0-15.6,7-15.6,15.6v31.2c0,8.6,7,15.6,15.6,15.6h15.6v31.2c0,75.4,53.5,138.3,124.7,152.7v96.6h62.3V644 c71.1-14.4,124.7-77.3,124.7-152.7v-31.2h15.6c8.6,0,15.6-7,15.6-15.6v-31.2C668.7,404.8,661.7,397.8,653.1,397.8z M419.3,382.2 V273.1c0-17.2-14-31.2-31.2-31.2c-17.2,0-31.2,14-31.2,31.2v109.1H419.3z"/> </svg>
		<strong class="fs-logo">
			<span class="fs-logo-1">Synergy</span><span class="fs-logo-2">Press</span>
		</strong>
	</h1>

	<hr class="wp-header-end">
	<?php
		global $synergypress;

		$get_request = synergypress_get_request();
		$config      = $synergypress->resources->Get( 'config' );
		$website     = $synergypress->resources->Get( 'website' );
		$domain_fs   = isset( $website->domain ) ? $synergypress->validate->sanitize_domain( $website->domain ) : $synergypress->validate->sanitize_domain( get_home_url() );
		$site_name   = isset( $website->name ) ? $website->name : get_bloginfo( 'name' );
		$proto       = isset( $website->proto ) ? $website->proto : $synergypress->plugin->get_proto( get_home_url() );
		$strategy    = $synergypress->resources->Get( 'strategy' );
		$objectives  = $synergypress->resources->Get( 'objectives' );

		$notices = array(
			'api-config',
			'site-config',
			'packages',
		);

		$packages = array(
			'contact-form',
			'request-callback',
			'newsletter-subscription',
		);

		$objectives = array(
			'contact-us',
			'callback-request',
			'newsletter-subscription',
		);

		$get_requested_tab = isset( $get_request['tab'] )
							? sanitize_text_field( $get_request['tab'] )
							: null;
		$active_nav_tab    = array(
			'dashboard'         => ! isset( $get_requested_tab )
								|| 'dashboard' === $get_requested_tab
								? 'nav-tab-active'
								: '',

			'api-configuration' => isset( $get_requested_tab )
								&& 'api-configuration' === $get_requested_tab
								? 'nav-tab-active'
								: '',

			'site-config'       => isset( $get_requested_tab )
								&& 'site-config' === $get_requested_tab
								? 'nav-tab-active'
								: '',

			'plugin-options'    => isset( $get_requested_tab )
								&& 'plugin-options' === $get_requested_tab
								? 'nav-tab-active'
								: '',

			'packages'          => isset( $get_requested_tab )
								&& 'packages' === $get_requested_tab
								? 'nav-tab-active'
								: '',

			'localize'          => isset( $get_requested_tab )
								&& 'localize' === $get_requested_tab
								? 'nav-tab-active'
								: '',
		);

		// If this is a new installation load the interactive account access page.
		$load_interactive_account_access = get_transient( 'synergypress_load_dashboard' );
		if ( $load_interactive_account_access && ! isset( $get_request['iaa'] ) ) :
			include_once plugin_dir_path( __FILE__ ) . '/options/interactive-account-access.php';
		elseif ( isset( $get_request['iaa'] ) ) :
			switch ( sanitize_text_field( $get_request['iaa'] ) ) :

				case 'interactive-account-access':
					include_once plugin_dir_path( __FILE__ ) . '/options/interactive-account-access.php';
					break;

				case 'upload-settings':
					include_once plugin_dir_path( __FILE__ ) . '/options/upload-settings.php';
					break;

				case 'dashboard':
					delete_transient( 'synergypress_load_dashboard' );
					include_once plugin_dir_path( __FILE__ ) . '/options/main-panel.php';
					break;
			endswitch;
			else :
				include_once plugin_dir_path( __FILE__ ) . '/options/main-panel.php';
		endif;
			?>
</div>
