<?php get_header(); ?>
<div id="level-main" class="level">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<hgroup class="heading">
				<h1 class="title">
					<?php madeleine_tags_list(); ?>
					<div style="clear: left;"></div>
				</h1>
			</hgroup>
			<?php $layout = madeleine_layout( 'tag' ); ?>
		<?php endif; ?>
		<?php madeleine_sidebar( $layout ); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>