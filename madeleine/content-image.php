<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php madeleine_thumbnail( 'large' ); ?>
  <p class="entry-title">
    <?php the_title(); ?>
  </p>
  <p class="entry-permalink">
  	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">#</a>
  </p>
</article>