<?php get_header(); ?>
<div id="level-main" class="level">
	<div class="wrap">
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
			<?php $layout = madeleine_layout( 'author' ); ?>
		<?php endif; ?>
		<?php madeleine_sidebar( $layout ); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>