<?php
/**
 * Canvas Class
 *
 * @author   Joshua McKendall
 * @since    3.0.0
 * @package  canvas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Canvas' ) ) :

	/**
	 * The main Canvas class
	 */
	class Canvas {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'init', 										array( $this, 'setup_profile_block' ) );
			add_action( 'after_setup_theme',          					array( $this, 'setup' ) );
			add_action( 'widgets_init',               					array( $this, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts',         					array( $this, 'scripts' ),       10 );
			add_action( 'wp_enqueue_scripts',         					array( $this, 'child_scripts' ), 30 ); // After WooCommerce.
			add_filter( 'body_class',                 					array( $this, 'body_classes' ) );
			add_filter( 'wp_page_menu_args',          					array( $this, 'page_menu_args' ) );
			//add_filter( 'navigation_markup_template', 				array( $this, 'navigation_markup_template' ) );
			//add_action( 'enqueue_embed_scripts',      				array( $this, 'print_embed_styles' ) );
			add_action( 'template_redirect', 		  					array( $this, 'content_width' ), 0 );
			add_action( 'canvas_post_meta',		  	  					array( $this, 'get_post_meta' ), 0, 1 );
			add_action( 'canvas_post_meta',								array( $this, 'get_article_categories' ), 5 );
			add_action( 'canvas_post_meta',								array( $this, 'get_edit_post_meta_item' ), 10 );
			add_action( 'bp_member_options_nav',	  					array( $this, 'get_menu_list_items' ), 10, 1 );
			add_action( 'canvas_menu_item_user_avatar',					array( $this, 'canvas_render_user_menu_item' ) );
			add_action( 'canvas_menu_item_shopping_cart',				array( $this, 'canvas_render_cart_menu_item' ) );
			add_action( 'wp_ajax_loadmore',								array( $this, 'canvas_loadmore_ajax_handler' ) );
			add_action( 'wp_ajax_nopriv_loadmore',						array( $this, 'canvas_loadmore_ajax_handler' ) );
			// add_action('wp_ajax_cloadmore', 							array( $this, 'canvas_comments_loadmore_handler') );
			// add_action('wp_ajax_nopriv_cloadmore', 						array( $this, 'canvas_comments_loadmore_handler') );
			// add_action('wp_ajax_ajax_comments', 						array( $this, 'canvas_submit_ajax_comment') );
			// add_action('wp_ajax_nopriv_ajax_comments', 					array( $this, 'canvas_submit_ajax_comment') );

			//add_action( 'canvas_cart_dropdown_menu_body', 			array( $this, 'canvas_render_cart_links' ) );

			add_filter( 'wpcf7_default_template', 						array( $this, 'modify_contact7_form_content' ), 10, 2 );
			add_filter( 'wpcf7_contact_form_default_pack', 				array( $this, 'modify_contact7_form_title' ), 10, 1 );
			add_filter( 'wp_nav_menu_container_allowedtags',			array( $this, 'modify_allowed_tags' ), 10, 1 );
			add_filter( 'conductor_render_back_button',					array( $this, 'canvas_render_back_btn' ), 10, 2 );	
			add_filter( 'the_title',									array( $this, 'filter_post_type_title' ), 10, 2 );
			add_filter( 'feed_link',									array( $this, 'filter_feed_link' ), 10, 1 );
			add_filter( 'get_comment_author_link',						array( $this, 'link_to_profile' ), 10, 1 );
			add_filter( 'get_comment_author_url',						array( $this, 'link_to_profile_url' ), 10, 3 );	
			add_filter( 'query_vars',									array( $this, 'comment_sort_query_var' ), 10, 1 );		 
		}

		public function setup_profile_block() {


			wp_register_script(
				'canvas-profile-block-script', // Handle.
				get_template_directory_uri() . '/js/profile/block.js', // Block.js: We register the block here.
				array( 'wp-blocks', 'wp-element', 'wp-i18n' ) // Dependencies, defined above.
			);

				// Register the block with WP using our namespacing
				// We also specify the scripts and styles to be used in the Gutenberg interface
				register_block_type( 'canvas/profile-block', array(
					'editor_script' => 'canvas-profile-block-script',
					'editor_style' => 'canvas-profile-block-editor-style',
					'style' => 'canvas-profile-block-editor-style',
				) );

		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			/*
			 * Load Localisation files.
			 *
			 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
			 */

			// Loads wp-content/languages/themes/canvas-it_IT.mo.
			load_theme_textdomain( 'canvas', trailingslashit( WP_LANG_DIR ) . 'themes/' );

			// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
			load_theme_textdomain( 'canvas', get_stylesheet_directory() . '/languages' );

			// Loads wp-content/themes/canvas/languages/it_IT.mo.
			load_theme_textdomain( 'canvas', get_template_directory() . '/languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );

			add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );

			/**
			 * Enable support for site logo
			 */
		    add_theme_support( 'custom-logo', array(
		        'flex-height' => true,
		        'flex-width'  => true,
		        'header-text' => array( 'site-title', 'site-description' ),
		    ) );

			// This theme uses wp_nav_menu() in two locations.
		    register_nav_menus( apply_filters( 'canvas_register_nav_menus', array(
		        'top'       => __('Top Menu', 'canvas'),
		    ) ) );

			/*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', apply_filters( 'canvas_html5_args', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			) ) );

			// Declare WooCommerce support.
			add_theme_support( 'woocommerce', apply_filters( 'canvas_woocommerce_args', array(
				'single_image_width'    => 416,
				'thumbnail_image_width' => 324,
				'product_grid'          => array(
					'default_columns' => 3,
					'default_rows'    => 4,
					'min_columns'     => 1,
					'max_columns'     => 6,
					'min_rows'        => 1
				)
			) ) );

			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );

			// Declare support for title theme feature.
			add_theme_support( 'title-tag' );

			// Declare support for selective refreshing of widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );
		}

		/**
		 * Register widget area.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function widgets_init() {

			$sidebar_args['top_blog_sidebar'] = array(
		        'name'          => __('Top Blog Sidebar', 'canvas'),
		        'id'            => 'sidebar-1',
		        'description'   => __('Add widgets here to appear in your top sidebar on blog posts and archive pages.', 'canvas')
		    );

			$sidebar_args['bottom_blog_sidebar'] = array(
		        'name'          => __('Bottom Blog Sidebar', 'canvas'),
		        'id'            => 'sidebar-2',
		        'description'   => __('Add widgets here to appear in your bottom sidebar on blog posts and archive pages.', 'canvas')
		    );

		    $sidebar_args['shop_sidebar'] = array(
		        'name'          => __('Shop Sidebar', 'canvas'),
		        'id'            => 'sidebar-3',
		        'description'   => __('Add widgets here to appear in your sidebar on shop posts and archive pages.', 'canvas')
		    );

		    $sidebar_args['members_sidebar'] = array(
		        'name'          => __('Members Sidebar', 'canvas'),
		        'id'            => 'sidebar-4',
		        'description'   => __('Add widgets here to appear in your sidebar on the members page.', 'canvas')
		    );

			$rows    = intval( apply_filters( 'canvas_footer_widget_rows', 1 ) );
			$regions = intval( apply_filters( 'canvas_footer_widget_columns', 4 ) );

			for ($row = 1; $row <= $rows; $row++) { 
				for ($region = 1; $region <= $regions; $region++) { 

					$footer_n = $region + $regions * ( $row - 1 ); // Defines footer sidebar ID.
					$footer   = sprintf( 'footer_%d', $footer_n );

					if ( 1 == $rows ) {
						$footer_region_name = sprintf( __( 'Footer Column %1$d', 'canvas' ), $region );
						$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of the footer.', 'canvas' ), $region );
					} else {
						$footer_region_name = sprintf( __( 'Footer Row %1$d - Column %2$d', 'canvas' ), $row, $region );
						$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of footer row %2$d.', 'canvas' ), $region, $row );
					}

					$sidebar_args[ $footer ] = array(
						'name'        => $footer_region_name,
						'id'          => sprintf( 'footer-widget-area-%d', $footer_n ),
						'description' => $footer_region_description,
					);

				}
			}

			$sidebar_args = apply_filters( 'canvas_sidebar_args', $sidebar_args );

			foreach ( $sidebar_args as $sidebar => $args ) {

				if( $sidebar != 'bottom_blog_sidebar' ) {

					$widget_tags = array(
				        'before_widget' => '<section id="%1$s" class="widget %2$s">',
				        'after_widget'  => '</section>',
				        'before_title'  => '<span class="widget-title">',
				        'after_title'   => '</span>'
					);

				} else {

					$widget_tags = array(
				        'before_widget' => '<section id="%1$s" class="column col-xs-12 col-sm-12 col-md-6 col-lg-12 widget %2$s">',
				        'after_widget'  => '</section>',
				        'before_title'  => '<span class="widget-title">',
				        'after_title'   => '</span>'
					);

				}

				/**
				 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
				 *
				 * 'canvas_header_widget_tags'
				 * 'canvas_sidebar_widget_tags'
				 *
				 * 'canvas_footer_1_widget_tags'
				 * 'canvas_footer_2_widget_tags'
				 * 'canvas_footer_3_widget_tags'
				 * 'canvas_footer_4_widget_tags'
				 */
				$filter_hook = sprintf( 'canvas_%s_widget_tags', $sidebar );
				$widget_tags = apply_filters( $filter_hook, $widget_tags );

				if ( is_array( $widget_tags ) ) {
					register_sidebar( $args + $widget_tags );
				}
			}

		    register_widget( 'Canvas_Categories_Widget' );

		    if( function_exists( 'bp_is_active' ) ) {

				register_widget( 'Canvas_User_Widget' );

		    }

		}

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * Priority 0 to make it available to lower priority callbacks.
		 *
		 * @global int $content_width
		 */
		public function content_width() {

		    $content_width = '';


		    if ( canvas_is_frontpage() ) {
		        $content_width = 960;
		    } elseif( is_home() ) {

		    	$content_width = 690;

		    } elseif ( is_page() ) {
		        $content_width = 960;
		    }

		    // Check if is single post and there is no sidebar.
		    if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		        $content_width = 690;
		    } else {
		    	$content_width = 690;

		    }

		    /**
		     * Filter Canvas content width of the theme.
		     *
		     * @since Canvas
		     *
		     * @param int $content_width Content width in pixels.
		     */
		    $GLOBALS['content_width'] = apply_filters( 'canvas_content_width', $content_width );
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @return array
		 */
		public function body_classes( $classes ) {

			$classes[] = 'canvas';

			$classes[] = 'container';
			// Adds a class of group-blog to blogs with more than 1 published author.
			if ( is_multi_author() ) {
				$classes[] = 'group-blog';
			}

			if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
				$classes[]	= 'no-wc-breadcrumb';
			}

			// If our main sidebar doesn't contain widgets, adjust the layout to be full-width.
			if ( ! is_active_sidebar( 'sidebar-1' ) ) {
				$classes[] = 'canvas-full-width-content';
			}

			// Add class when using homepage template + featured image
			if ( is_page_template( 'template-homepage.php' ) && has_post_thumbnail() ) {
				$classes[] = 'has-post-thumbnail';
			}

			return $classes;
		}

		/**
	     * Determine whether to render post meta time item.
	     *
	     * @param 	$post_id 	int 	The id of the associated post.
	     *
	     * @return $enabled 	bool 	Whether or not time item is enabled.
	     */
		public function canvas_time_link_enabled( $post_id ) {

			$enabled = true; // Time permalink should always be enabled

			return apply_filters( 'canvas_time_link_enabled', $enabled, $post_id );

		}

		/**
	     * Gets a nicely formatted string for the published date.
	     */
	    public function canvas_time_link( $post_id ) {
	        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	        // if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
	        //     $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	        // }

	        $time_string = sprintf( $time_string,
	            get_the_date( DATE_W3C ),
	            get_the_date( __('M j, Y') ),
	            get_the_modified_date( DATE_W3C ),
	            get_the_modified_date()
	        );

	        $icon = canvas_get_svg_icon( array( 
	            'icon'  => 'calendar',
	            'size'  => 'sm'
	         ) );

	        $post_date = ( is_single() ) ? '<small class="meta-text" ><span class="icon icon-left">'.$icon.'</span><strong>' . $time_string . '</strong></small>' : '<small><strong><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="link link-secondary"><span class="icon icon-left">'.$icon.'</span>' . $time_string . '</a></strong></small>';

	        // Wrap the time string in a link, and preface it with 'Posted on'.
	        return apply_filters( 'canvas_time_link', sprintf(
						            /* translators: %s: post date */
						            __( '%s', 'canvas' ),
						            $post_date
						        ), $post_date, $icon, $time_string );
	    }

		/**
	     * Determine whether to render post meta comments item.
	     *
	     * @param 	$post_id 	int 	The id of the associated post.
	     *
	     * @return 	$enabled 	bool 	Whether or not comments item is enabled.
	     */
		public function canvas_comments_link_enabled( $post_id ) {

			if( comments_open( $post_id ) ) {

				$enabled = true;

			} elseif( get_comments_number( $post_id ) > 1 ) {

				$enabled = true;

			} else {

				$enabled = false;

			}

			if( is_single() ) {

				$enabled = false; 

			}

			return apply_filters( 'canvas_comments_link_enabled', $enabled, $post_id );

		}

	    public function canvas_comments_link( $post_id ) {

	    	$comment_count = get_comments_number();

	        $icon = canvas_get_svg_icon( array( 
	            'icon'  => 'message-circle',
	            'size'  => 'sm'
	         ) );

	        return sprintf(
	            __('%s', 'canvas'),
	            '<small><strong><a href="' . esc_url( get_comments_link() ) . '" rel="bookmark" class="link link-secondary"><span class="icon icon-left">'. $icon .'</span>' . esc_html( $comment_count ) . '</a></strong></small>'
	        );
	    }

		/**
	     * Determine whether to render post meta permalink item.
	     *
	     * @param 	$post_id 	int 	The id of the associated post.
	     *
	     * @return 	$enabled 	bool 	Whether or not permalink item is enabled.
	     */
		public function canvas_perma_link_enabled( $post_id ) {

			$enabled = true;

			if( is_single() ) {

				$enabled = false; 

			}

			return apply_filters( 'canvas_perma_link_enabled', $enabled, $post_id );

		}

	    public function canvas_perma_link( $post_id ) {

	        $icon = canvas_get_svg_icon( array( 
	            'icon'  => 'link-2',
	            'size'  => 'sm'
	         ) );

	        return '<small><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="link link-secondary"><span class="icon icon-left">'.$icon.'</span></a></small>';
	    }

	    public function sort_meta_list_items( $items ) {

	    	$sorted = array();

	    	foreach ( $items as $key => $item ) {
	    		
	    		$position = 99;

	    		if( isset( $item['position'] ) ) {

	    			$position = (int) $item['position'];

	    		}

	    		if( isset( $sorted[ $position ] ) ) {

	    			$sorted_keys = array_keys( $sorted );

	    			do {

	    				$position += 1;

	    			} while( in_array( $position, $sorted_keys ) );

	    		}

	    		$item['key'] = $key;

	    		$sorted[ $position ] = $item;

	    	}

	    	ksort( $sorted );

	    	return $sorted;

	    }



	    public function get_post_meta_items( $post_id ) {

	    	return apply_filters( 'canvas_meta_items', array(

						'time'			=> array(

							'link'		=> $this->canvas_time_link( $post_id ),
							'enabled'	=> $this->canvas_time_link_enabled( $post_id ),
							'position'	=> 10

						),
						'comments'		=> array(

							'link'		=> $this->canvas_comments_link( $post_id ),
							'enabled'	=> $this->canvas_comments_link_enabled( $post_id ),
							'position'	=> 20

						),
						'permalink'		=> array(

							'link'		=> $this->canvas_perma_link( $post_id ),
							'enabled'	=> $this->canvas_perma_link_enabled( $post_id ),
							'position'	=> 30

						),

					) );

	    }


		public function get_post_meta( $post_id ) {

			$meta_items = $this->get_post_meta_items( $post_id );

			$list_items = $this->sort_meta_list_items( $meta_items );

			$list = '';

			foreach ( $list_items as $list_item ) {
				
				if( ! $list_item['enabled'] ) {

					continue;

				}

				$list .= '<li class="'.$list_item['key'].' list-item">'.$list_item['link'].'</li>';

			}

			echo $list;

		}

		public function get_edit_post_meta_item() {

			if( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {

				$edit_icn = canvas_get_svg_icon( array( 

					'icon'	=> 'edit',
					'size'	=> 'sm'

				 ) );

				$edit_post_link = '<li class="edit-post list-item"><small><strong><a href="'.esc_url( get_edit_post_link() ).'" title="Edit post" class="link link-secondary"><span class="icon">'.$edit_icn.'</span></a></strong></small></li>';

				echo $edit_post_link;

			}

		}


		public function get_article_categories() {

			if( is_single() ) {

				$folder_icn = canvas_get_svg_icon( array( 

					'icon'	=> 'folder',
					'size'	=> 'sm'

				 ) );

				$categories = get_the_category();

				$category_list = '';

				$top_category = $categories[0]->name;

				// foreach ( $categories as $key => $category ) {
					

				// }

				$categories_meta = '<li class="categories list-item mini-dropdown"><small><strong><a href="#categories" title="'. esc_attr__( 'Categories', 'canvas' ) .'" class="link link-secondary"><span class="icon icon-left">'.$folder_icn.'</span> '. esc_html( $top_category ) .' </a></strong></small><label><input type="checkbox">' . esc_html( $category_list ) . '</label></li>';

				echo $categories_meta;

			}

		}


		public function display_post_meta_list_items( $post_id ) {

			$list = get_post_meta( $post_id );

			echo $list;

		}

		public function scripts() {

			global $wp_query; 

			wp_enqueue_script( 'comment-reply' );

		    wp_enqueue_script( 'navbar_js', get_template_directory_uri() . '/js/navbar.js', 'jquery', false, true );

		    wp_enqueue_style('open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,600');

		    wp_enqueue_style( 'style_css', get_template_directory_uri() . '/style.css' );
		 
			wp_register_script( 'canvas_loadmore', get_stylesheet_directory_uri() . '/js/canvas_loadmore.js', array('jquery') );
		 
			wp_localize_script( 'canvas_loadmore', 'canvas_loadmore_params', array(
				'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
				'trigger_text' => esc_html__( 'Load More', 'canvas' ),
				'loading_text' => esc_html__( 'Loading...', 'canvas' ),
				'posts' => json_encode( $wp_query->query_vars ), // Main loop
				'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
				'max_page' => $wp_query->max_num_pages
			) );
		 
		 	wp_enqueue_script( 'canvas_loadmore' );

		 	wp_register_style(

		 		'canvas-profile-block-editor-style',
		 		get_stylesheet_directory() . '/css/editor.css',
		 		array( 'wp-edit-blocks' )

		 	);

		 	wp_register_style(

		 		'canvas-profile-block-frontend-style',
		 		get_stylesheet_directory() . '/css/profile.css',
		 		array( 'wp-edit-blocks' )

		 	);


		}

		public function child_scripts() {

		}

		public function get_menu_list_items( $args = array() ) {

			$list_items = '';

			return apply_filters( 'canvas_menu_list_items', $list_items );

		}


		public function canvas_render_user_menu_item() {

			echo canvas_get_user_menu_item();

		}


		public function canvas_render_user_links() {

			echo canvas_get_user_links();

		}


		public function canvas_render_cart_menu_item() {

			$cart_icn = canvas_get_svg_icon( array( 

				'icon'	=> 'shopping-cart', 
				'size'	=> 'sm' 
			) );

			$cart_menu_item = $cart_icn;

			$badge = '<div class="badge badge-empty badge-xs badge-bottom-left badge-primary badge-outlined flickering-slowly"></div>';

			if( ! canvas_cart_is_empty() ) {

				$cart_menu_item .= $badge;

			}

			echo $cart_menu_item;
		}

		public function canvas_render_cart_links() {

			//echo canvas_get_cart_links();

		}

		public function modify_contact7_form_title( $template ) {

			$template->set_title( 'Contact Me' );

			return $template;

		}

		public function modify_contact7_form_content( $template, $prop ) {

		  if ( 'form' == $prop ) {

		  	$your_name_label 		= __( 'Name', 		'canvas' );
		  	$your_email_label 		= __( 'Email', 		'canvas' );
		  	$your_subject_label 	= __( 'Subject', 	'canvas' );
		  	$your_message_label 	= __( 'Message', 	'canvas' );

		    return implode( '', array(

		    	  '<label for="your-name" class="screen-reader-text">'.$your_name_label.'</label>',
		          '[text* your-name default:user_display_name class:form-control placeholder"Name" ]',
		          '<label for="your-email" class="screen-reader-text">'.$your_email_label.'</label>',
		          '[email* your-email default:user_email class:form-control placeholder"Email" ]',
		          '<label for="your-subject" class="screen-reader-text">'.$your_subject_label.'</label>',
		          '[text* your-subject class:form-control placeholder"Subject"]',
		          '<label for="your-message" class="screen-reader-text">'.$your_message_label.'</label>',
		          '[textarea* your-message class:form-control placeholder"Message"]',
		          '[submit class:btn class:btn-pill class:btn-primary "Send Message"]',

		    ) );

		  } else {

		    return $template;

		  } 

		}


		public function canvas_loadmore_ajax_handler(){
 
			// prepare our arguments for the query
			$args = json_decode( stripslashes( $_POST['query'] ), true );
			$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
			$args['post_status'] = 'publish';
		 
			query_posts( $args );
		 
			if( have_posts() ) :
		 
				// run the loop
				while( have_posts() ): the_post();
		 
					get_template_part( '_template-parts/post/content', get_post_format() );		 
		 
				endwhile;
		 
			endif;

			die;

		}


		public function canvas_comments_loadmore_handler() {

			// maybe it isn't the best way to declare global $post variable, but it is simple and works perfectly!
			global $post;

			//$post = get_post( $_POST['post_id'] );

			$comment_args = array( 'post_id' => $post->ID );

			setup_postdata( $post );

			$args = array(

				'style'			=> 'ol',
				'short_ping'	=> true,
				'callback'		=> 'canvas_comment',
				'post_id'		=> ( isset( $_POST['post_id'] ) ) ? $_POST['post_id'] : $post->ID,
				'sort_by'		=> ( isset( $_POST['csort'] ) ) ? $_POST['csort'] : null,
				'page'			=> ( isset( $_POST['cpage'] ) ) ? $_POST['cpage'] : false,
				'default_page'	=> ( isset( $_POST['default_page'] ) ) ? $_POST['default_page'] : get_option( 'default_comments_page' )

			);
		 
			canvas_list_comments( $args );

			die; // don't forget this thing if you don't want "0" to be displayed

		}



		public function modify_allowed_tags( $tags ) {

			$tags[] = 'li';

			return $tags;

		}

		public function canvas_render_back_btn( $html, $link ) {

			$chevron_left_icn = canvas_get_svg_icon( array( 

				'icon'		=> 'chevron-left',
				'size'		=> 'sm'

			 ) );

			$html = '<a href="'. esc_url( $link ) .'" id="conductor-back-button" class="btn btn-default"><span class="icon icon-left">'. $chevron_left_icn .'</span>'.__('Back', 'canvas').'</a>';

			return $html;

		}


		public function filter_post_type_title( $title, $id = null ) {

			if( ! $title ) {

				$format = get_post_format( $id );

				if ( $format ) {

					switch ( $format ) {

						case 'image':
							
							$title = __( 'Image', 'canvas' );
							break;

						case 'gallery':
							
							$title = __( 'Gallery', 'canvas' );
							break;

						case 'audio':
							
							$title = __( 'Audio', 'canvas' );
							break;

						case 'quote':
							
							$title = __( 'Quote', 'canvas' );
							break;

						case 'aside':
							
							$title = __( 'Aside', 'canvas' );
							break;

						case 'video':
							
							$title = __( 'Video', 'canvas' );
							break;

						case 'link':
							
							$title = __( 'Link', 'canvas' );
							break;

						case 'status':
							
							$title = __( 'Status', 'canvas' );
							break;

						case 'chat':
							
							$title = __( 'Chat', 'canvas' );
							break;

						default:
							
							$title = __( 'Post', 'canvas' );
							break;
					}


					$title = apply_filters( 'canvas_title_for_type_' . $format, $title, $format, $id );


				} else {

					$title = apply_filters( 'canvas_no_title',  __( 'Post', 'canvas' ) . ' ' . $id, $title, $id );

				}

			}

			return $title;

		}


		public function filter_feed_link( $link ) {

			if( canvas_is_frontpage() ) {

				$link = 'hello_there';

				return $link;

			}

			return $link;

		}

		public function link_to_profile( $link ) {

			if ( ! function_exists( 'bp_core_get_user_domain' ) )
        		return $link;

			global $comment;
			     
		    if ( !empty( $comment->user_id ) && !empty( get_userdata( $comment->user_id )->ID ) ) {
		 
		        $link = sprintf(
		            '<a href="%s" rel="external nofollow" class="url">%s</a>',
		            bp_core_get_user_domain( $comment->user_id ),
		            strip_tags( $link )
		        );
		 
		    }
		 
		    return $link;

		}


		public function link_to_profile_url( $url, $id, $comment ) {

			if ( ! function_exists( 'bp_core_get_user_domain' ) )
        		return $url;
			     
		    if ( !empty( $comment->user_id ) && !empty( get_userdata( $comment->user_id )->ID ) ) {
		 
		        $url = bp_core_get_user_domain( $comment->user_id );
		 
		    }
		 
		    return $url;

		}


		public function canvas_submit_ajax_comment() {


			$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );

			if ( is_wp_error( $comment ) ) {
				$error_data = intval( $comment->get_error_data() );
				if ( ! empty( $error_data ) ) {
					wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $error_data, 'back_link' => true ) );
				} else {
					wp_die( 'Unknown error' );
				}
			}
		 
			/*
			 * Set Cookies
			 */
			$user = wp_get_current_user();
			do_action('set_comment_cookies', $comment, $user);
		 
			/*
			 * If you do not like this loop, pass the comment depth from JavaScript code
			 */
			$comment_depth = 1;
			$comment_parent = $comment->comment_parent;
			while( $comment_parent ){
				$comment_depth++;
				$parent_comment = get_comment( $comment_parent );
				$comment_parent = $parent_comment->comment_parent;
			}
		 
		 	/*
		 	 * Set the globals, so our comment functions below will work correctly
		 	 */
			$GLOBALS['comment'] = $comment;
			$GLOBALS['comment_depth'] = $comment_depth;

			$args = array( 'style' => 'ul', 'max_depth' => get_option( 'thread_comments_depth' ), 'post_id' => $_POST['post_id'] );

			ob_start();
				call_user_func( 'canvas_comment', $comment, $args, $comment_depth );
			$comment_html = ob_get_clean();

			echo $comment_html;
		 
			die();
		 
		}


		public function comment_sort_query_var( $vars ) {

			$query_vars = array( 'csort', 'cid', 'caction' );

			$vars = array_merge( $vars, $query_vars );

			return $vars;

		}



	}

endif;

return new Canvas();