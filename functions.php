<?php

/**
 * Canvas functions.php
 *
 * @package canvas
 */

/**
 * Assign the Canvas version to a var
 */
$theme = wp_get_theme( 'canvas_3' );

$canvas_version = $theme['Version'];

/**
 * Include icon functions
 */
require get_parent_theme_file_path( '/_include/icon-functions.php' );

/**
 * Include general temnplate functions
 */
require get_parent_theme_file_path( '/_include/canvas-functions.php' );

/**
 * Include categories walker class
 */
require get_parent_theme_file_path( '/_include/canvas-category-walker.php' );

/**
 * Include categories widget class
 */
require get_parent_theme_file_path( '/_include/categories-widget.php' );

/**
 * Include user widget class
 */
require get_parent_theme_file_path( '/_include/user-widget.php' );

if( function_exists( 'bp_is_active' ) ) {

	/**
	 * Include BuddyPress functions
	 */
	require get_parent_theme_file_path( '/_include/buddypress/buddypress-functions.php' );

}



/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/_include/template-tags.php' );


$canvas = (object) array(

    'version'       => $canvas_version,
    'main'          => require '_include/class-canvas.php',
    //'customizer'    => require '_include/customizer/class-canvas-customizer.php'

);


if ( canvas_is_woocommerce_activated() ) {
    $canvas->woocommerce = require '_include/woocommerce/class-canvas-woocommerce.php';

    require '_include/woocommerce/canvas-woocommerce-template-hooks.php';
    require '_include/woocommerce/canvas-woocommerce-template-functions.php';
}




function canvas_register_scripts() {
    //De-registers script
    //wp_deregister_script('jquery');

    //wp_enqueue_style('open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,600');
    wp_enqueue_style( 'style_css', get_template_directory_uri() . '/style.css' );
}
add_action('wp_enqueue_scripts', 'canvas_register_scripts');




function unhighlight_blog_nav_menu_item( $sorted_menu_items, $args ) {

    if ( is_404() || is_search() ) {

        foreach( $sorted_menu_items as $id => $menu_item ) {

            foreach( $sorted_menu_items[$id]->classes as $classid => $classname ) {

                if( $classname == 'current_page_parent' ) {

                    unset( $sorted_menu_items[$id]->classes[$classid] );


                }

            }

        }

    }

    return $sorted_menu_items;
}

add_filter( 'wp_nav_menu_objects', 'unhighlight_blog_nav_menu_item', 10, 2 );