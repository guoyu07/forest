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
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
  <div id="debug" style="position: absolute; right: 10px; top: 40px;">
    <a href="/forest/">Home</a>
    <a href="/forest/category/social-media">Category</a>
    <a href="/forest/tag/apple">Tag</a>
    <a href="/forest/author/jt">Author</a>
    <a href="/forest/type/image">Format</a>
    <a href="/forest/">Search</a>
  </div>
  <header id="header">
    <div class="wrap">
      <a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
        <hgroup>
          <h1 id="title">
            <?php bloginfo( 'name' ); // echo $_SERVER['REQUEST_URI']; ?>
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
            <li><a id="quotes" href="<?php echo esc_url( home_url( '/' ) . '/type/quote' ); ?>">Quotes</a></li>
            <li><a id="links" href="<?php echo esc_url( home_url( '/' ) . '/type/link' ); ?>">Links</a></li>
            <li><a id="videos" href="<?php echo esc_url( home_url( '/' ) . '/type/video' ); ?>">Videos</a></li>
            <li><a id="images" href="<?php echo esc_url( home_url( '/' ) . '/type/image' ); ?>">Images</a></li>
          </ul>
        </nav>
        <div id="trending">
          <p>Trending</p>
          <ul>
            <li><a>San Diego Comic-Con</a></li>
            <li><a>Sony PlayStation 4</a></li>
            <li><a>Microsoft Xbox One</a></li>
            <li><a>Hollywood</a></li>
            <li><a>NSA surveillance</a></li>
            <li><a>Samsung Galaxy S4</a></li>
            <li><a>Google Glass</a></li>
            <li><a>Best New Apps</a></li>
            <li><a>Verge Favorites</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
