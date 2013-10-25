<?php

if ( !function_exists( 'madeleine_default_options_layout' ) ) {
	function madeleine_default_options_layout() {
		$defaults = array(
			'home' => 'grid',
			'category' => 'list',
			'tag' => 'pinterest',
			'date' => 'list',
			'search' => 'list'
		);
	return apply_filters( 'madeleine_default_options_layout', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_layout_options' ) ) {
	function madeleine_initialize_layout_options() {
		if( false == get_option( 'madeleine_options_layout' ) ):
			add_option( 'madeleine_options_layout', apply_filters( 'madeleine_default_options_layout', madeleine_default_options_layout() ) );
		endif;

		add_settings_section(
			'layout_section',
			__( 'Layouts', 'madeleine' ),
			'madeleine_layout_section_callback',
			'madeleine_layout_options_page'
		);
		
		add_settings_field( 
			'home',
			__( 'Home', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_layout_options_page',
			'layout_section',
			array(
				'description' => 'The layout of posts on the Home page.',
				'type' => 'boxes',
				'option' => 'madeleine_options_layout',
				'id' => 'home',
				'boxes' => array(
					array(
						'value' => 'grid',
						'label' => 'Grid',
						'image' => 'layout-grid'
					),
					array(
						'value' => 'list',
						'label' => 'List',
						'image' => 'layout-list'
					)
				)
			)
		);
		
		add_settings_field( 
			'category',
			__( 'Category', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_layout_options_page',
			'layout_section',
			array(
				'description' => 'The layout of posts in a Category archive.',
				'type' => 'boxes',
				'option' => 'madeleine_options_layout',
				'id' => 'category',
				'boxes' => array(
					array(
						'value' => 'grid',
						'label' => 'Grid',
						'image' => 'layout-grid'
					),
					array(
						'value' => 'list',
						'label' => 'List',
						'image' => 'layout-list'
					),
					array(
						'value' => 'pinterest',
						'label' => 'Pinterest',
						'image' => 'layout-pinterest'
					)
				)
			)
		);
		
		add_settings_field( 
			'tag',
			__( 'Tag', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_layout_options_page',
			'layout_section',
			array(
				'description' => 'The layout of posts in a Tag archive.',
				'type' => 'boxes',
				'option' => 'madeleine_options_layout',
				'id' => 'tag',
				'boxes' => array(
					array(
						'value' => 'grid',
						'label' => 'Grid',
						'image' => 'layout-grid'
					),
					array(
						'value' => 'list',
						'label' => 'List',
						'image' => 'layout-list'
					),
					array(
						'value' => 'pinterest',
						'label' => 'Pinterest',
						'image' => 'layout-pinterest'
					)
				)
			)
		);
		
		add_settings_field( 
			'date',
			__( 'Date', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_layout_options_page',
			'layout_section',
			array(
				'description' => 'The layout of posts in a Date archive.',
				'type' => 'boxes',
				'option' => 'madeleine_options_layout',
				'id' => 'date',
				'boxes' => array(
					array(
						'value' => 'grid',
						'label' => 'Grid',
						'image' => 'layout-grid'
					),
					array(
						'value' => 'list',
						'label' => 'List',
						'image' => 'layout-list'
					),
					array(
						'value' => 'pinterest',
						'label' => 'Pinterest',
						'image' => 'layout-pinterest'
					)
				)
			)
		);
		
		add_settings_field( 
			'search',
			__( 'Search', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_layout_options_page',
			'layout_section',
			array(
				'description' => 'The layout of posts in Search results.',
				'type' => 'boxes',
				'option' => 'madeleine_options_layout',
				'id' => 'search',
				'boxes' => array(
					array(
						'value' => 'grid',
						'label' => 'Grid',
						'image' => 'layout-grid'
					),
					array(
						'value' => 'list',
						'label' => 'List',
						'image' => 'layout-list'
					),
					array(
						'value' => 'pinterest',
						'label' => 'Pinterest',
						'image' => 'layout-pinterest'
					)
				)
			)
		);
		
		register_setting(
			'madeleine_layout_options_group',
			'madeleine_options_layout'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_layout_options' );


if ( !function_exists( 'madeleine_layout_section_callback' ) ) {
	function madeleine_layout_section_callback() {
		echo '<p>Choose the different layouts for posts.</p>';
	}
}


?>