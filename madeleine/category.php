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
<div id="category">
  <div class="wrap">
    <strong><?php single_cat_title(); ?></strong>
    <?php
    $cat = get_query_var('cat');
    $args = array(
      'child_of'          => $cat,
      'hide_empty '       => 0,
      // 'show_option_none'  => '',
      'title_li'          => ''
    );
    ?>
    <ul>
      <?php wp_list_categories( $args ); ?>
    </ul>
  </div>
</div>
<div id="full">
  <div class="wrap">
    <div id="main">
      <?php get_template_part( 'layout', 'grid' ); ?>
    </div>
		<?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>