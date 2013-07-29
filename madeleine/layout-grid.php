<div class="grid">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php the_post_thumbnail( 'medium' ); ?>
        <h2 class="entry-title">
          <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <?php if ( 'post' == get_post_type() ) : ?>
          <div class="entry-meta">
            <?php twentyeleven_posted_on(); ?>
          </div><!-- .entry-meta -->
        <?php endif; ?>
        <div class="entry-summary">
          <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
      </article>
    <?php endwhile; ?>
  <?php endif; ?>
</div>