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

$advanced_mode           = $synergypress->resources->Get( 'advancedMode' );
$default_mode            = $advanced_mode && 'yes' === $advanced_mode->status
									? false
									: true;
$contact_form            = $synergypress->resources->Get( 'contactForm' );
$request_callback        = $synergypress->resources->Get( 'requestCallback' );
$newsletter_subscription = $synergypress->resources->Get( 'newsLetterSubscription' );

$objective_contact_requests        = $synergypress->resources->Get( 'objectiveContactRequests' );
$objective_request_callback        = $synergypress->resources->Get( 'objectiveRequestCallback' );
$objective_newsletter_subscription = $synergypress->resources->Get( 'objectiveNewsLetterSubscription' );
?>
<div id="fs-notification-package-config" class="fs-card">
	<div class="fs-sub-headings">
		<button type="button" class="fs-toggler" aria-expanded="true">
			<span class="screen-reader-text edit">Read more: Packages</span>
			<span class="dashicons dashicons-arrow-down"></span>
		</button>
		<h4>Packages</h4>
	</div>
	<div class="fs-toggle fs-body h-15">
		<div class="fs-content">
		<?php
		if ( ! $default_mode ) :
			?>
			<ul class="fs-list w-50">
				<li>
					<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span>
					<span class="fs-main-text">Advanced Mode Enabled</span>
				</li>
			</ul>
			<?php
		else :
			?>
			<ul class="fs-list w-50">
				<li>
					<?php if ( $strategy ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
						<span class="fs-main-text">Strategy</span>
					<?php else : ?>
						<span class="fs-main-text">Strategy: </span> 
						<span class="fs-notice">To get started with interactions, a strategy must be defined first.</span> 
						<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-strategy', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Update</a>
					<?php endif; ?>
				</li>
				<li>
					<?php if ( $contact_form ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">Contact Form <em class="fs-small">Module</em></span>
					<?php else : ?>
						<span class="fs-main-text">Contact Form: </span> 
										<em class="fs-small">(Module)</em>  
										<span class="fs-notice">is disabled</span> 
										<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-contact-form', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Enable</a>
					<?php endif; ?>
				</li>
				<li>
					<?php if ( $request_callback ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">Request Callback <em class="fs-small">Module</em></span>
					<?php else : ?>
						<span class="fs-main-text">Request Callback: </span> 
										<em class="fs-small">(Module)</em>  
										<span class="fs-notice">is disabled</span> 
										<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-request-callback', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Enable</a>
					<?php endif; ?>
				</li>
				<li>
					<?php if ( $newsletter_subscription ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">News Letter Subscription <em class="fs-small">Module</em></span>
					<?php else : ?>
						<span class="fs-main-text">News Letter Subscription:</span> 
										<em class="fs-small">(Module)</em>  
										<span class="fs-notice">is disabled</span> 
										<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-news-letter-subscription', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Enable</a>
					<?php endif; ?>
				</li>
				<li>
					<?php if ( $objective_contact_requests ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">Contact Requests <em class="fs-small">Objective</em></span>
					<?php else : ?>
						<span class="fs-main-text">Contact Requests:</span> 
										<em class="fs-small">(Objective)</em> 
										<span class="fs-notice">is disabled</span>
										<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-objective-contact-requests', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Enable</a>
					<?php endif; ?>
				</li>
				<li>
					<?php if ( $objective_request_callback ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">Callback Requests <em class="fs-small">Objective</em></span>
					<?php else : ?>
						<span class="fs-main-text">Callback Requests Objective:</span> 
										<em class="fs-small">(Objective)</em> 
										<span class="fs-notice">is disabled</span>
										<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-objective-call-back-requests', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Enable</a>
					<?php endif; ?>
				</li>
				<li>
					<?php if ( $objective_newsletter_subscription ) : ?>
						<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">News Letter Subscribers <em class="fs-small">Objective</em></span>
					<?php else : ?>
						<span class="fs-main-text">News Letter Subscribers:</span> 
										<em class="fs-small">(Objective)</em> 
										<span class="fs-notice">is disabled</span>
										<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-objective-news-letter-subscription', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Enable</a>
					<?php endif; ?>
				</li>
			</ul>
			<?php
		endif;
		?>
		</div>
	</div>
</div>
