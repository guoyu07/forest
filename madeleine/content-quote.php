<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php madeleine_entry_category(); ?>
	<p class="entry-format"><?php _e( 'Quote', 'madeleine' ); ?></p>
	<blockquote class="entry-title">
		<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'madeleine' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
	</blockquote>
	<div class="entry-source">
		<?php echo get_post_meta( get_the_ID(), '_madeleine_quote_source', true ); ?>
	</div>
	<div class="entry-info">
		<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="entry-comments">
				<?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
			</div>
		<?php endif; ?>
		<?php madeleine_entry_info(); ?>
	</div>
	<div style="clear: both;"></div>
</article>