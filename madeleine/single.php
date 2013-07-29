<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Madeleine
 * @since Madeleine 1.0
 */

get_header(); ?>
<div id="full">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_post_thumbnail( 'large' ); ?>
        <?php get_template_part( 'content', get_post_format() ); ?>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
	<div style="clear: both;"></div>
</div>
<?php get_footer(); ?>