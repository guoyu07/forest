<?php

if ( !function_exists( 'madeleine_default_options_reviews' ) ) {
	function madeleine_default_options_reviews() {
		$defaults = array(
			'maximum_rating' => 10,
			'maximum_price' => 2000,
			'currency' => '$',
		);
	return apply_filters( 'madeleine_default_options_reviews', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_reviews_options' ) ) {
	function madeleine_initialize_reviews_options() {
		if( false == get_option( 'madeleine_options_reviews' ) ):
			add_option( 'madeleine_options_reviews', apply_filters( 'madeleine_default_options_reviews', madeleine_default_options_reviews() ) );
		endif;

		add_settings_section(
			'rating_section',
			__( 'Rating', 'madeleine' ),
			'madeleine_rating_section_callback',
			'madeleine_reviews_options_page'
		);
		
		add_settings_field( 
			'maximum_rating',
			__( 'Maximum Rating', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_reviews_options_page',
			'rating_section',
			array(
				'description' => 'The maximum rating you can set on a Review.',
				'type' => 'select',
				'option' => 'madeleine_options_reviews',
				'id' => 'maximum_rating',
				'select' => array(10, 20, 50, 100)
			)
		);

		add_settings_section(
			'price_section',
			__( 'Price', 'madeleine' ),
			'madeleine_price_section_callback',
			'madeleine_reviews_options_page'
		);
		
		add_settings_field( 
			'maximum_price',
			__( 'Maximum Price', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_reviews_options_page',
			'price_section',
			array(
				'description' => 'The maximum price you can filter the Reviews with.',
				'type' => 'select',
				'option' => 'madeleine_options_reviews',
				'id' => 'maximum_price',
				'select' => array(100, 200, 300, 400, 500, 1000, 2000, 3000, 4000, 5000, 10000)
			)
		);
		
		add_settings_field( 
			'currency',
			__( 'Currency', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_reviews_options_page',
			'price_section',
			array(
				'description' => 'The currency for the Reviews\' prices.',
				'type' => 'select',
				'option' => 'madeleine_options_reviews',
				'id' => 'currency',
				'select' => array('$', '€', '£')
			)
		);
		
		register_setting(
			'madeleine_reviews_options_group',
			'madeleine_options_reviews'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_reviews_options' );


if ( !function_exists( 'madeleine_rating_section_callback' ) ) {
	function madeleine_rating_section_callback() {
		echo '<p>Choose the maximum rating value for the reviews. You will need to make sure that each review has a rating <strong>between 0 and this maximum value</strong>.<br>
		For example, if the maximum rating is set at 10, you can enter values like 9.4, 5.6, 7.1, but not 11.4.</p>';
	}
}


if ( !function_exists( 'madeleine_price_section_callback' ) ) {
	function madeleine_price_section_callback() {
		echo '<p>Choose the maximum price value for the reviews. This value will be used to filter the reviews by price.</p>';
	}
}


?>