<?php

if ( !function_exists( 'madeleine_default_options_colors' ) ) {
	function madeleine_default_options_colors() {
		$defaults = array(
			'main' => '#d0574e',
			'reviews' => '#276791'
		);
	return apply_filters( 'madeleine_default_options_colors', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_colors_options' ) ) {
	function madeleine_initialize_colors_options() {
		if( false == get_option( 'madeleine_options_colors' ) ):
			add_option( 'madeleine_options_colors', apply_filters( 'madeleine_default_options_colors', madeleine_default_options_colors() ) );
		endif;

		add_settings_section(
			'colors_section',
			__( 'Colors', 'madeleine' ),
			'madeleine_colors_section_callback',
			'madeleine_colors_options_page'
		);
		
		add_settings_field( 
			'main',
			__( 'Main Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The Main color of your website, used for links, the top border and other elements.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'main',
				'default' => '#708491'
			)
		);
		
		add_settings_field( 
			'text',
			__( 'Text Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The color of the body text.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'text',
				'default' => '#444444'
			)
		);
		
		add_settings_field( 
			'reviews',
			__( 'Reviews Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The color for the Reviews section.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'reviews',
				'default' => '#276791'
			)
		);
		
		add_settings_field( 
			'footer_background',
			__( 'Footer Background Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The footer background color.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'footer_background',
				'default' => '#708491'
			)
		);
		
		add_settings_field( 
			'footer_text',
			__( 'Footer Text Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The footer text color.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'footer_text',
				'default' => '#b8c2c8'
			)
		);
		
		add_settings_field( 
			'footer_title',
			__( 'Footer Titles Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The footer titles color.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'footer_title',
				'default' => '#ffffff'
			)
		);
		
		add_settings_field( 
			'footer_link',
			__( 'Footer Links Color', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_colors_options_page',
			'colors_section',
			array(
				'description' => 'The footer links color.',
				'type' => 'color',
				'option' => 'madeleine_options_colors',
				'id' => 'footer_link',
				'default' => '#d7dce1'
			)
		);

		add_settings_section(
			'header_background_colors_section',
			__( 'Header and Background Colors', 'madeleine' ),
			'madeleine_header_background_colors_section_callback',
			'madeleine_colors_options_page'
		);

		add_settings_section(
			'category_colors_section',
			__( 'Category Colors', 'madeleine' ),
			'madeleine_category_colors_section_callback',
			'madeleine_colors_options_page'
		);
		
		register_setting(
			'madeleine_colors_options_group',
			'madeleine_options_colors'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_colors_options' );


if ( !function_exists( 'madeleine_colors_section_callback' ) ) {
	function madeleine_colors_section_callback() {
		echo '<p>Set the different colors of your website.</p>';
	}
}


if ( !function_exists( 'madeleine_header_background_colors_section_callback' ) ) {
	function madeleine_header_background_colors_section_callback() {
		echo '<p>You can customize:
		<ul>
			<li>the <strong>header logo, title, subtitle, and color</strong> on the <a href="' . get_admin_url() . 'themes.php?page=custom-header">Custom Header</a> page</li>
			<li>the <strong>background image, color, position, repeat, and attachment</strong> on the <a href="' . get_admin_url() . 'themes.php?page=custom-background">Custom Background</a> page</li>
		</ul>
		<p>Or just use the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a>.';
	}
}


if ( !function_exists( 'madeleine_category_colors_section_callback' ) ) {
	function madeleine_category_colors_section_callback() {
		echo '<p>If a category is a <strong>top-level category</strong> (i.e. it has no parent category), you can set a color for that category and its children.<br>
		 Just go to the <a href="' . get_admin_url() . 'edit-tags.php?taxonomy=category">Categories main page</a> and browse to a category\'s edit page to choose a color.</p>';
	}
}


?>