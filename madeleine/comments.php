<div id="comments" class="chapter">
	<?php if ( post_password_required() ) : ?>
			<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'madeleine' ); ?></p>
		</div>
		<?php return; ?>
	<?php	endif; ?>

	<?php if ( have_comments() ) : ?>
		<h3 id="comments-title">
			<?php printf( __( 'One comment', 'madeleine' ), __( '%1$s comments', 'madeleine' ), get_comments_number(),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ); ?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comments-navigation-above" class="comments-navigation">
				<div class="comment-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'madeleine' ) ); ?></div>
				<div class="comment-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'madeleine' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<ol class="comments-list">
			<?php wp_list_comments( array( 'callback' => 'madeleine_entry_comments' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comments-navigation-below" class="comments-navigation">
				<div class="comment-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'madeleine' ) ); ?></div>
				<div class="comment-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'madeleine' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="comments-closed"><?php _e( 'Comments are closed.', 'madeleine' ); ?></p>
		<?php endif; ?>

	<?php endif; ?>

	<?php comment_form(); ?>
</div>
