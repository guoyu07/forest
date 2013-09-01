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
    'madeleine_settings_page',        // The ID of the top-level menu page to which this submenu item belongs
    __( 'Home Options', 'madeleine' ),        // The value used to populate the browser's title bar when the menu page is active
    __( 'Home Options', 'madeleine' ),        // The label of this submenu item displayed in the menu
    'update_core',                    // What roles are able to access this submenu item
    'madeleine_home_options_page',    // The ID used to represent this submenu item
    'madeleine_settings_display'      // The callback function used to render the settings for this submenu item
  );
  
  add_submenu_page(
    'madeleine_settings_page',
    __( 'Social Features', 'madeleine' ),
    __( 'Social Features', 'madeleine' ),
    'update_core',
    'madeleine_social_options_page',
    create_function( null, 'madeleine_settings_display( "social_options" );' )
  );
  
  add_submenu_page(
    'madeleine_settings_page',
    __( 'Feedburner and Analytics', 'madeleine' ),
    __( 'Feedburner and Analytics', 'madeleine' ),
    'update_core',
    'madeleine_analytics_options_page',
    create_function( null, 'madeleine_settings_display( "analytics_options" );' )
  );
  
  add_submenu_page(
    'madeleine_settings_page',
    __( 'Reviews', 'madeleine' ),
    __( 'Reviews', 'madeleine' ),
    'update_core',
    'madeleine_reviews_options_page',
    create_function( null, 'madeleine_settings_display( "reviews_options" );' )
  );
  
  add_submenu_page(
    'madeleine_settings_page',
    __( 'Custom CSS', 'madeleine' ),
    __( 'Custom CSS', 'madeleine' ),
    'update_core',
    'madeleine_css_options_page',
    create_function( null, 'madeleine_settings_display( "css_options" );' )
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
  <div id="madeleine-settings" class="wrap">
    <div id="madeleine-settings-header">
      <h2 id="madeleine-settings-title"><?php _e( 'Madeleine Theme Settings', 'madeleine' ); ?></h2>
      <?php settings_errors(); ?>
      <?php
      if ( isset( $_GET[ 'tab' ] ) ):
        $active_tab = $_GET[ 'tab' ];
      elseif ( $active_tab == 'social_options' ):
        $active_tab = 'social_options';
      elseif ( $active_tab == 'analytics_options' ):
        $active_tab = 'analytics_options';
      elseif ( $active_tab == 'reviews_options' ):
        $active_tab = 'reviews_options';
      elseif ( $active_tab == 'css_options' ):
        $active_tab = 'css_options';
      elseif ( $active_tab == 'other_options' ):
        $active_tab = 'other_options';
      elseif ( $active_tab == 'input_examples' ):
        $active_tab = 'input_examples';
      else:
        $active_tab = 'home_options';
      endif;
      ?>
      <h2 class="nav-tab-wrapper">
        <a href="?page=madeleine_settings_page&tab=home_options" class="nav-tab <?php echo $active_tab == 'home_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Home Options', 'madeleine' ); ?></a>
        <a href="?page=madeleine_settings_page&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social features', 'madeleine' ); ?></a>
        <a href="?page=madeleine_settings_page&tab=analytics_options" class="nav-tab <?php echo $active_tab == 'analytics_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Feedburner &amp; Analytics', 'madeleine' ); ?></a>
        <a href="?page=madeleine_settings_page&tab=reviews_options" class="nav-tab <?php echo $active_tab == 'reviews_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Reviews', 'madeleine' ); ?></a>
        <a href="?page=madeleine_settings_page&tab=css_options" class="nav-tab <?php echo $active_tab == 'css_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Custom CSS', 'madeleine' ); ?></a>
        <a href="?page=madeleine_settings_page&tab=other_options" class="nav-tab <?php echo $active_tab == 'other_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Header &amp; Background', 'madeleine' ); ?></a>
        <a href="?page=madeleine_settings_page&tab=input_examples" class="nav-tab <?php echo $active_tab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', 'madeleine' ); ?></a>
        <div style="clear: left;"></div>
      </h2>
    </div>

    <form id="madeleine-settings-form" method="post" action="options.php">
      <?php
        if( $active_tab == 'home_options' ):
          settings_fields( 'madeleine_home_options_group' );
          do_settings_sections( 'madeleine_home_options_page' );
        elseif( $active_tab == 'social_options' ):
          settings_fields( 'madeleine_social_options_group' );
          do_settings_sections( 'madeleine_social_options_page' );
        elseif( $active_tab == 'analytics_options' ):
          settings_fields( 'madeleine_analytics_options_group' );
          do_settings_sections( 'madeleine_analytics_options_page' );
        elseif( $active_tab == 'reviews_options' ):
          settings_fields( 'madeleine_reviews_options_group' );
          do_settings_sections( 'madeleine_reviews_options_page' );
        elseif( $active_tab == 'css_options' ):
          settings_fields( 'madeleine_css_options_group' );
          do_settings_sections( 'madeleine_css_options_page' );
        elseif( $active_tab == 'other_options' ):
          $html = '<h3>Header</h3>';
          $html .= '<p>You can customize the <strong>header logo, title, subtitle, and color</strong> on the <a href="' . get_admin_url() . '/themes.php?page=custom-header">Custom Header</a> page or use the <a href="' . get_admin_url() . '/customize.php">WordPress Customizer</a>.</p>';
          $html .= '<h3>Background</h3>';
          $html .= '<p>You can customize the <strong>background image, color, position, repeat, and attachment</strong> on the <a href="' . get_admin_url() . '/themes.php?page=custom-background">Custom Background</a>  or use the <a href="' . get_admin_url() . '/customize.php">WordPress Customizer</a>.</p>';
          echo $html;
        elseif( $active_tab == 'input_examples' ):
          settings_fields( 'madeleine_input_examples_group' );
          do_settings_sections( 'madeleine_input_examples_page' );
        else:
          settings_fields( 'madeleine_home_options_group' );
          do_settings_sections( 'madeleine_home_options_page' );
        endif;
        if ( $active_tab != 'other_options' )
          submit_button();
      ?>
    </form>
  </div>
<?php
}


// Load the different settings pages


require_once( SETTINGS_DIR .'/pages/home-options.php' );
require_once( SETTINGS_DIR .'/pages/social-options.php' );
require_once( SETTINGS_DIR .'/pages/analytics-options.php' );
require_once( SETTINGS_DIR .'/pages/reviews-options.php' );
require_once( SETTINGS_DIR .'/pages/css-options.php' );
require_once( SETTINGS_DIR .'/pages/examples-options.php' );


?>