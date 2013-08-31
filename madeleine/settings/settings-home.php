<?php

function madeleine_default_home_options() {
  $defaults = array(
    'focus_status' => 1,
    'grid_status' => 1,
    'grid_number' => 6,
    'next_status' => 1,
    'next_number' => 6,
    'category_tabs_status' => 1,
    'reviews_status' => 1,
    'reviews_number' => 6,
  );
  return apply_filters( 'madeleine_default_home_options', $defaults );
}


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
    'grid_status',
    __( 'Status', 'madeleine' ),
    'madeleine_grid_status_callback',
    'madeleine_home_options_page',
    'grid_section'
  );
  
  add_settings_field( 
    'grid_number',
    __( 'Number of grid posts', 'madeleine' ),
    'madeleine_grid_number_callback',
    'madeleine_home_options_page',
    'grid_section'
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
    'madeleine_next_status_callback',
    'madeleine_home_options_page',
    'next_section'
  );
  
  add_settings_field( 
    'next_number',
    __( 'Number of next posts', 'madeleine' ),
    'madeleine_next_number_callback',
    'madeleine_home_options_page',
    'next_section'
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
    'madeleine_category_tabs_status_callback',
    'madeleine_home_options_page',
    'category_tabs_section'
  );

  add_settings_section(
    'reviews_section',
    __( 'Next posts', 'madeleine' ),
    'madeleine_reviews_callback',
    'madeleine_home_options_page'
  );
  
  add_settings_field( 
    'reviews_status',
    __( 'Status', 'madeleine' ),
    'madeleine_reviews_status_callback',
    'madeleine_home_options_page',
    'reviews_section'
  );
  
  add_settings_field( 
    'reviews_number',
    __( 'Number of maxium reviews', 'madeleine' ),
    'madeleine_reviews_number_callback',
    'madeleine_home_options_page',
    'reviews_section'
  );

  
  register_setting(
    'madeleine_home_options_group',
    'madeleine_home_options'
  );  
}
add_action( 'admin_init', 'madeleine_initialize_home_options' );


function madeleine_focus_callback() {
  echo '<p>The Focus feature represents the 5 posts that are displayed on the home page, right below the navigation bar.<br>
  The 5 posts displayed are the <strong>5 latest sticky posts</strong>, from any category. As a result you need to have <em>at least</em> 5 sticky posts published for this feature to work.</p>';
}


function madeleine_focus_status_callback($args) {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<label><input type="radio" name="madeleine_home_options[focus_status]" value="1"' . checked( 1, $settings['focus_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Show</label>';
  $html .= '<br>';
  $html .= '<label><input type="radio" name="madeleine_home_options[focus_status]" value="0"' . checked( 0, $settings['focus_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Hide</label>';
  echo $html;
}


function madeleine_grid_callback() {
  echo '<p>The grid feature represents the 5 posts that are displayed on the home page, right below the navigation bar.</p>';
}


function madeleine_grid_status_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<label><input type="radio" name="madeleine_home_options[grid_status]" value="1"' . checked( 1, $settings['grid_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Show</label>';
  $html .= '<br>';
  $html .= '<label><input type="radio" name="madeleine_home_options[grid_status]" value="0"' . checked( 0, $settings['grid_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Hide</label>';
  echo $html;
}


function madeleine_grid_number_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<select id="grid_number" name="madeleine_home_options[grid_number]">';
  $html .= '<option value="2"' . selected( '2', $settings['grid_number'], false ) . '>2</option>';
  $html .= '<option value="4"' . selected( '4', $settings['grid_number'], false ) . '>4</option>';
  $html .= '<option value="6"' . selected( '6', $settings['grid_number'], false ) . '>6</option>';
  $html .= '<option value="8"' . selected( '8', $settings['grid_number'], false ) . '>8</option>';
  $html .= '<option value="10"' . selected( '10', $settings['grid_number'], false ) . '>10</option>';
  echo $html;
}


function madeleine_next_callback() {
  echo '<p>The grid feature represents the 5 posts that are displayed on the home page, right below the navigation bar.</p>';
}


function madeleine_next_status_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<label><input type="radio" name="madeleine_home_options[next_status]" value="1"' . checked( 1, $settings['next_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Show</label>';
  $html .= '<br>';
  $html .= '<label><input type="radio" name="madeleine_home_options[next_status]" value="0"' . checked( 0, $settings['next_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Hide</label>';
  echo $html;
}


function madeleine_next_number_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<select id="next_number" name="madeleine_home_options[next_number]">';
  $html .= '<option value="2"' . selected( '2', $settings['next_number'], false ) . '>2</option>';
  $html .= '<option value="4"' . selected( '4', $settings['next_number'], false ) . '>4</option>';
  $html .= '<option value="6"' . selected( '6', $settings['next_number'], false ) . '>6</option>';
  $html .= '<option value="8"' . selected( '8', $settings['next_number'], false ) . '>8</option>';
  $html .= '<option value="10"' . selected( '10', $settings['next_number'], false ) . '>10</option>';
  echo $html;
}


function madeleine_category_tabs_callback() {
  echo '<p>The grid feature represents the 5 posts that are displayed on the home page, right below the navigation bar.</p>';
}


function madeleine_category_tabs_status_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<label><input type="radio" name="madeleine_home_options[category_tabs_status]" value="1"' . checked( 1, $settings['category_tabs_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Show as tabs</label>';
  $html .= '<br>';
  $html .= '<label><input type="radio" name="madeleine_home_options[category_tabs_status]" value="0"' . checked( 0, $settings['category_tabs_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Show all one after the other</label>';
  echo $html;
}


function madeleine_reviews_callback() {
  echo '<p>The grid feature represents the 5 posts that are displayed on the home page, right below the navigation bar.</p>';
}


function madeleine_reviews_status_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<label><input type="radio" name="madeleine_home_options[reviews_status]" value="1"' . checked( 1, $settings['reviews_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Show</label>';
  $html .= '<br>';
  $html .= '<label><input type="radio" name="madeleine_home_options[reviews_status]" value="0"' . checked( 0, $settings['reviews_status'], false ) . '>';
  $html .= '&nbsp;';
  $html .= 'Hide</label>';
  echo $html;
}


function madeleine_reviews_number_callback() {
  $settings = get_option( 'madeleine_home_options' );
  $html = '<select id="reviews_number" name="madeleine_home_options[reviews_number]">';
  $html .= '<option value="3"' . selected( '3', $settings['reviews_number'], false ) . '>3</option>';
  $html .= '<option value="6"' . selected( '6', $settings['reviews_number'], false ) . '>6</option>';
  $html .= '<option value="9"' . selected( '9', $settings['reviews_number'], false ) . '>9</option>';
  echo $html;
}

?>