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
 * Form Synergy plugin options: Localize.
 */
?>
<div class="fs-row">
	<div class="fs-col-left">
		<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=localize', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
			<input type="hidden" name="action" value="synergy-press-update-action">
			<input type="hidden" name="synergy-press-update-action" value="localize">
			<div id="synergy-press-localize" class="tab-content w-50">
				<div class="fs-headings">
					<?php
						$localization = $synergypress->resources->Get( 'localization' );
					?>
					<h3>Localize Interactions</h3>
					<?php if ( ! isset( $config->profileid, $config->apikey, $config->secretkey ) ) : ?>
					<p class="fs-item-description fs-strong">
						You must complete all required fields under the API configuration tab.
					</p>
					<?php elseif ( ! $strategy ) : ?>
					<p class="fs-item-description fs-strong">
						No strategy specified! You must specify a strategy to localize any of it's modules.
					</p>
					<a class="button btn-fix-it"
									href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages', 'form_synergy_update_options', '_wpnonce' ) ); ?>"> 
										Strategy 
								</a>
					<?php else : ?>
					<div class="fs-item-description fs-strong">
						Please Note: When this feature depends on wp_mail.
						<?php if ( ! function_exists( 'mail' ) ) : ?>
							<p>
								<span class="text-danger">
									It seems like your server does not support php mail!
								</span>

							</p>
						<?php endif; ?>
					</div>
					<p class="fs-item-description">If connection difficulties with the Form Synergy service are encountered,
						this plugin can switch gears and function within WordPress resources by localizing FS modules, thus
						preventing the loss of any inquiries.
					</p>
				</div>
				<hr class="fs-hr" />
				<div class="checkbox-group max-w-50">
					<strong class="fs-large">Localize modules</strong>

						<?php
						$synergypress_wp_mail_error = get_transient( 'synergypress_wp_mail_error' );
						if ( $synergypress_wp_mail_error ) :
							?>

							<p class="fs-danger">Error wp_mail : <?php echo esc_attr( $synergypress_wp_mail_error ); ?></p>
							<p class="description">
								Try installing an SMTP mailer plugin 
								<a class="fs-btn-icon"
									href="<?php echo esc_url( admin_url( 'plugin-install.php?s=smtp&tab=search&type=term&s=smtp' ) ); ?>"> 
										<span class="dashicons-before dashicons-admin-plugins"></span>
										Add Plugin 
								</a>
							</p>
							<?php
						endif;
						if ( ! isset( $website->heartbeat->enable )
							|| 'yes' !== $website->heartbeat->enable ) :
							?>
							<p class="fs-danger">Form Synergy heartbeat is disabled!</p>
							<p class="description">
								The Form Synergy heartbeat must be enabled in order to use this feature.
							</p>
							<a class="button button-primary mt-2"
								href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=heartbeat', 'form_synergy_update_options', '_wpnonce' ) ); ?>"> 
									Enable 
							</a>
							<?php
						else :
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'show-label' => true,
										'type'       => 'radio',
										'options'    => array(
											array(
												'id'      => 'fs-install-localization-yes',

												'value'   => 'yes',
												'checked' => ( isset( $localization->enable )
																&& 'yes' === $localization->enable
																? true
																: false ),
												'label'   => 'Yes',
												'name'    => 'localize[enable]',
											),
											array(
												'id'      => 'fs-install-localization-no',

												'value'   => 'no',
												'checked' => ( ! isset( $localization->enable )
																|| 'no' === $localization->enable
																? true
																: false ),
												'label'   => 'No',
												'name'    => 'localize[enable]',
											),
										),
									),
								)
							);

							?>
				</div>
				<?php endif; ?>
			</div>
		<div class="clear"></div>
		<div class="tab-container fs-tab-content  w-50 <?php echo( isset( $localization->enable ) && 'yes' === $localization->enable ? 'fs-tab-active' : '' ); ?>"
			data-tab-group="localization-settings" id="localization-settings">
			<hr class="fs-hr" />
				<input type="hidden" name="action" value="synergy-press-update-action">
				<input type="hidden" name="synergy-press-update-action" value="localize">
				<div class="checkbox-group max-w-50">
					<strong class="fs-large">Debug localization</strong>
					<p class="description">This option will force requests to use WordPress resources. </p>
						<?php
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
										),
									),
								),
							)
						);
						?>
				</div>
			</div>
			<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
		</form>
	</div>
			<div class="fs-col-left">
				<div class="fs-body">
						<?php if ( $localization && isset( $localization->modules ) ) : ?>
						<div class="fs-headings">Localized modules</div>
						<ul class="fs-list-unstyled max-w-500 ml-0">
							<?php foreach ( $localization->modules as $localized ) : ?>
									<li class="underline"> 
										<span class="dashicons dashicons-yes fs-icon-sm fs-success"></span> 
										<span class="fs-main-text">
											<?php echo esc_attr( $localized->name ); ?> <em class="fs-small">Localized</em>
										</span>
									</li> 
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
