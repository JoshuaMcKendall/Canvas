<?php

add_theme_support( 'custom-logo', array(
	'height'      => 42,
	'width'       => 339.95,
	'flex-height' => false,
	'flex-width'  => false,
	'header-text' => array( 'site-title', 'site-description' ),
) );

function canvas_custom_logo() {

    $output = '';
    if (function_exists('get_custom_logo'))
        $output = get_custom_logo();

    if (empty($output))
        $output = '<a href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>';

    echo $output;
}

function canvas_site_icon() {

	$output = '';
	if (has_site_icon()) {
		// User set a Site Icon, do something awesome!
	}
	else {
		// User didn't set a Site Icon, do something else. But still awesome.
	}

	echo $output;
}