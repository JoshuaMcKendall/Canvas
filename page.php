<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

	<?php

		while ( have_posts() ) : the_post();

			get_template_part( '_template-parts/page/content', 'page-blank' );

	?>

		<div class="column col-xs-12 col-sm-12 col-md-12 col-lg-12">

	<?php

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :

				comments_template();
				
			endif;

		endwhile; // End of the loop.
	?>

		</div>

<?php get_footer();