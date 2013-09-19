<?php


if ( !function_exists( 'madeleine_add_review_meta_boxes' ) ) {
	function madeleine_add_review_meta_boxes() {
		$reviews_options = get_option( 'madeleine_options_reviews' );
		$maximum_rating = ( isset( $reviews_options['maximum_rating'] ) ) ? $reviews_options['maximum_rating'] : 10;
		$meta_box = array(
			'id' => 'review',
			'title' => __( 'Review settings', 'madeleine' ),
			'description' => __( 'Choose the review settings.', 'madeleine' ),
			'page' => 'review',
			'context' => 'normal',
			'priority' => 'high',
			 'fields' => array(
				array(
					'name' => __( 'Rating', 'madeleine' ),
					'help' => sprintf( __( 'Choose a number between 0 and %s, like 8.6.', 'madeleine' ), $maximum_rating ),
					'id' => '_madeleine_review_rating',
					'type' => 'text',
					'default' => ''
				),
				array(
					'name' => __( 'Price', 'madeleine' ),
					'help' => __( 'Type the price of the product, like 299.', 'madeleine' ),
					'id' => '_madeleine_review_price',
					'type' => 'text',
					'default' => ''
				),
				array(
					'name' => __( 'Good', 'madeleine' ),
					'help' => __( 'The pros of the product you\'reviewing. It will be displayed as a list. Just add a new line to add a new list item.', 'madeleine' ),
					'id' => '_madeleine_review_good',
					'type' => 'textarea',
					'default' => ''
				),
				array(
					'name' => __( 'Bad', 'madeleine' ),
					'help' => __( 'The cons of the product you\'reviewing. It will be displayed as a list. Just add a new line to add a new list item.', 'madeleine' ),
					'id' => '_madeleine_review_bad',
					'type' => 'textarea',
					'default' => ''
				)
			)
		);
		madeleine_add_meta_box( $meta_box );
	}
}


?>