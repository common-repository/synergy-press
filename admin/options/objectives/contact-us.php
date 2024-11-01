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
<!-- Objective for contact requests requests -->
<div id="fs-objective-contact-requests" class="fs-card">
	<?php
		$objective_contact_requests = $synergypress->resources->Get( 'objectiveContactRequests' );
		$get_request                = synergypress_get_request();
		$notificationmethod         = isset( $objective_contact_requests->notificationmethod )
							? $objective_contact_requests->notificationmethod
							: '';
	?>
	<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-objective-contact-requests', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="synergy-press-update-action">
		<input type="hidden" name="synergy-press-update-action" value="objectives">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-objective-contact-requests' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>">
				<span class="screen-reader-text edit">Edit: contact requests</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
		 
			<h4>
			<?php if ( $objective_contact_requests ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
			Contact Requests</h4>
		</div>
		<div class="fs-toggle fs-body  <?php echo( isset( $get_request['card'] ) && 'fs-objective-contact-requests' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<p class="fs-description">
			<?php if ( ! $contact_form ) : ?>
					The package contact form must be enabled in order to proceed <a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-contact-form', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="button btn-fix-it">Enable</a>
			<?php endif; ?>
			</p>
			<div class="radio-group">
				<strong>Enable this objective to receive notifications of new contact requests. </strong>
				<?php
					echo $synergypress->plugin->render(
						array(
							'input' => array(
								'show-label' => true,
								'type'       => 'radio',
								'options'    => array(
									array(
										'id'      => 'fs-install-objective-contact-requests-yes',
										'checked' => ( $objective_contact_requests
														? true
														: false ),
										'data'    => array(
											'toggle' => 'objective-contact-requests-settings',
										),
										'value'   => 'yes',
										'label'   => 'Yes',
										'name'    => 'objectives[contactRequests][install]',
									),
									array(
										'id'      => 'fs-install-objective-contact-requests-no',
										'checked' => ( ! $objective_contact_requests
														? true
														: false ),
										'data'    => array(
											'collapse' => 'objective-contact-requests-settings',
										),
										'value'   => 'no',
										'label'   => 'No',
										'name'    => 'objectives[contactRequests][install]',
									),
								),
							),
						)
					);
					?>
			</div>
			<div 
				id="objective-contact-requests-settings" 
				data-tab-group="objective-contact-requests-settings"
				class="tab-container fs-tab-content <?php echo( $objective_contact_requests ? 'fs-tab-active' : '' ); ?>">
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label'  => true,
									'type'        => 'text',
									'id'          => 'fs-objective-contact-requests-label',
									'label'       => 'Label',
									'name'        => 'objectives[contactRequests][label]',
									'description' => 'The label of the objective',
								),
							),
							isset( $objective_contact_requests->label )
							? esc_attr( $objective_contact_requests->label )
							: ''
						);
						?>
				</div>
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'select',
									'id'         => 'fs-objective-contact-requests-notification-method',
									'label'      => 'Notification method',
									'name'       => 'objectives[contactRequests][notificationmethod]',
									'options'    => array(
										''          => 'Select a method',
										'leadboard' => 'Lead Board only',
										'email'     => 'Get notified by email',
										'webhook'   => 'Set up a webhook',
										'sms'       => 'Get a text message',
									),
								),
							),
							isset( $notificationmethod )
							? esc_attr( $notificationmethod )
							: ''
						);
						?>
				</div>
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'text',
									'id'         => 'fs-objective-contact-requests-recipient-fname',
									'label'      => 'First name',
									'name'       => 'objectives[contactRequests][recipient][fname]',
									'status'     => esc_attr(
										( ! isset( $notificationmethod )
														? ''
														: 'email' === esc_attr( $notificationmethod ) )
														? 'required'
										: ''
									),
								),
							),
							( isset( $objective_contact_requests->recipient->fname )
							? esc_attr( $objective_contact_requests->recipient->fname )
							: '' )
						);
						?>
				</div>
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'text',
									'id'         => 'fs-objective-contact-requests-recipient-lname',
									'label'      => 'Last name',
									'name'       => 'objectives[contactRequests][recipient][lname]',
									'status'     => esc_attr(
										( ! isset( $notificationmethod )
														? ''
														: 'email' === esc_attr( $notificationmethod ) )
														? 'required'
										: ''
									),
								),
							),
							( isset( $objective_contact_requests->recipient->lname )
							? esc_attr( $objective_contact_requests->recipient->lname )
							: '' )
						);
						?>
				</div>
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'email',
									'id'         => 'fs-objective-contact-requests-recipient-email',
									'label'      => 'Email address',
									'name'       => 'objectives[contactRequests][recipient][email]',
									'status'     => esc_attr(
										( ! isset( $notificationmethod )
														? ''
														: 'email' === esc_attr( $notificationmethod ) )
														? 'required'
										: ''
									),
								),
							),
							( isset( $objective_contact_requests->recipient->email )
							? esc_attr( $objective_contact_requests->recipient->email )
							: '' )
						);
						?>
				</div>
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'tel',
									'id'         => 'fs-objective-contact-requests-recipient-phone',
									'label'      => 'Phone number',
									'name'       => 'objectives[contactRequests][recipient][sms]',
									'status'     => esc_attr(
										( ! isset( $notificationmethod )
														? ''
														: 'sms' === esc_attr( $notificationmethod ) )
														? 'required'
										: ''
									),
								),
							),
							( isset( $objective_contact_requests->recipient->sms )
							? esc_attr( $objective_contact_requests->recipient->sms )
							: '' )
						);
						?>
				</div>
				<div class="fs-form-group">
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'url',
									'id'         => 'fs-objective-contact-requests-endpoint-url',
									'label'      => 'Webhook address',
									'name'       => 'objectives[contactRequests][endpoint][url]',
									'status'     => esc_attr(
										( ! isset( $notificationmethod )
														? ''
														: 'webhook' === esc_attr( $notificationmethod ) )
														? 'required'
										: ''
									),
								),
							),
							( isset( $objective_contact_requests->endpoint->url )
							? esc_attr( $objective_contact_requests->endpoint->url )
							: '' )
						);
						?>
				</div>
				</div>
			<button type="submit" class="button button-primary button-small fs-button-right">Update</button>
			<div class="clear"></div>
			</div>
	</form>
</div>
