<?php get_header(); ?>
<?php madeleine_category_breadcrumb(); ?>
<div id="main">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php $format = get_post_format(); ?>
				<?php // edit_post_link(); ?>
				<div <?php post_class( 'entry-article' ); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-bar">
							<?php if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) : ?>
								<?php $category_list = get_the_category_list( '</li><li>' ); ?>
								<?php if ( $category_list ): ?>
									<ul class="entry-category">
										<li><?php printf( $category_list ); ?></li>
									</ul>
								<?php endif; ?>
							<?php endif; ?>
							<div class="entry-info">
								<?php if ( $format ): ?>
									<a class="entry-format" href="<?php echo esc_url( home_url( '/' ) . '/type/' . $format ); ?>"><?php echo $format; ?></a>
								<?php endif; ?>
								<?php if ( comments_open() && ! post_password_required() ) : ?>
									<div class="entry-comments">
										<?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
									</div>
								<?php endif; ?>
								<?php madeleine_entry_info(); ?>
								<div style="clear: both;"></div>
							</div>
						</div>
					</header>
				</div>
				<div id="lead">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-article' ); ?>>
						<?php
						if ( has_post_thumbnail() ):
							if ( $format == 'video' ):
								madeleine_entry_video();
							elseif ( $format == 'image' ):
								echo '<div class="entry-thumbnail">';
								echo wp_get_attachment_link( get_post_thumbnail_id(), 'full', true );
								echo '</div>';
							else:
								echo '<div class="entry-thumbnail">';
								the_post_thumbnail( 'large' );
								echo '</div>';
							endif;
						endif;
						?>
						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="pagination">', 'after' => '</div>', 'pagelink' => '<strong>%</strong>' ) ); ?>
						</div>
						<?php if ( is_object_in_taxonomy( get_post_type(), 'post_tag' ) ) : ?>
							<?php $tags_list = get_the_tag_list( '<li>', '</li><li>', '</li>' ); ?>
							<?php if ( $tags_list ): ?>
								<ul class="entry-tags">
									<?php printf( $tags_list ); ?>
								</ul>
								<div style="clear: left;"></div>
							<?php endif; ?>
						<?php endif; ?>
						
						<?php madeleine_entry_share(); ?>
						<?php comments_template( '', true ); ?>
					</article>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
		<?php get_sidebar(); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>