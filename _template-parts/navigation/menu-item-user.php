<?php

// Menu Item User

?>

<li id="user-menu-dropdown-container" class="menu-item">

	<a href="#user-menu" id="user-menu-trigger" class="link link-secondary dropdown-trigger" onclick="event.preventDefault();" aria-expanded="false" aria-controls="user-menu">

		<?php do_action( 'canvas_menu_item_user_avatar' ); ?>

	</a>

	<div id="user-menu" class="dropdown" aria-hidden="true">

		<div class="dropdown-header">

			<span class="user-dropdown-item close">

				<a href="#close-user-menu" id="user-menu-dropdown-close" class="link link-secondary dropdown-close">

					<?php _e( 'Close', 'canvas' ) ?>

					<span class="icon icon-right icon-sm">

						<?php echo canvas_get_svg_icon( array( 'icon' => 'x', 'size' => 'sm' ) ); ?>				

					</span>

				</a>

			</span>
			
		</div>

		<div class="dropdown-body">

			<?php echo canvas_get_user_links(); ?>
			
			<?php do_action( 'canvas_user_dropdown_menu_body' ); ?>

		</div>

	</div>

</li>