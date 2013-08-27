<?php

define( 'INCLUDE_DIR', get_template_directory() .'/includes' );
define( 'INCLUDE_URL', get_template_directory_uri() .'/includes' );

// Load the post meta boxes

require_once( INCLUDE_DIR .'/meta/post-meta.php' );

// Load the Widgets

require_once( INCLUDE_DIR .'/widgets/latest-posts-widget.php' );
require_once( INCLUDE_DIR .'/widgets/popular-posts-widget.php' );
require_once( INCLUDE_DIR .'/widgets/images-widget.php' );
require_once( INCLUDE_DIR .'/widgets/videos-widget.php' );
require_once( INCLUDE_DIR .'/widgets/links-widget.php' );
require_once( INCLUDE_DIR .'/widgets/quotes-widget.php' );

// Add a stylesheet to the admin area

function madeleine_admin_css() {
  wp_enqueue_style( 'madeleine_admin_css', INCLUDE_URL .'/css/madeleine-admin.css' );
}
add_action( 'admin_print_styles', 'madeleine_admin_css' );

?>