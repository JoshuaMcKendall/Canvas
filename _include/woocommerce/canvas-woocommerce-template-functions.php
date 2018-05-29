<?php
/**
 * WooCommerce Template Functions.
 *
 * @package canvas
 */

if ( ! function_exists( 'canvas_before_content' ) ) {
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function canvas_before_content() {
		?>
		<div id="primary" class="content-area column col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php
	}
}

if ( ! function_exists( 'canvas_after_content' ) ) {
	/**
	 * After Content
	 * Closes the wrapping divs
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function canvas_after_content() {
		?>
		</div><!-- #primary -->

		<?php do_action( 'canvas_sidebar' );
	}
}

if ( ! function_exists( 'canvas_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function canvas_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		canvas_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();


		return $fragments;
	}
}

if ( ! function_exists( 'canvas_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function canvas_cart_link() {
		?>

			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'canvas' ); ?>" id="canvas-shopping-cart" class="cart-contents link link-secondary dropdown-trigger" >

				<span id="shopping-cart-nav" class="icon">

					<?php do_action( 'canvas_menu_item_shopping_cart' ); ?>

				</span>

			</a>

		<?php
	}
}

if ( ! function_exists( 'canvas_product_search' ) ) {
	/**
	 * Display Product Search
	 *
	 * @since  1.0.0
	 * @uses  canvas_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function canvas_product_search() {
		if ( canvas_is_woocommerce_activated() ) { ?>
			<div class="site-search">
				<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			</div>
		<?php
		}
	}
}

if ( ! function_exists( 'canvas_header_cart' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  canvas_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function canvas_header_cart() {
		if ( canvas_is_woocommerce_activated() ) {
			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
		?>

		<li id="site-header-cart" class="menu-item site-header-cart">

			<?php canvas_cart_link(); ?>

			<div id="cart-menu" class="dropdown">

				<div class="dropdown-header">

					<span class="cart-dropdown-item close">

						<a href="#close-cart-menu" id="cart-menu-dropdown-close" class="link link-secondary dropdown-close">

							<?php _e( 'Close', 'canvas' ) ?>

							<span class="icon icon-right">

								<?php echo canvas_get_svg_icon( array( 'icon' => 'x', 'size' => 'sm' ) ); ?>				

							</span>

						</a>

					</span>
					
				</div>

				<div class="dropdown-body">

					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>

				</div>

			</div>

		</li>
		<?php
		}
	}
}

if ( ! function_exists( 'canvas_upsell_display' ) ) {
	/**
	 * Upsells
	 * Replace the default upsell function with our own which displays the correct number product columns
	 *
	 * @since   1.0.0
	 * @return  void
	 * @uses    woocommerce_upsell_display()
	 */
	function canvas_upsell_display() {
		$columns = apply_filters( 'canvas_upsells_columns', 3 );
		woocommerce_upsell_display( -1, $columns );
	}
}

if ( ! function_exists( 'canvas_sorting_wrapper' ) ) {
	/**
	 * Sorting wrapper
	 *
	 * @since   1.4.3
	 * @return  void
	 */
	function canvas_sorting_wrapper() {
		echo '<div class="canvas-sorting">';
	}
}

if ( ! function_exists( 'canvas_sorting_wrapper_close' ) ) {
	/**
	 * Sorting wrapper close
	 *
	 * @since   1.4.3
	 * @return  void
	 */
	function canvas_sorting_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'canvas_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper
	 *
	 * @since   2.2.0
	 * @return  void
	 */
	function canvas_product_columns_wrapper() {
		$columns = canvas_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}

if ( ! function_exists( 'canvas_loop_columns' ) ) {
	/**
	 * Default loop columns on product archives
	 *
	 * @return integer products per row
	 * @since  1.0.0
	 */
	function canvas_loop_columns() {
		$columns = 3; // 3 products per row

		if ( function_exists( 'wc_get_default_products_per_row' ) ) {
			$columns = wc_get_default_products_per_row();
		}

		return apply_filters( 'canvas_loop_columns', $columns );
	}
}

if ( ! function_exists( 'canvas_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close
	 *
	 * @since   2.2.0
	 * @return  void
	 */
	function canvas_product_columns_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'canvas_shop_messages' ) ) {
	/**
	 * canvas shop messages
	 *
	 * @since   1.4.4
	 * @uses    canvas_do_shortcode
	 */
	function canvas_shop_messages() {
		if ( ! is_checkout() ) {
			echo wp_kses_post( canvas_do_shortcode( 'woocommerce_messages' ) );
		}
	}
}

if ( ! function_exists( 'canvas_woocommerce_pagination' ) ) {
	/**
	 * canvas WooCommerce Pagination
	 * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
	 * but since canvas adds pagination before that function is excuted we need a separate function to
	 * determine whether or not to display the pagination.
	 *
	 * @since 1.4.4
	 */
	function canvas_woocommerce_pagination() {
		if ( woocommerce_products_will_display() ) {
			woocommerce_pagination();
		}
	}
}

if ( ! function_exists( 'canvas_promoted_products' ) ) {
	/**
	 * Featured and On-Sale Products
	 * Check for featured products then on-sale products and use the appropiate shortcode.
	 * If neither exist, it can fallback to show recently added products.
	 *
	 * @since  1.5.1
	 * @param integer $per_page total products to display.
	 * @param integer $columns columns to arrange products in to.
	 * @param boolean $recent_fallback Should the function display recent products as a fallback when there are no featured or on-sale products?.
	 * @uses  canvas_is_woocommerce_activated()
	 * @uses  wc_get_featured_product_ids()
	 * @uses  wc_get_product_ids_on_sale()
	 * @uses  canvas_do_shortcode()
	 * @return void
	 */
	function canvas_promoted_products( $per_page = '2', $columns = '2', $recent_fallback = true ) {
		if ( canvas_is_woocommerce_activated() ) {

			if ( wc_get_featured_product_ids() ) {

				echo '<h2>' . esc_html__( 'Featured Products', 'canvas' ) . '</h2>';

				echo canvas_do_shortcode( 'featured_products', array(
											'per_page' => $per_page,
											'columns'  => $columns,
				) );
			} elseif ( wc_get_product_ids_on_sale() ) {

				echo '<h2>' . esc_html__( 'On Sale Now', 'canvas' ) . '</h2>';

				echo canvas_do_shortcode( 'sale_products', array(
											'per_page' => $per_page,
											'columns'  => $columns,
				) );
			} elseif ( $recent_fallback ) {

				echo '<h2>' . esc_html__( 'New In Store', 'canvas' ) . '</h2>';

				echo canvas_do_shortcode( 'recent_products', array(
											'per_page' => $per_page,
											'columns'  => $columns,
				) );
			}
		}
	}
}