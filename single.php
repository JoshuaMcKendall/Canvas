<?php get_header(); ?>
<div id="page">
	<div id="posts" class="article-list" >
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php
	if ( has_post_format( array('gallery', 'image', 'video'))) { ?>
		<div class="media">
			<?php the_content(); ?>
		</div>
	<?php } else { ?>
		<?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array( 'class' => 'blog-post-image')); } ?>
        <h1 class="title"><?php the_title(); ?></h1>      
        <div class="entry">
            <p><?php the_content(); ?></p>
        </div>
	<?php } ?>
	<div id="rc" class="clearfix" >
		<div id="rc-date" class="post-meta">
			<a href="<?php 
			$archive_year  = get_the_time('Y');
			$archive_month = get_the_time('m');
			$archive_day = get_the_time('d');
			
			echo get_day_link( $archive_year, $archive_month, $archive_day );
			?>" class="date">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 18 18">
<path fill="#aaaaaa" d="M9 0.75q1.682 0 3.208 0.653t2.631 1.758 1.758 2.631 0.653 3.208-0.653 3.208-1.758 2.631-2.631 1.758-3.208 0.653-3.208-0.653-2.631-1.758-1.758-2.631-0.653-3.208 0.653-3.208 1.758-2.631 2.631-1.758 3.208-0.653zM9 2.25q-1.371 0-2.622 0.536t-2.153 1.438-1.438 2.153-0.536 2.622 0.536 2.622 1.438 2.153 2.153 1.438 2.622 0.536 2.622-0.536 2.153-1.438 1.438-2.153 0.536-2.622-0.536-2.622-1.438-2.153-2.153-1.438-2.622-0.536zM9 3.75q0.311 0 0.53 0.22t0.22 0.53v4.189l2.033 2.027q0.217 0.217 0.217 0.533t-0.217 0.533-0.533 0.217-0.533-0.217l-2.25-2.25q-0.217-0.217-0.217-0.533v-4.5q0-0.311 0.22-0.53t0.53-0.22z"></path>
</svg><span><?php the_time('M j, Y'); ?></span>
			</a>
		</div>
		
		
		<div id="rc-tags" class="post-meta" >
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 18 18">
<path fill="#aaaaaa" d="M0.75 0.75h8.484l7.359 7.354q0.656 0.656 0.656 1.588 0 0.937-0.656 1.594l-5.309 5.309q-0.656 0.656-1.588 0.656-0.937 0-1.594-0.656l-7.354-7.359v-8.484zM15.533 9.164l-6.92-6.914h-6.363v6.363l6.914 6.92q0.223 0.217 0.533 0.217t0.527-0.217l5.309-5.309q0.217-0.217 0.217-0.527t-0.217-0.533zM6.375 4.5q0.779 0 1.327 0.548t0.548 1.327-0.548 1.327-1.327 0.548-1.327-0.548-0.548-1.327 0.548-1.327 1.327-0.548zM6.375 6q-0.152 0-0.264 0.111t-0.111 0.264 0.111 0.264 0.264 0.111 0.264-0.111 0.111-0.264-0.111-0.264-0.264-0.111z"></path>
</svg>
			<?php
				$tags = get_the_tags();
				$count = 0;
				foreach($tags as $tag) {
					$count++;
					if(1 == $count) {
						echo '<span><a href="'.get_tag_link( $tag->term_id ).'">#'.$tag->name.'</a></span>';
					}
				}
			?>
		</div>
	</div>
	
	<div class="navigation">
	<div class="prev"><h3><?php next_post_link('%link', 'Next Post') ?></h3></div>
	<div class="next"><h3><?php previous_post_link('%link', 'Previous Post') ?></h3></div>
	</div>
<?php endwhile; endif; ?>
	
	<div id="comments"><?php comments_template(); ?></div>
	</div><!--posts-->
	<div id="sidebar">
		<?php get_sidebar(); ?>
	</div><!--sidebar-->
</div><!--page-->
<?php get_footer(); ?>