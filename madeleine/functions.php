<?php


/**
 * 01 WordPress Settings
 * 02 Global functions
 * 03 Archive settings
 * 04 Entry
 * 05 Reviews
 * 06 Ajax and GET Parameters
*/


/**
 * 01 WordPress Settings
 * 
 * General settings for WordPress core functions.
 *
 */


if ( !function_exists( 'madeleine_theme_setup' ) ) {
	function madeleine_theme_setup() {
		load_theme_textdomain( 'madeleine', get_template_directory() . '/languages' );

		if ( ! isset( $content_width ) ) $content_width = 1020;

		add_theme_support( 'post-formats', array( 'image', 'video', 'link', 'quote', ) );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'menus' );

		add_image_size( 'thumbnail', 100, 100, true );
		add_image_size( 'medium', 300, 150, true );
		add_image_size( 'large', 640, 230, true );
		add_image_size( 'tall', 340, 320, true );
		add_image_size( 'focus', 680, 320, true );
		add_image_size( 'wide', 340, 160, true );
		add_image_size( 'panorama', 1020, 360, true );
	}
}
add_action( 'after_setup_theme', 'madeleine_theme_setup' );


if ( !function_exists( 'madeleine_widgets_setup' ) ) {
	function madeleine_widgets_setup() {
		$sidebar_arguments = array(
			'name'					=> __( 'Sidebar', 'madeleine' ),
			'before_widget'	=> '<section id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</section>',
			'before_title'	=> '<h4 class="widget-title">',
			'after_title'		=> '</h4>'
		);
		
		if ( function_exists('register_sidebar') )
			register_sidebar( $sidebar_arguments );
	}
}
add_action( 'widgets_init', 'madeleine_widgets_setup' );


/**
 * 02 Global functions
 * 
 * Functions used throughout the website.
 *
 */



/**
 * Displays a list of your social accounts.
 * This list appears as icons in the header and as a simple list in the footer.
 *
 */

if ( !function_exists( 'madeleine_social_links' ) ) {
	function madeleine_social_links() {
		$social_options = get_option( 'madeleine_options_social' );
		$social_links = '';
		if ( isset( $social_options['accounts'] ) ):
			foreach ( $social_options['accounts'] as $key => $value) {
				if ( $value != '' ):
					$slug = str_replace( '_account', '', $key );
					$name = ucwords( $slug );
					switch( $slug ):
						case 'twitter':
							$social_links .= '<li class="social-' . $slug . '"><a class="social-follow" href="https://twitter.com/' . $value . '">' . $name . '</a>';
							$social_links .= '<div class="social-window"><a href="https://twitter.com/' . $value . '" class="twitter-follow-button" data-show-count="false">Follow @' . $value . '</a></div>';
							$social_links .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
							break;
						case 'facebook':
							$social_links .= '<li class="social-' . $slug . '"><a class="social-follow" href="' . esc_url( $value ) . '">' . $name . '</a>';
							$social_links .= '<div class="social-window"><iframe src="//www.facebook.com/plugins/likebox.php?href=' . $value . '&amp;width=300&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="background:white; border:none; overflow:hidden; width:300px; height:62px;" allowTransparency="true"></iframe></div>';
							break;
						case 'google':
							$social_links .= '<li class="social-' . $slug . '"><a class="social-follow" href="' . esc_url( $value ) . '">Google +</a>';
							$social_links .= '<div class="social-window">';
							$social_links .= '<div class="g-person" data-href="' . esc_url( $value ) . '" data-rel="author"></div>';
							$social_links .= '<script type="text/javascript">';
							$social_links .= "(function() {";
							$social_links .= "var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;";
							$social_links .= "po.src = 'https://apis.google.com/js/plusone.js';";
							$social_links .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);";
							$social_links .= "})();";
							$social_links .= '</script>';
							$social_links .= '</div>';
							break;
						case 'tumblr':
							$social_links .= '<li class="social-' . $slug . '"><a href="' . esc_url( $value ) . '">' . $name . '</a>';
							break;
						case 'youtube':
							$social_links .= '<li class="social-' . $slug . '"><a href="' . esc_url( $value ) . '">' . $name . '</a>';
							break;
					endswitch;
					$social_links .= '</li>';
				endif;
			}
		endif;
		echo $social_links;
	}
}


/**
 * Redirects the main feed to the Feedburner (if provided).
 *
 */

if( !preg_match( "/feedburner|feedvalidator/i", $_SERVER['HTTP_USER_AGENT'] ) ):
	add_action( 'template_redirect', 'madeleine_feed_redirect' );
	add_action( 'init', 'madeleine_check_url' );
endif;


if ( !function_exists( 'madeleine_feed_redirect' ) ) {
	function madeleine_feed_redirect() {
		global $wp, $feed, $withcomments;
		$general_options = get_option( 'madeleine_options_general' );
		if ( is_array( $general_options ) ):
			if ( array_key_exists( 'feedburner_url', $general_options ) && $general_options['feedburner_url'] != '' ):
				if ( is_feed() && $feed != 'comments-rss2' && !is_single() && $wp->query_vars['category_name'] == '' && ( $withcomments != 1 ) ):
					if( function_exists('status_header') ) status_header( 302 );
					header( "Location:" . trim( $general_options['feedburner_url'] ) );
					header( "HTTP/1.1 302 Temporary Redirect" );
					exit();
				endif;
			endif;
		endif;
	}
}


if ( !function_exists( 'madeleine_check_url' ) ) {
	function madeleine_check_url() {
		$general_options = get_option( 'madeleine_options_general' );
		if( is_array( $general_options ) ):
			if( array_key_exists( 'feedburner_url', $general_options ) && $general_options['feedburner_url'] != '' ):
				switch( basename($_SERVER['PHP_SELF']) ):
					case 'wp-rss.php':
					case 'wp-rss2.php':
					case 'wp-atom.php':
					case 'wp-rdf.php':
						if( function_exists('status_header') ) status_header( 302 );
						header( "Location:" . trim( $general_options['feedburner_url'] ) );
						header( "HTTP/1.1 302 Temporary Redirect" );
						exit();
						break;
				endswitch;
			endif;
		endif;
	}
}


/**
 * Outputs the analytics tracking code in the footer (if provided).
 *
 */

if ( !function_exists( 'madeleine_fonts' ) ) {
	function madeleine_fonts(){
		$typography_options = get_option( 'madeleine_options_typography' );
		$fonts = array(
			'droidsans' => 'Droid+Sans:400,700',
			'lato' => 'Lato:400,700,400italic,700italic',
			'arvo' => 'Arvo:400,700,400italic,700italic',
			'ptsans' => 'PT+Sans:400,700,400italic,700italic',
			'ubuntu' => 'Ubuntu:400,700,400italic,700italic',
			'bitter' => 'Bitter:400,700,400italic',
			'droidserif' => 'Droid+Serif:400,700,400italic,700italic',
			'opensans' => 'Open+Sans:400italic,700italic,400,700',
			'oswald' => 'Oswald:400,700',
			'roboto' => 'Roboto:400,400italic,700,700italic',
			'montserrat' => 'Montserrat:400,700',
			'nunito' => 'Nunito:400,700',
			'francois' => 'Francois+One',
			'merriweather' => 'Merriweather:400,400italic,700italic,700',
			'merriweathersans' => 'Merriweather+Sans:400,700italic,700,400italic',
			'gentiumbookbasic' => 'Gentium+Book+Basic:400,400italic,700,700italic'
		);
		$chosen_fonts = array();
		if( is_array( $typography_options ) ):
			if( array_key_exists( 'font_body', $typography_options ) && $typography_options['font_body'] != '' ):
				$chosen_fonts[] = $typography_options['font_body'];
			endif;
			if( array_key_exists( 'font_title', $typography_options ) && $typography_options['font_title'] != '' ):
				$chosen_fonts[] = $typography_options['font_title'];
			endif;
		endif;
		$loaded_fonts = array_unique($chosen_fonts);
		$fonts_list = '';
		foreach ( $loaded_fonts as $loaded_font ):
			$fonts_list .= $fonts[$loaded_font] . '|';
		endforeach;
		if ( $fonts_list != '' ):
			echo '<link href="http://fonts.googleapis.com/css?family=' . substr( $fonts_list, 0, -1) . '" rel="stylesheet" type="text/css">';
		endif;
	}
}


/**
 * Outputs the analytics tracking code in the footer (if provided).
 *
 */

if ( !function_exists( 'madeleine_tracking_code' ) ) {
	function madeleine_tracking_code(){
		$general_options = get_option( 'madeleine_options_general' );
		if( is_array( $general_options ) ):
			if( array_key_exists( 'tracking_code', $general_options ) && $general_options['tracking_code'] != '' )
				echo stripslashes( $general_options['tracking_code'] );
		endif;
	}
}
add_action( 'wp_footer', 'madeleine_tracking_code' );


/**
 * Outputs footer text (customizable in the theme settings).
 *
 */

if ( !function_exists( 'madeleine_footer' ) ) {
	function madeleine_footer(){
		$general_options = get_option( 'madeleine_options_general' );
		if( is_array( $general_options ) ):
			if( array_key_exists( 'footer_text', $general_options ) && $general_options['footer_text'] != '' ):
				echo nl2br( stripslashes( $general_options['footer_text'] ) );
			else:
				echo '<a href="' . esc_url( 'http://wordpress.org/' )  . '">' . sprintf( __( 'Powered by %s', 'madeleine' ), 'WordPress' ) . '</a>.<br>
					Theme <a href="http://madeleine.haxokeno.com">Madeleine</a> available on Theme Forest.<br>
					&copy; 2013 The Magazine Theme. All rights reserved.';
			endif;
		endif;
	}
}


/**
 * Outputs the different scripts needed in the <head>.
 *
 */

if ( !function_exists( 'madeleine_enqueue_scripts' ) ) {
	function madeleine_enqueue_scripts() {
		$js_directory = get_template_directory_uri() . '/js/';
		wp_register_script( 'menu-aim', $js_directory . 'jquery.menu-aim.js', 'jquery', '1.0' );
		wp_register_script( 'global', $js_directory . 'global.js', 'jquery', '1.0' );
		wp_register_script( 'date', $js_directory . 'date.js', 'jquery', '1.0' );
		wp_register_script( 'home', $js_directory . 'home.js', 'jquery', '1.0' );
		wp_register_script( 'jump', $js_directory . 'jump.js', 'jquery', '1.0' );
		wp_register_script( 'pinterest', $js_directory . 'pinterest.js', 'jquery', '1.0' );
		wp_register_script( 'reviews', $js_directory . 'reviews.js', 'jquery', '1.0' );
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'menu-aim' );
		wp_enqueue_script( 'global' );

		if ( is_home() ):
			wp_enqueue_script( 'home' );
		elseif ( is_date() ):
			wp_enqueue_script( 'date' );
		elseif ( is_tag() ):
			wp_enqueue_script( 'pinterest' );
		elseif ( is_post_type_archive( 'review' ) || is_tax( 'product' ) || is_tax( 'brand' ) ):
			wp_enqueue_script( 'reviews' );
		elseif ( is_singular( 'review' ) ):
			wp_enqueue_script( 'jump' );
		endif;
		
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'madeleine_enqueue_scripts' );


/**
 * Outputs a stylesheet in the <head> with the different fonts set in the theme settings.
 *
 */

if ( !function_exists( 'madeleine_custom_typography' ) ) {
	function madeleine_custom_typography() {
		$typography_options = get_option( 'madeleine_options_typography' );
		$fonts = array(
			'droidsans' => 'Droid Sans',
			'lato' => 'Lato',
			'arvo' => 'Arvo',
			'ptsans' => 'PT Sans',
			'ubuntu' => 'Ubuntu',
			'bitter' => 'Bitter',
			'droidserif' => 'Droid Serif',
			'opensans' => 'Open Sans',
			'oswald' => 'Oswald',
			'roboto' => 'Roboto',
			'montserrat' => 'Montserrat',
			'nunito' => 'Nunito',
			'francois' => 'Francois One',
			'merriweather' => 'Merriweather',
			'merriweathersans' => 'Merriweather Sans',
			'gentiumbookbasic' => 'Gentium Book Basicc'
		);
		$custom_css = '<style id="madeleine-custom-typography" type="text/css">';
		if ( isset( $typography_options['font_body'] ) && $typography_options['font_body'] != '' ):
			$font_body = $typography_options['font_body'];
			$custom_css .= 'body, #trending .section, #footer-about, .entry-comments a .leave-reply, #latest a{ font-family: \'' . $fonts[$font_body] . '\', Arial, sans-serif;}';
		endif;
		if ( isset( $typography_options['font_title'] ) && $typography_options['font_title'] != '' ):
			$font_title = $typography_options['font_title'];
			$custom_css .= '.heading, .pagination, .tabs, .section, .widget-title, .button, #top-icon, #logo, #nav, #nav-icon, #today-news, #footer, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6, .entry-content figcaption, .entry-content input[type="submit"], .entry-content input[type="reset"], .entry-format, .entry-title, .entry-info, .post .entry-comments a, .page .entry-comments a, #wp-calendar caption, #popular li, #comments-title, #reply-title, .comment-info, #commentform label, .form-submit #submit, .entry-rating, .rating, .single-review .review .entry-summary, #menu-icon, #category{ font-family: \'' . $fonts[$font_title] . '\', Arial, sans-serif;}';
		endif;
		$custom_css .= '</style>';
		echo $custom_css;
	}
}
add_action( 'wp_print_styles', 'madeleine_custom_typography' );


/**
 * Outputs a stylesheet in the <head> with the different colors set in the WP Customizer:
 * - the main website color
 * - the reviews color
 *
 */

if ( !function_exists( 'madeleine_custom_colors' ) ) {
	function madeleine_custom_colors() {
		$colors_options = get_option( 'madeleine_options_colors' );
		$custom_css = '<style id="madeleine-custom-colors" type="text/css">';
		if ( isset( $colors_options['main'] ) && $colors_options['main'] != '' ):
			$main_color = $colors_options['main'];
			$custom_css .= 'body{ border-top-color: ' . $main_color . ';}';
			$custom_css .= 'a, #wp-calendar a,.entry-title a,#category .current-cat a{ color: ' . $main_color . ';}';
			$custom_css .= '#wp-calendar #today,.post .entry-category a,.format-image .entry-thumbnail:hover:after,.format-video .entry-thumbnail:hover:after,#category strong{ background-color: ' . $main_color . ';}';
			$custom_css .= '#category strong:after{ border-left-color: ' . $main_color . ';}';
		endif;
		if ( isset( $colors_options['text'] ) && $colors_options['text'] != '' ):
			$text_color = $colors_options['text'];
			$custom_css .= 'body{ color: ' . $text_color . ';}';
		endif;
		if ( isset( $colors_options['reviews'] ) && $colors_options['reviews'] != '' ):
			$reviews_color = $colors_options['reviews'];
			$custom_css .= '#menu a, .review .entry-title a{ color: ' . $reviews_color . ';}';
			$custom_css .= '#nav .nav-reviews:hover, .review .entry-category a,.single-review #category strong, #reviews-tabs a:hover, #reviews-tabs .on{ background-color: ' . $reviews_color . ';}';
			$custom_css .= '#jump .on,#jump .on:hover,#menu-icon,#menu .current-cat a,#menu .ui-slider-handle:hover,#menu .ui-state-active{ background-color: ' . $reviews_color . ';}';
			$custom_css .= '#nav .nav-reviews, .reviews-grid .review .review-text{ border-top-color: ' . $reviews_color . ';}';
			$custom_css .= '.single-review #category strong:after{ border-left-color: ' . $reviews_color . ';}';
		endif;
		if ( isset( $colors_options['footer_background'] ) && $colors_options['footer_background'] != '' ):
			$footer_background_color = $colors_options['footer_background'];
			$custom_css .= '#footer{ background: ' . $footer_background_color . ';}';
		endif;
		if ( isset( $colors_options['footer_text'] ) && $colors_options['footer_text'] != '' ):
			$footer_text_color = $colors_options['footer_text'];
			$custom_css .= '#footer{ color: ' . $footer_text_color . ';}';
		endif;
		if ( isset( $colors_options['footer_title'] ) && $colors_options['footer_title'] != '' ):
			$footer_title_color = $colors_options['footer_title'];
			$custom_css .= '#footer .section, #footer-title{ color: ' . $footer_title_color . ';}';
		endif;
		if ( isset( $colors_options['footer_link'] ) && $colors_options['footer_link'] != '' ):
			$footer_link_color = $colors_options['footer_link'];
			$custom_css .= '#footer a, #footer-description{ color: ' . $footer_link_color . ';}';
		endif;
		$custom_css .= '</style>';
		echo $custom_css;
	}
}
add_action( 'wp_print_styles', 'madeleine_custom_colors' );


/**
 * Outputs a stylesheet in the <head> with the different category colors.
 * Only a top-level category can have its own color.
 * The children categories will inherit the parent category color.
 * To set a category color, just use the color picker in a category's edit page.
 *
 */

if ( !function_exists( 'madeleine_categories_colors' ) ) {
	function madeleine_categories_colors() {
		$cats = get_categories( 'hide_empty=0&orderby=ID&parent=0' );
		$category_meta = get_option( 'madeleine_category_colors' );
		$style = '<style id="madeleine-categories-colors" type="text/css">';
		foreach( $cats as $cat ):
			if ( isset( $category_meta[$cat->term_id] ) ):
				$color = $category_meta[$cat->term_id]['color'];
			else:
				$color = '#d0574e';
			endif;
			$slug = $cat->slug;
			$style .= '.post.category-' . $slug . ' a, .subnav.category-' . $slug . ' .subnav-menu a, .tabs .category-' . $slug . ' a, body.category-' . $slug . ' #nav .current-cat > a, #nav .category-' . $slug . '.current-cat-parent > a, #category.category-' . $slug . ' .current-cat a{ color: ' . $color . ';}';
			$style .= '#nav .category-' . $slug . ' a:hover, #nav .category-' . $slug . ' .maintainHover, .tabs .category-' . $slug . ' a:hover, .subnav.category-' . $slug . ' .subnav-menu a:hover, .tabs .category-' . $slug . ' .on, #category.category-' . $slug . ' strong, .category-' . $slug . ' .entry-category a, #popular .category-' . $slug . ' em, #popular .category-' . $slug . ' strong, .format-image.category-' . $slug . ' .entry-thumbnail:hover:after, .format-video.category-' . $slug . ' .entry-thumbnail:hover:after,  .focus.category-' . $slug . '{ background-color: ' . $color . ';}';
			$style .= '.quote.category-' . $slug . ', #category.category-' . $slug . ' strong:after{ border-left-color: ' . $color . ';}';
			$style .= '#nav .category-' . $slug . ' a, .subnav.category-' . $slug . ', body.category-' . $slug . ', body.category-' . $slug . ' #nav .current-cat a, #category.category-' . $slug . ' .wrap, .focus.category-' . $slug . ' .focus-text{ border-top-color: ' . $color . ';}';
		endforeach;
		$style .= '</style>';
		echo $style;
	}
}
add_action( 'wp_print_styles', 'madeleine_categories_colors' );


/**
 * Outputs the custom CSS in the <head>.
 * The custom CSS code is defined in the WP Customizer.
 *
 */

if ( !function_exists( 'madeleine_custom_css' ) ) {
	function madeleine_custom_css() {
		$css_options = get_option( 'madeleine_options_general' );
		if ( isset( $css_options['custom_css'] ) && $css_options['custom_css'] != '' ):
			$custom_css = '<style id="madeleine-custom-css" type="text/css">';
			$custom_css .= $css_options['custom_css'];
			$custom_css .= '</style>';
			echo $custom_css;
		endif;
	}
}
add_action( 'wp_print_styles', 'madeleine_custom_css' );


/**
 * Get the final destination of an HTML redirect.
 * Used to retrieve the Dailymotion thumbnail.
 *
 * @param string $destination
 * @return string
 */

if ( !function_exists( 'madeleine_get_redirect_target' ) ) {
	function madeleine_get_redirect_target( $destination ) {
		$headers = get_headers( $destination, 1 );
		return $headers['Location'];
	}
}


/**
 * Get the video ID of a YouTube URL.
 * 
 * @param string $url
 * @return string or null
 */

if ( !function_exists( 'madeleine_get_youtube_id' ) ) {
	function madeleine_get_youtube_id( $url ) {
		if ( preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match) ):
			return $match[1];
		else:
			return null;
		endif;
	}
}


/**
 * Get the video ID of a Vimeo URL.
 * 
 * @param string $url
 * @return string or null
 */

if ( !function_exists( 'madeleine_get_vimeo_id' ) ) {
	function madeleine_get_vimeo_id( $url ) {
		if ( preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $url, $match) ):
			return $match[2];
		else:
			return null;
		endif;
	}
}


/**
 * Get the video ID of a Dailymotion URL.
 * 
 * @param string $url
 * @return string or null
 */

if ( !function_exists( 'madeleine_get_dailymotion_id' ) ) {
	function madeleine_get_dailymotion_id( $url ) {
		if ( preg_match('/^.+dailymotion.com\/((video|hub)\/([^_]+))?[^#]*(#video=([^_&]+))?/', $url, $match) ):
			return $match[3];
		else:
			return null;
		endif;
	}
}


/**
 * Uploads the video thumbnail for YouTube, Vimeo, and Dailymotion.
 * 
 * @param string $image_id
 * @param string $image_url
 * @param integer $post_id the ID of the post to which the thumbnail will be attached.
 * @param string $source the video website the thumbnail comes from.
 * @return integer
 */

if ( !function_exists( 'madeleine_upload_video_thumbnail' ) ) {
	function madeleine_upload_video_thumbnail( $image_id, $image_url, $post_id, $source ) {
		$error = '';
		$response = wp_remote_get( $image_url, array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) ):
			$error = new WP_Error( 'get_video_thumbnail', $response->get_error_message() );
		else:
			$image_contents = $response['body'];
			$image_type = wp_remote_retrieve_header( $response, 'content-type' );
		endif;

		if ( $error != '' ):
			return $error;
		else:
			if ( $image_type == 'image/jpeg' ):
				$image_extension = '.jpg';
			elseif ( $image_extension == 'image/png' ):
				$image_extension = '.png';
			endif;

			$new_filename = $source . '_' . $image_id . '_' . basename( $image_url );
			$upload = wp_upload_bits( $new_filename, null, $image_contents );

			if ( $upload['error'] ):
				$error = new WP_Error( 'thumbnail_upload', __( 'Error uploading image data:', 'madeleine' ) . ' ' . $upload['error'] );
				return $error;
			else:
				$filename = $upload['file'];
				$image_url = $upload['url'];
				$wp_filetype = wp_check_filetype( basename( $filename ), null );
				$wp_upload_dir = wp_upload_dir();
				$attachment = array(
					'guid' 						=> $wp_upload_dir['url'] . '/' . basename( $filename ), 
					'post_mime_type' 	=> $wp_filetype['type'],
					'post_title' 			=> preg_replace('/\.[^.]+$/', '', basename( $filename ) ),
					'post_content' 		=> '',
					'post_status' 		=> 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $filename , $post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				set_post_thumbnail( $post_id, $attach_id );
			endif;
		endif;
		return $attach_id;
	}
}


/**
 * Retrieves the top-level category of any post.
 * If a post belongs to a second-level category, this function will find the top-level category ID.
 * This function is used to define the color of a post by retrieving the top-level category's color.
 *
 * @param integer $cat_ID
 * @return integer
 */

if ( !function_exists( 'madeleine_top_category' ) ) {
	function madeleine_top_category( $cat_ID = null ) {
		if ( isset( $cat_ID ) ):
			$cat = $cat_ID;
		elseif ( is_category() ):
			$cat = get_query_var('cat');
		elseif ( is_attachment() ):
			$parent = get_post_field( 'post_parent', get_the_ID() );
			$parent_categories = get_the_category( $parent );
			$cat = $parent_categories[0]->cat_ID;
		else:
			$categories = get_the_category();
			$cat = $categories[0]->cat_ID;
		endif;
		$category = get_category( $cat );
		if ( isset( $category ) ):
			if ( $category->category_parent == '0' )
				$top_category_ID = $cat;
			else
				$top_category_ID = $category->category_parent;
			return $top_category_ID;
		endif;
	}
}


/**
 * Adds the top-level category class to the body classes.
 * 
 * @param string $classes
 * @return string
 */

if ( !function_exists( 'madeleine_body_class' ) ) {
	function madeleine_body_class( $classes ) {
		if ( is_category() ):
			$top_category_ID = madeleine_top_category();
			$top_category = get_category( $top_category_ID );
			$classes[] = 'category-' . $top_category->category_nicename;
		endif;
		return $classes;
	}
}
add_filter( 'body_class', 'madeleine_body_class' );


/**
 * Returns an HTML list of all top-level categories.
 * 
 * @return string
 */

if ( !function_exists( 'madeleine_categories_list' ) ) {
	function madeleine_categories_list( $depth = 1) {
		$cats = get_categories('orderby=ID');
		$nav = wp_list_categories('depth=' . $depth . '&echo=0&orderby=ID&title_li=');
		foreach( $cats as $cat ):
			$find = 'cat-item-' . $cat->term_id . '"';
			$replace = 'category-' . $cat->slug . '" data-slug="' . $cat->slug . '"';
			$nav = str_replace( $find, $replace, $nav );
			$find = 'cat-item-' . $cat->term_id . ' ';
			$replace = 'category-' . $cat->slug . ' ';
			$nav = str_replace( $find, $replace, $nav );
			$find = ' title=';
			$replace = ' data-title=';
			$nav = str_replace( $find, $replace, $nav );
		endforeach;
		return $nav;
	}
}


/**
 * Displays a submenu for each category.
 *
 *
 */

if ( !function_exists( 'madeleine_subnav' ) ) {
	function madeleine_subnav() {
		$top_categories = get_categories('orderby=ID&parent=0');
		$subnav = '<div id="subnav-viewport">';
		$subnav = '<div id="subnav-reel" style="width: 7140px;">';
		foreach ( $top_categories as $top_category ):
			$subnav .= '<div class="subnav category-' . $top_category->slug . '">';
			$subnav .= '<p class="title">';
			$subnav .= '<a href="' . get_category_link( $top_category->cat_ID ) . '">';
			$subnav .= '<em>' . $top_category->name . '</em>';
			$subnav .= '<strong>' . $top_category->description . '</strong>';
			$subnav .= '</a>';
			$subnav .= '</p>';
			$subnav .= '<ul class="subnav-menu">';
			$subnav .= wp_list_categories('child_of=' . $top_category->cat_ID . '&depth=1&echo=0&orderby=ID&title_li=');
			$subnav .= '</ul>';
			$subnav .= '<div class="subnav-posts">';
			$standard_posts = madeleine_standard_posts();
			$args = array(
				'cat' => $top_category->cat_ID,
				'posts_per_page' => 4,
				'post_type' => 'post',
				'post_status' => 'publish',
				'tax_query' => $standard_posts
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ):
				while ( $query->have_posts() ):
					$query->the_post();
					$thumbnail_url = madeleine_entry_thumbnail_url();
					$subnav .= '<a class="subnav-post" style="background-image: url(' . $thumbnail_url . ');" href="' . esc_url( get_permalink() ) . '">';
					$subnav .= '<strong class="entry-title">' . get_the_title() . '</strong>';
					$subnav .= '</a>';
				endwhile;
			endif;
			$subnav .= '</div>';
			$subnav .= '<div style="clear: left;"></div>';
			$subnav .= '</div>';
		endforeach;
		$subnav .= '</div>';
		$subnav .= '</div>';
		echo $subnav;
		wp_reset_postdata();
	}
}


/**
 * Displays an HTML dropdown list of all tags.
 *
 */

if ( !function_exists( 'madeleine_tags_list' ) ) {
	function madeleine_tags_list() {
		$tags = get_tags();
		$tags_list = '<div id="tags">';
		$tags_list .= '<em>' . __( 'Tag:', 'madeleine' ) . '</em>';
		$tags_list .= '<div class="dropdown">';
		$tags_list .= '<ul>';
		$current_tag = get_query_var( 'tag' );
		foreach ( $tags as $tag ) {
			$tag_link = get_tag_link( $tag->term_id );
			if ( $tag->slug == $current_tag )
				$tags_list .= '<li class="on">';
			else
				$tags_list .= '<li>';
			$tags_list .= '<a href="' . esc_url( $tag_link ) . '" class="' . $tag->slug . '">' . $tag->name . '<span>' . $tag->count . '</span></a>';
		}
		$tags_list .= '</ul>';
		$tags_list .= '</div>';
		$tags_list .= '</div>';
		echo $tags_list;
	}
}


/**
 * Returns a list of all terms for one taxonomy.
 * 
 * @param integer $taxonomy
 * @return string
 */

if ( !function_exists( 'madeleine_taxonomy_list' ) ) {
	function madeleine_taxonomy_list( $taxonomy ) {
		$terms = get_categories('taxonomy=' . $taxonomy );
		$list = wp_list_categories('depth=1&echo=0&show_count=1&title_li=&taxonomy=' . $taxonomy );
		foreach( $terms as $term ):
			$find = 'class="cat-item cat-item-' . $term->term_id;
			$replace = ' data-id="' . $term->term_id . '" data-slug="' . $term->slug . '" class="cat-item cat-item-' . $term->term_id;
			$list = str_replace( $find, $replace, $list );
			$list = str_replace( 'posts', $taxonomy . 's', $list );
		endforeach;
		return $list;
	}
}


/**
 * Displays a list of links to each post format archive.
 * 
 */

if ( !function_exists( 'madeleine_format_list' ) ) {
	function madeleine_format_list() {
		$formats = get_theme_support( 'post-formats' );
		if ( is_array( $formats[0] ) ):
			foreach( $formats[0] as $format ):
				$args = array(
					'post_type'			=> 'post',
					'post_status'		=> 'publish',
					'tax_query' 		=> array(
						array(
							'taxonomy'	=> 'post_format',
							'field'			=> 'slug',
							'terms'			=> array( 'post-format-' . $format )
						)
					)
				);
				$query = new WP_Query( $args );
				if ( $query->have_posts() ):
					echo '<li><a href="' . esc_url( get_post_format_link( $format ) ) . '" title="' . esc_attr( ucwords( $format ) ) . 's">' . ucwords( $format ) . 's</a></li>';
				endif;
			endforeach;
		endif;
	}
}


/**
 * Displays a list of icons to each post format archive.
 * 
 */

if ( !function_exists( 'madeleine_format_icons' ) ) {
	function madeleine_format_icons() {
		$formats = get_theme_support( 'post-formats' );
		if ( is_array( $formats[0] ) ):
			foreach( $formats[0] as $format ):
				$args = array(
					'post_type'			=> 'post',
					'post_status'		=> 'publish',
					'tax_query'			=> array(
						array(
							'taxonomy'	=> 'post_format',
							'field'			=> 'slug',
							'terms'			=> array( 'post-format-' . $format )
						)
					)
				);
				$query = new WP_Query( $args );
				if ( $query->have_posts() )
					echo '<li><a class="nav-format nav-' . $format . 's" href="' . esc_url( get_post_format_link( $format ) ) . '" title="' . esc_attr( ucwords( $format ) ) . 's"><span class="icon icon-' . $format . 's"></span>' . ucwords( $format ) . 's</a></li>';
			endforeach;
		endif;
	}
}


/**
 * Displays a link to the reviews archive.
 * 
 */

if ( !function_exists( 'madeleine_reviews_link' ) ) {
	function madeleine_reviews_link() {
		$args = array(
			'post_type'			=> 'review',
			'post_status'		=> 'publish'
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() )
			echo '<li><a class="nav-reviews" href="' . esc_url( get_post_type_archive_link( 'review' ) ) . '">' . __( 'Reviews', 'madeleine' ) . '</a></li>';
	}
}


/**
 * Displays a list of the most popular tags in the last 30 days.
 * 
 * @param integer $limit
 */

if ( !function_exists( 'madeleine_trending' ) ) {
	function madeleine_trending( $limit = 16 ) {
		$general_options = get_option( 'madeleine_options_general' );
		if ( isset( $general_options['trending_status'] ) && $general_options['trending_status'] == 1 ):
			$n = isset( $limit ) ? $limit : $general_options['trending_number'];
			$trending = '<section><h4 class="section">' . __( 'Trending', 'madeleine' ) . '</h4><ul>';
			global $wpdb;
			$term_ids = $wpdb->get_col("
				SELECT term_id, taxonomy FROM $wpdb->term_taxonomy
				INNER JOIN $wpdb->term_relationships ON $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id
				INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
				WHERE taxonomy = 'post_tag'
				AND DATE_SUB(CURDATE(), INTERVAL 90 DAY) <= $wpdb->posts.post_date
				AND $wpdb->posts.post_status = 'publish'");
			if ( count( $term_ids ) > 4 ):
				$tags = array_unique( $term_ids );
				$tags = array_slice( $tags, 0, $n );
				foreach ( $tags as $tag ):
					$tag_info = get_tag( $tag );
					$trending .= '<li><a href="' . esc_url( get_tag_link( $tag ) ) . '" rel="tag">' . $tag_info->name . '</a></li>';
				endforeach;
			endif;
			$trending .= '</ul><div style="clear: left;"></div></section>';
			echo $trending;
		endif;
	}
}


/**
 * Returns an array of standard posts.
 * This function is used in a WP Query to filter out all post formats.
 *
 * @return array
 */

if ( !function_exists( 'madeleine_standard_posts' ) ) {
	function madeleine_standard_posts() {
		$standard_posts = array(
			array(
				'taxonomy'	=> 'post_format',
				'field'			=> 'slug',
				'terms'			=> array( 
						'post-format-aside',
						'post-format-audio',
						'post-format-chat',
						'post-format-gallery',
						'post-format-image',
						'post-format-link',
						'post-format-quote',
						'post-format-status',
						'post-format-video'
				),
				'operator'	=> 'NOT IN'
			)
		);
		return $standard_posts;
	}
}


/**
 * Returns an array of sticky posts.
 * This function is used in a WP Query to filter out all non-sticky posts.
 *
 * @return array
 */

if ( !function_exists( 'madeleine_sticky_posts' ) ) {
	function madeleine_sticky_posts() {
		$sticky_posts = get_option( 'sticky_posts' );
		rsort( $sticky_posts );
		$sticky_posts = array_slice( $sticky_posts, 0, 5 );
		return $sticky_posts;
	}
}


/**
 * Displays a list of the 5 latest sticky posts on the homepage.
 * Returns an array of these sticky posts' IDs to prevent the homepage from displaying them twice.
 * 
 * @return array
 */

if ( !function_exists( 'madeleine_focus' ) ) {
	function madeleine_focus() {
		$home_options = get_option( 'madeleine_options_home' );
		if ( $home_options['focus_status'] == 1 ):
			$sticky_posts = madeleine_sticky_posts();
			$args = array(
				'ignore_sticky_posts' => 1,
				'post__in' => $sticky_posts
			);
			$query = new WP_Query( $args );
			if ( $query->found_posts > 4 ):
				$n = 1;
				if ( $home_options['focus_layout'] == 'highlight' ):
					echo '<div id="level-focus-highlight" class="level"><div class="wrap">';
					echo '<div id="focus" class="focus-highlight">';
					while ( $query->have_posts() ):
						$query->the_post();
						$categories = get_the_category();
						$top_category = get_category( madeleine_top_category( $categories[0] ) );
						$category_links = '';
						$class = 'focus category-' . $top_category->category_nicename;;
						foreach ( $categories as $category ):
							$category_links .= '<li><a href="' . esc_url( get_category_link( $category->cat_ID ) ) . '">' . $category->name . '</a></li>';
						endforeach;
						$thumbnail_medium_url = madeleine_entry_thumbnail_url();
						$thumbnail_large_url = madeleine_entry_thumbnail_url( 'large' );
						// Big
						echo '<article id="focus-big-' . $n . '" class="post focus-big ' . $class . '">';
						echo '<a class="entry-permalink" href="' . esc_url( get_permalink() ) . '"></a>';
						echo '<ul class="entry-category">' . $category_links . '</ul>';
						echo '<div class="focus-image" style="background-image: url(' . $thumbnail_large_url . ');"></div>';
						echo '<div class="focus-text">';
						echo '<h2 class="entry-title">' . get_the_title() . '</h2>';
						echo '<p class="entry-info">';
						madeleine_entry_info();
						echo '</p>';
						echo '<p class="entry-excerpt">' . get_the_excerpt() . '</p>';
						echo '</div>';
						echo '</article>';
						// Small
						echo '<article id="focus-small-' . $n . '" class="post focus-small ' . $class . '" style="background-image: url(' . $thumbnail_medium_url . ');">';
						echo '<a class="entry-permalink" href="' . esc_url( get_permalink() ) . '"></a>';
						echo '<ul class="entry-category">' . $category_links . '</ul>';
						echo '<h2 class="entry-title">' . get_the_title() . '</h2>';
						echo '</article>';
						$n++;
					endwhile;
					echo '</div></div></div>';
				elseif ( $home_options['focus_layout'] == 'carousel' ):
					echo '<div id="level-focus-carousel" class="level"><div class="wrap">';
					echo '<div id="focus" class="focus-carousel">';
					while ( $query->have_posts() ):
						$query->the_post();
						$categories = get_the_category();
						$top_category = get_category( madeleine_top_category( $categories[0] ) );
						$category_links = '';
						$class = 'focus category-' . $top_category->category_nicename;
						$letters = array('alpha', 'beta', 'gamma', 'delta', 'epsilon');
						foreach ( $categories as $category ):
							$category_links .= '<li><a href="' . esc_url( get_category_link( $category->cat_ID ) ) . '">' . $category->name . '</a></li>';
						endforeach;
						$thumbnail_full_url = madeleine_entry_thumbnail_url( 'full' );
						echo '<article class="post focus-' . $letters[$n - 1] . ' ' . $class . '" style="background-image: url(' . $thumbnail_full_url . ');">';
						echo '<a class="entry-permalink" href="' . esc_url( get_permalink() ) . '"></a>';
						echo '<ul class="entry-category">' . $category_links . '</ul>';
						echo '<div class="focus-text">';
						echo '<h2 class="entry-title">' . get_the_title() . '</h2>';
						echo '<p class="entry-info">';
						madeleine_entry_info();
						echo '</p>';
						echo '</div>';
						echo '</article>';
						$n++;
					endwhile;
					echo '</div></div></div>';
				else:
					echo '<div id="level-focus-puzzle" class="level"><div class="wrap">';
					echo '<div id="focus" class="focus-puzzle">';
					while ( $query->have_posts() ):
						$query->the_post();
						$categories = get_the_category();
						$top_category = get_category( madeleine_top_category( $categories[0] ) );
						$category_links = '';
						$class = 'focus category-' . $top_category->category_nicename;;
						foreach ( $categories as $category ):
							$category_links .= '<li><a href="' . esc_url( get_category_link( $category->cat_ID ) ) . '">' . $category->name . '</a></li>';
						endforeach;
						echo '<article class="post ' . $class . '" id="focus-' . $n . '"';
						if ( $n == 1 ):
							echo ' style="background-image: url(' . madeleine_entry_thumbnail_url( 'focus' ) . ');"';
						elseif ( $n == 5 ):
							echo ' style="background-image: url(' . madeleine_entry_thumbnail_url( 'tall' ) . ');"';
						else:
							echo ' style="background-image: url(' . madeleine_entry_thumbnail_url( 'wide' ) . ');"';
						endif;
						echo '>';
						echo '<a class="entry-permalink" href="' . esc_url( get_permalink() ) . '"></a>';
						echo '<div class="focus-text">';
						echo '<h2 class="entry-title">' . get_the_title() . '</h2>';
						echo '<ul class="entry-category">' . $category_links . '</ul>';
						echo '<p class="entry-excerpt">' . get_the_excerpt() . '</p>';
						echo '</div>';
						echo '</article>';
						$n++;
					endwhile;
					echo '<div style="clear: left;"></div>';
					echo '</div></div></div>';
				endif;
				wp_reset_postdata();
				return $sticky_posts;
			else:
				return array();
			endif;
		else:
			return array();
		endif;
	}
}


/**
 * Returns an array of post IDs from the last month.
 * This function is used by the Popular Posts widget to only display last month's popular posts.
 * 
 * @return array
 */

if ( !function_exists( 'madeleine_latest_posts' ) ) {
	function madeleine_latest_posts() {
		global $wpdb;
		$latests = $wpdb->get_results( 
			"
			SELECT ID
			FROM $wpdb->posts
			WHERE post_status = 'publish'
			AND post_date between date_sub(now(), INTERVAL 1 MONTH) and now();
			"
		, 'ARRAY_N');
		$latest_ids = array();
		foreach ( $latests as $latest ):
			$latest_ids[] = $latest[0];
		endforeach;
		return $latest_ids;
	}
}


/**
 * 03 Archive settings
 * 
 * Functions for custom WP queries and for any archive page (category, date, reviews).
 *
 */



/**
 * Defines the query arguments for the homepage, the tag archive, and the reviews archive.
 * 
 * @param object $query
 * @return object
 */

if ( !function_exists( 'madeleine_archive_settings' ) ) {
	function madeleine_archive_settings( $query ) {
		$query->set( 'ignore_sticky_posts', 1 );
		$standard_posts = madeleine_standard_posts();
		if ( ( $query->is_home() ) && $query->is_main_query() ):
			$sticky_posts = madeleine_sticky_posts();
			$home_options = get_option( 'madeleine_options_home' );
			$grid_number = ( isset( $home_options['grid_number'] ) ) ? $home_options['grid_number'] : 6;
			$query->set( 'tax_query', $standard_posts );
			$query->set( 'post__not_in', $sticky_posts );
			$query->set( 'posts_per_page', $grid_number );
		elseif ( $query->is_tag() && $query->is_main_query() ):
			$query->set( 'posts_per_page', -1 );
		elseif ( ( $query->is_post_type_archive( 'review' ) || $query->is_tax( 'product' ) || $query->is_tax( 'brand' ) ) && $query->is_main_query() ):
			$reviews_options = get_option( 'madeleine_options_reviews' );
			$maximum_rating = ( isset( $reviews_options['maximum_rating'] ) ) ? $reviews_options['maximum_rating'] : 10;
			$maximum_price = ( isset( $reviews_options['maximum_price'] ) ) ? $reviews_options['maximum_price'] : 2000;
			$product = get_query_var( 'product_id' ) != '' ? get_query_var( 'product_id' ) : '';
			$brand = get_query_var( 'brand_id' ) != '' ? get_query_var( 'brand_id' ) : '';
			$tax_query = array(
				'relation' => 'AND'
			);
			if ( $product != '' ):
				$tax_query[] = array(
					'taxonomy'	=> 'product',
					'field'			=> 'id',
					'terms'			=> $product,
					'operator'	=> 'IN'
				);
			endif;
			if ( $brand != '' ):
				$tax_query[] = array(
					'taxonomy'	=> 'brand',
					'field'			=> 'id',
					'terms'			=> $brand,
					'operator'	=> 'IN'
				);
			endif;
			$rating_min = get_query_var( 'rating_min' ) != '' ? get_query_var( 'rating_min' ) : 0;
			$rating_max = get_query_var( 'rating_max' ) != '' ? get_query_var( 'rating_max' ) : $maximum_rating;
			$price_min = get_query_var( 'price_min' ) != '' ? get_query_var( 'price_min' ) : 0;
			$price_max = get_query_var( 'price_max' ) != '' ? get_query_var( 'price_max' ) : $maximum_price;
			$rating_range = array( $rating_min, $rating_max );
			$price_range = array( $price_min, $price_max );
			$meta_query = array(
				'relation' => 'AND',
				array(
					'key'			=> '_madeleine_review_rating',
					'value'		=> $rating_range,
					'type'		=> 'numeric',
					'compare'	=> 'BETWEEN'
				),
				array(
					'key'			=> '_madeleine_review_price',
					'value'		=> $price_range,
					'type'		=> 'numeric',
					'compare'	=> 'BETWEEN'
				)
			);
			$query->set( 'tax_query', $tax_query );
			$query->set( 'meta_query', $meta_query );
		endif;
	}
}
add_action( 'pre_get_posts', 'madeleine_archive_settings' );


/**
 * Displays a list of posts on the homepage, after the grid.
 * Each post has a square thumbnail, a title, and a category list.
 * The number of next posts is defined by a setting in the WP Customizer.
 * Returns an array of these sticky posts' IDs to prevent the homepage from displaying them twice.
 * 
 * @param array $already_posted an array of posts already posted to prevent duplicates.
 * @return array
 */

if ( !function_exists( 'madeleine_next_posts' ) ) {
	function madeleine_next_posts( $already_posted ) {
		$home_options = get_option( 'madeleine_options_home' );
		if ( $home_options['next_status'] == 1 ):
			$next_number = ( isset( $home_options['next_number'] ) ) ? $home_options['next_number'] : 10;
			$standard_posts = madeleine_standard_posts();
			$post_ids = array(); 
			$offset = get_option( 'posts_per_page' );
			$args = array(
				'post_type'				=> 'post',
				'posts_per_page'	=> $next_number,
				'post__not_in'		=> $already_posted,
				'offset'					=> $offset,
				'tax_query'				=> $standard_posts
			);
			$query = new WP_Query( $args );
			echo '<div class="board">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$categories = get_the_category( get_the_ID() );
				$category = get_category( madeleine_top_category( $categories[0] ) );
				echo '<div ';
				post_class();
				echo '>';
				madeleine_entry_thumbnail( 'thumbnail' );
				echo '<ul class="entry-category"><li>' . get_the_category_list( '</li><li>' ) . '</li></ul>';
				echo '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h2>';
				echo '</div>';
				$post_ids[] = get_the_ID();
			}
			echo '</div>';
			echo '<div style="clear: left;"></div>';
			wp_reset_postdata();
			return $post_ids;
		else:
			return array();
		endif;
	}
}


/**
 * Displays a list of 5 posts per category on the homepage.
 * The categories can be displayed as tabs or one after the other.
 * This option is defined by a setting in the WP Customizer.
 * 
 * @param array $already_posted
 */

if ( !function_exists( 'madeleine_category_wheels' ) ) {
	function madeleine_category_wheels( $already_posted ) {
		$home_options = get_option( 'madeleine_options_home' );
		$categories_as_tabs = ( isset( $home_options['category_tabs_status'] ) ) ? $home_options['category_tabs_status'] : 1;
		$cats = get_categories( 'orderby=ID&parent=0' );
		$standard_posts = madeleine_standard_posts();
		if ( $categories_as_tabs ):
			echo '<div id="wheel-tabs" class="wheels" data-display="tabs">';
			echo '<div class="tabs">';
			echo '<p>' . __( 'Categories', 'madeleine' ) . '</p>';
			echo '<ul>' . madeleine_categories_list() . '</ul>';
			echo '<em><a id="wheel-link" href="#">' . __( 'View all', 'madeleine' ) . ' <span></span> &rarr;</a></em>';
			echo '<div style="clear: left;"></div>';
			echo '</div>';
		else:
			echo '<div id="wheel-list" class="wheels" data-display="list">';
		endif;
		foreach( $cats as $cat ):
			$args = array(
				'cat'							=> $cat->term_id,
				'post_type'				=> 'post',
				'posts_per_page'	=> 5,
				'post__not_in'		=> $already_posted,
				'tax_query'				=> $standard_posts
			);
			$query = new WP_Query( $args );
			if ( $query->found_posts > 0 ):
				if ( ! $categories_as_tabs ):
					$category_link = get_category_link( $cat->term_id );
					echo '<div class="tabs">';
					echo '<ul>';
					echo '<li class="category-' . $cat->slug . '"><a class="on" href="' . esc_url( $category_link ) . '">' . $cat->name . '</a></li>';
					echo '</ul>';
					echo '<strong>' . $cat->description . '</strong>';
					echo '<em><a id="wheel-link" href="' . esc_url( $category_link ) . '">' . __( 'View all', 'madeleine' ) . ' <span>' . $cat->name . '</span> &rarr;</a></em>';
					echo '<div style="clear: left;"></div>';
					echo '</div>';
				endif;
				echo '<div id="' . $cat->slug . '" class="wheel category-' . $cat->category_nicename . '">';
				while ( $query->have_posts() ) {
					$query->the_post();
					$categories = get_the_category( get_the_ID() );
					$category = get_category( madeleine_top_category( $categories[0] ) );
					echo '<div ';
					post_class();
					echo '>';
					madeleine_entry_thumbnail( 'medium' );
					echo '<div class="entry-comments">';
					comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' );
					echo '</div>';
					echo '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h2>';
					echo '<div class="entry-info">';
					madeleine_entry_info();
					echo '</div>';
					echo '<p class="entry-summary">'. get_the_excerpt() . '</p>';
					echo '</div>';
				}
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			endif;
			wp_reset_postdata();
		endforeach;
		echo '</div>';
	}
}


/**
 * Displays a category breadcrumb on the category archives.
 * It displays the top-level category as well as its children.
 *
 */

if ( !function_exists( 'madeleine_category_breadcrumb' ) ) {
	function madeleine_category_breadcrumb() {
		$top_category_ID = madeleine_top_category();
		$top_category = get_category( $top_category_ID );
		$title = $top_category->cat_name;
		$slug = $top_category->slug;
		$args = array(
			'child_of'		=> $top_category_ID,
			'echo'				=> false,
			'orderby'			=> 'name',
			'title_li'		=> ''
		);
		$link = get_category_link( $top_category_ID );
		$subcategories = wp_list_categories( $args );
		if ( $subcategories != '<li>No categories</li>' ):
			echo'<div id="category" class="category-' . $slug . '">';
			echo '<div class="wrap">';
			echo '<strong>';
			echo '<span class="icon icon-dropdown"></span>';
			echo '<a href="' . esc_url( $link ) . '">' . $title . '</a>';
			echo '</strong>';
			echo '<ul>';
			echo $subcategories;
			echo '</ul>';
			echo '</div>';
			echo '</div>';
		endif;
	}
}


/**
 * Displays the pagination for post archives.
 *
 */

if ( !function_exists( 'madeleine_pagination' ) ) {
	function madeleine_pagination() {
		global $wp_query;
		$big = 999999999; // need an unlikely integer
		echo '<div class="pagination">';
		echo paginate_links(
			array(
				'base'		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'	=> '?paged=%#%',
				'current'	=> max( 1, get_query_var('paged') ),
				'total'		=> $wp_query->max_num_pages
			)
		);
		echo '</div>';
	}
}


/**
 * Displays the pagination for reviews.
 *
 */

if ( !function_exists( 'madeleine_reviews_pagination' ) ) {
	function madeleine_reviews_pagination( $query ) {
		$big = 999999999; // need an unlikely integer
		echo '<div class="pagination">';
		echo paginate_links(
			array(
				'base'		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'	=> '?paged=%#%',
				'current'	=> max( 1, get_query_var('paged') ),
				'total'		=> $query->max_num_pages
			)
		);
		echo '</div>';
	}
}


if ( !function_exists( 'madeleine_date_vars' ) ) {
	function madeleine_date_vars() {
		$m = get_query_var('m');
		if ( is_year() ):
			$year = substr( $m, 0, 4);
			$args['year'] = $year;
		elseif ( is_month() ):
			$year = substr( $m, 0, 4);
			$month = substr( $m, 4, 2);
			$args['m'] = $year . $month;
		elseif ( is_day() ):
			$year = substr( $m, 0, 4);
			$month = abs( substr( $m, 4, 2) );
			$day = abs( substr( $m, 6, 2) );
			$args['year'] = $year;
			$args['monthnum'] = $month;
			$args['day'] = $day;
		endif;
	}
}


if ( !function_exists( 'madeleine_date_archive' ) ) {
	function madeleine_date_archive() {
		$archive_year = get_the_date('Y');
		$archive_month = get_the_date('m');
		$archive_day = get_the_date('d');
		$day_link = '<a href="' . esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ) . '">' . $archive_day . '</a>';
		$month_link = '<a href="' . esc_url( get_month_link( $archive_year, $archive_month ) ) . '">' . $archive_month . '</a>';
		$year_link = '<a href="' . esc_url( get_year_link( $archive_year ) ) . '">' . $archive_year . '</a>';
		if ( is_day() ):
			echo $day_link . $month_link . $year_link;
		elseif ( is_month() ):
			echo $month_link . $year_link;
		elseif ( is_year() ):
			echo $year_link;
		endif;
	}
}


/**
 * Displays a 3-part dropdown menu on the date archive page.
 * Year, month, and day, each have a dropdown menu to filter the date archive.
 *
 */

if ( !function_exists( 'madeleine_nested_date' ) ) {
	function madeleine_nested_date() {
		global $wpdb;
		$y = get_query_var( 'year' );
		$m = get_query_var( 'monthnum' );
		$d = get_query_var( 'day' );
		// if ( is_year() ):
		//	 $type = 'year';
		// elseif ( is_month() ):
		//	 $y = substr( $date, 0, 4);
		//	 $m = abs( substr( $date, 4, 2) );
		//	 $type = 'month';
		// elseif ( is_day() ):
		//	 $y = substr( $date, 0, 4);
		//	 $m = abs( substr( $date, 4, 2) );
		//	 $d = abs( substr( $date, 6, 2) );
		//	 $type = 'day';
		// endif;
		$years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC");
		$years_list = '<li class="select"><span class="icon icon-close"></span>' . __( 'Select year', 'madeleine' ) . '</li>';
		echo '<div id="date-archive" data-year="' . $y . '" data-month="' . $m . '" data-day="' . $d . '">';
		foreach( $years as $year ):
			$years_list .= '<li class="year" data-value="' . $year . '"><a href="'. esc_url( get_year_link( $year ) ) . '"><span class="icon icon-dropdown"></span>' . $year . '</a></li>';
			$months_list = '<li class="select"><span class="icon icon-close"></span>' . __( 'Select month', 'madeleine' ) . '</li>';
			$months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND YEAR(post_date) = '" . $year . "' ORDER BY post_date DESC");
			foreach( $months as $month ):
				$months_list .= '<li class="month" data-value="' . $month . '""><a href="' . esc_url( get_month_link( $year, $month ) ) . '"><span class="icon icon-dropdown"></span>' . date( 'F', mktime( 0, 0, 0, $month, 1, $year ) ) . '</a></li>';
				echo '<ul class="days" data-year="' . $year . '" data-month="' . $month . '">';
				$days = $wpdb->get_col("SELECT DISTINCT DAY(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND MONTH(post_date) = '" . $month . "' AND YEAR(post_date) = '" . $year . "' ORDER BY post_date DESC");
				echo '<li class="select"><span class="icon icon-close"></span>' . __( 'Select day', 'madeleine' ) . '</li>';
				foreach( $days as $day ):
					echo '<li class="day" data-value="' . $day . '"><a href="' . esc_url( get_day_link( $year, $month, $day ) ) . '"><span class="icon icon-dropdown"></span>' . $day . '</a></li>';
				endforeach;
				echo '</ul>';
			endforeach;
			echo '<ul class="months" data-year="' . $year . '">';
			echo $months_list;
			echo '</ul>';
		endforeach;
		echo '<ul class="years active">';
		echo $years_list;
		echo '</ul>';
		echo '</div>';
	}
}


/**
 * 04 Entry
 * 
 * Functions related to an entry's output (excerpt, class, thumbnail, comments...).
 *
 */


/**
 * Changes the excerpt "more" text
 * 
 * @param string $text
 * @return string
 */

if ( !function_exists( 'madeleine_entry_excerpt_more' ) ) {
	function madeleine_entry_excerpt_more( $text ) {
		return '&#8230; <a href="'. esc_url( get_permalink() ) . '">&rarr;</a>';
	}
}
add_filter( 'excerpt_more', 'madeleine_entry_excerpt_more' );


/**
 * Changes the excerpt length depending on if the post has a thumbnail or not.
 * 
 * @param integer $length
 * @return integer
 */

if ( !function_exists( 'madeleine_entry_excerpt_length' ) ) {
	function madeleine_entry_excerpt_length( $length ) {
		global $post;
		if ( has_post_thumbnail( $post->ID ) )
			return 20;
		else
			return 90;
	}
}
add_filter( 'excerpt_length', 'madeleine_entry_excerpt_length' );


/**
 * Adds a "jump" menu on a review's single page
 * 
 * @param string $content
 * @return string
 */

if ( !function_exists( 'madeleine_entry_content' ) ) {
	function madeleine_entry_content( $content ) {
		global $post;
		if ( !is_feed() && is_single() ):
			if ( $post->post_type == 'review' ):
				$dom = new DOMDocument;
				$dom->loadHTML( $content );
				$xpath = new DOMXPath( $dom );
				$sections = array();
				foreach ( $xpath->query("//h2") as $node ):
					$node->setAttribute( 'id', strtolower( $node->nodeValue ) );
					$node->setAttribute( 'class', 'chapter' );
					$sections[] = $node->nodeValue;
				endforeach;
				$content = $dom->saveHtml();
				$jump = '<div id="jump">';
				$jump .= '<em class="section">' . __( 'Jump to', 'madeleine' ) . '</em>';
				$jump .= '<a class="on" href="#start">' . __( 'Start', 'madeleine' ) . '</a>';
				foreach( $sections as $section ):
					$jump .= '<a href="#' . strtolower( $section ) . '">' . $section . '</a>';
				endforeach;
				$jump .= '<a href="#verdict">' . __( 'Verdict', 'madeleine' ) . '</a>';
				$jump .= '<a href="#comments">' . __( 'Comments', 'madeleine' ) . '</a>';
				$jump .= '</div>';
				$content .= $jump;
			endif;
		endif;
		return $content;
	}
}
add_filter( 'the_content', 'madeleine_entry_content' );


/**
 * Adds a category class to the post class
 * 
 * @param string $classes
 * @return string
 */

if ( !function_exists( 'madeleine_entry_post_class' ) ) {
	function madeleine_entry_post_class( $classes ) {
		$top_category_ID = madeleine_top_category();
		$top_category = get_category( $top_category_ID );
		$classes[] = 'category-' . $top_category->category_nicename;
		return $classes;
	}
}
add_filter( 'post_class', 'madeleine_entry_post_class' );


/**
 * Adds a permalink to a post's thumbnail
 * 
 * @param string $size
 */

if ( !function_exists( 'madeleine_entry_thumbnail' ) ) {
	function madeleine_entry_thumbnail( $size = 'thumbnail' ) {
		if ( has_post_thumbnail() ):
			echo '<a href="' . esc_url( get_permalink() ) . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
		endif;
	}
}


/**
 * Returns the thumbnail URL
 * 
 * @param string $size
 */

if ( !function_exists( 'madeleine_entry_thumbnail_url' ) ) {
	function madeleine_entry_thumbnail_url( $size = 'medium' ) {
		if ( has_post_thumbnail() ):
			$thumbnail_id = get_post_thumbnail_id();
			$thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, $size, true );
			return $thumbnail_url[0];
		endif;
	}
}


/**
 * Changes a post's caption to a more HTML5 friendly code
 * 
 * @param string $val
 * @param array $attr
 * @param string $content
 * @return string
 */

if ( !function_exists( 'madeleine_entry_caption' ) ) {
	function madeleine_entry_caption( $val, $attr, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'			=> '',
					'align'		=> 'alignnone',
					'width'		=> '',
					'caption'	=> ''
				),
			$attr)
		);
		
		if ( 1 > (int) $width || empty($caption) )
			return $val;
	 
		if ( $id )
			$id = esc_attr( $id );
			 
		// Add itemprop="contentURL" to image - Ugly hack
		$content = str_replace( 'height=', 'data-height=', $content );
		$content = str_replace( 'width=', 'data-width=', $content );

		return '<figure id="' . $id . '" class="wp-caption ' . esc_attr( $align ) . '">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
	}
}
add_filter( 'img_caption_shortcode', 'madeleine_entry_caption', 10, 3 );


/**
 * Displays an embed video player for YouTube, Vimeo, or Dailymotion.
 *
 */

if ( !function_exists( 'madeleine_entry_video' ) ) {
	function madeleine_entry_video() {
		global $post;
		$youtube_id = get_post_meta( $post->ID, '_madeleine_video_youtube_id', true );
		$dailymotion_id = get_post_meta( $post->ID, '_madeleine_video_dailymotion_id', true );
		$vimeo_id = get_post_meta( $post->ID, '_madeleine_video_vimeo_id', true );
		if ( $youtube_id != '' ):
			echo '<iframe width="640" height="480" src="//www.youtube.com/embed/' . $youtube_id . '" frameborder="0" allowfullscreen></iframe>';
		elseif ( $vimeo_id != '' ):
			echo '<iframe src="http://player.vimeo.com/video/' . $vimeo_id . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		elseif ( $dailymotion_id != '' ):
			echo '<iframe frameborder="0" width="640" height="360" src="http://www.dailymotion.com/embed/video/' . $dailymotion_id . '"></iframe>';
		endif;
	}
}


/**
 * Outputs the HTML for each comment.
 * 
 * @param object $comment
 * @param array $args
 * @param integer $depth
 */

if ( !function_exists( 'madeleine_entry_comments' ) ) {
	function madeleine_entry_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ):
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="pingback">
			<p><?php _e( 'Pingback:', 'madeleine' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', '<span class="comment-edit">', '</span>' ); ?></p>
		<?php
				break;
			default:
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment-article">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'madeleine' ); ?></p>
				<?php endif; ?>
				<div class="comment-avatar">
					<?php $avatar_size = 60;
					if ( '0' != $comment->comment_parent )
						$avatar_size = 40;
					echo get_avatar( $comment, $avatar_size ); ?>
				</div>
				<div class="comment-content">
					<div class="comment-info vcard">
						<?php printf( '<span class="comment-author">%s</span>', get_comment_author_link() ) ?>
						<?php printf( '<a href="%1$s" class="comment-date"><time datetime="%2$s">%3$s</time></a>', esc_url( get_comment_link( $comment->comment_ID ) ), get_comment_time( 'c' ), sprintf( '%1$s at %2$s', get_comment_date(), get_comment_time() ) ); ?>
						<?php edit_comment_link( 'Edit' , '<span class="comment-edit">', '</span>' ); ?>
					</div>
					<div class="comment-text"><?php comment_text(); ?></div>
					<div class="comment-reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply &darr;', 'madeleine' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div>
				</div>
			</article>
		<?php
				break;
		endswitch;
	}
}


/**
 * Displays the info of an entry.
 *
 */

if ( !function_exists( 'madeleine_entry_info' ) ) {
	function madeleine_entry_info() {
		$archive_year = get_the_time('Y'); 
		$archive_month = get_the_time('m'); 
		$archive_day = get_the_time('d'); 
		printf( 'by <strong class="entry-author vcard"><a href="%1$s" title="%2$s" rel="author">%3$s</a></strong> on <time class="entry-date" datetime="%4$s"><a href="%5$s" title="%4$s" rel="bookmark">%6$s</a></time>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'madeleine' ), get_the_author() ) ),
			get_the_author(),
			esc_attr( get_the_date( 'c' ) ),
			esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
			get_the_date()
		);
	}
}


/**
 * Displays the share buttons for each post.
 * Each button's presence is defined by a setting in the WP Customizer.
 *
 */

if ( !function_exists( 'madeleine_entry_share' ) ) {
	function madeleine_entry_share() {
		$social_options = get_option( 'madeleine_options_social' );
		$social_buttons = '';
		$permalink = get_permalink();
		$encoded_permalink = urlencode( $permalink );
		$title = get_the_title();
		if ( isset( $social_options['buttons'] ) ):
			foreach ( $social_options['buttons'] as $key => $value):
				if ( $value == 1 ):
					$slug = str_replace( '_button', '', $key );
					$social_buttons .= '<div class="share">';
					switch( $slug ):
						case 'twitter':
							$social_buttons .= '<a href="//twitter.com/share" class="twitter-share-button" data-url="' . $permalink . '" data-text="' . $title . '">Tweet</a>';
							$social_buttons .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
							break;
						case 'facebook':
							$social_buttons .= '<iframe src="http://www.facebook.com/plugins/like.php?href=' . $encoded_permalink . '&width=128&height=21&colorscheme=light&layout=button_count&action=like&show_faces=false&send=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:128px; height:21px;" allowTransparency="true"></iframe>';
							break;
						case 'google':
							$social_buttons .= '<script src="https://apis.google.com/js/plusone.js"></script>';
							$social_buttons .= '<g:plus action="share" href="' . $encoded_permalink . '" annotation="bubble"></g:plus>';
							break;
						case 'pinterest':
							$social_buttons .= '<a href="http://pinterest.com/pin/create/button/?url=' . $encoded_permalink . '&description=' . $title . '" data-pin-do="buttonPin" data-pin-config="beside"><img src="http://assets.pinterest.com/images/pidgets/pin_it_button.png"></a>';
							$social_buttons .= '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>';
							break;
						case 'reddit':
							$social_buttons .= '<script type="text/javascript">reddit_url="' . $permalink . '";</script>';
							$social_buttons .= '<script type="text/javascript" src="http://www.reddit.com/static/button/button1.js"></script>';
							break;
					endswitch;
					$social_buttons .= '</div>';
				endif;
			endforeach;
		endif;
		if ( $social_buttons != '' ):
			echo '<div class="entry-share">';
			echo $social_buttons;
			echo '<div style="clear: left;"></div></div>';
		endif;
	}
}


/**
 * Displays (or returns) a square box for a review's rating.
 * The background color of the box depends on the rating's value.
 *
 * @param integer $id The ID of the review.
 * @param boolean $echo
 */

if ( !function_exists( 'madeleine_entry_rating' ) ) {
	function madeleine_entry_rating( $id, $echo = true ) {
		$reviews_options = get_option( 'madeleine_options_reviews' );
		$maximum_rating = ( isset( $reviews_options['maximum_rating'] ) ) ? $reviews_options['maximum_rating'] : 10;
		$rating = get_post_meta( $id, '_madeleine_review_rating', true );
		$range = floor( $rating * 10 / $maximum_rating );
		$div = '<div class="entry-rating rating-' . $range . '">' . $rating . '</div>';
		if ( $echo )
			echo $div;
		else
			return $div;
	}
}


/**
 * Displays (or returns) an HTML element for a review's price.
 *
 * @param integer $id The ID of the review.
 * @param boolean $echo
 */

if ( !function_exists( 'madeleine_entry_price' ) ) {
	function madeleine_entry_price( $id, $echo = true ) {
		$price = get_post_meta( $id, '_madeleine_review_price', true );
		if ( $price ):
			$div = '<p class="entry-price price-' . floor( $price ) . '">$' . $price . '</p>';
			if ( $echo )
				echo $div;
			else
				return $div;
		endif;
	}
}


/**
 * Displays the HTML of a reviews's verdict.
 * It displays the review's rating and a list of pros and cons.
 *
 * @param integer $id The ID of the review.
 */

if ( !function_exists( 'madeleine_entry_verdict' ) ) {
	function madeleine_entry_verdict( $id ) {
		$good = get_post_meta( $id, '_madeleine_review_good', true );
		$bad = get_post_meta( $id, '_madeleine_review_bad', true );
		$lists = array(
			'good'	=> array($good, __( 'Good', 'madeleine' )),
			'bad'		=> array($bad, __( 'Bad', 'madeleine' ))
		);
		echo '<div class="entry-value">';
		foreach( $lists as $key => $value ):
			echo '<div class="entry-value-' . $key . '">';
			echo '<h4 class="section">' . ucwords( $value[1] ) . '</h4>';
			echo '<ul class="entry-value-list">';
			$items = explode( "\n", $value[0] );
			foreach( $items as $item ):
				echo '<li>' . $item . '</li>';
			endforeach;
			echo '</ul>';
			echo '</div>';
		endforeach;
		echo '<p style="clear: left;"></p>';
		echo '</div>';
	}
}


/**
 * Removes the width and height parameters of the <img> tags.
 * It makes the max-width CSS property work.
 *
 * @param string $html
 */

if ( !function_exists( 'madeleine_entry_images' ) ) {
	function madeleine_entry_images( $html ) {
		 $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
		 return $html;
	}
}
add_filter( 'post_thumbnail_html', 'madeleine_entry_images', 10 );
add_filter( 'image_send_to_editor', 'madeleine_entry_images', 10 );
add_filter( 'wp_get_attachment_link', 'madeleine_entry_images', 10 );


/**
 * Modifies the entry title for links and quotes.
 * 
 * @param string $title
 * @param integer $id
 * @return string
 */

if ( !function_exists( 'madeleine_entry_title' ) ) {
	function madeleine_entry_title( $title, $id ) {
		if ( !is_admin() ):
			$format = get_post_format( $id );
			if ( $format == 'link' ):
				$link_url =	get_post_meta( get_the_ID(), '_madeleine_link_url', true );
				return '<a href="' . esc_url( $link_url ) . '">' . $title . '</a> &rarr;';
			elseif ( $format == 'quote' ):
				return '&#8220; ' . $title . ' &#8221;';
			endif;
		endif;
		return $title;
	}
}
add_filter( 'the_title', 'madeleine_entry_title', 10, 2 );


/**
 * Displays an entry's categories list.
 *
 */

if ( !function_exists( 'madeleine_entry_category' ) ) {
	function madeleine_entry_category() {
		$category_list = get_the_category_list( '</li><li>' );
		if ( $category_list ):
			echo '<ul class="entry-category">';
			echo '<li>' . $category_list . '</li>';
			echo '</ul>';
		endif;
	}
}


/**
 * 05 Reviews
 * 
 * Functions related to the Reviews custom post type and its taxonomies (Product and Brand).
 *
 */



/**
 * Register the reviews custom post type and
 * the product and brand taxonomies.
 *
 */

if ( !function_exists( 'madeleine_register_reviews' ) ) {
	function madeleine_register_reviews() {
		register_post_type( 'review', array(
			'label' => __( 'Reviews', 'madeleine' ),
			'labels' => array(
				'name'								=> __( 'Reviews', 'madeleine' ),
				'singular_name'				=> __( 'Review', 'madeleine' ),
				'all_items'						=> __( 'All Reviews', 'madeleine' ),
				'add_new_item'				=> __( 'Add New Review', 'madeleine' ),
				'edit_item'						=> __( 'Edit Review', 'madeleine' ),
				'new_item'						=> __( 'New Review', 'madeleine' ),
				'view_item'						=> __( 'View Review', 'madeleine' ),
				'search_items'				=> __( 'Search Reviews', 'madeleine' ),
				'not_found'						=> __( 'No reviews found', 'madeleine' ),
				'not_found_in_trash'	=> __( 'No reviews found in Trash', 'madeleine' )
			 ),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'supports' => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbaks',
				'custom-fields',
				'comments',
				'revisions'
			),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'reviews'
			)
		));
		register_taxonomy( 'product', null, array(
			'label' => __( 'Products', 'madeleine' ),
			'labels' => array(
				'name'					=> __( 'Products', 'madeleine' ),
				'singular_name'	=> __( 'Product', 'madeleine' ),
				'all_items'			=> __( 'All Products', 'madeleine' ),
				'edit_item'			=> __( 'Edit Product', 'madeleine' ),
				'view_item'			=> __( 'View Product', 'madeleine' ),
				'update_item'		=> __( 'Update Product', 'madeleine' ),
				'add_new_item'	=> __( 'Add New Product', 'madeleine' ),
				'new_item_name'	=> __( 'New Product', 'madeleine' ),
				'search_items'	=> __( 'Search Products', 'madeleine' ),
				'popular_items'	=> __( 'Popular Products', 'madeleine' ),
			),
			'hierarchical' => true,
			'sort' => true
		));
		register_taxonomy( 'brand', null, array(
			'label' => __( 'Brands', 'madeleine' ),
			'labels' => array(
				'name' => __( 'Brands', 'madeleine' ),
				'singular_name'	=> __( 'Brand', 'madeleine' ),
				'all_items'			=> __( 'All Brands', 'madeleine' ),
				'edit_item'			=> __( 'Edit Brand', 'madeleine' ),
				'view_item'			=> __( 'View Brand', 'madeleine' ),
				'update_item'		=> __( 'Update Brand', 'madeleine' ),
				'add_new_item'	=> __( 'Add New Brand', 'madeleine' ),
				'new_item_name'	=> __( 'New Brand', 'madeleine' ),
				'search_items'	=> __( 'Search Brands', 'madeleine' ),
				'popular_items'	=> __( 'Popular Brands', 'madeleine' ),
			),
			'hierarchical' => true,
			'sort' => true
		));
		register_taxonomy_for_object_type( 'product', 'review' );
		register_taxonomy_for_object_type( 'brand', 'review' );
	}
}
add_action( 'init', 'madeleine_register_reviews' );


/**
 * Displays a list of the product taxonomy terms.
 *
 */

if ( !function_exists( 'madeleine_products_list' ) ) {
	function madeleine_products_list() {
		$nav = wp_list_categories('depth=1&orderby=ID&title_li=&taxonomy=product');
		echo $nav;
	}
}


/**
 * Displays a list of the product taxonomy terms as tabs.
 * It's displayed on the homepage.
 *
 * @return string 
 */

if ( !function_exists( 'madeleine_reviews_tabs' ) ) {
	function madeleine_reviews_tabs() {
		$reviews_link = get_post_type_archive_link( 'review' );
		$tabs = '<div id="reviews-tabs" class="tabs">';
		$tabs .= '<p><a href="' . esc_url( $reviews_link ) . '">' . __( 'Reviews', 'madeleine' ) . '</a></p>';
		$tabs .= '<ul>';
		$tabs .= '<li data-id="all"><a href="' . esc_url( $reviews_link ) . '">' . __( 'All', 'madeleine' ) . '</a></li>';
		$tabs .= wp_list_categories('depth=1&echo=0&orderby=ID&title_li=&taxonomy=product');
		$tabs .= '</ul>';
		$tabs .= '<em><a id="reviews-link" href="' . esc_url( $reviews_link ) . '">' . __( 'View all', 'madeleine' ) . ' <span>' . __( 'Reviews', 'madeleine' ) . '</span> &rarr;</a></em>';
		$tabs .= '<div style="clear: left;"></div>';
		$tabs .= '</div>';
		$products = get_categories('orderby=ID&taxonomy=product');
		foreach( $products as $product ):
			$find = 'cat-item-' . $product->term_id . '"';
			$replace = 'cat-item-' . $product->term_id . '" data-id="' . $product->term_id . '"';
			$tabs = str_replace( $find, $replace, $tabs );
			$tabs = str_replace( 'posts', __( 'reviews', 'madeleine' ), $tabs );
		endforeach;
		return $tabs;
	}
}


/**
 * Displays a grid of reviews on the homepage.
 * 
 * @param string $tax_ID
 * @return string
 */

if ( !function_exists( 'madeleine_reviews_grid' ) ) {
	function madeleine_reviews_grid( $tax_ID = 'all' ) {
		$home_options = get_option( 'madeleine_options_home' );
		$reviews_number = ( isset( $home_options['reviews_number'] ) ) ? $home_options['reviews_number'] : 10;
		$args = array(
			'post_type'				=> 'review',
			'posts_per_page'	=> $reviews_number
		);
		if ( $tax_ID != 'all' ):
			$args['tax_query'] = array(
				array(
					'taxonomy'	=> 'product',
					'terms'			=> $tax_ID,
					'field'			=> 'term_id',
				)
			);
		endif;
		$query = new WP_Query( $args );
		$grid = '';
		while ( $query->have_posts() ) {
			$query->the_post();
			$grid .= '<div class="review">';
			$grid .= get_the_post_thumbnail( null, 'tall' );
			$grid .= '<div class="review-text">';
			$grid .= '<h2 class="entry-title">' . get_the_title() . '</h2>';
			$grid .= '<p class="entry-summary">' . get_the_excerpt() . '</p>';
			$grid .= '</div>';
			$grid .= madeleine_entry_rating( get_the_ID(), false );
			$grid .= '<a class="entry-permalink" href="' . esc_url( get_permalink() ) . '"></a>';
			$grid .= '</div>';
		}
		if ( $grid != '' ):
			$grid .= '<div style="clear: left;"></div>';
		endif;
		wp_reset_postdata();
		return $grid;
	}
}


/**
 * Displays a grid of reviews on the homepage depending on the WP Customizer setting.
 *
 */

if ( !function_exists( 'madeleine_reviews_home' ) ) {
	function madeleine_reviews_home() {
		$home_options = get_option( 'madeleine_options_home' );
		if ( $home_options['reviews_status'] == 1 ):
			$reviews_grid = madeleine_reviews_grid();
			if ( $reviews_grid != '' ):
				$reviews_home = madeleine_reviews_tabs();
				$reviews_home .= '<div class="reviews-grid"><div id="reviews-result">';
				$reviews_home .= madeleine_reviews_grid();
				$reviews_home .= '</div><div id="reviews-loading" class="loading"></div></div>';
				echo $reviews_home;
			endif;
		endif;
	}
}


/**
 * Displays a breadcrumb with the product taxonomy terms.
 *
 */

if ( !function_exists( 'madeleine_reviews_breadcrumb' ) ) {
	function madeleine_reviews_breadcrumb() {
		$args = array(
			'depth'			=> 1,
			'orderby'		=> 'ID',
			'title_li'	=> '',
			'taxonomy'	=> 'product'
		);
		echo '<div id="category">';
		echo '<div class="wrap">';
		echo '<strong>';
		echo '<span class="icon icon-dropdown"></span>';
		echo '<a href="' . esc_url( get_post_type_archive_link( 'review' ) ) . '">' . __( 'Reviews', 'madeleine' ) . '</a>';
		echo '</strong>';
		echo '<ul>';
		wp_list_categories( $args );
		echo '</ul>';
		echo '</div>';
		echo '</div>';
	}
}


/**
 * Displays a menu for the reviews to filter them by product, brand, rating, and/or price.
 *
 */

if ( !function_exists( 'madeleine_reviews_menu' ) ) {
	function madeleine_reviews_menu() {
		$reviews_options = get_option( 'madeleine_options_reviews' );
		$maximum_rating = ( isset( $reviews_options['maximum_rating'] ) ) ? $reviews_options['maximum_rating'] : 10;
		$maximum_price = ( isset( $reviews_options['maximum_price'] ) ) ? $reviews_options['maximum_price'] : 2000;
		$reviews_count = wp_count_posts( 'review' );
		$product_args = array(
			'depth'				=> 1,
			'echo'				=> 0,
			'orderby'			=> 'ID',
			'show_count'	=> 1,
			'title_li'		=> '',
			'taxonomy'		=> 'product'
		);
		$brand_args = $product_args;
		$brand_args['taxonomy'] = 'brand';
		$menu = '<a id="menu-icon"><span class="icon icon-dropdown"></span>Menu</a>';
		$menu .= '<div id="menu" data-maximum-rating="' . $maximum_rating . '" data-maximum-price="' . $maximum_price . '">';
		$menu .= '<p class="section"><a href="' . esc_url( get_post_type_archive_link( 'review' ) ) . '">' . __( 'All reviews', 'madeleine' ) . '</a></p>';
		$menu .= '<p class="section">' . __( 'Products', 'madeleine' ) . '</p>';
		$menu .= '<ul id="products">' . madeleine_taxonomy_list( 'product' ) . '</ul>';
		$menu .= '<p class="section">' . __( 'Brands', 'madeleine' ) . '</p>';
		$menu .= '<ul id="brands">' . madeleine_taxonomy_list( 'brand' ) . '</ul>';
		$menu .= '<div id="menu-sliders">';
		$menu .= '<p class="section section-price">' . __( 'Rating', 'madeleine' ) . '</p>';
		$menu .= '<p id="rating-value" class="slider-value"></p>';
		$menu .= '<div id="rating"></div>';
		$menu .= '<p class="section section-price">' . __( 'Price', 'madeleine' ) . '</p>';
		$menu .= '<p id="price-value" class="slider-value"></p>';
		$menu .= '<div id="price"></div>';
		$menu .= '</div>';
		$menu .= '<p id="reviews-filter"><button class="button"><span>' . __( 'Apply filters', 'madeleine' ) . '</span></button></p>';
		$menu .= '</div>';
		$menu = str_replace( 'posts', __( 'reviews', 'madeleine' ), $menu );
		$menu = str_replace( '(', '<span>', $menu );
		$menu = str_replace( ')', '</span>', $menu );
		echo $menu;
	}
}


/**
 * 06 Ajax and GET Parameters
 * 
 * PHP function for Ajax callbacks and additional GET parameters for taxonomies.
 *
 */



/**
 * Provides a callback function for Ajax request.
 *
 */

if ( !function_exists( 'madeleine_ajax_request' ) ) {
	function madeleine_ajax_request() {
		switch ( $_REQUEST['fn'] ) {
			case 'madeleine_reviews_tabs':
				$output = madeleine_reviews_grid( $_REQUEST['id'] );
			break;
			default:
				$output = 'No function specified, check your jQuery.ajax() call.';
			break;
		} 
		echo $output;
		die;
	}
}
add_action( 'wp_ajax_nopriv_madeleine_ajax', 'madeleine_ajax_request' );
add_action( 'wp_ajax_madeleine_ajax', 'madeleine_ajax_request' );


/**
 * Adds HTML GET parameters for the reviews.
 * 
 * @param array $vars
 * @return array
 */

if ( !function_exists( 'madeleine_query_vars' ) ) {
	function madeleine_query_vars( $vars ) {
		$vars[] = 'product_id';
		$vars[] = 'brand_id';
		$vars[] = 'rating_min';
		$vars[] = 'rating_max';
		$vars[] = 'price_min';
		$vars[] = 'price_max';
		return $vars;
	}
}
add_filter( 'query_vars', 'madeleine_query_vars' );


$template_dir = get_template_directory();
require_once( $template_dir . '/admin/init.php' );
require_once( $template_dir . '/settings/init.php' );


?>