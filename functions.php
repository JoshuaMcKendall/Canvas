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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// hook failed login
//add_action('wp_login_failed', 'my_front_end_login_fail'); 

function my_front_end_login_fail($username){
    // Get the reffering page, where did the post submission come from?
    $referrer = add_query_arg('login', false, $_SERVER['HTTP_REFERER']);

    // if there's a valid referrer, and it's not the default log-in screen
    if(!empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin')){
        // let's append some information (login=failed) to the URL for the theme to use
        wp_redirect($referrer . '?login=failed'); 
    exit;
    }
}


//add_action( 'login_head', 'my_frontend_login_no_pass_no_username' );

function my_frontend_login_no_pass_no_username(){
    $referrer = add_query_arg('login', false, $_SERVER['HTTP_REFERER']);
    if ( (!isset($_REQUEST['user_login']) || ( isset( $_REQUEST['user_login'] ) && trim( $_REQUEST['user_login'] ) == '' ) ) || (!isset($_REQUEST['user_pass']) || ( isset( $_REQUEST['user_pass'] ) && trim( $_REQUEST['user_pass'] ) == '' ) ) ){
        wp_redirect( add_query_arg('login', 'failed', $referrer) ); 
        exit; 
    }   
}

//add_filter( 'login_url', 'canvas_filter_site_url',   10, 3 );


function canvas_filter_site_url(  $url, $path, $scheme  ) {
	global $pagenow;

	// Bail if currently visiting wp-login.php
	if ( 'wp-login.php' == $pagenow ) {
		return $url;
	}

	// Bail if currently in /wp-admin
	if ( is_admin() && ! canvas_is_post_request() ) {
		return $url;
	}

	// Bail if currently customizing
	if ( is_customize_preview() ) {
		return $url;
	}

	// Parse the URL
	$parsed_url = parse_url( $url );

	// Determine the path
	$path = '';
	if ( ! empty( $parsed_url['path'] ) ) {
		$path = basename( trim( $parsed_url['path'], '/' ) );
	}

	// Parse the query
	$query = array();
	if ( ! empty( $parsed_url['query'] ) ) {
		parse_str( htmlspecialchars_decode( $parsed_url['query'] ), $query );
	}

	// Determine the action
	switch ( $path ) {
		case 'wp-login.php' :
			// Determine the action
			$action = isset( $query['action'] ) ? $query['action'] : 'login';

			// Fix some alias actions
			if ( 'retrievepassword' == $action ) {
				$action = 'lostpassword';
			} elseif ( 'rp' == $action ) {
				$action = 'resetpass';
			}

			// Unset the action
			unset( $query['action'] );
			break;

		// case 'wp-signup.php' :
		// 	$action = 'signup';
		// 	break;

		// case 'wp-activate.php' :
		// 	$action = 'activate';
		// 	break;

		default :
			return $url;
	}

	// Bail if not a TML action
	// if ( ! tml_action_exists( $action ) ) {
	// 	return $url;
	// }

	// Get the URL
	$url = home_url( $action, $scheme );

	// Add the query
	$url = add_query_arg( $query, $url );

	return $url;

}


/**
 * Determine if the current request is a wp-login.php request.
 *
 * @since 7.0
 *
 * @return bool
 */
function canvas_is_wp_login() {
	global $pagenow;

	return ( 'wp-login.php' == $pagenow );
}

/**
 * Determine if the current request is a GET request.
 *
 * @since 7.0
 *
 * @return bool
 */
function canvas_is_get_request() {
	return 'GET' === strtoupper( $_SERVER['REQUEST_METHOD'] );
}

/**
 * Determine if the current request is a POST request.
 *
 * @since 7.0
 *
 * @return bool
 */
function canvas_is_post_request() {
	return 'POST' === strtoupper( $_SERVER['REQUEST_METHOD'] );
}
