<?php get_header(); ?>
<div id="main">
  <div class="wrap">
    <div id="lead">
      <?php if ( have_posts() ) : ?>
        <hgroup class="heading">
          <h1 class="title">
            <em><?php _e( 'Search', 'madeleine' ); ?></em>: <strong><?php the_search_query(); ?></strong>
          </h1>
        </hgroup>
        <?php get_template_part( 'layout', 'list' ); ?>
      <?php else: ?>
        <hgroup class="heading">
          <h1 class="title">
            <em><?php _e( 'Nothing found for', 'madeleine' ); ?></em> <strong><?php the_search_query(); ?></strong>
          </h1>
        </hgroup>
        <div class="entry-content no-search-results">
          <p><?php _e( 'Try another search', 'madeleine' ); ?></p>
          <?php get_search_form(); ?>
          <p><?php _e( 'Or browse the most popular tags:', 'madeleine' ); ?></p>
          <?php wp_tag_cloud('largest=20&smallest=12&unit=px'); ?>
        </div>
      <?php endif; ?>
    </div>
		<?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>