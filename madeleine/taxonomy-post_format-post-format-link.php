<?php get_header(); ?>
<div id="level-main" class="level">
	<div class="wrap">
		<div id="lead">
			<?php if ( have_posts() ) : ?>
				<hgroup class="heading">
					<h1 class="title"><em><?php _e( 'Links', 'madeleine' ); ?></em></h1>
				</hgroup>
				<?php get_template_part( 'layout', 'list' ); ?>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>