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

/**
 * Notice for: API Configuration.
 */
$toggle_status = null;
if ( isset( $config->profileid, $config->apikey, $config->secretkey ) ) :
	$toggle_status = 'close';
endif;
?>
<div id="fs-notification-api-config" class="fs-card">
	<div class="fs-sub-headings">
		<button type="button" class="fs-toggler" aria-expanded="<?php echo is_null( $toggle_status ) ? 'true' : 'false'; ?>">
			<span class="screen-reader-text edit">Read more: API Configuration Status</span>
			<span class="dashicons dashicons-arrow-down"></span>
		</button>
		<h4>  
			<?php if ( ! is_null( $toggle_status ) ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
			API Configuration</h4>
	</div>
	<div class="fs-toggle fs-body h-10 <?php echo esc_attr( $toggle_status ); ?>">
		<div class="fs-content">
			<ul class="fs-list w-50">
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $config->profileid ) ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">Profile ID: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php
							echo ( isset( $config->profileid )
								? esc_attr( $config->profileid )
								: 'Profile ID is missing' );
							?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=api-configuration', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a>
				</li>
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $config->apikey ) ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">API Key: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php
							echo ( isset( $config->apikey )
								? esc_attr( $config->apikey )
								: 'Key is missing' );
							?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=api-configuration', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a>
				</li>
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $config->secretkey ) ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">Secret Key: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php
							echo ( isset( $config->secretkey )
								? '*****************'
								: 'Secret key is missing' );
							?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=api-configuration', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a>
				</li>
			</ul>
		</div>
	</div>
</div>
