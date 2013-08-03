<?php


add_theme_support( 'post-formats', array( 'link', 'image', 'quote', ) );
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


/* Backend */


function hide_editor() {
  if( isset( $_GET['post'] ) ) { 
    $post_id = $_GET['post'];
    if ( $post_id == 119 ){
      remove_post_type_support( 'post', 'editor' );
    }
  }
}
add_action( 'admin_init', 'hide_editor' );


/* Frontend */


function madeleine_excerpt( $text ) {
  return str_replace(' [...]', '... <a href="'. get_permalink() . '">&rarr;</a>', $text);
}
add_filter( 'the_excerpt', 'madeleine_excerpt' );


function madeleine_excerpt_length( $length ) {
  global $post;
  if ( has_post_thumbnail( $post->ID ) ) {
    return 25;
  } else {
    return 75;
  }
}
add_filter( 'excerpt_length', 'madeleine_excerpt_length' );


function madeleine_body_class( $classes ) {
  if ( is_category() || is_single() ) {
    $top_category_ID = madeleine_top_category();
    $top_category = get_category( $top_category_ID );
    $classes[] = 'category-' . $top_category->category_nicename;
  }
  return $classes;
}
add_filter( 'body_class', 'madeleine_body_class' );


function madeleine_post_class( $classes ) {
  $top_category_ID = madeleine_top_category();
  $top_category = get_category( $top_category_ID );
  $classes[] = 'category-' . $top_category->category_nicename;
  return $classes;
}
add_filter( 'post_class', 'madeleine_post_class' );


function madeleine_thumbnail( $size = 'thumbnail' ) {
  if ( has_post_thumbnail() ) {
    echo '<a href="' . get_permalink() . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
  }
}


function madeleine_caption( $val, $attr, $content = null ) {
  extract(shortcode_atts(array(
    'id'      => '',
    'align'   => 'alignnoe',
    'width'   => '',
    'caption' => ''
  ), $attr));
  
  // No caption, no dice... But why width? 
  if ( 1 > (int) $width || empty($caption) )
    return $val;
 
  if ( $id )
    $id = esc_attr( $id );
     
  // Add itemprop="contentURL" to image - Ugly hack
  $content = str_replace( 'height=', 'data-height=', $content );
  $content = str_replace( 'width=', 'data-width=', $content );

  return '<figure id="' . $id . '" class="wp-caption ' . esc_attr($align) . '">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'madeleine_caption', 10, 3 );


function madeleine_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
  ?>
  <li class="pingback">
    <p><?php echo 'Pingback:'; ?> <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', '<span class="comment-edit">', '</span>' ); ?></p>
  <?php
      break;
    default :
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <article id="comment-<?php comment_ID(); ?>" class="comment-article">
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
        <?php if ( $comment->comment_approved == '0' ) : ?>
          <p class="comment-awaiting-moderation"><?php echo 'Your comment is awaiting moderation.'; ?></p>
        <?php endif; ?>
        <div class="comment-text"><?php comment_text(); ?></div>
      </div>
      <div class="comment-reply">
        <?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reply <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
      </div>
    </article>
  <?php
      break;
  endswitch;
}


function madeleine_pagination( $pages = '', $range = 2 ) {
  global $paged;
  $showitems = ( $range * 2 ) + 1;
  if ( empty( $paged ) ) {
    $paged = 1;
  }

  if ( $pages == '' ) {
    global $wp_query;
    $pages = $wp_query->max_num_pages;
    if ( !$pages ) {
      $pages = 1;
    }
  }   

  if ( 1 != $pages ) {
    echo '<div class="pagination">';
    if ( $paged > 2 && $paged > $range+1 && $showitems < $pages ) echo '<a href="' . get_pagenum_link( 1 ) . '" class="page-nav page-first">&laquo;</a>';
    if ( $paged > 1 && $showitems < $pages ) echo '<a href="' . get_pagenum_link( $paged - 1 ) . '" class="page-nav page-previous">&lsaquo;</a>';
    for ( $i=1; $i <= $pages; $i++ ) {
      if ( 1 != $pages &&(  !( $i >= $paged+$range+1 || $i <= $paged-$range-1 ) || $pages <= $showitems  ) ) {
        echo ( $paged == $i )? "<strong>".$i."</strong>":'<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>';
      }
    }
    if ( $paged < $pages && $showitems < $pages ) echo '<a href="' . get_pagenum_link( $paged + 1 ) . '" class="page-nav page-next">&rsaquo;</a>';  
    if ( $paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages ) echo '<a href="' . get_pagenum_link( $pages ) . '" class="page-nav page-last">&raquo;</a>';
    echo "</div>\n";
  }
}


function madeleine_tags( $query ) {
  if ( $query->is_tag() && $query->is_main_query() ) {
    $query->set( 'posts_per_page', 9 );
  }
}
add_action( 'pre_get_posts', 'madeleine_tags' );


function madeleine_top_category( $cat_ID = 1 ) {
  if ( isset( $cat_ID ) ) {
    $cat = $cat_ID;
  } elseif ( is_category() ) {
    $cat = get_query_var('cat');
  } elseif ( is_attachment() ) {
    $cat = 2;
  } else {
    $categories = get_the_category();
    $cat = $categories[0]->cat_ID;
  }
  $category = get_category( $cat );
  if ( isset( $category ) ) {
    if ( $category->category_parent == '0' ) {
      $top_category_ID = $cat;
    } else {
      $top_category_ID = $category->category_parent;
    }
    return $top_category_ID;
  }
}


function madeleine_category_description() {

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


function madeleine_focus() {
  $args = array(
    'ignore_sticky_posts' => 1,
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


function madeleine_popular() {
  global $wpdb;
  madeleine_register_popularity_table();
  $populars = $wpdb->get_results( 
    "
    SELECT post_id, total
    FROM $wpdb->madeleine_popularity
    ORDER BY total DESC
    LIMIT 5
    "
  );
  if ( $populars ) {
    echo '<ul class="popular">';
    foreach ( $populars as $popular ) {
      $id = $popular->post_id;
      $categories = get_the_category( $id );
      $category = get_category( madeleine_top_category( $categories[0] ) );
      echo '<li class="post category-' . $category->category_nicename . '">';
      echo '<em data-total="' . $popular->total . '"></em>';
      echo '<strong><span>' . $popular->total . '</span></strong> ';
      echo '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';
      echo '</li>';
    }
    echo '</ul>';
    echo '<div style="clear: left;"></div>';
  }
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
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
  $cat   = get_query_var('cat');
  $args  = array(
    'cat'                 => $cat,
    'ignore_sticky_posts' => 1,
    'paged'               => $paged,
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


function gplus_shares($url){
    // G+ DATA
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p",
"params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},
"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $result = curl_exec ($ch);
    curl_close ($ch);
    return json_decode($result, true);
}


function madeleine_share_count() {
  $url = 'http://uscodebeta.house.gov/download/download.shtml';

  $finfo = json_decode(file_get_contents('http://api.ak.facebook.com/restserver.php?v=1.0&method=links.getStats&urls='.$url.'&format=json'));
  $tinfo = json_decode(file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.$url));
  $pinfo = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1",file_get_contents('http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url='.$url)));
  $gplus = gplus_shares($url);

  $shares = array(
    'facebook'=> isset($finfo[0]) ? $finfo[0]->total_count : NULL,
    'twitter'=> isset($tinfo->count) ? $tinfo->count : NULL,
    'google'=> isset($gplus[0]['result']) ? $gplus[0]['result']['metadata']['globalCounts']['count'] : NULL,
    'pinterest'=> isset($pinfo->count) ? $pinfo->count : NULL
  );

  return $shares;
}

 
function madeleine_register_popularity_table() {
  global $wpdb;
  $wpdb->madeleine_popularity = "{$wpdb->prefix}madeleine_popularity";
}

add_action( 'init', 'madeleine_register_popularity_table', 1 );


function madeleine_create_popularity_table() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate;
  madeleine_register_popularity_table();
  $sql_create_table = "CREATE TABLE IF NOT EXISTS {$wpdb->madeleine_popularity} (
    post_id smallint(5) unsigned NOT NULL,
    facebook smallint(5) unsigned NOT NULL default '0',
    twitter smallint(5) unsigned NOT NULL default '0',
    google smallint(5) unsigned NOT NULL default '0',
    pinterest smallint(5) unsigned NOT NULL default '0',
    total smallint(5) unsigned NOT NULL default '0',
    PRIMARY KEY  (post_id)
   ) $charset_collate; ";
   
  dbDelta( $sql_create_table );
  madeleine_initiate_popularity();
}
add_action( 'after_switch_theme', 'madeleine_create_popularity_table' );


function madeleine_initiate_popularity() {
  global $post;
  $posts = get_posts('posts_per_page=-1');
  foreach( $posts as $post ) {
    madeleine_insert_popularity( $post->ID );
  }
}


function madeleine_insert_popularity( $post_id ) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM $wpdb->madeleine_popularity WHERE post_id = $post_id");
  if ( $result == null ) {
    $wpdb->insert( 
      $wpdb->madeleine_popularity, 
      array( 
        'post_id' => $post_id
      ), 
      array( '%d' ) 
    );
  }
}
add_action( 'publish_post', 'madeleine_insert_popularity' );


function madeleine_update_popularity( $post_id ) {
  global $wpdb;
  $shares = madeleine_share_count();
  $total = array_sum( $shares );
  $wpdb->update(
    $wpdb->madeleine_popularity,
    array(
      'facebook' => $shares['facebook'],
      'twitter' => $shares['twitter'],
      'google' => $shares['google'],
      'pinterest' => $shares['pinterest'],
      'total' => $total
    ),
    array( 'post_id' => $post_id ),
    array( '%d' ),
    array( '%d' )
  );
}
add_action( 'save_post', 'madeleine_update_popularity' );


function madeleine_schedule_popularity( $post_id ) {
  $schedule = wp_get_schedule( 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
  $post = get_post( $post_id );
  if ( $schedule == false && $post->post_status == 'publish' ) {
    wp_schedule_event( current_time ( 'timestamp' ), 'daily', 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
  }
}
// add_action( 'save_post', 'madeleine_schedule_popularity' );
add_action( 'madeleine_popularity_event', 'madeleine_update_popularity' );


function madeleine_delete_popularity( $post_id ) {
  wp_clear_scheduled_hook( 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
}
add_action( 'delete_post', 'madeleine_delete_popularity' );