<?php
/**
 * Template part for displaying home page content in page-home.php
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

		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->