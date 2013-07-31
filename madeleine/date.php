<?php get_header(); ?>
<div id="full">
  <div class="wrap">
    <div id="main">
      <?php if ( have_posts() ) : ?>
        <p id="debug" style="display: none; margin: 20px;"><?php echo get_query_var('m'); ?></p>
        <hgroup class="heading">
          <h1 class="title" id="date">
            <?php madeleine_nested_date() ?>
            <?php // madeleine_date_archive() ?>
          </h1>
          <div style="clear: left;"></div>
        </hgroup>
        <?php get_template_part( 'layout', 'grid' ); ?>
      <?php endif; ?>
    </div>
		<?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>