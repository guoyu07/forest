<?php get_header(); ?>
<?php madeleine_category_breadcrumb(); ?>
<div id="main">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <div id="full">
        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            <div class="entry-attachment">
            <?php if ( wp_attachment_is_image( $post->id ) ) : $att_image = wp_get_attachment_image_src( $post->id, 'full' ); ?>
              <a href="<?php echo wp_get_attachment_url($post->id); ?>" title="<?php the_title(); ?>" rel="attachment"><img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>"  class="attachment-medium" alt="<?php $post->post_excerpt; ?>" /></a>
            <?php else : ?>
              <a href="<?php echo wp_get_attachment_url($post->ID) ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo basename($post->guid) ?></a>
            <?php endif; ?>
            </div>
            <div class="entry-content">
              <?php the_content(); ?>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>