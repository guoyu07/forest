<?php get_header(); ?>
<div id="category">
  <div class="wrap">
    <?php madeleine_breadcrumb(); ?>
  </div>
</div>
<div id="main">
  <div class="wrap">
    <div id="lead">
      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
              <?php if ( comments_open() && ! post_password_required() ) : ?>
                <div class="entry-comments">
                  <?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
                </div>
              <?php endif; ?>

              <h1 class="entry-title"><?php the_title(); ?></h1>

              <?php if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) : ?>
                <?php $categories_list = get_the_category_list( '</li><li>' ); ?>
                <?php if ( $categories_list ): ?>
                  <ul class="entry-category">
                    <li><?php printf( $categories_list ); ?></li>
                  </ul>
                <?php endif; ?>
              <?php endif; ?>
              
              <?php if ( 'post' == get_post_type() ) : ?>
                <div class="entry-info">
                  <?php madeleine_posted_on(); ?>
                </div>
              <?php endif; ?>
            </header>

            <?php the_post_thumbnail( 'large' ); ?>

            <?php
            $format = get_post_format();
            if ( $format == 'video' ):
              madeleine_video();
            endif;
            ?>

            <div class="entry-content">
              <?php the_content(); ?>
              <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>Pages:</span>', 'after' => '</div>' ) ); ?>
            </div>

            <?php if ( is_object_in_taxonomy( get_post_type(), 'post_tag' ) ) : ?>
              <?php $tags_list = get_the_tag_list( '<li>', '</li><li>', '</li>' ); ?>
              <?php if ( $tags_list ): ?>
                <ul class="entry-tags">
                  <?php printf( $tags_list ); ?>
                </ul>
              <?php endif; ?>
            <?php endif; ?>

            <?php comments_template( '', true ); ?>
          </article>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
    <?php get_sidebar(); ?>
    <div style="clear: both;"></div>
  </div>
</div>
<?php get_footer(); ?>