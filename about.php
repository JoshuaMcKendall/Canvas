<?php 
/*
Template Name: About Page
*/
get_header();
?>
<div id="page" class="clearfix">
<div id="main-content" >
<?php while ( have_posts() ) : the_post(); ?>

					<?php the_content(); ?>

				<?php endwhile; // end of the loop. ?>
</div><!--main-content-->
</div>
<?php get_footer(); ?>