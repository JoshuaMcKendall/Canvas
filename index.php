<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 * @version 3.0.0
 */

get_header(); ?>

	<?php get_sidebar(); ?>

	<div class="content column col-xs-12 col-sm-12 col-md-12 col-lg-8">

		<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( '_template-parts/post/content', get_post_format() );

				endwhile;

				canvas_paginator();
		?>

		<noscript>
			
			<?php

				the_posts_pagination( array(
					'prev_text' => '<span class="icon">' . canvas_get_svg_icon( array( 'icon' => 'arrow-left', 'size' => 'sm' ) ) . '</span>' . '<span class="screen-reader-text">' . __( 'Previous page', 'canvas' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'canvas' ) . '</span>' . '<span class="icon">' . canvas_get_svg_icon( array( 'icon' => 'arrow-right', 'size' => 'sm' ) ) . '</span>',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'canvas' ) . ' </span>',
				) );


			?>

		</noscript>

		<?php

			else :

				get_template_part( '_template-parts/post/content', 'none' );

			endif;
		?>
		
	</div><!-- .content.column -->


	<?php get_template_part( '_template-parts/sidebar/bottom', 'sidebar' ); ?>


<?php get_footer();