<?php get_header(); ?>
<div id="main">
  <div class="wrap">
    <div id="lead">
      <?php if ( have_posts() ) : ?>
        <?php the_post(); ?>
        <hgroup class="heading">
          <p id="author-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 100 ); ?></p>
          <h1 class="title" id="author">
            <?php printf( ' %s', '<strong class="vcard">' . get_the_author() . '</strong>' ); ?>
          </h1>
          <?php if ( get_the_author_meta( 'description' ) ) : ?>
            <h2 class="subtitle" id="author-description">
              <?php the_author_meta( 'description' ); ?>
            </h2>
          <?php endif; ?>
          <div style="clear: left;"></div>
        </hgroup>
        <?php get_template_part( 'layout', 'list' ); ?>
      <?php endif; ?>
    </div>
		<?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>