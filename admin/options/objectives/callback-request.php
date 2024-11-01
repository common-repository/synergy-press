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
<!-- Objective for callback request requests -->
<div id="fs-objective-call-back-requests" class="fs-card">
	<?php
		$objective_request_callback = $synergypress->resources->Get( 'objectiveRequestCallback' );
		$get_request                = synergypress_get_request();
		$notificationmethod         = isset( $objective_request_callback->notificationmethod )
							? $objective_request_callback->notificationmethod
							: '';
	?>
	<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-objective-call-back-requests', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="synergy-press-update-action">
		<input type="hidden" name="synergy-press-update-action" value="objectives">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-objective-call-back-requests' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>">
				<span class="screen-reader-text edit">Edit: Callback Requests</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
		 
			<h4>
			<?php if ( $objective_request_callback ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
			Callback Requests</h4>
		</div>
		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'fs-objective-call-back-requests' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<p class="fs-description">
			<?php if ( ! $request_callback ) : ?>
					The package request callback must be enabled in order to proceed <a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-request-callback', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="button btn-fix-it">Enable</a>
			<?php endif; ?>
			</p>
			<div class="checkbox-group">
				<strong>Enable this objective to receive notifications of new callback requests. </strong>
				<?php
					echo $synergypress->plugin->render(
						array(
							'input' => array(
								'show-label' => true,
								'type'       => 'radio',
								'options'    => array(
									array(
										'id'      => 'fs-install-objective-call-back-requests-yes',
										'checked' => ( $objective_request_callback
														&& isset( $objective_request_callback->install )
														&& 'yes' === $objective_request_callback->install
														? true
														: false ),
										'data'    => array(
											'toggle' => 'objective-call-back-requests-settings',
										),
										'value'   => 'yes',
										'label'   => 'Yes',
										'name'    => 'objectives[requestCallback][install]',
									),
									array(
										'id'      => 'fs-install-objective-call-back-requests-no',
										'checked' => ( ! $objective_request_callback
														|| ! isset( $objective_request_callback->install )
														|| 'no' === $objective_request_callback->install
														? true
														: false ),
										'data'    => array(
											'collapse' => 'objective-call-back-requests-settings',
										),
										'value'   => 'no',
										'label'   => 'No',
										'name'    => 'objectives[requestCallback][install]',
									),
								),
							),
						)
					);
					?>
			</div>
			<div id="objective-call-back-requests-settings" data-tab-group="objective-call-back-requests-settings"
				class="tab-container fs-tab-content <?php echo( $objective_request_callback ? 'fs-tab-active' : '' ); ?>">
				<div class="fs-tabs">
					<div class="fs-form-group">
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label'  => true,
										'type'        => 'text',
										'id'          => 'fs-objective-call-back-requests-label',
										'label'       => 'Label',
										'name'        => 'objectives[requestCallback][label]',
										'status'      => 'required',
										'description' => 'The label of the objective',
									),
								),
								isset( $objective_request_callback->label )
								? esc_attr( $objective_request_callback->label )
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
										'id'         => 'fs-objective-call-back-requests-notification-method',
										'label'      => 'Notification method',
										'name'       => 'objectives[requestCallback][notificationmethod]',
										'status'     => 'required',
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
										'id'         => 'fs-objective-call-back-requests-recipient-fname',
										'label'      => 'First name',
										'name'       => 'objectives[requestCallback][recipient][fname]',
										'status'     => esc_attr(
											( ! isset( $notificationmethod )
														? ''
														: 'email' === esc_attr( $notificationmethod ) )
														? 'required'
											: ''
										),
									),
								),
								( isset( $objective_request_callback->recipient->fname )
								? esc_attr( $objective_request_callback->recipient->fname )
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
										'id'         => 'fs-objective-call-back-requests-recipient-lname',
										'label'      => 'Last name',
										'name'       => 'objectives[requestCallback][recipient][lname]',
										'status'     => esc_attr(
											( ! isset( $notificationmethod )
														? ''
														: 'email' === esc_attr( $notificationmethod ) )
														? 'required'
											: ''
										),
									),
								),
								( isset( $objective_request_callback->recipient->lname )
								? esc_attr( $objective_request_callback->recipient->lname )
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
										'id'         => 'fs-objective-call-back-requests-recipient-email',
										'label'      => 'Email address',
										'name'       => 'objectives[requestCallback][recipient][email]',
										'status'     => esc_attr(
											( ! isset( $notificationmethod )
														? ''
														: 'email' === esc_attr( $notificationmethod ) )
														? 'required'
											: ''
										),
									),
								),
								( isset( $objective_request_callback->recipient->email )
								? esc_attr( $objective_request_callback->recipient->email )
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
										'id'         => 'fs-objective-call-back-requests-recipient-phone',
										'label'      => 'Phone number',
										'name'       => 'objectives[requestCallback][recipient][sms]',
										'status'     => esc_attr(
											( ! isset( $notificationmethod )
														? ''
														: 'sms' === esc_attr( $notificationmethod ) )
														? 'required'
											: ''
										),
									),
								),
								( isset( $objective_request_callback->recipient->sms )
								? esc_attr( $objective_request_callback->recipient->sms )
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
										'id'         => 'fs-objective-call-back-requests-endpoint-url',
										'label'      => 'Webhook address',
										'name'       => 'objectives[requestCallback][endpoint][url]',
										'status'     => esc_attr(
											( ! isset( $notificationmethod )
														? ''
														: 'webhook' === esc_attr( $notificationmethod ) )
														? 'required'
											: ''
										),
									),
								),
								( isset( $objective_request_callback->endpoint->url )
								? esc_url( $objective_request_callback->endpoint->url )
								: '' )
							);
							?>
					</div>
				</div>
			</div>
			<button type="submit" class="button button-primary button-small fs-button-right">Update</button>
			<div class="clear"></div>
		</div>
	</form>
</div>
