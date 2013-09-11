<?php

if ( !function_exists( 'madeleine_default_analytics_options' ) ) {
  function madeleine_default_analytics_options() {
    $defaults = array(
      'feedburner_url' => '',
      'tracking_code' => '',
    );
    return apply_filters( 'madeleine_default_analytics_options', $defaults );
  }
}


if ( !function_exists( 'madeleine_initialize_analytics_options' ) ) {
  function madeleine_initialize_analytics_options() {
    if( false == get_option( 'madeleine_analytics_options' ) )
      add_option( 'madeleine_analytics_options', apply_filters( 'madeleine_default_analytics_options', madeleine_default_analytics_options() ) );

    add_settings_section(
      'feedburner_section',
      __( 'Feedburner', 'madeleine' ),
      'madeleine_feedburner_callback',
      'madeleine_analytics_options_page'
    );
    
    add_settings_field( 
      'feedburner_url',
      __( 'URL', 'madeleine' ),
      'madeleine_feedburner_url_callback',
      'madeleine_analytics_options_page',
      'feedburner_section'
    );

    add_settings_section(
      'tracking_section',
      __( 'Tracking', 'madeleine' ),
      'madeleine_tracking_callback',
      'madeleine_analytics_options_page'
    );
    
    add_settings_field( 
      'tracking_code',
      __( 'Tracking code', 'madeleine' ),
      'madeleine_tracking_code_callback',
      'madeleine_analytics_options_page',
      'tracking_section'
    );
    
    register_setting(
      'madeleine_analytics_options_group',
      'madeleine_analytics_options'
    );  
  }
}
add_action( 'admin_init', 'madeleine_initialize_analytics_options' );


if ( !function_exists( 'madeleine_feedburner_callback' ) ) {
  function madeleine_feedburner_callback() {
    echo '<p>Enter your Feedburner URL. The main RSS feed will be redirected to this URL.</p>';
  }
}


if ( !function_exists( 'madeleine_feedburner_url_callback' ) ) {
  function madeleine_feedburner_url_callback() {
    $settings = get_option( 'madeleine_analytics_options' );
    $url = '';
    if( isset( $settings['feedburner_url'] ) )
      $url = esc_url( $settings['feedburner_url'] );
    echo '<input class="regular-text" type="text" name="madeleine_analytics_options[feedburner_url]" value="' . $url . '">';
  }
}


if ( !function_exists( 'madeleine_tracking_callback' ) ) {
  function madeleine_tracking_callback() {
    echo '<p>Enter your tracking script information (like Google Analytics). It will be inserted in the footer of every page.</p>';
  }
}


if ( !function_exists( 'madeleine_tracking_code_callback' ) ) {
  function madeleine_tracking_code_callback() {
    $settings = get_option( 'madeleine_analytics_options' );
    echo '<textarea name="madeleine_analytics_options[tracking_code]" rows="10" cols="100">' . $settings['tracking_code'] . '</textarea>';
  }
}

// function madeleine_status_callback( $args ) {
//   $settings = get_option( 'madeleine_analytics_options' );
//   $key = $args[0] . '_status';
//   if ( isset( $args[1] ) )
//     $labels = $args[1];
//   $html = '<label><input type="radio" name="madeleine_analytics_options[' . $key . ']" value="1"' . checked( 1, $settings[$key], false ) . '>&nbsp;';
//   $html .= ( isset( $labels ) ) ? $labels[0] : 'Show';
//   $html .= '</label><br>';
//   $html .= '<label><input type="radio" name="madeleine_analytics_options[' . $key . ']" value="0"' . checked( 0, $settings[$key], false ) . '>&nbsp;';
//   $html .= ( isset( $labels ) ) ? $labels[1] : 'Hide';
//   $html .= '</label>';
//   echo $html;
// }


?>