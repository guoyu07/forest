<?php

/*
01 Settings
02 Common functions
03 Backend
04 Sidebar widgets
05 Archives
06 Post
07 Popularity plugin
*/

// 01 Settings


add_theme_support( 'post-formats', array( 'image', 'video', 'link', 'quote', ) );
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


if ( function_exists('register_sidebar') )
  register_sidebar( $sidebar_arguments );


// 02 Common functions


function madeleine_get_redirect_target( $destination ) {
  $headers = get_headers( $destination, 1 );
  return $headers['Location'];
}


function madeleine_get_youtube_id( $url ) {
  if ( preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match) ):
    return $match[1];
  else:
    return null;
  endif;
}


function madeleine_get_vimeo_id( $url ) {
  if ( preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $url, $match) ):
    return $match[2];
  else:
    return null;
  endif;
}


function madeleine_get_dailymotion_id( $url ) {
  if ( preg_match('/^.+dailymotion.com\/((video|hub)\/([^_]+))?[^#]*(#video=([^_&]+))?/', $url, $match) ):
    return $match[3];
  else:
    return null;
  endif;
}


function madeleine_top_category( $cat_ID = null ) {
  if ( isset( $cat_ID ) ):
    $cat = $cat_ID;
  elseif ( is_category() ):
    $cat = get_query_var('cat');
  elseif ( is_attachment() ):
    $cat = 2;
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


function madeleine_body_class( $classes ) {
  if ( is_category() ):
    $top_category_ID = madeleine_top_category();
    $top_category = get_category( $top_category_ID );
    $classes[] = 'category-' . $top_category->category_nicename;
  endif;
  return $classes;
}
add_filter( 'body_class', 'madeleine_body_class' );


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


function madeleine_page_redirect() {
  if ( $_SERVER['REQUEST_URI'] == '/forest/images' ):
    require( TEMPLATEPATH . '/images.php' );
  elseif ( $_SERVER['REQUEST_URI'] == '/forest/videos' ):
    require( TEMPLATEPATH . '/videos.php' );
  elseif ( $_SERVER['REQUEST_URI'] == '/forest/links' ):
    require( TEMPLATEPATH . '/links.php' );
  elseif ( $_SERVER['REQUEST_URI'] == '/forest/quotes' ):
    require( TEMPLATEPATH . '/quotes.php' );
  endif;
}
// add_action( 'template_redirect', 'page_redirect' );


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


function madeleine_focus() {
  $posts_list = array( 1, 10, 16, 23, 57 );
  $args = array(
    'ignore_sticky_posts' => 1,
    'post__in' => $posts_list
  );
  $query = new WP_Query( $args );
  $n = 1;
  echo '<div id="focus">';
  while ( $query->have_posts() ) {
    $query->the_post();
    $categories = get_the_category();
    $category_links = '';
    $class = 'focus';
    foreach ( $categories as $category ):
      $class .= ' category-' . $category->category_nicename;
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
  return $posts_list;
}


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


// 03 Backend


function madeleine_entry_video_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'madeleine_nonce' ); ?>
  <p>
    <label for="video-youtube">YouTube Video URL</label>
    <input type="text" name="video-youtube" id="video-youtube" value="<?php echo esc_attr( get_post_meta( $object->ID, 'video_youtube', true ) ); ?>" size="30" class="regular-text">
  </p>
  <p>
    <label for="video-vimeo">Vimeo Video URL</label>
    <input type="text" name="video-vimeo" id="video-vimeo" value="<?php echo esc_attr( get_post_meta( $object->ID, 'video_vimeo', true ) ); ?>" size="30" class="regular-text">
  </p>
  <p>
    <label for="video-dailymotion">Dailymotion Video URL</label>
    <input type="text" name="video-dailymotion" id="video-dailymotion" value="<?php echo esc_attr( get_post_meta( $object->ID, 'video_dailymotion', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php
}


function madeleine_link_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'madeleine_nonce' ); ?>
  <p>
    <label for="link-url">URL</label>
    <input type="text" name="link-url" id="link-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'link_url', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php
}


function madeleine_quote_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'madeleine_nonce' ); ?>
  <p>
    <label for="quote-author">Author or source</label>
    <input type="text" name="quote-author" id="quote-author" value="<?php echo esc_attr( get_post_meta( $object->ID, 'quote_author', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php
}


function madeleine_review_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'madeleine_nonce' ); ?>
  <p>
    <label for="rating">Rating</label>
    <input type="text" name="rating" id="rating" value="<?php echo esc_attr( get_post_meta( $object->ID, 'rating', true ) ); ?>">
  </p>
  <p>
    <label for="good">Good</label><br>
    <textarea type="text" name="good" id="good" rows="1" cols="40" style="height: 8em; width: 98%;"><?php echo get_post_meta( $object->ID, 'good', true ); ?></textarea>
  </p>
  <p>
    <label for="bad">Bad</label><br>
    <textarea type="text" name="bad" id="bad" rows="1" cols="40" style="height: 8em; width: 98%;"><?php echo get_post_meta( $object->ID, 'bad', true ); ?></textarea>
  </p>
  <?php
}


function madeleine_add_meta_boxes() {
  add_meta_box(
    'video',
    esc_html( 'Video' ),
    'madeleine_entry_video_meta_box',
    'post',
    'normal',
    'high'
  );
  add_meta_box(
    'link',
    esc_html( 'Link' ),
    'madeleine_link_meta_box',
    'post',
    'normal',
    'high'
  );
  add_meta_box(
    'quote',
    esc_html( 'Quote' ),
    'madeleine_quote_meta_box',
    'post',
    'normal',
    'high'
  );
  add_meta_box(
    'review',
    esc_html( 'Review' ),
    'madeleine_review_meta_box',
    'review',
    'normal',
    'high'
  );
}


function madeleine_save_meta( $post_id, $post ) {
  $metas = array( 'video_dailymotion', 'video_vimeo', 'video_youtube', 'link_url', 'quote_author', 'rating', 'good', 'bad' );
  foreach ( $metas as $meta ):
    $nonce = 'madeleine_nonce';

    if ( !isset( $_POST[$nonce] ) || !wp_verify_nonce( $_POST[$nonce], basename( __FILE__ ) ) ):
      return $post_id;
    endif;

    $post_type = get_post_type_object( $post->post_type );

    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ):
      return $post_id;
    endif;

    $posted = str_replace( '_', '-', $meta );
    $new_meta_value = ( isset( $_POST[$posted] ) ? $_POST[$posted] : '' );
    $current_meta_value = get_post_meta( $post_id, $meta, true );

    // if ( $new_meta_value != '' ):
    //   if ( $meta == 'video_youtube' ):
    //     $youtube_id = madeleine_get_youtube_id( $new_meta_value );
    //     $youtube_image = 'http://img.youtube.com/vi/' . $youtube_id . '/0.jpg';
    //     madeleine_upload_video_thumbnail( $youtube_image, $youtube_id, $post_id, 'youtube' );
    //   elseif ( $meta == 'video_vimeo' ):
    //     $vimeo_id = madeleine_get_vimeo_id( $new_meta_value );
    //     $vimeo_json = file_get_contents( 'http://vimeo.com/api/v2/video/' . $vimeo_id . '.json' );
    //     $vimeo_data = json_decode( $vimeo_json, true );
    //     $vimeo_image = $vimeo_data[0]['thumbnail_large'];
    //     madeleine_upload_video_thumbnail( $vimeo_image, $vimeo_id, $post_id, 'vimeo' );
    //   elseif ( $meta == 'video_dailymotion' ):
    //     $dailymotion_id = madeleine_get_dailymotion_id( $new_meta_value );
    //     $dailymotion_image = madeleine_get_redirect_target( 'http://www.dailymotion.com/thumbnail/video/' . $dailymotion_id );
    //     madeleine_upload_video_thumbnail( $dailymotion_image, $dailymotion_id, $post_id, 'dailymotion' );
    //   endif;
    // endif;

    if ( $new_meta_value && $current_meta_value == '' )
      add_post_meta( $post_id, $meta, $new_meta_value, true );
    elseif ( $new_meta_value && $new_meta_value != $current_meta_value )
      update_post_meta( $post_id, $meta, $new_meta_value );
    elseif ( $new_meta_value == '' && $current_meta_value )
      delete_post_meta( $post_id, $meta, $current_meta_value );
  endforeach;
}


function madeleine_setup_meta_boxes() {
  add_action( 'add_meta_boxes', 'madeleine_add_meta_boxes' );
  add_action( 'save_post', 'madeleine_save_meta', 10, 2 );
}
add_action( 'load-post.php', 'madeleine_setup_meta_boxes' );
add_action( 'load-post-new.php', 'madeleine_setup_meta_boxes' );


function madeleine_upload_video_thumbnail( $image_url, $image_id, $post_id, $source ) {
  $upload_dir = wp_upload_dir();
  $image_data = file_get_contents( $image_url );
  $filename =  $source . '_' . $image_id . '_' . basename( $image_url );

  if( wp_mkdir_p( $upload_dir['path'] ) )
      $file = $upload_dir['path'] . '/' . $filename;
  else
      $file = $upload_dir['basedir'] . '/' . $filename;

  if ( !file_exists( $file ) ):
    file_put_contents( $file, $image_data );
    $wp_filetype = wp_check_filetype( $filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name( $filename ),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    set_post_thumbnail( $post_id, $attach_id );
  endif;
}


// 04 Sidebar widgets


function madeleine_latest_widget() {
  $args = array(
    'posts_per_page ' => -1
  );
  $query = new WP_Query( $args );
  if ( $query->have_posts() ):
    echo '<section class="widget latest">';
    echo '<h4 class="widget-title">Latest posts</h4>';
    echo '<ul class="latest">';
    while ( $query->have_posts() ) {
      $query->the_post();
      $categories = get_the_category( get_the_ID() );
      $category = get_category( madeleine_top_category( $categories[0] ) );
      echo '<li class="post category-' . $category->category_nicename . '">';
      echo '<time class="entry-date">' . get_the_date( 'd/m' ) . '</time>';
      echo '<h3 class="entry-title"><a href="' . get_permalink() . '" title="' . esc_attr( sprintf( 'Permalink to %s', the_title_attribute( 'echo=0' ) ) ) . '" rel="bookmark">' . get_the_title() . '</a></h3>';
      echo '</li>';
    }
    echo '</section>';
  endif;
  wp_reset_postdata();
}


function madeleine_on_the_radar_widget() {
  $args = array(
    'ignore_sticky_posts' => 1,
    'post__in' => get_option( 'sticky_posts' ),
    'posts_per_page ' => 2,
    'post_type' => 'post'
  );
  $cat = get_query_var('cat');
  if ( isset( $cat ) )
    $args['cat'] = get_query_var('cat');
  $query = new WP_Query( $args );
  if ( $query->have_posts() ):
    echo '<section class="widget radar">';
    echo '<h4 class="widget-title">On the radar</h4>';
    while ( $query->have_posts() ) {
      $query->the_post();
      $categories = get_the_category( get_the_ID() );
      $category = get_category( madeleine_top_category( $categories[0] ) );
      echo '<div class="post category-' . $category->category_nicename . '">';
      madeleine_entry_thumbnail( 'medium' );
      echo '<h3 class="entry-title">';
      echo '<a href="' . get_permalink() . '" title="' . esc_attr( sprintf( 'Permalink to %s', the_title_attribute( 'echo=0' ) ) ) . '" rel="bookmark">' . get_the_title() . '</a>';
      echo '</h3>';
      echo '</div>';
    }
    echo '</section>';
  endif;
  wp_reset_postdata();
}


function madeleine_popular_widget() {
  global $wpdb;
  $latest_ids = join(',', madeleine_latest_posts() ); 
  $populars = $wpdb->get_results( 
    "
    SELECT post_id, meta_key, meta_value
    FROM $wpdb->postmeta
    WHERE meta_key = 'share_total'
    ORDER BY CAST(meta_value AS UNSIGNED) DESC
    LIMIT 5
    "
  );
  if ( $populars ):
    echo '<section class="widget popular">';
    echo '<h4 class="widget-title">Popular this month</h4>';
    echo '<ul>';
    foreach ( $populars as $popular ):
      $id = $popular->post_id;
      $categories = get_the_category( $id );
      $category = get_category( madeleine_top_category( $categories[0] ) );
      echo '<li class="post category-' . $category->category_nicename . '">';
      echo '<em data-total="' . $popular->meta_value . '"></em>';
      echo '<strong><span>' . $popular->meta_value . '</span></strong> ';
      echo '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';
      echo '</li>';
    endforeach;
    echo '</ul>';
    echo '<div style="clear: left;"></div>';
    echo '</section>';
  endif;
}


function madeleine_format_widget( $format ) {
  $args = array(
    'post_type' => 'post',
    'tax_query' => array(
      array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => array( 'post-format-' . $format )
      )
    )
  );
  $cat = get_query_var('cat');
  if ( isset( $cat ) )
    $args['cat'] = get_query_var('cat');
  $query = new WP_Query( $args );
  if ( $query->have_posts() ):
    echo '<section class="widget ' . $format . 's">';
    echo '<h4 class="widget-title">';
    single_cat_title();
    echo ' ' . $format . 's</h4>';
    echo '<ul>';
    while ( $query->have_posts() ) {
      $query->the_post();
      $categories = get_the_category( get_the_ID() );
      $category = get_category( madeleine_top_category( $categories[0] ) );
      echo '<li class="post ' . $format . ' category-' . $category->category_nicename . '">';
      if ( $format == 'image' ):
        madeleine_entry_thumbnail( 'thumbnail' );
      elseif ( $format == 'link' ):
        echo '<a href="' . get_the_excerpt() . '">' . get_the_title() . ' <span>&rarr;</span></a>';
      elseif ( $format == 'quote' ):
        echo '<blockquote>&#8220; ' . get_the_title() . ' &#8221;</blockquote>';
        echo '<p>' . get_the_excerpt() . '</p>';
      endif;
      echo '</li>';
    }
    echo '</ul>';
    echo '<div style="clear: left;"></div>';
    echo '</section>';
  endif;
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
    $categories = get_the_category( get_the_ID() );
    $category = get_category( madeleine_top_category( $categories[0] ) );
    echo '<li class="post image category-' . $category->category_nicename . '">';
    madeleine_entry_thumbnail( 'thumbnail' );
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
  $cat = get_query_var('cat');
  if ( isset( $cat ) )
    $args['cat'] = get_query_var('cat');
  $query = new WP_Query( $args );
  echo '<ul class="links">';
  while ( $query->have_posts() ) {
    $query->the_post();
    $categories = get_the_category( get_the_ID() );
    $category = get_category( madeleine_top_category( $categories[0] ) );
    echo '<li class="post link category-' . $category->category_nicename . '"><a href="' . get_the_excerpt() . '">' . get_the_title() . ' <span>&rarr;</span></a></li>';
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
  $cat = get_query_var('cat');
  if ( isset( $cat ) )
    $args['cat'] = get_query_var('cat');
  $query = new WP_Query( $args );
  echo '<ul class="quotes">';
  while ( $query->have_posts() ) {
    $query->the_post();
    $categories = get_the_category( get_the_ID() );
    $category = get_category( madeleine_top_category( $categories[0] ) );
    echo '<li class="post quote category-' . $category->category_nicename . '">';
    echo '<blockquote>&#8220; ' . get_the_title() . ' &#8221;</blockquote>';
    echo '<p>' . get_the_excerpt() . '</p>';
    echo '</li>';
  }
  echo '</ul>';
  echo '<div style="clear: left;"></div>';
  wp_reset_postdata();
}


// 05 Archives


function madeleine_next_posts() {
  $standard_posts = madeleine_standard_posts();
  $post_ids = array(); 
  $offset = get_option( 'posts_per_page' );
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 10,
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


function madeleine_category_wheels( $already_posted ) {
  $cats = get_categories('hide_empty=0&orderby=ID&parent=0');
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


function madeleine_archive_settings( $query ) {
  $query->set( 'ignore_sticky_posts', 1 );
  $standard_posts = madeleine_standard_posts();
  if ( ( $query->is_home() || $query->is_category() ) && $query->is_main_query() )
    $query->set( 'tax_query', $standard_posts );
  if ( $query->is_tag() && $query->is_main_query() )
    $query->set( 'posts_per_page', 9 );
  if ( $query->is_tax() && $query->is_main_query() )
    $query->set( 'posts_per_page', 12 );
}
add_action( 'pre_get_posts', 'madeleine_archive_settings' );


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
  $years_list = '<li class="select">Select year</li>';
  echo '<div id="date-archive" data-year="' . $y . '" data-month="' . $m . '" data-day="' . $d . '">';
  foreach( $years as $year ):
    $years_list .= '<li class="year" data-value="' . $year . '"><a href="'. get_year_link( $year ). '">' . $year . '</a></li>';
    $months_list = '<li class="select">Select month</li>';
    $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND YEAR(post_date) = '" . $year . "' ORDER BY post_date DESC");
    foreach( $months as $month ):
      $months_list .= '<li class="month" data-value="' . $month . '""><a href="' . get_month_link( $year, $month ) . '">' . date( 'F', mktime( 0, 0, 0, $month, 1, $year ) ) . '</a></li>';
      echo '<ul class="days" data-year="' . $year . '" data-month="' . $month . '">';
      $days = $wpdb->get_col("SELECT DISTINCT DAY(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND MONTH(post_date) = '" . $month . "' AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
      echo '<li class="select">Select day</li>';
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


// 06 Post


function madeleine_entry_excerpt_more( $text ) {
  return '&#8230; <a href="'. get_permalink() . '">&rarr;</a>';
}
add_filter( 'excerpt_more', 'madeleine_entry_excerpt_more' );


function madeleine_entry_excerpt_length( $length ) {
  global $post;
  if ( has_post_thumbnail( $post->ID ) )
    return 25;
  else
    return 90;
}
add_filter( 'excerpt_length', 'madeleine_entry_excerpt_length' );


function madeleine_entry_post_class( $classes ) {
  $top_category_ID = madeleine_top_category();
  $top_category = get_category( $top_category_ID );
  $classes[] = 'category-' . $top_category->category_nicename;
  return $classes;
}
add_filter( 'post_class', 'madeleine_entry_post_class' );


function madeleine_entry_thumbnail( $size = 'thumbnail' ) {
  if ( has_post_thumbnail() )
    echo '<a href="' . get_permalink() . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
}


function madeleine_entry_caption( $val, $attr, $content = null ) {
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
add_filter( 'img_caption_shortcode', 'madeleine_entry_caption', 10, 3 );


function madeleine_entry_video() {
  global $post;
  $youtube = get_post_meta( $post->ID, 'video_youtube', true );
  $dailymotion = get_post_meta( $post->ID, 'video_dailymotion', true );
  $vimeo = get_post_meta( $post->ID, 'video_vimeo', true );
  if ( $youtube != '' ):
    $youtube_id = madeleine_get_youtube_id( $youtube );
    echo '<iframe width="640" height="480" src="//www.youtube.com/embed/' . $youtube_id . '" frameborder="0" allowfullscreen></iframe>';
  elseif ( $vimeo != '' ):
    $vimeo_id = madeleine_get_vimeo_id( $vimeo );
    echo '<iframe src="http://player.vimeo.com/video/' . $vimeo_id . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
  elseif ( $dailymotion != '' ):
    $dailymotion_id = madeleine_get_dailymotion_id( $dailymotion );
    echo '<iframe frameborder="0" width="640" height="360" src="http://www.dailymotion.com/embed/video/' . $dailymotion_id . '"></iframe>';
  endif;
}


function madeleine_entry_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ):
    case 'pingback' :
    case 'trackback' :
  ?>
  <li class="pingback">
    <p><?php echo 'Pingback:'; ?> <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', '<span class="comment-edit">', '</span>' ); ?></p>
  <?php
      break;
    default:
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


function madeleine_entry_info() {
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


function madeleine_entry_rating( $id ) {
  $rating = get_post_meta( $id, 'rating', true );
  echo '<div class="entry-rating rating-' . floor( $rating ) . '">' . $rating . '</div>';
}


function madeleine_entry_verdict( $id ) {
  $good = get_post_meta( $id, 'good', true );
  $bad = get_post_meta( $id, 'bad', true );
  $lists = array( 'good' => $good, 'bad' => $bad );
  echo '<div class="entry-value">';
  foreach( $lists as $key => $value ):
    echo '<div class="entry-value-' . $key . '">';
    echo '<h4 class="section">' . ucwords( $key ) . '</h4>';
    echo '<ul class="entry-value-list">';
    $items = explode( "\n", $value );
    foreach( $items as $item ):
      echo '<li>' . $item . '</li>';
    endforeach;
    echo '</ul>';
    echo '</div>';
  endforeach;
  echo '</div>';
}


// 07 Popularity Plugin


function madeleine_google_shares( $url ){
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ" );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p",
"params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},
"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
    $result = curl_exec ( $ch );
    curl_close ( $ch );
    return json_decode( $result, true );
}


function madeleine_share_count() {
  $url = 'http://uscodebeta.house.gov/download/download.shtml';
  $url = 'http://arstechnica.com/gadgets/2013/08/review-lego-mindstorms-ev3-means-giant-robots-powerful-computers/';
  $url = 'http://www.theverge.com/2013/8/7/4596646/behind-the-art-of-elysium';
  $url = 'http://www.wired.com/underwire/2013/08/kevin-feige-marvel-dc-movies/';
  $url = 'http://paulgraham.com/convince.html';

  $finfo = json_decode(file_get_contents('http://api.ak.facebook.com/restserver.php?v=1.0&method=links.getStats&urls='.$url.'&format=json'));
  $tinfo = json_decode(file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.$url));
  $pinfo = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1",file_get_contents('http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url='.$url)));
  $gplus = madeleine_google_shares($url);

  $shares = array(
    'facebook'=> isset($finfo[0]) ? $finfo[0]->total_count : NULL,
    'twitter'=> isset($tinfo->count) ? $tinfo->count : NULL,
    'google'=> isset($gplus[0]['result']) ? $gplus[0]['result']['metadata']['globalCounts']['count'] : NULL,
    'pinterest'=> isset($pinfo->count) ? $pinfo->count : NULL
  );

  return $shares;
}


function madeleine_save_popularity( $post_id ) {
  $shares = madeleine_share_count();
  $total = array_sum( $shares );
  update_post_meta( $post_id, 'share_counts', $shares );
  update_post_meta( $post_id, 'share_total', $total );
}
// add_action( 'save_post', 'madeleine_save_popularity' );


function madeleine_register_popularity_table() {
  global $wpdb;
  $wpdb->madeleine_popularity = "{$wpdb->prefix}madeleine_popularity";
}
// add_action( 'init', 'madeleine_register_popularity_table', 1 );


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
// add_action( 'after_switch_theme', 'madeleine_create_popularity_table' );


function madeleine_initiate_popularity() {
  global $post;
  $posts = get_posts('posts_per_page=-1');
  foreach( $posts as $post ):
    madeleine_insert_popularity( $post->ID );
  endforeach;
}


function madeleine_insert_popularity( $post_id ) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT * FROM $wpdb->madeleine_popularity WHERE post_id = $post_id");
  if ( $result == null ):
    $wpdb->insert( 
      $wpdb->madeleine_popularity, 
      array( 
        'post_id' => $post_id
      ), 
      array( '%d' ) 
    );
  endif;
}
// add_action( 'publish_post', 'madeleine_insert_popularity' );


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
// add_action( 'save_post', 'madeleine_update_popularity' );


function madeleine_schedule_popularity( $post_id ) {
  $schedule = wp_get_schedule( 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
  $post = get_post( $post_id );
  if ( $schedule == false && $post->post_status == 'publish' )
    wp_schedule_event( current_time ( 'timestamp' ), 'daily', 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
}
// add_action( 'save_post', 'madeleine_schedule_popularity' );
// add_action( 'madeleine_popularity_event', 'madeleine_update_popularity' );


function madeleine_delete_popularity( $post_id ) {
  wp_clear_scheduled_hook( 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
}
// add_action( 'delete_post', 'madeleine_delete_popularity' );


// 08 Reviews


function madeleine_register_reviews() {
  register_post_type( 'review', array(
    'label' => 'Reviews',
    'labels' => array(
      'name' => 'Reviews',
      'singular_name' => 'Review',
      'all_items' => 'All Reviews',
      'add_new_item' => 'Add New Review',
      'edit_item' => 'Edit Review',
      'new_item' => 'New Review',
      'view_item' => 'View Review',
      'search_items' => 'Search Reviews',
      'not_found' => 'No reviews found',
      'not_found_in_trash' => 'No reviews found in Trash'
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
    'label' => 'Products',
    'labels' => array(
      'name' => 'Products',
      'singular_name' => 'Product',
      'all_items' => 'All Products',
      'edit_item' => 'Edit Product',
      'view_item' => 'View Product',
      'update_item' => 'Update Product',
      'add_new_item' => 'Add New Product',
      'new_item_name' => 'New Product',
      'search_items' => 'Search Products',
      'popular_items' => 'Popular Products',
    ),
    'hierarchical' => true,
    'sort' => true
  ));
  register_taxonomy( 'brand', null, array(
    'label' => 'Brands',
    'labels' => array(
      'name' => 'Brands',
      'singular_name' => 'Brand',
      'all_items' => 'All Brands',
      'edit_item' => 'Edit Brand',
      'view_item' => 'View Brand',
      'update_item' => 'Update Brand',
      'add_new_item' => 'Add New Brand',
      'new_item_name' => 'New Brand',
      'search_items' => 'Search Brands',
      'popular_items' => 'Popular Brands',
    ),
    'hierarchical' => true,
    'sort' => true
  ));
  register_taxonomy_for_object_type( 'product', 'review' );
  register_taxonomy_for_object_type( 'brand', 'review' );
}
add_action( 'init', 'madeleine_register_reviews' );


function madeleine_products_list() {
  $nav = wp_list_categories('depth=1 &hide_empty=0&orderby=ID&title_li=&taxonomy=product');
  echo $nav;
}


function madeleine_reviews_grid() {
  $post_ids = array(); 
  $args = array(
    'post_type' => 'review',
    'posts_per_page' => 10
  );
  $query = new WP_Query( $args );
  echo '<div class="reviews-grid">';
  while ( $query->have_posts() ) {
    $query->the_post();
    echo '<div class="review">';
    madeleine_entry_thumbnail( 'tall' );
    echo '<div class="review-text">';
    echo '<h2 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
    echo '<p class="entry-summary">' . get_the_excerpt() . '</p>';
    echo '</div>';
    madeleine_entry_rating( get_the_ID() );
    echo '</div>';
  }
  echo '<div style="clear: left;"></div>';
  echo '</div>';
  wp_reset_postdata();
}


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
  echo '<a href="' . get_post_type_archive_link( 'review' ) . '">Reviews</a>';
  echo '</strong>';
  echo '<ul>';
  wp_list_categories( $args );
  echo '</ul>';
  echo '</div>';
  echo '</div>';
}


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
  $menu .= '<p class="section"><a href="' . get_post_type_archive_link( 'review' ) . '">All reviews</a></p>';
  $menu .= '<p class="section">Products</p>';
  $menu .= '<ul>' . wp_list_categories( $product_args ) . '</ul>';
  $menu .= '<p class="section">Brands</p>';
  $menu .= '<ul>';
  $menu .= '<ul>' . wp_list_categories( $brand_args ) . '</ul>';
  $menu .= '</ul>';
  $menu .= '</div>';
  $menu = str_replace( 'posts', 'reviews', $menu );
  $menu = str_replace( '(', '<span>', $menu );
  $menu = str_replace( ')', '</span>', $menu );
  echo $menu;
}