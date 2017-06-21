<?php
/**
 * @package WordPress
 * @subpackage HTML5-Reset-WordPress-Theme
 * @since HTML5 Reset 2.0
 */
?>
 <div id="sidebarbox">

    <?php if (!function_exists('dynamic_sidebar') && !dynamic_sidebar('Sidebar Widgets')) : else : ?>
    
        <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->

    	<?php get_search_form(); ?>
    	<div id="archives">
    		<h2 class="sidehead">Monthly Archive</h2>
    			<?php get_calendar(); ?>
    	</div><!--archives-->
    	
    	<div id="categories">
    	<h2 class="sidehead">Categories</h2>
		<ul class="sidelinks">
			 <?php wp_list_categories('title_li='); ?>
		</ul>
    	</div><!--categories-->
    
	<?php endif; ?>

</div>