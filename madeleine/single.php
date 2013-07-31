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
    <?php madeleine_breadcrumb(); ?>
  </div>
</div>
<div id="full">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <?php if ( comments_open() && ! post_password_required() ) : ?>
              <div class="entry-comments">
                <?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
              </div>
            <?php endif; ?>

            <h1 class="entry-title"><?php the_title(); ?></h1>

            <?php if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) : // Hide category text when not supported ?>
                <?php $categories_list = get_the_category_list( '</li><li>' ); ?>
                <?php if ( $categories_list ): ?>
                  <ul class="entry-categories">
                    <li><?php printf( $categories_list ); ?></li>
                  </ul>
                <?php endif; // End if categories ?>
            <?php endif; // End if is_object_in_taxonomy( get_post_type(), 'category' ) ?>
            
            <?php if ( 'post' == get_post_type() ) : ?>
              <div class="entry-info">
                <?php madeleine_posted_on(); ?>
              </div>
            <?php endif; ?>
          </header>

          <?php the_post_thumbnail( 'large' ); ?>

          <div class="entry-content">
            <?php the_content( 'Continue reading <span class="meta-nav">&rarr;</span>' ); ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>Pages:</span>', 'after' => '</div>' ) ); ?>
          </div>

          <?php if ( is_object_in_taxonomy( get_post_type(), 'post_tag' ) ) : // Hide tag text when not supported ?>
            <?php $tags_list = get_the_tag_list( '<li>', '</li><li>', '</li>' ); ?>
            <?php if ( $tags_list ): ?>
              <ul class="entry-tags">
                <?php printf( $tags_list ); ?>
              </ul>
            <?php endif; // End if $tags_list ?>
          <?php endif; // End if is_object_in_taxonomy( get_post_type(), 'post_tag' ) ?>
        </article>
      <?php endwhile; ?>
    <?php endif; ?>
    <?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>