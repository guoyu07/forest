<?php

define( 'INCLUDE_DIR', get_template_directory() .'/includes' );
define( 'INCLUDE_URL', get_template_directory_uri() .'/includes' );

// Load the meta boxes

require_once( INCLUDE_DIR .'/meta-boxes/meta-functions.php' );
require_once( INCLUDE_DIR .'/meta-boxes/post-meta.php' );
require_once( INCLUDE_DIR .'/meta-boxes/review-meta.php' );

// Load the share count plugin

require_once( INCLUDE_DIR .'/share-count/get-share-count.php' );
require_once( INCLUDE_DIR .'/share-count/set-share-count.php' );

// Load the widgets

require_once( INCLUDE_DIR .'/widgets/latest-posts-widget.php' );
require_once( INCLUDE_DIR .'/widgets/popular-posts-widget.php' );
require_once( INCLUDE_DIR .'/widgets/images-widget.php' );
require_once( INCLUDE_DIR .'/widgets/videos-widget.php' );
require_once( INCLUDE_DIR .'/widgets/links-widget.php' );
require_once( INCLUDE_DIR .'/widgets/quotes-widget.php' );

// Add a stylesheet to the admin area

if ( !function_exists( 'madeleine_admin_css' ) ) {
  function madeleine_admin_css() {
    wp_enqueue_style( 'madeleine_admin_css', INCLUDE_URL .'/css/madeleine-admin.css' );
  }
}
add_action( 'admin_print_styles', 'madeleine_admin_css' );

// Add a custom JS to the admin area

if ( !function_exists( 'madeleine_enqueue_admin_scripts' ) ) {
  function madeleine_enqueue_admin_scripts() {
    wp_register_script( 'madeleine-admin', get_template_directory_uri() . '/includes/js/madeleine-admin.js', 'jquery' );
    wp_enqueue_script( 'madeleine-admin' );
  }
}
add_action( 'admin_enqueue_scripts', 'madeleine_enqueue_admin_scripts' );

// Setup a post format filter

if ( !function_exists( 'madeleine_admin_posts_filter' ) ) {
  function madeleine_admin_posts_filter( &$query ) {
    if ( is_admin() AND 'edit.php' === $GLOBALS['pagenow'] AND isset( $_GET['p_format'] ) AND $_GET['p_format'] != '-1' ):
      $query->query_vars['tax_query'] = array( array(
        'taxonomy' => 'post_format',
        'field'    => 'ID',
        'terms'    => array( $_GET['p_format'] )
      ) );
    endif;
  }
}
add_filter( 'parse_query', 'madeleine_admin_posts_filter' );

if ( !function_exists( 'madeleine_restrict_manage_posts_format' ) ) {
  function madeleine_restrict_manage_posts_format() {
    wp_dropdown_categories( array(
      'taxonomy'         => 'post_format',
      'hide_empty'       => 0,
      'name'             => 'p_format',
      'show_option_none' => __( 'View all formats', 'madeleine' )
    ));
  }
}
add_action( 'restrict_manage_posts', 'madeleine_restrict_manage_posts_format' );

?>