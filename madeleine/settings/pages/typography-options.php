<?php

if ( !function_exists( 'madeleine_default_options_typography' ) ) {
	function madeleine_default_options_typography() {
		$defaults = array(
			'font_body' => '',
			'font_title' => 'bitter',
			'font_name' => 'bitter',
			'font_description' => 'bitter'
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
			__( 'Body font', 'madeleine' ),
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
					array('opensans', 'Open Sans'),
					array('ubuntu', 'Ubuntu'),
					array('droidsans', 'Droid Sans'),
					array('lato', 'Lato'),
					array('sourcesans', 'Source Sans Pro'),
					array('roboto', 'Roboto'),
					array('ptsans', 'PT Sans'),
					array('nunito', 'Nunito'),
					array('merriweathersans', 'Merriweather Sans'),
					array('bitter', 'Bitter'),
					array('montserrat', 'Montserrat'),
					array('arvo', 'Arvo'),
					array('oswald', 'Oswald'),
					array('merriweather', 'Merriweather'),
					array('francois', 'Francois One'),
					array('droidserif', 'Droid Serif'),
					array('gentiumbookbasic', 'Gentium Book Basic')
				)
			)
		);
		
		add_settings_field( 
			'font_title',
			__( 'Title font', 'madeleine' ),
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
					array('bitter', 'Bitter'),
					array('montserrat', 'Montserrat'),
					array('arvo', 'Arvo'),
					array('oswald', 'Oswald'),
					array('merriweather', 'Merriweather'),
					array('francois', 'Francois One'),
					array('droidserif', 'Droid Serif'),
					array('gentiumbookbasic', 'Gentium Book Basic'),
					array('', 'Arial'),
					array('opensans', 'Open Sans'),
					array('ubuntu', 'Ubuntu'),
					array('droidsans', 'Droid Sans'),
					array('lato', 'Lato'),
					array('sourcesans', 'Source Sans Pro'),
					array('roboto', 'Roboto'),
					array('ptsans', 'PT Sans'),
					array('nunito', 'Nunito'),
					array('merriweathersans', 'Merriweather Sans')
				)
			)
		);
		
		add_settings_field( 
			'font_name',
			__( 'Header Name font', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_typography_options_page',
			'typography_section',
			array(
				'description' => 'The font used for the site\'s name in the header.<br>Default: <strong>Bitter</strong>',
				'type' => 'font',
				'option' => 'madeleine_options_typography',
				'id' => 'font_name',
				'selectid' => 'name',
				'select' => array(
					array('bitter', 'Bitter'),
					array('montserrat', 'Montserrat'),
					array('arvo', 'Arvo'),
					array('oswald', 'Oswald'),
					array('merriweather', 'Merriweather'),
					array('francois', 'Francois One'),
					array('droidserif', 'Droid Serif'),
					array('gentiumbookbasic', 'Gentium Book Basic'),
					array('', 'Arial'),
					array('opensans', 'Open Sans'),
					array('ubuntu', 'Ubuntu'),
					array('droidsans', 'Droid Sans'),
					array('lato', 'Lato'),
					array('sourcesans', 'Source Sans Pro'),
					array('roboto', 'Roboto'),
					array('ptsans', 'PT Sans'),
					array('nunito', 'Nunito'),
					array('merriweathersans', 'Merriweather Sans')
				)
			)
		);
		
		add_settings_field( 
			'font_description',
			__( 'Header Description font', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_typography_options_page',
			'typography_section',
			array(
				'description' => 'The font used for the site\'s description in the header.<br>Default: <strong>Bitter</strong>',
				'type' => 'font',
				'option' => 'madeleine_options_typography',
				'id' => 'font_description',
				'selectid' => 'description',
				'select' => array(
					array('bitter', 'Bitter'),
					array('montserrat', 'Montserrat'),
					array('arvo', 'Arvo'),
					array('oswald', 'Oswald'),
					array('merriweather', 'Merriweather'),
					array('francois', 'Francois One'),
					array('droidserif', 'Droid Serif'),
					array('gentiumbookbasic', 'Gentium Book Basic'),
					array('', 'Arial'),
					array('opensans', 'Open Sans'),
					array('ubuntu', 'Ubuntu'),
					array('droidsans', 'Droid Sans'),
					array('lato', 'Lato'),
					array('sourcesans', 'Source Sans Pro'),
					array('roboto', 'Roboto'),
					array('ptsans', 'PT Sans'),
					array('nunito', 'Nunito'),
					array('merriweathersans', 'Merriweather Sans')
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