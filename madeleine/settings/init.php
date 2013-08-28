<?php

/**
 * This function introduces the theme settings into the 'Appearance' menu and into a top-level 
 * 'Madeleine Theme' menu.
 */
function madeleine_example_theme_menu() {

  add_object_page(
    'Madeleine Theme',          // The title to be displayed in the browser window for this page.
    'Madeleine',          // The text to be displayed for this menu item
    'update_core',          // Which type of users can see this menu item
    'madeleine_theme_settings',      // The unique ID - that is, the slug - for this menu item
    'madeleine_theme_display'       // The name of the function to call when rendering this menu's page
  );
  
  add_submenu_page(
    'madeleine_theme_settings',       // The ID of the top-level menu page to which this submenu item belongs
    __( 'General Settings', 'madeleine' ),     // The value used to populate the browser's title bar when the menu page is active
    __( 'General Settings', 'madeleine' ),         // The label of this submenu item displayed in the menu
    'update_core',          // What roles are able to access this submenu item
    'madeleine_theme_settings',  // The ID used to represent this submenu item
    'madeleine_theme_display'       // The callback function used to render the settings for this submenu item
  );
  
  add_submenu_page(
    'madeleine_theme_settings',
    __( 'Social Accounts', 'madeleine' ),
    __( 'Social Accounts', 'madeleine' ),
    'update_core',
    'madeleine_theme_social_settings',
    create_function( null, 'madeleine_theme_display( "social_settings" );' )
  );
  
  add_submenu_page(
    'madeleine_theme_settings',
    __( 'Input Examples', 'madeleine' ),
    __( 'Input Examples', 'madeleine' ),
    'update_core',
    'madeleine_theme_input_examples',
    create_function( null, 'madeleine_theme_display( "input_examples" );' )
  );


} 
add_action( 'admin_menu', 'madeleine_example_theme_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function madeleine_theme_display( $active_tab = '' ) {
  ?>
  <div class="wrap">
    <div id="icon-themes" class="icon32"></div>

    <h2><?php _e( 'Madeleine Theme Settings', 'madeleine' ); ?></h2>

    <?php settings_errors(); ?>

    <?php
    if ( isset( $_GET[ 'page' ] ) ):
      $active_tab = $_GET[ 'page' ];
    elseif ( $active_tab == 'madeleine_theme_social_settings' ):
      $active_tab = 'madeleine_theme_social_settings';
    elseif ( $active_tab == 'madeleine_theme_input_examples' ):
      $active_tab = 'madeleine_theme_input_examples';
    else:
      $active_tab = 'madeleine_theme_settings';
    endif;
    ?>

    <h2 class="nav-tab-wrapper">
      <a href="?page=madeleine_theme_settings" class="nav-tab <?php echo $active_tab == 'madeleine_theme_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Settings', 'madeleine' ); ?></a>
      <a href="?page=madeleine_theme_social_settings" class="nav-tab <?php echo $active_tab == 'madeleine_theme_social_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Accounts', 'madeleine' ); ?></a>
      <a href="?page=madeleine_theme_input_examples" class="nav-tab <?php echo $active_tab == 'madeleine_theme_input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', 'madeleine' ); ?></a>
    </h2>

    <form method="post" action="settings.php">
      <?php
        if( $active_tab == 'madeleine_theme_settings' ):
          settings_fields( 'madeleine_theme_display_settings' );
          do_settings_sections( 'madeleine_theme_display_settings' );
        elseif( $active_tab == 'madeleine_theme_social_settings' ):
          settings_fields( 'madeleine_theme_social_settings' );
          do_settings_sections( 'madeleine_theme_social_settings' );
        else:
          settings_fields( 'madeleine_theme_input_examples' );
          do_settings_sections( 'madeleine_theme_input_examples' );
        endif;
        submit_button();
      ?>
    </form>
  </div>
<?php
} 


/**
 * Provides default values for the Social Accounts.
 */
function madeleine_theme_default_social_settings() {
  
  $defaults = array(
    'twitter'   =>  '',
    'facebook'    =>  '',
    'googleplus'  =>  '',
  );
  
  return apply_filters( 'madeleine_theme_default_social_settings', $defaults );
  
} 

/**
 * Provides default values for the General Settings.
 */
function madeleine_theme_default_display_settings() {
  
  $defaults = array(
    'show_header'   =>  '',
    'show_content'    =>  '',
    'show_footer'   =>  '',
  );
  
  return apply_filters( 'madeleine_theme_default_display_settings', $defaults );
  
} 

/**
 * Provides default values for the Input Settings.
 */
function madeleine_theme_default_input_settings() {
  
  $defaults = array(
    'input_example'   =>  '',
    'textarea_example'  =>  '',
    'checkbox_example'  =>  '',
    'radio_example'   =>  '',
    'time_settings'    =>  'default' 
  );
  
  return apply_filters( 'madeleine_theme_default_input_settings', $defaults );
  
} 

/**
 * Initializes the theme's display settings page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function madeleine_initialize_theme_settings() {

  // If the theme settings don't exist, create them.
  if( false == get_option( 'madeleine_theme_display_settings' ) )
    add_option( 'madeleine_theme_display_settings', apply_filters( 'madeleine_theme_default_display_settings', madeleine_theme_default_display_settings() ) );

  // First, we register a section. This is necessary since all future settings must belong to a 
  add_settings_section(
    'general_settings_section',     // ID used to identify this section and with which to register settings
    __( 'General Settings', 'madeleine' ),   // Title to be displayed on the administration page
    'madeleine_general_settings_callback', // Callback used to render the description of the section
    'madeleine_theme_display_settings'   // Page on which to add this section of settings
  );
  
  // Next, we'll introduce the fields for toggling the visibility of content elements.
  add_settings_field( 
    'show_header',            // ID used to identify the field throughout the theme
    __( 'Header', 'madeleine' ),              // The label to the left of the option interface element
    'madeleine_toggle_header_callback', // The name of the function responsible for rendering the option interface
    'madeleine_theme_display_settings',  // The page on which this option will be displayed
    'general_settings_section',     // The name of the section to which this field belongs
    array(                // The array of arguments to pass to the callback. In this case, just a description.
      __( 'Activate this setting to display the header.', 'madeleine' ),
    )
  );
  
  add_settings_field( 
    'show_content',           
    __( 'Content', 'madeleine' ),       
    'madeleine_toggle_content_callback',  
    'madeleine_theme_display_settings',          
    'general_settings_section',     
    array(                
      __( 'Activate this setting to display the content.', 'madeleine' ),
    )
  );
  
  add_settings_field( 
    'show_footer',            
    __( 'Footer', 'madeleine' ),        
    'madeleine_toggle_footer_callback', 
    'madeleine_theme_display_settings',    
    'general_settings_section',     
    array(                
      __( 'Activate this setting to display the footer.', 'madeleine' ),
    )
  );
  
  // Finally, we register the fields with WordPress
  register_setting(
    'madeleine_theme_display_settings',
    'madeleine_theme_display_settings'
  );
  
} 
add_action( 'admin_init', 'madeleine_initialize_theme_settings' );


/**
 * Initializes the theme's social settings by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function madeleine_theme_intialize_social_settings() {

  if( false == get_option( 'madeleine_theme_social_settings' ) )
    add_option( 'madeleine_theme_social_settings', apply_filters( 'madeleine_theme_default_social_settings', madeleine_theme_default_social_settings() ) );
  
  add_settings_section(
    'social_settings_section',      // ID used to identify this section and with which to register settings
    __( 'Social Accounts', 'madeleine' ),    // Title to be displayed on the administration page
    'madeleine_social_settings_callback',  // Callback used to render the description of the section
    'madeleine_theme_social_settings'    // Page on which to add this section of settings
  );
  
  add_settings_field( 
    'twitter',            
    'Twitter',              
    'madeleine_twitter_callback', 
    'madeleine_theme_social_settings', 
    'social_settings_section'     
  );

  add_settings_field( 
    'facebook',           
    'Facebook',             
    'madeleine_facebook_callback',  
    'madeleine_theme_social_settings', 
    'social_settings_section'     
  );
  
  add_settings_field( 
    'googleplus',           
    'Google+',              
    'madeleine_googleplus_callback',  
    'madeleine_theme_social_settings', 
    'social_settings_section'     
  );
  
  register_setting(
    'madeleine_theme_social_settings',
    'madeleine_theme_social_settings',
    'madeleine_theme_sanitize_social_settings'
  );
  
} 
add_action( 'admin_init', 'madeleine_theme_intialize_social_settings' );


/**
 * Initializes the theme's input example by registering the Sections,
 * Fields, and Settings. This particular group of settings is used to demonstration
 * validation and sanitization.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function madeleine_theme_initialize_input_examples() {

  if( false == get_option( 'madeleine_theme_input_examples' ) )
    add_option( 'madeleine_theme_input_examples', apply_filters( 'madeleine_theme_default_input_settings', madeleine_theme_default_input_settings() ) );

  add_settings_section(
    'input_examples_section',
    __( 'Input Examples', 'madeleine' ),
    'madeleine_input_examples_callback',
    'madeleine_theme_input_examples'
  );
  
  add_settings_field( 
    'Input Element',            
    __( 'Input Element', 'madeleine' ),             
    'madeleine_input_element_callback', 
    'madeleine_theme_input_examples', 
    'input_examples_section'      
  );
  
  add_settings_field( 
    'Textarea Element',           
    __( 'Textarea Element', 'madeleine' ),              
    'madeleine_textarea_element_callback',  
    'madeleine_theme_input_examples', 
    'input_examples_section'      
  );
  
  add_settings_field(
    'Checkbox Element',
    __( 'Checkbox Element', 'madeleine' ),
    'madeleine_checkbox_element_callback',
    'madeleine_theme_input_examples',
    'input_examples_section'
  );
  
  add_settings_field(
    'Radio Button Elements',
    __( 'Radio Button Elements', 'madeleine' ),
    'madeleine_radio_element_callback',
    'madeleine_theme_input_examples',
    'input_examples_section'
  );
  
  add_settings_field(
    'Select Element',
    __( 'Select Element', 'madeleine' ),
    'madeleine_select_element_callback',
    'madeleine_theme_input_examples',
    'input_examples_section'
  );
  
  register_setting(
    'madeleine_theme_input_examples',
    'madeleine_theme_input_examples',
    'madeleine_theme_validate_input_examples'
  );

} 
add_action( 'admin_init', 'madeleine_theme_initialize_input_examples' );


/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the General Settings page. 
 *
 * It's called from the 'madeleine_initialize_theme_settings' function by being passed as a parameter
 * in the add_settings_section function.
 */
function madeleine_general_settings_callback() {
  echo '<p>' . __( 'Select which areas of content you wish to display.', 'madeleine' ) . '</p>';
} 

/**
 * This function provides a simple description for the Social Accounts page. 
 *
 * It's called from the 'madeleine_theme_intialize_social_settings' function by being passed as a parameter
 * in the add_settings_section function.
 */
function madeleine_social_settings_callback() {
  echo '<p>' . __( 'Provide the URL to the social networks you\'d like to display.', 'madeleine' ) . '</p>';
} 

/**
 * This function provides a simple description for the Input Examples page.
 *
 * It's called from the 'madeleine_theme_intialize_input_examples_settings' function by being passed as a parameter
 * in the add_settings_section function.
 */
function madeleine_input_examples_callback() {
  echo '<p>' . __( 'Provides examples of the five basic element types.', 'madeleine' ) . '</p>';
} 

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function madeleine_toggle_header_callback($args) {
  
  // First, we read the settings collection
  $settings = get_option('madeleine_theme_display_settings');
  
  // Next, we update the name attribute to access this element's ID in the context of the display settings array
  // We also access the show_header element of the settings collection in the call to the checked() helper function
  $html = '<input type="checkbox" id="show_header" name="madeleine_theme_display_settings[show_header]" value="1" ' . checked( 1, isset( $settings['show_header'] ) ? $settings['show_header'] : 0, false ) . '/>'; 
  
  // Here, we'll take the first argument of the array and add it to a label next to the checkbox
  $html .= '<label for="show_header">&nbsp;'  . $args[0] . '</label>'; 
  
  echo $html;
  
} 

function madeleine_toggle_content_callback($args) {

  $settings = get_option('madeleine_theme_display_settings');
  
  $html = '<input type="checkbox" id="show_content" name="madeleine_theme_display_settings[show_content]" value="1" ' . checked( 1, isset( $settings['show_content'] ) ? $settings['show_content'] : 0, false ) . '/>'; 
  $html .= '<label for="show_content">&nbsp;'  . $args[0] . '</label>'; 
  
  echo $html;
  
} 

function madeleine_toggle_footer_callback($args) {
  
  $settings = get_option('madeleine_theme_display_settings');
  
  $html = '<input type="checkbox" id="show_footer" name="madeleine_theme_display_settings[show_footer]" value="1" ' . checked( 1, isset( $settings['show_footer'] ) ? $settings['show_footer'] : 0, false ) . '/>'; 
  $html .= '<label for="show_footer">&nbsp;'  . $args[0] . '</label>'; 
  
  echo $html;
  
} 

function madeleine_twitter_callback() {
  
  // First, we read the social settings collection
  $settings = get_option( 'madeleine_theme_social_settings' );
  
  // Next, we need to make sure the element is defined in the settings. If not, we'll set an empty string.
  $url = '';
  if( isset( $settings['twitter'] ) ) {
    $url = esc_url( $settings['twitter'] );
  } 
  
  // Render the output
  echo '<input type="text" id="twitter" name="madeleine_theme_social_settings[twitter]" value="' . $url . '" />';
  
} 

function madeleine_facebook_callback() {
  
  $settings = get_option( 'madeleine_theme_social_settings' );
  
  $url = '';
  if( isset( $settings['facebook'] ) ) {
    $url = esc_url( $settings['facebook'] );
  } 
  
  // Render the output
  echo '<input type="text" id="facebook" name="madeleine_theme_social_settings[facebook]" value="' . $url . '" />';
  
} 

function madeleine_googleplus_callback() {
  
  $settings = get_option( 'madeleine_theme_social_settings' );
  
  $url = '';
  if( isset( $settings['googleplus'] ) ) {
    $url = esc_url( $settings['googleplus'] );
  } 
  
  // Render the output
  echo '<input type="text" id="googleplus" name="madeleine_theme_social_settings[googleplus]" value="' . $url . '" />';
  
} 

function madeleine_input_element_callback() {
  
  $settings = get_option( 'madeleine_theme_input_examples' );
  
  // Render the output
  echo '<input type="text" id="input_example" name="madeleine_theme_input_examples[input_example]" value="' . $settings['input_example'] . '" />';
  
} 

function madeleine_textarea_element_callback() {
  
  $settings = get_option( 'madeleine_theme_input_examples' );
  
  // Render the output
  echo '<textarea id="textarea_example" name="madeleine_theme_input_examples[textarea_example]" rows="5" cols="50">' . $settings['textarea_example'] . '</textarea>';
  
} 

function madeleine_checkbox_element_callback() {

  $settings = get_option( 'madeleine_theme_input_examples' );
  
  $html = '<input type="checkbox" id="checkbox_example" name="madeleine_theme_input_examples[checkbox_example]" value="1"' . checked( 1, $settings['checkbox_example'], false ) . '/>';
  $html .= '&nbsp;';
  $html .= '<label for="checkbox_example">This is an example of a checkbox</label>';
  
  echo $html;

} 

function madeleine_radio_element_callback() {

  $settings = get_option( 'madeleine_theme_input_examples' );
  
  $html = '<input type="radio" id="radio_example_one" name="madeleine_theme_input_examples[radio_example]" value="1"' . checked( 1, $settings['radio_example'], false ) . '/>';
  $html .= '&nbsp;';
  $html .= '<label for="radio_example_one">Option One</label>';
  $html .= '&nbsp;';
  $html .= '<input type="radio" id="radio_example_two" name="madeleine_theme_input_examples[radio_example]" value="2"' . checked( 2, $settings['radio_example'], false ) . '/>';
  $html .= '&nbsp;';
  $html .= '<label for="radio_example_two">Option Two</label>';
  
  echo $html;

} 

function madeleine_select_element_callback() {

  $settings = get_option( 'madeleine_theme_input_examples' );
  
  $html = '<select id="time_settings" name="madeleine_theme_input_examples[time_settings]">';
    $html .= '<option value="default">' . __( 'Select a time option...', 'madeleine' ) . '</option>';
    $html .= '<option value="never"' . selected( $settings['time_settings'], 'never', false) . '>' . __( 'Never', 'madeleine' ) . '</option>';
    $html .= '<option value="sometimes"' . selected( $settings['time_settings'], 'sometimes', false) . '>' . __( 'Sometimes', 'madeleine' ) . '</option>';
    $html .= '<option value="always"' . selected( $settings['time_settings'], 'always', false) . '>' . __( 'Always', 'madeleine' ) . '</option>'; $html .= '</select>';
  
  echo $html;

} 

/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
 
/**
 * Sanitization callback for the social settings. Since each of the social settings are text inputs,
 * this function loops through the incoming option and strips all tags and slashes from the value
 * before serializing it.
 *  
 * @params  $input  The unsanitized collection of settings.
 *
 * @returns     The collection of sanitized values.
 */
function madeleine_theme_sanitize_social_settings( $input ) {
  
  // Define the array for the updated settings
  $output = array();

  // Loop through each of the settings sanitizing the data
  foreach( $input as $key => $val ) {
  
    if( isset ( $input[$key] ) ) {
      $output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
    } 
  
  } 
  
  // Return the new collection
  return apply_filters( 'madeleine_theme_sanitize_social_settings', $output, $input );

} 

function madeleine_theme_validate_input_examples( $input ) {

  // Create our array for storing the validated settings
  $output = array();
  
  // Loop through each of the incoming settings
  foreach( $input as $key => $value ) {
    
    // Check to see if the current option has a value. If so, process it.
    if( isset( $input[$key] ) ) {
    
      // Strip all HTML and PHP tags and properly handle quoted strings
      $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
      
    } 
    
  } 
  
  // Return the array processing any additional functions filtered by this action
  return apply_filters( 'madeleine_theme_validate_input_examples', $output, $input );

} 

?>