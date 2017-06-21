<?php if ( have_posts() ) : ?>

<?php while (have_posts()) : the_post(); ?>

<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

<?php
	if ( has_post_format( array('gallery', 'image', 'video'))) { ?>
		<div class="media">
			<?php the_content(); ?>
		</div>
	<?php } else { ?>
		 <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail('full', array( 'class' => 'blog-post-image')); } ?></a>
        <h1 class="title"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h1>      
        <div class="entry">
            <p><?php the_content(); ?></p>
        </div>
	<?php } ?>
	<div id="rc" class="clearfix">
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
			
			<div id="rc-comments" class="post-meta">
			<a href="<?php the_permalink(); ?>#comments" class="comments">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 18 18">
<path fill="#aaaaaa" d="M3 0.75h12q0.932 0 1.591 0.659t0.659 1.591v8.25q0 0.932-0.659 1.591t-1.591 0.659h-6l-5.25 3.75v-3.75h-0.75q-0.932 0-1.591-0.659t-0.659-1.591v-8.25q0-0.932 0.659-1.591t1.591-0.659zM15 2.25h-12q-0.311 0-0.53 0.22t-0.22 0.53v8.25q0 0.311 0.22 0.53t0.53 0.22h2.25v2.338l3.27-2.338h6.48q0.311 0 0.53-0.22t0.22-0.53v-8.25q0-0.311-0.22-0.53t-0.53-0.22z"></path>
</svg><span><?php comments_number('0 Comments'); ?></span></a>
			</div>
			
			<div id="rc-link" class="post-meta">
			<a class="moretag" href="<?php the_permalink();?>">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 18 18">
<path fill="#aaaaaa" d="M12.75 0.75q0.885 0 1.708 0.334t1.474 0.984 0.984 1.474 0.334 1.708q0 0.879-0.337 1.708t-0.981 1.474l-2.25 2.25q-0.064 0.064-0.193 0.182-0.627 0.557-1.4 0.847t-1.588 0.29q-1.025 0-1.939-0.439-0.686-0.322-1.242-0.879t-0.879-1.242q0.439-0.439 1.061-0.439 0.217 0 0.439 0.064 0.381 0.615 0.996 0.996 0.721 0.439 1.564 0.439 0.586 0 1.137-0.223t0.984-0.656l2.25-2.25q0.434-0.434 0.656-0.984t0.223-1.137-0.223-1.137-0.656-0.984-0.984-0.656-1.137-0.223-1.137 0.223-0.984 0.656l-1.576 1.576q-0.75-0.205-1.553-0.205-0.129 0-0.363 0.012 0.117-0.129 0.182-0.193l2.25-2.25q0.645-0.645 1.474-0.981t1.708-0.337zM7.5 6q1.025 0 1.939 0.439 0.686 0.322 1.242 0.879t0.879 1.242q-0.439 0.439-1.061 0.439-0.217 0-0.439-0.064-0.381-0.615-0.996-0.996-0.721-0.439-1.564-0.439-0.586 0-1.137 0.223t-0.984 0.656l-2.25 2.25q-0.434 0.434-0.656 0.984t-0.223 1.137 0.223 1.137 0.656 0.984 0.984 0.656 1.137 0.223 1.137-0.223 0.984-0.656l1.576-1.576q0.75 0.205 1.553 0.205 0.129 0 0.363-0.012-0.117 0.129-0.182 0.193l-2.25 2.25q-0.65 0.65-1.474 0.984t-1.708 0.334q-0.879 0-1.708-0.337t-1.474-0.981q-0.65-0.65-0.984-1.474t-0.334-1.708 0.334-1.708 0.984-1.474l2.25-2.25q0.064-0.064 0.193-0.182 0.627-0.557 1.4-0.847t1.588-0.29z"></path>
</svg><span>Go to post ></span></a>
			</div>
	</div>
	
</div><!-- /.post -->
<?php endwhile; ?> 


<div class="navigation">
<div class="prev"><h3><?php previous_posts_link('&laquo; Previous Page') ?></h3></div>
<div class="next"><h3><?php next_posts_link('Next Page &raquo;','') ?></h3></div>
</div>

<?php else : ?>
<p><?php _e('Sorry, there are no posts here.'); ?></p>
<?php endif; ?>
