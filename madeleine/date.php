<?php get_header(); ?>
<div id="level-main" class="level">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<hgroup class="heading">
				<h1 class="title" id="date">
					<?php madeleine_nested_date() ?>
				</h1>
				<div style="clear: left;"></div>
			</hgroup>
			<?php $layout = madeleine_layout( 'date' ); ?>
		<?php endif; ?>
		<?php madeleine_sidebar( $layout ); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>