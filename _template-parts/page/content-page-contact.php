<?php
/**
 * Template part for displaying contact form page content in page-contact.php
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

	<div id="canvas-contact-form" class="column col-xs-12 col-sm-12 col-md-12 col-lg-6">

		<?php 

			echo do_shortcode( '[contact-form-7 title="Contact Me" html_id="contact-form" html_class="input-group"]' );

		?>

	</div><!-- #canvas-contact-form -->

	<div class="entry-content column col-xs-12 col-sm-12 col-md-12 col-lg-6">

		<?php

			the_content();

		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->