function gplus_shares( $url ){
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
  if ( $schedule == false && $post->post_status == 'publish' )
    wp_schedule_event( current_time ( 'timestamp' ), 'daily', 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
}
// add_action( 'save_post', 'madeleine_schedule_popularity' );
add_action( 'madeleine_popularity_event', 'madeleine_update_popularity' );


function madeleine_delete_popularity( $post_id ) {
  wp_clear_scheduled_hook( 'madeleine_popularity_event', array( '$post_id' => $post_id ) );
}
add_action( 'delete_post', 'madeleine_delete_popularity' );