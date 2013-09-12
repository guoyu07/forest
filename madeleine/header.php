<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
  	/*
  	 * Print the <title> tag based on what is being viewed.
  	 */
  	global $page, $paged;
  	wp_title( '|', true, 'right' );

  	// Add the blog name.
  	bloginfo( 'name' );

  	// Add the blog description for the home/front page.
  	$site_description = get_bloginfo( 'description', 'display' );
  	if ( $site_description && ( is_home() || is_front_page() ) )
  		echo " | $site_description";

  	// Add a page number if necessary:
  	if ( $paged >= 2 || $page >= 2 )
  		echo ' | ' . sprintf( 'Page %s', max( $paged, $page ) );

  	?>
  </title>
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header id="header">
    <div class="wrap">
      <a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
        <hgroup>
          <h1 id="title">
            <?php bloginfo( 'name' ); ?>
          </h1>
          <h2 id="description"><?php bloginfo( 'description' ); ?></h2>
        </hgroup>
      </a>
      <br>
      <ul id="social-icons">
        <li class="social-rss"><a href="<?php bloginfo( 'rss2_url' ); ?>"></a></li>
        <?php madeleine_social_links(); ?>
      </ul>
      <?php get_search_form(); ?>
      <div id="navigation">
        <a id="nav-icon"><span class="icon icon-menu"></span>Navigation</a>
        <div id="nav-menu">
          <nav id="nav">
            <ul>
              <li><a class="nav-home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Home" rel="home"><span class="icon icon-home"></span><?php _e( 'Home', 'madeleine' ); ?></a></li>
              <?php echo madeleine_categories_list(); ?>
              <?php madeleine_reviews_link(); ?>
              <?php madeleine_format_icons(); ?>
            </ul>
            <div style="clear: left;"></div>
          </nav>
          <div id="trending">
            <p><?php _e( 'Trending', 'madeleine' ); ?></p>
            <ul>
              <?php madeleine_trending(); ?>
            </ul>
            <div style="clear: left;"></div>
          </div>
        </div>
      </div>
    </div>
  </header>
