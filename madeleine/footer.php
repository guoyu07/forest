    <footer id="footer">
      <div class="wrap">
        <?php do_action( 'twentyeleven_credits' ); ?>
        <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyeleven' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyeleven' ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentyeleven' ), 'WordPress' ); ?></a>
      </div>
    </footer>
    <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/global.js"></script>
    <?php if ( is_home() ): ?>
      <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/home.js"></script>
    <?php endif; ?>
    <?php if ( is_date() ): ?>
      <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/date.js"></script>
    <?php endif; ?>
    <?php if ( is_post_type_archive( 'review' ) || is_tax() ): ?>
      <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/reviews.js"></script>
    <?php endif; ?>
    <?php if ( is_singular( 'review' ) ): ?>
      <script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/jump.js"></script>
    <?php endif; ?>
    <?php wp_footer(); ?>
  </body>
</html>