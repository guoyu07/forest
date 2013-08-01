<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <blockquote class="entry-title">
    &#8220; <?php the_title(); ?> &#8221;
  </blockquote>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
  <p class="entry-permalink">
  	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">#</a>
  </p>
</article>