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


function madeleine_tags( $query ) {
  if ( $query->is_tag() && $query->is_main_query() )
    $query->set( 'posts_per_page', 9 );
}
add_action( 'pre_get_posts', 'madeleine_tags' );


function madeleine_type_archive( $query ) {
  if ( $query->is_tax() && $query->is_main_query() )
    $query->set( 'posts_per_page', 1 );
}
add_action( 'pre_get_posts', 'madeleine_type_archive' );


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
  $date = get_query_var('m');
  $m = '';
  $d = '';
  if ( is_year() ):
    $y  = substr( $date, 0, 4);
  elseif ( is_month() ):
    $y  = substr( $date, 0, 4);
    $m = abs( substr( $date, 4, 2) );
  elseif ( is_day() ):
    $y  = substr( $date, 0, 4);
    $m = abs( substr( $date, 4, 2) );
    $d   = abs( substr( $date, 6, 2) );
  endif;
  $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC");
  $years_list = '<li class="select">Select year</li>';
  echo '<div id="date-archive" data-year="' . $y . '" data-month="' . $m . '" data-day="' . $d . '">';
  foreach( $years as $year ):
    $years_list .= '<li class="year" data-value="' . $year . '"><a href="'. get_year_link( $year ). '">' . $year . '</a></li>';
    $months_list = '<li class="select">Select month</li>';
    $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
    foreach( $months as $month ):
      $months_list .= '<li class="month" data-value="' . $month . '""><a href="' . get_month_link( $year, $month ) . '">' . date( 'F', mktime( 0, 0, 0, $month, 1, $year ) ) . '</a></li>';
      echo '<ul class="days" data-year="' . $year . '" data-month="' . $month . '">';
      $days = $wpdb->get_col("SELECT DISTINCT DAY(post_date) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND MONTH(post_date) = '".$month."' AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
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