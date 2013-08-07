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
  if ( $populars ):
    echo '<ul class="popular">';
    foreach ( $populars as $popular ):
      $id = $popular->post_id;
      $categories = get_the_category( $id );
      $category = get_category( madeleine_top_category( $categories[0] ) );
      echo '<li class="post category-' . $category->category_nicename . '">';
      echo '<em data-total="' . $popular->total . '"></em>';
      echo '<strong><span>' . $popular->total . '</span></strong> ';
      echo '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';
      echo '</li>';
    endforeach;
    echo '</ul>';
    echo '<div style="clear: left;"></div>';
  endif;
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