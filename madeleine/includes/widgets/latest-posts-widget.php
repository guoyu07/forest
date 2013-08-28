<?php

/*-----------------------------------------------------------------------------------
  Plugin Name: Madeleine Latest Posts Widget
  Plugin URI: http://haxokeno.com
  Description: A widget that displays your latest posts with a pagination
  Version: 1.0
  Author: Haxokeno
  Author URI: http://haxokeno.com
-----------------------------------------------------------------------------------*/


// Register the widget

if ( !function_exists( 'madeleine_register_latest_posts_widget' ) ) {
  function madeleine_register_latest_posts_widget() {
    register_widget( 'madeleine_latest_posts_widget' );
  }
}
add_action( 'widgets_init', 'madeleine_register_latest_posts_widget' );


// Create the widget

class madeleine_latest_posts_widget extends WP_Widget {

  // Set widget options

  function madeleine_latest_posts_widget() {
    $widget_ops = array(
      'classname' => 'madeleine-latest-posts-widget',
      'description' => __( 'A list of your latest posts, with a pagination', 'madeleine' )
    );
    $control_ops = array(
      'width' => 300,
      'height' => 350,
      'id_base' => 'madeleine-latest-posts-widget'
    );
    $this->WP_Widget( 'madeleine-latest-posts-widget', __( 'Madeleine Latest Posts', 'madeleine' ), $widget_ops, $control_ops );
  }

  // Display the widget

  function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', $instance['title'] );
    $total = $instance['total'];
    $division = $instance['division'];

    $standard_posts = madeleine_standard_posts(); // Get only standard format for the posts
    $args = array(
      'posts_per_page' => $total,
      'tax_query' => $standard_posts
    );
    $cat = get_query_var('cat'); // If we're in a category archive, only display the posts of that category
    if ( $cat != '' ):
      $category = get_category( $cat );
      $title .= ' in ' . $category->name;
      $args['cat'] = get_query_var('cat');
    endif;
    $query = new WP_Query( $args );
    if ( $query->have_posts() ):
      $post_counter = 0;
      echo $before_widget;
      echo '<div id="latest">';
      echo $before_title . $title . $after_title;
      echo '<ul>';
      while ( $query->have_posts() ) {
        $query->the_post();
        $categories = get_the_category( get_the_ID() );
        $category = get_category( madeleine_top_category( $categories[0] ) );
        $date = get_the_date( 'd/m' );
        if ( $date == date( 'd/m' ) )
          $date = get_the_date( 'H:i' );
        echo '<li class="post category-' . $category->category_nicename;
        if ( is_sticky() )
          echo ' sticky';
        echo '"><a href="' . get_permalink() . '">';
        echo '<time class="entry-date">' . $date . '</time>';
        echo $category->name;
        echo ' <span>' . get_the_title() . '</span>';
        echo '</a></li>';
        $post_counter++;
        if ( $post_counter % $division == 0 ) // Divide the posts in multiple lists
          echo '</ul><ul>';
      }
      echo '</ul>';
      $n = ceil( $query->post_count / $division );
      if ( $n > 1 ): // If there's more than 1 page to display, we add the pagination
        echo '<div id="latest-dots" class="dots">';
        echo str_repeat( '<span></span>', $n );
        echo '</div>';
      endif;
      echo '</div>';
      echo $after_widget;
    endif;
    wp_reset_postdata();
  }

  // Update the widget when we save it

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['total'] = $new_instance['total'];
    $instance['division'] = $new_instance['division'];
    return $instance;
  }

  // Display the form in the widgets panel

  function form( $instance ) {

    // Setup the default values for the widget
    $defaults = array(
      'title' => __( 'Latest posts', 'madeleine' ),
      'total' => 50,
      'division' => 10,
    );
    
    $instance = wp_parse_args( (array) $instance, $defaults );
    // Possible options for the select menus
    $total_values = [25, 50, 75, 100];
    $division_values = [5, 10, 15, 20, 25];
    
    ?>

    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'madeleine' ) ?></label>
      <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>">
    </p>
    
    <p>
      <?php _e( 'Display a total of', 'madeleine' ) ?>
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
    
    <p>
      <?php _e( 'Divide the lists every', 'madeleine' ) ?>
      <select id="<?php echo $this->get_field_id( 'division' ); ?>" name="<?php echo $this->get_field_name( 'division' ); ?>">
        <?php
        foreach ( $division_values as $value ):
          echo '<option value="' . $value . '"';
          if ( $value == $instance['division'] )
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