<?php get_header(); ?>
<?php madeleine_category_breadcrumb(); ?>
<div id="main">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<hgroup class="heading">
				<h1 class="title">
					<em><?php single_cat_title(); ?></em>
					<?php
						$category_description = category_description();
						if ( ! empty( $category_description ) )
							echo $category_description;
					?>
				</h1>
			</hgroup>
			<div id="lead">
				<?php get_template_part( 'layout', 'grid' ); ?>
				<?php madeleine_pagination(); ?>
			</div>
		<?php endif; ?>
		<?php get_sidebar(); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>