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
 * Form Synergy plugin options: API Configuration.
 */
?>
<form action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&tab=api-configuration', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="synergy-press-update-action">
	<input type="hidden" name="synergy-press-update-action" value="config">
	<div id="synergy-press-api-configuration" class="tab-content w-50">
		<div class="fs-headings">
			<?php
			if ( isset( $config->profileid, $config->apikey, $config->secretkey, $website->siteid ) ) :
				?>
				<strong><span class="dashicons dashicons-yes fs-icon-lg fs-success"></span> All done</strong> 
			<?php else : ?>
				<strong>API Configuration</strong> 
			<?php endif; ?>
		</div>
		<hr class="wp-header-end">
		<table class="form-table">
			<tbody>
				<tr scope="row">
					<th scope="row">
						Profile ID
					</th>
					<td>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'        => 'text',
										'id'          => 'profileid',
										'label'       => 'Profile ID',
										'name'        => 'config[profileid]',
										'status'      => 'required',
										'description' => 'The profile id can be retrieved by accessing the profile page in the FS Console',
									),
								),
								isset( $config->profileid )
								? esc_attr( $config->profileid )
								: ''
							);
							?>
					</td>
				</tr>
				<tr scope="row">
					<th scope="row">
						API Key
					</th>
					<td>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'        => 'text',
										'id'          => 'apikey',
										'label'       => 'API Key',
										'name'        => 'config[apikey]',
										'status'      => 'required',
										'description' => 'The API key can be retrieved by accessing the api access page in the FS Console',
									),
								),
								isset( $config->apikey )
								? esc_attr( $config->apikey )
								: ''
							);
							?>
					</td>
				</tr>
				<tr scope="row">
					<th scope="row">
						Secret Key
					</th>
					<td>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'        => 'text',
										'id'          => 'secretkey',
										'label'       => 'Secret Key',
										'name'        => 'config[secretkey]',
										'status'      => 'required',
										'description' => 'The secret key can be retrieved by accessing the api access page in the FS Console',
									),
								),
								isset( $config->secretkey )
								? esc_attr( $config->secretkey )
								: ''
							);
							?>
					</td>
				</tr>
				<tr scope="row">
					<th scope="row">
						Site ID
					</th>
					<td>
						<?php
							echo $synergypress->plugin->render(
								array(
									'input' => array(
										'type'        => 'text',
										'id'          => 'siteid',
										'label'       => 'Site ID',
										'name'        => 'config[siteid]',
										'description' => 'The site id will populate automatically once a site is verified.',
									),
								),
								isset( $config->siteid )
								? esc_attr( $config->siteid )
								: ''
							);
							?>
					</td>
				</tr>
			</tbody>
		</table>
		<button type="submit" class="button button-primary button-large fs-button-right">Update</button>
	</div>
</form>
