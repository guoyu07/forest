<div class="board">
	<?php while ( have_posts() ) : the_post(); ?>
		<div <?php post_class(); ?>>
			<?php madeleine_entry_thumbnail( 'thumbnail' ); ?>
			<h2 class="entry-title">
				<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'madeleine' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>
		</div>'
	<?php endwhile; ?>
</div>