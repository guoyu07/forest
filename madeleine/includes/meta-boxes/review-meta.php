<?php

if ( !function_exists( 'madeleine_add_review_meta_boxes' ) ) {
  function madeleine_add_review_meta_boxes() {
    $meta_box = array(
      'id' => 'review',
      'title' => 'Review settings',
      'description' => 'Choose the review settings.',
      'page' => 'review',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => 'Rating',
          'help' => 'Choose a number between 0 and 10, like 8.6.',
          'id' => '_madeleine_review_rating',
          'type' => 'text',
          'default' => ''
        ),
        array(
          'name' => 'Price',
          'help' => 'Type the price of the product, like 299. The Dollar sign &#36; will be automatically added.',
          'id' => '_madeleine_review_price',
          'type' => 'text',
          'default' => ''
        ),
        array(
          'name' => 'Good',
          'help' => 'The pros of the product you\'reviewing. It will be displayed as a list. Just add a new line to add a new list item.',
          'id' => '_madeleine_review_good',
          'type' => 'textarea',
          'default' => ''
        ),
        array(
          'name' => 'Bad',
          'help' => 'The cons of the product you\'reviewing. It will be displayed as a list. Just add a new line to add a new list item.',
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