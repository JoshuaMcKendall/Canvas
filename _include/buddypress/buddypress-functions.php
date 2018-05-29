<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/** Theme Setup ***************************************************************/

if ( !class_exists( 'BP_Legacy' ) ) :

/**
 * Loads BuddyPress Legacy Theme functionality.
 *
 * This is not a real theme by WordPress standards, and is instead used as the
 * fallback for any WordPress theme that does not have BuddyPress templates in it.
 *
 * To make your custom theme BuddyPress compatible and customize the templates, you
 * can copy these files into your theme without needing to merge anything
 * together; BuddyPress should safely handle the rest.
 *
 * See @link BP_Theme_Compat() for more.
 *
 * @since 1.7.0
 *
 * @package BuddyPress
 * @subpackage BP_Theme_Compat
 */
class BP_Legacy extends BP_Theme_Compat {

	/** Functions *************************************************************/

	/**
	 * The main BuddyPress (Legacy) Loader.
	 *
	 * @since 1.7.0
	 *
	 */
	public function __construct() {
		parent::start();
	}

	/**
	 * Component global variables.
	 *
	 * You'll want to customize the values in here, so they match whatever your
	 * needs are.
	 *
	 * @since 1.7.0
	 */
	protected function setup_globals() {
		$bp            = buddypress();
		$this->id      = 'legacy';
		$this->name    = __( 'BuddyPress Legacy', 'buddypress' );
		$this->version = bp_get_version();
		$this->dir     = trailingslashit( $bp->themes_dir . '/bp-legacy' );
		$this->url     = trailingslashit( $bp->themes_url . '/bp-legacy' );
	}

	/**
	 * Setup the theme hooks.
	 *
	 * @since 1.7.0
	 *
	 */
	protected function setup_actions() {

		// Template Output.
		add_filter( 'bp_get_activity_action_pre_meta', array( $this, 'secondary_avatars' ), 10, 2 );

		// Filter BuddyPress template hierarchy and look for page templates.
		add_filter( 'bp_get_buddypress_template', array( $this, 'theme_compat_page_templates' ), 10, 1 );

		/** Scripts ***********************************************************/

		add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_styles'   ) ); // Enqueue theme CSS
		add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts'  ) ); // Enqueue theme JS
		add_filter( 'bp_enqueue_scripts', array( $this, 'localize_scripts' ) ); // Enqueue theme script localization

		/** Body no-js Class **************************************************/

		add_filter( 'body_class', array( $this, 'add_nojs_body_class' ), 20, 1 );

		/** Buttons ***********************************************************/

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			// Register buttons for the relevant component templates
			// Friends button.
			if ( bp_is_active( 'friends' ) )
				add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );

			// Activity button.
			if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() )
				add_action( 'bp_member_header_actions',    'canvas_send_public_message_button',  20 );

			// Messages button.
			if ( bp_is_active( 'messages' ) )
				add_action( 'bp_member_header_actions',    'canvas_send_private_message_button', 20 );

			// Group buttons.
			if ( bp_is_active( 'groups' ) ) {
				add_action( 'bp_group_header_actions',          'bp_group_join_button',               5           );
				add_action( 'bp_group_header_actions',          'bp_group_new_topic_button',         20           );
				add_action( 'bp_directory_groups_actions',      'bp_group_join_button'                            );
				add_action( 'bp_groups_directory_group_filter', 'bp_legacy_theme_group_create_nav', 999           );
				add_action( 'bp_after_group_admin_content',     'bp_legacy_groups_admin_screen_hidden_input'      );
				add_action( 'bp_before_group_admin_form',       'bp_legacy_theme_group_manage_members_add_search' );
			}

			// Blog button.
			if ( bp_is_active( 'blogs' ) ) {
				add_action( 'bp_directory_blogs_actions',    'bp_blogs_visit_blog_button'           );
				add_action( 'bp_blogs_directory_blog_types', 'bp_legacy_theme_blog_create_nav', 999 );
			}
		}

		/** Notices ***********************************************************/

		// Only hook the 'sitewide_notices' overlay if the Sitewide
		// Notices widget is not in use (to avoid duplicate content).
		if ( bp_is_active( 'messages' ) ) {
			add_action( 'canvas_before_main_content', array( $this, 'sitewide_notices' ), 9999 );
		}

		/** Ajax **************************************************************/

		$actions = array(

			// Directory filters.
			'blogs_filter'    => 'bp_legacy_theme_object_template_loader',
			'forums_filter'   => 'bp_legacy_theme_object_template_loader',
			'groups_filter'   => 'bp_legacy_theme_object_template_loader',
			'members_filter'  => 'bp_legacy_theme_object_template_loader',
			'messages_filter' => 'bp_legacy_theme_messages_template_loader',
			'invite_filter'   => 'bp_legacy_theme_invite_template_loader',
			'requests_filter' => 'bp_legacy_theme_requests_template_loader',

			// Friends.
			'accept_friendship' => 'bp_legacy_theme_ajax_accept_friendship',
			'addremove_friend'  => 'bp_legacy_theme_ajax_addremove_friend',
			'reject_friendship' => 'bp_legacy_theme_ajax_reject_friendship',

			// Activity.
			'activity_get_older_updates'  => 'bp_legacy_theme_activity_template_loader',
			'activity_mark_fav'           => 'canvas_activity_mark_fav',
			'activity_mark_unfav'         => 'canvas_unmark_activity_favorite',
			'activity_widget_filter'      => 'bp_legacy_theme_activity_template_loader',
			'delete_activity'             => 'bp_legacy_theme_delete_activity',
			'delete_activity_comment'     => 'bp_legacy_theme_delete_activity_comment',
			'get_single_activity_content' => 'bp_legacy_theme_get_single_activity_content',
			'new_activity_comment'        => 'bp_legacy_theme_new_activity_comment',
			'post_update'                 => 'bp_legacy_theme_post_update',
			'bp_spam_activity'            => 'bp_legacy_theme_spam_activity',
			'bp_spam_activity_comment'    => 'bp_legacy_theme_spam_activity',

			// Groups.
			'groups_invite_user' => 'bp_legacy_theme_ajax_invite_user',
			'joinleave_group'    => 'bp_legacy_theme_ajax_joinleave_group',

			// Messages.
			'messages_autocomplete_results' => 'bp_legacy_theme_ajax_messages_autocomplete_results',
			'messages_close_notice'         => 'bp_legacy_theme_ajax_close_notice',
			'messages_delete'               => 'bp_legacy_theme_ajax_messages_delete',
			'messages_markread'             => 'bp_legacy_theme_ajax_message_markread',
			'messages_markunread'           => 'bp_legacy_theme_ajax_message_markunread',
			'messages_send_reply'           => 'bp_legacy_theme_ajax_messages_send_reply',
		);

		// Conditional actions.
		if ( bp_is_active( 'messages', 'star' ) ) {
			$actions['messages_star'] = 'bp_legacy_theme_ajax_messages_star_handler';
		}

		/**
		 * Register all of these AJAX handlers.
		 *
		 * The "wp_ajax_" action is used for logged in users, and "wp_ajax_nopriv_"
		 * executes for users that aren't logged in. This is for backpat with BP <1.6.
		 */
		foreach( $actions as $name => $function ) {
			add_action( 'wp_ajax_'        . $name, $function );
			add_action( 'wp_ajax_nopriv_' . $name, $function );
		}

		add_filter( 'bp_ajax_querystring', 'bp_legacy_theme_ajax_querystring', 10, 2 );

		/** Override **********************************************************/

		/**
		 * Fires after all of the BuddyPress theme compat actions have been added.
		 *
		 * @since 1.7.0
		 *
		 * @param BP_Legacy $this Current BP_Legacy instance.
		 */
		do_action_ref_array( 'bp_theme_compat_actions', array( &$this ) );
	}

	/**
	 * Load the theme CSS
	 *
	 * @since 1.7.0
	 * @since 2.3.0 Support custom CSS file named after the current theme or parent theme.
	 *
	 */
	public function enqueue_styles() {
		$min = bp_core_get_minified_asset_suffix();

		// Locate the BP stylesheet.
		$ltr = $this->locate_asset_in_stack( "buddypress{$min}.css",     'css' );

		// LTR.
		if ( ! is_rtl() && isset( $ltr['location'], $ltr['handle'] ) ) {
			wp_enqueue_style( $ltr['handle'], $ltr['location'], array(), $this->version, 'screen' );

			if ( $min ) {
				wp_style_add_data( $ltr['handle'], 'suffix', $min );
			}
		}

		// RTL.
		if ( is_rtl() ) {
			$rtl = $this->locate_asset_in_stack( "buddypress-rtl{$min}.css", 'css' );

			if ( isset( $rtl['location'], $rtl['handle'] ) ) {
				$rtl['handle'] = str_replace( '-css', '-css-rtl', $rtl['handle'] );  // Backwards compatibility.
				wp_enqueue_style( $rtl['handle'], $rtl['location'], array(), $this->version, 'screen' );

				if ( $min ) {
					wp_style_add_data( $rtl['handle'], 'suffix', $min );
				}
			}
		}

		// Compatibility stylesheets for specific themes.
		$theme = $this->locate_asset_in_stack( get_template() . "{$min}.css", 'css' );
		if ( ! is_rtl() && isset( $theme['location'] ) ) {
			// Use a unique handle.
			$theme['handle'] = 'bp-' . get_template();
			wp_enqueue_style( $theme['handle'], $theme['location'], array(), $this->version, 'screen' );

			if ( $min ) {
				wp_style_add_data( $theme['handle'], 'suffix', $min );
			}
		}

		// Compatibility stylesheet for specific themes, RTL-version.
		if ( is_rtl() ) {
			$theme_rtl = $this->locate_asset_in_stack( get_template() . "-rtl{$min}.css", 'css' );

			if ( isset( $theme_rtl['location'] ) ) {
				$theme_rtl['handle'] = $theme['handle'] . '-rtl';
				wp_enqueue_style( $theme_rtl['handle'], $theme_rtl['location'], array(), $this->version, 'screen' );

				if ( $min ) {
					wp_style_add_data( $theme_rtl['handle'], 'suffix', $min );
				}
			}
		}
	}

	/**
	 * Enqueue the required JavaScript files
	 *
	 * @since 1.7.0
	 */
	public function enqueue_scripts() {
		$min = bp_core_get_minified_asset_suffix();

		// Locate the BP JS file.
		$asset = $this->locate_asset_in_stack( "buddypress{$min}.js", 'js' );

		// Enqueue the global JS, if found - AJAX will not work
		// without it.
		if ( isset( $asset['location'], $asset['handle'] ) ) {
			wp_enqueue_script( $asset['handle'], $asset['location'], bp_core_get_js_dependencies(), $this->version );
		}

		/**
		 * Filters core JavaScript strings for internationalization before AJAX usage.
		 *
		 * @since 2.0.0
		 *
		 * @param array $value Array of key/value pairs for AJAX usage.
		 */
		$params = apply_filters( 'bp_core_get_js_strings', array(
			'accepted'            => __( 'Accepted', 'buddypress' ),
			'close'               => __( 'Close', 'buddypress' ),
			'comments'            => __( 'comments', 'buddypress' ),
			'leave_group_confirm' => __( 'Are you sure you want to leave this group?', 'buddypress' ),
			'mark_as_fav'	      => __( 'Favorite', 'buddypress' ),
			'my_favs'             => __( 'My Favorites', 'buddypress' ),
			'rejected'            => __( 'Rejected', 'buddypress' ),
			'remove_fav'	      => __( 'Remove Favorite', 'buddypress' ),
			'show_all'            => __( 'Show all', 'buddypress' ),
			'show_all_comments'   => __( 'Show all comments for this thread', 'buddypress' ),
			'show_x_comments'     => __( 'Show all comments (%d)', 'buddypress' ),
			'unsaved_changes'     => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', 'buddypress' ),
			'view'                => __( 'View', 'buddypress' ),
		) );
		wp_localize_script( $asset['handle'], 'BP_DTheme', $params );

		// Maybe enqueue comment reply JS.
		if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Maybe enqueue password verify JS (register page or user settings page).
		if ( bp_is_register_page() || ( function_exists( 'bp_is_user_settings_general' ) && bp_is_user_settings_general() ) ) {

			// Locate the Register Page JS file.
			$asset = $this->locate_asset_in_stack( "password-verify{$min}.js", 'js', 'bp-legacy-password-verify' );

			$dependencies = array_merge( bp_core_get_js_dependencies(), array(
				'password-strength-meter',
			) );

			// Enqueue script.
			wp_enqueue_script( $asset['handle'] . '-password-verify', $asset['location'], $dependencies, $this->version);
		}

		// Star private messages.
		if ( bp_is_active( 'messages', 'star' ) && bp_is_user_messages() ) {
			wp_localize_script( $asset['handle'], 'BP_PM_Star', array(
				'strings' => array(
					'text_unstar'  => __( 'Unstar', 'buddypress' ),
					'text_star'    => __( 'Star', 'buddypress' ),
					'title_unstar' => __( 'Starred', 'buddypress' ),
					'title_star'   => __( 'Not starred', 'buddypress' ),
					'title_unstar_thread' => __( 'Remove all starred messages in this thread', 'buddypress' ),
					'title_star_thread'   => __( 'Star the first message in this thread', 'buddypress' ),
				),
				'is_single_thread' => (int) bp_is_messages_conversation(),
				'star_counter'     => 0,
				'unstar_counter'   => 0
			) );
		}
	}

	/**
	 * Get the URL and handle of a web-accessible CSS or JS asset
	 *
	 * We provide two levels of customizability with respect to where CSS
	 * and JS files can be stored: (1) the child theme/parent theme/theme
	 * compat hierarchy, and (2) the "template stack" of /buddypress/css/,
	 * /community/css/, and /css/. In this way, CSS and JS assets can be
	 * overloaded, and default versions provided, in exactly the same way
	 * as corresponding PHP templates.
	 *
	 * We are duplicating some of the logic that is currently found in
	 * bp_locate_template() and the _template_stack() functions. Those
	 * functions were built with PHP templates in mind, and will require
	 * refactoring in order to provide "stack" functionality for assets
	 * that must be accessible both using file_exists() (the file path)
	 * and at a public URI.
	 *
	 * This method is marked private, with the understanding that the
	 * implementation is subject to change or removal in an upcoming
	 * release, in favor of a unified _template_stack() system. Plugin
	 * and theme authors should not attempt to use what follows.
	 *
	 * @since 1.8.0
	 * @param string $file A filename like buddypress.css.
	 * @param string $type Optional. Either "js" or "css" (the default).
	 * @param string $script_handle Optional. If set, used as the script name in `wp_enqueue_script`.
	 * @return array An array of data for the wp_enqueue_* function:
	 *   'handle' (eg 'bp-child-css') and a 'location' (the URI of the
	 *   asset)
	 */
	private function locate_asset_in_stack( $file, $type = 'css', $script_handle = '' ) {
		$locations = array();

		// Ensure the assets can be located when running from /src/.
		if ( defined( 'BP_SOURCE_SUBDIRECTORY' ) && BP_SOURCE_SUBDIRECTORY === 'src' ) {
			$file = str_replace( '.min', '', $file );
		}

		// No need to check child if template == stylesheet.
		if ( is_child_theme() ) {
			$locations['bp-child'] = array(
				'dir'  => get_stylesheet_directory(),
				'uri'  => get_stylesheet_directory_uri(),
				'file' => str_replace( '.min', '', $file ),
			);
		}

		$locations['bp-parent'] = array(
			'dir'  => get_template_directory(),
			'uri'  => get_template_directory_uri(),
			'file' => str_replace( '.min', '', $file ),
		);

		$locations['bp-legacy'] = array(
			'dir'  => bp_get_theme_compat_dir(),
			'uri'  => bp_get_theme_compat_url(),
			'file' => $file,
		);

		// Subdirectories within the top-level $locations directories.
		$subdirs = array(
			'buddypress/' . $type,
			'community/' . $type,
			$type,
		);

		$retval = array();

		foreach ( $locations as $location_type => $location ) {
			foreach ( $subdirs as $subdir ) {
				if ( file_exists( trailingslashit( $location['dir'] ) . trailingslashit( $subdir ) . $location['file'] ) ) {
					$retval['location'] = trailingslashit( $location['uri'] ) . trailingslashit( $subdir ) . $location['file'];
					$retval['handle']   = ( $script_handle ) ? $script_handle : "{$location_type}-{$type}";

					break 2;
				}
			}
		}

		return $retval;
	}

	/**
	 * Adds the no-js class to the body tag.
	 *
	 * This function ensures that the <body> element will have the 'no-js' class by default. If you're
	 * using JavaScript for some visual functionality in your theme, and you want to provide noscript
	 * support, apply those styles to body.no-js.
	 *
	 * The no-js class is removed by the JavaScript created in buddypress.js.
	 *
	 * @since 1.7.0
	 *
	 * @param array $classes Array of classes to append to body tag.
	 * @return array $classes
	 */
	public function add_nojs_body_class( $classes ) {
		if ( ! in_array( 'no-js', $classes ) )
			$classes[] = 'no-js';

		return array_unique( $classes );
	}

	/**
	 * Load localizations for topic script.
	 *
	 * These localizations require information that may not be loaded even by init.
	 *
	 * @since 1.7.0
	 */
	public function localize_scripts() {
	}

	/**
	 * Outputs sitewide notices markup in the footer.
	 *
	 * @since 1.7.0
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/4802
	 */
	public function sitewide_notices() {
		// Do not show notices if user is not logged in.
		if ( ! is_user_logged_in() )
			return;

		// Add a class to determine if the admin bar is on or not.
		$class = did_action( 'admin_bar_menu' ) ? 'admin-bar-on' : 'admin-bar-off';

		echo '<div id="sitewide-notice" class="' . $class . ' column col-xs-12">';
		canvas_message_get_notices();
		echo '</div>';
	}

	/**
	 * Add secondary avatar image to this activity stream's record, if supported.
	 *
	 * @since 1.7.0
	 *
	 * @param string               $action   The text of this activity.
	 * @param BP_Activity_Activity $activity Activity object.
	 * @return string
	 */
	function secondary_avatars( $action, $activity ) {
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists.
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}

		return $action;
	}

	/**
	 * Filter the default theme compatibility root template hierarchy, and prepend
	 * a page template to the front if it's set.
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/6065
	 *
	 * @since 2.2.0
	 *
	 * @param  array $templates Array of templates.
	 *                         to use the defined page template for component's directory and its single items
	 * @return array
	 */
	public function theme_compat_page_templates( $templates = array() ) {

		/**
		 * Filters whether or not we are looking at a directory to determine if to return early.
		 *
		 * @since 2.2.0
		 *
		 * @param bool $value Whether or not we are viewing a directory.
		 */
		if ( true === (bool) apply_filters( 'bp_legacy_theme_compat_page_templates_directory_only', ! bp_is_directory() ) ) {
			return $templates;
		}

		// No page ID yet.
		$page_id = 0;

		// Get the WordPress Page ID for the current view.
		foreach ( (array) buddypress()->pages as $component => $bp_page ) {

			// Handles the majority of components.
			if ( bp_is_current_component( $component ) ) {
				$page_id = (int) $bp_page->id;
			}

			// Stop if not on a user page.
			if ( ! bp_is_user() && ! empty( $page_id ) ) {
				break;
			}

			// The Members component requires an explicit check due to overlapping components.
			if ( bp_is_user() && ( 'members' === $component ) ) {
				$page_id = (int) $bp_page->id;
				break;
			}
		}

		// Bail if no directory page set.
		if ( 0 === $page_id ) {
			return $templates;
		}

		// Check for page template.
		$page_template = get_page_template_slug( $page_id );

		// Add it to the beginning of the templates array so it takes precedence
		// over the default hierarchy.
		if ( ! empty( $page_template ) ) {

			/**
			 * Check for existence of template before adding it to template
			 * stack to avoid accidentally including an unintended file.
			 *
			 * @see: https://buddypress.trac.wordpress.org/ticket/6190
			 */
			if ( '' !== locate_template( $page_template ) ) {
				array_unshift( $templates, $page_template );
			}
		}

		return $templates;
	}
}
new BP_Legacy();
endif;


/**
 * BP Legacy's callback for the cover image feature.
 *
 * @since  2.4.0
 *
 * @param  array $params the current component's feature parameters.
 * @return null|string An array to inform about the css handle to attach the css rules to
 */
function canvas_cover_image( $params = array() ) {

	if ( empty( $params ) ) {
		return;
	}

	// Avatar height - padding - 1/2 avatar height.
	$avatar_offset = $params['height'] - 5 - round( (int) bp_core_avatar_full_height() / 2 );

	// Header content offset + spacing.
	$avatar_full_height  = bp_core_avatar_full_height();
	$top_offset  		 = ( ! bp_is_active( 'activity' ) ) ? $avatar_full_height : $avatar_full_height + 20; 
	$left_offset 		 = $avatar_full_height + 20;
	$item_btn_margin	 = ( ! bp_is_active( 'activity' ) ) ? '2em 0 10px' : '0 0 10px';
	$default_cover_image = apply_filters( 'canvas_default_cover_image', get_template_directory_uri() . './img/default_cover_image_md.jpg' );

	$cover_image = ( !empty( $params['cover_image'] ) ) ? 'background-image: url(' . $params['cover_image'] . ');' : 'background-image: url(' . $default_cover_image . ');';

	$hide_avatar_style = '';

	// Adjust the cover image header, in case avatars are completely disabled.
	if ( ! buddypress()->avatar->show_avatars ) {
		$hide_avatar_style = '
			#buddypress #item-header-cover-image #item-header-avatar {
				display:  none;
			}
		';

		if ( bp_is_user() ) {
			$hide_avatar_style = '
				#buddypress #item-header-cover-image #item-header-avatar a {
					display: block;
					height: ' . $top_offset . 'px;
					margin: 0 15px 19px 0;
					position: relative;
				}

				#buddypress div#item-header #item-header-cover-image #item-header-content {
					margin-left: auto;
				}
			';
		}
	}

	return '
		/* Cover image */
		#buddypress #header-cover-image {
			height: ' . $params["height"] . 'px;
			' . $cover_image . '
		}

		#buddypress #create-group-form #header-cover-image {
			margin: 1em 0;
			position: relative;
		}

		.bp-user #buddypress #item-header {
			padding-top: 0;
		}

		#buddypress #item-header-cover-image #item-header-avatar {
			margin-top: '. $avatar_offset .'px;
			float: left;
			overflow: visible;
			width: auto;
			position: relative;
		}

		#buddypress div#item-header #item-header-cover-image #item-header-content {
			clear: both;
			float: left;
			margin-left: ' . $left_offset . 'px;
			margin-top: -' . $top_offset . 'px;
			width: auto;
			line-height: 1;
		}

		body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-header-content,
		body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-actions {
			clear: none;
			margin-top: ' . $params["height"] . 'px;
			margin-left: 0;
			max-width: 50%;
		}

		body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-actions {
			max-width: 20%;
			padding-top: 20px;
		}

		' . $hide_avatar_style . '

		#buddypress div#item-header-cover-image .user-nicename a,
		#buddypress div#item-header-cover-image .user-nicename {
		    font-size: 1.17em;
		    margin: 0 0 1.9em;
		    text-rendering: optimizelegibility;
		    text-shadow: 0 0 3px rgba( 0, 0, 0, 0.8 );
		    opacity: 0.6;
		    line-height: 0.9;
		}

		#buddypress #item-header-cover-image #item-header-avatar img.avatar {
			background: rgba( 255, 255, 255, 0.8 );
			border: solid 2px #fff;
			border-radius: 50%;
		}

		#buddypress #item-header-cover-image #item-header-avatar a {
			border: 0;
			text-decoration: none;
		}

		#buddypress #item-header-cover-image #item-buttons {
			margin: '. $item_btn_margin .';
			padding: 0 0 5px;
		}

		#buddypress #item-header-cover-image #item-buttons:after {
			clear: both;
			content: "";
			display: table;
		}

		@media screen and (max-width: 750px) {

			#buddypress div#item-header-cover-image .user-nicename a,
			#buddypress div#item-header-cover-image .user-nicename {
			    font-size: 1.17em;
			    margin: 0 0 1.9em;
			    text-rendering: optimizelegibility;
			    text-shadow: none;
			    opacity: 0.6;
			    line-height: 0.9;
			}

			#buddypress #item-header-cover-image #item-header-avatar,
			.bp-user #buddypress #item-header #item-header-cover-image #item-header-avatar,
			#buddypress div#item-header #item-header-cover-image #item-header-content {
				width: 100%;
				text-align: center;
			}

			#buddypress #item-header-cover-image #item-header-avatar a {
				display: inline-block;
			}

			#buddypress #item-header-cover-image #item-header-avatar img {
				margin: 0;
			}

			#buddypress div#item-header #item-header-cover-image #item-header-content,
			body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-header-content,
			body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-actions {
				margin: 0;
			}

			body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-header-content,
			body.single-item.groups #buddypress div#item-header #item-header-cover-image #item-actions {
				max-width: 100%;
			}

			#buddypress div#item-header-cover-image h2 a,
			#buddypress div#item-header-cover-image h2,
			#buddypress div#item-header-cover-image h3 {
				margin-bottom: 5px;
				font-family: "Open sans", sans-serif;
    			font-weight: 300;
				color: inherit;
				text-shadow: none;
				margin: 15px 0 6px;
			}

			#buddypress small.activity {
    			color: inherit;
			}

			#buddypress #item-header-cover-image #item-buttons div {
				float: none;
				display: inline-block;
			}

			#buddypress #item-header-cover-image #item-buttons:before {
				content: "";
			}

			#buddypress #item-header-cover-image #item-buttons {
				margin: 5px 0;
			}
		}
	';
}

function canvas_cover_image_css( $settings = array() ) {

	$theme_handle = 'bp-parent-css';

	$settings['theme_handle'] = $theme_handle;

	$settings['callback'] = 'canvas_cover_image';

	return $settings;

}

add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'canvas_cover_image_css', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'canvas_cover_image_css', 10, 1 );


function canvas_show_notifications_button( ) {

	if( bp_is_my_profile() ) {

		$user_id = bp_loggedin_user_id();

		$link = esc_url( bp_get_notifications_permalink( $user_id ) );

		$unread_notifications_count = bp_notifications_get_unread_notification_count( $user_id );

		$unread_notifications_count = ( $unread_notifications_count > 99 ) ? '99+' : $unread_notifications_count;

		$notifications_badge = ( $unread_notifications_count ) ? '<div class="badge badge-md badge-top-right badge-danger">'.$unread_notifications_count.'</div>' : '';

		$bell_svg = canvas_get_svg_icon( array( 
			'icon'	=> 'bell',
			'size'	=> 'md'
		 ) );

		echo '<a href="'.$link.'" class="btn btn-link"><span class="icon">'.$bell_svg.''.$notifications_badge.'</span></a>';

	}
}

add_action('bp_member_header_actions', 'canvas_show_notifications_button');

function canvas_show_messages_button( ) {

	if( bp_is_my_profile() && bp_is_active('messages') ) {

		$user_id = bp_loggedin_user_id();

		$link = esc_url( bp_loggedin_user_domain().bp_get_messages_slug() );

		$unread_messages_count = bp_get_total_unread_messages_count( $user_id );

		$unread_messages_count = ( $unread_messages_count > 99 ) ? '99+' : $unread_messages_count;

		$messages_badge = ( $unread_messages_count ) ? '<div class="badge badge-md badge-top-right badge-danger">'.$unread_messages_count.'</div>' : '';

		$mail_svg = canvas_get_svg_icon( array( 
			'icon'	=> 'mail',
			'size'	=> 'md'
		 ) );

		echo '<a href="'.$link.'" class="btn btn-link"><span class="icon">'.$mail_svg.''.$messages_badge.'</span></a>';

	}
}

add_action('bp_member_header_actions', 'canvas_show_messages_button');

function canvas_show_settings_button( ) {

	if( bp_is_my_profile() && bp_is_active('settings') ) {

		$link = esc_url( bp_loggedin_user_domain().bp_get_settings_slug() );

		$settings_svg = canvas_get_svg_icon( array( 
			'icon'	=> 'settings',
			'size'	=> 'md'
		 ) );

		echo '<a href="'.$link.'" class="btn btn-link"><span class="icon">'.$settings_svg.'</span></a>';

	}
}

add_action('bp_member_header_actions', 'canvas_show_settings_button');




function canvas_add_btn_classes( $button ) {


	switch ( $button['id'] ) {

		case 'not_friends':

			$button['link_class'] .= ' btn btn-pill btn-primary';
			break;

		case 'is_friend':

			$button['link_class'] .= ' btn btn-pill btn-danger';
			break;

		case 'awaiting_response':

			$button['link_class'] .= ' btn btn-pill btn-success';
			break;

		case 'pending':

			$button['link_class'] .= ' btn btn-pill btn-warning';
			break;
		
		default:

			$button['link_class'] .= ' btn btn-pill btn-primary';
			break;
	}


	return $button;

}

//add_filter('bp_get_add_friend_button', 'canvas_add_btn_classes', 10, 1);


function canvas_get_the_notification_read_link( $link, $user_id ) {

	$user_id = 0 === $user_id ? bp_displayed_user_id() : $user_id;

	$eye_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'eye',
		'size'	=> 'sm'
	 ) );

	$link =  sprintf( '<td class="read-action"><a href="%1$s" class="mark-read primary">%2$s</a></td>', esc_url( bp_get_the_notification_mark_read_url( $user_id ) ), '<span class="icon graphite">'.$eye_svg.'</span>' );
 
	return $link;

}

add_filter( 'bp_get_the_notification_mark_read_link', 'canvas_get_the_notification_read_link', 10, 2 );




function canvas_get_the_notification_unread_link( $link, $user_id ) {

	$user_id = 0 === $user_id ? bp_displayed_user_id() : $user_id;

	$eye_off_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'eye-off',
		'size'	=> 'sm'
	 ) );

	$link =  sprintf( '<td class="read-action"><a href="%1$s" class="mark-read primary">%2$s</a></td>', esc_url( bp_get_the_notification_mark_read_url( $user_id ) ), '<span class="icon graphite">'.$eye_off_svg.'</span>' );
 
	return $link;

}

add_filter( 'bp_get_the_notification_mark_unread_link', 'canvas_get_the_notification_unread_link', 10, 2 );


function canvas_get_the_notification_delete_link( $link, $user_id ) {

	$user_id = 0 === $user_id ? bp_displayed_user_id() : $user_id;

	$delete_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'x-circle',
		'size'	=> 'sm'
	 ) );

	$link =  sprintf( '<td class="delete-action"><a href="%1$s" class="delete secondary confirm">%2$s</a></td>', esc_url( bp_get_the_notification_delete_url( $user_id ) ), '<span class="icon graphite">'.$delete_svg.'</span>' );
 
	return $link;


}


add_filter('bp_get_the_notification_delete_link', 'canvas_get_the_notification_delete_link', 10, 2 );


function canvas_activity_mark_fav() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset( $_POST['nonce'] ) ) {
		return;
	}

	// Either the 'mark' or 'unmark' nonce is accepted, for backward compatibility.
	$nonce = wp_unslash( $_POST['nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mark_favorite' ) && ! wp_verify_nonce( $nonce, 'unmark_favorite' ) ) {
		return;
	}

	$heart_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'heart',
		'size'	=> 'sm'
	 ) );

	$favorite_link = '<small class="icon icon-left">'.$heart_svg.'</small>';

	if ( bp_activity_add_user_favorite( $_POST['id'] ) )
		echo $favorite_link;
	else
		echo $favorite_link;
	exit;
}


function canvas_unmark_activity_favorite() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset( $_POST['nonce'] ) ) {
		return;
	}

	// Either the 'mark' or 'unmark' nonce is accepted, for backward compatibility.
	$nonce = wp_unslash( $_POST['nonce'] );
	if ( ! wp_verify_nonce( $nonce, 'mark_favorite' ) && ! wp_verify_nonce( $nonce, 'unmark_favorite' ) ) {
		return;
	}

	$heart_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'heart',
		'size'	=> 'sm'
	 ) ); 

	$favorite_link = '<small class="icon icon-left">'.$heart_svg.'</small>';

	if ( bp_activity_remove_user_favorite( $_POST['id'] ) )
		echo $favorite_link;
	else
		echo $favorite_link;
	exit;
}




function canvas_send_private_message_button() {

	echo canvas_get_send_message_button();

}


function canvas_get_send_message_button() {

	$mail_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'mail',
		'size'	=> 'md'
	 ) ); 

	$link_text = '<span class="icon">'.$mail_svg.'</span>';

	return bp_get_button( array(
			'id' 				=> 'private_message',
			'component'         => 'messages',
			'must_be_logged_in' => true,
			'block_self'        => true,
			'wrapper_id'        => 'send-private-message',
			'link_href'         => bp_get_send_private_message_link(),
			'link_text'         => $link_text,
			'link_class'        => 'send-message btn btn-link',
	) );
}


function canvas_send_public_message_button() {

	echo canvas_get_send_public_message_button();

}


function canvas_get_send_public_message_button() {

	$at_sign_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'at-sign',
		'size'	=> 'md'
	 ) );

	$link_text = '<span class="icon">'.$at_sign_svg.'</span>';

	return bp_get_send_public_message_button( array(

		'link_text'		=> $link_text,
		'link_class'	=> 'btn btn-link'

	) );

}


/**
 * Output the form for changing the sort order of notifications.
 *
 * @since 1.9.0
 */
function canvas_notifications_sort_order_form() {

	// Setup local variables.
	$orders   = array( 'DESC', 'ASC' );
	$selected = 'DESC';

	// Check for a custom sort_order.
	if ( !empty( $_REQUEST['sort_order'] ) ) {
		if ( in_array( $_REQUEST['sort_order'], $orders ) ) {
			$selected = $_REQUEST['sort_order'];
		}
	} ?>

	<form action="" method="get" id="notifications-sort-order" class="input-group">
		<label for="notifications-sort-order-list" class="screen-reader-text"><?php esc_html_e( 'Order By:', 'buddypress' ); ?></label>

		<select id="notifications-sort-order-list" class="form-control" name="sort_order" onchange="this.form.submit();">
			<option value="DESC" <?php selected( $selected, 'DESC' ); ?>><?php _e( 'Newest First', 'buddypress' ); ?></option>
			<option value="ASC"  <?php selected( $selected, 'ASC'  ); ?>><?php _e( 'Oldest First', 'buddypress' ); ?></option>
		</select>

		<noscript class="input-group-btn">
				<input id="submit" type="submit" name="form-submit" class="submit btn btn-default" value="<?php esc_attr_e( 'Go', 'buddypress' ); ?>" />
		</noscript>
	</form>

<?php
}


/**
 * Output the dropdown for bulk management of notifications.
 *
 * @since 2.2.0
 */
function canvas_notifications_bulk_management_dropdown() {
	?>
	<label class="bp-screen-reader-text" for="notification-select"><?php
		/* translators: accessibility text */
		_e( 'Select Bulk Action', 'buddypress' );
	?></label>
	<div class="column col-xs-12 col-sm-12 col-md-8 col-lg-5">
		<div class="input-group">
			<select name="notification_bulk_action" id="notification-select" class="form-control">
				<option value="" selected="selected"><?php _e( 'Bulk Actions', 'buddypress' ); ?></option>

				<?php if ( bp_is_current_action( 'unread' ) ) : ?>
					<option value="read"><?php _e( 'Mark read', 'buddypress' ); ?></option>
				<?php elseif ( bp_is_current_action( 'read' ) ) : ?>
					<option value="unread"><?php _e( 'Mark unread', 'buddypress' ); ?></option>
				<?php endif; ?>
				<option value="delete"><?php _e( 'Delete', 'buddypress' ); ?></option>
			</select>
			<span class="input-group-btn">
				<input type="submit" id="notification-bulk-manage" class="button action btn btn-default" value="<?php esc_attr_e( 'Apply', 'buddypress' ); ?>">				
			</span>
		</div>
	</div>

	<?php
}



/**
 * Generate markup for currently active notices.
 */
function canvas_message_get_notices() {
	$notice = BP_Messages_Notice::get_active();

	$x_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'x',
		'size'	=> 'sm'
	 ) );

	if ( empty( $notice ) ) {
		return false;
	}

	$closed_notices = bp_get_user_meta( bp_loggedin_user_id(), 'closed_notices', true );

	if ( empty( $closed_notices ) ) {
		$closed_notices = array();
	}

	if ( is_array( $closed_notices ) ) {
		if ( !in_array( $notice->id, $closed_notices ) && $notice->id ) {
			?>
			<div id="message" class="info notice" rel="n-<?php echo esc_attr( $notice->id ); ?>">
				<p>
					<strong><?php echo stripslashes( wp_filter_kses( $notice->subject ) ) ?></strong>
					<?php echo stripslashes( wp_filter_kses( $notice->message) ) ?>
					<button type="button" id="close-notice" class="btn btn-default" ><span class="bp-screen-reader-text"><?php _e( 'Dismiss this notice', 'buddypress' ); ?></span> <span aria-hidden="true" class="icon icon-sm graphite"><?php echo $x_svg; ?></span></button>
					<?php wp_nonce_field( 'bp_messages_close_notice', 'close-notice-nonce' ); ?>
				</p>
			</div>
			<?php
		}
	}
}


function canvas_message_search_form() {

	// Get the default search text.
	$default_search_value = bp_get_search_default_text( 'messages' );

	// Setup a few values based on what's being searched for.
	$search_submitted     = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value;
	$search_placeholder   = ( $search_submitted === $default_search_value ) ? ' placeholder="' .  esc_attr( $search_submitted ) . '"' : '';
	$search_value         = ( $search_submitted !== $default_search_value ) ? ' value="'       .  esc_attr( $search_submitted ) . '"' : '';

	$search_svg = canvas_get_svg_icon( array( 
		'icon'	=> 'search',
		'size'	=> 'sm'
	 ) );

	ob_start(); ?>

	<form action="" method="get" id="search-message-form">
		<label for="messages_search" class="bp-screen-reader-text"><?php
			/* translators: accessibility text */
			esc_html_e( 'Search Messages', 'buddypress' );
		?></label>
		<div class="input-group">
			<input type="text" name="s" id="messages_search" class="form-control" <?php echo $search_placeholder . $search_value; ?> />
			<span class="input-group-btn">
				<button type="submit" id="messages_search_submit" name="messages_search_submit" class="button search-submit btn btn-default">
					<span class="icon icon-sm">
						<?php echo $search_svg; ?>
					</span>
				</button>
			</span>
		</div>
	</form>

	<?php

	$search_form_html = ob_get_clean();

	return $search_form_html;

}

add_filter('bp_message_search_form', 'canvas_message_search_form');


/**
 * Filters the star action link, including markup.
 * Find in: buddypress/bp-messages/bp-messages-star.php:232
 *
 * @since 2.3.0
 *
 * @param string $retval Link for starring / unstarring a message, including markup.
 * @param array  $r      Parsed link arguments. See $args in bp_get_the_message_star_action_link().
 */
function canvas_get_the_message_star_action_link( $retval, $r ) {

	$star_icn = canvas_get_svg_icon( array( 

		'icon'	=> 'star',
		'size'	=> 'sm'

	 ) );

	// Default user ID.
	$user_id = bp_displayed_user_id()
		? bp_displayed_user_id()
		: bp_loggedin_user_id();

	// Check user ID and determine base user URL.
	switch ( $r['user_id'] ) {

		// Current user.
		case bp_loggedin_user_id() :
			$user_domain = bp_loggedin_user_domain();
			break;

		// Displayed user.
		case bp_displayed_user_id() :
			$user_domain = bp_displayed_user_domain();
			break;

		// Empty or other.
		default :
			$user_domain = bp_core_get_user_domain( $r['user_id'] );
			break;
	}

	// Bail if no user domain was calculated.
	if ( empty( $user_domain ) ) {
		return '';
	}

	// Define local variables.
	$retval = $bulk_attr = '';

	// Thread ID.
	if ( (int) $r['thread_id'] > 0 ) {

		// See if we're in the loop.
		if ( bp_get_message_thread_id() == $r['thread_id'] ) {

			// Grab all message ids.
			$mids = wp_list_pluck( $GLOBALS['messages_template']->thread->messages, 'id' );

			// Make sure order is ASC.
			// Order is DESC when used in the thread loop by default.
			$mids = array_reverse( $mids );

		// Pull up the thread.
		} else {
			$thread = new BP_Messages_Thread( $r['thread_id'] );
			$mids   = wp_list_pluck( $thread->messages, 'id' );
		}

		$is_starred = false;
		$message_id = 0;
		foreach ( $mids as $mid ) {

			// Try to find the first msg that is starred in a thread.
			if ( true === bp_messages_is_message_starred( $mid ) ) {
				$is_starred = true;
				$message_id = $mid;
				break;
			}
		}

		// No star, so default to first message in thread.
		if ( empty( $message_id ) ) {
			$message_id = $mids[0];
		}

		$message_id = (int) $message_id;

		// Nonce.
		$nonce = wp_create_nonce( "bp-messages-star-{$message_id}" );

		if ( true === $is_starred ) {
			$action    = 'unstar';
			$bulk_attr = ' data-star-bulk="1"';
			$retval    = $user_domain . bp_get_messages_slug() . '/unstar/' . $message_id . '/' . $nonce . '/all/';
		} else {
			$action    = 'star';
			$retval    = $user_domain . bp_get_messages_slug() . '/star/' . $message_id . '/' . $nonce . '/';
		}

		$title = $r["title_{$action}_thread"];

	// Message ID.
	} else {
		$message_id = (int) $r['message_id'];
		$is_starred = bp_messages_is_message_starred( $message_id );
		$nonce      = wp_create_nonce( "bp-messages-star-{$message_id}" );

		if ( true === $is_starred ) {
			$action = 'unstar';
			$retval = $user_domain . bp_get_messages_slug() . '/unstar/' . $message_id . '/' . $nonce . '/';
		} else {
			$action = 'star';
			$retval = $user_domain . bp_get_messages_slug() . '/star/' . $message_id . '/' . $nonce . '/';
		}

		$title = $r["title_{$action}"];
	}

	/**
	 * Filters the star action URL for starring / unstarring a message.
	 *
	 * @since 2.3.0
	 *
	 * @param string $retval URL for starring / unstarring a message.
	 * @param array  $r      Parsed link arguments. See $args in bp_get_the_message_star_action_link().
	 */
	$retval = esc_url( apply_filters( 'bp_get_the_message_star_action_urlonly', $retval, $r ) );
	if ( true === (bool) $r['url_only'] ) {
		return $retval;
	}

	$message_star_action_link = '<a data-bp-tooltip="' . esc_attr( $title ) . '" class="bp-tooltip message-action-' . esc_attr( $action ) . '" data-star-status="' . esc_attr( $action ) .'" data-star-nonce="' . esc_attr( $nonce ) . '"' . $bulk_attr . ' data-message-id="' . esc_attr( (int) $message_id ) . '" href="' . $retval . '" role="button" aria-pressed="false"><span class="icon">'. $star_icn .'</span> <span class="bp-screen-reader-text">' . $r['text_' . $action] . '</span></a>';

	return $message_star_action_link;

}

add_filter('bp_get_the_message_star_action_link', 'canvas_get_the_message_star_action_link', 10, 2);


/**
 * Output the dropdown for bulk management of messages.
 * Find in: buddypress/bp-messages/bp-messages-template.php:1030
 *
 * @since 3.0.0
 */
function canvas_messages_bulk_management_dropdown() {
	?>
	<label class="bp-screen-reader-text" for="messages-select"><?php
		_e( 'Select Bulk Action', 'buddypress' );
	?></label>
	<div class="column col-xs-12 col-sm-12 col-md-8 col-lg-5">
		<div class="input-group">
			<select name="messages_bulk_action" id="messages-select" class="form-control">
				<option value="" selected="selected"><?php _e( 'Bulk Actions', 'buddypress' ); ?></option>
				<option value="read"><?php _e( 'Mark read', 'buddypress' ); ?></option>
				<option value="unread"><?php _e( 'Mark unread', 'buddypress' ); ?></option>
				<option value="delete"><?php _e( 'Delete', 'buddypress' ); ?></option>
				<?php
					/**
					 * Action to add additional options to the messages bulk management dropdown.
					 *
					 * @since 2.3.0
					 */
					do_action( 'bp_messages_bulk_management_dropdown' );
				?>
			</select>
			<span class="input-group-btn">
				<input type="submit" id="messages-bulk-manage" class="btn btn-default button action" value="<?php esc_attr_e( 'Apply', 'buddypress' ); ?>">
			</span>
		</div>
	</div>
	<?php
}


remove_action('bp_enqueue_scripts', 'messages_add_autocomplete_js');
remove_action( 'wp_head', 'messages_add_autocomplete_css' );


// add_filter( 'bp_get_send_message_button', 'canvas_get_send_message_button', 10, 1 );


// function add_patron_tier() {
// 	echo '<div style="position:absolute; bottom: 0px;">HELLO WORLD</div>';
// }

// add_action('canvas_after_avatar', 'add_patron_tier');