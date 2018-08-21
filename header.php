<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 * @version 3.0.0
 */

?>

<!DOCTYPE html>

<html <?php language_attributes(); ?> class="no-js no-svg">

	<head id="<?php bloginfo('url'); ?>" data-template-set="canvas">

		<meta charset="<?php bloginfo( 'charset' ); ?>">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<meta name="title" content="<?php bloginfo('name'); ?>" />

		<meta name="description" content="<?php esc_html( bloginfo('description') ); ?>" />

		<meta name="copyright" content="<?php echo date('Y'); ?> <?php bloginfo('name'); ?> ">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

		<?php echo wp_site_icon(); ?>

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php wp_head(); ?>

		<noscript class="lazyload">
			
			<?php do_action( 'canvas_lazy_load' ); ?>

		</noscript>

	</head>

	<body <?php body_class(); ?>>

		<?php do_action('canvas_before_header'); ?>

		<header class="site-header row">

			<?php

			do_action('canvas_header');

			do_action('canvas_header_before_navigation'); ?>

			<nav id="top-nav-menu" class="site-navigation column col-lg-12 col-md-12 col-sm-12 col-xs-12" role="navigation">

				<?php 

					do_action('canvas_header_before_branding');

					get_template_part( '_template-parts/header/site', 'branding' );

					do_action('canvas_header_after_branding');

					do_action('canvas_header_navigation');

					do_action('canvas_header_before_navigation_bar');

				?>

				<div class="navigation-bar-container">

					<ul id="navigation-bar">

						<?php

						if( function_exists( 'bp_is_active' ) || canvas_is_woocommerce_activated() ) {

							get_template_part( '_template-parts/navigation/menu-item', 'search' );

						}

						do_action( 'canvas_before_nav_menu' );

						wp_nav_menu( array(

							'theme_location' 		=> 'top', 
							'menu_id' 				=> 'top-menu', 
							'container'				=> 'li',
							'container_id'			=> 'main-menu',
							'container_class'		=> 'top-menu-list-item menu-item priority-nav',
							'items_wrap'			=> canvas_top_menu_wrap()

						) );


						do_action('canvas_after_nav_menu');

						if( canvas_is_woocommerce_activated() ) {

							echo canvas_header_cart();

						}

						if( function_exists( 'bp_is_active' ) || canvas_is_woocommerce_activated() ) {

							get_template_part( '_template-parts/navigation/menu-item', 'user' );

						} else {

							get_template_part( '_template-parts/navigation/menu-item', 'search' );

						}

						?>

					</ul><!-- #navigation-bar -->			

				</div><!-- .navigation-bar-container -->

				<?php

					do_action('canvas_header_after_navigation_bar');

				?>

			</nav>

			<?php 

			do_action('canvas_header_after_navigation'); ?>

		</header>

		<?php do_action('canvas_after_header'); ?>

		<main id="main" class="site-main row" role="main">

			<?php 
			/**
			 * Functions hooked in to canvas_before_main_content
			 *
			 * @hooked BP_Legacy() -> sitewide_notices()	- 10
			 * @src  ./_include/buddypress/buddypress-functions.php
			 */
			do_action('canvas_before_main_content');