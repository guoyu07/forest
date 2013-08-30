<?php

function madeleine_category_custom_fields( $tag ) {
  wp_enqueue_script( 'jquery-color' );
  $category_meta = get_option( 'madeleine_category_meta' );
  $colors = ['d0574e', '4d9cd1', '96ca47', 'd1ae4d', '6acdb3', 'd087cb', 'd1d126'];
  ?>
  <tr>
      <th scope="row" valign="top"><label for="category-color"><?php _e( 'Color', 'madeleine' ); ?></label></th>
      <td>
          <input id="madeleine-category-color" name="madeleine_category_meta[<?php echo $tag->term_id ?>][color]" value="<?php if ( isset( $category_meta[ $tag->term_id ] ) ) echo $category_meta[ $tag->term_id ]['color']; ?>" type="text" size="40" data-default-color="#d0574e">
          <p class="description"><?php _e( 'Enter a color for this category or select one by clicking on one of the following colors:', 'madeleine' ); ?></p>
          <ul id="madeleine-color-choices">
            <?php foreach( $colors as $color ): ?>
              <li data-color="#<?php echo $color; ?>" style="background: #<?php echo $color; ?>"></li>
            <?php endforeach; ?>
          </ul>
      </td>
  </tr>
  <?php
}

function madeleine_save_category_custom_fields( $term_id ) {
  $category_meta = get_option( 'madeleine_category_meta' );
  if ( isset( $_POST['madeleine_category_meta'] ) ):
    if ( ! isset( $category_meta ) ):
      add_option( 'category_meta', $_POST['madeleine_category_meta'] );
    elseif ( $category_meta != $_POST['madeleine_category_meta'] ):
      $category_meta[$term_id][color] = $_POST['madeleine_category_meta'][$term_id][color];
      update_option( 'madeleine_category_meta', $category_meta );
    endif;
  endif;
}

add_action( 'edit_category_form_fields', 'madeleine_category_custom_fields' );
add_action( 'edit_category', 'madeleine_save_category_custom_fields' );


?>