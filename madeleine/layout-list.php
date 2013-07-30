<div class="list">
  <?php if ( have_posts() ) : ?>
    <?php madeleine_standard_posts(); ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if ( is_sticky() ) : ?>
          <?php madeleine_thumbnail( 'large' ); ?>
        <?php else : ?>
          <?php madeleine_thumbnail( 'thumbnail' ); ?>
        <?php endif; ?>
        <h2 class="entry-title">
          <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <?php if ( 'post' == get_post_type() ) : ?>
          <div class="entry-info">
            <?php madeleine_posted_on(); ?>
          </div>
        <?php endif; ?>
        <div class="entry-summary">
          <?php the_excerpt(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  <?php endif; ?>
</div>