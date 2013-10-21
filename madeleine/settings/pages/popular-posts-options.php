<?php

if ( !function_exists( 'madeleine_default_options_popular_posts' ) ) {
	function madeleine_default_options_popular_posts() {
		$defaults = array(
			'status' => 0
		);
	return apply_filters( 'madeleine_default_options_popular_posts', $defaults );
	}
}


if ( !function_exists( 'madeleine_initialize_popular_posts_options' ) ) {
	function madeleine_initialize_popular_posts_options() {
		if( false == get_option( 'madeleine_options_popular_posts' ) ):
			add_option( 'madeleine_options_popular_posts', apply_filters( 'madeleine_default_options_popular_posts', madeleine_default_options_popular_posts() ) );
		endif;

		add_settings_section(
			'popular_posts_section',
			__( 'Description', 'madeleine' ),
			'madeleine_popular_posts_section_callback',
			'madeleine_popular_posts_options_page'
		);
		
		add_settings_field( 
			'status',
			__( 'Status', 'madeleine' ),
			'madeleine_field_callback',
			'madeleine_popular_posts_options_page',
			'popular_posts_section',
			array(
				'description' => 'The Popular Posts feature.',
				'type' => 'radio',
				'option' => 'madeleine_options_popular_posts',
				'id' => 'status',
				'labels' => array(
					'Enabled',
					'Disabled'
				)
			)
		);
		
		add_settings_field( 
			'reset',
			__( 'Reset', 'madeleine' ),
			'madeleine_popular_posts_reset_callback',
			'madeleine_popular_posts_options_page',
			'popular_posts_section'
		);
		
		register_setting(
			'madeleine_popular_posts_options_group',
			'madeleine_options_popular_posts',
			'madeleine_popular_posts_options_callback'
		);	
	}
}
add_action( 'admin_init', 'madeleine_initialize_popular_posts_options' );


if ( !function_exists( 'madeleine_popular_posts_section_callback' ) ) {
	function madeleine_popular_posts_section_callback() {
		echo '<p>The Popular Posts plugin is a feature built in the Madeleine Theme. It retrieves the <strong>share count</strong> from the following sites:</p>';
		echo '<ul>';
		$share_sites = array('Twitter', 'Facebook', 'Google +', 'Pinterest', 'Reddit');
		foreach( $share_sites as $share_site ):
			echo '<li>' . $share_site . '</li>';
		endforeach;
		echo '</ul>';
		echo '<p>Whenever you create a new (standard) post, the plugin will set up a <strong>scheduled event</strong> to retrieve the post\'s share count <strong>once per day</strong>. It will then sum up all these individual share counts into a <strong>total</strong> share count.</p>';
		echo '<p>This total share count is used in the Popular Posts widget (available in the <a href="' . get_admin_url() . 'widgets.php">Widgets panel</a>). This widget displays the <strong>most shared posts in the last 30 days</strong>.</p>';
		echo '<h3>Cron events</h3>';
		echo '<p>Because the Popular Posts plugin makes use of the <a href="http://codex.wordpress.org/Category:WP-Cron_Functions">WordPress Cron functions</a>, it will populate scheduled events for each post that you publish.<br>
		When you delete a post, the scheduled event for that post will be deleted as well.<br>
		To check out the list of the current scheduled events, use the <a href="http://wordpress.org/plugins/cron-view/">Cron View plugin</a>.</p>';
		echo '<h3>Status</h3>';
		echo '<p>By default, the Popular Posts plugin is disabled. Just select "Enabled" and save your changes to enable the plugin. From that moment on, it will schedule daily events to retrieve the share counts.</p>';
	}
}


if ( !function_exists( 'madeleine_popular_posts_status_callback' ) ) {
	function madeleine_popular_posts_status_callback( $args ) {
		$options = get_option( 'madeleine_options_popular_posts' );
		$key = 'status';
		$html = '<label><input type="radio" name="madeleine_options_popular_posts[' . $key . ']" value="1"' . checked( 1, $options[$key], false ) . '>&nbsp;';
		$html .= 'Enabled';
		$html .= '</label>';
		$html .= '<label><input type="radio" name="madeleine_options_popular_posts[' . $key . ']" value="0"' . checked( 0, $options[$key], false ) . '>&nbsp;';
		$html .= 'Disabled';
		$html .= '</label>';
		echo $html;
	}
}


if ( !function_exists( 'madeleine_popular_posts_reset_callback' ) ) {
	function madeleine_popular_posts_reset_callback() {
		$key = 'reset';
		$html = '<div class="madeleine-field"><label><input id="madeleine-unschedule" type="checkbox" name="madeleine_options_popular_posts[' . $key . ']" value="1">&nbsp;';
		$html .= 'Unschedule all events?';
		$html .= '</label>';
		$html .= '<p id="madeleine-unschedule-message" class="madeleine-warning">This action is non-reversible so please be sure to understand how it works.</p></div>';
		$html .= '<div class="madeleine-description">';
		$html .= '<p>By checking this box and saving your changes, the form will <strong>clear all scheduled events for every post</strong>.<br>It means that it won\'t retrieve any share count anymore. You will have to re-enable the plugin <strong>and</strong> save any post for which you want to enable the share count event.</p></div>';
		$html .= '<div style="clear: both;"></div>';
		echo $html;
	}
}


/**
* Unschedule all previously scheduled cron job for a hook.
* The $hook parameter is required, so that the events can be identified.
* @param string $hook Action hook, the execution of which will be unscheduled.
*/

if ( !function_exists( 'madeleine_unschedule_hook' ) ) {
	function madeleine_unschedule_hook( $hook ) { 
		$crons = _get_cron_array();
		foreach( $crons as $timestamp => $args ):
			unset( $crons[$timestamp][$hook] ); 
			if ( empty( $crons[$timestamp] ) )
				unset( $crons[$timestamp] ); 
		endforeach;
		_set_cron_array( $crons ); 
	}
}


if ( !function_exists( 'madeleine_unschedule_old_events' ) ) {
	function madeleine_unschedule_old_events() { 
		$crons = _get_cron_array();
		$scheduled_posts = array();
		foreach( $crons as $timestamp ):
			$event_timestamp = key( $crons );
			if ( array_key_exists( 'madeleine_share_count_event', $timestamp ) ):
				foreach( $timestamp['madeleine_share_count_event'] as $random ):
					$scheduled_posts[] = $random['args']['$post_id'];
				endforeach;
			endif;
		endforeach;
		if ( !empty( $scheduled_posts ) ):
			foreach( $scheduled_posts as $scheduled_post ):
				if ( get_post_time( 'U', false, $scheduled_post ) < strtotime( '-1 month' ) ):
					$next_timestamp = wp_next_scheduled( 'madeleine_share_count_event', array( '$post_id' => $scheduled_post ) );
					if ( $next_timestamp )
						wp_unschedule_event( $next_timestamp, 'madeleine_share_count_event', array( '$post_id' => $scheduled_post ) );
				endif;
			endforeach;
		endif;
	}
}


if ( !function_exists( 'madeleine_popular_posts_options_callback' ) ) {
	function madeleine_popular_posts_options_callback( $input ) {
		wp_schedule_event( current_time ( 'timestamp' ), 'daily', 'madeleine_unschedule_old_events' );
		if ( isset( $input['reset'] ) ):
			if ( $input['reset'] == 1 )
				madeleine_unschedule_hook( 'madeleine_share_count_event' );
			unset( $input['reset'] );
		endif;
		return $input;
	}
}


?>