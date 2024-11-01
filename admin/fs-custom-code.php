<?php
/**
 * Created on Tue April 30 2019
 *
 * @link    https://formsynergy.com/synergypress-wordpress-plugin/
 * @version 1.6.0
 * @since   1.0
 * @package synergy-press
 */

// Make sure we don't expose any info if called directly.
if ( ! defined( 'SYNERGY_PRESS' ) ) :
	return;
endif;

/**
 * Synergy Press Custom code.
 */
global $synergypress;
$custom_code          = false;
$custom_code          = $synergypress->resources->Get( 'customCode' );
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:bottom;" viewBox="0 0 960 960" height="24px"
			width="24px" x="0px" y="0px">
			<g>
				<g>
					<path opacity="0.7" fill="#010101" enable-background="new"
						d="M703.1,241.8c-60.3-56.5-138.6-87.5-221.7-87.4 c-149.5,0.1-278.6,102.6-314.2,244.9c-2.6,10.3-11.8,17.7-22.5,17.7H34.1c-14.5,0-25.5-13.2-22.8-27.3C53.1,167.8,247.8,0,481.7,0 c128.2,0,244.7,50.4,330.7,132.6l68.9-68.9c29.2-29.2,79.1-8.5,79.1,32.8v258.7c0,25.6-20.7,46.3-46.3,46.3 M260.2,715.6 C320.6,772,398.9,803,482,802.9c149.4-0.1,278.5-102.6,314.2-244.8c2.6-10.3,11.8-17.7,22.5-17.7h110.6 c14.5,0,25.4,13.1,22.8,27.4c-41.8,221.7-236.5,389.5-470.4,389.5c-128.3,0-244.7-50.4-330.7-132.5l-68.9,68.9 C52.9,922.9,3,902.3,3,860.9V602.2c0-25.6,20.7-46.3,46.3-46.3" />
				</g>
			</g>
			<path fill="#ACD147"
				d="M544,382.2V273.1c0-17.2,14-31.2,31.2-31.2s31.2,14,31.2,31.2v109.1H544z M653.1,397.8H310.2 c-8.6,0-15.6,7-15.6,15.6v31.2c0,8.6,7,15.6,15.6,15.6h15.6v31.2c0,75.4,53.5,138.3,124.7,152.7v96.6h62.3V644 c71.1-14.4,124.7-77.3,124.7-152.7v-31.2h15.6c8.6,0,15.6-7,15.6-15.6v-31.2C668.7,404.8,661.7,397.8,653.1,397.8z M419.3,382.2 V273.1c0-17.2-14-31.2-31.2-31.2c-17.2,0-31.2,14-31.2,31.2v109.1H419.3z" />
		</svg>
		<strong class="fs-logo">
			<span class="fs-logo-1">Synergy</span><span class="fs-logo-2">Press</span>
		</strong>
	</h1>

	<hr class="wp-header-end">
	<div id="synergy-press">
		<div class="fs-headings">
			<h3 class="fs-danger"> JavaScript Only</h3>
			<p class="description">
				Keep events and methods in one place.  
			</p>
		</div>
		<hr class="fs-hr" />
		<div id="fs-custom-code-area">
		<form 
			action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
				<input type="hidden" name="action" value="synergy-press-update-action">
				<input type="hidden" name="synergy-press-update-action" value="customCode">
		<textarea 
			class="fs-custom-code custom-codearea"
			name="customCode[javascript]" id="customCode-javaScript"><?php
			if ( $custom_code->javascript ) :
				$java_script_code = stripslashes( $custom_code->javascript );
				$java_script_code = html_entity_decode( $java_script_code );
				echo $java_script_code;
			endif;
			?></textarea>
			<div class="mt-3">
				<button type="submit" class="button button-primary button-large fs-right"> Update </button>
			</div>
		</form>
		</div>
	</div>
</div>
