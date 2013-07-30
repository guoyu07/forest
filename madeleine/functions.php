<?php


add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );
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


if ( function_exists('register_sidebar') ) {
	register_sidebar( $sidebar_arguments );
}


function custom_excerpt( $text ) {
  return str_replace('[...]', '<a href="'. get_permalink($post->ID) . '">&rarr;</a>', $text);
}
add_filter( 'the_excerpt', 'custom_excerpt' );


function custom_excerpt_length( $length ) {
  global $post;
  if ( has_post_thumbnail( $post->ID ) ) {
    return 25;
  } else {
    return 75;
  }
}
add_filter( 'excerpt_length', 'custom_excerpt_length' );


function madeleine_thumbnail( $size = 'thumbnail' ) {
  echo '<a href="#" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
}


function madeleine_breadcrumb() {
  $cat = get_query_var('cat');
  $category = get_category($cat);
  if ( $category->category_parent == '0' ) {
    $parent = $cat;
    $title = $category->cat_name;
  } else {
    $parent = $category->category_parent;
    $title = get_cat_name($parent);
  }
  $args = array(
    'child_of'          => $parent,
    'hide_empty '       => 0,
    // 'show_option_none'  => '',
    'title_li'          => ''
  );
  $link = get_category_link( $parent );
  echo '<strong>';
  echo '<a href="' . esc_url( $link ) . '">' . $title . '</a>';
  echo '</strong>';
  echo '<ul>';
  wp_list_categories( $args );
  echo '</ul>';
}


function madeleine_focus() {
  $args = array(
    'post__in' => array( 1, 10, 16, 23, 57 )
  );
  $query = new WP_Query( $args );
  $n = 1;
  echo '<div id="focus">';
  while ( $query->have_posts() ) {
    $query->the_post();
    $categories = get_the_category();
    $class = 'focus';
    foreach ( $categories as $category ) {
      $class .= ' focus-' . $category->category_nicename;
    }
    echo '<article class="' . $class . '" id="focus-' . $n . '">';
    if ( $n == 1 ) {
      madeleine_thumbnail( 'focus' );
    } elseif ( $n == 5 ) {
      madeleine_thumbnail( 'tall' );
    } else {
      madeleine_thumbnail( 'wide' );
    }
    echo '<div class="focus-text">';
    echo '<h2 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
    echo '<p class="entry-excerpt">' . get_the_excerpt() . '</p>';
    echo '</div>';
    echo '</article>';
    $n++;
  }
  echo '</div>';
  wp_reset_postdata();
}


function madeleine_images() {
  $args = array(
    'post_type' => 'post',
    'tax_query' => array(
      array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => array( 'post-format-image' )
      )
    )
  );
  $query = new WP_Query( $args );
  echo '<ul class="images">';
  while ( $query->have_posts() ) {
    $query->the_post();
    echo '<li>';
    madeleine_thumbnail( 'thumbnail' );
    echo '</li>';
  }
  echo '</ul>';
  echo '<div style="clear: left;"></div>';
  wp_reset_postdata();
}


function madeleine_links() {
  $args = array(
    'post_type' => 'post',
    'tax_query' => array(
      array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => array( 'post-format-link' )
      )
    )
  );
  $query = new WP_Query( $args );
  echo '<ul class="links">';
  while ( $query->have_posts() ) {
    $query->the_post();
    echo '<li><a href="' . get_the_excerpt() . '">' . get_the_title() . ' <span>&rarr;</span></a></li>';
  }
  echo '</ul>';
  wp_reset_postdata();
}


function madeleine_quotes() {
  $args = array(
    'post_type' => 'post',
    'tax_query' => array(
      array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => array( 'post-format-quote' )
      )
    )
  );
  $query = new WP_Query( $args );
  echo '<ul class="quotes">';
  while ( $query->have_posts() ) {
    $query->the_post();
    echo '<li>';
    echo '<blockquote>&#8220; ' . get_the_title() . ' &#8221;</blockquote>';
    echo '<p>' . get_the_excerpt() . '</p>';
    echo '</li>';
  }
  echo '</ul>';
  echo '<div style="clear: left;"></div>';
  wp_reset_postdata();
}


function madeleine_standard_posts() {
  $cat = get_query_var('cat');
  $args = array(
    'cat'                 => $cat,
    'ignore_sticky_posts' => 1,
    'post_type'           => 'post',
    'tax_query'           => array(
      array(
        'taxonomy' => 'post_format',
        'field'    => 'slug',
        'terms'    => array( 
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
        'operator' => 'NOT IN'
      )
    )
  );
  query_posts( $args );
}


function madeleine_posted_on() {
	printf( 'by <strong class="entry-author vcard"><a href="%5$s" title="%6$s" rel="author">%7$s</a></strong> on <time class="entry-date" datetime="%3$s"><a href="%1$s" title="%2$s" rel="bookmark">%4$s</a></time>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
		get_the_author()
	);
}