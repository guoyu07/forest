<div class="pinterest">
  <?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'content', get_post_format() ); ?>
    <!--article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php $format = ( get_post_format() != '' ? get_post_format() : 'standard' ); ?>
      <p class="entry-format"><?php echo $format; ?></p>
      <?php madeleine_entry_thumbnail( 'medium' ); ?>
      <h2 class="entry-title">
        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
      </h2>
      <?php if ( 'post' == get_post_type() ) : ?>
        <div class="entry-info">
          <?php madeleine_entry_info(); ?>
        </div>
      <?php endif; ?>
      <div class="entry-summary">
        <?php the_excerpt(); ?>
      </div>
    </article-->
  <?php endwhile; ?>
</div>
<?php madeleine_pagination(); ?>