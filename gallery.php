<?php 
/*
Template Name: Gallery Page
*/
$galcount = esc_attr( get_option( 'canvas_setting_name' ) );
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$galquery = new WP_Query('showposts='.$galcount.'&post_type=art'.'&paged='.$paged);
  if (!isset($page)) $page = get_query_var('page');
  if (!isset($paged)) $paged = get_query_var('paged');
  if (is_page()) {
  $realpagescount = $galquery->max_num_pages;

    if ( $paged > $realpagescount ){
      nocache_headers();
            status_header( '404' );
            $wp_query->is_404=true;
            $wp_query->is_single=false;
            $wp_query->is_singular=false;
            $wp_query->post_count=0;
            $wp_query->page=0;
            $wp_query->query['page']='';
            $wp_query->query['posts']=array();
            $wp_query->query['post']=array();
            $wp_query->posts=array();
            $wp_query->post=array();
            $wp_query->queried_object=array();
            $wp_query->queried_object_id=0;
            locate_template('404.php', true);
            exit;
    }
  } 
get_header();
?>
<div id="page">
  <div id="gallery">
    <ul>
      <?php 
      while ($galquery->have_posts()) : $galquery->the_post();
              $full = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
              $imgtitle = get_the_title($post->ID);
			  $santitle = sanitize_title($imgtitle); 
              $imgexc = get_the_excerpt();
              $imgdate = get_the_date();
			  $imgurl = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'gallery_thumb');
			  $bg = get_template_directory_uri().'/_assets/img/bg.png';
              echo '<li><div class="arthumb" title="'.$imgtitle.'" ><a data-imagelightbox="imagelightbox-gallery" id="'.$santitle.'" href="'.$full['0'].'"  ><img class="attachment-gallery_thumb wp-post-image unveil" src="'.get_stylesheet_directory_uri().'/_assets/img/blank.gif" data-src="'.$imgurl[0].'" ></img></a></div></li>';
          endwhile;
        ?>
    </ul>
  </div>
  <div id="#nav-gallery">
	
	
	<div class="navigation">
		<div class="prev">
			<h3><?php previous_posts_link('&laquo; Previous Page') ?></h3>
		</div>
		<div class="next">
			<h3><?php next_posts_link('Next Page &raquo;',$galquery->max_num_pages ) ?></h3>
		</div>
	</div>

  </div>
    <?php wp_reset_postdata(); ?>
</div>
<?php
get_footer();
?>