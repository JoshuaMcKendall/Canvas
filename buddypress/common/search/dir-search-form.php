<?php
/**
 * Output the search form markup.
 *
 * @since 2.7.0
 */

	$search_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'search',
		'size'	=> 'sm'
	 ) );

?>

<div id="<?php echo esc_attr( bp_current_component() ); ?>-dir-search" class="dir-search search-form form-unit" role="search">
	<form action="" method="get" id="search-<?php echo esc_attr( bp_current_component() ); ?>-form">
		<label for="<?php bp_search_input_name(); ?>" class="bp-screen-reader-text assistive-text"><?php bp_search_placeholder(); ?></label>
		<div class="input-group">
			<input type="text"  class="search-field form-control" name="<?php echo esc_attr( bp_core_get_component_search_query_arg() ); ?>" id="<?php bp_search_input_name(); ?>" placeholder="<?php bp_search_placeholder(); ?>" />
			<span class="input-group-btn">
					<button type="submit" class="search-submit btn btn-default" id="<?php echo esc_attr( bp_get_search_input_name() ); ?>_submit" name="<?php bp_search_input_name(); ?>_submit">
						<span class="btn-icon icon-sm">
							<?php echo $search_svg; ?>
						</span>
					</button>
			</span>
		</div>	
	</form>
</div><!-- #<?php echo esc_attr( bp_current_component() ); ?>-dir-search -->
