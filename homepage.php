<?php
/*
Template Name: Home Page
*/
?>
<?php get_header(); ?>
<div id="page">
	<div class="flexslider">
		<ul class="slides">
			<?php 
    			$query = new WP_Query('post_type=slides'. '&order=DESC'. '&posts_per_page=3');  
    			if (have_posts()) : while ($query->have_posts()) : $query->the_post();  
    				global $post;
					$image = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'front_slide_large');
					$imgsrc = $image[0];
					$imgexp = explode('.', $imgsrc);
					$type = end($imgexp);
					$format = ($type == 'png') ? 'png' : 'jpeg' ;
    				echo '<li><a href="'.site_url('/gallery/').'" ><img src="data:image/'.$format.';base64,'.base64_encode(file_get_contents($image[0])).'"/></a></li>'; 
    			endwhile; endif;
    			wp_reset_postdata();
    		?>
  		</ul>
	</div><!--recgallery-->
</div><!--page-->
<?php get_footer(); ?>