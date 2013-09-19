<aside id="sidebar" class="widget-area">
	<?php
	if ( is_page() ):
		echo '<ul id="pages">';
		wp_list_pages( 'title_li=' );
		echo '</ul>';
	else:
		dynamic_sidebar();
	endif;
	?>
</aside>