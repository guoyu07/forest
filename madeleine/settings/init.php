<?php

define( 'SETTINGS_DIR', get_template_directory() .'/settings' );
define( 'SETTINGS_URL', get_template_directory_uri() .'/settings' );


// Load the custom header, custom background and custom categories functions


require_once( SETTINGS_DIR .'/custom/custom-header.php' );
require_once( SETTINGS_DIR .'/custom/custom-background.php' );
require_once( SETTINGS_DIR .'/custom/custom-categories.php' );
require_once( SETTINGS_DIR .'/custom/custom-colors.php' );


/**
 * This function introduces the theme settings into the 'Appearance' menu and into a top-level 
 * 'Madeleine Theme' menu.
 */
if ( !function_exists( 'madeleine_theme_settings' ) ) {
  function madeleine_theme_settings() {

    $icon = SETTINGS_URL . '/images/icon.png';

    add_theme_page(
      __( 'Madeleine Popular Posts plugin', 'madeleine' ),
      __( 'Madeleine Popular Posts plugin', 'madeleine' ),
      'update_core',
      'madeleine_popularity_options_page',
      'madeleine_popularity_options_display'
    );
  }
}
add_action( 'admin_menu', 'madeleine_theme_settings' );


if ( !function_exists( 'madeleine_popularity_options_display' ) ) {
  function madeleine_popularity_options_display() {
    ?>
    <div id="madeleine-settings" class="wrap">
      <div id="madeleine-settings-header">
        <h2 id="madeleine-settings-title"><?php _e( 'Popular Posts plugin', 'madeleine' ); ?></h2>
        <?php settings_errors(); ?>
      </div>

      <form id="madeleine-settings-form" method="post" action="options.php">
        <?php
          settings_fields( 'madeleine_popularity_options_group' );
          do_settings_sections( 'madeleine_popularity_options_page' );
          submit_button();
        ?>
      </form>
    </div>
    <?php
  }
}


/**
 * This function introduces the theme settings into the 'Appearance' menu and into a top-level 
 * 'Madeleine Theme' menu.
 */

/*if ( !function_exists( 'madeleine_theme_settings' ) ) {
  function madeleine_theme_settings() {

    $icon = SETTINGS_URL . '/images/icon.png';

    // add_menu_page(
    //   'Madeleine Theme',           // The title to be displayed in the browser window for this page.
    //   'Madeleine',                 // The text to be displayed for this menu item
    //   'update_core',               // Which type of users can see this menu item
    //   'madeleine_settings',        // The unique ID - that is, the slug - for this menu item
    //   'madeleine_settings_display' // The name of the function to call when rendering this menu's page
    // );

    add_theme_page(
      __( 'Popular Posts plugin', 'madeleine' ),
      __( 'Popular Posts plugin', 'madeleine' ),
      'update_core',
      'madeleine_popularity_options_page',
      create_function( null, 'madeleine_settings_display( "popularity_options" );' )
    );

    add_object_page(
      'Madeleine Theme Settings',     // The title to be displayed in the browser window for this page.
      'Madeleine',                    // The text to be displayed for this menu item
      'update_core',                  // Which type of users can see this menu item
      'madeleine_home_options_page',      // The unique ID - that is, the slug - for this menu item
      'madeleine_settings_display',   // The name of the function to call when rendering this menu's page
      $icon
    );
    
    add_submenu_page(
      'madeleine_home_options_page',        // The ID of the top-level menu page to which this submenu item belongs
      __( 'Home Options', 'madeleine' ),        // The value used to populate the browser's title bar when the menu page is active
      __( 'Home Options', 'madeleine' ),        // The label of this submenu item displayed in the menu
      'update_core',                    // What roles are able to access this submenu item
      'madeleine_home_options_page',    // The ID used to represent this submenu item
      'madeleine_settings_display'      // The callback function used to render the settings for this submenu item
    );
    
    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Social Features', 'madeleine' ),
      __( 'Social Features', 'madeleine' ),
      'update_core',
      'madeleine_social_options_page',
      create_function( null, 'madeleine_settings_display( "social_options" );' )
    );
    
    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Feedburner and Analytics', 'madeleine' ),
      __( 'Feedburner and Analytics', 'madeleine' ),
      'update_core',
      'madeleine_analytics_options_page',
      create_function( null, 'madeleine_settings_display( "analytics_options" );' )
    );
    
    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Reviews', 'madeleine' ),
      __( 'Reviews', 'madeleine' ),
      'update_core',
      'madeleine_reviews_options_page',
      create_function( null, 'madeleine_settings_display( "reviews_options" );' )
    );
    
    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Custom CSS', 'madeleine' ),
      __( 'Custom CSS', 'madeleine' ),
      'update_core',
      'madeleine_css_options_page',
      create_function( null, 'madeleine_settings_display( "css_options" );' )
    );

    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Popularity Plugin', 'madeleine' ),
      __( 'Popularity Plugin', 'madeleine' ),
      'update_core',
      'madeleine_popularity_options_page',
      create_function( null, 'madeleine_settings_display( "popularity_options" );' )
    );
    
    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Header & Background', 'madeleine' ),
      __( 'Header & Background', 'madeleine' ),
      'update_core',
      'madeleine_header_and_background_options_page',
      create_function( null, 'madeleine_settings_display( "header_and_background_options" );' )
    );
    
    add_submenu_page(
      'madeleine_home_options_page',
      __( 'Colors', 'madeleine' ),
      __( 'Colors', 'madeleine' ),
      'update_core',
      'madeleine_colors_options_page',
      create_function( null, 'madeleine_settings_display( "colors_options" );' )
    );
  }
}
// add_action( 'admin_menu', 'madeleine_theme_settings' );
*/


/*if ( !function_exists( 'madeleine_settings_display' ) ) {
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
        elseif ( $active_tab == 'header_and_background_options' ):
          $active_tab = 'header_and_background_options';
        elseif ( $active_tab == 'colors_options' ):
          $active_tab = 'colors_options';
        elseif ( $active_tab == 'popularity_options' ):
          $active_tab = 'popularity_options';
        else:
          $active_tab = 'home_options';
        endif;
        ?>
        <h2 class="nav-tab-wrapper">
          <a href="?page=madeleine_home_options_page" class="nav-tab <?php echo $active_tab == 'home_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Home Options', 'madeleine' ); ?></a>
          <a href="?page=madeleine_social_options_page" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Features', 'madeleine' ); ?></a>
          <a href="?page=madeleine_analytics_options_page" class="nav-tab <?php echo $active_tab == 'analytics_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Feedburner &amp; Analytics', 'madeleine' ); ?></a>
          <a href="?page=madeleine_reviews_options_page" class="nav-tab <?php echo $active_tab == 'reviews_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Reviews', 'madeleine' ); ?></a>
          <a href="?page=madeleine_css_options_page" class="nav-tab <?php echo $active_tab == 'css_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Custom CSS', 'madeleine' ); ?></a>
          <a href="?page=madeleine_popularity_options_page" class="nav-tab <?php echo $active_tab == 'popularity_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Popularity Plugin', 'madeleine' ); ?></a>
          <a href="?page=madeleine_header_and_background_options_page" class="nav-tab <?php echo $active_tab == 'header_and_background_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Header &amp; Background', 'madeleine' ); ?></a>
          <a href="?page=madeleine_colors_options_page" class="nav-tab <?php echo $active_tab == 'colors_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Colors', 'madeleine' ); ?></a>
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
          elseif( $active_tab == 'popularity_options' ):
            settings_fields( 'madeleine_popularity_options_group' );
            do_settings_sections( 'madeleine_popularity_options_page' );
          elseif( $active_tab == 'header_and_background_options' ):
            $html = '<h3>Header</h3>';
            $html .= '<p>You can customize the <strong>header logo, title, subtitle, and color</strong> on the <a href="' . get_admin_url() . '/themes.php?page=custom-header">Custom Header</a> page or use the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a>.</p>';
            $html .= '<h3>Background</h3>';
            $html .= '<p>You can customize the <strong>background image, color, position, repeat, and attachment</strong> on the <a href="' . get_admin_url() . '/themes.php?page=custom-background">Custom Background</a>  or use the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a>.</p>';
            echo $html;
          elseif( $active_tab == 'colors_options' ):
            $html = '<h3>Category colors</h3>';
            $html .= '<p>If a category is a <strong>top-level category</strong> (i.e. it has no parent category), you can set a color for that category and its children.<br>
             Just go to the <a href="' . get_admin_url() . 'edit-tags.php?taxonomy=category">Categories main page</a> and browse to a category\'s edit page to choose a color.</p>';
            $html .= '<h3>Main color</h3>';
            $html .= '<p>The Main color is the default color for categories, as well as the color of various elements of the website (calendar, top border, entry titles...).<br>
            You can set it in the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a></p>';
            $html .= '<h3>Reviews color</h3>';
            $html .= '<p>The Reviews section of the website has also its own color.<br>
            You can set it in the <a href="' . get_admin_url() . 'customize.php">WordPress Customizer</a></p>';
            echo $html;
          else:
            settings_fields( 'madeleine_home_options_group' );
            do_settings_sections( 'madeleine_home_options_page' );
          endif;
          if ( $active_tab != 'header_and_background_options' && $active_tab != 'colors_options' )
            submit_button();
        ?>
      </form>
    </div>
  <?php
  }
}*/


// Load the different settings pages


// require_once( SETTINGS_DIR .'/pages/home-options.php' );
// require_once( SETTINGS_DIR .'/pages/social-options.php' );
// require_once( SETTINGS_DIR .'/pages/analytics-options.php' );
// require_once( SETTINGS_DIR .'/pages/reviews-options.php' );
// require_once( SETTINGS_DIR .'/pages/css-options.php' );
require_once( SETTINGS_DIR .'/pages/popularity-options.php' );
// require_once( SETTINGS_DIR .'/pages/examples-options.php' );


?>