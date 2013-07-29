<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
    <footer id="footer" role="contentinfo">
    	<div class="wrap">
    		<?php do_action( 'twentyeleven_credits' ); ?>
    		<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyeleven' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyeleven' ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentyeleven' ), 'WordPress' ); ?></a>
    	</div>
    </footer><!-- #footer -->
    <?php wp_footer(); ?>
  </body>
</html>