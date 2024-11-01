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
<!-- Request callback -->
<div id="fs-request-callback" class="fs-card">
	<?php $get_request = synergypress_get_request(); ?>
	<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-request-callback', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
		<input type="hidden" name="action" value="synergy-press-update-action">
		<input type="hidden" name="synergy-press-update-action" value="modules">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-request-callback' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="modules">
				<span class="screen-reader-text edit">Edit: Request Callback Modules</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<?php $request_callback = $synergypress->resources->Get( 'requestCallback' ); ?>
			<h4>
			<?php if ( $request_callback ) : ?>
				<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
			<?php endif; ?>
			Request Callback</h4>
		</div>
		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'fs-request-callback' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<p class="fs-description">
				This interaction allows visitors to request a callback by providing a mobile phone number.
				<ul class="fs-features-list">
					<li>Mobile phone validation</li>
				</ul>
			</p>
			<hr class="fs-hr" />
			<div class="checkbox-group">
				<strong>Enable request callback</strong>
				<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'radio',
									'options'    => array(
										array(
											'id'      => 'fs-install-request-callback-yes',
											'checked' => ( $request_callback
															? true
															: false ),
											'data'    => array(
												'toggle' => 'request-callback-settings',
											),
											'value'   => 'yes',
											'label'   => 'Yes',
											'name'    => 'modules[requestCallback][install]',
										),
										array(
											'id'      => 'fs-install-request-callback-no',
											'checked' => ( ! $request_callback
															? true
															: false ),
											'data'    => array(
												'collapse' => 'request-callback-settings',
											),
											'value'   => 'no',
											'label'   => 'No',
											'name'    => 'modules[requestCallback][install]',
										),
									),
								),
							)
						);
						?>
			</div>
			<div id="request-callback-settings" data-tab-group="request-callback-settings"
				class="tab-container fs-tab-content <?php echo( $request_callback ? 'fs-tab-active' : '' ); ?>">
				<h3 class="nav-tab-wrapper">
					<a href="#request-callback-options" class="nav-tab nav-tab-active"> Options </a>
					<a href="#request-callback-trigger" class="nav-tab"> Trigger </a>
				</h3>
				<div class="fs-tabs">
					<div class="fs-tab-content fs-tab-active border bg-light" id="request-callback-options">
						<div class="fs-form-group">
							<?php
								echo $synergypress->plugin->render(
									array(
										'input' => array(
											'show-label'  => true,
											'type'        => 'text',
											'id'          => 'fs-request-callback-subject',
											'label'       => 'Subject',
											'name'        => 'modules[requestCallback][headings][0][subject]',
											'status'      => '',
											'description' => 'The subject of the interaction, displayed at the very top of the interaction',
										),
									),
									isset( $request_callback->headings[0]->subject )
									? esc_attr( $request_callback->headings[0]->subject )
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
											'id'          => 'fs-request-callback-body',
											'label'       => 'Body',
											'name'        => 'modules[requestCallback][headings][0][body]',
											'status'      => '',
											'description' => 'The body of the interaction, displayed underneath the subject, and above form inputs',
										),
									),
									isset( $request_callback->headings[0]->body )
									? esc_textarea( $request_callback->headings[0]->body )
									: ''
								);
								?>
						</div>
					</div>
					<div class="fs-tab-content" id="request-callback-trigger">
						<?php if ( $request_callback ) : ?>
							<div class="fs-form-group">
								<input type="hidden" name="modules[requestCallback][fstrigger]" value=".fs-trigger-<?php echo esc_attr( $request_callback->moduleid ); ?>">
								<div class="fs-item-description">Simply add this class to the element triggering this interaction.</div>
								<input type="text" value="fs-trigger-<?php echo esc_attr( $request_callback->moduleid ); ?>" class="fs-code widefat" readonly>
							</div>
							<?php
								$params  = array(
									'etag'   => 'onclick:request-callback',
									'params' => array(
										'trigger' => array(
											'moduleid' => esc_attr( $request_callback->moduleid ),
										),
									),
								);
								$options = array(
									'el'  => '@' . esc_attr( $request_callback->moduleid ),
									'opt' => array(
										'display'   => 'fixed',
										'placement' => 'centered',
										'size'      => 'lg',
										'theme'     => 'white',
									),
								);
							?>
							<input type="hidden" name="modules[requestCallback][params]" value='<?php echo wp_json_encode( $params ); ?>'>
							<input type="hidden" name="modules[requestCallback][opt]" value='<?php echo wp_json_encode( $options ); ?>'>
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
