<?php

define( 'INCLUDE_DIR', get_template_directory() .'/includes' );
define( 'INCLUDE_URL', get_template_directory_uri() .'/includes' );


/**
 * Load the meta boxes for links, quotes, videos and reviews.
 *
 */

require_once( INCLUDE_DIR .'/meta-boxes/meta-functions.php' );
require_once( INCLUDE_DIR .'/meta-boxes/post-meta.php' );
require_once( INCLUDE_DIR .'/meta-boxes/review-meta.php' );


/**
 * Load the share count plugin.
 * It retrives the share count of Twitter, Facebook, Google +, Pinterest, and Reddit.
 *
 */

require_once( INCLUDE_DIR .'/share-count/get-share-count.php' );
require_once( INCLUDE_DIR .'/share-count/set-share-count.php' );


/**
 * Load the custom Madeleine widgets.
 *
 */

require_once( INCLUDE_DIR .'/widgets/latest-posts-widget.php' );
require_once( INCLUDE_DIR .'/widgets/popular-posts-widget.php' );
require_once( INCLUDE_DIR .'/widgets/images-widget.php' );
require_once( INCLUDE_DIR .'/widgets/videos-widget.php' );
require_once( INCLUDE_DIR .'/widgets/links-widget.php' );
require_once( INCLUDE_DIR .'/widgets/quotes-widget.php' );


/**
 * Register a customizable top menu.
 *
 */

if ( !function_exists( 'madeleine_top_menu' ) ) {
	function madeleine_top_menu() {
		register_nav_menus(
			array(
				'top-menu' => __( 'Top Menu', 'madeleine' ),
				'footer-menu' => __( 'Footer Menu', 'madeleine' )
			)
		);
	}
}
add_action( 'init', 'madeleine_top_menu' );


/**
 * Add a stylesheet for the WYSIWYG editor.
 *
 */

if ( !function_exists( 'madeleine_editor_style' ) ) {
	function madeleine_editor_style() {
		add_editor_style( 'editor-style.css' );
	}
}
add_action( 'init', 'madeleine_editor_style' );


/**
 * Add a custom stylesheet to the admin area.
 *
 */

if ( !function_exists( 'madeleine_admin_css' ) ) {
	function madeleine_admin_css() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'madeleine_admin_css', INCLUDE_URL .'/css/madeleine-admin.css' );
	}
}
add_action( 'admin_print_styles', 'madeleine_admin_css' );


/**
 * Add a custom script to the admin area.
 *
 */

if ( !function_exists( 'madeleine_enqueue_admin_scripts' ) ) {
	function madeleine_enqueue_admin_scripts() {
		wp_register_script( 'madeleine-admin', get_template_directory_uri() . '/includes/js/madeleine-admin.js' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'madeleine-admin' );
	}
}
add_action( 'admin_enqueue_scripts', 'madeleine_enqueue_admin_scripts' );


/**
 * Setup a post format filter for the post archive in the admin.
 *
 */

if ( !function_exists( 'madeleine_admin_posts_filter' ) ) {
	function madeleine_admin_posts_filter( &$query ) {
		if ( is_admin() AND 'edit.php' === $GLOBALS['pagenow'] AND isset( $_GET['p_format'] ) AND $_GET['p_format'] != '-1' ):
			$query->query_vars['tax_query'] = array( array(
				'taxonomy'	=> 'post_format',
				'field'			=> 'ID',
				'terms'			=> array( $_GET['p_format'] )
			) );
		endif;
	}
}
add_filter( 'parse_query', 'madeleine_admin_posts_filter' );


if ( !function_exists( 'madeleine_restrict_manage_posts_format' ) ) {
	function madeleine_restrict_manage_posts_format() {
		wp_dropdown_categories( array(
			'taxonomy'					=> 'post_format',
			'hide_empty'				=> 0,
			'name'							=> 'p_format',
			'show_option_none'	=> __( 'View all formats', 'madeleine' )
		));
	}
}
add_action( 'restrict_manage_posts', 'madeleine_restrict_manage_posts_format' );

?>