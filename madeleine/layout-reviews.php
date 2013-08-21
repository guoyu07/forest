<div id="reviews">
  <hgroup class="heading">
    <h1 class="title">
      <?php $reviews_count = wp_count_posts( 'review' ); ?> 
      <em><?php echo $reviews_count->publish ?> reviews</em>
    </h1>
  </hgroup> 
  <?php madeleine_reviews_menu(); ?>
  <div id="lead">
    <?php if ( have_posts() ) : ?>
      <div class="list reviews-list">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php get_template_part( 'content', 'review' ); ?>
        <?php endwhile; ?>
        <?php madeleine_pagination(); ?>
      </div>
    <?php endif; ?>
  </div>
  <div style="clear: both;"></div>
</div>