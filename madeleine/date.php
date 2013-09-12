<?php get_header(); ?>
<div id="main">
	<div class="wrap">
		<div id="lead">
			<?php if ( have_posts() ) : ?>
				<hgroup class="heading">
					<h1 class="title" id="date">
						<?php madeleine_nested_date() ?>
					</h1>
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