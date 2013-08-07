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
  if ( is_category() || is_single() ):
    $top_category_ID = madeleine_top_category();
    $top_category = get_category( $top_category_ID );
    $classes[] = 'category-' . $top_category->category_nicename;
  endif;
  return $classes;
}
add_filter( 'body_class', 'madeleine_body_class' );


function madeleine_nav() {
  $cats = get_categories('hide_empty=0&order=desc');
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


function page_redirect() {
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
    foreach ( $categories as $category ):
      $class .= ' focus-' . $category->category_nicename;
    endforeach;
    echo '<article class="' . $class . '" id="focus-' . $n . '">';
    if ( $n == 1 )
      madeleine_thumbnail( 'focus' );
    elseif ( $n == 5 )
      madeleine_thumbnail( 'tall' );
    else
      madeleine_thumbnail( 'wide' );
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