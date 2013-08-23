<?php get_header(); ?>
<div id="main">
  <div class="wrap">
    <div id="full">
      <?php if ( have_posts() ) : ?>
        <hgroup class="heading">
          <h1 class="title">
            <em style="float: left;">Tag:</em> <?php madeleine_tags_list(); ?>
            <div style="clear: left;"></div>
          </h1>
        </hgroup>
        <?php get_template_part( 'layout', 'pinterest' ); ?>
      <?php endif; ?>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>