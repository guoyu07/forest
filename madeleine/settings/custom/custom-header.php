<?php

function madeleine_custom_header_setup() {
  $args = array(
    'default-image'          => '%s/images/logo.png',
    'default-text-color'     => 'd0574e',
    'height'                 => 60,
    'width'                  => 60,
    'wp-head-callback'       => 'madeleine_header_style',
    'admin-head-callback'    => 'madeleine_admin_header_style',
    'admin-preview-callback' => 'madeleine_admin_header_image',
  );
  add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'madeleine_custom_header_setup' );


function madeleine_header_style() {
  $header_image = get_header_image();
  $text_color   = get_header_textcolor();

  if ( empty( $header_image ) && $text_color == get_theme_support( 'custom-header', 'default-text-color' ) )
    return;
  ?>

  <style id="madeleine-header-css" type="text/css">
    <?php if ( !empty( $header_image ) ) : ?>
      #logo{ background: url(<?php echo $header_image; ?>) no-repeat center left; padding-left: 80px;}
    <?php endif; ?>

    <?php if ( !display_header_text() ) : ?>
      #title,
      #description{ display: none;}
      <?php if ( !empty( $header_image ) ) : ?>
        #logo{ padding-left: 0; width: 60px;}
      <?php endif; ?>
    <?php  elseif ( $text_color != get_theme_support( 'custom-header', 'default-text-color' ) ) : ?>
      #title{ color: #<?php echo esc_attr( $text_color ); ?>;}
    <?php endif; ?>
  </style>
  <?php
}


function madeleine_admin_header_style() {
  $header_image = get_header_image();
  ?>
  <style id="madeleine-admin-header-css" type="text/css">
  .appearance_page_custom-header #headimg{ height: 60px; padding: 20px 0;}
  #headimg .home-link{ background: url(<?php echo $header_image; ?>) no-repeat center left; display: block; float: left; height: 60px;}
  #headimg h1,
  #headimg h2{ font-family: Bitter, Georgia, serif; line-height: 1; margin: 0; padding: 0; text-shadow: none;}
  #headimg h1{ color: #d0574e; font-size: 22px; font-weight: bold; padding-top: 6px;}
  #headimg h2{ color: #708491; font-size: 16px; font-style: italic; padding-top: 4px;}
  #headimg h1 a{ text-decoration: none;}
  <?php if ( !empty( $header_image ) ) : ?>
    #headimg .home-link{ padding-left: 80px;}
  <?php endif; ?>
  </style>
<?php
}


function madeleine_admin_header_image() {
  ?>
  <div id="headimg" style="background-color: #<?php echo get_background_color(); ?>;">
    <?php $style = ' style=" color: #' . get_header_textcolor() . ';"'; ?>
    <div class="home-link">
      <h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="#"><?php bloginfo( 'name' ); ?></a></h1>
      <h2 class="displaying-header-text"><?php bloginfo( 'description' ); ?></h2>
    </div>
  </div>
  <?php
}

?>