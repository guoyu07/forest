<?php

if ( !function_exists( 'madeleine_default_options_typography' ) ) {
	function madeleine_default_options_typography() {
		$defaults = array(
			'font_body' => '',
			'font_title' => ''
		);
	return apply_filters( 'madeleine_default_options_typography', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_typography_options' ) ) {
	function madeleine_initialize_typography_options() {
		if( false == get_option( 'madeleine_options_typography' ) ):
			add_option( 'madeleine_options_typography', apply_filters( 'madeleine_default_options_typography', madeleine_default_options_typography() ) );
		endif;

		add_settings_section(
			'typography_section',
			__( 'Typography options', 'madeleine' ),
			'madeleine_typography_section_callback',
			'madeleine_typography_options_page'
		);
		
		add_settings_field( 
			'font_body',
			__( 'Body Font', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_typography_options_page',
			'typography_section',
			array(
				'description' => 'The font used for body text.<br>Default: <strong>Arial</strong>',
				'type' => 'font',
				'option' => 'madeleine_options_typography',
				'id' => 'font_body',
				'selectid' => 'body',
				'select' => array(
					array('', 'Arial'),
					array('bitter', 'Bitter'),
					array('droidsans', 'Droid Sans'),
					array('lato', 'Lato'),
					array('arvo', 'Arvo'),
					array('ptsans', 'PT Sans'),
					array('ubuntu', 'Ubuntu'),
					array('droidserif', 'Droid Serif'),
					array('opensans', 'Open Sans'),
					array('oswald', 'Oswald'),
					array('roboto', 'Roboto'),
					array('montserrat', 'Montserrat'),
					array('nunito', 'Nunito'),
					array('francois', 'Francois One'),
					array('merriweather', 'Merriweather'),
					array('merriweathersans', 'Merriweather Sans'),
					array('gentiumbookbasic', 'Gentium Book Basic')
				)
			)
		);
		
		add_settings_field( 
			'font_title',
			__( 'Title Font', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_typography_options_page',
			'typography_section',
			array(
				'description' => 'The font used for titles, headings, buttons, the navigation bar and other elements.<br>Default: <strong>Bitter</strong>',
				'type' => 'font',
				'option' => 'madeleine_options_typography',
				'id' => 'font_title',
				'selectid' => 'title',
				'select' => array(
					array('', 'Arial'),
					array('bitter', 'Bitter'),
					array('droidsans', 'Droid'),
					array('lato', 'Lato'),
					array('arvo', 'Arvo'),
					array('ptsans', 'PT Sans'),
					array('ubuntu', 'Ubuntu'),
					array('droidserif', 'Droid Serif'),
					array('opensans', 'Open Sans'),
					array('oswald', 'Oswald'),
					array('roboto', 'Roboto'),
					array('montserrat', 'Montserrat'),
					array('nunito', 'Nunito'),
					array('francois', 'Francois One'),
					array('merriweather', 'Merriweather'),
					array('merriweathersans', 'Merriweather Sans'),
					array('gentiumbookbasic', 'Gentium Book Basic')
				)
			)
		);
		
		register_setting(
			'madeleine_typography_options_group',
			'madeleine_options_typography'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_typography_options' );


if ( !function_exists( 'madeleine_typography_section_callback' ) ) {
	function madeleine_typography_section_callback() {
		echo '<p>Configure the general settings of your theme. Upload your favicon, setup your feed, insert your analytics tracking code, your custom CSS and your footer\'s text.</p>';
	}
}


?>