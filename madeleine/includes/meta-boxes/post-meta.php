<?php


if ( !function_exists( 'madeleine_add_post_meta_boxes' ) ) {
  function madeleine_add_post_meta_boxes() {
    $meta_box = array(
      'id' => 'video',
      'title' => __( 'Video settings', 'madeleine' ),
      'description' => __( 'Choose a video to display. Just type the URL of a video and it will automatically fetch the correct embed code and the thumbnail.', 'madeleine' ),
      'page' => 'post',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => __( 'YouTube URL', 'madeleine' ),
          'help' => __( 'Paste a video URL like http://www.youtube.com/watch?v=fDUKt_XgfJ4', 'madeleine' ),
          'id' => '_madeleine_video_youtube_url',
          'type' => 'text',
          'default' => ''
        ),
        array(
          'name' => __( 'Vimeo URL', 'madeleine' ),
          'help' => __( 'Paste a video URL', 'madeleine' ),
          'id' => '_madeleine_video_vimeo_url',
          'type' => 'text',
          'default' => ''
        ),
        array(
          'name' => __( 'Dailymotion URL', 'madeleine' ),
          'help' => __( 'Paste a video URL', 'madeleine' ),
          'id' => '_madeleine_video_dailymotion_url',
          'type' => 'text',
          'default' => ''
        )
      )
    );
    madeleine_add_meta_box( $meta_box );

    $meta_box = array(
      'id' => 'link',
      'title' => __( 'Link settings', 'madeleine' ),
      'description' => __( 'Type a link to display.', 'madeleine' ),
      'page' => 'post',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => __( 'URL', 'madeleine' ),
          'help' => __( 'The URL of your link, like http://codex.wordpress.org/', 'madeleine' ),
          'id' => '_madeleine_link_url',
          'type' => 'text',
          'default' => ''
        )
      )
    );
    madeleine_add_meta_box( $meta_box );

    $meta_box = array(
      'id' => 'quote',
      'title' => __( 'Quote settings', 'madeleine' ),
      'description' => __( 'Type or paste a quote to display, and add the source or the author of the quote.', 'madeleine' ),
      'page' => 'post',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => __( 'Quote', 'madeleine' ),
          'help' => __( 'The content of your quote.', 'madeleine' ),
          'id' => '_madeleine_quote_content',
          'type' => 'textarea',
          'default' => ''
        ),
        array(
          'name' => __( 'Source', 'madeleine' ),
          'help' => __( 'The source or author of the quote.', 'madeleine' ),
          'id' => '_madeleine_quote_source',
          'type' => 'text',
          'default' => ''
        )
      )
    );
    madeleine_add_meta_box( $meta_box );
  }
}


?>