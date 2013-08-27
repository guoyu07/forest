<?php

/*-----------------------------------------------------------------------------------
  Plugin Name: Madeleine popular Posts Widget
  Plugin URI: http://haxokeno.com
  Description: A widget that displays your popular posts with a pagination
  Version: 1.0
  Author: Haxokeno
  Author URI: http://haxokeno.com
-----------------------------------------------------------------------------------*/


// Register the widget

if ( !function_exists( 'madeleine_register_popular_posts_widget' ) ) {
  function madeleine_register_popular_posts_widget() {
    register_widget( 'madeleine_popular_posts_widget' );
  }
}
add_action( 'widgets_init', 'madeleine_register_popular_posts_widget' );


// Create the widget

class madeleine_popular_posts_widget extends WP_Widget {

  // Set widget options

  function madeleine_popular_posts_widget() {
    $widget_ops = array(
      'classname' => 'madeleine-popular-posts-widget',
      'description' => __('A list of your popular posts, with a pagination', 'madeleine')
    );
    $control_ops = array(
      'width' => 300,
      'height' => 350,
      'id_base' => 'madeleine-popular-posts-widget'
    );
    $this->WP_Widget( 'madeleine-popular-posts-widget', __('Madeleine Popular Posts', 'madeleine'), $widget_ops, $control_ops );
  }

  // Display the widget

  function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', $instance['title'] );
    $total = $instance['total'];

    global $wpdb;
    $latest_ids = join(',', madeleine_latest_posts() ); 
    $populars = $wpdb->get_results( 
      "
      SELECT post_id, meta_key, meta_value
      FROM $wpdb->postmeta
      WHERE meta_key = '_madeleine_share_total'
      ORDER BY CAST(meta_value AS UNSIGNED) DESC
      LIMIT " . $total
    );
    if ( $populars ):
      echo $before_widget;
      echo '<div id="popular">';
      echo $before_title . $title . $after_title;
      echo '<ul>';
      foreach ( $populars as $popular ):
        $id = $popular->post_id;
        $categories = get_the_category( $id );
        $category = get_category( madeleine_top_category( $categories[0] ) );
        echo '<li class="post category-' . $category->category_nicename . '">';
        echo '<em data-total="' . $popular->meta_value . '"></em>';
        echo '<strong><span>' . $popular->meta_value . '</span></strong> ';
        echo '<a href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a>';
        echo '</li>';
      endforeach;
      echo '</ul>';
      echo '<div style="clear: left;"></div>';
      echo '</div>';
     echo $after_widget;
    endif;
  }

  // Update the widget when we save it

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['total'] = $new_instance['total'];
    return $instance;
  }

  // Display the form in the widgets panel

  function form( $instance ) {

    // Setup the default values for the widget
    $defaults = array(
      'title' => 'Popular posts',
      'total' => 5,
    );
    
    $instance = wp_parse_args( (array) $instance, $defaults );
    // Possible options for the select menus
    $total_values = [3, 5, 7];
    
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'madeleine') ?></label>
      <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>">
    </p>
    
    <p>
      <?php _e('Display a total of', 'madeleine') ?>
      <select id="<?php echo $this->get_field_id( 'total' ); ?>" name="<?php echo $this->get_field_name( 'total' ); ?>">
        <?php
        foreach ( $total_values as $value ):
          echo '<option value="' . $value . '"';
          if ( $value == $instance['total'] )
            echo ' selected="selected"';
          echo '">' . $value . '</option>';
        endforeach;
        ?>
      </select>
      posts
    </p>
      
    <?php
  }
}
?>