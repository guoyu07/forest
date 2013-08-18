<div class="board">
  <?php while ( have_posts() ) : the_post(); ?>
    <div <?php post_class(); ?>>
      <?php madeleine_thumbnail( 'thumbnail' ); ?>
      <h2 class="entry-title">
        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
      </h2>
    </div>'
  <?php endwhile; ?>
</div>