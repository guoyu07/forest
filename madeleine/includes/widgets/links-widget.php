<?php

/*-----------------------------------------------------------------------------------
  Plugin Name: Madeleine links Widget
  Plugin URI: http://haxokeno.com
  Description: A widget that displays a list of your latest links, with a pagination
  Version: 1.0
  Author: Haxokeno
  Author URI: http://haxokeno.com
-----------------------------------------------------------------------------------*/


// Register the widget

if ( !function_exists( 'madeleine_register_links_widget' ) ) {
  function madeleine_register_links_widget() {
    register_widget( 'madeleine_links_widget' );
  }
}
add_action( 'widgets_init', 'madeleine_register_links_widget' );


// Create the widget

class madeleine_links_widget extends WP_Widget {

  // Set widget options

  function madeleine_links_widget() {
    $widget_ops = array(
      'classname' => 'madeleine-links-widget',
      'description' => __( 'A list of your latest links, with a pagination', 'madeleine' )
    );
    $control_ops = array(
      'width' => 300,
      'height' => 350,
      'id_base' => 'madeleine_links_widget'
    );
    $this->WP_Widget( 'madeleine_links_widget', __( 'Madeleine Links', 'madeleine' ), $widget_ops, $control_ops );
  }

  // Display the widget

  function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', $instance['title'] );
    $total = $instance['total'];

    $args = array(
      'post_type' => 'post',
      'posts_per_page' => $total,
      'tax_query' => array(
        array(
          'taxonomy' => 'post_format',
          'field' => 'slug',
          'terms' => array( 'post-format-link' )
        )
      )
    );
    $title = 'links';
    $cat = get_query_var('cat'); // If we're in a category archive, only display the links of that category
    if ( $cat != '' ):
      $category = get_category( $cat );
      $title = $category->name . ' ' . $title;
      $args['cat'] = get_query_var('cat');
    endif;
    $query = new WP_Query( $args );
    if ( $query->have_posts() ):
      echo $before_widget;
      echo '<div id="links">';
      echo $before_title . $title . $after_title;
      echo '<ul>';
      while ( $query->have_posts() ) {
        $query->the_post();
        $categories = get_the_category( get_the_ID() );
        $category = get_category( madeleine_top_category( $categories[0] ) );
        echo '<li class="post format-link category-' . $category->category_nicename . '">';
        echo '<p class="entry-title"><a class="entry-permalink" href="' . get_permalink() . '">#</a>' . get_the_title() . '</p>';
        echo '</li>';
      }
      echo '</ul>';
      echo '<div style="clear: left;"></div>';
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
    return $instance;
  }

  // Display the form in the widgets panel

  function form( $instance ) {

    // Setup the default values for the widget
    $defaults = array(
      'title' => __( 'Links', 'madeleine' ),
      'total' => 6,
    );
    
    $instance = wp_parse_args( (array) $instance, $defaults );
    // Possible options for the select menu
    $total_values = [3, 5, 7];
    
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
      links
    </p>
      
    <?php
  }
}
?>