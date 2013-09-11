<?php

if ( !function_exists( 'madeleine_default_social_options' ) ) {
  function madeleine_default_social_options() {
    $defaults = array(
      'social_accounts' => array(
        'twitter_account' => '',
        'facebook_account' => '',
        'googleplus_account' => '',
        'tumblr_account' => '',
        'youtube_account' => ''
      ),
      'social_buttons' => array(
        'twitter_button' => 1,
        'facebook_button' => 1,
        'googleplus_button' => 1,
        'pinterest_button' => 1,
        'reddit_button' => 1
      )
    );
    return apply_filters( 'madeleine_default_social_options', $defaults );
  }
}


if ( !function_exists( 'madeleine_intialize_social_options' ) ) {
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
      'googleplus_account',
      'Google+',
      'madeleine_social_account_callback',
      'madeleine_social_options_page',
      'social_accounts_section',
      array( 'googleplus' )
    );
    
    add_settings_field( 
      'tumblr_account',
      'Tumblr',
      'madeleine_social_account_callback',
      'madeleine_social_options_page',
      'social_accounts_section',
      array( 'tumblr' )
    );
    
    add_settings_field( 
      'youtube_account',
      'YouTube',
      'madeleine_social_account_callback',
      'madeleine_social_options_page',
      'social_accounts_section',
      array( 'youtube' )
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
      'googleplus_button',
      'Google +',
      'madeleine_social_button_callback',
      'madeleine_social_options_page',
      'social_buttons_section',
      array( 'googleplus' )
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
}
add_action( 'admin_init', 'madeleine_intialize_social_options' );


if ( !function_exists( 'madeleine_social_accounts_callback' ) ) {
  function madeleine_social_accounts_callback() {
    echo '<p>' . __( 'Provide the URL to the social accounts you would like to display. They will appear in the header as clickable icons, as well as in a footer list.', 'madeleine' ) . '</p>';
  }
}


if ( !function_exists( 'madeleine_social_account_callback' ) ) {
  function madeleine_social_account_callback( $args ) {
    // First, we read the social settings collection
    $settings = get_option( 'madeleine_social_options' );
    $social_accounts = $settings['social_accounts'];
    $key = $args[0] . '_account';
    // Next, we need to make sure the element is defined in the settings. If not, we'll set an empty string.
    $url = '';
    if( isset( $social_accounts[$key] ) )
      $url = $social_accounts[$key];
    echo '<input class="regular-text" type="text" name="madeleine_social_options[social_accounts][' . $key . ']" value="' . $url . '">';
  }
}


if ( !function_exists( 'madeleine_social_buttons_callback' ) ) {
  function madeleine_social_buttons_callback() {
    echo '<p>' . __( 'Choose the social sharing buttons you would like to display on a Single Post page.', 'madeleine' ) . '</p>';
  }
}


if ( !function_exists( 'madeleine_social_button_callback' ) ) {
  function madeleine_social_button_callback( $args ) {
    $settings = get_option( 'madeleine_social_options' );
    $social_buttons = $settings['social_buttons'];
    $key = $args[0] . '_button';
    $html = '<label><input type="radio" name="madeleine_social_options[social_buttons][' . $key . ']" value="1"' . checked( 1, $social_buttons[$key], false ) . '>';
    $html .= '&nbsp;Show</label>&nbsp;';
    $html .= '<label><input type="radio" name="madeleine_social_options[social_buttons][' . $key . ']" value="0"' . checked( 0, $social_buttons[$key], false ) . '>';
    $html .= '&nbsp;Hide</label>';
    echo $html;
  }
}


if ( !function_exists( 'madeleine_social_buttons_checkboxes_callback' ) ) {
  function madeleine_social_buttons_checkboxes_callback() {
    $settings = get_option( 'madeleine_social_options' );
    $social_buttons = $settings['social_buttons'];
    $html = '<label><input type="checkbox" name="madeleine_social_options[social_buttons][facebook_button]" value="1"' . checked( 1, $social_buttons['facebook_button'], false ) . '>';
    $html .= '&nbsp;Facebook</label><br>';
    $html .= '<label><input type="checkbox" name="madeleine_social_options[social_buttons][twitter_button]" value="1"' . checked( 1, $social_buttons['twitter_button'], false ) . '>';
    $html .= '&nbsp;Twitter</label><br>';
    $html .= '<label><input type="checkbox" name="madeleine_social_options[social_buttons][googleplus_button]" value="1"' . checked( 1, $social_buttons['googleplus_button'], false ) . '>';
    $html .= '&nbsp;Google +</label><br>';
    $html .= '<label><input type="checkbox" name="madeleine_social_options[social_buttons][pinterest_button]" value="1"' . checked( 1, $social_buttons['pinterest_button'], false ) . '>';
    $html .= '&nbsp;Pinterest</label><br>';
    $html .= '<label><input type="checkbox" name="madeleine_social_options[social_buttons][reddit_button]" value="1"' . checked( 1, $social_buttons['reddit_button'], false ) . '>';
    $html .= '&nbsp;Reddit</label>';
    echo $html;
  }
}



if ( !function_exists( 'madeleine_sanitize_social_accounts' ) ) {
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
}



?>