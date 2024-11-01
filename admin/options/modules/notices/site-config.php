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
 * Notice for: Site Configuration.
 */

$toggle_status = null;
if ( isset( $website->domain, $website->proto, $website->siteid, $website->verified )
	&& 'yes' === $website->verified ) :
	$toggle_status = 'close';
endif;
?>
<div id="fs-notification-site-config" class="fs-card">
	<div class="fs-sub-headings">
		<button type="button" class="fs-toggler" aria-expanded="<?php echo is_null( $toggle_status ) ? 'true' : 'false'; ?>">
			<span class="screen-reader-text edit">Read more: Site Configuration Status</span>
			<span class="dashicons dashicons-arrow-down"></span>
		</button>
		<h4>
		<?php if ( ! is_null( $toggle_status ) ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
		<?php endif; ?>
			Site Configuration</h4>
	</div>
   
	<div class="fs-toggle fs-body h-10 <?php echo esc_attr( $toggle_status ); ?>">
		<div class="fs-content">
			<ul class="fs-list w-50">
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
					<span class="fs-main-text">Site Name:</span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php echo esc_attr( $site_name ); ?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a> 
				</li>
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $website->domain ) ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">Domain Name: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php echo ( isset( $website->domain ) ? $synergypress->validate->sanitize_domain( $website->domain ) : $synergypress->validate->sanitize_domain( get_home_url() ) ); ?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a>
				</li>
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $website->proto ) ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">Protocol: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php
							echo ( isset( $website->proto )
								&& 'https://' === $website->proto
								? 'Secured - https'
								: 'Unsecured - http' );
							?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a>
				</li>
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $website->siteid ) ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">Site ID: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php echo ( isset( $website->siteid ) ? esc_attr( $website->siteid ) : 'Site ID is missing' ); ?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Edit</a>
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm <?php echo ( isset( $website->verified ) && 'yes' === $website->verified ? ' fs-success' : 'fs-error' ); ?>"></span> 
					<span class="fs-main-text">Verified: </span> 
					<span class="fs-bold" style="color: #2196F3;">
						<?php
							echo ( isset( $website->verified )
								&& 'yes' === $website->verified
								? 'Yes'
								: 'No' );
							?>
					</span> 
					<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it  ">Edit</a>
				</li>
			</ul>
		</div>
	</div>
</div>
