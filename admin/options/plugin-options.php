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
 * Form Synergy plugin options: Other options.
 */
?>

<div id="synergy-press-plugin-options" class="tab-content w-50">
	<div class="fs-headings"></div>
	<!-- Form Synergy heartbeat -->
	<div id="fs-options-heartbeat" class="fs-card">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'heartbeat' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="heartbeat">
				<span class="screen-reader-text edit">Edit: Heartbeat</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<h4>Heartbeat</h4>
		</div>

		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'heartbeat' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<div class="tab-container">
				<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=heartbeat', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
					<input type="hidden" name="action" value="synergy-press-update-action">
					<input type="hidden" name="synergy-press-update-action" value="website">
					<div>
						<p class="fs-item-description">
							The Form Synergy API heartbeat is not related nor will it affect the WordPress heartbeat. 
						</p>
						<p class="fs-strong">
							Features and benefits:
						</p>
						<ul class="fs-list-description-blank">
							<li>
								<span class="fs-bold">Interuptions:</span> The first measure to detect interuptions between visitors and Form Synergy, during interuptions, requests will be routed locally and handled by WordPress resources in order to prevent loss of any inquiries.
							</li>
							<li><span class="fs-bold">Automated triggers:</span> May include trigger information. (Implemented by Form Synergy).</li>
						</ul>
					</div>
					<hr />
					<div class="checkbox-group">
						<strong>Enable Heartbeat</strong>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label' => true,
										'type'       => 'radio',
										'options'    => array(
											array(
												'id'      => 'fs-heartbeat-enable-yes',
												'checked' => ( isset( $website->heartbeat->enable )
																&& 'yes' === $website->heartbeat->enable
																? true
																: false ),
												'data'    => array(
													'toggle' => 'fs-heartbeat-settings',
												),
												'value'   => 'yes',
												'label'   => 'Yes',
												'name'    => 'website[heartbeat][enable]',
											),
											array(
												'id'      => 'fs-heartbeat-enable-no',
												'checked' => ( ! isset( $website->heartbeat->enable )
																|| 'no' === $website->heartbeat->enable
																? true
																: false ),
												'data'    => array(
													'collapse' => 'fs-heartbeat-settings',
												),
												'value'   => 'no',
												'label'   => 'No',
												'name'    => 'website[heartbeat][enable]',
											),
										),
									),
								)
							);
							?>
					</div>
					<div id="fs-heartbeat-settings" 
						data-tab-group="fs-heartbeat-settings"
						class="tab-container fs-tab-content 
						<?php
							echo ( isset( $website->heartbeat->enable )
								&& 'yes' === $website->heartbeat->enable
								? 'fs-tab-active'
								: '' );
							?>
						">
						<div class="fs-form-group w-50">
							<?php
								echo $synergypress->plugin->render(
									array(
										'input' => array(
											'show-label'  => true,
											'type'        => 'number',
											'name'        => 'website[heartbeat][frequency]',
											'id'          => 'fs-heartbeat',
											'label'       => 'Heartbeat',
											'description' => 'Heartbeat frequency in milliseconds. Min frequency is: 7,500 ms',
										),
									),
									isset( $website->heartbeat->frequency )
									? esc_attr( $website->heartbeat->frequency )
									: 7800
								);
								?>
						</div>
					</div>
					<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
					<div class="clear"></div>
				</form>
			</div>
		</div>
	</div>
	<!-- SynergyPress mode -->
	<div id="fs-options-advanced-mod" class="fs-card">
		<?php $advanced_mode = $synergypress->resources->Get( 'advancedMode' ); ?>
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'advanced-mode' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="interactions-presets">
				<span class="screen-reader-text edit">Edit: Plugin mode</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<h4>Advanced Mode</h4>
		</div>
		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'advanced-mode' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<div class="tab-container">
				<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=advanced-mode', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
					<input type="hidden" name="action" value="synergy-press-update-action">
					<input type="hidden" name="synergy-press-update-action" value="advancedMode">
					<div>
					<p class="fs-strong">Default mode</p>
					<p class="fs-item-description mt-0">
						In order to create and manage essential modules along with their objectives, advanced must be dissabled. 
					</p>
					<hr />
					<p class="fs-strong">Advanced mode</p>
					<p class="fs-item-description mt-0">
					Alternatively, if a strategy and it's modules already exists, enable advanced mod. 
					</p>
					<p class="fs-strong">Getting started:</p>
					<ol class="fs-features-list mt-0">
						<li>You will need to locate the strategy ID</li>
						<li>Next, within the plugin, click on the interactions tab, add the strategy ID in appropriate field and update. </li>
						<li>During the update process, the plugin will import all modules available in that strategy.</li>
						<li>
							<span class="fs-bold">Where to find the strategy ID</span>
							<ul class="fs-list-description-blank mt-0">
								<li><span class="fs-bold">If using the API:</span> When a strategy is created, the strategy ID can be found within the response.</li>
								<li><span class="fs-bold">Using the Console:</span> Enter the website / domain you whish to administer, click to manage the strategy in question, select the strategy tab in order to reveal it's contents. 
									<a class="fs-btn-icon"
										href="<?php echo esc_url( 'https://formsynergy.com/console/' ); ?>"
										target="_blank" 
										title="Open the Form Synergy Console in a new tab"> 
											<span class="dashicons dashicons-external"></span> 
									</a>
								</li>
							</ul>
						</li>
					</ol>
					</div>
					<hr />
					<div class="checkbox-group fs-left">
						<strong>Advanced mode</strong>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'    => 'radio',
										'options' => array(

											array(
												'name'    => 'advancedMode[status]',
												'value'   => 'yes',
												'checked' => ( isset( $advanced_mode->status )
															&& 'yes' === esc_attr( $advanced_mode->status )
															? true
															: false ),
												'id'      => 'fs-enable-advanced-mode-yes',
												'label'   => 'Enable',
											),

											array(
												'name'    => 'advancedMode[status]',
												'value'   => 'no',
												'checked' => ( ! isset( $advanced_mode->status )
															|| 'no' === esc_attr( $advanced_mode->status )
															? true
															: false ),
												'id'      => 'fs-enable-advanced-mode-no',
												'label'   => 'Disable',
											),

										),
									),
								)
							);
							?>
					</div>
					<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
					<div class="clear"></div>
				</form>
			</div>
		</div>
	</div>
<?php $synergy_press = $synergypress->resources->Get( 'synergypress' ); ?>
	<!-- If SynergyPress advanced mode is enabled -->
<?php if ( isset( $advanced_mode->status ) && 'yes' === $advanced_mode->status ) : ?>
	<div id="fs-options-interactions-presets" class="fs-card">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'interactions-presets' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="interactions-presets">
				<span class="screen-reader-text edit">Edit: Interactions presets</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<h4>Interaction Presets - <small class="fs-important">Advanced mode only</small></h4>
		</div>

		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'interactions-presets' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<div class="tab-container">
				<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=interactions-presets', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
					<input type="hidden" name="action" value="synergy-press-update-action">
					<input type="hidden" name="synergy-press-update-action" value="synergypress">
					<div>
						<p class="fs-item-description">
							Unlike default mode, in advanced mode, we can implement interactions directly into the source code or within the post contents. This option will stop SynergyPress from automatically implementing interaction presets.
						</p>
						<p class="fs-item-description">
							Alternatively, automatic implementation of presets can be prevented within each interaction. 
							<a 
								href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages', 'form_synergy_update_options', '_wpnonce' ) ); ?>">
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
						</p>
					</div>
					<hr />
					<div class="fs-form-group">
						<strong>Autoload triggers and parameters</strong>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'    => 'radio',
										'options' => array(

											array(
												'name'    => 'synergypress[autoload][params]',
												'value'   => 'yes',
												'checked' => ( isset( $synergy_press->params )
															&& 'yes' === $synergy_press->params
															? true
															: false ),
												'id'      => 'fs-load-params-yes',
												'label'   => 'Allow',
											),

											array(
												'name'    => 'synergypress[autoload][params]',
												'value'   => 'no',
												'checked' => ( ! isset( $synergy_press->params )
															|| 'no' === $synergy_press->params
															? true
															: false ),
												'id'      => 'fs-load-params-no',
												'label'   => 'Prevent',
											),

										),
									),
								)
							);
						?>
					</div>
					<div class="fs-form-group mt-3">
						<strong>Autoload display and placement options</strong>
						<p class="description">If you choose to prevent placement options from loading, unless provided in the post content or HTML, the default placement options will be applied to each interaction.</p>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'    => 'radio',
										'options' => array(

											array(
												'name'    => 'synergypress[autoload][options]',
												'value'   => 'yes',
												'checked' => ( isset( $synergy_press->options )
															&& 'yes' === $synergy_press->options
															? true
															: false ),
												'id'      => 'fs-load-options-yes',
												'label'   => 'Allow',
											),

											array(
												'name'    => 'synergypress[autoload][options]',
												'value'   => 'no',
												'checked' => ( ! isset( $synergy_press->options )
															|| 'no' === $synergy_press->options
															? true
															: false ),
												'id'      => 'fs-load-options-no',
												'label'   => 'Prevent',
											),

										),
									),
								)
							);
						?>
					</div>
					<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
					<div class="clear"></div>
					</form>
				</div>
			</div>
	</div>
<?php endif; ?>
	<!-- SynergyPress interactions offset -->
	<div id="fs-options-interaction-offset" class="fs-card">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'interaction-offset' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="interaction-offset">
				<span class="screen-reader-text edit">Edit: Interaction offset</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<h4>Interaction Offset</h4>
		</div>

		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'interaction-offset' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<div class="tab-container">
				<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=interaction-offset', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
					<input type="hidden" name="action" value="synergy-press-update-action">
					<input type="hidden" name="synergy-press-update-action" value="website">
					<div>
						<p class="fs-item-description">
							In certain cases, CSS style sheets may affect the positioning of interactions! Causing them to shift from their intended position!
						</p>
						<p class="fs-item-description">
							A simple offset can fix the problem on all interactions. For better placement accuracy, each interaction can be independently manipulated  using data-attributes
							<a 
								href="<?php echo esc_url( 'https://formsynergy.com/documentation/options-and-parameters/#fs-opt-offset' ); ?>" 
								target="_blank"
								title="This link will open a page from FormSynergy.com using a new tab">
									Read more
							</a>
						</p>
					</div>
					<hr />
					<div class="fs-form-group w-50">
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label'  => true,
										'type'        => 'number',
										'name'        => 'website[offset][up]',
										'id'          => 'fs-interaction-offset-top',
										'label'       => 'Move upwards',
										'description' => 'Shift interactions towards the bottom (.px)',
									),
								),
								isset( $website->offset->up )
								? esc_attr( $website->offset->up )
								: 0
							);
							?>
					</div>
					<div class="fs-form-group w-50">
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label'  => true,
										'type'        => 'number',
										'name'        => 'website[offset][right]',
										'id'          => 'fs-interaction-offset-right',
										'label'       => 'Move towards the right',
										'description' => 'Shift interactions towards the left (.px)',
									),
								),
								isset( $website->offset->right )
								? esc_attr( $website->offset->right )
								: 0
							);
							?>
					</div>
					<div class="fs-form-group w-50">
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label'  => true,
										'type'        => 'number',
										'name'        => 'website[offset][down]',
										'id'          => 'fs-interaction-offset-bottom',
										'label'       => 'Move downards',
										'description' => 'Shift interactions towards the top (.px)',
									),
								),
								isset( $website->offset->down )
								? esc_attr( $website->offset->down )
								: 0
							);
							?>
					</div>
					<div class="fs-form-group w-50">
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label'  => true,
										'type'        => 'number',
										'name'        => 'website[offset][left]',
										'id'          => 'fs-interaction-offset-left',
										'label'       => 'Move towards the left',
										'description' => 'Shift interactions towards the right (.px)',
									),
								),
								isset( $website->offset->left )
								? esc_attr( $website->offset->left )
								: 0
							);
							?>
					</div>    
					<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
					<div class="clear"></div>
				</form>
			</div>
		</div>
	</div>
	<div id="fs-options-synergypress-api-debug" class="fs-card">
		<div class="fs-sub-headings">
			<button type="button" class="fs-toggler"
				aria-expanded="<?php echo( isset( $get_request['card'] ) && 'synergypress-api-debug' === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
				data-package-action="synergypress-api-debug">
				<span class="screen-reader-text edit">Edit: Form Synergy Responses</span>
				<span class="dashicons dashicons-arrow-down"></span>
			</button>
			<h4>Troubleshooting</h4>
		</div>

		<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'synergypress-api-debug' === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
			<div class="tab-container">
				<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=synergypress-api-debug', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
					<input type="hidden" name="action" value="synergy-press-update-action">
					<input type="hidden" name="synergy-press-update-action" value="synergypress">
					<div class="checkbox-group">
						<strong>Enable API messages</strong>
						<p class="description">Will display messages returned by the Form Synergy API server.</p>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label' => true,
										'type'       => 'radio',
										'options'    => array(
											array(
												'id'      => 'fs-api-debug-enable-yes',
												'checked' => ( isset( $synergy_press->synergypress_api_debug )
																&& 'yes' === $synergy_press->synergypress_api_debug
																? true
																: false ),

												'value'   => 'yes',
												'label'   => 'Yes',
												'name'    => 'synergypress[synergypress_api_debug]',
												'data'    => array(
													'synergypress' => 'auto-update',
												),
											),
											array(
												'id'      => 'fs-api-debug-enable-no',
												'checked' => ( ! isset( $synergy_press->synergypress_api_debug )
																|| 'no' === $synergy_press->synergypress_api_debug
																? true
																: false ),

												'value'   => 'no',
												'label'   => 'No',
												'name'    => 'synergypress[synergypress_api_debug]',
												'data'    => array(
													'synergypress' => 'auto-update',
												),
											),
										),
									),
								)
							);
							?>
					</div>
				</form>
			</div>
			<hr />
			<div class="tab-container">
				<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=synergypress-api-debug', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
					<input type="hidden" name="action" value="synergy-press-update-action">
					<input type="hidden" name="synergy-press-update-action" value="localize">
					<div class="checkbox-group max-w-50">
					<strong class="fs-large">Debug localization</strong>
					<p class="description">This option will force API requests to use WordPress resources. </p>
						<?php
						$localization = $synergypress->resources->Get( 'localization' );
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'show-label' => true,
									'type'       => 'radio',
									'options'    => array(
										array(
											'id'      => 'fs-debug-localization-yes',

											'value'   => 'yes',
											'checked' => ( isset( $localization->debug )
															&& 'yes' === $localization->debug
															? true
															: false ),
											'label'   => 'Yes',
											'name'    => 'localize[debug]',
											'data'    => array(
												'synergypress' => 'auto-update',
											),
										),
										array(
											'id'      => 'fs-debug-localization-no',

											'value'   => 'no',
											'checked' => ( ! isset( $localization->debug )
															|| 'no' === $localization->debug
															? true
															: false ),
											'label'   => 'No',
											'name'    => 'localize[debug]',
											'data'    => array(
												'synergypress' => 'auto-update',
											),
										),
									),
								),
							)
						);
						?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
