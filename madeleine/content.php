<?php $class = ( is_sticky() ? 'sticky' : '' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
  <?php edit_post_link(); ?>
  <?php if ( is_archive() ): ?>
    <?php if ( is_sticky() ) : ?>
      <?php madeleine_thumbnail( 'large' ); ?>
    <?php else : ?>
      <?php madeleine_thumbnail( 'medium' ); ?>
    <?php endif; ?>
  <?php else : ?>
    <?php madeleine_thumbnail( 'medium' ); ?>
  <?php endif; ?>
  <?php $categories_list = get_the_category_list( '</li><li>' ); ?>
  <?php if ( $categories_list ): ?>
    <ul class="entry-category">
      <li><?php printf( $categories_list ); ?></li>
    </ul>
  <?php endif; ?>
  <h2 class="entry-title">
    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
  </h2>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
  <?php if ( comments_open() && ! post_password_required() ) : ?>
    <div class="entry-comments">
      <?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
    </div>
  <?php endif; ?>
  <div class="entry-info">
    <?php madeleine_posted_on(); ?>
  </div>
  <div style="clear: both;"></div>
</article>