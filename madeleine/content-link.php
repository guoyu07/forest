<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php madeleine_entry_category(); ?>
	<p class="entry-format"><?php _e( 'Link', 'madeleine' ); ?></p>
	<p class="entry-title">
		<?php $link_url = get_post_meta( get_the_ID(), '_madeleine_link_url', true ); ?>
		<?php the_title(); ?>
	</p>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<div class="entry-info">
		<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="entry-comments">
				<?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
			</div>
		<?php endif; ?>
		<a class="entry-permalink" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'madeleine' ), the_title_attribute( 'echo=0' ) ) ); ?>">#</a>
		<?php madeleine_entry_info(); ?>
	</div>
	<div style="clear: both;"></div>
</article>