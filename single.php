<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Joshua Mckendall
 * @subpackage canvas
 * @since 3.0.0
 * @version 3.0.0
 */

get_header(); ?>

	<?php get_sidebar(); ?>

	<div class="content column col-xs-12 col-sm-12 col-md-12 col-lg-8">

		<?php

		/* Start the Loop */

		while ( have_posts() ) : the_post();

			get_template_part( '_template-parts/post/content', get_post_format() );

			$arrow_left_icn = canvas_get_svg_icon( array( 

				'icon'		=> 'arrow-left',
				'size'		=> 'sm'

			) );

			$arrow_right_icn = canvas_get_svg_icon( array( 

				'icon'		=> 'arrow-right',
				'size'		=> 'sm'

			) );

			// the_post_navigation( array(
			// 	'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'canvas' ) . '</span><span aria-hidden="true" class="nav-subtitle"><span class="icon icon-left"> ' . $arrow_left_icn . ' </span>' . __( 'Previous', 'canvas' ) . '</span>',
			// 	'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'canvas' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'canvas' ) . '<span class="icon icon-right"> ' . $arrow_right_icn . ' </span></span>',
			// ) );

			echo '<div class="post-nav nav-links">';

			echo '<span class="nav-link nav-previous">';

				previous_post_link( '%link', '<span class="icon icon-left"> ' . $arrow_left_icn . ' </span>' . __( 'Previous', 'canvas' ) );

			echo '</span>';

			canvas_blog_link();

			echo '<span class="nav-link nav-next">';

				next_post_link( '%link', __( 'Next', 'canvas' ) . '<span class="icon icon-right"> ' . $arrow_right_icn . ' </span>' );

			echo '</span>';

			echo '</div>';

			//If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :

				echo '<div class="comments-section">';

					comments_template();

				echo '</div>';

			endif;

		endwhile; // End of the loop.

		?>

	</div><!-- .content.container -->

	<?php	get_template_part( '_template-parts/sidebar/bottom', 'sidebar' ); ?>


<?php get_footer();
