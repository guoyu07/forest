<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php madeleine_thumbnail( 'medium' ); ?>
  <p class="entry-format">Video</p>
  <p class="entry-title">
    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( 'Permalink to %s', the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
  </p>
</article>