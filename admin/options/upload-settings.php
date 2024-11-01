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

<div id="synergy-press">
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&iaa=interactive-account-access', 'form_synergy_update_options', '_wpnonce' ) ); ?>" class="nav-tab"> <span class="dashicons dashicons-arrow-left-alt2" style="vertical-align:sub;" ></span> Back </a>
	</h2>
	<div class="tab-container">
		<h3>Upload Settings</h3>
		<p class="pb-2">The Synergy Press settings file can be downloaded from the Form Synergy Console.  
			<a class="fs-btn-icon"
				href="<?php echo esc_url( 'https://formsynergy.com/console/' ); ?>"
				target="_blank" 
				title="Open the Form Synergy Console in a new tab"> 
				<span class="dashicons dashicons-external"></span> </a>
		</p>
		<form enctype="multipart/form-data" action="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&iaa=upload-settings', 'form_synergy_update_options', '_wpnonce' ) ); ?>" method="POST">
			<input type="hidden" name="action" value="synergy-press-update-action">
			<input type="hidden" name="synergy-press-update-action" value="upload">
			<label class="screen-reader-text" for="upload-synergy-press-settings">Synergy Press Settings (json)</label>
			<input type="file" id="upload-synergy-press-settings">
			<input type="hidden" id="upload-synergy-press-domain" name="upload[settings][domain]" value=''>
			<input type="hidden" id="upload-synergy-press-name" name="upload[settings][name]" value=''>
			<input type="hidden" id="upload-synergy-press-verified" name="upload[settings][verified]" value=''>
			<input type="hidden" id="upload-synergy-press-indexpage" name="upload[settings][indexpage]" value=''>
			<input type="hidden" id="upload-synergy-press-proto" name="upload[settings][proto]" value=''>
			<input type="hidden" id="upload-synergy-press-profileid" name="upload[settings][profileid]" value=''>
			<input type="hidden" id="upload-synergy-press-siteid" name="upload[settings][siteid]" value=''>
			<input type="hidden" id="upload-synergy-press-apikey" name="upload[settings][apikey]" value=''>
			<input type="hidden" id="upload-synergy-press-secretkey" name="upload[settings][secretkey]" value=''>
			<button type="submit" id="submit-synergy-press-settings" class="button" disabled=""> Import </button>	
		</form>
</div>
