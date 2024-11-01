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
<!-- Contact form -->
<div id="fs-contact-form" class="fs-card">
	<?php $get_request = synergypress_get_request(); ?>
	<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-contact-form', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="synergy-press-update-action">
		<input type="hidden" name="synergy-press-update-action" value="modules">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-contact-form' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="modules">
				<span class="screen-reader-text edit">Edit: Contact Form</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<?php $contact_form = $synergypress->resources->Get( 'contactForm' ); ?>
			<h4> 
			<?php if ( $contact_form ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
			Contact Form</h4>
		</div>
		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'fs-contact-form' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<p class="fs-description">
				This simple yet elegant contact form, is loaded with features.
			</p>
			<ul class="fs-features-list">
				<li>Bad word checker</li>
				<li>Email validation</li>
				<li>Mobile phone validation</li>
				<li>Address auto complete</li>
			</ul>
			<hr class="fs-hr" />
			<div class="checkbox-group">
				<strong>Enable contact form</strong>
				<?php
					echo $synergypress->plugin->render(
						array(
							'input' => array(
								'show-label' => true,
								'type'       => 'radio',
								'options'    => array(
									array(
										'id'      => 'fs-install-contact-form-yes',
										'checked' => ( $contact_form
														? true
														: false ),
										'data'    => array(
											'toggle' => 'contact-form-settings',
										),
										'value'   => 'yes',
										'label'   => 'Yes',
										'name'    => 'modules[contactForm][install]',
									),
									array(
										'id'      => 'fs-install-contact-form-no',
										'checked' => ( ! $contact_form
														? true
														: false ),
										'data'    => array(
											'collapse' => 'contact-form-settings',
										),
										'value'   => 'no',
										'label'   => 'No',
										'name'    => 'modules[contactForm][install]',
									),
								),
							),
						)
					);
					?>
			</div>
			<div id="contact-form-settings" data-tab-group="contact-form-settings"
				class="tab-container fs-tab-content <?php echo( $contact_form ? 'fs-tab-active' : '' ); ?>">
				<h3 class="nav-tab-wrapper">
					<a href="#contact-form-options" class="nav-tab nav-tab-active"> Settings </a>
					<a href="#contact-form-class-name" class="nav-tab nav-tab-active"> Trigger </a>
				</h3>
				<div class="fs-tabs">
					<div class="fs-tab-content fs-tab-active border bg-light" id="contact-form-options">
							<div class="fs-form-group">
							<?php
								echo $synergypress->plugin->render(
									array(
										'input' => array(
											'show-label'  => true,
											'type'        => 'text',
											'id'          => 'fs-contactform-subject',
											'label'       => 'Subject',
											'name'        => 'modules[contactForm][headings][0][subject]',
											'status'      => '',
											'description' => 'The subject of the interaction, displayed at the very top of the interaction',
										),
									),
									isset( $contact_form->headings[0]->subject )
									? esc_html( $contact_form->headings[0]->subject )
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
											'id'          => 'fs-contactform-body',
											'label'       => 'Body',
											'name'        => 'modules[contactForm][headings][0][body]',
											'status'      => '',
											'description' => 'The body of the interaction, displayed underneath the subject, and above form inputs',
										),
									),
									isset( $contact_form->headings[0]->body )
									? esc_textarea( $contact_form->headings[0]->body )
									: ''
								);
								?>
						</div>
					</div>
					<div class="fs-tab-content border bg-light" id="contact-form-class-name">
						<?php if ( $contact_form ) : ?>
							<div class="fs-form-group">
								<div class="fs-item-descrition">Simply add this class to the element triggering this interaction.</div>
								<input type="text" value="fs-trigger-<?php echo esc_attr( $contact_form->moduleid ); ?>" class="fs-code widefat" readonly>
								<input type="hidden" name="modules[contactForm][fstrigger]" value=".fs-trigger-<?php echo esc_attr( $contact_form->moduleid ); ?>">
							</div>
							<?php
								$params  = array(
									'etag'   => 'onclick:contact-us',
									'params' => array(
										'trigger' => array(
											'moduleid' => esc_attr( $contact_form->moduleid ),
										),
									),
								);
								$options = array(
									'el'  => '@' . esc_attr( $contact_form->moduleid ),
									'opt' => array(
										'display'   => 'fixed',
										'placement' => 'centered',
										'size'      => 'lg',
										'theme'     => 'white',
									),
								);
							?>
							<input type="hidden" name="modules[contactForm][params]" value='<?php echo wp_json_encode( $params ); ?>'>
							<input type="hidden" name="modules[contactForm][opt]" value='<?php echo wp_json_encode( $options ); ?>'>
						<?php else : ?>
								<p class="fs-description pt-3 pl-3">Trigger snippet will be available when this module is installed </p>
						<?php endif; ?>
						</div>
					</div>
				</div>
				<button type="submit" class="button button-primary button-small fs-button-right">Update</button>
				<div class="clear"></div>
		</div>
	</form>
</div>
