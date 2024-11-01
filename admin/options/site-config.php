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
 * Form Synergy plugin options: Site configuration.
 */
?>
<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=site-config', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="synergy-press-update-action">
	<input type="hidden" name="synergy-press-update-action" value="website">
	<div id="synergy-press-api-configuration" class="tab-content w-50">
		<div class="fs-headings">
			<?php if ( isset( $website->verified ) && 'yes' === $website->verified ) : ?>
				<strong><span class="dashicons dashicons-yes fs-icon-lg fs-success"></span> All done</strong>
			<?php else : ?>
				<strong>Site Configuration</strong>
			<?php endif; ?>
			<?php if ( ! isset( $config->profileid, $config->apikey, $config->secretkey ) ) : ?>
			<p class="description">
				You must complete all required fields under the API configuration tab.
			</p>
			<?php else : ?>
			<p class="description">The fields below where populated based on the WP domain configuration, please make
				any necessary changes before registering your site.</p>
		</div>
		<hr class="wp-header-end">
		<table class="form-table">
			<tbody>
				<tr scope="row">
					<th scope="row">
						Label
					</th>
					<td>
						<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'type'        => 'text',
									'id'          => 'website-name',
									'label'       => 'Label',
									'name'        => 'website[name]',
									'status'      => 'required',
									'description' => 'Label, name or alias',
								),
							),
							( isset( $synergypress->request['name'] )
							? sanitize_text_field( $synergypress->request['name'] )
							: esc_attr( $site_name ) )
						);
						?>
					</td>
				</tr>
				<tr scope="row">
					<th scope="row">
						Domain Name
					</th>
					<td>
						<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'type'        => 'text',
									'id'          => 'website-domain',
									'label'       => 'Domain Name',
									'placeholder' => 'eg: ' . get_home_url(),
									'name'        => 'website[domain]',
									'status'      => 'required',
									'description' => 'Domain name where interactions will be served',
								),
							),
							( isset( $synergypress->request['domain'] )
							? $synergypress->validate->sanitize_domain( $synergypress->request['domain'] )
							: $synergypress->validate->sanitize_domain( $domain_fs ) )
						);
						?>
					</td>
				</tr>
				<tr scope="row">
					<th scope="row">
						Protocol
					</th>
					<td>
						<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'type'        => 'select',
									'id'          => 'website-proto',
									'label'       => 'Protocol',
									'name'        => 'website[proto]',
									'options'     => array(
										''         => 'Select desired protocol',
										'https' => 'Secured - https',
										'http'  => 'Unsecured - http',
									),
									'status'      => 'required',
									'description' => 'Desired protocol',
								),
							),
							( isset( $synergypress->request['proto'] )
							? esc_url( $synergypress->request['proto'] )
							: esc_url( $proto ) )
						);
						?>
					</td>
				</tr>
				<tr scope="row">
					<th scope="row">
						Index page
					</th>
					<td>
						<?php

						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'type'        => 'text',
									'id'          => 'website-indexpage',
									'label'       => 'Index page',
									'name'        => 'website[indexpage]',
									'description' => 'The home page',
								),
							),
							( isset( $synergypress->request['indexpage'] )
							? esc_url( $synergypress->request['indexpage'] )
							: isset( $website->indexpage ) )
							? esc_url( $website->indexpage )
							: '/'
						);
						?>
					</td>
				</tr>
				<th scope="row">
					Verification status
				</th>
				<td>
					<?php
						echo $synergypress->plugin->render(
							array(
								'input' => array(
									'type'    => 'checkbox',
									'options' => array(
										array(
											'name'  => 'website[register]',
											'value' => 'yes',
											'id'    => 'fs-register-website',
											'label' => isset( $website->verified )
													&& 'yes' === $website->verified
													? 'Verified'
													: 'Not verified',
										),
									),
								),
							),
							( isset( $website->verified )
							&& 'yes' === $website->verified
							? esc_attr( $website->verified )
							: 'no' )
						);
					?>
				</td>
				</tr>
			</tbody>
		</table>
		<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
		<?php endif; ?>
	</div>
</form>
