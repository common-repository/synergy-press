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
if ( ! defined( 'SYNERGY_PRESS' ) ) {
	return;
}
?>
<!-- Newsletter subscription -->
<div id="fs-news-letter-subscription" class="fs-card">
	<?php $get_request = synergypress_get_request(); ?>
	<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-news-letter-subscription', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="synergy-press-update-action">
		<input type="hidden" name="synergy-press-update-action" value="modules">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-news-letter-subscription' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="modules">
				<span class="screen-reader-text edit">Edit: Newsletter Subscription</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<?php $newsletter_subscription = $synergypress->resources->Get( 'newsletterSubscription' ); ?>
			<h4>
			<?php if ( $newsletter_subscription ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
				Newsletter Subscription</h4>
		</div>
		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'fs-news-letter-subscription' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<p class="fs-description">
				This interaction is dedicated for newsletter subscriptions.
				<ul class="fs-features-list">
					<li>Email validation</li>
				</ul>
			</p>
			<hr class="fs-hr" />
			<div class="checkbox-group">
				<strong>Enable newsletter subscription</strong>
				<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'radio',
									'options'    => array(
										array(
											'id'      => 'fs-install-news-letter-subscription-yes',
											'checked' => ( $newsletter_subscription
															? true
															: false ),
											'data'    => array(
												'toggle' => 'news-letter-subscription-settings',
											),
											'value'   => 'yes',
											'label'   => 'Yes',
											'name'    => 'modules[newsletterSubscription][install]',
										),
										array(
											'id'      => 'fs-install-news-letter-subscription-no',
											'checked' => ( ! $newsletter_subscription
															? true
															: false ),
											'data'    => array(
												'collapse' => 'news-letter-subscription-settings',
											),
											'value'   => 'no',
											'label'   => 'No',
											'name'    => 'modules[newsletterSubscription][install]',
										),
									),
								),
							)
						);

						?>
			</div>
			<div id="news-letter-subscription-settings" data-tab-group="news-letter-subscription-settings"
				class="tab-container fs-tab-content <?php echo( $newsletter_subscription ? 'fs-tab-active' : '' ); ?>">
				<h3 class="nav-tab-wrapper">
					<a href="#news-letter-subscription-options" class="nav-tab nav-tab-active"> Options </a>
					<a href="#news-letter-subscription-trigger" class="nav-tab"> Trigger </a>
				</h3>
				<div class="fs-tabs">
					<div class="fs-tab-content fs-tab-active border bg-light"
						id="news-letter-subscription-options">
						<div class="fs-form-group">
							<?php
									echo $synergypress->plugin->render(
										array(
											'input' => array(
												'show-label' => true,
												'type'   => 'text',
												'id'     => 'fs-news-letter-subscription-subject',
												'label'  => 'Subject',
												'name'   => 'modules[newsletterSubscription][headings][0][subject]',
												'status' => '',
												'description' => 'The subject of the interaction, displayed at the very top of the interaction',
											),
										),
										isset( $newsletter_subscription->headings[0]->subject )
										? esc_attr( $newsletter_subscription->headings[0]->subject )
										: ''
									);
									?>
						</div>
						<div class="fs-form-group">
							<?php
								echo $synergypress->plugin->render(
									array(
										'input' => array(
											'show-label'  => true,
											'type'        => 'textarea',
											'id'          => 'fs-news-letter-subscription-body',
											'label'       => 'Body',
											'name'        => 'modules[newsletterSubscription][headings][0][body]',
											'status'      => '',
											'description' => 'The body of the interaction, displayed underneath the subject, and above form inputs',
										),
									),
									isset( $newsletter_subscription->headings[0]->body )
									? esc_textarea( $newsletter_subscription->headings[0]->body )
									: ''
								);
								?>
						</div>
					</div>
					<div class="fs-tab-content" id="news-letter-subscription-trigger">
						<?php if ( $newsletter_subscription ) : ?>
							<div class="fs-form-group">
								<input type="hidden" name="modules[newsletterSubscription][fstrigger]" value=".fs-trigger-<?php echo esc_attr( $newsletter_subscription->moduleid ); ?>">
								<div class="fs-item-description">Simply add this class to the element triggering this interaction.</div>
								<input type="text" value="fs-trigger-<?php echo esc_attr( $newsletter_subscription->moduleid ); ?>" class="fs-code widefat" readonly>
							</div>
							<?php
								$params  = array(
									'etag'   => 'onclick:news-letter-subscription',
									'params' => array(
										'trigger' => array(
											'moduleid' => esc_attr( $newsletter_subscription->moduleid ),
										),
									),
								);
								$options = array(
									'el'  => '@' . esc_attr( $newsletter_subscription->moduleid ),
									'opt' => array(
										'display'   => 'fixed',
										'placement' => 'centered',
										'size'      => 'lg',
										'theme'     => 'white',
									),
								);
							?>
							<input type="hidden" name="modules[newsletterSubscription][params]" value='<?php echo wp_json_encode( $params ); ?>'>
							<input type="hidden" name="modules[newsletterSubscription][opt]" value='<?php echo wp_json_encode( $options ); ?>'>
						<?php else : ?>
							<p class="fs-description">Trigger snippet will be available when this module is installed </p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<button type="submit" class="button button-primary button-small fs-button-right">Update</button>
			<div class="clear"></div>
		</div>
	</form>
</div>
