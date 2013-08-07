function madeleine_video_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'video_youtube_nonce' ); ?>
  <p>
    <label for="video-youtube">YouTube Video URL</label>
    <input type="text" name="video-youtube" id="video-youtube" value="<?php echo esc_attr( get_post_meta( $object->ID, 'video_youtube', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php wp_nonce_field( basename( __FILE__ ), 'video_vimeo_nonce' ); ?>
  <p>
    <label for="video-vimeo">Vimeo Video URL</label>
    <input type="text" name="video-vimeo" id="video-vimeo" value="<?php echo esc_attr( get_post_meta( $object->ID, 'video_vimeo', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php wp_nonce_field( basename( __FILE__ ), 'video_dailymotion_nonce' ); ?>
  <p>
    <label for="video-dailymotion">Dailymotion Video URL</label>
    <input type="text" name="video-dailymotion" id="video-dailymotion" value="<?php echo esc_attr( get_post_meta( $object->ID, 'video_dailymotion', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php
}


function madeleine_link_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'link_url_nonce' ); ?>
  <p>
    <label for="link-url">URL</label>
    <input type="text" name="link-url" id="link-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'link_url', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php
}


function madeleine_quote_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'quote_author_nonce' ); ?>
  <p>
    <label for="quote-author">Author or source</label>
    <input type="text" name="quote-author" id="quote-author" value="<?php echo esc_attr( get_post_meta( $object->ID, 'quote_author', true ) ); ?>" size="30" class="regular-text">
  </p>
  <?php
}


function madeleine_add_meta_boxes() {
  add_meta_box(
    'video',
    esc_html( 'Video' ),
    'madeleine_video_meta_box',
    'post',
    'normal',
    'high'
  );
  add_meta_box(
    'link',
    esc_html( 'Link' ),
    'madeleine_link_meta_box',
    'post',
    'normal',
    'high'
  );
  add_meta_box(
    'quote',
    esc_html( 'Quote' ),
    'madeleine_quote_meta_box',
    'post',
    'normal',
    'high'
  );
}


// Save the meta box's post metadata
function madeleine_save_meta( $post_id, $post ) {
  $metas = array( 'video_youtube', 'video_vimeo', 'video_dailymotion', 'link_url', 'quote_author' );
  foreach ( $metas as $meta ):
    $nonce = $meta . '_nonce';
    if ( !isset( $_POST[$nonce] ) || !wp_verify_nonce( $_POST[$nonce], basename( __FILE__ ) ) )
      return $post_id;
    $post_type = get_post_type_object( $post->post_type );
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
      return $post_id;
    $posted = str_replace( '_', '-', $meta );
    $new_meta_value = ( isset( $_POST[$posted] ) ? $_POST[$posted] : '' );
    $key = $meta;
    $meta_value = get_post_meta( $post_id, $key, true );
    if ( $new_meta_value && '' == $meta_value )
      add_post_meta( $post_id, $key, $new_meta_value, true );
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
      update_post_meta( $post_id, $key, $new_meta_value );
    elseif ( '' == $new_meta_value && $meta_value )
      delete_post_meta( $post_id, $key, $meta_value );
  endforeach;
}


function madeleine_setup_meta_boxes() {
  add_action( 'add_meta_boxes', 'madeleine_add_meta_boxes' );
  add_action( 'save_post', 'madeleine_save_meta', 10, 2 );
}
add_action( 'load-post.php', 'madeleine_setup_meta_boxes' );
add_action( 'load-post-new.php', 'madeleine_setup_meta_boxes' );


function madeleine_hide_editor() {
  if( isset( $_GET['post'] ) ):
    $post_id = $_GET['post'];
    $format = get_post_format( $post_id );
    if ( $format == 'link' || $format == 'quote' )
      remove_post_type_support( 'post', 'editor' );
  endif;
}
// add_action( 'admin_init', 'madeleine_hide_editor' );