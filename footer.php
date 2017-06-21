<footer class="clearfix foot">
	<ul class="clearfix" >
		<li class="bottomLinks"><a title="Policies" href="<?php echo get_site_url(); ?>/policies/"><small>Policies</small></a></li>
		<li class="bottomLinks"><a title="Copyright" href="<?php echo get_site_url(); ?>/copyright/"><small>&copy; Copyright <?php echo date('Y'); ?></small></a></li>
		<li class="bottomLinks"><a title="Rss Feed" href="<?php bloginfo('rss_url'); ?>" ><small>Rss</small></a></li>
	</ul>
</footer>
</div><!--wrapper-->
</div><!--canvas-->
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<?php wp_footer(); ?>
<?php $offset = (is_page('gallery')) ? 0 : 120; ?>
<?php
if (is_page_template('gallery.php') || is_search() || is_archive() || is_single() || is_home()) {
	echo '<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$(".unveil").unveil('.$offset.', function() {
		  $(this).load(function() {
			this.style.opacity = 1;
		  });
		});
	});
	</script>';
}
?>
<?php
	if (is_front_page()) {
		echo "
		<script type=\"text/javascript\" charset=\"utf-8\">
  			jQuery(window).load(function() {
    			jQuery('.flexslider').flexslider({
    				animation: \"fade\",
    				controlNav: false,
					directionNav: false,
    			});
  			});
		</script>";
	}
	if(is_page('gallery')) {
		echo " ";
	}
	if(is_page('blog') || is_home() || is_single() || is_archive()) {
		echo "
			<script type=\"text/javascript\" charset=\"utf-8\">
			$('#page').css('min-height', function(){
			return $('#sidebar').height() + 60; });


			</script>";
	}
?>
</body>
</html>
