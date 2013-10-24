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
 * This function introduces the theme settings into the 'Appearance' menu and into a top-level 
 * 'Madeleine Theme' menu.
 *
 */

if ( !function_exists( 'madeleine_theme_settings' ) ) {
	function madeleine_theme_settings() {

		$icon = SETTINGS_URL . '/images/icon.png';

		add_object_page(
			'Madeleine Theme Settings',				// The title to be displayed in the browser window for this page.
			'Madeleine',											// The text to be displayed for this menu item
			'update_core',										// Which type of users can see this menu item
			'madeleine_general_options_page',	// The unique ID - that is, the slug - for this menu item
			'madeleine_settings_display',			// The name of the function to call when rendering this menu's page
			$icon
		);
		
		add_submenu_page(
			'madeleine_general_options_page',				// The ID of the top-level menu page to which this submenu item belongs
			__( 'General Options', 'madeleine' ),		// The value used to populate the browser's title bar when the menu page is active
			__( 'General Options', 'madeleine' ),		// The label of this submenu item displayed in the menu
			'update_core',													// What roles are able to access this submenu item
			'madeleine_general_options_page',				// The ID used to represent this submenu item
			'madeleine_settings_display'						// The callback function used to render the settings for this submenu item
		);
		
		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Social Features', 'madeleine' ),
			__( 'Social Features', 'madeleine' ),
			'update_core',
			'madeleine_social_options_page',
			create_function( null, 'madeleine_settings_display( "social_options" );' )
		);
		
		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Home Options', 'madeleine' ),
			__( 'Home Options', 'madeleine' ),
			'update_core',
			'madeleine_home_options_page',
			create_function( null, 'madeleine_settings_display( "home_options" );' )
		);

		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Popular Posts', 'madeleine' ),
			__( 'Popular Posts', 'madeleine' ),
			'update_core',
			'madeleine_popular_posts_options_page',
			create_function( null, 'madeleine_settings_display( "popular_posts_options" );' )
		);
		
		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Reviews', 'madeleine' ),
			__( 'Reviews', 'madeleine' ),
			'update_core',
			'madeleine_reviews_options_page',
			create_function( null, 'madeleine_settings_display( "reviews_options" );' )
		);
		
		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Header & Background', 'madeleine' ),
			__( 'Header & Background', 'madeleine' ),
			'update_core',
			'madeleine_header_and_background_options_page',
			create_function( null, 'madeleine_settings_display( "header_and_background_options" );' )
		);
		
		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Colors', 'madeleine' ),
			__( 'Colors', 'madeleine' ),
			'update_core',
			'madeleine_colors_options_page',
			create_function( null, 'madeleine_settings_display( "colors_options" );' )
		);
		
		add_submenu_page(
			'madeleine_general_options_page',
			__( 'Typography', 'madeleine' ),
			__( 'Typography', 'madeleine' ),
			'update_core',
			'madeleine_typography_options_page',
			create_function( null, 'madeleine_settings_display( "typography_options" );' )
		);
	}
}
add_action( 'admin_menu', 'madeleine_theme_settings' );


if ( !function_exists( 'madeleine_settings_display' ) ) {
	function madeleine_settings_display( $active_tab = '' ) {
		?>
		<div id="madeleine-settings" class="wrap">
			<div id="madeleine-settings-header">
				<h2 id="madeleine-settings-title"><?php _e( 'Madeleine Theme Settings', 'madeleine' ); ?></h2>
				<?php settings_errors(); ?>
				<?php
				if ( isset( $_GET[ 'tab' ] ) ):
					$active_tab = $_GET[ 'tab' ];
				elseif ( $active_tab == 'general_options' ):
					$active_tab = 'general_options';
				elseif ( $active_tab == 'social_options' ):
					$active_tab = 'social_options';
				elseif ( $active_tab == 'home_options' ):
					$active_tab = 'home_options';
				elseif ( $active_tab == 'popular_posts_options' ):
					$active_tab = 'popular_posts_options';
				elseif ( $active_tab == 'reviews_options' ):
					$active_tab = 'reviews_options';
				elseif ( $active_tab == 'header_and_background_options' ):
					$active_tab = 'header_and_background_options';
				elseif ( $active_tab == 'colors_options' ):
					$active_tab = 'colors_options';
				elseif ( $active_tab == 'typography_options' ):
					$active_tab = 'typography_options';
				else:
					$active_tab = 'general_options';
				endif;
				?>
			</div>

			<div id="madeleine-settings-container">
				<div id="madeleine-settings-tabs">
					<a href="?page=madeleine_general_options_page" class="<?php echo $active_tab == 'general_options' ? 'current' : ''; ?>"><?php _e( 'General Options', 'madeleine' ); ?></a>
					<a href="?page=madeleine_colors_options_page" class="<?php echo $active_tab == 'colors_options' ? 'current' : ''; ?>"><?php _e( 'Colors', 'madeleine' ); ?></a>
					<a href="?page=madeleine_typography_options_page" class="<?php echo $active_tab == 'typography_options' ? 'current' : ''; ?>"><?php _e( 'Typography', 'madeleine' ); ?></a>
					<a href="?page=madeleine_social_options_page" class="<?php echo $active_tab == 'social_options' ? 'current' : ''; ?>"><?php _e( 'Social Features', 'madeleine' ); ?></a>
					<a href="?page=madeleine_home_options_page" class="<?php echo $active_tab == 'home_options' ? 'current' : ''; ?>"><?php _e( 'Homepage', 'madeleine' ); ?></a>
					<a href="?page=madeleine_popular_posts_options_page" class="<?php echo $active_tab == 'popular_posts_options' ? 'current' : ''; ?>"><?php _e( 'Popular Posts', 'madeleine' ); ?></a>
					<a href="?page=madeleine_reviews_options_page" class="<?php echo $active_tab == 'reviews_options' ? 'current' : ''; ?>"><?php _e( 'Reviews', 'madeleine' ); ?></a>
					<a href="?page=madeleine_header_and_background_options_page" class="<?php echo $active_tab == 'header_and_background_options' ? 'current' : ''; ?>"><?php _e( 'Header &amp; Background', 'madeleine' ); ?></a>
					<div style="clear: left;"></div>
				</div>

				<form id="madeleine-settings-form" method="post" action="options.php">
					<?php
						if( $active_tab == 'general_options' ):
							settings_fields( 'madeleine_general_options_group' );
							do_settings_sections( 'madeleine_general_options_page' );
						elseif( $active_tab == 'colors_options' ):
							settings_fields( 'madeleine_colors_options_group' );
							do_settings_sections( 'madeleine_colors_options_page' );
						elseif( $active_tab == 'typography_options' ):
							settings_fields( 'madeleine_typography_options_group' );
							do_settings_sections( 'madeleine_typography_options_page' );
						elseif( $active_tab == 'social_options' ):
							settings_fields( 'madeleine_social_options_group' );
							do_settings_sections( 'madeleine_social_options_page' );
						elseif( $active_tab == 'home_options' ):
							settings_fields( 'madeleine_home_options_group' );
							do_settings_sections( 'madeleine_home_options_page' );
						elseif( $active_tab == 'popular_posts_options' ):
							settings_fields( 'madeleine_popular_posts_options_group' );
							do_settings_sections( 'madeleine_popular_posts_options_page' );
						elseif( $active_tab == 'reviews_options' ):
							settings_fields( 'madeleine_reviews_options_group' );
							do_settings_sections( 'madeleine_reviews_options_page' );
						elseif( $active_tab == 'header_and_background_options' ):
							$html = '<h3>Header</h3>';
							$html .= '<p>You can customize the <strong>header logo, title, subtitle, and color</strong> on the <a href="' . get_admin_url() . 'themes.php?page=custom-header">Custom Header</a> page or use the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a>.</p>';
							$html .= '<h3>Background</h3>';
							$html .= '<p>You can customize the <strong>background image, color, position, repeat, and attachment</strong> on the <a href="' . get_admin_url() . 'themes.php?page=custom-background">Custom Background</a>	page or use the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a>.</p>';
							echo $html;
						else:
							settings_fields( 'madeleine_general_options_group' );
							do_settings_sections( 'madeleine_general_options_page' );
						endif;
						if ( $active_tab != 'header_and_background_options' )
							submit_button();
					?>
				</form>
			</div>
		</div>
	<?php
	}
}


/**
 * Set the different setting fields.
 *
 */


if ( !function_exists( 'madeleine_field_callback' ) ) {
	function madeleine_field_callback( $args ) {
		$options = get_option( $args['option'] );
		if ( isset( $args['sub'] ) ):
			$val = isset( $options[$args['sub']][$args['id']] ) ? $options[$args['sub']][$args['id']] : '';
			$name = $args['option'] . '[' . $args['sub'] . ']' . '[' . $args['id'] . ']';
		else:
			$val = isset( $options[$args['id']] ) ? $options[$args['id']] : '';
			$name = $args['option'] . '[' . $args['id'] . ']';
		endif;
		echo '<div class="madeleine-description">';
		if ( isset( $args['image'] ) ):
			echo '<img src="' . SETTINGS_URL . '/images/' . $args['image'] . '.png"><br>';
		endif;
		echo $args['description'] . '</div>';
		echo '<div class="madeleine-field">';
		if ( $args['type'] == 'text' ):
			echo '<input class="regular-text" type="text" name="' . $name . '" value="' . $val . '">';
		elseif ( $args['type'] == 'textarea' ):
			echo '<textarea name="' . $name . '" rows="10" cols="100">' . $val . '</textarea>';
		elseif ( $args['type'] == 'radio' ):
			$html = '<label><input type="radio" name="' . $name . '" value="1"' . checked( 1, $val, false ) . '>&nbsp;';
			$html .= ( isset( $args['labels'] ) ) ? $args['labels'][0] : 'Show';
			$html .= '</label>';
			$html .= '<label><input type="radio" name="' . $name . '" value="0"' . checked( 0, $val, false ) . '>&nbsp;';
			$html .= ( isset( $args['labels'] ) ) ? $args['labels'][1] : 'Hide';
			$html .= '</label>';
			echo $html;
		elseif ( $args['type'] == 'select' ):
			$html = '<select name="' . $name . '">';
			foreach ($args['select'] as $option ):
				$html .= '<option value="' . $option . '"' . selected( $option, $val, false ) . '>' . $option . '</option>';
			endforeach;
			$html .= '</select>';
			echo $html;
		elseif ( $args['type'] == 'font' ):
			$html = '<select class="madeleine-font-select" name="' . $name . '" data-font-select="' . $args['selectid'] . '">';
			foreach ($args['select'] as $option ):
				$html .= '<option value="' . $option[0] . '"' . selected( $option[0], $val, false ) . '>' . $option[1] . '</option>';
			endforeach;
			$html .= '</select>';
			echo $html;
		elseif ( $args['type'] == 'color' ):
			$html = '<input class="madeleine-color-picker" name="' . $name . '" value="' . $val . '" type="text" size="40" data-default-color="' . $args['default'] . '">';
			echo $html;
		elseif ( $args['type'] == 'image' ):
			$html = '<input id="upload-image-button" class="button" type="button" value="Upload or choose an image">
				<br>
				<input id="upload-image-url" type="text" class="regular-text text-upload" style="margin: 10px 0;" name="' . $name .'" value="' . esc_url( $val ) . '">
				<br>
				<img id="upload-image-preview" style="max-width: 450px; display: block;" src="' . esc_url( $val ) . '">';
			echo $html;
		elseif ( $args['type'] == 'boxes' ):
			$html_id = 'madeleine-' . str_replace('_', '-', $args['id']);
			$html = '<input id="' . $html_id . '" class="madeleine-box-field" type="hidden" name="' . $name .'" value="' . $val . '">';
			$html .= '<div class="madeleine-boxes" data-field="' . $html_id . '">';
			foreach ( $args['boxes'] as $box ):
				$html .= '<span class="madeleine-box" data-value="' . $box['value'] . '">';
				$html .= '<img src="' . SETTINGS_URL . '/images/' . $box['image'] . '.png"><br>';
				$html .= $box['label'];
				$html .= '</span>';
			endforeach;
			$html .= '</div>';
			echo $html;
		endif;
		echo '</div>';
		if ( $args['id'] == 'font_body' ):
			echo '<div style="clear: both;"></div>
			<div id="madeleine-font-body" class="madeleine-font">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla tempus pretium justo id ultrices. Vivamus erat orci, sodales vel nulla non, accumsan faucibus purus. Nullam cursus, diam vel ultricies blandit, elit libero ullamcorper sapien, eu lacinia mauris leo eu odio. Maecenas feugiat in nunc vitae varius. Etiam at varius metus. Mauris a condimentum urna. Duis nulla justo, dictum a lorem id, imperdiet luctus sem. Proin adipiscing, orci quis euismod volutpat, lectus dui vehicula eros, in faucibus sapien purus quis leo. Integer laoreet sagittis sem et vestibulum. Donec lacinia nisl id est lacinia ultricies. Quisque non sem facilisis, sodales metus fringilla, commodo magna.</div>';
		elseif ( $args['id'] == 'font_title' ):
			echo '<div style="clear: both;"></div>
			<div id="madeleine-font-title" class="madeleine-font">The quick brown fox jumps over the lazy dog</div>';
		endif;
		echo '<div style="clear: both;"></div>';
	}
}


/**
 * Load the different settings tabs.
 *
 */

require_once( SETTINGS_DIR .'/pages/general-options.php' );
require_once( SETTINGS_DIR .'/pages/colors-options.php' );
require_once( SETTINGS_DIR .'/pages/social-options.php' );
require_once( SETTINGS_DIR .'/pages/typography-options.php' );
require_once( SETTINGS_DIR .'/pages/home-options.php' );
require_once( SETTINGS_DIR .'/pages/popular-posts-options.php' );
require_once( SETTINGS_DIR .'/pages/reviews-options.php' );


?>