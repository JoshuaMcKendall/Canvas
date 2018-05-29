<?php
/**
 * Widget API: Canvas_Categories_Widget class
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 */

/**
 * Core class used to implement an Advanced Categories widget.
 *
 * @since 3.0.0
 *
 * @see WP_Widget
 */
class Canvas_Categories_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'		=> 'canvas_categories_widget',
			'description'	=> esc_html__('An advanced category widget', 'canvas'),
		);

		parent::__construct('canvas_categories_widget', esc_html__('Advanced Categories', 'canvas'), $widget_ops);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		static $first_dropdown = true;

		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$cat_args = array(
			'orderby'      => 'count',
			'order'		   => 'DESC',
			'show_count'   => $c,
			'hierarchical' => $h,
		);

		if ( $d ) {
			echo sprintf( '<form action="%s" method="get">', esc_url( home_url() ) );
			$dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
			$first_dropdown = false;

			echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

			$cat_args['show_option_none'] = __( 'Select Category' );
			$cat_args['id'] = $dropdown_id;
			$cat_args['class'] = 'form-control';

			/**
			 * Filters the arguments for the Categories widget drop-down.
			 *
			 * @since 2.8.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @see wp_dropdown_categories()
			 *
			 * @param array $cat_args An array of Categories widget drop-down arguments.
			 * @param array $instance Array of settings for the current widget.
			 */
			wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args, $instance ) );

			echo '</form>';
			?>

<script type='text/javascript'>
/* <![CDATA[ */
(function() {
	var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
	function onCatChange() {
		if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
			dropdown.parentNode.submit();
		}
	}
	dropdown.onchange = onCatChange;
})();
/* ]]> */
</script>

<?php
		} else {

			$cat_args['parent_category_count'] = count( get_terms( 'category', array( 'parent' => 0 ) ) );
			$display_count = ( is_numeric( $instance['display-count'] ) ) ? (int) $instance['display-count'] + 1 : 5;
			$drawer_state = '';
			$drawer_trigger = '';
			$drawer_wrap_class = '';

			if( $cat_args['parent_category_count'] >= $instance['display-count'] ) {

				$chevron_down_svg = canvas_get_svg_icon( array( 
					'icon'	=> 'chevron-down',
					'size'	=> 'sm'
				 ) );

				$drawer_trigger = '<label for="categories-drawer" class="drawer-trigger">'.$chevron_down_svg.'</label>';
				$drawer_state = '<input type="checkbox" class="drawer-state" id="categories-drawer" name="categories-drawer" />';
				$drawer_wrap_class = 'drawer-wrap';

			}

?>
	<div>
		<?php echo $drawer_state ?>
		<ul class="list-group accordian accordian-closed column-list-group <?php echo $drawer_wrap_class ?>">
<?php
		$cat_args['title_li'] = '';
		$cat_args['echo'] = 1;
		$cat_args['walker'] = new Canvas_Category_Walker($instance['display-count']);

		/**
		 * Filters the arguments for the Categories widget.
		 *
		 * @since 2.8.0
		 * @since 4.9.0 Added the `$instance` parameter.
		 *
		 * @param array $cat_args An array of Categories widget options.
		 * @param array $instance Array of settings for the current widget.
		 */
		wp_list_categories( apply_filters( 'widget_categories_args', $cat_args, $instance ) );

?>
		</ul>
		<?php echo $drawer_trigger; ?>
	</div>
<?php
		}

		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = sanitize_text_field( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$displayed_categories = isset( $instance['display-count'] ) ? abs( (int) $instance['display-count'] ) : 5;
		$display_count = sanitize_text_field( $displayed_categories );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

		<br />
		<?php if( ! $dropdown ) { ?>
			<?php _e('Display first '); ?> <input type="number" class="input" id="<?php echo $this->get_field_id('display-count'); ?>" name="<?php echo $this->get_field_name('display-count'); ?>" value="<?php echo esc_attr( $display_count ); ?>" />
			<label for="<?php echo $this->get_field_id('display-count'); ?>"><?php _e( ' categories' ); ?></label><br />
		<?php } ?>

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['display-count'] = sanitize_text_field( abs( (int) $new_instance['display-count'] ) );

		return $instance;
	}
	
}