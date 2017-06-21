<?php 

get_header();

//content
?>
<div id="page">

<div id="posts" class="article-list" >

<?php

if (get_query_var('s') !== '') {
	get_template_part('blog', 'search');
} else {
	get_template_part('blog', 'main');
}

?>

</div><!--posts-->
<div id="sidebar">
<?php get_sidebar(); ?>
</div><!--sidebar-->

</div><!--page-->
<?php

get_footer();

?>