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
 * Form Synergy plugin options: Package configuration.
 */
$advanced_mode = $synergypress->resources->Get( 'advancedMode' );

$default_mode = $advanced_mode && 'yes' === $advanced_mode->status
	? false
	: true;

$action_type = ! $default_mode
	? 'update_id'
	: 'default';

$get_request = synergypress_get_request();

if ( ! isset( $config->profileid, $config->apikey, $config->secretkey ) ) : ?>
<div class="fs-headings">
	<div class="tab-content">
		<h3> Packages </h3>
		<p class="description">
			You must complete all required fields under the API configuration tab.
		</p>
	</div>
</div>
<?php elseif ( ! $config->siteid ) : ?>
<div class="fs-headings">
	<div class="tab-content">
		<h3> Packages </h3>
		<p class="description">
			You must register and verify your website under the site configuration tab.
		</p>
	</div>
</div>
<?php else : ?>
<div id="fs-strategy" class="fs-card">
	<div class="fs-sub-headings">
		<button type="button" class="fs-toggler"
			aria-expanded="<?php echo( isset( $get_request['card'] ) && 'strategy' === sanitize_text_field( $get_request['card'] ) || ! $strategy || ! is_object( $strategy ) ? 'true' : 'false' ); ?>"
			data-package-action="strategy">
			<span class="screen-reader-text edit">Edit: Strategy</span>
			<span class="dashicons dashicons-arrow-down"></span>
		</button>
		<h4>
		<?php if ( $strategy && is_object( $strategy ) ) : ?>
			<span class="dashicons dashicons-yes fs-icon-lg fs-success"></span>
		<?php endif; ?> Strategy</h4>
	</div>
	<div class="fs-toggle fs-body <?php echo( isset( $get_request['card'] ) && 'strategy' === sanitize_text_field( $get_request['card'] ) || ! $strategy || ! is_object( $strategy ) ? '' : 'close' ); ?>">

		<div class="fs-row">
			<div id="fs-modules" class="fs-col-left">
			<div class="fs-content">
			<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=packages&card=fs-strategy', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
				<input type="hidden" name="action" value="synergy-press-update-action">
				<input type="hidden" name="synergy-press-update-action" value="strategy">
				<input type="hidden" name="strategy[actiontype]" value="<?php echo esc_attr( $action_type ); ?>">
				<table class="form-table">
					<tbody>
						<tr scope="row">
							<th scope="col">
								Label
							</th>
							<td>
								<?php
									echo $synergypress->plugin->render(
										array(
											'input' => array(
												'type'     => 'text',
												'id'       => 'fs-strategy-name',
												'label'    => 'Label',
												'name'     => 'strategy[name]',
												'status'   => 'required',
												'description' => 'Strategy label, name or alias, this field is not visible to the public.',
												'readonly' => false,
											),
										),
										isset( $strategy->name )
										? esc_attr( $strategy->name )
										: ''
									);
								?>
							</td>
						</tr>
						<tr scope="row">
							<th scope="col">
								Strategy ID
							</th>
							<td>
								<?php
									echo $synergypress->plugin->render(
										array(
											'input' => array(
												'type'     => 'text',
												'id'       => 'fs-strategy-id',
												'label'    => 'Strategy ID',
												'name'     => 'strategy[modid]',
												'status'   => ( $default_mode
																? 'readonly'
																: 'required' ),
												'description' => ( $default_mode
																? 'The strategy id field is automatically populated once the strategy is created.'
																: 'In advanced mode you will need to provide the strategy id, (modid)' ),
												'readonly' => false,
											),
										),
										isset( $strategy->modid )
										? esc_attr( $strategy->modid )
										: ''
									);
								?>
							</td>
						</tr>
					</tbody>
				</table>
				<button type="submit" id="btn-update-strategy" class="button button-primary button-large fs-button-right mr-3">
					<?php
					echo ( $default_mode && ! $strategy
							? 'Create'
							: 'Update' );
					?>
				</button>
			</form>
		</div></div>
				<div id="fs-strategy-quique-tips-tab" class="fs-col-right">
					<div class="fs-content">
						<div id="fs-strategy-help-sub-tab">
							<div class="fs-sub-tab-headings">
								<button type="button" class="fs-toggler" aria-expanded="false">
									<span class="screen-reader-text edit">Show: More help</span>
									<span class="dashicons dashicons-arrow-down"></span>
								</button>
								<h4><span class="dashicons dashicons-info"></span> Quick tips</h4>
							</div>
							<div class="fs-toggle fs-body close"> 
								<?php if ( ! $default_mode ) : ?>
									<p class="fs-description pl-3"> 
										When using this plugin in advanced mode, a strategy including it's modules will be automatically imported, therefore must already exist. 
									</p>
									<p class="fs-description pl-3"> 
										On the other hand this plugin can create a strategy for you, along with objectives and interaction modules. 
									</p>
									<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=advanced-mode', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="btn-fix-it">Switch modes</a>
									<p class="fs-description pl-3 pt-3">
										Use the following methods to create a strategy while remaining in advanced mode:
									</p> 
									<ul class="fs-list-description-blank mt-1">
										<li class="mb-1">
											<strong><a 
												class="fs-btn-icon" 
												href="<?php echo esc_url( 'https://formsynergy.com/console/' ); ?>" 
												target="_blank" title="Form Synergy Console">
													Form Synergy console 
											</a></strong>
										</li>
										<li class="mb-1">
										<strong>API Client</strong>
											<ul class="pl-2">
												<li>
													<a 
														class="fs-btn-icon" 
														href="<?php echo esc_url( 'https://formsynergy.com/documentation/strategies/' ); ?>" 
														target="_blank" 
														title="GitHub Repository">
															Creating a strategy
													</a>
												</li>
												<li>
													<a 
														class="fs-btn-icon" 
														href="<?php echo esc_url( 'https://formsynergy.com/documentation/modules/' ); ?>" 
														target="_blank" 
														title="GitHub Repository">
															Creating modules
													</a>
												</li>
												<li>
													<a 
														class="fs-btn-icon" 
														href="<?php echo esc_url( 'https://formsynergy.com/documentation/objectives/' ); ?>" 
														target="_blank" 
														title="GitHub Repository">
															Creating an objective
													</a>
												</li>
										</ul>
										</li>
									</ul>
								<?php elseif ( $default_mode && ! $strategy ) : ?>
									<p class="fs-description pl-3"> 
										To create your first strategy using this plugin, simply click on the create button. 
									</p>
								<?php elseif ( ! $default_mode && ! $strategy ) : ?>
									<p class="fs-description pl-3"> 
										To create your first strategy using this plugin, you must first disable advanced mode. 
										<a 
											href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=plugin-options&card=advanced-mode', 'form_synergy_update_options', '_wpnonce' ) ); ?>" 
											class="button btn-fix-it">
											Plugin options
										</a>
									</p>
								<?php elseif ( $strategy ) : ?>
								<p class="fs-description pl-3"> 
									<span class="dashicons dashicons-yes fs-success"></span> Your strategy is loaded. 
								</p>
								<?php endif; ?>
							</div>
						</div>
			<div id="fs-strategy-help-sub-tab">
				<div class="fs-sub-tab-headings">
					<button type="button" class="fs-toggler" aria-expanded="false">
						<span class="screen-reader-text edit">Show: Retrieve the strategy ID</span>
						<span class="dashicons dashicons-arrow-down"></span>
					</button>
					<h4><span class="dashicons dashicons-info"></span> Retrieve the strategy ID</h4>
				</div>
				<div class="fs-toggle fs-body close"> 
					<ol class="fs-features-list">
						<li>Retrieve the strategy ID in question. 
							<ul class="fs-list-description-blank">
								<li class="mb-1">
									<span class="dashicons dashicons-arrow-right"></span> 
									<strong>Using the API: </strong> When a strategy is created, the strategy ID can be found within the response.
								</li>
								<li class="mb-1">
									<span class="dashicons dashicons-arrow-right"></span> 
									<span class="fs-bold">Using the Console:</span> Enter the website / domain you whish to administer, click to manage the strategy in question, select the strategy tab in order to reveal it's contents   
									<a 
										class="fs-btn-icon"
										href="<?php echo esc_url( 'https://formsynergy.com/console/' ); ?>" 
										target="_blank"
										title="Open the Form Synergy Console in a new tab"> <span class="dashicons dashicons-external"></span> </a>
								</li>
							</ul>

						</li>
						<li class="mb-1">Next, add the strategy ID in appropriate field and click update. </li>
						<li class="mb-1">During the update process, the plugin will import all modules available in that strategy.</li>
					</ol>
				</div>
			</div>
		</div>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div id="default-packages-panel">
	<div class="fs-row">
		<div id="fs-modules" class="fs-card fs-col-left">
			<div class="fs-sub-headings">
				<button type="button" class="fs-toggler" aria-expanded="true" data-package-action="modules" <?php echo ( ! isset( $strategy->modid ) ? 'disabled' : '' ); ?>>
					<span class="screen-reader-text edit">Show: Modules</span>
					<span class="dashicons dashicons-arrow-down"></span>
				</button>
				<h4>Modules</h4>
			</div>
			<div class="fs-toggle fs-body">
				<?php if ( isset( $strategy->modid ) && ! $default_mode ) : ?>
				<div class="fs-content mb-3 underline"> 
					<p class="fs-description fs-left fs-strong">Click to update strategy and synchronize modules  </p>
					<a href="#" id="synchronize-modules" class="fs-right pointer"><span class="dashicons dashicons-update fs-icon-lg"></span></a>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
				<?php
				if ( $default_mode ) :
					foreach ( $packages as $package ) :
						if ( file_exists( plugin_dir_path( __FILE__ ) . 'modules/' . $package . '.php' ) ) {
							include_once plugin_dir_path( __FILE__ ) . 'modules/' . $package . '.php';
						}
					endforeach;

					else :
						if ( file_exists( plugin_dir_path( __FILE__ ) . 'modules/advanced-mode.php' ) ) :
							include_once plugin_dir_path( __FILE__ ) . 'modules/advanced-mode.php';
						endif;
				endif;
					?>
				<!-- ./ Modules -->
			</div>
		</div>
		<?php if ( ! $default_mode ) : ?>
		<div id="fs-advanced-tabs" class="fs-card fs-col-right">
			<div class="fs-toggle fs-body">
				<?php include_once plugin_dir_path( __FILE__ ) . 'advanced-tips.php'; ?>
			</div>
		</div>
			<?php
		endif;
		if ( $default_mode ) :
			?>
		<div id="fs-objectives" class="fs-card fs-col-right">
			<div class="fs-sub-headings">
				<button type="button" class="fs-toggler" aria-expanded="true" data-package-action="objectives">
					<span class="screen-reader-text edit">Edit: Objectives</span>
					<span class="dashicons dashicons-arrow-down"></span>
				</button>
				<h4>Objectives</h4>
			</div>
			<div class="fs-toggle fs-body">
				<!-- Objectives -->
				<?php
				foreach ( $objectives as $objective ) :
					if ( file_exists( plugin_dir_path( __FILE__ ) . 'objectives/' . $objective . '.php' ) ) :
						include_once plugin_dir_path( __FILE__ ) . 'objectives/' . $objective . '.php';
						endif;
					endforeach;
				?>
				<!-- ./ Objectives -->
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
	<?php
endif;

