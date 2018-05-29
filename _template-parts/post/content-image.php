<?php
/**
 * Template part for displaying post content in index.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 * @version 3.0.0
 */

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php

		if ( is_single() ) {

			the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' );

		} elseif ( is_front_page() && is_home() ) {

			the_title( '<h3 class="entry-title screen-reader-text"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );

		} else {

			the_title( '<h2 class="entry-title screen-reader-text"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

		}

		?>
		
	</header><!-- .entry-header -->

	<div class="entry-content post">
		<?php
		
			$more_link = canvas_get_svg_icon( array( 

				'icon'		=> 'chevron-down',
				'size'		=> 'sm'

			 ) );

			the_content( $more_link );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'canvas' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->	

	<footer>
		
		<div id="canvas-post-meta-<?php the_ID(); ?>" class="meta entry-meta">

			<ul class="info list-group-inline">

				<?php 
				/**
				 * Functions hooked in to canvas_post_meta
				 *
				 * @hooked Canvas() -> get_post_meta()	- 10
				 * @src  ./_include/class-canvas.php:358
				 */
				do_action( 'canvas_post_meta' ); ?>

			</ul>
			
		</div><!-- .entry-meta -->

		<?php

			if( is_single() ) {

				echo '<div class="tag-list">';

				the_tags( '', null, null );

				echo '</div>';

			}

		?>	

	</footer>
	
</article><!-- /.post -->