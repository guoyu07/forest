<?php

function madeleine_default_social_options() {
  $defaults = array(
    'twitter_account' => '',
    'facebook_account' => '',
    'google_plus_account' => '',
    'youtube_account' => '',
    'tumblr_account' => '',
    'twitter_button' => 1,
    'facebook_button' => 1,
    'google_plus_button' => 1,
    'pinterest_button' => 1,
    'reddit_button' => 1,
    ''
  );
  return apply_filters( 'madeleine_default_social_options', $defaults );
}


function madeleine_intialize_social_options() {

  if( false == get_option( 'madeleine_social_options' ) )
    add_option( 'madeleine_social_options', apply_filters( 'madeleine_default_social_options', madeleine_default_social_options() ) );
  
  add_settings_section(
    'social_accounts_section', // ID used to identify this section and with which to register settings
    __( 'Social Accounts', 'madeleine' ), // Title to be displayed on the administration page
    'madeleine_social_accounts_callback', // Callback used to render the description of the section
    'madeleine_social_options_page' // Page on which to add this section of settings
  );
  
  add_settings_field( 
    'twitter_account',
    'Twitter',
    'madeleine_social_account_callback',
    'madeleine_social_options_page',
    'social_accounts_section',
    array( 'twitter' )
  );

  add_settings_field( 
    'facebook_account',
    'Facebook',
    'madeleine_social_account_callback',
    'madeleine_social_options_page',
    'social_accounts_section',
    array( 'facebook' )
  );
  
  add_settings_field( 
    'google_plus_account',
    'Google+',
    'madeleine_social_account_callback',
    'madeleine_social_options_page',
    'social_accounts_section',
    array( 'google_plus' )
  );
  
  add_settings_field( 
    'youtube_account',
    'YouTube',
    'madeleine_social_account_callback',
    'madeleine_social_options_page',
    'social_accounts_section',
    array( 'youtube' )
  );
  
  add_settings_field( 
    'tumblr_account',
    'Tumblr',
    'madeleine_social_account_callback',
    'madeleine_social_options_page',
    'social_accounts_section',
    array( 'tumblr' )
  );
  
  add_settings_section(
    'social_buttons_section',
    __( 'Social Buttons', 'madeleine' ),
    'madeleine_social_buttons_callback',
    'madeleine_social_options_page'
  );
  
  add_settings_field( 
    'twitter_button',
    'Twitter',
    'madeleine_social_button_callback',
    'madeleine_social_options_page',
    'social_buttons_section',
    array( 'twitter' )
  );
  
  add_settings_field( 
    'facebook_button',
    'Facebook',
    'madeleine_social_button_callback',
    'madeleine_social_options_page',
    'social_buttons_section',
    array( 'facebook' )
  );
  
  add_settings_field( 
    'google_plus_button',
    'Google +',
    'madeleine_social_button_callback',
    'madeleine_social_options_page',
    'social_buttons_section',
    array( 'google_plus' )
  );
  
  add_settings_field( 
    'pinterest_button',
    'Pinterest',
    'madeleine_social_button_callback',
    'madeleine_social_options_page',
    'social_buttons_section',
    array( 'pinterest' )
  );
  
  add_settings_field( 
    'reddit_button',
    'Reddit',
    'madeleine_social_button_callback',
    'madeleine_social_options_page',
    'social_buttons_section',
    array( 'reddit' )
  );
  
  register_setting(
    'madeleine_social_options_group',
    'madeleine_social_options'
  );
  
} 
add_action( 'admin_init', 'madeleine_intialize_social_options' );


function madeleine_social_accounts_callback() {
  echo '<p>' . __( 'Provide the URL to the social accounts you would like to display. They will appear in the header as clickable icons, as well as in a footer list.', 'madeleine' ) . '</p>';
}


function madeleine_social_account_callback( $args ) {
  // First, we read the social settings collection
  $settings = get_option( 'madeleine_social_options' );
  $key = $args[0] . '_account';
  // Next, we need to make sure the element is defined in the settings. If not, we'll set an empty string.
  $url = '';
  if( isset( $settings[$key] ) )
    $url = esc_url( $settings[$key] );
  echo '<input class="regular-text" type="text" name="madeleine_social_options[' . $key . ']" value="' . $url . '">';
}


function madeleine_social_buttons_callback() {
  echo '<p>' . __( 'Choose the social sharing buttons you would like to display on a Single Post page.', 'madeleine' ) . '</p>';
}


function madeleine_social_button_callback( $args ) {
  $settings = get_option( 'madeleine_social_options' );
  $key = $args[0] . '_button';
  $html = '<label><input type="radio" name="madeleine_social_options[' . $key . ']" value="1"' . checked( 1, $settings[$key], false ) . '>';
  $html .= '&nbsp;Show</label>&nbsp;';
  $html .= '<label><input type="radio" name="madeleine_social_options[' . $key . ']" value="0"' . checked( 0, $settings[$key], false ) . '>';
  $html .= '&nbsp;Hide</label>';
  echo $html;
}


function madeleine_social_buttons_checkboxes_callback() {
  $settings = get_option( 'madeleine_social_options' );
  $html = '<label><input type="checkbox" name="madeleine_social_options[facebook_button]" value="1"' . checked( 1, $settings['facebook_button'], false ) . '>';
  $html .= '&nbsp;Facebook</label><br>';
  $html .= '<label><input type="checkbox" name="madeleine_social_options[twitter_button]" value="1"' . checked( 1, $settings['twitter_button'], false ) . '>';
  $html .= '&nbsp;Twitter</label><br>';
  $html .= '<label><input type="checkbox" name="madeleine_social_options[google_plus_button]" value="1"' . checked( 1, $settings['google_plus_button'], false ) . '>';
  $html .= '&nbsp;Google +</label><br>';
  $html .= '<label><input type="checkbox" name="madeleine_social_options[pinterest_button]" value="1"' . checked( 1, $settings['pinterest_button'], false ) . '>';
  $html .= '&nbsp;Pinterest</label><br>';
  $html .= '<label><input type="checkbox" name="madeleine_social_options[reddit_button]" value="1"' . checked( 1, $settings['reddit_button'], false ) . '>';
  $html .= '&nbsp;Reddit</label>';
  echo $html;
}



function madeleine_sanitize_social_accounts( $input ) {
  // Define the array for the updated settings
  $output = array();

  // Loop through each of the settings sanitizing the data
  foreach( $input as $key => $val ):
    if( isset ( $input[$key] ) )
      $output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
  endforeach;
  
  // Return the new collection
  return apply_filters( 'madeleine_sanitize_social_accounts', $output, $input );
}



?>