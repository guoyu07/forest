<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <p class="entry-format">Quote</p>
  <blockquote class="entry-title">
    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( 'Permalink to %s', the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">&#8220; <?php the_title(); ?> &#8221;</a>
  </blockquote>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
  <div style="clear: both;"></div>
</article>