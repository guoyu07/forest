<?php

add_theme_support( 'post-thumbnails' );
add_image_size( 'thumbnail', 100, 100, true );
add_image_size( 'medium', 300, 150, true );
add_image_size( 'large', 960, 340, true );
add_image_size( 'list', 620, 220, true );
add_image_size( 'square', 320, 320, true );
add_image_size( 'focus', 640, 300, true );

// function custom_excerpt( $text ) {
//   return str_replace('[...]', '<a href="'. get_permalink($post->ID) . '">' . '&raquo;' . '</a>', $text);
// }
// add_filter( 'the_excerpt', 'custom_excerpt' );

// function custom_excerpt_length( $length ) {
//   return 35;
// }
// add_filter( 'excerpt_length', 'custom_excerpt_length' );

function madeleine_thumbnail( $size = 'thumbnail' ) {
  echo '<a href="#" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
}

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_posted_on() {
	printf( __( '<span class="entry-author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span> - <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;