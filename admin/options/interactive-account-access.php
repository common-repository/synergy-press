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
<div id="synergy-press">
	<p>Thank you for installing the <strong>Form Synergy plugin</strong></p>
	<hr />
	<div>
		<h2>
			Initialization / Synchronization
		</h2>
		<p>
			A connection between SynergyPress and Form Synergy must be established. Please select an option to get started.
		</p>

		<div class="card">
				<h3 class="title">
					<span style="color:tomato; font-weight:900;">1</span> Upload settings
				</h3>
				<p>
					This method will automatically populate all requirements. All you need is a Form Synergy account.
				</p>
				<ol>
					<li>Click on the generate button, to retrieve your settings.</li>
					<li>Upload the file and complete this step.</li>
				</ol>
				
				<a class="button button-primary right ml-1" href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&iaa=upload-settings', 'form_synergy_update_options', '_wpnonce' ) ); ?>">Upload</a>
				<a class="button button-secondary right"
					href="<?php echo esc_url( 'https://formsynergy.com/console/?options=wordpress' ); ?>" target="_blank" title="Open the Form Synergy Console in a new tab"> Generate </a>
				<br class="clear">
			</div>
		<div class="card">
			<h3 class="title">
				<span style="color:tomato; font-weight:900;">2</span> Manual key input
			</h3>
			<p>
				If you already have an account with Form Synergy and have all the required keys handy, go ahead.</p>

			<a class="button button-primary right"
				href="<?php echo esc_url( wp_nonce_url( '?page=synergy-press&iaa=dashboard&tab=api-configuration&initiator=interactive-account-access', 'form_synergy_update_options', '_wpnonce' ) ); ?>">Select</a>
			<br class="clear">
		</div>
		<div class="card">
			<h3 class="title">
				<span style="color:tomato; font-weight:900;">3</span> Create an account
			</h3>
			<p>
				This is not a trial offer. Accounts are free!
			</p>
			<a class="button button-primary right"
				href="<?php echo esc_url( 'https://formsynergy.com/console/' ); ?>" target="_blank" title="Open the Form Synergy Console in a new tab"> Register </a>
			<br class="clear">
		</div>
	</div>
</div>
