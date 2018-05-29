<?php

// Menu item search

?>

<li id="search-menu-container" class="menu-item">

	<a href="<?php esc_url( get_search_link() ); ?>" class="link link-secondary" >

		<span class="icon">

			<?php echo canvas_get_svg_icon( array( 'icon' => 'search', 'size' => 'sm' ) ); ?>

		</span>

	</a>

</li>