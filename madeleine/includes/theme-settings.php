?><?php

add_theme_support( 'post-formats', array( 'image', 'video', 'link', 'quote', ) );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-background' );
add_theme_support( 'custom-header' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'menus' );


add_image_size( 'thumbnail', 100, 100, true );
add_image_size( 'medium', 300, 150, true );
add_image_size( 'large', 640, 230, true );
add_image_size( 'tall', 340, 320, true );
add_image_size( 'focus', 680, 320, true );
add_image_size( 'wide', 340, 160, true );
add_image_size( 'panorama', 1020, 360, true );


$sidebar_arguments = array(
  'name'          => 'Sidebar',
  'before_widget' => '<section id="%1$s" class="widget %2$s">',
  'after_widget'  => '</section>',
  'before_title'  => '<h4 class="widget-title">',
  'after_title'   => '</h4>'
);


if ( function_exists('register_sidebar') )
  register_sidebar( $sidebar_arguments );