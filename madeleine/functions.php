<?php

/*
01 Settings
02 Common functions
03 Archives
04 Post
05 Review
06 Ajax
*/

// 01 Settings


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


$sidebar_arguments = array(
  'name'          => __( 'Sidebar', 'madeleine' ),
  'before_widget' => '<section id="%1$s" class="widget %2$s">',
  'after_widget'  => '</section>',
  'before_title'  => '<h4 class="widget-title">',
  'after_title'   => '</h4>'
);


if ( function_exists('register_sidebar') )
  register_sidebar( $sidebar_arguments );





if ( !function_exists( 'madeleine_enqueue_scripts' ) ) {
  function madeleine_enqueue_scripts() {
    $js_directory = get_template_directory_uri() . '/js/';
    wp_register_script( 'global', $js_directory . 'global.js', 'jquery', '1.0' );
    wp_register_script( 'date', $js_directory . 'date.js', 'jquery', '1.0' );
    wp_register_script( 'home', $js_directory . 'home.js', 'jquery', '1.0' );
    wp_register_script( 'jump', $js_directory . 'jump.js', 'jquery', '1.0' );
    wp_register_script( 'pinterest', $js_directory . 'pinterest.js', 'jquery', '1.0' );
    wp_register_script( 'reviews', $js_directory . 'reviews.js', 'jquery', '1.0' );
    
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-slider' );
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


function madeleine_categories_colors() {
  $cats = get_categories( 'hide_empty=0&orderby=ID&parent=0' );
  $category_meta = get_option( 'madeleine_category_meta' );
  $style = '<style>';
  foreach( $cats as $cat ):
    if ( isset( $category_meta[$cat->term_id] ) ):
      $color = $category_meta[$cat->term_id]['color'];
    else:
      $color = '#d0574e';
    endif;
    $slug = $cat->slug;
    $style .= '.post.category-' . $slug . ' a,#nav .category-' . $slug . ' a:hover,.tabs .category-' . $slug . ' a,body.category-' . $slug . ' #nav .current-cat a,#category.category-' . $slug . ' .current-cat a{ color: ' . $color . ';}';
    $style .= '.tabs .category-' . $slug . ' a:hover,.tabs .category-' . $slug . ' .on,#category.category-' . $slug . ' strong,.category-' . $slug . ' .entry-category a,#popular .category-' . $slug . ' em,#popular .category-' . $slug . ' strong,.format-image.category-' . $slug . ' .entry-thumbnail:hover:after,.format-video.category-' . $slug . ' .entry-thumbnail:hover:after{ background-color: ' . $color . ';}';
    $style .= '.quote.category-' . $slug . ',#category.category-' . $slug . ' strong:after{ border-left-color: ' . $color . ';}';
    $style .= '#nav .category-' . $slug . ' a,body.category-' . $slug . ',body.category-' . $slug . ' #nav .current-cat a,#category.category-' . $slug . ' .wrap,.focus.category-' . $slug . ' .focus-text{ border-top-color: ' . $color . ';}';
  endforeach;
  $style .= '</style>';
  echo $style;
}
add_action( 'wp_head', 'madeleine_categories_colors' );


// 02 Common functions


if ( !function_exists( 'madeleine_get_redirect_target' ) ) {
  function madeleine_get_redirect_target( $destination ) {
    $headers = get_headers( $destination, 1 );
    return $headers['Location'];
  }
}


if ( !function_exists( 'madeleine_get_youtube_id' ) ) {
  function madeleine_get_youtube_id( $url ) {
    if ( preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match) ):
      return $match[1];
    else:
      return null;
    endif;
  }
}


if ( !function_exists( 'madeleine_get_vimeo_id' ) ) {
  function madeleine_get_vimeo_id( $url ) {
    if ( preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $url, $match) ):
      return $match[2];
    else:
      return null;
    endif;
  }
}


if ( !function_exists( 'madeleine_get_dailymotion_id' ) ) {
  function madeleine_get_dailymotion_id( $url ) {
    if ( preg_match('/^.+dailymotion.com\/((video|hub)\/([^_]+))?[^#]*(#video=([^_&]+))?/', $url, $match) ):
      return $match[3];
    else:
      return null;
    endif;
  }
}


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

      $new_filename =  $source . '_' . $image_id . '_' . basename( $image_url );
      $upload = wp_upload_bits( $new_filename, null, $image_contents );

      if ( $upload['error'] ):
        $error = new WP_Error( 'thumbnail_upload', __( 'Error uploading image data:', 'madeleine' ) . ' ' . $upload['error'] );
        return $error;
      else:
        $filename  = $upload['file'];
        $image_url = $upload['url'];
        $wp_filetype = wp_check_filetype( basename( $filename  ), null );
        $wp_upload_dir = wp_upload_dir();
        $attachment = array(
          'guid' => $wp_upload_dir['url'] . '/' . basename( $filename  ), 
          'post_mime_type' => $wp_filetype['type'],
          'post_title' => preg_replace('/\.[^.]+$/', '', basename( $filename ) ),
          'post_content' => '',
          'post_status' => 'inherit'
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


if ( !function_exists( 'madeleine_categories_list' ) ) {
  function madeleine_categories_list() {
    $cats = get_categories('hide_empty=0&orderby=ID');
    $nav = wp_list_categories('depth=1&echo=0&hide_empty=0&orderby=ID&title_li=');
    foreach( $cats as $cat ):
      $find = 'cat-item-' . $cat->term_id . '"';
      $replace =  'category-' . $cat->slug . '"';
      $nav = str_replace( $find, $replace, $nav );
      $find = 'cat-item-' . $cat->term_id . ' ';
      $replace = 'category-' . $cat->slug . ' ';
      $nav = str_replace( $find, $replace, $nav );
    endforeach;
    echo $nav;
  }
}


if ( !function_exists( 'madeleine_tags_list' ) ) {
  function madeleine_tags_list() {
    $tags = get_tags();
    $tags_list = '<div id="tags" class="dropdown">';
    $tags_list .= '<ul>';
    $current_tag = get_query_var( 'tag' );
    foreach ( $tags as $tag ) {
      $tag_link = get_tag_link( $tag->term_id );
      if ( $tag->slug == $current_tag )
        $tags_list .= '<li class="on">';
      else
        $tags_list .= '<li>';
      $tags_list .= '<a href="' . $tag_link . '" class="' . $tag->slug . '">' . $tag->name . '<span>' . $tag->count . '</span></a>';
    }
    $tags_list .= '</ul>';
    $tags_list .= '</div>';
    echo $tags_list;
  }
}


if ( !function_exists( 'madeleine_taxonomy_list' ) ) {
  function madeleine_taxonomy_list( $taxonomy ) {
    $terms = get_categories('hide_empty=0&taxonomy=' . $taxonomy );
    $list = wp_list_categories('depth=1&echo=0&hide_empty=0&show_count=1&title_li=&taxonomy=' . $taxonomy );
    foreach( $terms as $term ):
      $find = 'class="cat-item cat-item-' . $term->term_id;
      $replace =  ' data-id="' . $term->term_id . '" data-slug="' . $term->slug . '" class="cat-item cat-item-' . $term->term_id;
      $list = str_replace( $find, $replace, $list );
      $list = str_replace( 'posts', $taxonomy . 's', $list );
    endforeach;
    return $list;
  }
}


if ( !function_exists( 'madeleine_trending' ) ) {
  function madeleine_trending( $limit = 16 ) {
    global $wpdb;
    $term_ids = $wpdb->get_col("
      SELECT term_id, taxonomy FROM $wpdb->term_taxonomy
      INNER JOIN $wpdb->term_relationships ON $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id
      INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
      WHERE taxonomy = 'post_tag'
      AND DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= $wpdb->posts.post_date");
    if ( count( $term_ids ) > 0 ):
      $tags = array_unique( $term_ids );
      $tags = array_slice( $tags, 0, $limit );
      foreach ( $tags as $tag ):
        $tag_info = get_tag( $tag );
        echo '<li><a href="' . get_tag_link( $tag ) . '" rel="tag">' . $tag_info->name . '</a></li>';
      endforeach;
    endif;
  }
}


if ( !function_exists( 'madeleine_standard_posts' ) ) {
  function madeleine_standard_posts() {
    $standard_posts  = array(
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
    );
    return $standard_posts;
  }
}


if ( !function_exists( 'madeleine_sticky_posts' ) ) {
  function madeleine_sticky_posts() {
    $sticky_posts = get_option( 'sticky_posts' );
    rsort( $sticky_posts );
    $sticky_posts = array_slice( $sticky_posts, 0, 5 );
    return $sticky_posts;
  }
}


if ( !function_exists( 'madeleine_focus' ) ) {
  function madeleine_focus() {
    $sticky_posts = madeleine_sticky_posts();
    $args = array(
      'ignore_sticky_posts' => 1,
      'post__in' => $sticky_posts
    );
    $query = new WP_Query( $args );
    $n = 1;
    echo '<div id="focus">';
    while ( $query->have_posts() ) {
      $query->the_post();
      $categories = get_the_category();
      $top_category = get_category( madeleine_top_category( $categories[0] ) );
      $category_links = '';
      $class = 'focus category-' . $top_category->category_nicename;;
      foreach ( $categories as $category ):
        $category_links .= '<li><a href="' . get_category_link( $category->cat_ID ) . '">' . $category->name . '</a></li>';
      endforeach;
      echo '<article class="post ' . $class . '" id="focus-' . $n . '">';
      if ( $n == 1 )
        madeleine_entry_thumbnail( 'focus' );
      elseif ( $n == 5 )
        madeleine_entry_thumbnail( 'tall' );
      else
        madeleine_entry_thumbnail( 'wide' );
      echo '<div class="focus-text">';
      echo '<h2 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
      echo '<ul class="entry-category">' . $category_links . '</ul>';
      echo '<p class="entry-excerpt">' . get_the_excerpt() . '</p>';
      echo '</div>';
      echo '</article>';
      $n++;
    }
    echo '</div>';
    wp_reset_postdata();
    return $sticky_posts;
  }
}


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


// 03 Archives


if ( !function_exists( 'madeleine_archive_settings' ) ) {
  function madeleine_archive_settings( $query ) {
    $query->set( 'ignore_sticky_posts', 1 );
    $standard_posts = madeleine_standard_posts();
    if ( ( $query->is_home() ) && $query->is_main_query() ):
      $sticky_posts = madeleine_sticky_posts();
      $query->set( 'tax_query', $standard_posts );
      $query->set( 'post__not_in', $sticky_posts );
    elseif ( $query->is_tag() && $query->is_main_query() ):
      $query->set( 'posts_per_page', -1 );
    elseif ( ( $query->is_post_type_archive( 'review' ) || $query->is_tax( 'product' ) || $query->is_tax( 'brand' ) ) && $query->is_main_query() ):
      $product = get_query_var( 'product_id' ) != '' ? get_query_var( 'product_id' ) : '';
      $brand = get_query_var( 'brand_id' ) != '' ? get_query_var( 'brand_id' ) : '';
      $tax_query = array(
        'relation' => 'AND'
      );
      if ( $product != '' ):
        $tax_query[] = array(
          'taxonomy' => 'product',
          'field' => 'id',
          'terms' => $product,
          'operator' => 'IN'
        );
      endif;
      if ( $brand != '' ):
        $tax_query[] = array(
          'taxonomy' => 'brand',
          'field' => 'id',
          'terms' => $brand,
          'operator' => 'IN'
        );
      endif;
      $rating_min = get_query_var( 'rating_min' ) != '' ? get_query_var( 'rating_min' ) : 0;
      $rating_max = get_query_var( 'rating_max' ) != '' ? get_query_var( 'rating_max' ) : 10;
      $price_min = get_query_var( 'price_min' ) != '' ? get_query_var( 'price_min' ) : 0;
      $price_max = get_query_var( 'price_max' ) != '' ? get_query_var( 'price_max' ) : 2000;
      $rating_range = array( $rating_min, $rating_max );
      $price_range = array( $price_min, $price_max );
      $meta_query = array(
        'relation' => 'AND',
        array(
          'key' => '_madeleine_review_rating',
          'value' => $rating_range,
          'type' => 'numeric',
          'compare' => 'BETWEEN'
        ),
        array(
          'key' => '_madeleine_review_price',
          'value' => $price_range,
          'type' => 'numeric',
          'compare' => 'BETWEEN'
        )
      );
      $query->set( 'tax_query', $tax_query );
      $query->set( 'meta_query', $meta_query );
    endif;
  }
}
add_action( 'pre_get_posts', 'madeleine_archive_settings' );


if ( !function_exists( 'madeleine_next_posts' ) ) {
  function madeleine_next_posts( $already_posted ) {
    $standard_posts = madeleine_standard_posts();
    $post_ids = array(); 
    $offset = get_option( 'posts_per_page' );
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => 10,
      'post__not_in' => $already_posted,
      'offset' => $offset,
      'tax_query' => $standard_posts
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
      echo '<h2 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
      echo '</div>';
      $post_ids[] = get_the_ID();
    }
    echo '</div>';
    echo '<div style="clear: left;"></div>';
    wp_reset_postdata();
    return $post_ids;
  }
}


if ( !function_exists( 'madeleine_category_wheels' ) ) {
  function madeleine_category_wheels( $already_posted ) {
    $cats = get_categories( 'hide_empty=0&orderby=ID&parent=0' );
    $standard_posts = madeleine_standard_posts();
    echo '<div class="wheels">';
    foreach( $cats as $cat ):
      $args = array(
        'cat' => $cat->term_id,
        'post_type' => 'post',
        'posts_per_page' => 5,
        'post__not_in' => $already_posted,
        'tax_query' => $standard_posts
      );
      $query = new WP_Query( $args );
      echo '<div class="wheel category-' . $cat->category_nicename . '">';
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
        echo '<h2 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
        echo '<div class="entry-info">';
        madeleine_entry_info();
        echo '</div>';
        echo '<p class="entry-summary">'. get_the_excerpt() . '</p>';
        echo '</div>';
      }
      echo '</div>';
      wp_reset_postdata();
    endforeach;
    echo '</div>';
    echo '<div style="clear: both;"></div>';
  }
}


if ( !function_exists( 'madeleine_category_breadcrumb' ) ) {
  function madeleine_category_breadcrumb() {
    $top_category_ID = madeleine_top_category();
    $top_category = get_category( $top_category_ID );
    $title = $top_category->cat_name;
    $slug = $top_category->slug;
    $args = array(
      'child_of'    => $top_category_ID,
      'hide_empty'  => 0,
      'orderby'     => 'ID',
      'title_li'    => ''
    );
    $link = get_category_link( $top_category_ID );
    echo '<div id="category" class="category-' . $slug . '">';
    echo '<div class="wrap">';
    echo '<strong>';
    echo '<a href="' . esc_url( $link ) . '">' . $title . '</a>';
    echo '</strong>';
    echo '<ul>';
    wp_list_categories( $args );
    echo '</ul>';
    echo '</div>';
    echo '</div>';
  }
}


if ( !function_exists( 'madeleine_pagination' ) ) {
  function madeleine_pagination( $pages = '', $range = 2 ) {
    global $paged;
    $showitems = ( $range * 2 ) + 1;
    if ( empty( $paged ) )
      $paged = 1;

    if ( $pages == '' ):
      global $wp_query;
      $pages = $wp_query->max_num_pages;
      if ( !$pages )
        $pages = 1;
    endif;

    if ( 1 != $pages ) {
      echo '<div class="pagination">';
      if ( $paged > 2 && $paged > $range+1 && $showitems < $pages ) echo '<a href="' . get_pagenum_link( 1 ) . '" class="page-nav page-first">&laquo;</a>';
      if ( $paged > 1 && $showitems < $pages ) echo '<a href="' . get_pagenum_link( $paged - 1 ) . '" class="page-nav page-previous">&lsaquo;</a>';
      for ( $i=1; $i <= $pages; $i++ ):
        if ( 1 != $pages &&(  !( $i >= $paged+$range+1 || $i <= $paged-$range-1 ) || $pages <= $showitems  ) )
          echo ( $paged == $i )? "<strong>".$i."</strong>":'<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>';
      endfor;
      if ( $paged < $pages && $showitems < $pages ) echo '<a href="' . get_pagenum_link( $paged + 1 ) . '" class="page-nav page-next">&rsaquo;</a>';  
      if ( $paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages ) echo '<a href="' . get_pagenum_link( $pages ) . '" class="page-nav page-last">&raquo;</a>';
      echo "</div>\n";
    }
  }
}


if ( !function_exists( 'madeleine_date_vars' ) ) {
  function madeleine_date_vars() {
    $m = get_query_var('m');
    if ( is_year() ):
      $year  = substr( $m, 0, 4);
      $args['year'] = $year;
    elseif ( is_month() ):
      $year  = substr( $m, 0, 4);
      $month = substr( $m, 4, 2);
      $args['m'] = $year . $month;
    elseif ( is_day() ):
      $year  = substr( $m, 0, 4);
      $month = abs( substr( $m, 4, 2) );
      $day   = abs( substr( $m, 6, 2) );
      $args['year'] = $year;
      $args['monthnum'] = $month;
      $args['day'] = $day;
    endif;
  }
}


if ( !function_exists( 'madeleine_date_archive' ) ) {
  function madeleine_date_archive() {
    $archive_year  = get_the_date('Y');
    $archive_month = get_the_date('m');
    $archive_day   = get_the_date('d');
    $day_link   = '<a href="' . get_day_link( $archive_year, $archive_month, $archive_day ) . '">' . $archive_day . '</a>';
    $month_link = '<a href="' . get_month_link( $archive_year, $archive_month ) . '">' . $archive_month . '</a>';
    $year_link  = '<a href="' . get_year_link( $archive_year ) . '">' . $archive_year . '</a>';
    if ( is_day() ):
      echo $day_link . $month_link . $year_link;
    elseif ( is_month() ):
      echo $month_link . $year_link;
    elseif ( is_year() ):
      echo $year_link;
    endif;
  }
}


if ( !function_exists( 'madeleine_nested_date' ) ) {
  function madeleine_nested_date() {
    global $wpdb;
    $y = get_query_var( 'year' );
    $m = get_query_var( 'monthnum' );
    $d = get_query_var( 'day' );
    // if ( is_year() ):
    //   $type = 'year';
    // elseif ( is_month() ):
    //   $y = substr( $date, 0, 4);
    //   $m = abs( substr( $date, 4, 2) );
    //   $type = 'month';
    // elseif ( is_day() ):
    //   $y = substr( $date, 0, 4);
    //   $m = abs( substr( $date, 4, 2) );
    //   $d = abs( substr( $date, 6, 2) );
    //   $type = 'day';
    // endif;
    $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC");
    $years_list = '<li class="select">' . __( 'Select year', 'madeleine' ) . '</li>';
    echo '<div id="date-archive" data-year="' . $y . '" data-month="' . $m . '" data-day="' . $d . '">';
    foreach( $years as $year ):
      $years_list .= '<li class="year" data-value="' . $year . '"><a href="'. get_year_link( $year ). '">' . $year . '</a></li>';
      $months_list = '<li class="select">' . __( 'Select month', 'madeleine' ) . '</li>';
      $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND YEAR(post_date) = '" . $year . "' ORDER BY post_date DESC");
      foreach( $months as $month ):
        $months_list .= '<li class="month" data-value="' . $month . '""><a href="' . get_month_link( $year, $month ) . '">' . date( 'F', mktime( 0, 0, 0, $month, 1, $year ) ) . '</a></li>';
        echo '<ul class="days" data-year="' . $year . '" data-month="' . $month . '">';
        $days = $wpdb->get_col("SELECT DISTINCT DAY(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND MONTH(post_date) = '" . $month . "' AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
        echo '<li class="select">' . __( 'Select day', 'madeleine' ) . '</li>';
        foreach( $days as $day ):
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
}


// 04 Post


if ( !function_exists( 'madeleine_entry_excerpt_more' ) ) {
  function madeleine_entry_excerpt_more( $text ) {
    return '&#8230; <a href="'. get_permalink() . '">&rarr;</a>';
  }
}
add_filter( 'excerpt_more', 'madeleine_entry_excerpt_more' );


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


if ( !function_exists( 'madeleine_entry_post_class' ) ) {
  function madeleine_entry_post_class( $classes ) {
    $top_category_ID = madeleine_top_category();
    $top_category = get_category( $top_category_ID );
    $classes[] = 'category-' . $top_category->category_nicename;
    return $classes;
  }
}
add_filter( 'post_class', 'madeleine_entry_post_class' );


if ( !function_exists( 'madeleine_entry_thumbnail' ) ) {
  function madeleine_entry_thumbnail( $size = 'thumbnail' ) {
    if ( has_post_thumbnail() )
      echo '<a href="' . get_permalink() . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
  }
}


if ( !function_exists( 'madeleine_entry_caption' ) ) {
  function madeleine_entry_caption( $val, $attr, $content = null ) {
    extract(shortcode_atts(array(
      'id'      => '',
      'align'   => 'alignnone',
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
}
add_filter( 'img_caption_shortcode', 'madeleine_entry_caption', 10, 3 );


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
            <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'madeleine' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
          </div>
        </div>
      </article>
    <?php
        break;
    endswitch;
  }
}


if ( !function_exists( 'madeleine_entry_info' ) ) {
  function madeleine_entry_info() {
    $archive_year  = get_the_time('Y'); 
    $archive_month = get_the_time('m'); 
    $archive_day   = get_the_time('d'); 
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


if ( !function_exists( 'madeleine_entry_rating' ) ) {
  function madeleine_entry_rating( $id, $echo = true ) {
    $rating = get_post_meta( $id, '_madeleine_review_rating', true );
    $div = '<div class="entry-rating rating-' . floor( $rating ) . '">' . $rating . '</div>';
    if ( $echo )
      echo $div;
    else
      return $div;
  }
}



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


if ( !function_exists( 'madeleine_entry_verdict' ) ) {
  function madeleine_entry_verdict( $id ) {
    $good = get_post_meta( $id, '_madeleine_review_good', true );
    $bad = get_post_meta( $id, '_madeleine_review_bad', true );
    $lists = array( 'good' => [$good, __( 'Good', 'madeleine' )], 'bad' => [$bad, __( 'Bad', 'madeleine' )] );
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
    echo '</div>';
  }
}


if ( !function_exists( 'madeleine_entry_images' ) ) {
  function madeleine_entry_images( $html ) {
     $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
     return $html;
  }
}
add_filter( 'post_thumbnail_html', 'madeleine_entry_images', 10 );
add_filter( 'image_send_to_editor', 'madeleine_entry_images', 10 );
add_filter( 'wp_get_attachment_link', 'madeleine_entry_images', 10 );


if ( !function_exists( 'madeleine_entry_title' ) ) {
  function madeleine_entry_title( $title, $id ) {
    if ( !is_admin() ):
      $format = get_post_format( $id );
      if ( $format == 'link' ):
        $link_url =  get_post_meta( get_the_ID(), '_madeleine_link_url', true );
        return '<a href="' . $link_url . '">' . $title . '</a> &rarr;';
      elseif ( $format == 'quote' ):
        return '&#8220; ' . $title . ' &#8221;';
      endif;
    endif;
    return $title;
  }
}
add_filter( 'the_title', 'madeleine_entry_title', 10, 2 );


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


// 05 Reviews


if ( !function_exists( 'madeleine_register_reviews' ) ) {
  function madeleine_register_reviews() {
    register_post_type( 'review', array(
      'label' => __( 'Reviews', 'madeleine' ),
      'labels' => array(
        'name' => __( 'Reviews', 'madeleine' ),
        'singular_name' => __( 'Review', 'madeleine' ),
        'all_items' => __( 'All Reviews', 'madeleine' ),
        'add_new_item' => __( 'Add New Review', 'madeleine' ),
        'edit_item' => __( 'Edit Review', 'madeleine' ),
        'new_item' => __( 'New Review', 'madeleine' ),
        'view_item' => __( 'View Review', 'madeleine' ),
        'search_items' => __( 'Search Reviews', 'madeleine' ),
        'not_found' => __( 'No reviews found', 'madeleine' ),
        'not_found_in_trash' => __( 'No reviews found in Trash', 'madeleine' )
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
        'name' => __( 'Products', 'madeleine' ),
        'singular_name' => __( 'Product', 'madeleine' ),
        'all_items' => __( 'All Products', 'madeleine' ),
        'edit_item' => __( 'Edit Product', 'madeleine' ),
        'view_item' => __( 'View Product', 'madeleine' ),
        'update_item' => __( 'Update Product', 'madeleine' ),
        'add_new_item' => __( 'Add New Product', 'madeleine' ),
        'new_item_name' => __( 'New Product', 'madeleine' ),
        'search_items' => __( 'Search Products', 'madeleine' ),
        'popular_items' => __( 'Popular Products', 'madeleine' ),
      ),
      'hierarchical' => true,
      'sort' => true
    ));
    register_taxonomy( 'brand', null, array(
      'label' => __( 'Brands', 'madeleine' ),
      'labels' => array(
        'name' => __( 'Brands', 'madeleine' ),
        'singular_name' => __( 'Brand', 'madeleine' ),
        'all_items' => __( 'All Brands', 'madeleine' ),
        'edit_item' => __( 'Edit Brand', 'madeleine' ),
        'view_item' => __( 'View Brand', 'madeleine' ),
        'update_item' => __( 'Update Brand', 'madeleine' ),
        'add_new_item' => __( 'Add New Brand', 'madeleine' ),
        'new_item_name' => __( 'New Brand', 'madeleine' ),
        'search_items' => __( 'Search Brands', 'madeleine' ),
        'popular_items' => __( 'Popular Brands', 'madeleine' ),
      ),
      'hierarchical' => true,
      'sort' => true
    ));
    register_taxonomy_for_object_type( 'product', 'review' );
    register_taxonomy_for_object_type( 'brand', 'review' );
  }
}
add_action( 'init', 'madeleine_register_reviews' );


if ( !function_exists( 'madeleine_products_list' ) ) {
  function madeleine_products_list() {
    $nav = wp_list_categories('depth=1 &hide_empty=0&orderby=ID&title_li=&taxonomy=product');
    echo $nav;
  }
}


if ( !function_exists( 'madeleine_reviews_tabs' ) ) {
  function madeleine_reviews_tabs() {
    $products = get_categories('hide_empty=0&orderby=ID&taxonomy=product');
    $tabs = wp_list_categories('depth=1&echo=0&hide_empty=0&orderby=ID&title_li=&taxonomy=product');
    foreach( $products as $product ):
      $find = 'cat-item-' . $product->term_id . '"';
      $replace = 'cat-item-' . $product->term_id . '" data-id="' . $product->term_id . '"';
      $tabs = str_replace( $find, $replace, $tabs );
      $tabs = str_replace( 'posts', __( 'reviews', 'madeleine' ), $tabs );
    endforeach;
    echo $tabs;
  }
}


if ( !function_exists( 'madeleine_reviews_grid' ) ) {
  function madeleine_reviews_grid( $tax_ID = 'all' ) {
    $args = array(
      'post_type' => 'review',
      'posts_per_page' => 6
    );
    if ( $tax_ID != 'all' ):
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'product',
          'terms' => $tax_ID,
          'field' => 'term_id',
        )
      );
    endif;
    $query = new WP_Query( $args );
    $grid = '';
    while ( $query->have_posts() ) {
      $query->the_post();
      $grid .= '<div class="review">';
      $grid .= '<a href="' . get_permalink() . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, 'tall' ) . '</a>';
      $grid .= '<div class="review-text">';
      $grid .= '<h2 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
      $grid .= '<p class="entry-summary">' . get_the_excerpt() . '</p>';
      $grid .= '</div>';
      $grid .= madeleine_entry_rating( get_the_ID(), false );
      $grid .= '</div>';
    }
    $grid .= '<div style="clear: left;"></div>';
    wp_reset_postdata();
    return $grid;
  }
}


if ( !function_exists( 'madeleine_reviews_breadcrumb' ) ) {
  function madeleine_reviews_breadcrumb() {
    $args = array(
      'depth' => 1,
      'hide_empty' => 0,
      'orderby' => 'ID',
      'title_li' => '',
      'taxonomy' => 'product'
    );
    echo '<div id="category">';
    echo '<div class="wrap">';
    echo '<strong>';
    echo '<a href="' . get_post_type_archive_link( 'review' ) . '">' . __( 'Reviews', 'madeleine' ) . '</a>';
    echo '</strong>';
    echo '<ul>';
    wp_list_categories( $args );
    echo '</ul>';
    echo '</div>';
    echo '</div>';
  }
}


if ( !function_exists( 'madeleine_reviews_menu' ) ) {
  function madeleine_reviews_menu() {
    $reviews_count = wp_count_posts( 'review' );
    $product_args = array(
      'depth' => 1,
      'echo' => 0,
      'hide_empty' => 0,
      'orderby' => 'ID',
      'show_count' => 1,
      'title_li' => '',
      'taxonomy' => 'product'
    );
    $brand_args = $product_args;
    $brand_args['taxonomy'] = 'brand';
    $menu = '<div id="menu">';
    $menu .= '<p class="section"><a href="' . get_post_type_archive_link( 'review' ) . '">' . __( 'All reviews', 'madeleine' ) . '</a></p>';
    $menu .= '<p class="section">' . __( 'Products', 'madeleine' ) . '</p>';
    $menu .= '<ul id="products">' . madeleine_taxonomy_list( 'product' ) . '</ul>';
    $menu .= '<p class="section">' . __( 'Brands', 'madeleine' ) . '</p>';
    $menu .= '<ul id="brands">' . madeleine_taxonomy_list( 'brand' ) . '</ul>';
    $menu .= '<p class="section">' . __( 'Rating', 'madeleine' ) . '</p>';
    $menu .= '<p id="rating-value" class="slider-value"></p>';
    $menu .= '<div id="rating"></div>';
    $menu .= '<p class="section">' . __( 'Price', 'madeleine' ) . '</p>';
    $menu .= '<p id="price-value" class="slider-value"></p>';
    $menu .= '<div id="price"></div>';
    $menu .= '<p id="reviews-filter"><button class="button"><span>' . __( 'Apply filters', 'madeleine' ) . '</span></button></p>';
    $menu .= '</div>';
    $menu = str_replace( 'posts', __( 'reviews', 'madeleine' ), $menu );
    $menu = str_replace( '(', '<span>', $menu );
    $menu = str_replace( ')', '</span>', $menu );
    echo $menu;
  }
}


// 06 Ajax


if ( !function_exists( 'madeleine_ajax_request' ) ) {
  function madeleine_ajax_request() {
    switch ( $_REQUEST['fn'] ) {
      case 'madeleine_reviews_tabs':
        $output = madeleine_reviews_grid( $_REQUEST['id'] );
      break;
      default:
        $output = __( 'No function specified, check your jQuery.ajax() call.', 'madeleine' );
      break;
    } 
    echo $output;
    die;
  }
}
add_action( 'wp_ajax_nopriv_madeleine_ajax', 'madeleine_ajax_request' );
add_action( 'wp_ajax_madeleine_ajax', 'madeleine_ajax_request' );


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
require_once( $template_dir .'/includes/init.php ');
require_once( $template_dir .'/settings/init.php' );


?>