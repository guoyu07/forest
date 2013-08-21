<article id="post-<?php the_ID(); ?>" class="post review">
  <?php edit_post_link(); ?>
  <?php madeleine_entry_thumbnail( 'medium' ); ?>
  <?php $product_list = get_the_term_list( get_the_ID(), 'product', '</li><li>' ); ?>
  <?php $brand_list = get_the_term_list( get_the_ID(), 'brand', '</li><li>' ); ?>
  <?php if ( $product_list || $brand_list ): ?>
    <ul class="entry-category">
      <li><?php printf( $product_list ); ?></li>
      <li><?php printf( $brand_list ); ?></li>
    </ul>
  <?php endif; ?>
  <h2 class="entry-title">
    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
  </h2>
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
  <?php madeleine_entry_rating( get_the_ID() ); ?>
  <div style="clear: both;"></div>
</article>