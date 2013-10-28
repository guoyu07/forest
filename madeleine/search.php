<?php get_header(); ?>
<div id="level-main" class="level">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<hgroup class="heading">
				<h1 class="title">
					<em><?php _e( 'Search', 'madeleine' ); ?></em>: <strong><?php the_search_query(); ?></strong>
				</h1>
			</hgroup>
			<?php $layout = madeleine_layout( 'search' ); ?>
		<?php else: ?>
			<hgroup class="heading">
				<h1 class="title">
					<em><?php _e( 'Nothing found for', 'madeleine' ); ?></em> <strong><?php the_search_query(); ?></strong>
				</h1>
			</hgroup>
			<div id="lead">
				<div class="no-search-results">
					<p><?php _e( 'Try another search', 'madeleine' ); ?></p>
					<?php get_search_form(); ?>
					<p><?php _e( 'Or browse the most popular tags:', 'madeleine' ); ?></p>
					<?php wp_tag_cloud('largest=20&smallest=12&unit=px'); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php madeleine_sidebar( $layout ); ?>
		<div style="clear: both;"></div>
	</div>
</div>
<?php get_footer(); ?>