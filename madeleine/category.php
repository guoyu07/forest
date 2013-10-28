<?php get_header(); ?>
<?php madeleine_category_breadcrumb(); ?>
<div id="level-main" class="level">
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
			<?php $layout = madeleine_layout( 'category' ); ?>
		<?php endif; ?>
		<?php madeleine_sidebar( $layout ); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>