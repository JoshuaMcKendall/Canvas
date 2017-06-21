<?php

// function canvas_gallery() {
//    wp_enqueue_script('gallery_script', get_template_directory_uri().'/_assets/js/gallery.js', '', '1.5', true);
// }

if (is_admin()) {
  add_action( 'wp_ajax_canvas_contact_send_email', 'canvas_contact_send_email_callback' );
  add_action( 'wp_ajax_nopriv_canvas_contact_send_email', 'canvas_contact_send_email_callback' );
}


function canvas_contact_send_email_callback() {
  if (isset($_POST['message-name'], $_POST['message-email'], $_POST['message-text'])) {
   //sanitize user posted variables
   $name =  sanitize_text_field($_POST['message-name']);
   $email = sanitize_email($_POST['message-email']);
   $message = sanitize_text_field($_POST['message-text']);
   $spam = sanitize_text_field($_POST['title']);
   $domain = 'contact@joshuamckendall.com';

   //php mailer variables
   $to = get_option('admin_email');
   $subject = "New Message from ".$name;
   $headers = 'From: '.$name.' <'.$domain.'>' . "\r\n" ;
   $headers .='Reply-To: '. $email . "\r\n" ;
   $headers .='X-Mailer: PHP/' . phpversion();
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

   if ($name == 'Name' || $message == 'Message') {
     echo "failure";
     exit();
   } else if (!is_email($email)) {
     echo "failure";
     exit();
   } else if (empty($name) || empty($message)) {
     echo "failure";
     exit();
   } else if (isset($spam) && !empty($spam)) {
     echo "failure";
     exit();
   } else {
     $sent = mail($to, $subject, $message, $headers);
     echo "success";
     wp_die();
   }
 }
}




 // ------------------------------------------------------------------
 // Add custom post types
 // ------------------------------------------------------------------
 //
 // adds post types slides and art
 //

//Add post type Slides
add_action ('init', 'featured_slides_init');
function featured_slides_init()
{
	$slide_labels = array(
		'name' => _x('Slides', 'post type general name'),
		'singular_name' => _x('Slide', 'post type singular name'),
		'all_items' => __('All Slides'),
		'add_new' => _x('Add New Slide', 'Slides'),
		'add_new_item' => __('Add New Slide'),
		'edit_item' => __('Edit Slide'),
		'new_item' => __('New Slide'),
		'view_item' => __('View Slide'),
		'search_items' => __('Search In Slides'),
		'not_found' => __('No Slides Found'),
		'not_found_in_trash' => __('No Slides Found In Trash.'),
		'parent_item_colon' => ''
	);

	$args2 = array(
		'labels' => $slide_labels,
		'public' => false,
		'publicly_queryable' => false,
		'show_ui' => true,
		'exclude_from_search' => true,
		'query_var' => false,
		'rewrite' => false,
		'capability_true' => 'attachment',
		'hierarchical' => false,
		'menu_position' => 7,
		'supports' => array('thumbnail'),
		'menu_icon' => '',
	);
	register_post_type('slides',$args2);


}

//Add post type Art
add_action ('init', 'canvas_art_init');
function canvas_art_init()
{
	$art_labels = array(
		'name' => _x('Art', 'post type general name'),
		'singular_name' => _x('Art', 'post type singular name'),
		'all_items' => __('All Art'),
		'add_new' => _x('Add Art', 'Art'),
		'add_new_item' => __('Add Art'),
		'edit_item' => __('Edit Art'),
		'new_item' => __('New Art'),
		'view_item' => __('View Art'),
		'search_items' => __('Search In Art'),
		'not_found' => __('No Art Found'),
		'not_found_in_trash' => __('No Art Found In Trash.'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $art_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => false,
		'rewrite' => false,
		'capability_true' => 'attachment',
		'hierarchical' => false,
		'menu_position' => 6,
		'supports' => array('thumbnail','title','excerpt','publicize'),
		'taxonomies' => array('post_tag'),
		'menu_icon' => '',
		'searchable' => false
	);
	register_post_type('art',$args);


}

//Makes sure only posts are searchable
function SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}
add_filter('pre_get_posts','SearchFilter');

//Add art custom post type to main rss
function myfeed_request($qv) {
	if (isset($qv['feed']) && !isset($qv['post_type']))
		$qv['post_type'] = array('post', 'art');
	return $qv;
}
add_filter('request', 'myfeed_request');


//Change Post type art permalink to joshuamckendall.com/gallery/#post-title
function canvasGalleryLink ($url, $post) {
	if ('art' == get_post_type($post)) {
		$url = home_url().'/gallery/#'.sanitize_title($post->post_title);
		return $url;
	}
}
add_filter('post_type_link', 'canvasGalleryLink', 10, 2);



//Add art post type to at a glance widget
add_filter( 'dashboard_glance_items', 'custom_glance_items', 10, 1 );
function custom_glance_items( $items = array() ) {
    $post_types = array( 'art' );
    foreach( $post_types as $type ) {
        if( ! post_type_exists( $type ) ) continue;
        $num_posts = wp_count_posts( $type );
        if( $num_posts ) {
            $published = intval( $num_posts->publish );
            $post_type = get_post_type_object( $type );
            $text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'your_textdomain' );
            $text = sprintf( $text, number_format_i18n( $published ) );
            if ( current_user_can( $post_type->cap->edit_posts ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            } else {
            $output = '<span>' . $text . '</span>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            }
        }
    }
    return $items;
}



/******************************
* Social Links
******************************/

//social array
$sl_array = array(

	array(
		'icon_link' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px"
	 height="30px" viewBox="-9.32 -6.32 26.64 26.64" enable-background="new -9.32 -6.32 26.64 26.64" xml:space="preserve">
<g id="Layer_2">
</g>
<g id="Layer_1">
	<path fill="#36465D" d="M4-5.96C-3.158-5.96-8.96-0.158-8.96,7c0,7.157,5.802,12.96,12.96,12.96c7.157,0,12.96-5.803,12.96-12.96
		C16.96-0.158,11.157-5.96,4-5.96z M6.867,13.248c-0.458,0.162-0.919,0.245-1.383,0.25c-0.542,0.011-1.038-0.058-1.487-0.203
		c-0.45-0.146-0.821-0.339-1.113-0.578s-0.539-0.515-0.742-0.827c-0.203-0.313-0.348-0.625-0.434-0.938
		c-0.087-0.312-0.13-0.619-0.129-0.922v-4.25H0.266v-1.68c0.375-0.135,0.711-0.316,1.008-0.543s0.534-0.461,0.711-0.703
		c0.178-0.242,0.328-0.508,0.453-0.797c0.125-0.29,0.214-0.547,0.267-0.773C2.756,1.059,2.795,0.828,2.82,0.594
		c0.006-0.026,0.018-0.048,0.035-0.066C2.874,0.51,2.894,0.5,2.915,0.5H4.82v3.313h2.602v1.969H4.813v4.046
		c0,0.156,0.017,0.302,0.051,0.438c0.034,0.137,0.093,0.273,0.177,0.41c0.082,0.138,0.212,0.246,0.387,0.324
		c0.174,0.078,0.387,0.115,0.637,0.109c0.406-0.011,0.754-0.086,1.046-0.228L7.109,10.88l0.002,0.003H7.11l0.624,1.849
		C7.614,12.914,7.325,13.086,6.867,13.248z"/>
</g>
</svg>
',
		'option_code' => 'tumblr_url',
		'site' => 'Tumblr',
	),
	array(
		'icon_link' => '',
		'option_code' => 'facebook_url',
		'site' => 'Facebook',
	),
	array(
		'icon_link' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px"
	 height="30px" viewBox="-4.66 -4.16 22.32 22.32" enable-background="new -4.66 -4.16 22.32 22.32" xml:space="preserve">
<g id="Layer_2">
	<circle fill="#55ACEE" cx="6.5" cy="7" r="10.8"/>
</g>
<g id="Layer_1">
	<path fill="#FFFFFF" d="M12.655,3.187c-0.35,0.511-0.771,0.945-1.266,1.305c0.005,0.072,0.008,0.182,0.008,0.328
		c0,0.678-0.1,1.353-0.297,2.027c-0.199,0.673-0.499,1.321-0.902,1.94c-0.403,0.621-0.884,1.168-1.441,1.645
		c-0.557,0.477-1.229,0.857-2.016,1.143C5.954,11.857,5.113,12,4.218,12c-1.411,0-2.703-0.379-3.875-1.133
		c0.182,0.02,0.385,0.029,0.609,0.029c1.172,0,2.216-0.359,3.133-1.078c-0.547-0.01-1.036-0.178-1.469-0.504
		c-0.433-0.324-0.73-0.74-0.892-1.246c0.172,0.027,0.331,0.039,0.478,0.039c0.224,0,0.445-0.027,0.664-0.086
		C2.282,7.902,1.8,7.611,1.417,7.151C1.034,6.69,0.843,6.155,0.843,5.546v-0.03C1.197,5.713,1.577,5.82,1.983,5.835
		C1.64,5.605,1.366,5.307,1.164,4.938C0.961,4.568,0.859,4.167,0.858,3.734c0-0.459,0.115-0.883,0.345-1.273
		C1.833,3.237,2.6,3.858,3.504,4.324s1.871,0.725,2.902,0.777C6.364,4.903,6.344,4.711,6.344,4.523c0-0.698,0.246-1.293,0.738-1.785
		S8.169,2,8.867,2c0.729,0,1.344,0.266,1.844,0.797c0.567-0.109,1.103-0.312,1.603-0.608c-0.193,0.599-0.562,1.062-1.109,1.391
		c0.484-0.052,0.969-0.183,1.453-0.391L12.655,3.187z"/>
</g>
</svg>
',
		'option_code' => 'twitter_url',
		'site' => 'Twitter',
	),
	array(
		'icon_link' => '',
		'option_code' => 'vimeo_url',
		'site' => 'Vimeo',
	),
	array(
		'icon_link' => '',
		'option_code' => 'dribbble_url',
		'site' => 'Dribbble',
	),
	array(
		'icon_link' => '',
		'option_code' => 'behance_url',
		'site' => 'Behance',
	),
	array(
		'icon_link' => '',
		'option_code' => 'youtube_url',
		'site' => 'Youtube',
	),
	array(
		'icon_link' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px"
	 height="30px" viewBox="-9.32 -6.32 26.64 26.64" enable-background="new -9.32 -6.32 26.64 26.64" xml:space="preserve">
<g id="Layer_2">
</g>
<g id="Layer_1">
	<path fill="#5FAC75" d="M4-5.96C-3.157-5.96-8.96-0.157-8.96,7S-3.157,19.96,4,19.96S16.96,14.157,16.96,7S11.157-5.96,4-5.96z
		 M8,2.868L5.633,7.415L5.82,7.657H8v3.242H4.039l-0.344,0.233l-1.109,2.134L2.353,13.5H0v-2.367l2.367-4.555L2.181,6.344H0V3.102
		h3.961l0.345-0.233l1.108-2.134l0.234-0.233H8V2.868z"/>
</g>
</svg>',
		'option_code' => 'deviantart_url',
		'site' => 'Deviant Art',
	),
	array(
		'icon_link' => '',
		'option_code' => 'googleplus_url',
		'site' => 'Google Plus',
	),
	array(
		'icon_link' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px"
	 height="30px" viewBox="257.399 257.399 26.64 26.64" enable-background="new 257.399 257.399 26.64 26.64"
	 xml:space="preserve">
<g id="Layer_2">
</g>
<g id="svg3168" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:svg="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" inkscape:version="0.48.4 r9939" xmlns:dc="http://purl.org/dc/elements/1.1/" sodipodi:docname="New document 4">

		<sodipodi:namedview  id="base" inkscape:window-maximized="1" inkscape:cx="-612.89609" pagecolor="#ffffff" fit-margin-right="0" inkscape:window-width="1920" inkscape:object-nodes="true" fit-margin-top="0" inkscape:window-y="-8" fit-margin-bottom="0" inkscape:zoom="0.24748737" inkscape:pageshadow="2" inkscape:cy="147.0455" inkscape:current-layer="layer1" inkscape:window-x="-8" inkscape:snap-center="true" borderopacity="1.0" inkscape:pageopacity="0.0" inkscape:window-height="1018" fit-margin-left="0" showgrid="false" bordercolor="#666666" inkscape:document-units="px">
		</sodipodi:namedview>
	<path fill="#FF5900" d="M270.719,257.759c-7.158,0-12.96,5.803-12.96,12.96s5.802,12.96,12.96,12.96
		c7.158,0,12.961-5.803,12.961-12.96S277.877,257.759,270.719,257.759z M270.719,274.957c-0.069,0-0.14-0.002-0.209-0.005h0.429
		C270.866,274.955,270.793,274.957,270.719,274.957z M270.95,274.951c-0.842-0.007-1.559-0.144-1.873-0.337v-2.145
		c0.469,0.308,1.029,0.486,1.632,0.486c1.647,0,2.983-1.336,2.983-2.982c0-1.648-1.336-2.984-2.983-2.984s-2.982,1.336-2.982,2.984
		v6.486h-1.997v-1.508v-2.011v-2.974c0-2.755,2.234-4.989,4.989-4.989c2.756,0,4.989,2.234,4.989,4.989
		C275.708,272.646,273.598,274.83,270.95,274.951z"/>
</g>
</svg>
',
		'option_code' => 'patreon_url',
		'site' => 'Patreon',
	)
);

// retrieve our plugin settings from the options table
$sl_options = get_option('sl_settings');

//Display function
function sl_add_links($content) {

	global $sl_options, $sl_array;

	ob_start();
	echo '<div id="social-links">';

	foreach ($sl_array as $sl_key => $sl_content) {
		if (!empty($sl_options[$sl_content['option_code']])) {
			$content = '<a href="'.esc_url($sl_options[$sl_content['option_code']]).'" target="_blank" title="'.$sl_content['site'].'" >'.$sl_content['icon_link'].'</a>';

			echo $content;

		}
	}

	echo '</div>';
	$social = ob_get_clean();
    return $social;
}
add_shortcode( 'social_links', 'sl_add_links' );

//Admin Page
function sl_options_page() {

	global $sl_options, $sl_array;

?>
	<div class="wrap">
	<?php screen_icon(); ?>
		<h2>Social Links</h2>

		<form method="post" action="options.php">

			<?php settings_fields('sl_settings_group'); ?>

			<?php foreach ($sl_array as $sl_key => $sl_content) { ?>

			<h4><?php ?></h4>
			<p>
				<label class="description" for="<?php echo $sl_content['option_code']; ?>"><?php echo $sl_content['icon_link']; ?></label>
				<input id="<?php echo $sl_content['option_code']; ?>" name="sl_settings[<?php echo $sl_content['option_code']; ?>]" type="text" value="<?php echo $sl_options[$sl_content['option_code']]; ?>"/>
			</p>

			<?php } ?>

			<p class="submit">
				<input type="submit" class="button-primary" value="Save Options" />
			</p>

		</form>

	</div>
<?php

}

function sl_add_options_link() {
	add_options_page('Social Links', 'Social Links', 'manage_options', 'sl-options', 'sl_options_page');
}
add_action('admin_menu', 'sl_add_options_link');

function sl_register_settings() {
	register_setting('sl_settings_group', 'sl_settings');
}
add_action('admin_init', 'sl_register_settings');

//Adds lightbox to single post images
add_filter('the_content', 'my_addlightboxrel');
function my_addlightboxrel($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 data-imagelightbox="imagelightbox-blog" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}

//Add rel=lightbox
add_filter('the_content', 'addlightboxrel', 12);
function addlightboxrel ($content)
{   global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-imagelightbox="imagelightbox-blog-'.$post->ID.'"$6>$7</a>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}



//Adds Featured image to Rss feed

function add_featured_image_to_feed($content) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ){
		$content = ('post' == get_post_type()) ? '' . get_the_post_thumbnail( $post->ID, 'medium' ) . '' . $content : '' . get_the_post_thumbnail( $post->ID, 'gallery_thumb' ) . '' . $content;
		}
	return $content;
}

add_filter('the_excerpt_rss', 'add_featured_image_to_feed', 1000, 1);
add_filter('the_content_feed', 'add_featured_image_to_feed', 1000, 1);




//first add a new image size
add_image_size( 'admin-gallery-thumb', 225, 337, true);

add_filter('manage_art_posts_columns', 'new_add_post_thumbnail_column', 5);

// Add the column
function new_add_post_thumbnail_column($cols){
$cols['new_post_thumb'] = __('Preview');
return $cols;
}

// Hook into the posts an pages column managing. Sharing function callback again.
add_action('manage_art_posts_custom_column', 'new_display_post_thumbnail_column', 5, 2);

// Grab featured-thumbnail size post thumbnail and display it.
function new_display_post_thumbnail_column($col, $id){
switch($col){
case 'new_post_thumb':
if( function_exists('the_post_thumbnail') ) {
echo the_post_thumbnail( 'admin-gallery-thumb' );
}
else
echo 'Not supported in theme';
break;
}
}


/******************************
* Gallery Reading Settings
******************************/


 function canvas_settings_api_init() {
 	// Add the section to reading settings so we can add our
 	// fields to it
 	add_settings_section(
		'canvas_setting_section',
		'Gallery settings',
		'canvas_setting_section_callback_function',
		'reading'
	);

 	// Add the field with the names and function to use for our new
 	// settings, put it in our new section
 	add_settings_field(
		'canvas_setting_name',
		'Gallery pages show at most',
		'canvas_setting_callback_function',
		'reading',
		'canvas_setting_section'
	);

 	// Register our setting so that $_POST handling is done for us and
 	// our callback function just has to echo the <input>
 	register_setting( 'reading', 'canvas_setting_name' );
 } // eg_settings_api_init()

 add_action( 'admin_init', 'canvas_settings_api_init' );


 // ------------------------------------------------------------------
 // Settings section callback function
 // ------------------------------------------------------------------
 //
 // This function is needed if we added a new section. This function
 // will be run at the start of our section
 //

 function canvas_setting_section_callback_function() {
 	echo '<p>Change how many art pieces are displayed on a single page in the gallery.</p>';
 }

 // ------------------------------------------------------------------
 // Callback function for our example setting
 // ------------------------------------------------------------------
 //
 // creates a checkbox true/false option. Other types are surely possible
 //

 function canvas_setting_callback_function() {
 	$setting = esc_attr( get_option( 'canvas_setting_name' ) );
    echo "<input class='small-text' type='number' name='canvas_setting_name' value='$setting' min='1' step='1' /> Art piece(s)";
 }


 // ------------------------------------------------------------------
 // Style Admin
 // ------------------------------------------------------------------
 //
 // custom login logo
 //

	function admin_css() {
		wp_enqueue_style( 'login_css', get_template_directory_uri() . '/_assets/css/admin.css?v=2.5' );
	}
	add_action('login_head', 'admin_css');
	//add_action('wp_head', 'admin_css');
	add_action('admin_print_styles', 'admin_css' );

// Use your own external URL logo link
function wpc_url_login(){
	return "http://joshuamckendall.com";
}
add_filter('login_headerurl', 'wpc_url_login');


//remove WordPress Howdy

function no_howdy() {
    global $wp_admin_bar;
    $user_id      = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url  = get_edit_profile_url( $user_id );

	if ( ! $user_id )
		return;

	$avatar = get_avatar( $user_id, 26 );
	$howdy  = sprintf( $current_user->display_name );
	$class  = empty( $avatar ) ? '' : 'with-avatar';

	$wp_admin_bar->add_menu( array(
		'id'        => 'my-account',
		'parent'    => 'top-secondary',
		'title'     => $howdy . $avatar,
		'href'      => $profile_url,
		'meta'      => array(
			'class'     => $class,
			'title'     => __('My Account'),
		),
	) );
}

add_action('wp_before_admin_bar_render', 'no_howdy', 0);

  // ------------------------------------------------------------------
 // Redirect Search Query
 // ------------------------------------------------------------------
 //
 // redirects query to Blog if typed on any other page
 //

 function canvas_search_url_redirect() {
	if (is_search() && !empty($_GET['s'])) {
		wp_redirect(home_url('/blog/?s=').get_query_var('s'));
		exit();
	}
 }
 add_action('template_redirect', 'canvas_search_url_redirect');

 // custom title for Canvas

 function canvas_custom_title( $title, $sep ) {
	$domain = get_bloginfo( 'name' );
	//$title .= $domain;

	if (is_single()) {
		$title = the_title().' '.$sep.' Blog '.$sep.' '.$domain;
		return $title;
	} else if (is_archive()) {
		$title = $title.' Blog '.$sep.' '.$domain;
		return $title;
	} else if (!empty($_GET['s'])) {
		$title = get_query_var('s').' '.$sep.' Search '.$sep.' Blog '.$sep.' '.$domain;
		return $title;
	} else if (is_page() || is_home()) {
		$title .= $domain;
		return $title;
	} else if (is_feed()) {
		return $title;
	} else {
		return $domain;
	}

}
add_filter( 'wp_title', 'canvas_custom_title', 10, 2 );


function no_wp_logo_admin_bar_remove() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'no_wp_logo_admin_bar_remove', 0);



add_filter( 'gallery_style', 'my_gallery_style', 99 );

function my_gallery_style() {
    return "<div id='gallery' class='blog-gallery'>";
}

// First, make sure Jetpack doesn't concatenate all its CSS
add_filter( 'jetpack_implode_frontend_css', '__return_false' );

// Then, remove each CSS file, one at a time
function jeherve_remove_all_jp_css() {
  wp_deregister_style( 'AtD_style' ); // After the Deadline
  wp_deregister_style( 'jetpack_likes' ); // Likes
  wp_deregister_style( 'jetpack_related-posts' ); //Related Posts
  wp_deregister_style( 'jetpack-carousel' ); // Carousel
  wp_deregister_style( 'grunion.css' ); // Grunion contact form
  wp_deregister_style( 'the-neverending-homepage' ); // Infinite Scroll
  wp_deregister_style( 'infinity-twentyten' ); // Infinite Scroll - Twentyten Theme
  wp_deregister_style( 'infinity-twentyeleven' ); // Infinite Scroll - Twentyeleven Theme
  wp_deregister_style( 'infinity-twentytwelve' ); // Infinite Scroll - Twentytwelve Theme
  wp_deregister_style( 'noticons' ); // Notes
  wp_deregister_style( 'post-by-email' ); // Post by Email
  wp_deregister_style( 'publicize' ); // Publicize
  wp_deregister_style( 'sharedaddy' ); // Sharedaddy
  wp_deregister_style( 'sharing' ); // Sharedaddy Sharing
  wp_deregister_style( 'stats_reports_css' ); // Stats
  wp_deregister_style( 'jetpack-widgets' ); // Widgets
  wp_deregister_style( 'jetpack-slideshow' ); // Slideshows
  wp_deregister_style( 'presentations' ); // Presentation shortcode
  wp_deregister_style( 'jetpack-subscriptions' ); // Subscriptions
  wp_deregister_style( 'widget-conditions' ); // Widget Visibility
  wp_deregister_style( 'jetpack_display_posts_widget' ); // Display Posts Widget
  wp_deregister_style( 'gravatar-profile-widget' ); // Gravatar Widget
  wp_deregister_style( 'widget-grid-and-list' ); // Top Posts widget
  wp_deregister_style( 'jetpack-widgets' ); // Widgets
}
add_action('wp_print_styles', 'jeherve_remove_all_jp_css' );