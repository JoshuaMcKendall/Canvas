<?php
/**
 * Template part for displaying gallery page content in page-gallery.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 * @version 3.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content column col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php

			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'canvas' ),
				'after'  => '</div>',
			) );
			
		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
