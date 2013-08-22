<?php get_header(); ?>
<div id="main">
  <div class="wrap">
    <div id="full">
      <?php if ( have_posts() ) : ?>
        <hgroup class="heading">
          <h1 class="title">Video</h1>
        </hgroup>
        <?php get_template_part( 'layout', 'list' ); ?>
      <?php endif; ?>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>