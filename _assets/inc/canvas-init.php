<?php

if ( ! isset( $content_width ) )
	$content_width = 630;

add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'quote', 'link' ) );

//Gets rid of stupid wp-emoji scripts and style
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' ); 

function canvasRegisterScript () {
//De-registers script
wp_deregister_script('jquery');

wp_enqueue_style( 'style_css', get_template_directory_uri() . '/style.css' );

//Registers local jquery script in _assets/js/jquery.js
wp_register_script( 'jquery', 'http' . ($_SERVER['SERVER_PORT'] == 443 ? 's' : '') . '://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', '', '2.1.3', true);

wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'canvasRegisterScript');

//Add thumbnail support
add_theme_support( 'post-thumbnails' );

//registers custom menu
function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );


$nav_menus = array(

	array(
		'title' => 'Home',
		'content' => '',
		'template' => 'homepage.php'

	),
	array(
		'title' => 'About',
		'content' => 'Hello my name is Joshua Mckendall and I’m an artist. Born in 1989, I’ve been drawing since the day I could hold a pencil. I am mainly self taught and drawing and painting has consumed my life ever since I was a child. Born and raised in the United States it is my dream to live abroad. I am currently enrolled in college and plan to graduate mid 2013.My interests and hobbies include, but are not limited to: Art, technology, comics, learning spoken and written Japanese, good food, photography, reading, writing, movies, music, and video games.',
		'template' => 'about.php'

	),
	array(
		'title' => 'Gallery',
		'content' => '',
		'template' => 'gallery.php'

	),
	array(
		'title' => 'Contact',
		'content' => 'For general questions or commissions use the contact form below, I respond fairly quickly. You can also contact me through the various social networking links below. Your e-mail is not stored or distributed by this system but will be attached to your message.',
		'template' => 'contact.php'

	),
	array(
		'title' => 'Blog',
		'content' => '',
		'template' => 'blog.php'

	),
	array(
		'title' => 'Archives',
		'content' => '',
		'template' => 'archive.php'

	)
					);
//Adds Menu Pages
if (isset($_GET['activated']) && is_admin()){

	foreach ($nav_menus as $key => $menu) {
        $new_page_title = $menu['title'];
        $new_page_content = $menu['content'];
        $new_page_template = $menu['template']; //ex. template-custom.php. Leave blank if you don't want a custom page template.
        //don't change the code bellow, unless you know what you're doing
        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                'post_type' => 'page',
                'post_title' => $new_page_title,
                'post_content' => $new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
        if(!isset($page_check->ID)){
                $new_page_id = wp_insert_post($new_page);
                if(!empty($new_page_template)){
                        update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
                }
        }
    }
}


//Adds the current-menu-item class to Blog link when viewing single post.
//(http://codex.wordpress.org/Function_Reference/wp_nav_menu)
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item){
     if(is_single() && $item->title == "Blog"){ //Notice you can change the conditional from is_single() and $item->title
             $classes[] = "current-menu-item";
     }

     if ( is_archive() && $item->title == "Blog" ) {

			$classes[] = "current-menu-item";

	}

	 if ( is_search() && $item->title == "Blog" ) {

			$classes[] = "current-menu-item";

	}

     return $classes;
}

//Overrides Wordpress' Width and Height in img src http://www.blissfulinterfaces.com/making-wordpress-images-responsive/
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );
add_filter( 'wp_get_attachment_link', 'remove_thumbnail_dimensions', 10 );
function remove_thumbnail_dimensions( $html ) {
$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
return $html; }

add_image_size( 'large_thumb', 300, 150, true );
add_image_size( 'larger_thumb', 450, 225, true );
add_image_size( 'front_slide', 900, 450, true );
add_image_size( 'front_slide_large', 900, 600, true );
add_image_size( 'gallery_thumb', 225, 337, true );
add_image_size( 'gallery_full', 900, 1024 );


//Enqueues scripts to wp_head on the front end.
function canvas_flexslider() {
   wp_enqueue_script('flexslider_script', get_template_directory_uri().'/_assets/js/jquery.flexslider-min.js', array( 'jquery' ), '1.0', true);
}

// function canvas_modernizr() {
//    wp_enqueue_script('modernizr_script', get_template_directory_uri().'/_assets/js/modernizr.js', '', '', true);
// }

function canvas_unveil() {
   wp_enqueue_script('unveil_script', get_template_directory_uri().'/_assets/js/jquery.unveil.js', array( 'jquery' ), '2.1', true);
}

function canvas_contact() {
   wp_register_script('contact_script', get_template_directory_uri().'/_assets/js/contact.min.js', '', '3.10', true);
   wp_localize_script('contact_script', 'contact', array('ajaxurl' => admin_url( 'admin-ajax.php' )));
   wp_enqueue_script('contact_script');
}