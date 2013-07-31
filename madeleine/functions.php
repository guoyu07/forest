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
  return str_replace(' [...]', '... <a href="'. get_permalink() . '">&rarr;</a>', $text);
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


function madeleine_body_class() {
  $class = '';
  if ( is_category() ) {
    $top_category_ID = madeleine_top_category();
    $top_category = get_category( $top_category_ID );
    $class = 'category-' . $top_category->category_nicename;
  }
  return body_class( $class );
}


function madeleine_thumbnail( $size = 'thumbnail' ) {
  echo '<a href="' . get_permalink() . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
}


function madeleine_top_category() {
  if ( is_category() ) {
    $cat = get_query_var('cat');
  } elseif ( is_single() ) {
    $categories = get_the_category();
    $cat = $categories[0]->cat_ID;
  }
  $category = get_category( $cat );
  if ( $category->category_parent == '0' ) {
    $top_category_ID = $cat;
  } else {
    $top_category_ID = $category->category_parent;
  }
  return $top_category_ID;
}


function madeleine_breadcrumb() {
  $top_category_ID = madeleine_top_category();
  $top_category = get_category( $top_category_ID );
  $title = $top_category->cat_name;
  $args = array(
    'child_of'    => $top_category_ID,
    'hide_empty'  => 0,
    'orderby'     => 'ID',
    'title_li'    => ''
  );
  $link = get_category_link( $top_category_ID );
  echo '<strong>';
  echo '<a href="' . esc_url( $link ) . '">' . $title . '</a>';
  echo '</strong>';
  echo '<ul>';
  wp_list_categories( $args );
  echo '</ul>';
}


function madeleine_nav() {
  $cats = get_categories('hide_empty=0&order=desc');
  $nav = wp_list_categories('depth=1&echo=0&hide_empty=0&orderby=ID&title_li=');
  foreach( $cats as $cat ) {
    $find = 'cat-item-' . $cat->term_id . '"';
    $replace =  'category-' . $cat->slug . '"';
    $nav = str_replace( $find, $replace, $nav );
    $find = 'cat-item-' . $cat->term_id . ' ';
    $replace = 'category-' . $cat->slug . ' ';
    $nav = str_replace( $find, $replace, $nav );
  }
  echo $nav;
}
// add_filter( 'wp_list_categories', 'madeleine_list_categories' );


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
  $cat   = get_query_var('cat');
  $args  = array(
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
  if ( get_query_var('m') ) {
    $m     = get_query_var('m');
    if ( is_year() ) {
      $year  = substr( $m, 0, 4);
      $args['year'] = $year;
    } elseif ( is_month() ) {
      $year  = substr( $m, 0, 4);
      $month = substr( $m, 4, 2);
      $args['m'] = $year . $month;
    } elseif ( is_day() ) {
      $year  = substr( $m, 0, 4);
      $month = abs( substr( $m, 4, 2) );
      $day   = abs( substr( $m, 6, 2) );
      $args['year'] = $year;
      $args['monthnum'] = $month;
      $args['day'] = $day;
    }
  }
  query_posts( $args );
}


function madeleine_posted_on() {
  $archive_year  = get_the_time('Y'); 
  $archive_month = get_the_time('m'); 
  $archive_day   = get_the_time('d'); 
	printf( 'by <strong class="entry-author vcard"><a href="%1$s" title="%2$s" rel="author">%3$s</a></strong> on <time class="entry-date" datetime="%4$s"><a href="%5$s" title="%4$s" rel="bookmark">%6$s</a></time>',
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    esc_attr( sprintf( 'View all posts by %s', get_the_author() ) ),
    get_the_author(),
    esc_attr( get_the_date( 'c' ) ),
		esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
		get_the_date()
	);
}


function madeleine_date_vars() {
  $m     = get_query_var('m');
  if ( is_year() ) {
    $year  = substr( $m, 0, 4);
    $args['year'] = $year;
  } elseif ( is_month() ) {
    $year  = substr( $m, 0, 4);
    $month = substr( $m, 4, 2);
    $args['m'] = $year . $month;
  } elseif ( is_day() ) {
    $year  = substr( $m, 0, 4);
    $month = abs( substr( $m, 4, 2) );
    $day   = abs( substr( $m, 6, 2) );
    $args['year'] = $year;
    $args['monthnum'] = $month;
    $args['day'] = $day;
  }
}

function madeleine_date_archive() {
  $archive_year  = get_the_date('Y');
  $archive_month = get_the_date('m');
  $archive_day   = get_the_date('d');
  $day_link   = '<a href="' . get_day_link( $archive_year, $archive_month, $archive_day ) . '">' . $archive_day . '</a>';
  $month_link = '<a href="' . get_month_link( $archive_year, $archive_month ) . '">' . $archive_month . '</a>';
  $year_link  = '<a href="' . get_year_link( $archive_year ) . '">' . $archive_year . '</a>';
  if ( is_day() ) :
    echo $day_link . $month_link . $year_link;
  elseif ( is_month() ) :
    echo $month_link . $year_link;
  elseif ( is_year() ) :
    echo $year_link;
  endif;
}


function madeleine_nested_date() {
  global $wpdb;
  $date = get_query_var('m');
  $m = '';
  $d = '';
  if ( is_year() ) {
    $y  = substr( $date, 0, 4);
  } elseif ( is_month() ) {
    $y  = substr( $date, 0, 4);
    $m = abs( substr( $date, 4, 2) );
  } elseif ( is_day() ) {
    $y  = substr( $date, 0, 4);
    $m = abs( substr( $date, 4, 2) );
    $d   = abs( substr( $date, 6, 2) );
  }
  $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC");
  $years_list = '<li class="select">Select year</li>';
  echo '<div id="date-archive" data-year="' . $y . '" data-month="' . $m . '" data-day="' . $d . '">';
  foreach( $years as $year ) :
    $years_list .= '<li class="year" data-value="' . $year . '"><a href="'. get_year_link( $year ). '">' . $year . '</a></li>';
    $months_list = '<li class="select">Select month</li>';
    $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
    foreach( $months as $month ) :
      $months_list .= '<li class="month" data-value="' . $month . '""><a href="' . get_month_link( $year, $month ) . '">' . date( 'F', mktime( 0, 0, 0, $month, 1, $year ) ) . '</a></li>';
      echo '<ul class="days" data-year="' . $year . '" data-month="' . $month . '">';
      $days = $wpdb->get_col("SELECT DISTINCT DAY(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND MONTH(post_date) = '".$month."' AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
      echo '<li class="select">Select day</li>';
      foreach( $days as $day ) :
        echo '<li class="day" data-value="' . $day . '"><a href="' . get_day_link( $year, $month, $day ) . '">' . $day . '</a></li>';
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