<?php

$include_dir = get_template_directory() . '/includes/';

// Load the Widgets

require_once( $include_dir .'widgets/latest-posts-widget.php' );
require_once( $include_dir .'widgets/popular-posts-widget.php' );
require_once( $include_dir .'widgets/images-widget.php' );
require_once( $include_dir .'widgets/videos-widget.php' );
require_once( $include_dir .'widgets/links-widget.php' );
require_once( $include_dir .'widgets/quotes-widget.php' );

?>