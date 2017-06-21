<?php $version = 8.36; ?>
<!DOCTYPE html>

<!--CANVAS v2.6 by Joshua Mckendall-->

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->

<head id="<?php bloginfo('url'); ?>" data-template-set="canvas">

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php wp_title('|',true,'right'); ?></title>

	<meta name="title" content="<?php bloginfo('name'); ?>" />

	<meta name="description" content="<?php bloginfo('description'); ?>" />

	<meta name="author" content="<?php bloginfo('name'); ?>" />

	<meta name="google-site-verification" content="s_sehS6luXZFRkVNyjMgLJYWnU3FDCyMrdeiGo3xpGg" />

	<meta name="Copyright" content="Copyright &copy; <?php bloginfo('name'); ?> <?php echo date('Y'); ?>. All Rights Reserved.">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	
	<meta name="theme-color" content="#fff">

	<meta name="msapplication-TileColor" content="#fff"/>

	<meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/_assets/img/mstile-144x144.png">

	<meta name="application-name" content="<?php bloginfo('name'); ?>">

	<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
	
	<link rel="home" href="http://joshuamckendall.com">
	
	<link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/manifest.json?v=<?php echo $version; ?>">

	<?php echo wp_site_icon(); ?>



	<?php
	//Conditionally add Javascript
		if (is_front_page()) {
			add_action( 'wp_enqueue_scripts', 'canvas_flexslider' );
		}
		if (is_page_template('contact.php')) {
			add_action( 'wp_enqueue_scripts', 'canvas_contact' );
		}
		if (is_page_template('gallery.php') || is_search() || is_archive() || is_single() || is_home()) {
			add_action( 'wp_enqueue_scripts', 'canvas_unveil' );
			// add_action( 'wp_enqueue_scripts', 'canvas_gallery' );
		}

		//add_action( 'wp_enqueue_scripts', 'canvas_modernizr' );
	?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">


	<?php wp_head(); ?>
</head>

<body class="logo-image">

<div id="canvas">
	<div id="wrapper">
		<header id="header">
			<div id="logo">
			<h1 class="logo">
			<?php echo canvas_custom_logo(); ?>
			</h1>
			<small>
				<?php bloginfo('description'); ?>
			</small>
		</div>
			<div id="top-nav">
			<nav id="nav" class="main-nav">
						<?php wp_nav_menu( array('menu' => 'Menu 1') ); ?>
			</nav>
		</div>
		</header>
