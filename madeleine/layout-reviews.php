<div id="reviews">
  <div id="reviews-result">
    <?php if ( have_posts() ) : ?>
      <hgroup class="heading">
        <h1 class="title">
          <?php
            $reviews_count = $wp_query->found_posts;
            $title = $reviews_count . ' reviews';
            if ( $reviews_count == 1 )
              $title = '1 review';
          ?> 
          <em id="reviews-title"><?php echo $title ?></em>
        </h1>
      </hgroup> 
      <div id="lead">
        <div class="list reviews-list">
          <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'content', 'review' ); ?>
          <?php endwhile; ?>
          <?php madeleine_pagination(); ?>
        </div>
      </div>
    <?php else: ?>
      <hgroup class="heading">
        <h1 class="title">
          <em id="reviews-title">Sorry. No reviews match these parameters.</em>
        </h1>
      </hgroup> 
    <?php endif; ?>
  </div>
  <?php madeleine_reviews_menu(); ?>
  <div class="loading white"></div>
  <div style="clear: both;"></div>
</div>