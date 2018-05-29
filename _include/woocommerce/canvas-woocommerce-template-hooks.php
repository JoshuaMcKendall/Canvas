<?php
/**
 * canvas WooCommerce hooks
 *
 * @package canvas
 */

/**
 * Styles
 *
 * @see  canvas_woocommerce_scripts()
 */

/**
 * Layout
 *
 * @see  canvas_before_content()
 * @see  canvas_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  canvas_shop_messages()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb',                   20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper',       10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end',   10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar',                  10 );
remove_action( 'woocommerce_after_shop_loop',     'woocommerce_pagination',                   10 );
remove_action( 'woocommerce_before_shop_loop',    'woocommerce_result_count',                 20 );
remove_action( 'woocommerce_before_shop_loop',    'woocommerce_catalog_ordering',             30 );
add_action( 'woocommerce_before_main_content',    'canvas_before_content',                	  10 );
add_action( 'woocommerce_after_main_content',     'canvas_after_content',                 	  10 );
add_action( 'canvas_before_main_content',             	  'canvas_shop_messages',                 	  15 );
//add_action( 'canvas_before_main_content',             	  'woocommerce_breadcrumb',                   10 );

add_action( 'woocommerce_after_shop_loop',        'canvas_sorting_wrapper',               9 );
add_action( 'woocommerce_after_shop_loop',        'woocommerce_catalog_ordering',             10 );
add_action( 'woocommerce_after_shop_loop',        'woocommerce_result_count',                 20 );
add_action( 'woocommerce_after_shop_loop',        'woocommerce_pagination',                   30 );
add_action( 'woocommerce_after_shop_loop',        'canvas_sorting_wrapper_close',         31 );
add_action( 'woocommerce_after_shop_loop',        'canvas_product_columns_wrapper_close', 40 );

add_action( 'woocommerce_before_shop_loop',       'canvas_sorting_wrapper',               9 );
add_action( 'woocommerce_before_shop_loop',       'woocommerce_catalog_ordering',             10 );
add_action( 'woocommerce_before_shop_loop',       'woocommerce_result_count',                 20 );
add_action( 'woocommerce_before_shop_loop',       'canvas_woocommerce_pagination',        30 );
add_action( 'woocommerce_before_shop_loop',       'canvas_sorting_wrapper_close',         31 );
add_action( 'woocommerce_before_shop_loop',       'canvas_product_columns_wrapper',       40 );

// add_action( 'canvas_footer',                  'canvas_handheld_footer_bar',           999 );

// Legacy WooCommerce columns filter.
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
	add_filter( 'loop_shop_columns', 'canvas_loop_columns' );
}

/**
 * Products
 *
 * @see  canvas_upsell_display()
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display',               15 );
add_action( 'woocommerce_after_single_product_summary',    'canvas_upsell_display',                15 );
remove_action( 'woocommerce_before_shop_loop_item_title',  'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_after_shop_loop_item_title',      'woocommerce_show_product_loop_sale_flash', 6 );

/**
 * Header
 *
 * @see  canvas_product_search()
 * @see  canvas_header_cart()
 */
//add_action( 'canvas_header', 'canvas_product_search', 40 );
//add_action( 'canvas_header', 'canvas_header_cart',    60 );

/**
 * Cart fragment
 *
 * @see canvas_cart_link_fragment()
 */
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'canvas_cart_link_fragment' );
} else {
	add_filter( 'add_to_cart_fragments', 'canvas_cart_link_fragment' );
}
