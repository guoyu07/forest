<?php

define( 'SETTINGS_DIR', get_template_directory() .'/settings' );


// Load the custom header, custom background and custom categories functions


require_once( SETTINGS_DIR .'/custom/custom-header.php' );
require_once( SETTINGS_DIR .'/custom/custom-background.php' );
require_once( SETTINGS_DIR .'/custom/custom-categories.php' );


/**
 * This function introduces the theme settings into the 'Appearance' menu and into a top-level 
 * 'Madeleine Theme' menu.
 */
function madeleine_theme_settings() {

  // add_menu_page(
  //   'Madeleine Theme',           // The title to be displayed in the browser window for this page.
  //   'Madeleine',                 // The text to be displayed for this menu item
  //   'update_core',               // Which type of users can see this menu item
  //   'madeleine_settings',        // The unique ID - that is, the slug - for this menu item
  //   'madeleine_settings_display' // The name of the function to call when rendering this menu's page
  // );

  add_theme_page(
    'Madeleine Theme Settings',     // The title to be displayed in the browser window for this page.
    'Madeleine Settings',           // The text to be displayed for this menu item
    'update_core',                  // Which type of users can see this menu item
    'madeleine_settings_page',      // The unique ID - that is, the slug - for this menu item
    'madeleine_settings_display'    // The name of the function to call when rendering this menu's page
  );
  
  add_submenu_page(
    'madeleine_settings_page',                  // The ID of the top-level menu page to which this submenu item belongs
    __( 'Home', 'madeleine' ),      // The value used to populate the browser's title bar when the menu page is active
    __( 'Home', 'madeleine' ),      // The label of this submenu item displayed in the menu
    'update_core',                              // What roles are able to access this submenu item
    'madeleine_home_options_page',          // The ID used to represent this submenu item
    'madeleine_settings_display'                // The callback function used to render the settings for this submenu item
  );
  
  add_submenu_page(
    'madeleine_settings_page',
    __( 'Social Accounts', 'madeleine' ),
    __( 'Social Accounts', 'madeleine' ),
    'update_core',
    'madeleine_social_options_page',
    create_function( null, 'madeleine_settings_display( "social_options" );' )
  );
  
  add_submenu_page(
    'madeleine_settings_page',
    __( 'Input Examples', 'madeleine' ),
    __( 'Input Examples', 'madeleine' ),
    'update_core',
    'madeleine_input_examples_page',
    create_function( null, 'madeleine_settings_display( "input_examples" );' )
  );
}
add_action( 'admin_menu', 'madeleine_theme_settings' );


function madeleine_settings_display( $active_tab = '' ) {
  ?>
  <div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e( 'Madeleine Theme Settings', 'madeleine' ); ?></h2>
    <?php settings_errors(); ?>
    <?php
    if ( isset( $_GET[ 'tab' ] ) ):
      $active_tab = $_GET[ 'tab' ];
    elseif ( $active_tab == 'social_options' ):
      $active_tab = 'social_options';
    elseif ( $active_tab == 'input_examples' ):
      $active_tab = 'input_examples';
    else:
      $active_tab = 'home_options';
    endif;
    ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=madeleine_settings_page&tab=home_options" class="nav-tab <?php echo $active_tab == 'home_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Home options', 'madeleine' ); ?></a>
      <a href="?page=madeleine_settings_page&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Accounts', 'madeleine' ); ?></a>
      <a href="?page=madeleine_settings_page&tab=input_examples" class="nav-tab <?php echo $active_tab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', 'madeleine' ); ?></a>
    </h2>

    <form method="post" action="options.php">
      <?php
        if( $active_tab == 'home_options' ):
          settings_fields( 'madeleine_home_options_group' );
          do_settings_sections( 'madeleine_home_options_page' );
        elseif( $active_tab == 'social_options' ):
          settings_fields( 'madeleine_social_options_group' );
          do_settings_sections( 'madeleine_social_options_page' );
        else:
          settings_fields( 'madeleine_input_examples_group' );
          do_settings_sections( 'madeleine_input_examples_page' );
        endif;
        submit_button();
      ?>
    </form>
  </div>
<?php
}


// Load the different settings pages


require_once( SETTINGS_DIR .'/settings-home.php' );
require_once( SETTINGS_DIR .'/settings-social.php' );
require_once( SETTINGS_DIR .'/settings-examples.php' );


?>