		<footer id="footer">
			<div class="wrap">
				<section>
					<h4 class="section"><?php _e( 'Navigation', 'madeleine' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url() ); ?>"><?php _e( 'Home', 'madeleine' ); ?></a></li>
						<?php madeleine_format_list(); ?>
						<?php madeleine_reviews_link(); ?>
						<?php if ( has_nav_menu( 'footer-menu') ) : ?>
							<?php wp_nav_menu( array( 'container_id' => 'footer-menu', 'theme_location' => 'footer-menu' ) ); ?>
						<?php endif; ?>
					</ul>
				</section>
				<section>
					<h4 class="section"><?php _e( 'Categories', 'madeleine' ); ?></h4>
					<ul>
						<?php echo madeleine_categories_list(); ?>
					</ul>
				</section>
				<section>
					<h4 class="section"><?php _e( 'Trending', 'madeleine' ); ?></h4>
					<ul>
						<?php madeleine_trending( 7 ); ?>
					</ul>
				</section>
				<section>
					<h4 class="section"><?php _e( 'Social', 'madeleine' ); ?></h4>
					<ul id="social-links">
						<?php madeleine_social_links(); ?>
						<li><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS', 'madeleine' ); ?></a></li>
						<li><a href="#"><?php _e( 'Email', 'madeleine' ); ?></a></li>
					</ul>
				</section>
				<?php get_search_form(); ?>
				<hgroup id="footer-logo">
					<h1 id="footer-title">
						<?php bloginfo( 'name' ); ?>
					</h1>
					<h2 id="footer-description"><?php bloginfo( 'description' ); ?></h2>
				</hgroup>
				<p id="footer-about">
					<a href="<?php echo esc_url( 'http://wordpress.org/' ); ?>"><?php printf( __( 'Powered by %s', 'madeleine' ), 'WordPress' ); ?></a>.<br>
					Theme <a href="#">Madeleine</a> available on Theme Forest.<br>
					&copy; 2013 The Magazine Theme. All rights reserved.
				</p>
			</div>
		</footer>
		<?php wp_footer(); ?>
	</body>
</html>