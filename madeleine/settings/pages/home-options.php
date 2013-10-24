<?php

if ( !function_exists( 'madeleine_default_options_home' ) ) {
	function madeleine_default_options_home() {
		$defaults = array(
			'focus_status' => 1,
			'focus_layout' => 'highlight',
			'grid_number' => 6,
			'next_status' => 1,
			'next_number' => 10,
			'category_tabs_status' => 1,
			'reviews_status' => 1,
			'reviews_number' => 6
		);
	return apply_filters( 'madeleine_default_options_home', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_home_options' ) ) {
	function madeleine_initialize_home_options() {
		if( false == get_option( 'madeleine_options_home' ) ):
			add_option( 'madeleine_options_home', apply_filters( 'madeleine_default_options_home', madeleine_default_options_home() ) );
		endif;

		add_settings_section(
			'focus_section', // ID used to identify this section and with which to register settings
			__( 'Focus posts', 'madeleine' ), // Title to be displayed on the administration page
			'madeleine_focus_section_callback', // Callback used to render the description of the section
			'madeleine_home_options_page' // Page on which to add this section of settings
		);
		
		add_settings_field( 
			'focus_status', // ID used to identify the field throughout the theme
			__( 'Focus status', 'madeleine' ), // The label to the left of the option interface element
			'madeleine_field_callback', // The name of the function responsible for rendering the option interface
			'madeleine_home_options_page', // The page on which this option will be displayed
			'focus_section', // The name of the section to which this field belongs
			array(
				'description' => 'The 5 latest sticky posts displayed right below the navigation bar.',
				'type' => 'radio',
				'option' => 'madeleine_options_home',
				'id' => 'focus_status'
			)
		);
		
		add_settings_field( 
			'focus_layout',
			__( 'Focus layout', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'focus_section',
			array(
				'description' => 'The layout of the Focus posts.',
				'type' => 'boxes',
				'option' => 'madeleine_options_home',
				'id' => 'focus_layout',
				'boxes' => array(
					array(
						'value' => 'highlight',
						'label' => 'Highlight',
						'image' => 'focus-highlight'
					),
					array(
						'value' => 'carousel',
						'label' => 'Carousel',
						'image' => 'focus-carousel'
					),
					array(
						'value' => 'puzzle',
						'label' => 'Puzzle',
						'image' => 'focus-puzzle'
					)
				)
			)
		);

		add_settings_section(
			'grid_section',
			__( 'Grid posts', 'madeleine' ),
			'madeleine_grid_section_callback',
			'madeleine_home_options_page'
		);
		
		add_settings_field( 
			'grid_number',
			__( 'Number of Grid posts', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'grid_section',
			array(
				'description' => 'The number of posts in the Grid.',
				'type' => 'select',
				'option' => 'madeleine_options_home',
				'id' => 'grid_number',
				'select' => array(2, 4, 6, 8, 10, 12)
			)
		);

		add_settings_section(
			'next_section',
			__( 'Next posts', 'madeleine' ),
			'madeleine_next_section_callback',
			'madeleine_home_options_page'
		);
		
		add_settings_field( 
			'next_status',
			__( 'Next posts status', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'next_section',
			array(
				'description' => 'The posts displayed below the Grid, with no excerpt.',
				'type' => 'radio',
				'option' => 'madeleine_options_home',
				'id' => 'next_status'
			)
		);
		
		add_settings_field( 
			'next_number',
			__( 'Number of Next posts', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'next_section',
			array(
				'description' => 'The number of Next posts.',
				'type' => 'select',
				'option' => 'madeleine_options_home',
				'id' => 'next_number',
				'select' => array(2, 4, 6, 8, 10, 12)
			)
		);

		add_settings_section(
			'category_tabs_section',
			__( 'Category tabs', 'madeleine' ),
			'madeleine_category_tabs_section_callback',
			'madeleine_home_options_page'
		);
		
		add_settings_field( 
			'category_tabs_status',
			__( 'Category tabs display', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'category_tabs_section',
			array(
				'description' => 'The Category posts on the homepage.',
				'type' => 'radio',
				'option' => 'madeleine_options_home',
				'id' => 'category_tabs_status',
				'labels' => array(
					'Show categories as tabs',
					'Show categories one after the other'
				)
			)
		);

		add_settings_section(
			'reviews_section',
			__( 'Reviews', 'madeleine' ),
			'madeleine_reviews_section_callback',
			'madeleine_home_options_page'
		);
		
		add_settings_field( 
			'reviews_status',
			__( 'Home reviews status', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'reviews_section',
			array(
				'description' => 'The reviews displayed on the homepage.',
				'type' => 'radio',
				'option' => 'madeleine_options_home',
				'id' => 'reviews_status'
			)
		);
		
		add_settings_field( 
			'reviews_number',
			__( 'Number of home reviews', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_home_options_page',
			'reviews_section',
			array(
				'description' => 'The maximum number of reviews to display.',
				'type' => 'select',
				'option' => 'madeleine_options_home',
				'id' => 'reviews_number',
				'select' => array(3, 6, 9)
			)
		);
		
		register_setting(
			'madeleine_home_options_group',
			'madeleine_options_home'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_home_options' );


if ( !function_exists( 'madeleine_focus_section_callback' ) ) {
	function madeleine_focus_section_callback() {
		echo '<p>The Focus feature represents the 5 posts that are displayed on the home page, right below the navigation bar.<br>
		The 5 posts displayed are the <strong>5 latest sticky posts</strong>, from any category. As a result you need to have <em>at least</em> 5 sticky posts published for this feature to work.</p>';
	}
}


if ( !function_exists( 'madeleine_grid_section_callback' ) ) {
	function madeleine_grid_section_callback() {
		echo '<p>The Grid posts are the ones displayed after the Focus posts.</p>';
	}
}


if ( !function_exists( 'madeleine_next_section_callback' ) ) {
	function madeleine_next_section_callback() {
		echo '<p>The Next posts are the ones displayed after the Grid posts. They have no excerpt.</p>';
	}
}


if ( !function_exists( 'madeleine_category_tabs_section_callback' ) ) {
	function madeleine_category_tabs_section_callback() {
		echo '<p>The Category tabs display the 5 latest posts of each category. They can be displayed as tabs (one category at a time) or displayed in full, one after the other.</p>';
	}
}


if ( !function_exists( 'madeleine_reviews_section_callback' ) ) {
	function madeleine_reviews_section_callback() {
		echo '<p>The homepage displays the latest Reviews, which can be filtered by Review category.</p>';
	}
}

?>