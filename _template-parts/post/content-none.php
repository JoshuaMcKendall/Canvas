<?php 
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Canvas
 * @since 1.0
 * @version 1.0
 */

?>

<section class="no-results not-found">

	<header class="page-header">

		<h1 class="page-title"><?php _e( 'Nothing Found', 'canvas' ); ?></h1>

	</header>

	<div class="page-content">

		<?php

		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'canvas' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php else : ?>

			<p><?php _e( 'Hm, It seems we can&rsquo;t find what you&rsquo;re looking for.', 'canvas' ); ?></p>
			
		<?php endif; ?>

	</div><!-- .page-content -->
	
</section><!-- .no-results -->