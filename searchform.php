<?php

/**
 * Template for displaying search forms in Canvas
 *
 * @package Joshua McKendall
 * @subpackage canvas
 * @since 3.0.0
 * @version 3.0.0
 */

$unique_id = uniqid( 'search-form-' );

?>

<form role="search" method="get" class="search-form form-unit" action="<?php echo esc_url( canvas_search_action()['action'] ); ?>">

	<?php do_action( 'canvas_before_search' ); ?>

	<label for="<?php echo $unique_id; ?>" class="assistive-text">

		<span class="screen-reader-text"><?php echo _x( 'Search', 'label', 'canvas' ); ?></span>

	</label>

	<?php do_action( 'canvas_search_form' ); ?>

	<div class="input-group">

		<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field form-control" placeholder="<?php echo esc_attr( canvas_search_action()['placeholder'] ); ?>" value="<?php echo get_search_query(); ?>" name="s" />

		<span class="input-group-btn">

			<button type="submit" class="search-submit btn btn-default">

				<span class="icon icon-sm">

					<?php echo canvas_get_svg_icon( array( 'icon' => 'search', 'size' => 'sm' ) ); ?>

				</span>

			</button>

		</span>	

	</div>

	<?php do_action( 'canvas_after_search' ); ?>

</form>