<?php

get_header();

?>
<div id="page">
<div id="posts" class="article-list animated fadeIn" >
<?php if ( have_posts() ) : ?>

					<h2 class="something"><?php printf('Search Results for: %s' , '<span>' . get_search_query() . '</span>' ); ?></h2>
				


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					
					
					
					<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
         <a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail('blog_featured'); } ?></a>
        <h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <p class="post-meta">
        <span class="post-date"><small><?php the_time('F j, Y'); ?></small></span>
        </p>      
        <div class="entry">
            <p><?php the_excerpt(); ?></p>
        </div>
        <div id="rc">
			<div class="readmore"><h3 id="readmore"><a class="moretag" href="<?php the_permalink(); ?>"> Continue &rarr;</a></h3></div>
			<a href="<?php the_permalink(); ?>#comments" class="comments"><?php comments_number('0 Comments'); ?></a> 
		</div> 
        <div class="page-divider post-divider"></div>
</div><!-- /.post -->



				<?php endwhile; ?>
				
				<div id="#nav-below">
  				<?php posts_nav_link(); ?> 
				</div>

			<?php else : ?>
						<h2 class="nothing">Nothing Found</h2>
		
						<p>Sorry, no posts match your search.</p>


<?php endif; ?>
</div><!--posts-->
<div id="sidebar">
<?php get_sidebar(); ?>
</div><!--sidebar-->
</div><!--page-->
<?php get_footer(); ?>