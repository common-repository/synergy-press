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
<!-- Objective for newsletter subscription requests -->
<div id="fs-objective-news-letter-subscription" class="fs-card">
	<?php
		$objective_newsletter_subscription = $synergypress->resources->Get( 'objectiveNewsLetterSubscription' );
		$get_request                       = synergypress_get_request();
		$notificationmethod                = isset( $objective_newsletter_subscription->notificationmethod )
							? $objective_newsletter_subscription->notificationmethod
							: '';
	?>
	<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-objective-news-letter-subscription', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="synergy-press-update-action">
		<input type="hidden" name="synergy-press-update-action" value="objectives">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-objective-news-letter-subscription' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>">
				<span class="screen-reader-text edit">Edit: Newsletter Subscriber</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>

			<h4>
			<?php if ( $objective_newsletter_subscription ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
			Newsletter Subscriber</h4>
		</div>
		<div
			class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'fs-objective-news-letter-subscription' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<p class="fs-description">
				<?php if ( ! $newsletter_subscription ) : ?>
					The package newsletter subscription must be enabled in order to proceed <a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-news-letter-subscription', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="button btn-fix-it">Enable</a>
				<?php endif; ?>
			</p>
			<div class="checkbox-group">
				<strong>Enable this objective to receive notifications of new subscriber ( Newsletter ). </strong>
				<?php
					echo $synergypress->plugin->render(
						array(
							'input' => array(
								'show-label' => true,
								'type'       => 'radio',
								'options'    => array(
									array(
										'id'      => 'fs-install-objective-news-letter-subscription-yes',
										'checked' => ( $objective_newsletter_subscription
														&& isset( $objective_newsletter_subscription->install )
														&& 'yes' === $objective_newsletter_subscription->install
														? true
														: false ),
										'data'    => array(
											'toggle' => 'objective-news-letter-subscription-settings',
										),
										'value'   => 'yes',
										'label'   => 'Yes',
										'name'    => 'objectives[newsLetterSubscription][install]',
									),
									array(
										'id'      => 'fs-install-objective-news-letter-subscription-no',
										'checked' => ( ! $objective_newsletter_subscription
														|| ! isset( $objective_newsletter_subscription->install )
														|| 'no' === $objective_newsletter_subscription->install
														? true
														: false ),
										'data'    => array(
											'collapse' => 'objective-news-letter-subscription-settings',
										),
										'value'   => 'no',
										'label'   => 'No',
										'name'    => 'objectives[newsLetterSubscription][install]',
									),
								),
							),
						)
					);
					?>
			</div>
			<div id="objective-news-letter-subscription-settings"
				data-tab-group="objective-news-letter-subscription-settings"
				class="tab-container fs-tab-content <?php echo( $objective_newsletter_subscription ? 'fs-tab-active' : '' ); ?>">
				<div class="fs-tabs">
					<div class="fs-form-group">
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label'  => true,
										'type'        => 'text',
										'id'          => 'fs-objective-news-letter-subscription-label',
										'label'       => 'Label',
										'name'        => 'objectives[newsLetterSubscription][label]',
										'status'      => 'required',
										'description' => 'The label of the objective',
									),
								),
								isset( $objective_newsletter_subscription->label )
								? esc_attr( $objective_newsletter_subscription->label )
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
										'id'         => 'fs-objective-news-letter-subscription-notification-method',
										'label'      => 'Notification method',
										'name'       => 'objectives[newsLetterSubscription][notificationmethod]',
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
								isset( $objective_newsletter_subscription->notificationmethod )
								? esc_attr( $objective_newsletter_subscription->notificationmethod )
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
										'id'         => 'fs-objective-news-letter-subscription-recipient-fname',
										'label'      => 'First name',
										'name'       => 'objectives[newsLetterSubscription][recipient][fname]',
										'status'     => ( ! isset( $objective_newsletter_subscription->notificationmethod )
														? ''
														: 'email' === esc_attr( $objective_newsletter_subscription->notificationmethod ) )
														? 'required'
														: '',
									),
								),
								( isset( $objective_newsletter_subscription->recipient->fname )
								? esc_attr( $objective_newsletter_subscription->recipient->fname )
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
										'id'         => 'fs-objective-news-letter-subscription-recipient-lname',
										'label'      => 'Last name',
										'name'       => 'objectives[newsLetterSubscription][recipient][lname]',
										'status'     => ( ! isset( $objective_newsletter_subscription->notificationmethod )
														? ''
														: 'email' === esc_attr( $objective_newsletter_subscription->notificationmethod ) )
														? 'required'
														: '',
									),
								),
								( isset( $objective_newsletter_subscription->recipient->lname )
								? esc_attr( $objective_newsletter_subscription->recipient->lname )
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
										'id'         => 'fs-objective-news-letter-subscription-recipient-email',
										'label'      => 'Email address',
										'name'       => 'objectives[newsLetterSubscription][recipient][email]',
										'status'     => ( ! isset( $objective_newsletter_subscription->notificationmethod )
														? ''
														: 'email' == esc_attr( $objective_newsletter_subscription->notificationmethod ) )
														? 'required'
														: '',
									),
								),
								( isset( $objective_newsletter_subscription->recipient->email )
								? esc_attr( $objective_newsletter_subscription->recipient->email )
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
										'id'         => 'fs-objective-news-letter-subscription-recipient-phone',
										'label'      => 'Phone number',
										'name'       => 'objectives[newsLetterSubscription][recipient][sms]',
										'status'     => ( ! isset( $objective_newsletter_subscription->notificationmethod )
														? ''
														: 'sms' == esc_attr( $objective_newsletter_subscription->notificationmethod ) )
														? 'required'
														: '',
									),
								),
								( isset( $objective_newsletter_subscription->recipient->sms )
								? esc_attr( $objective_newsletter_subscription->recipient->sms )
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
										'id'         => 'fs-objective-news-letter-subscription-endpoint-url',
										'label'      => 'Webhook address',
										'name'       => 'objectives[newsLetterSubscription][endpoint][url]',
										'status'     => ( ! isset( $objective_newsletter_subscription->notificationmethod )
														? ''
														: 'webhook' == esc_attr( $objective_newsletter_subscription->notificationmethod ) )
														? 'required'
														: '',
									),
								),
								( isset( $objective_newsletter_subscription->endpoint->url )
								? esc_attr( $objective_newsletter_subscription->endpoint->url )
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
