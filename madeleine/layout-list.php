<div class="list">
	<?php while ( have_posts() ) : the_post();
		$post_type = get_post_type();
		if ( $post_type == 'review' )
			get_template_part( 'content', 'review' );
		else
			get_template_part( 'content', get_post_format() );
	endwhile; ?>
</div>