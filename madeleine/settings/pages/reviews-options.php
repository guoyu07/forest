<?php

function madeleine_default_reviews_options() {
  $defaults = array(
    'maximum_rating' => 10,
    'maximum_price' => 2000,
  );
  return apply_filters( 'madeleine_default_reviews_options', $defaults );
}


function madeleine_initialize_reviews_options() {
  if( false == get_option( 'madeleine_reviews_options' ) )
    add_option( 'madeleine_reviews_options', apply_filters( 'madeleine_default_reviews_options', madeleine_default_reviews_options() ) );

  add_settings_section(
    'rating_section',
    __( 'Rating', 'madeleine' ),
    'madeleine_rating_callback',
    'madeleine_reviews_options_page'
  );
  
  add_settings_field( 
    'maximum_rating',
    __( 'Maximum Rating', 'madeleine' ),
    'madeleine_maximum_rating_callback',
    'madeleine_reviews_options_page',
    'rating_section'
  );

  add_settings_section(
    'price_section',
    __( 'Price', 'madeleine' ),
    'madeleine_price_callback',
    'madeleine_reviews_options_page'
  );
  
  add_settings_field( 
    'maximum_price',
    __( 'Maximum Price ($)', 'madeleine' ),
    'madeleine_maximum_price_callback',
    'madeleine_reviews_options_page',
    'price_section'
  );
  
  register_setting(
    'madeleine_reviews_options_group',
    'madeleine_reviews_options'
  );  
}
add_action( 'admin_init', 'madeleine_initialize_reviews_options' );


function madeleine_rating_callback() {
  echo '<p>Choose the maximum rating value for the reviews. You will need to make sure that each review has a rating <strong>between 0 and this maximum value</strong>.<br>
  For example, if the maximum rating is set at 10, you can enter values like 9.4, 5.6, 7.1, but not 11.4.</p>';
}


function madeleine_maximum_rating_callback() {
  $settings = get_option( 'madeleine_reviews_options' );
  $numbers = [10, 20, 50, 100];
  $html = '<select name="madeleine_reviews_options[maximum_rating]">';
  foreach ($numbers as $number ):
    $html .= '<option value="' . $number . '"' . selected( $number, $settings['maximum_rating'], false ) . '>' . $number . '</option>';
  endforeach;
  $html .= '</select>';
  echo $html;
}


function madeleine_price_callback() {
  echo '<p>Choose the maximum price value for the reviews. This value will be used to filter the reviews by price.</p>';
}


function madeleine_maximum_price_callback() {
  $settings = get_option( 'madeleine_reviews_options' );
  $numbers = [100, 200, 300, 400, 500, 1000, 2000, 3000, 4000, 5000, 10000];
  $html = '<select name="madeleine_reviews_options[maximum_price]">';
  foreach ($numbers as $number ):
    $html .= '<option value="' . $number . '"' . selected( $number, $settings['maximum_price'], false ) . '>' . $number . '</option>';
  endforeach;
  $html .= '</select>';
  echo $html;
}


?>