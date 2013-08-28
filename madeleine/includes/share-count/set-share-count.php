<?php

function madeleine_share_count() {
  $url = 'http://uscodebeta.house.gov/download/download.shtml';
  $url = 'http://arstechnica.com/gadgets/2013/08/review-lego-mindstorms-ev3-means-giant-robots-powerful-computers/';
  $url = 'http://www.theverge.com/2013/8/7/4596646/behind-the-art-of-elysium';
  $url = 'http://www.wired.com/underwire/2013/08/kevin-feige-marvel-dc-movies/';

  $facebook = madeleine_facebook_share_count( $url );
  $twitter = madeleine_twitter_share_count( $url );
  $google = madeleine_google_share_count( $url );
  $pinterest = madeleine_pinterest_share_count( $url );

  $shares = array(
    'facebook'=> isset( $facebook) ? $facebook : null,
    'twitter'=> isset( $twitter) ? $twitter : null,
    'google'=> isset( $google) ? $google : null,
    'pinterest'=> isset( $pinterest) ? $pinterest : null
  );

  return $shares;
}


function madeleine_save_share_count( $post_id ) {
  $shares = madeleine_share_count();
  $total = array_sum( $shares );
  update_post_meta( $post_id, '_madeleine_share_counts', $shares );
  update_post_meta( $post_id, '_madeleine_share_total', $total );
}
// add_action( 'save_post', 'madeleine_save_share_count' );


function madeleine_schedule_share_count( $post_id ) {
  $schedule = wp_get_schedule( 'madeleine_share_count_event', array( '$post_id' => $post_id ) );
  $post = get_post( $post_id );
  if ( $schedule == false && $post->post_status == 'publish' )
    wp_schedule_event( current_time ( 'timestamp' ), 'daily', 'madeleine_share_count_event', array( '$post_id' => $post_id ) );
}
// add_action( 'save_post', 'madeleine_schedule_share_count' );
// add_action( 'madeleine_share_count_event', 'madeleine_save_share_count' );


function madeleine_delete_share_count( $post_id ) {
  wp_clear_scheduled_hook( 'madeleine_share_count_event', array( '$post_id' => $post_id ) );
}
// add_action( 'delete_post', 'madeleine_delete_share_count' );

?>