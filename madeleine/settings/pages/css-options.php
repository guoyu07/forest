<?php

function madeleine_default_css_options() {
  $defaults = array(
    'css_code' => '',
  );
  return apply_filters( 'madeleine_default_css_options', $defaults );
}


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
    'css_code',
    __( 'Custom CSS code', 'madeleine' ),
    'madeleine_css_code_callback',
    'madeleine_css_options_page',
    'css_section'
  );
  
  register_setting(
    'madeleine_css_options_group',
    'madeleine_css_options'
  );  
}
add_action( 'admin_init', 'madeleine_initialize_css_options' );


function madeleine_css_callback() {
  echo '<p>You can add your own CSS code. It will be applied after all other CSS files so that you can override any default style.</p>';
}


function madeleine_css_code_callback() {
  $settings = get_option( 'madeleine_css_options' );
  echo '<textarea name="madeleine_css_options[css_code]" rows="10" cols="100">' . $settings['css_code'] . '</textarea>';
}


?>