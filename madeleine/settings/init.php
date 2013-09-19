<?php

define( 'SETTINGS_DIR', get_template_directory() .'/settings' );
define( 'SETTINGS_URL', get_template_directory_uri() .'/settings' );


/**
 * Load functions for the WP Customizer and the categories.
 *
 */

require_once( SETTINGS_DIR .'/custom/custom-header.php' );
require_once( SETTINGS_DIR .'/custom/custom-background.php' );
require_once( SETTINGS_DIR .'/custom/custom-settings.php' );
require_once( SETTINGS_DIR .'/custom/custom-categories.php' );


/**
 * This function introduces theme settings pages.
 * 
 */

if ( !function_exists( 'madeleine_theme_settings' ) ) {
	function madeleine_theme_settings() {

		add_theme_page(
			'Madeleine Popular Posts plugin',
			'Madeleine Popular Posts plugin',
			'update_core',
			'madeleine_popularity_options_page',
			'madeleine_popularity_options_display'
		);
	}
}
add_action( 'admin_menu', 'madeleine_theme_settings' );


if ( !function_exists( 'madeleine_popularity_options_display' ) ) {
	function madeleine_popularity_options_display() {
		?>
		<div id="madeleine-settings" class="wrap">
			<div id="madeleine-settings-header">
				<h2 id="madeleine-settings-title"><?php _e( 'Popular Posts plugin', 'madeleine' ); ?></h2>
				<?php settings_errors(); ?>
			</div>

			<form id="madeleine-settings-form" method="post" action="options.php">
				<?php
					settings_fields( 'madeleine_popularity_options_group' );
					do_settings_sections( 'madeleine_popularity_options_page' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}
}


/**
 * Load the Popular Posts plugin
 *
 */

require_once( SETTINGS_DIR .'/pages/popularity-options.php' );


?>