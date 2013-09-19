<?php

if ( !function_exists( 'madeleine_customize_register' ) ) {
	function madeleine_customize_register( $wp_customize ) {

		class Madeleine_Textarea_Control extends WP_Customize_Control {
			public $type	= 'textarea';
	 
			public function render_content() {
				?>
				<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
				</label>
				<?php
			}
		}



		/*
		** Colors section
		*/

		$wp_customize->add_setting(
			'madeleine_options_colors[main_color]',
			array(
				'default'			=> '#d0574e',
				'type'				=> 'option', 
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'madeleine_colors_main', 
				array(
					'label'			=> __( 'Main Color', 'madeleine' ), 
					'section'		=> 'colors',
					'settings'	=> 'madeleine_options_colors[main_color]'
				)
			)
		);


		/*
		** Home section
		*/


		$wp_customize->add_section(
			'madeleine_home_section',
			array(
				'title'				=> __( 'Home options', 'madeleine' ),
				'priority'		=> 80,
				'description'	=> __( 'Change the display of the homepage', 'madeleine' )
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[focus_status]',
			array(
				'default'			=> '1',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);
		
		$wp_customize->add_control(
			'madeleine_home_focus_status',
			array(
				'label'			=> __( 'Focus status', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[focus_status]',
				'priority'	=> 10,
				'type'			=> 'radio',
				'choices'		=> array(
					'1'	=> 'Show',
					'0'	=> 'Hide'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[grid_number]',
			array(
				'default'			=> '6',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_home_grid_number',
			array(
				'label'			=> __( 'Grid number', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[grid_number]',
				'priority'	=> 20,
				'type'			=> 'select',
				'choices'		=> array(
					'2'		=> '2 posts',
					'4'		=> '4 posts',
					'6'		=> '6 posts',
					'8'		=> '8 posts',
					'10'	=> '10 posts',
					'12'	=> '12 posts'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[next_status]',
			array(
				'default'			=> '1',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_home_next_status',
			array(
				'label'			=> __( 'Next posts status', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[next_status]',
				'priority'	=> 30,
				'type'			=> 'radio',
				'choices'		=> array(
					'1'	=> 'Show',
					'0'	=> 'Hide'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[next_number]',
			array(
				'default'			=> '10',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_home_next_number',
			array(
				'label'			=> __( 'Next posts number', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[next_number]',
				'priority'	=> 40,
				'type'			=> 'select',
				'choices'		=> array(
					'2'		=> '2 posts',
					'4'		=> '4 posts',
					'6'		=> '6 posts',
					'8'		=> '8 posts',
					'10'	=> '10 posts',
					'12'	=> '12 posts'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[category_tabs_status]',
			array(
				'default'			=> '1',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_home_category_tabs_status',
			array(
				'label'			=> __( 'Category tabs', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[category_tabs_status]',
				'priority'	=> 50,
				'type'			=> 'radio',
				'choices'		=> array(
					'1'	=> 'Show categories as tabs',
					'0'	=> 'Show all categories one after the other'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[reviews_status]',
			array(
				'default'			=> '1',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_home_reviews_status',
			array(
				'label'			=> __( 'Reviews status', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[reviews_status]',
				'priority'	=> 60,
				'type'			=> 'radio',
				'choices'		=> array(
					'1'	=> 'Show reviews on homepage',
					'0'	=> 'Hide reviews on homepage'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_home[reviews_number]',
			array(
				'default'			=> '6',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_home_reviews_number',
			array(
				'label'			=> __( 'Reviews number', 'madeleine' ),
				'section'		=> 'madeleine_home_section',
				'settings'	=> 'madeleine_options_home[reviews_number]',
				'priority'	=> 70,
				'type'			=> 'select',
				'choices'		=> array(
					'3'	=> '3 reviews',
					'6'	=> '6 reviews',
					'9'	=> '9 reviews'
				)
			)
		);


		/*
		** Social accounts section
		*/


		$wp_customize->add_section(
			'madeleine_social_accounts_section',
			array(
				'title'				=> __( 'Social accounts', 'madeleine' ),
				'priority'		=> 81,
				'description'	=> __( 'Add your social accounts', 'madeleine' )
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_social[social_accounts][twitter_account]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_social_twitter_account',
			array(
				'label'			=> __( 'Twitter username (like "haxokeno")', 'madeleine' ),
				'section'		=> 'madeleine_social_accounts_section',
				'settings'	=> 'madeleine_options_social[social_accounts][twitter_account]',
				'priority'	=> 10,
				'type'			=> 'text'
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_social[social_accounts][facebook_account]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_social_facebook_account',
			array(
				'label'			=> __( 'Facebook page URL', 'madeleine' ),
				'section'		=> 'madeleine_social_accounts_section',
				'settings'	=> 'madeleine_options_social[social_accounts][facebook_account]',
				'priority'	=> 20,
				'type'			=> 'text'
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_social[social_accounts][googleplus_account]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_social_googleplus_account',
			array(
				'label'			=> __( 'Google Plus URL', 'madeleine' ),
				'section'		=> 'madeleine_social_accounts_section',
				'settings'	=> 'madeleine_options_social[social_accounts][googleplus_account]',
				'priority'	=> 30,
				'type'			=> 'text'
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_social[social_accounts][tumblr_account]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_social_tumblr_account',
			array(
				'label'			=> __( 'Tumblr URL', 'madeleine' ),
				'section'		=> 'madeleine_social_accounts_section',
				'settings'	=> 'madeleine_options_social[social_accounts][tumblr_account]',
				'priority'	=> 30,
				'type'			=> 'text'
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_social[social_accounts][youtube_account]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_social_youtube_account',
			array(
				'label'			=> __( 'YouTube URL', 'madeleine' ),
				'section'		=> 'madeleine_social_accounts_section',
				'settings'	=> 'madeleine_options_social[social_accounts][youtube_account]',
				'priority'	=> 30,
				'type'			=> 'text'
			)
		);


		/*
		** Social buttons section
		*/


		$wp_customize->add_section(
			'madeleine_social_buttons_section',
			array(
				'title'				=> __( 'Social buttons', 'madeleine' ),
				'priority'		=> 82,
				'description'	=> __( 'Add social buttons to the Single page', 'madeleine' )
			)
		);

		$social_buttons	= ['twitter', 'facebook', 'googleplus', 'pinterest', 'reddit'];

		foreach( $social_buttons as $social_button):
			$wp_customize->add_setting(
				'madeleine_options_social[social_buttons][' . $social_button . '_button]',
				array(
					'default'			=> '1',
					'type'				=> 'option',
					'capability'	=> 'edit_theme_options'
				)
			);

			if ($social_button	== 'googleplus' ):
				$label	= 'Google +';
			else:
				$label	= ucwords( $social_button );
			endif;

			$wp_customize->add_control(
				'madeleine_social_' . $social_button . '_button',
				array(
					'label'			=> $label . ' button',
					'section'		=> 'madeleine_social_buttons_section',
					'settings'	=> 'madeleine_options_social[social_buttons][' . $social_button . '_button]',
					'priority'	=> 10,
					'type'			=> 'checkbox'
				)
			);
		endforeach;


		/*
		** Reviews section
		*/


		$wp_customize->add_section(
			'madeleine_reviews_section',
			array(
				'title'				=> __( 'Reviews options', 'madeleine' ),
				'priority'		=> 83,
				'description'	=> __( 'Change the reviews color, rating range, and price range', 'madeleine' )
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_reviews[color]',
			array(
				'default'			=> '#276791',
				'type'				=> 'option', 
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'madeleine_reviews_color', 
				array(
					'label'				=> __( 'Color', 'madeleine' ), 
					'section'			=> 'madeleine_reviews_section',
					'settings'		=> 'madeleine_options_reviews[color]',
					'priority'		=> 10
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_reviews[maximum_rating]',
			array(
				'default'			=> '10',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_reviews_maximum_rating',
			array(
				'label'			=> __( 'Maximum rating', 'madeleine' ),
				'section'		=> 'madeleine_reviews_section',
				'settings'	=> 'madeleine_options_reviews[maximum_rating]',
				'priority'	=> 20,
				'type'			=> 'select',
				'choices'		=> array(
					'10'	=> '10',
					'20'	=> '20',
					'50'	=> '50',
					'100'	=> '100'
				)
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_reviews[maximum_price]',
			array(
				'default'			=> '2000',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_reviews_maximum_price',
			array(
				'label'			=> __( 'Maximum price', 'madeleine' ),
				'section'		=> 'madeleine_reviews_section',
				'settings'	=> 'madeleine_options_reviews[maximum_price]',
				'priority'	=> 30,
				'type'			=> 'select',
				'choices'		=> array(
					'100'		=> '100',
					'200'		=> '200',
					'300'		=> '300',
					'400'		=> '400',
					'500'		=> '500',
					'1000'	=> '1000',
					'2000'	=> '2000',
					'3000'	=> '3000',
					'4000'	=> '4000',
					'5000'	=> '5000',
					'10000'	=> '10000'
				)
			)
		);


		/*
		** Analytics section
		*/


		$wp_customize->add_section(
			'madeleine_analytics_section',
			array(
				'title'				=> __( 'Feedburner & Analytics', 'madeleine' ),
				'priority'		=> 84,
				'description'	=> __( 'Add your Feedburner URL and your analytics script', 'madeleine' )
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_analytics[feedburner_url]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			'madeleine_analytics_feedburner_url',
			array(
				'label'			=> __( 'Feedburner URL', 'madeleine' ),
				'section'		=> 'madeleine_analytics_section',
				'settings'	=> 'madeleine_options_analytics[feedburner_url]',
				'priority'	=> 10,
				'type'			=> 'text'
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_analytics[tracking_code]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			new Madeleine_Textarea_Control(
				$wp_customize,
				'madeleine_analytics_tracking_code', 
				array(
					'label'			=> __( 'Tracking code (e.g. Google Analytics)', 'madeleine' ), 
					'section'		=> 'madeleine_analytics_section',
					'settings'	=> 'madeleine_options_analytics[tracking_code]',
					'priority'	=> 20
				)
			)
		);


		/*
		** Custom CSS
		*/


		$wp_customize->add_section(
			'madeleine_css_section',
			array(
				'title'				=> __( 'Custom CSS', 'madeleine' ),
				'priority'		=> 85,
				'description'	=> __( 'Add your own CSS code', 'madeleine' )
			)
		);

		$wp_customize->add_setting(
			'madeleine_options_css[custom_code]',
			array(
				'default'			=> '',
				'type'				=> 'option',
				'capability'	=> 'edit_theme_options'
			)
		);

		$wp_customize->add_control(
			new Madeleine_Textarea_Control(
				$wp_customize,
				'madeleine_css_custom_code', 
				array(
					'label'			=> __( 'CSS code', 'madeleine' ), 
					'section'		=> 'madeleine_css_section',
					'settings'	=> 'madeleine_options_css[custom_code]',
					'priority'	=> 10
				)
			)
		);


	}
}
add_action( 'customize_register', 'madeleine_customize_register' );

?>