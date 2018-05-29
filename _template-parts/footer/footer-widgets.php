<?php
/**
 * Displays footer widgets if assigned
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 * @version 3.0.0
 */

?>

<div class="container">

	<div class="row">

<?php if ( is_active_sidebar( 'footer-widget-area-1' ) ) { ?>

	<div id="footer-widget-area-1" class="widget-area column col-xs-12 col-sm-12 col-md-6 col-lg-4" role="complementary" aria-label="<?php esc_attr_e( 'Footer Widgets', 'canvas' ); ?>">
		<?php dynamic_sidebar( 'footer-widget-area-1' ); ?>	
	</div><!-- #footer-widget-area-1 -->

<?php } ?>

<?php if ( is_active_sidebar( 'footer-widget-area-2' ) ) { ?>

	<div id="footer-widget-area-2" class="widget-area column col-xs-12 col-sm-12 col-md-6 col-lg-4" role="complementary" aria-label="<?php esc_attr_e( 'Footer Widgets', 'canvas' ); ?>">
		<?php dynamic_sidebar( 'footer-widget-area-2' ); ?>	
	</div><!-- #footer-widget-area-2 -->

<?php } ?>

<?php if ( is_active_sidebar( 'footer-widget-area-3' ) ) { ?>

	<div id="footer-widget-area-3" class="widget-area column col-xs-12 col-sm-12 col-md-6 col-lg-4" role="complementary" aria-label="<?php esc_attr_e( 'Footer Widgets', 'canvas' ); ?>">
		<?php dynamic_sidebar( 'footer-widget-area-3' ); ?>	
	</div><!-- #footer-widget-area-3 -->

<?php } ?>

<?php if ( is_active_sidebar( 'footer-widget-area-4' ) ) { ?>

	<div id="footer-widget-area-4" class="widget-area column col-xs-12 col-sm-12 col-md-pull-6 col-lg-pull-4" role="complementary" aria-label="<?php esc_attr_e( 'Footer Widgets', 'canvas' ); ?>">
		<?php dynamic_sidebar( 'footer-widget-area-4' ); ?>	
	</div><!-- #footer-widget-area-4 -->

<?php } ?>

	</div>
	
</div>