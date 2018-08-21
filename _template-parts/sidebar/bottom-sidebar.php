<?php 
/**
 * Template part for displaying the bottom sidebar on blog pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Canvas
 * @since 1.0
 * @version 1.0
 */

?>

<aside id="bottom-blog-sidebar" class="widget-area column col-xs-12 col-sm-12 col-md-12 col-lg-pull-4" role="complementary" aria-label="<?php esc_attr_e( 'Blog Sidebar', 'canvas' ); ?>">

	<div class="row row-unpadded">

		<?php dynamic_sidebar( 'sidebar-2' ); ?>	
		
	</div>

	<div class="blog-feed feed">

		<a href="<?php bloginfo('rss2_url'); ?>" aria-label="<?php esc_attr_e( 'RSS Feed', 'canvas' ); ?>" class="btn">
				

				<?php 

					echo canvas_get_svg_icon( array(

					 'icon' => 'rss',
					 'size' => 'xs' 

					) ); 

				?>

		</a>

	</div>

</aside><!-- #blog-sidebar -->