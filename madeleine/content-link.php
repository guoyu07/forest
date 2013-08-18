<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <p class="entry-format">Link</p>
  <p class="entry-title">
    <?php $link_url = get_post_meta( get_the_ID(), 'link_url', true ); ?>
    <a href="<?php echo $link_url; ?>"><?php the_title(); ?> &rarr;</a>
  </p>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
  <p class="entry-permalink">
  	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">#</a>
  </p>
  <div style="clear: both;"></div>
</article>