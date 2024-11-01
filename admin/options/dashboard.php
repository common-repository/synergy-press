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
 * Synergy Press plugin options: Dashboard.
 */
?>

<div class="fs-headings">
	<div class="fs-content">
		<h3>Welcome!</h3>
	</div>
</div>
<hr class="fs-hr"/>

<div class="fs-row">
	<div class="fs-col-left">
		<?php

		/**
		 * Display notices
		 */
		foreach ( $notices as $notice ) :
			if ( file_exists( plugin_dir_path( __FILE__ ) . 'modules/notices/' . $notice . '.php' ) ) :
				include_once plugin_dir_path( __FILE__ ) . 'modules/notices/' . $notice . '.php';
				endif;
			endforeach;
		?>
	</div>
	<div class="fs-col-right">
		<?php

		/**
		 * Display explore the possibilities
		 */
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'welcome.php' ) ) :
			include_once plugin_dir_path( __FILE__ ) . 'welcome.php';
			endif;
		?>
	</div>
</div>
