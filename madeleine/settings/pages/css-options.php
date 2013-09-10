<?php

if ( !function_exists( 'madeleine_default_css_options' ) ) {
  function madeleine_default_css_options() {
    $defaults = array(
      'custom_code' => '',
    );
    return apply_filters( 'madeleine_default_css_options', $defaults );
  }
}


if ( !function_exists( 'madeleine_initialize_css_options' ) ) {
  function madeleine_initialize_css_options() {
    if( false == get_option( 'madeleine_css_options' ) )
      add_option( 'madeleine_css_options', apply_filters( 'madeleine_default_css_options', madeleine_default_css_options() ) );

    add_settings_section(
      'css_section',
      __( 'CSS', 'madeleine' ),
      'madeleine_css_callback',
      'madeleine_css_options_page'
    );
    
    add_settings_field( 
      'custom_code',
      __( 'Custom CSS code', 'madeleine' ),
      'madeleine_custom_code_callback',
      'madeleine_css_options_page',
      'css_section'
    );
    
    register_setting(
      'madeleine_css_options_group',
      'madeleine_css_options'
    );  
  }
  add_action( 'admin_init', 'madeleine_initialize_css_options' );
}


if ( !function_exists( 'madeleine_css_callback' ) ) {
  function madeleine_css_callback() {
    echo '<p>You can add your own CSS code to override any default style.</p>';
  }
}


if ( !function_exists( 'madeleine_custom_code_callback' ) ) {
  function madeleine_custom_code_callback() {
    $settings = get_option( 'madeleine_css_options' );
    echo '<textarea name="madeleine_css_options[custom_code]" rows="10" cols="100">' . $settings['custom_code'] . '</textarea>';
  }
}


?>