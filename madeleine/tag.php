<?php get_header(); ?>
<div id="main">
  <div class="wrap">
    <div id="lead">
      <?php if ( have_posts() ) : ?>
        <hgroup class="heading">
          <h1 class="title">
            <em>Tag:</em> <strong><?php single_cat_title(); ?></strong>
          </h1>
        </hgroup>
        <?php get_template_part( 'layout', 'list' ); ?>
      <?php endif; ?>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>