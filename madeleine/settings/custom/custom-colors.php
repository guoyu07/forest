<?php

function madeleine_customize_register( $wp_customize ) {
  $colors = array();
  $colors[] = array(
    'slug' => 'madeleine_main_color', 
    'default' => '#d0574e',
    'label' => __( 'Main Color', 'madeleine')
  );
  $colors[] = array(
    'slug' => 'madeleine_reviews_color', 
    'default' => '#276791',
    'label' => __( 'Reviews Color', 'madeleine')
  );
  foreach( $colors as $color ):
    // Settings
    $wp_customize->add_setting(
      $color['slug'],
      array(
        'default' => $color['default'],
        'type' => 'option', 
        'capability' => 'edit_theme_options'
      )
    );
    // Controls
    $wp_customize->add_control(
      new WP_Customize_Color_Control(
        $wp_customize,
        $color['slug'], 
        array(
          'label' => $color['label'], 
          'section' => 'colors',
          'settings' => $color['slug']
        )
      )
    );
  endforeach;
}
add_action( 'customize_register', 'madeleine_customize_register' );

?>