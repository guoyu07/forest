<article id="post-<?php the_ID(); ?>" class="post review">
	<?php madeleine_entry_thumbnail( 'medium' ); ?>
	<?php $product_list = get_the_term_list( get_the_ID(), 'product', '<li>' ,'</li><li>', '</li>' ); ?>
	<?php $brand_list = get_the_term_list( get_the_ID(), 'brand', '<li>' ,'</li><li>', '</li>' ); ?>
	<?php if ( $product_list || $brand_list ): ?>
		<ul class="entry-category">
			<?php echo $brand_list; ?>
			<?php echo $product_list; ?>
		</ul>
	<?php endif; ?>
	<h2 class="entry-title">
		<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'madeleine' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
	</h2>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<div class="entry-info">
		<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="entry-comments">
				<?php comments_popup_link( '<span class="leave-reply">+</span>', '1', '%' ); ?>
			</div>
		<?php endif; ?>
		<?php madeleine_entry_info(); ?>
	</div>
	<?php madeleine_entry_rating( get_the_ID() ); ?>
	<?php madeleine_entry_price( get_the_ID() ); ?>
	<div style="clear: both;"></div>
</article>