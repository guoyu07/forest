<?php


function madeleine_default_input_settings() {
  $defaults = array(
    'input_example' => '',
    'textarea_example'  => '',
    'checkbox_example' => '',
    'radio_example' => '',
    'time_settings' => 'default' 
  );
  return apply_filters( 'madeleine_default_input_settings', $defaults );
}


function madeleine_initialize_input_examples() {

  if( false == get_option( 'madeleine_input_examples' ) )
    add_option( 'madeleine_input_examples', apply_filters( 'madeleine_default_input_settings', madeleine_default_input_settings() ) );

  add_settings_section(
    'input_examples_section',
    __( 'Input Examples', 'madeleine' ),
    'madeleine_input_examples_callback',
    'madeleine_input_examples_page'
  );
  
  add_settings_field( 
    'Input Element',
    __( 'Input Element', 'madeleine' ),
    'madeleine_input_element_callback',
    'madeleine_input_examples_page',
    'input_examples_section'
  );
  
  add_settings_field( 
    'Textarea Element',
    __( 'Textarea Element', 'madeleine' ),
    'madeleine_textarea_element_callback',
    'madeleine_input_examples_page',
    'input_examples_section'
  );
  
  add_settings_field(
    'Checkbox Element',
    __( 'Checkbox Element', 'madeleine' ),
    'madeleine_checkbox_element_callback',
    'madeleine_input_examples_page',
    'input_examples_section'
  );
  
  add_settings_field(
    'Radio Button Elements',
    __( 'Radio Button Elements', 'madeleine' ),
    'madeleine_radio_element_callback',
    'madeleine_input_examples_page',
    'input_examples_section'
  );
  
  add_settings_field(
    'Select Element',
    __( 'Select Element', 'madeleine' ),
    'madeleine_select_element_callback',
    'madeleine_input_examples_page',
    'input_examples_section'
  );
  
  register_setting(
    'madeleine_input_examples_group',
    'madeleine_input_examples',
    'madeleine_validate_input_examples'
  );

} 
add_action( 'admin_init', 'madeleine_initialize_input_examples' );


function madeleine_input_examples_callback() {
  echo '<p>' . __( 'Provides examples of the five basic element types.', 'madeleine' ) . '</p>';
}


function madeleine_input_element_callback() {
  $settings = get_option( 'madeleine_input_examples' );
  echo '<input type="text" id="input_example" name="madeleine_input_examples[input_example]" value="' . $settings['input_example'] . '">';
}


function madeleine_textarea_element_callback() {
  $settings = get_option( 'madeleine_input_examples' );
  echo '<textarea id="textarea_example" name="madeleine_input_examples[textarea_example]" rows="5" cols="50">' . $settings['textarea_example'] . '</textarea>';
}


function madeleine_checkbox_element_callback() {
  $settings = get_option( 'madeleine_input_examples' );
  $html = '<input type="checkbox" id="checkbox_example" name="madeleine_input_examples[checkbox_example]" value="1"' . checked( 1, $settings['checkbox_example'], false ) . '>';
  $html .= '&nbsp;';
  $html .= '<label for="checkbox_example">This is an example of a checkbox</label>';
  echo $html;
}


function madeleine_radio_element_callback() {
  $settings = get_option( 'madeleine_input_examples' );
  $html = '<input type="radio" id="radio_example_one" name="madeleine_input_examples[radio_example]" value="1"' . checked( 1, $settings['radio_example'], false ) . '>';
  $html .= '&nbsp;';
  $html .= '<label for="radio_example_one">Option One</label>';
  $html .= '&nbsp;';
  $html .= '<input type="radio" id="radio_example_two" name="madeleine_input_examples[radio_example]" value="2"' . checked( 2, $settings['radio_example'], false ) . '>';
  $html .= '&nbsp;';
  $html .= '<label for="radio_example_two">Option Two</label>';
  echo $html;
}


function madeleine_select_element_callback() {
  $settings = get_option( 'madeleine_input_examples' );
  $html = '<select id="time_settings" name="madeleine_input_examples[time_settings]">';
  $html .= '<option value="default">' . __( 'Select a time option...', 'madeleine' ) . '</option>';
  $html .= '<option value="never"' . selected( $settings['time_settings'], 'never', false ) . '>' . __( 'Never', 'madeleine' ) . '</option>';
  $html .= '<option value="sometimes"' . selected( $settings['time_settings'], 'sometimes', false ) . '>' . __( 'Sometimes', 'madeleine' ) . '</option>';
  $html .= '<option value="always"' . selected( $settings['time_settings'], 'always', false ) . '>' . __( 'Always', 'madeleine' ) . '</option>'; $html .= '</select>';
  echo $html;
}


function madeleine_validate_input_examples( $input ) {
  // Create our array for storing the validated settings
  $output = array();
  
  // Loop through each of the incoming settings
  foreach( $input as $key => $value ):
    // Check to see if the current option has a value. If so, process it.
    if( isset( $input[$key] ) )
      $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
  endforeach;
  
  // Return the array processing any additional functions filtered by this action
  return apply_filters( 'madeleine_validate_input_examples', $output, $input );
}

?>