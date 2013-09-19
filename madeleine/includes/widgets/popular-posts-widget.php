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
			'description' => __( 'A list of your popular posts', 'madeleine' )
		);
		$control_ops = array(
			'width' => 300,
			'height' => 350,
			'id_base' => 'madeleine_popular_posts_widget'
		);
		$this->WP_Widget( 'madeleine_popular_posts_widget', 'Madeleine Popular Posts', $widget_ops, $control_ops );
	}

	// Display the widget

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$total = $instance['total'];

		$popularity_options = get_option( 'madeleine_options_popularity' );
		if ( isset( $popularity_options['popularity_status'] ) && $popularity_options['popularity_status'] == 1 ):
			$latest_ids = madeleine_latest_posts(); 
			$standard_posts = madeleine_standard_posts(); // Get only standard format for the posts
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => $total,
				// 'post__in' => $latest_ids, TESTDRIVE
				'meta_key' => '_madeleine_share_total',
				'orderby' => 'meta_value_num',
				'order' => 'DESC',
				'tax_query' => $standard_posts
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ):
				echo $before_widget;
				echo '<div id="popular">';
				echo $before_title . $title . $after_title;
				echo '<ul>';
				while ( $query->have_posts() ) {
					$query->the_post();
					$categories = get_the_category();
					$category = get_category( madeleine_top_category( $categories[0] ) );
					$share_total = get_post_meta( get_the_ID(), '_madeleine_share_total', true );
					$share_number = $share_total;
					if ( $share_number > 10000 )
						$share_number = floor( $share_number / 1000 ) . 'K';
					echo '<li class="post category-' . $category->category_nicename . '">';
					echo '<em data-total="' . $share_total . '"></em>';
					echo '<strong><span>' . $share_number . '</span></strong> ';
					echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
					echo '</li>';
				}
				echo '</ul>';
				echo '<div style="clear: left;"></div>';
				echo '</div>';
			 echo $after_widget;
			endif;
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
			'title' => __( 'Popular posts', 'madeleine' ),
			'total' => 5,
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		// Possible options for the select menus
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
			posts
		</p>
			
		<?php
	}
}
?>