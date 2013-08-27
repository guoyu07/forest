<?php

if ( !function_exists( 'madeleine_add_post_meta_boxes' ) ) {
  function madeleine_add_post_meta_boxes() {
    $meta_box = array(
      'id' => 'video',
      'title' => 'Video settings',
      'description' => 'Choose a video to display. Just type the URL of a video and it will automatically fetch the correct embed code and the thumbnail.',
      'page' => 'post',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => 'YouTube URL',
          'help' => 'Paste a video URL like http://www.youtube.com/watch?v=fDUKt_XgfJ4',
          'id' => '_madeleine_video_youtube_url',
          'type' => 'text',
          'default' => ''
        ),
        array(
          'name' => 'Vimeo URL',
          'help' => 'Paste a video URL',
          'id' => '_madeleine_video_vimeo_url',
          'type' => 'text',
          'default' => ''
        ),
        array(
          'name' => 'Dailymotion URL',
          'help' => 'Paste a video URL',
          'id' => '_madeleine_video_dailymotion_url',
          'type' => 'text',
          'default' => ''
        )
      )
    );
    madeleine_add_meta_box( $meta_box );

    $meta_box = array(
      'id' => 'link',
      'title' => 'Link settings',
      'description' => 'Type a link to display.',
      'page' => 'post',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => 'URL',
          'help' => 'The URL of your link, like http://codex.wordpress.org/',
          'id' => '_madeleine_link_url',
          'type' => 'text',
          'default' => ''
        )
      )
    );
    madeleine_add_meta_box( $meta_box );

    $meta_box = array(
      'id' => 'quote',
      'title' => 'Quote settings',
      'description' => 'Type or paste a quote to display, and add the source or the author of the quote.',
      'page' => 'post',
      'context' => 'normal',
      'priority' => 'high',
       'fields' => array(
        array(
          'name' => 'Quote',
          'help' => 'The content of your quote.',
          'id' => '_madeleine_quote_content',
          'type' => 'textarea',
          'default' => ''
        ),
        array(
          'name' => 'Source',
          'help' => 'The source or author of the quote.',
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