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
$get_request = synergypress_get_request();
$modules     = $synergypress->resources->Get( 'modules' );

$console  = 'https://formsynergy.com/console/website/?';
$console .= 'web=' . esc_attr( $config->siteid );
if( isset( $strategy->modid ) ) :
	$console .= '&modid=' . esc_attr( $strategy->modid );
endif;

if ( ! $modules ) : ?>

	<div class="fs-body">
		<div class="fs-content">
			<p class="fs-description">
				It appears that your strategy has no modules ready for interactions! 
				Please visit the <a href="<?php echo esc_url( $console ); ?>" target="_blank">console</a> to create new modules.
			</p>
		</div>
	</div>

	<?php
	return;
	endif;
foreach ( $modules as $each_module ) :
	$module = $synergypress->resources->Get(
		$synergypress->plugin->camel_case( esc_attr( $each_module->name ) )
	);
	if ( ! $module ) :
		return;
	endif;
	?>
		<div id="fs-<?php echo esc_attr( $module->name ); ?>" class="fs-card">
			<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-' . sanitize_title( $module->name ), 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
				<input type="hidden" name="action" value="synergy-press-update-action">
				<input type="hidden" name="synergy-press-update-action" value="modules">
				<input type="hidden" name="modules[<?php echo esc_attr( $synergypress->plugin->camel_case( $module->name ) ); ?>][initiator]" value="advancedmode">
				<div class="fs-sub-headings">
					<button type="button" class="fs-toggler"
						aria-expanded="<?php echo( isset( $get_request['card'] ) && 'fs-' . esc_attr( $synergypress->validate->sanitize_title( $module->name ) ) === sanitize_text_field( $get_request['card'] ) ? 'true' : 'false' ); ?>"
						data-package-action="modules">
						<span class="screen-reader-text edit">Edit:  <?php echo esc_attr( $module->name ); ?></span>
						<span class="dashicons dashicons-arrow-down"></span>
					</button>
					<h4>
						<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
					<?php echo esc_attr( $module->name ); ?>
					</h4>
				</div>
				<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'fs-' . esc_attr( $synergypress->validate->sanitize_title( $module->name ) ) === sanitize_text_field( $get_request['card'] ) ? '' : 'close' ); ?>">
					<?php if ( isset( $module->description ) ) : ?>
						<p class="fs-description"> <?php echo esc_html( $module->description ); ?> </p>
						<hr class="fs-hr" />
					<?php endif; ?>
					<div id="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-settings" data-tab-group="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-settings"
						class="tab-container fs-tab-content fs-tab-active">
						<h3 class="nav-tab-wrapper">
							<a href="#<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-class-name" class="nav-tab nav-tab-active refresh-tips-helper" data-fs-related="#fs-quick-tips-interactions-btn"> Reference ClassNames </a>
							<a href="#<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-params" class="nav-tab refresh-codemirror show-how-tips" data-fs-related="#fs-quick-tips-parameters-btn"> Interaction Parameters </a>
							<a href="#<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-options" class="nav-tab refresh-codemirror" data-fs-related="#fs-quick-tips-placement-btn"> Display Options </a>
						</h3>
						<div class="fs-tabs">
							<div class="fs-tab-content fs-tab-active border bg-light" id="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-class-name">
								<div class="fs-body">
									<input type="hidden" name="modules[<?php echo esc_attr( $synergypress->plugin->camel_case( $module->name ) ); ?>][fstrigger]" value=".fs-trigger-<?php echo esc_attr( $module->moduleid ); ?>">
									<input type="hidden" name="modules[<?php echo esc_attr( $synergypress->plugin->camel_case( $module->name ) ); ?>][fsplacement]" value=".fs-placement-<?php echo esc_attr( $module->moduleid ); ?>">
								<?php if ( ! isset( $module->autoload->params ) || 'no' !== $module->autoload->params ) : ?>
										<div class="fs-item-description">Trigger this interaction by adding this class to the appropriate element.</div>
										<input type="text" value="fs-trigger-<?php echo esc_attr( $module->moduleid ); ?>" class="fs-code widefat fs-tips-trigger-class" readonly> 
									<?php endif; ?>
								<?php if ( ! isset( $module->autoload->options ) || 'no' !== $module->autoload->options ) : ?>
										<div class="fs-item-description">This interaction will assume the position of the element containing this class.</div> 
										<input type="text" value="fs-placement-<?php echo esc_attr( $module->moduleid ); ?>" class="fs-code widefat fs-tips-placement-class" readonly>
									<?php endif; ?>
								</div>
							</div>
						<?php
							$params = array(
								'etag'   => 'click:' . esc_attr( $synergypress->validate->sanitize_title( $module->name ) ),
								'params' => array(
									'trigger' => array(
										'moduleid' => esc_attr( $module->moduleid ),
									),
								),
							);

							if ( isset( $module->params ) ) :
								$params = $module->params;
						endif;

							$options = array(
								'el'  => '@' . esc_attr( $module->moduleid ),
								'opt' => array(
									'display'   => 'fixed',
									'placement' => 'centered',
									'size'      => 'lg',
									'theme'     => 'white',
								),
							);

							if ( isset( $module->options ) ) :
								$options = $module->options;
						endif;
							?>
							<div class="fs-tab-content border" id="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-params">
								<textarea 
									class="fs-code codearea" 
									name="modules[<?php echo esc_attr( $synergypress->plugin->camel_case( $module->name ) ); ?>][params]"><?php echo wp_json_encode( $params, JSON_PRETTY_PRINT ); ?></textarea>
								<hr />
								<div class="fs-form-group">
									<strong>Autoload trigger and parameters</strong>
										<?php
										echo $synergypress->plugin->render(
											array(
												'input' => array(
													'type' => 'radio',
													'options' => array(
														array(
															'name'  => 'modules[' . esc_attr( $synergypress->plugin->camel_case( $module->name ) ) . '][autoload][params]',
															'value' => 'yes',
															'id'   => 'fs-load-params-yes',
															'label' => 'Autoload',
															'description' => 'Will automatically query for the reference class name and apply the parameters.',
															'checked' => ( ! isset( $module->autoload->params )
																			|| 'yes' === $module->autoload->params
																			? true
																			: false ),
														),
														array(
															'name' => 'modules[' . esc_attr( $synergypress->plugin->camel_case( $module->name ) ) . '][autoload][params]',
															'value' => 'no',
															'id'   => 'fs-load-params-no',
															'label' => 'Prevent',
															'description' => 'Parameters must be applied manually.',
															'checked' => ( isset( $module->autoload->params )
																			&& 'no' === $module->autoload->params
																			? true
																			: false ),
														),
													),
												),
											)
										);
										?>
									<div 
										id="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-parameter-options" 
										class="tab-container fs-tab-content"
										data-tab-group="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-parameter-options">
										<div class="fs-item-description">Trigger this interaction by adding this class to the appropriate element.</div>
										<input type="text" value="fs-trigger-<?php echo esc_attr( $module->moduleid ); ?>" class="fs-code widefat fs-tips-trigger-class" readonly> 
									</div>
									</div>
							</div>
							<div class="fs-tab-content border" id="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-options">
								<textarea 
									class="fs-code codearea" 
									name="modules[<?php echo esc_attr( $synergypress->plugin->camel_case( $module->name ) ); ?>][options]"><?php echo wp_json_encode( $options, JSON_PRETTY_PRINT ); ?></textarea>
									<hr />
									<div class="fs-form-group mt-3">
										<strong>Placement & options</strong>
										<?php
										echo $synergypress->plugin->render(
											array(
												'input' => array(
													'type' => 'radio',
													'options' => array(
														array(
															'name' => 'modules[' . esc_attr( $synergypress->plugin->camel_case( $module->name ) ) . '][autoload][options]',
															'value' => 'yes',
															'id'   => 'fs-load-options-yes',
															'label' => 'Autoload',
															'description' => 'Will automatically query for the reference class name and apply display options.',
															'checked' => ( ! isset( $module->autoload->options )
																			|| 'yes' === $module->autoload->options
																			? true
																			: false ),
														),
														array(
															'name' => 'modules[' . esc_attr( $synergypress->plugin->camel_case( $module->name ) ) . '][autoload][options]',
															'value' => 'no',
															'id'   => 'fs-load-options-no',
															'label' => 'Prevent',
															'description' => 'Display options must be applied manually.',
															'checked' => ( isset( $module->autoload->options )
																			&& 'no' === $module->autoload->options
																			? true
																			: false ),
														),
													),
												),
											)
										);
										?>
									<div 
										id="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-placement-options" 
										class="tab-container fs-tab-content"
										data-tab-group="<?php echo esc_attr( $synergypress->validate->sanitize_title( $module->name ) ); ?>-placement-options">
										<div class="fs-item-description">This interaction will assume the position of the element containing this class.</div> 
										<input type="text" value="fs-placement-<?php echo esc_attr( $module->moduleid ); ?>" class="fs-code widefat fs-tips-placement-class" readonly>
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<div class="mt-3">
								<button type="submit" class="button button-primary button-large fs-right"> Update </button>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<?php
	endforeach;

