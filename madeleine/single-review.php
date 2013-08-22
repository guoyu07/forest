<?php get_header(); ?>
<?php madeleine_reviews_breadcrumb(); ?>
<div id="main">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article id="review-<?php the_ID(); ?>" class="post review">
          <header class="entry-header">
            <?php $product_list = get_the_term_list( get_the_ID(), 'product', '<li>' ,'</li><li>', '</li>' ); ?>
            <?php $brand_list = get_the_term_list( get_the_ID(), 'brand', '<li>' ,'</li><li>', '</li>' ); ?>
            <?php if ( $product_list || $brand_list ): ?>
              <ul class="entry-category">
                <?php echo $brand_list; ?>
                <?php echo $product_list; ?>
              </ul>
            <?php endif; ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <div class="entry-summary">
              <?php the_excerpt(); ?>
            </div>
            <?php if ( comments_open() && ! post_password_required() ) : ?>
              <div class="entry-comments">
                <?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
              </div>
            <?php endif; ?>
            <div class="entry-info">
              <?php madeleine_entry_info(); ?>
            </div>
          </header>
          <div id="start" class="entry-thumbnail chapter">
            <?php the_post_thumbnail( 'panorama' ); ?>
          </div>
          <div class="entry-text">
            <div class="entry-content">
              <?php the_content(); ?>
              <?php wp_link_pages( array( 'before' => '<div class="pagination">', 'after' => '</div>', 'pagelink' => '<strong>%</strong>' ) ); ?>
            </div>
            <div id="verdict" class="entry-verdict chapter">
              <div class="entry-rating">
                <?php madeleine_entry_rating( get_the_ID() ); ?>
              </div>
              <?php madeleine_entry_verdict( get_the_ID() ); ?>
            </div>
            <?php comments_template( '', true ); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php endif; ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>