<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if ( is_category() ): ?>
    <?php if ( is_sticky() ) : ?>
      <?php madeleine_thumbnail( 'large' ); ?>
    <?php else : ?>
      <?php madeleine_thumbnail( 'thumbnail' ); ?>
    <?php endif; ?>
  <?php else : ?>
    <?php madeleine_thumbnail( 'medium' ); ?>
  <?php endif; ?>
  <h2 class="entry-title">
    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
  </h2>
  <div class="entry-info">
    <?php madeleine_posted_on(); ?>
  </div>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
  <div style="clear: left;"></div>
</article>