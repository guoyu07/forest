<?php

if ( !function_exists( 'madeleine_default_home_options' ) ) {
  function madeleine_default_home_options() {
    $defaults = array(
      'focus_status' => 1,
      'grid_number' => 6,
      'next_status' => 1,
      'next_number' => 10,
      'category_tabs_status' => 1,
      'reviews_status' => 1,
      'reviews_number' => 6,
    );
    return apply_filters( 'madeleine_default_home_options', $defaults );
  }
}


if ( !function_exists( 'madeleine_initialize_home_options' ) ) {
  function madeleine_initialize_home_options() {
    if( false == get_option( 'madeleine_home_options' ) )
      add_option( 'madeleine_home_options', apply_filters( 'madeleine_default_home_options', madeleine_default_home_options() ) );

    add_settings_section(
      'focus_section', // ID used to identify this section and with which to register settings
      __( 'Focus feature', 'madeleine' ), // Title to be displayed on the administration page
      'madeleine_focus_callback', // Callback used to render the description of the section
      'madeleine_home_options_page' // Page on which to add this section of settings
    );
    
    add_settings_field( 
      'focus_status', // ID used to identify the field throughout the theme
      __( 'Status', 'madeleine' ), // The label to the left of the option interface element
      'madeleine_focus_status_callback', // The name of the function responsible for rendering the option interface
      'madeleine_home_options_page', // The page on which this option will be displayed
      'focus_section', // The name of the section to which this field belongs
      array( // The array of arguments to pass to the callback. In this case, just a description.
        __( 'Activate this setting to display the header.', 'madeleine' ),
      )
    );

    add_settings_section(
      'grid_section',
      __( 'Grid posts', 'madeleine' ),
      'madeleine_grid_callback',
      'madeleine_home_options_page'
    );
    
    add_settings_field( 
      'grid_number',
      __( 'Number of grid posts', 'madeleine' ),
      'madeleine_home_number_callback',
      'madeleine_home_options_page',
      'grid_section',
      array( 'grid', [2, 4, 6, 8, 10, 12] )
    );

    add_settings_section(
      'next_section',
      __( 'Next posts', 'madeleine' ),
      'madeleine_next_callback',
      'madeleine_home_options_page'
    );
    
    add_settings_field( 
      'next_status',
      __( 'Status', 'madeleine' ),
      'madeleine_home_status_callback',
      'madeleine_home_options_page',
      'next_section',
      array( 'next' )
    );
    
    add_settings_field( 
      'next_number',
      __( 'Number of next posts', 'madeleine' ),
      'madeleine_home_number_callback',
      'madeleine_home_options_page',
      'next_section',
      array( 'next', [2, 4, 6, 8, 10, 12] )
    );

    add_settings_section(
      'category_tabs_section',
      __( 'Category tabs', 'madeleine' ),
      'madeleine_category_tabs_callback',
      'madeleine_home_options_page'
    );
    
    add_settings_field( 
      'category_tabs_status',
      __( 'Status', 'madeleine' ),
      'madeleine_home_status_callback',
      'madeleine_home_options_page',
      'category_tabs_section',
      array( 'category_tabs', ['Show categories as tabs', 'Show all categories one after the other'] )
    );

    add_settings_section(
      'reviews_section',
      __( 'Reviews tabs', 'madeleine' ),
      'madeleine_reviews_callback',
      'madeleine_home_options_page'
    );
    
    add_settings_field( 
      'reviews_status',
      __( 'Status', 'madeleine' ),
      'madeleine_home_status_callback',
      'madeleine_home_options_page',
      'reviews_section',
      array( 'reviews' )
    );
    
    add_settings_field( 
      'reviews_number',
      __( 'Number of maxium reviews', 'madeleine' ),
      'madeleine_home_number_callback',
      'madeleine_home_options_page',
      'reviews_section',
      array( 'reviews', [3, 6, 9] )
    );

    
    register_setting(
      'madeleine_home_options_group',
      'madeleine_home_options'
    );  
  }
}
add_action( 'admin_init', 'madeleine_initialize_home_options' );


if ( !function_exists( 'madeleine_focus_callback' ) ) {
  function madeleine_focus_callback() {
    echo '<p>The Focus feature represents the 5 posts that are displayed on the home page, right below the navigation bar.<br>
    The 5 posts displayed are the <strong>5 latest sticky posts</strong>, from any category. As a result you need to have <em>at least</em> 5 sticky posts published for this feature to work.</p>';
  }
}


if ( !function_exists( 'madeleine_focus_status_callback' ) ) {
  function madeleine_focus_status_callback($args) {
    $settings = get_option( 'madeleine_home_options' );
    $html = '<label><input type="radio" name="madeleine_home_options[focus_status]" value="1"' . checked( 1, $settings['focus_status'], false ) . '>';
    $html .= '&nbsp;Show</label>';
    $html .= '<label><input type="radio" name="madeleine_home_options[focus_status]" value="0"' . checked( 0, $settings['focus_status'], false ) . '>';
    $html .= '&nbsp;Hide</label>';
    echo $html;
  }
}


if ( !function_exists( 'madeleine_home_status_callback' ) ) {
  function madeleine_home_status_callback( $args ) {
    $settings = get_option( 'madeleine_home_options' );
    $key = $args[0] . '_status';
    if ( isset( $args[1] ) )
      $labels = $args[1];
    $html = '<label><input type="radio" name="madeleine_home_options[' . $key . ']" value="1"' . checked( 1, $settings[$key], false ) . '>&nbsp;';
    $html .= ( isset( $labels ) ) ? $labels[0] : 'Show';
    $html .= '</label>';
    $html .= '<label><input type="radio" name="madeleine_home_options[' . $key . ']" value="0"' . checked( 0, $settings[$key], false ) . '>&nbsp;';
    $html .= ( isset( $labels ) ) ? $labels[1] : 'Hide';
    $html .= '</label>';
    echo $html;
  }
}


if ( !function_exists( 'madeleine_home_number_callback' ) ) {
  function madeleine_home_number_callback( $args ) {
    $settings = get_option( 'madeleine_home_options' );
    $key = $args[0] . '_number';
    $numbers = $args[1];
    $html = '<select name="madeleine_home_options[' . $key . ']">';
    foreach ($numbers as $number ):
      $html .= '<option value="' . $number . '"' . selected( $number, $settings[$key], false ) . '>' . $number . '</option>';
    endforeach;
    $html .= '</select>';
    echo $html;
  }
}


if ( !function_exists( 'madeleine_grid_callback' ) ) {
  function madeleine_grid_callback() {
    echo '<p>The Grid posts are the ones displayed after the Focus posts.</p>';
  }
}


if ( !function_exists( 'madeleine_next_callback' ) ) {
  function madeleine_next_callback() {
    echo '<p>The Next posts are the ones displayed after the Grid posts. They have no excerpt.</p>';
  }
}


if ( !function_exists( 'madeleine_category_tabs_callback' ) ) {
  function madeleine_category_tabs_callback() {
    echo '<p>The Category tabs display the 5 latest posts of each category. They can be displayed as tabs (one category at a time) or displayed in full, one after the other.</p>';
  }
}


if ( !function_exists( 'madeleine_reviews_callback' ) ) {
  function madeleine_reviews_callback() {
    echo '<p>The homepage displays the latest Reviews, which can be filtered by Review category.</p>';
  }
}

?>