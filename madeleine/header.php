<!doctype html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	// wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		echo " | $site_description";
  }

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		echo ' | ' . sprintf( 'Page %s', max( $paged, $page ) );
  }

	?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <div id="debug" style="position: absolute; right: 10px; top: 20px;">
    <a href="/forest/">Home</a>
    <a href="/forest/category/social-media">Category</a>
    <a href="/forest/tag/apple">Tag</a>
    <a href="/forest/author/jt">Author</a>
    <a href="/forest/type/image">Format</a>
    <a href="/forest/?s=lorem">Search</a>
  </div>
  <p id="value" style="left: 20px; position: fixed; top: 40px;"></p>
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
      <?php get_search_form(); ?>
      <div id="navigation">
        <a href="#" id="today-news">
          <strong>12</strong>
          <em>news today</em>
        </a>
        <nav id="nav">
          <ul>
            <li><a id="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="Home" rel="home">Home</a></li>
            <?php madeleine_categories_list(); ?>
            <li><a href="<?php echo get_post_type_archive_link( 'review' ); ?>">Reviews</a></li>
            <li><a class="quotes" href="<?php echo esc_url( home_url( '/' ) . '/type/quote' ); ?>">Quotes</a></li>
            <li><a class="links" href="<?php echo esc_url( home_url( '/' ) . '/type/link' ); ?>">Links</a></li>
            <li><a class="videos" href="<?php echo esc_url( home_url( '/' ) . '/type/video' ); ?>">Videos</a></li>
            <li><a class="images" href="<?php echo esc_url( home_url( '/' ) . '/type/image' ); ?>">Images</a></li>
          </ul>
        </nav>
        <div id="trending">
          <p>Trending</p>
          <ul>
            <?php madeleine_trending(); ?>
          </ul>
        </div>
      </div>
    </div>
  </header>
