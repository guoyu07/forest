<?php

if ( !function_exists( 'madeleine_default_options_general' ) ) {
	function madeleine_default_options_general() {
		$defaults = array(
			'custom_css' => '',
			'trending_status' => 1,
			'trending_number' => 15,
			'footer_text' => '',
			'feedburner_url' => '',
			'tracking_code' => '',
			'favicon' => ''
		);
	return apply_filters( 'madeleine_default_options_general', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_general_options' ) ) {
	function madeleine_initialize_general_options() {
		if( false == get_option( 'madeleine_options_general' ) ):
			add_option( 'madeleine_options_general', apply_filters( 'madeleine_default_options_general', madeleine_default_options_general() ) );
		endif;

		add_settings_section(
			'general_section',
			__( 'General options', 'madeleine' ),
			'madeleine_general_section_callback',
			'madeleine_general_options_page'
		);
		
		add_settings_field( 
			'favicon',
			__( 'Favicon', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'Upload your 16x16 Png or Ico file.',
				'type' => 'image',
				'option' => 'madeleine_options_general',
				'id' => 'favicon'
			)
		);
		
		add_settings_field( 
			'trending_status',
			__( 'Trending Tags', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'Show a list of your most used tags in the last 30 days.',
				'type' => 'radio',
				'option' => 'madeleine_options_general',
				'id' => 'trending_status'
			)
		);
		
		add_settings_field( 
			'trending_number',
			__( 'Trending Tags Number', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'The maximum number of trending tags to show below the navigation bar.',
				'type' => 'select',
				'option' => 'madeleine_options_general',
				'id' => 'trending_number',
				'select' => array(5, 10, 15, 20)
			)
		);
		
		add_settings_field( 
			'feedburner_url',
			__( 'Feedburner URL', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'Enter your Feedburner URL. The main RSS feed will be redirected to this URL.',
				'type' => 'text',
				'option' => 'madeleine_options_general',
				'id' => 'feedburner_url'
			)
		);
		
		add_settings_field( 
			'tracking_code',
			__( 'Tracking code', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'Enter your tracking script information (like Google Analytics). It will be inserted in the footer of every page.',
				'type' => 'textarea',
				'option' => 'madeleine_options_general',
				'id' => 'tracking_code'
			)
		);
		
		add_settings_field( 
			'custom_css',
			__( 'CSS Code', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'Enter your custom CSS code.',
				'type' => 'textarea',
				'option' => 'madeleine_options_general',
				'id' => 'custom_css'
			)
		);
		
		add_settings_field( 
			'footer_text',
			__( 'Footer text', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_general_options_page',
			'general_section',
			array(
				'description' => 'Enter the text that will be displayed in the footer.',
				'type' => 'textarea',
				'option' => 'madeleine_options_general',
				'id' => 'footer_text'
			)
		);
		
		register_setting(
			'madeleine_general_options_group',
			'madeleine_options_general'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_general_options' );


if ( !function_exists( 'madeleine_general_section_callback' ) ) {
	function madeleine_general_section_callback() {
		echo '<p>Configure the general settings of your theme. Upload your favicon, setup your feed, insert your analytics tracking code, your custom CSS and your footer\'s text.</p>';
	}
}


?>