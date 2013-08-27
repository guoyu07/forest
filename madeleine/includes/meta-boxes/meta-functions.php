<?php

if ( !function_exists( 'madeleine_create_meta_box' ) ) {
  function madeleine_create_meta_box( $post, $meta_box ) {
    if ( !is_array( $meta_box ) )
      return false;
    
    if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' )
      echo '<p>'. $meta_box['description'] .'</p>';
      
    wp_nonce_field( basename(__FILE__), 'madeleine_meta_box_nonce' );

    echo '<table class="form-table madeleine-metabox-table">';
   
    foreach ( $meta_box['fields'] as $field ):
      $meta = get_post_meta( $post->ID, $field['id'], true );
      echo '<tr><th><label for="'. $field['id'] .'"><strong>'. $field['name'] .'</strong><span>'. $field['help'] .'</span></label></th>';
      switch ( $field['type'] ):
        case 'text':
          echo '<td><input type="text" name="madeleine_meta['. $field['id'] .']" id="'. $field['id'] .'" value="'. ( $meta ? $meta : $field['default'] ) .'"></td>';
          break;
        case 'textarea':
          echo '<td><textarea name="madeleine_meta['. $field['id'] .']" id="'. $field['id'] .'" rows="8" cols="40">'. ( $meta ? $meta : $field['default'] ) .'</textarea></td>';
          break;
        case 'select':
          echo'<td><select name="madeleine_meta['. $field['id'] .']" id="'. $field['id'] .'">';
          foreach( $field['options'] as $key => $option ):
            echo '<option value="' . $key . '"';
            if ( $meta ):
              if ( $meta == $key ):
                echo ' selected="selected"'; 
              endif;
            else:
              if ( $field['default'] == $key ):
                echo ' selected="selected"'; 
              endif;
            endif;
            echo '>' . $option .'</option>';
          endforeach;
          echo'</select></td>';
          break;
        case 'radio':
          echo '<td>';
          foreach( $field['options'] as $key => $option ):
            echo '<label class="radio-label"><input type="radio" name="madeleine_meta['. $field['id'] .']" value="'. $key .'" class="radio"';
            if ( $meta ):
              if ( $meta == $key ):
                echo ' checked="checked"'; 
              endif;
            else:
              if ( $field['default'] == $key ):
                echo ' checked="checked"';
              endif;
            endif;
            echo '>'. $option .'</label> ';
          endforeach;
          echo '</td>';
          break;
        case 'checkbox':
          echo '<td>';
          $val = '';
          if ( $meta ):
            if ( $meta == 'on' ):
              $val = ' checked="checked"';
            endif;
          else:
            if ( $field['default'] == 'on' ):
              $val = ' checked="checked"';
            endif;
          endif;
          echo '<input type="hidden" name="madeleine_meta['. $field['id'] .']" value="off">
          <input type="checkbox" id="'. $field['id'] .'" name="madeleine_meta['. $field['id'] .']" value="on"'. $val .'> ';
          echo '</td>';
          break;
      endswitch;
      echo '</tr>';
    endforeach;
    echo '</table>';
  }
}


if ( !function_exists( 'madeleine_add_meta_box' ) ) {
  function madeleine_add_meta_box( $meta_box ) {
    if ( !is_array($meta_box) )
      return false;
    $callback = create_function( '$post, $meta_box', 'madeleine_create_meta_box( $post, $meta_box["args"] );' );
    add_meta_box( 'madeleine-' . $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
  }
}


if ( !function_exists( 'madeleine_save_meta_boxes' ) ) {
  function madeleine_save_meta_boxes( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

    if ( !isset( $_POST['madeleine_meta']) || !isset($_POST['madeleine_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['madeleine_meta_box_nonce'], basename( __FILE__ ) ) )
      return;
    $post_type = get_post_type_object( $post->post_type );
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
      return;

    foreach ( $_POST['madeleine_meta'] as $key => $val ):
      $new_meta_value = ( isset( $val ) ? $val : '' );
      $current_meta_value = get_post_meta( $post_id, $key, true );

      if ( $new_meta_value != '' && $new_meta_value != $current_meta_value ): // If you add new value to the custom field
        if ( $key == '_madeleine_video_youtube_url' ):
          $youtube_id = madeleine_get_youtube_id( $new_meta_value );
          if ( $youtube_id != get_post_meta( $post_id, '_madeleine_video_youtube_id', true ) ):
            $youtube_image = 'http://img.youtube.com/vi/' . $youtube_id . '/0.jpg';
            madeleine_upload_video_thumbnail( $youtube_id, $youtube_image, $post_id, 'youtube' );
            update_post_meta( $post_id, '_madeleine_video_youtube_id', $youtube_id, true );
          endif;
        elseif ( $key == '_madeleine_video_vimeo_url' ):
          $vimeo_id = madeleine_get_vimeo_id( $new_meta_value );
          if ( $vimeo_id != get_post_meta( $post_id, '_madeleine_video_vimeo_id', true ) ):
            $vimeo_url = 'http://vimeo.com/api/v2/video/' . $vimeo_id . '.json';
            $vimeo_json = wp_remote_get( $vimeo_url );
            $vimeo_data = json_decode( $vimeo_json['body'], true );
            $vimeo_image = $vimeo_data[0]['thumbnail_large'];
            madeleine_upload_video_thumbnail( $vimeo_id, $vimeo_image, $post_id, 'vimeo' );
            update_post_meta( $post_id, '_madeleine_video_vimeo_id', $vimeo_id, true );
          endif;
        elseif ( $key == '_madeleine_video_dailymotion_url' ):
          $dailymotion_id = madeleine_get_dailymotion_id( $new_meta_value );
          if ( $dailymotion_id != get_post_meta( $post_id, '_madeleine_video_dailymotion_id', true ) ):
            $dailymotion_image = madeleine_get_redirect_target( 'http://www.dailymotion.com/thumbnail/video/' . $dailymotion_id );
            madeleine_upload_video_thumbnail( $dailymotion_id, $dailymotion_image, $post_id, 'dailymotion' );
            update_post_meta( $post_id, '_madeleine_video_dailymotion_id', $dailymotion_id, true );
          endif;
        endif;
        update_post_meta( $post_id, $key, $new_meta_value, true );
      elseif ( $new_meta_value == '' && $current_meta_value ): // If you delete the custom field
        if ( $key == '_madeleine_video_youtube_url' ):
          delete_post_meta( $post_id, '_madeleine_video_youtube_id' );
        elseif ( $key == '_madeleine_video_vimeo_url' ):
          delete_post_meta( $post_id, '_madeleine_video_vimeo_id' );
        elseif ( $key == '_madeleine_video_dailymotion_url' ):
          delete_post_meta( $post_id, '_madeleine_video_dailymotion_id' );
        endif;
        delete_post_meta( $post_id, $key, $current_meta_value );
      endif;
    endforeach;
  }
}


if ( !function_exists( 'madeleine_setup_meta_boxes' ) ) {
  function madeleine_setup_meta_boxes() {
    add_action( 'add_meta_boxes', 'madeleine_add_post_meta_boxes' );
    add_action( 'add_meta_boxes', 'madeleine_add_review_meta_boxes' );
    add_action( 'save_post', 'madeleine_save_meta_boxes', 10, 2 );
  }
}
add_action( 'load-post.php', 'madeleine_setup_meta_boxes' );
add_action( 'load-post-new.php', 'madeleine_setup_meta_boxes' );

?>