<?php

if ( !function_exists( 'madeleine_twitter_share_count' ) ) {
  function madeleine_twitter_share_count( $url ) {
    $api = 'http://urls.api.twitter.com/1/urls/count.json?url=' . $url;
    $json = wp_remote_get( $api );
    $data = json_decode( $json['body'], true );
    $share_count = $data['count'];
    return $share_count;
  }
}


if ( !function_exists( 'madeleine_facebook_share_count' ) ) {
  function madeleine_facebook_share_count( $url ) {
    $api = 'http://api.ak.facebook.com/restserver.php?v=1.0&method=links.getStats&urls=' . $url . '&format=json';
    $json = wp_remote_get( $api );
    $data = json_decode( $json['body'], true );
    $share_count = $data[0]['total_count'];
    return $share_count;
  }
}


if ( !function_exists( 'madeleine_google_share_count' ) ) {
  function madeleine_google_share_count( $url ) {
    $api = 'https://plusone.google.com/_/+1/fastbutton?url=' . $url;
    $html = wp_remote_get( $api, array( 'sslverify' => false ) );
    $dom = new DOMDocument;
    $dom->loadHTML( $html['body'] );
    $share_count = $dom->getElementById('aggregateCount')->nodeValue;
    return $share_count;
  }
}


if ( !function_exists( 'madeleine_pinterest_share_count' ) ) {
  function madeleine_pinterest_share_count( $url ) {
    $api = 'http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url;
    $json = wp_remote_get( $api );
    $data = json_decode( preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json['body'] ), true );
    $share_count = $data['count'];
    return $share_count;
  }
}


if ( !function_exists( 'madeleine_reddit_share_count' ) ) {
  function madeleine_reddit_share_count( $url ) {
    $api = 'http://www.reddit.com/api/info.json?url=' . $url;
    $json = wp_remote_get( $api );
    $data = json_decode( $json['body'], true );
    $share_count = $data['data']['children'][0]['data']['score'];
    return $share_count;
  }
}

?>