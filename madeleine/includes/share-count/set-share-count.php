<?php

if ( !function_exists( 'madeleine_get_share_count' ) ) {
	function madeleine_get_share_count( $url ) {
		$facebook = madeleine_facebook_share_count( $url );
		$twitter = madeleine_twitter_share_count( $url );
		$google = madeleine_google_share_count( $url );
		$pinterest = madeleine_pinterest_share_count( $url );
		$reddit = madeleine_reddit_share_count( $url );

		$shares = array(
			'facebook' => isset( $facebook ) ? $facebook : null,
			'twitter' => isset( $twitter ) ? $twitter : null,
			'google' => isset( $google ) ? $google : null,
			'pinterest' => isset( $pinterest ) ? $pinterest : null,
			'reddit' => isset( $reddit ) ? $reddit : null
		);

		return $shares;
	}
}


if ( !function_exists( 'madeleine_save_share_count' ) ) {
	function madeleine_save_share_count( $post_id = '' ) {
		global $post;
		if ( $post_id == '' )
			$post_id = $post->ID;
		$shares = madeleine_get_share_count( get_permalink( $post_id ) );
		$total = array_sum( $shares );
		update_post_meta( $post_id, '_madeleine_share_counts', $shares );
		update_post_meta( $post_id, '_madeleine_share_total', $total );
	}
}
// add_action( 'save_post', 'madeleine_save_share_count' );


if ( !function_exists( 'madeleine_schedule_share_count' ) ) {
	function madeleine_schedule_share_count( $post_id ) {
		$popularity_options = get_option( 'madeleine_options_popularity' );
		if ( isset( $popularity_options['popularity_status'] ) && $popularity_options['popularity_status'] == 1 ):
			$schedule = wp_get_schedule( 'madeleine_share_count_event', array( '$post_id' => $post_id ) );
			$post = get_post( $post_id );
			$format = get_post_format( $post_id );
			if ( $schedule == false && $post->post_status == 'publish' && $format == false )
				wp_schedule_event( current_time ( 'timestamp' ), 'daily', 'madeleine_share_count_event', array( '$post_id' => $post_id ) );
		endif;
	}
}
add_action( 'save_post', 'madeleine_schedule_share_count' );
add_action( 'madeleine_share_count_event', 'madeleine_save_share_count' );


if ( !function_exists( 'madeleine_delete_share_count_event' ) ) {
	function madeleine_delete_share_count_event( $post_id ) {
		wp_clear_scheduled_hook( 'madeleine_share_count_event', array( '$post_id' => $post_id ) );
	}
}
add_action( 'delete_post', 'madeleine_delete_share_count_event' );

?>