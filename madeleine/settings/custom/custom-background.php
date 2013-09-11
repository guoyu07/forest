<?php

if ( !function_exists( 'madeleine_custom_background_setup' ) ) {
  function madeleine_custom_background_setup() {
    $defaults = array(
      'default-color'          => 'f2f4f6',
      'default-image'          => '%s/images/body.png',
      'wp-head-callback'       => 'madeleine_background_style',
      'admin-head-callback'    => '',
      'admin-preview-callback' => ''
    );
    add_theme_support( 'custom-background', $defaults );
  }
}
add_action( 'after_setup_theme', 'madeleine_custom_background_setup' );


if ( !function_exists( 'madeleine_background_style' ) ) {
  function madeleine_background_style() {
    $background_image = get_background_image();
    $background_color = get_background_color();

    if ( empty( $background_image ) && $background_color == get_theme_support( 'custom-background', 'default-color' ) )
      return;

    $style = '';

    if ( !empty( $background_image ) ) :
      $image = " background-image: url($background_image);";
      $repeat = get_theme_mod( 'background_repeat', 'repeat' );

      if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
          $repeat = 'repeat';
      $repeat = " background-repeat: $repeat;";

      $position = get_theme_mod( 'background_position_x', 'left' );
      if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
          $position = 'left';
      $position = " background-position: top $position;";

      $attachment = get_theme_mod( 'background_attachment', 'scroll' );
      if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
          $attachment = 'scroll';
      $attachment = " background-attachment: $attachment;";

      $style .= $image . $repeat . $position . $attachment;
    endif;

    if ( !empty( $background_color ) ) :
     $style .= " background-color: #$background_color;";
    endif;
    
    echo '<style>body{' . $style . '}</style>';
  }
}

?>