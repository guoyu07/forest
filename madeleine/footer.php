    <footer id="footer">
      <div class="wrap">
        <section>
          <h4 class="section"><?php _e( 'Navigation', 'madeleine' ); ?></h4>
          <ul>
            <li><a id="home" href="<?php echo esc_url( home_url() ); ?>"><?php _e( 'Home', 'madeleine' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"><?php _e( 'Reviews', 'madeleine' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>"><?php _e( 'Images', 'madeleine' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>"><?php _e( 'Videos', 'madeleine' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>"><?php _e( 'Quotes', 'madeleine' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'link' ) ); ?>"><?php _e( 'Links', 'madeleine' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_page_link( 2 ) ); ?>"><?php _e( 'About', 'madeleine' ); ?></a></li>
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
          <ul>
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
          <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'madeleine' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'madeleine' ); ?>"><?php printf( __( 'Powered by %s', 'madeleine' ), 'WordPress' ); ?></a>.<br>
          Theme <a href="#">Madeleine</a> available on Theme Forest.<br>
          &copy; 2013 The Magazine Theme. All rights reserved.
        </p>
      </div>
    </footer>
    <?php wp_footer(); ?>
  </body>
</html>