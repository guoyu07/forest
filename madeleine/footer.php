    <footer id="footer">
      <div class="wrap">
        <section>
          <h4 class="section">Navigation</h4>
          <ul>
            <li><a id="home" href="<?php echo esc_url( home_url() ); ?>">Home</a></li>
            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>">Reviews</a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>">Images</a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>">Videos</a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>">Quotes</a></li>
            <li><a href="<?php echo esc_url( get_post_format_link( 'link' ) ); ?>">Links</a></li>
            <li><a href="<?php echo esc_url( get_page_link( 2 ) ); ?>">About</a></li>
          </ul>
        </section>
        <section>
          <h4 class="section">Categories</h4>
          <ul>
            <?php madeleine_categories_list(); ?>
          </ul>
        </section>
        <section>
          <h4 class="section">Trending</h4>
          <ul>
            <?php madeleine_trending( 7 ); ?>
          </ul>
        </section>
        <section>
          <h4 class="section">Social</h4>
          <ul>
            <li><a href="#">Facebook</a></li>
            <li><a href="#">Twitter</a></li>
            <li><a href="#">Google +</a></li>
            <li><a href="#">Tumblr</a></li>
            <li><a href="#">YouTube</a></li>
            <li><a href="#">RSS</a></li>
            <li><a href="#">Email</a></li>
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
          <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyeleven' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyeleven' ); ?>"><?php printf( __( 'Powered by %s', 'twentyeleven' ), 'WordPress' ); ?></a>.<br>
          Theme <a href="#">Madeleine</a> available on Theme Forest.<br>
          &copy; 2013 The Magazine Theme. All rights reserved.
        </p>
      </div>
    </footer>
    <?php wp_footer(); ?>
  </body>
</html>