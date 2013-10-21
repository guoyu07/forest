<?php

if ( !function_exists( 'madeleine_default_options_social' ) ) {
	function madeleine_default_options_social() {
		$defaults = array(
			'accounts' => array(
				'twitter' => '',
				'facebook' => '',
				'google' => '',
				'tumblr' => '',
				'youtube' => ''
			),
			'buttons' => array(
				'twitter' => 1,
				'facebook' => 1,
				'google' => 1,
				'pinterest' => 1,
				'reddit' => 1
			)
		);
	return apply_filters( 'madeleine_default_options_social', $defaults );
	}
}

if ( !function_exists( 'madeleine_intialize_social_options' ) ) {
	function madeleine_intialize_social_options() {
		if( false == get_option( 'madeleine_options_social' ) ):
			add_option( 'madeleine_options_social', apply_filters( 'madeleine_default_options_social', madeleine_default_options_social() ) );
		endif;

		add_settings_section(
			'social_accounts_section', // ID used to identify this section and with which to register settings
			__( 'Social Accounts', 'madeleine' ), // Title to be displayed on the administration page
			'madeleine_social_accounts_section_callback', // Callback used to render the description of the section
			'madeleine_social_options_page' // Page on which to add this section of settings
		);
		
		add_settings_field( 
			'twitter_account',
			'Twitter',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_accounts_section',
			array(
				'description' => 'Your Twitter username (without the @).',
				'type' => 'text',
				'option' => 'madeleine_options_social',
				'id' => 'twitter',
				'sub' => 'accounts'
			)
		);

		add_settings_field( 
			'facebook_account',
			'Facebook',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_accounts_section',
			array(
				'description' => 'Your Facebook page URL.',
				'type' => 'text',
				'option' => 'madeleine_options_social',
				'id' => 'facebook',
				'sub' => 'accounts'
			)
		);
		
		add_settings_field( 
			'google_account',
			'Google+',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_accounts_section',
			array(
				'description' => 'Your Google + page URL.',
				'type' => 'text',
				'option' => 'madeleine_options_social',
				'id' => 'google',
				'sub' => 'accounts'
			)
		);
		
		add_settings_field( 
			'tumblr_account',
			'Tumblr',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_accounts_section',
			array(
				'description' => 'Your Tumblr URL.',
				'type' => 'text',
				'option' => 'madeleine_options_social',
				'id' => 'tumblr',
				'sub' => 'accounts'
			)
		);
		
		add_settings_field( 
			'youtube_account',
			'YouTube',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_accounts_section',
			array(
				'description' => 'Your YouTube username.',
				'type' => 'text',
				'option' => 'madeleine_options_social',
				'id' => 'youtube',
				'sub' => 'accounts'
			)
		);
		
		add_settings_section(
			'social_buttons_section',
			__( 'Social Buttons', 'madeleine' ),
			'madeleine_social_buttons_section_callback',
			'madeleine_social_options_page'
		);
		
		add_settings_field( 
			'twitter_button',
			'Twitter',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_buttons_section',
			array(
				'description' => '',
				'type' => 'radio',
				'option' => 'madeleine_options_social',
				'id' => 'twitter',
				'sub' => 'buttons',
				'image' => 'twitter-button'
			)
		);
		
		add_settings_field( 
			'facebook_button',
			'Facebook',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_buttons_section',
			array(
				'description' => '',
				'type' => 'radio',
				'option' => 'madeleine_options_social',
				'id' => 'facebook',
				'sub' => 'buttons',
				'image' => 'facebook-button'
			)
		);
		
		add_settings_field( 
			'google_button',
			'Google +',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_buttons_section',
			array(
				'description' => '',
				'type' => 'radio',
				'option' => 'madeleine_options_social',
				'id' => 'google',
				'sub' => 'buttons',
				'image' => 'google-button'
			)
		);
		
		add_settings_field( 
			'pinterest_button',
			'Pinterest',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_buttons_section',
			array(
				'description' => '',
				'type' => 'radio',
				'option' => 'madeleine_options_social',
				'id' => 'pinterest',
				'sub' => 'buttons',
				'image' => 'pinterest-button'
			)
		);
		
		add_settings_field( 
			'reddit_button',
			'Reddit',
			'madeleine_field_callback',
			'madeleine_social_options_page',
			'social_buttons_section',
			array(
				'description' => '',
				'type' => 'radio',
				'option' => 'madeleine_options_social',
				'id' => 'reddit',
				'sub' => 'buttons',
				'image' => 'reddit-button'
			)
		);
		
		register_setting(
			'madeleine_social_options_group',
			'madeleine_options_social'
		);
		
	}
}
add_action( 'admin_init', 'madeleine_intialize_social_options' );


if ( !function_exists( 'madeleine_social_accounts_section_callback' ) ) {
	function madeleine_social_accounts_section_callback() {
		echo '<p>' . __( 'Provide the URL to the social accounts you would like to display. They will appear in the header as clickable icons, as well as in a footer list.', 'madeleine' ) . '</p>';
	}
}


if ( !function_exists( 'madeleine_social_account_callback' ) ) {
	function madeleine_social_account_callback( $args ) {
		$options = get_option( 'madeleine_options_social' );
		$social_accounts = $options['account'];
		$key = $args[0];
		$url = '';
		if( isset( $social_accounts[$key] ) ):
			$url = $social_accounts[$key];
		endif;
		echo '<input class="regular-text" type="text" name="madeleine_options_social[account][' . $key . ']" value="' . $url . '">';
	}
}


if ( !function_exists( 'madeleine_social_buttons_section_callback' ) ) {
	function madeleine_social_buttons_section_callback() {
		echo '<p>' . __( 'Choose the social sharing buttons you would like to display on a Single Post page.', 'madeleine' ) . '</p>';
	}
}


if ( !function_exists( 'madeleine_social_button_callback' ) ) {
	function madeleine_social_button_callback( $args ) {
		$options = get_option( 'madeleine_options_social' );
		$social_buttons = $options['button'];
		$key = $args[0];
		$html = '<label><input type="radio" name="madeleine_options_social[button][' . $key . ']" value="1"' . checked( 1, $social_buttons[$key], false ) . '>';
		$html .= '&nbsp;Show</label>&nbsp;';
		$html .= '<label><input type="radio" name="madeleine_options_social[button][' . $key . ']" value="0"' . checked( 0, $social_buttons[$key], false ) . '>';
		$html .= '&nbsp;Hide</label>';
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