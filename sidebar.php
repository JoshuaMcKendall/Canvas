<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 * @version 3.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

?>

<aside id="top-blog-sidebar" class="widget-area column col-xs-12 col-sm-12 col-md-12 col-lg-pull-4" role="complementary" aria-label="<?php esc_attr_e( 'Top Blog Sidebar', 'canvas' ); ?>">

	<?php dynamic_sidebar( 'sidebar-1' ); ?>	
	
</aside><!-- #blog-sidebar -->