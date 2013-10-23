<?php get_header(); ?>
<div id="main">
	<div class="wrap">
		<div id="lead">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" class="post">
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header>
						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="pagination">', 'after' => '</div>', 'pagelink' => '<strong>%</strong>' ) ); ?>
						</div>
					</article>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>