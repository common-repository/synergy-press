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
if ( ! defined( 'SYNERGY_PRESS' ) ) :
	return;
endif;
?>
<div id="synergy-press">
	<h2 class="nav-tab-wrapper">
		<?php
			$get_request   = synergypress_get_request();
			$advanced_mode = $synergypress->resources->Get( 'advancedMode' );
		if ( isset( $get_request['initiator'] ) && 'interactive-account-access' === sanitize_text_field( $get_request['initiator'] ) ) :
			?>
				<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&iaa=interactive-account-access', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="nav-tab"> 
					<span class="dashicons dashicons-arrow-left-alt2" style="vertical-align:sub;" ></span> Back 
				</a>

		<?php endif; ?>   
		<a 
			href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=dashboard', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
			class="nav-tab <?php echo esc_attr( $active_nav_tab['dashboard'] ); ?>">
				Dashboard 
		</a>
		<a 
			href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=api-configuration', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
			class="nav-tab <?php echo esc_attr( $active_nav_tab['api-configuration'] ); ?>">
				API Configuration 
		</a>
		<a 
			href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
			class="nav-tab <?php echo esc_attr( $active_nav_tab['site-config'] ); ?>">
				Site Configuration
		</a>
		<a 
			href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
			class="nav-tab <?php echo esc_attr( $active_nav_tab['plugin-options'] ); ?>">
				Plugin Options
		</a>
		<a 
			href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
			class="nav-tab <?php echo esc_attr( $active_nav_tab['packages'] ); ?>">
				<?php
				if ( $advanced_mode
						&& isset( $advanced_mode->status )
						&& 'yes' === $advanced_mode->status ) :
					?>
					Interactions
				<?php else : ?>
					Packages
				<?php endif; ?>
		</a>
		<a 
			href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=localize', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
			class="nav-tab <?php echo esc_attr( $active_nav_tab['localize'] ); ?>">
				Localize
		</a>
	</h2>
	<div class="tab-container">
		<?php
		if ( isset( $get_request['tab'] ) ) :
			$get_request_tab = sanitize_text_field( $get_request['tab'] );
			else :
				$get_request_tab = null;
			endif;

			switch ( true ) {
				case ! isset( $get_request_tab ) || 'dashboard' === $get_request_tab:
					include_once plugin_dir_path( __FILE__ ) . '/dashboard.php';
					break;

				case ! isset( $get_request_tab ) || 'api-configuration' === $get_request_tab:
					include_once plugin_dir_path( __FILE__ ) . '/api-config.php';
					break;

				case isset( $get_request_tab ) && 'site-config' === $get_request_tab:
					include_once plugin_dir_path( __FILE__ ) . '/site-config.php';
					break;

				case isset( $get_request_tab ) && 'plugin-options' === $get_request_tab:
						include_once plugin_dir_path( __FILE__ ) . '/plugin-options.php';
					break;

				case isset( $get_request_tab ) && 'packages' === $get_request_tab:
					include_once plugin_dir_path( __FILE__ ) . '/packages.php';
					break;

				case isset( $get_request_tab ) && 'localize' === $get_request_tab:
					include_once plugin_dir_path( __FILE__ ) . '/localize.php';
					break;
			}
			?>
	</div>
</div>
