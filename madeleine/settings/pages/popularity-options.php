<?php

if ( !function_exists( 'madeleine_default_options_popularity' ) ) {
  function madeleine_default_options_popularity() {
    $defaults = array(
      'popularity_status' => 0
    );
    return apply_filters( 'madeleine_default_options_popularity', $defaults );
  }
}


if ( !function_exists( 'madeleine_initialize_popularity_options' ) ) {
  function madeleine_initialize_popularity_options() {
    if( false == get_option( 'madeleine_options_popularity' ) )
      add_option( 'madeleine_options_popularity', apply_filters( 'madeleine_default_options_popularity', madeleine_default_options_popularity() ) );

    add_settings_section(
      'popularity_section',
      __( 'Description', 'madeleine' ),
      'madeleine_popularity_callback',
      'madeleine_popularity_options_page'
    );
    
    add_settings_field( 
      'popularity_status',
      __( 'Status', 'madeleine' ),
      'madeleine_popularity_status_callback',
      'madeleine_popularity_options_page',
      'popularity_section'
    );
    
    add_settings_field( 
      'popularity_reset',
      __( 'Reset', 'madeleine' ),
      'madeleine_popularity_reset_callback',
      'madeleine_popularity_options_page',
      'popularity_section'
    );
    
    register_setting(
      'madeleine_popularity_options_group',
      'madeleine_options_popularity',
      'madeleine_popularity_options_callback'
    );  
  }
}
add_action( 'admin_init', 'madeleine_initialize_popularity_options' );


if ( !function_exists( 'madeleine_popularity_callback' ) ) {
  function madeleine_popularity_callback() {
    echo '<p>The Popular Posts plugin is a feature built in the Madeleine Theme. It retrieves the <strong>share count</strong> from the following sites:</p>';
    echo '<ul>';
    $share_sites = ['Twitter', 'Facebook', 'Google +', 'Pinterest', 'Reddit'];
    foreach( $share_sites as $share_site ):
      echo '<li>' . $share_site . '</li>';
    endforeach;
    echo '</ul>';
    echo '<p>Whenever you create a new (standard) post, the plugin will set up a <strong>scheduled event</strong> to retrieve the post\'s share count <strong>once per day</strong>.<br>
    It will then sum up all these individual share counts into a <strong>total</strong> share count.</p>';
    echo '<p>This total share count is used in the Popular Posts widget (available in the <a href="' . get_admin_url() . 'widgets.php">Widgets panel</a>). This widget displays the <strong>most shared posts in the last 30 days</strong>.</p>';
    echo '<h3>Cron events</h3>';
    echo '<p>Because the Popular Posts plugin makes use of the <a href="http://codex.wordpress.org/Category:WP-Cron_Functions">WordPress Cron functions</a>, it will populate scheduled events for each post that you publish.<br>
    When you delete a post, the scheduled event for that post will be deleted as well.<br>
    To check out the list of the current scheduled events, use the <a href="http://wordpress.org/plugins/cron-view/">Cron View plugin</a>.</p>';
    echo '<h3>Status</h3>';
    echo '<p>By default, the Popular Posts plugin is disabled. Just select "Enabled" and save your changes to enable the plugin. From that moment on, it will schedule daily events to retrieve the share counts.</p>';
  }
}


if ( !function_exists( 'madeleine_popularity_status_callback' ) ) {
  function madeleine_popularity_status_callback( $args ) {
    $settings = get_option( 'madeleine_options_popularity' );
    $key = 'popularity_status';
    $html = '<label><input type="radio" name="madeleine_popularity_options[' . $key . ']" value="1"' . checked( 1, $settings[$key], false ) . '>&nbsp;';
    $html .= 'Enabled';
    $html .= '</label>';
    $html .= '<label><input type="radio" name="madeleine_popularity_options[' . $key . ']" value="0"' . checked( 0, $settings[$key], false ) . '>&nbsp;';
    $html .= 'Disabled';
    $html .= '</label>';
    echo $html;
  }
}


if ( !function_exists( 'madeleine_popularity_reset_callback' ) ) {
  function madeleine_popularity_reset_callback() {
    $key = 'popularity_reset';
    $html = '<label><input type="checkbox" name="madeleine_popularity_options[' . $key . ']" value="1">&nbsp;';
    $html .= 'Unschedule all events?';
    $html .= '</label>';
    $html .= '<br>By checking this box and saving your changes, the form will <strong>clear all scheduled events for every post</strong>.<br>It means that it won\'t retrieve any share count anymore. You will have to re-enable the plugin <strong>and</strong> save any post for which you want to enable the share count.';
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


if ( !function_exists( 'madeleine_popularity_options_callback' ) ) {
  function madeleine_popularity_options_callback( $input ) {
    if ( isset( $input['popularity_reset'] ) ):
      if ( $input['popularity_reset'] == 1 )
        madeleine_unschedule_hook( 'madeleine_share_count_event' );
      unset( $input['popularity_reset'] );
    endif;
    return $input;
  }
}


?>