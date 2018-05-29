<?php
/**
 * Template part for displaying page content in page.php
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

	<header class="entry-header column col-xs-12 col-sm-12 col-md-3 col-lg-3">

		<?php the_title( '<h1 class="title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content page column col-xs-12 col-sm-12 col-md-9 col-lg-9">

		<?php

			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'canvas' ),
				'after'  => '</div>',
			) );

		?>

	</div><!-- .entry-content -->
	
</article><!-- #post-## -->
