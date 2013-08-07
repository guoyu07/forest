<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <p class="entry-format">Image</p>
  <?php if ( is_category() ): ?>
    <?php madeleine_thumbnail( 'large' ); ?>
  <?php else: ?>
    <?php madeleine_thumbnail( 'medium' ); ?>
  <?php endif; ?>
  <p class="entry-title">
    <?php the_title(); ?>
  </p>
  <p class="entry-permalink">
  	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">#</a>
  </p>
</article>