<?php
/**
 * Template Name: Half Page
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 */

get_header(); ?>

	<?php

	while ( have_posts() ) : the_post();

		get_template_part( '_template-parts/page/content', 'page-half' );

	endwhile; // End of the loop.

	?>

<?php get_footer();