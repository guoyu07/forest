<?php get_header(); ?>
<div id="main">
  <div class="wrap">
    <div id="lead">
      <?php if ( have_posts() ) : ?>
        <hgroup class="heading">
          <h1 class="title">
            <em>Search</em>: <strong><?php the_search_query(); ?></strong>
          </h1>
        </hgroup>
        <?php get_template_part( 'layout', 'list' ); ?>
      <?php else: ?>
        <hgroup class="heading">
          <h1 class="title">
            <em>Nothing found for</em> <strong><?php the_search_query(); ?></strong>
          </h1>
        </hgroup>
        <div class="entry-content content">
          <p>Try another search</p>
          <?php get_search_form(); ?>
          <p>Or browse the most popular tags:</p>
          <?php wp_tag_cloud('largest=20&smallest=12&unit=px'); ?>
        </div>
      <?php endif; ?>
    </div>
		<?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>