<?php get_header(); ?>
<?php madeleine_category_breadcrumb(); ?>
<div id="main">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <div id="full">
        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php $parent = get_post_field( 'post_parent', get_the_ID() ); ?>
            <?php $link = get_permalink( $parent ); ?>
            <p class="entry-parent">
              <a href="<?php echo $link; ?>"><?php _e( '&larr; Back to post', 'madeleine' ); ?></a>
            </p>
            <header class="entry-header">
              <h1 class="entry-title">
                <?php
                if ( $post->post_excerpt )
                  the_excerpt();
                else
                  the_title();
                ?>
              </h1>
            </header>
            <div class="entry-content">
              <?php the_content(); ?>
            </div>
            <div class="entry-attachment">
            <?php if ( wp_attachment_is_image( $post->id ) ) : $att_image = wp_get_attachment_image_src( $post->id, 'full' ); ?>
              <a href="<?php echo wp_get_attachment_url( $post->ID ); ?>" title="<?php the_title(); ?>"><img src="<?php echo $att_image[0];?>"></a>
            <?php else : ?>
              <a href="<?php echo wp_get_attachment_url( $post->ID ) ?>" title="<?php echo esc_attr( get_the_title($post->ID), 1 ) ?>"><?php echo basename($post->guid) ?></a>
            <?php endif; ?>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>