<?php get_header(); ?>
<div id="category">
  <div class="wrap">
    <?php madeleine_breadcrumb(); ?>
  </div>
</div>
<div id="main">
  <div class="wrap">
    <div id="lead">
      <?php if ( have_posts() ) : ?>
        <hgroup class="heading">
          <h1 class="title">
            <em><?php single_cat_title(); ?></em>
            <?php
              $category_description = category_description();
              if ( ! empty( $category_description ) )
                echo $category_description;
            ?>
          </h1>
        </hgroup>        
        <?php get_template_part( 'layout', 'grid' ); ?>
      <?php endif; ?>
    </div>
		<?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>