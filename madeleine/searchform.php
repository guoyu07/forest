<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="search-text" name="s" placeholder="<?php _e( 'Search', 'madeleine' ); ?>" value="<?php echo esc_attr( the_search_query() ); ?>">
	<input type="submit" class="search-submit" value="">
</form>