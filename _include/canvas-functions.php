<?php

if ( ! function_exists( 'canvas_is_frontpage' ) ) {

	/**
	 * Checks to see if we're on the homepage or not. 
	 * If the home page is just the default blog loop, this returns false.
	 * Therefore the "frontpage" is the "static" home page, not the default blog loop.
	 */
	function canvas_is_frontpage() {

		return ( is_front_page() && ! is_home() );

	}

}


if ( ! function_exists( 'canvas_is_woocommerce_activated' ) ) {

	/**
	 * Query WooCommerce activation
	 */
	function canvas_is_woocommerce_activated() {

		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }

	}
}


if ( ! function_exists( 'canvas_is_buddypress_activated' ) ) {

	/**
	 * Query WooCommerce activation
	 */
	function canvas_is_buddypress_activated() {

		if ( function_exists('bp_is_active') ) { return true; } else { return false; }

	}
}

if ( ! function_exists( 'canvas_get_user_id' ) ) {

	/**
	 * Query WooCommerce activation
	 */
	function canvas_get_user_id() {

		if( is_user_logged_in() ) {

			$user_id;

			if( canvas_is_buddypress_activated() ) {

				$user_id = bp_loggedin_user_id();

			} else {

				$user_id = get_current_user_id();

			}

			return $user_id;

		}

		return false;

	}
}

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.4.6
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function canvas_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}


function canvas_cart_is_empty() {

	return ( WC()->cart->get_cart_contents_count() > 0 ) ? false : true;

}

if ( ! function_exists( 'canvas_custom_logo' ) ) {

	function canvas_custom_logo() {

		$site_name = get_bloginfo('name');

	    $output = '';
	    if (function_exists('get_custom_logo'))
	        $output = get_custom_logo();

	    if (empty($output))
	        $output = '<a href="' . esc_url( home_url('/') ) . '" title="'. esc_attr( $site_name ) .'"><h1 class="site-title">' . $site_name . '</h1></a>';

	    echo $output;
	}

}


if ( ! function_exists( 'canvas_top_menu_wrap' ) ) {

	function canvas_top_menu_wrap( $args = array() ) {
	  // default value of 'items_wrap' is <ul id="%1$s" class="%2$s">%3$s</ul>'

		$menu_icn = canvas_get_svg_icon( array( 

			'icon'	=> 'menu',
			'size'	=> 'sm'

		) );

		$close_icn = canvas_get_svg_icon( array( 

			'icon'	=> 'x',
			'size'	=> 'sm'

		) );

		$defaults = apply_filters( 'canvas_top_menu_items', array(

			'menu_txt'			=> '',
			'close_txt'			=> '',
			'menu_icn'			=> $menu_icn,
			'close_icn'			=> $close_icn,
			'menu_target'		=> '#main-menu',
			'close_target'		=> '#close-menu',

		) );

		$args = wp_parse_args( $args, $defaults );
	  
		// open the <ul>, set 'menu_class' and 'menu_id' values
		$wrap  = '<ul id="%1$s" class="%2$s">';

		// get nav items as configured in /wp-admin/
		$wrap .= '%3$s';

		$wrap .= '<li class="menu-item close-btn"><a href="'. $args['close_target'] .'" class="priority-nav-close-trigger" onclick="event.preventDefault();"><span class="icon icon-default">'. $args['close_icn'] .'</span> '. $args['close_txt'] .' </a></li>';

		// the static link 
		$wrap .= '<li class="menu-item menu-btn"><a href="'. $args['menu_target'] .'" class="priority-nav-trigger" onclick="event.preventDefault();"><span class="icon icon-default">'. $args['menu_icn'] .'</span> '. $args['menu_txt'] .' </a></li>';

		// close the <ul>
		$wrap .= '</ul>';
		// return the result
		return $wrap;
	}

}

if( ! function_exists( 'canvas_get_avatar' ) ) {

	function canvas_get_author_avatar( $user_id, $args = array() ) {

			$user_avatar = '';

			$defaults = array( 

				'height'		=> 21,
				'width'			=> 21,
				'type'			=> 'thumb'

			);

			$args = wp_parse_args( $args, $defaults );

			
			$user_avatar = get_avatar( $user_id, $args['height'] );


			return $user_avatar;

	}
}


if( ! function_exists( 'canvas_get_avatar' ) ) {

	function canvas_get_avatar( $args = array() ) {

		$user_avatar = '';

		$defaults = array( 

			'height'		=> 21,
			'width'			=> 21,
			'type'			=> 'thumb',
			'icon'			=> 'user',
			'icon_size'		=> 'sm'

		);

		$args = wp_parse_args( $args, $defaults );

		if ( canvas_is_buddypress_activated() && is_user_logged_in() && get_option( 'show_avatars' ) ) {

			$type 		= $args['type'];
			$height 	= $args['height'];
			$width		= $args['width'];

			$user_avatar = bp_get_loggedin_user_avatar( 'type='.$type.'&width='.$width.'&height='.$height );

		} elseif ( is_user_logged_in() && get_option( 'show_avatars' ) ) {

			$user_id = get_current_user_id();

			$user_avatar = get_avatar( $user_id, $args['height'] );

		} else {
			
			if( is_user_logged_in() ) {

				$args['icon'] = apply_filters( 'canvas_logged_in_user_icon', 'user-check' );

				$user_avatar = canvas_get_svg_icon( array( 'icon' => $args['icon'], 'size' => $args['icon_size'] ) );

			} else {

				$user_avatar = canvas_get_svg_icon( array( 'icon' => $args['icon'], 'size' => $args['icon_size'] ) );

			}

		}

		return $user_avatar;

	}

}


if( ! function_exists( 'canvas_get_user_menu_item' ) ) {

	function canvas_get_user_menu_item() {

		$user_avatar = canvas_get_avatar();

		if( is_user_logged_in() && function_exists( 'bp_is_active' ) ) {

			$user_id = bp_loggedin_user_id();

			$unread_notifications_count = bp_notifications_get_unread_notification_count( $user_id );

			$notification_badge = ( $unread_notifications_count ) ? '<div class="badge badge-empty badge-xs badge-bottom-left badge-danger badge-outlined flickering-slowly"></div>' : '';

			$user = '<span id="user-avatar-nav" class="user avatar icon">'.$user_avatar.' '.$notification_badge.'</span>';

		} elseif( is_user_logged_in() ) { 


			$user = '<span id="user-avatar-nav" class="user avatar icon">'.$user_avatar.'</span>';


		} else {

			$user = '<span class="user icon">'.$user_avatar.'</span>';

		}

		return $user;

	}

}


if( ! function_exists( 'canvas_more_link' ) ) {


	function canvas_more_link( $args = array() ) {

		$defaults = apply_filters( 'canvas_more_link_defaults', array(

			'text'				=>	__( 'Read More', 'canvas' ),
			'icon'				=>	'chevron-right',
			'icon_size'			=>	'sm',
			'post_format'		=>	null

		) );

		$args = wp_parse_args( $args, $defaults );


		$more_link = '<span class="more-text">'. $args['text'];

		$more_link .= '<span class="icon icon-right">'. canvas_get_svg_icon( array( 

			'icon'		=> $args['icon'],
			'size'		=> $args['icon_size']

		 ) ) .'</span></span>';


		return apply_filters( 'canvas_more_link', $more_link, $args );

	}


}


// ARE LINKS ENABLED FUNCTIONs

if( ! function_exists( 'canvas_is_profile_link_enabled' ) ) {

	function canvas_is_profile_link_enabled() {

		if( is_user_logged_in() ) {

			if( canvas_is_buddypress_activated() ) {

				if( bp_is_active( 'xprofile' ) ) {

					return true;

				}

				return false;

			}

		}

		return false;

	}

}


if( ! function_exists( 'canvas_is_notifications_link_enabled' ) ) {

	function canvas_is_notifications_link_enabled() {

		if( is_user_logged_in() ) {

			if( canvas_is_buddypress_activated() ) {

				if( bp_is_active( 'notifications' ) ) {

					return true;

				}

				return false;

			}

		}

		return false;

	}

}

if( ! function_exists( 'canvas_is_messages_link_enabled' ) ) {

	function canvas_is_messages_link_enabled() {

		if( is_user_logged_in() ) {

			if( canvas_is_buddypress_activated() ) {

				if( bp_is_active( 'messages' ) ) {

					return true;

				}

				return false;

			}

		}

		return false;

	}

}


if( ! function_exists( 'canvas_is_account_link_enabled' ) ) {

	function canvas_is_account_link_enabled() {

		if( is_user_logged_in() ) {

			if( canvas_is_buddypress_activated() || canvas_is_woocommerce_activated() ) {

				if( bp_is_active('settings') || get_option( 'woocommerce_myaccount_page_id' ) ) {

					return true;

				}

				return false;

			}

		}

		return false;

	}

}

if( ! function_exists( 'canvas_is_logout_link_enabled' ) ) {

	function canvas_is_logout_link_enabled() {

		if( is_user_logged_in() ) {

			return true;

		}

		return false;

	}

}

if( ! function_exists( 'canvas_is_login_link_enabled' ) ) {

	function canvas_is_login_link_enabled() {

		if( ! is_user_logged_in() ) {

			return true;

		}

		return false;

	}

}


if( ! function_exists( 'canvas_is_registration_link_enabled' ) ) {

	function canvas_is_registration_link_enabled() {

		if( ! is_user_logged_in() && get_option( 'users_can_register' ) ) {

			return true;

		}

		return false;

	}

}


// Canvas user menu link functions

if( ! function_exists( 'canvas_profile_link' ) ) {

	function canvas_profile_link() {

		if( is_user_logged_in() ) {

			$profile_link = '#profile';

			if( canvas_is_buddypress_activated() && bp_is_active( 'xprofile' ) ) {

				$profile_link = bp_get_loggedin_user_link();

			}

			return apply_filters( 'canvas_profile_link', $profile_link );

		}

		return wp_login_url( get_permalink() );

	}

}

if( ! function_exists( 'canvas_notifications_link' ) ) {

	function canvas_notifications_link() {

		if( is_user_logged_in() ) {

			$notifications_link = '#notifications';

			$user_id = canvas_get_user_id();

			if( canvas_is_buddypress_activated() && bp_is_active( 'notifications' ) ) {

				$notifications_link = bp_get_notifications_permalink( $user_id );

			}

			return apply_filters( 'canvas_notifications_link', $notifications_link );

		}

		return wp_login_url( get_permalink() );

	}

}

if( ! function_exists( 'canvas_messages_link' ) ) {

	function canvas_messages_link() {

		if( is_user_logged_in() ) {

			$messages_link = '#messages';

			if( canvas_is_buddypress_activated() && bp_is_active( 'messages' ) ) {

				$messages_link = bp_loggedin_user_domain().bp_get_messages_slug();

			}

			return apply_filters( 'canvas_messages_link', $messages_link );

		}

		return wp_login_url( get_permalink() );

	}

}

if( ! function_exists( 'canvas_account_link' ) ) {

	function canvas_account_link() {

		if( is_user_logged_in() ) {

			$account_link = '#account';

			if( canvas_is_buddypress_activated() && bp_is_active( 'settings' ) ) {

				$account_link = bp_loggedin_user_domain().bp_get_settings_slug();

			} elseif( canvas_is_woocommerce_activated() && get_option('woocommerce_myaccount_page_id') ) {

				$account_link = get_permalink( get_option('woocommerce_myaccount_page_id') );

			}

			return apply_filters( 'canvas_account_link', $account_link );

		}

		return wp_login_url( get_permalink() );

	}

}


if( ! function_exists( 'canvas_unread_notifications_count' ) ) {

	function canvas_unread_notifications_count() {

		$unread_notifications_count = 0;

		if( is_user_logged_in() ) {

			$user_id = canvas_get_user_id();

			if( canvas_is_buddypress_activated() && bp_is_active( 'notifications' ) ) {

				$unread_notifications_count = bp_notifications_get_unread_notification_count( $user_id );

			}

		}

		return apply_filters( 'canvas_unread_notifications_count', $unread_notifications_count );

	}

}


if( ! function_exists( 'canvas_unread_messages_count' ) ) {

	function canvas_unread_messages_count() {

		$unread_message_count = 0;

		if( is_user_logged_in() ) {

			$user_id = canvas_get_user_id();

			if( canvas_is_buddypress_activated() && bp_is_active( 'messages' ) ) {

				$unread_message_count = bp_get_total_unread_messages_count( $user_id );

			}

		}

		return apply_filters( 'canvas_unread_messages_count', $unread_message_count );

	}

}


if( !function_exists( 'canvas_sort_user_links' ) ) {


	function canvas_sort_user_links( $items ) {

		$sorted = array();

		foreach ( $items as $key => $item ) {

			if( ! $item['enabled'] ) {

				continue;

			}
			
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


}


if( ! function_exists( 'canvas_get_user_link_items' ) ) {

	function canvas_get_user_link_items() {

		return apply_filters( 'canvas_user_links', array( 

			'wp-admin'			=> array(
										'icon'		=> array( 'name' => 'sliders', 'size' => 'sm' ),
										'text'		=> __('Admin', 'canvas'),
										'link'		=> get_admin_url(),
										'enabled'	=> ( current_user_can( 'edit_posts' ) && ! is_admin_bar_showing() ),
										'position'	=> 5
									),

			'profile'			=> array(
										'icon'		=> array( 'name' => 'user', 'size' => 'sm' ),
										'text'		=> __('Profile', 'canvas'),
										'link'		=> canvas_profile_link(),
										'enabled'	=> canvas_is_profile_link_enabled(),
										'position'	=> 10
									),
			'notifications'		=> array(
										'icon'		=> array( 'name' => 'bell', 'size' => 'sm' ),
										'text'		=> __('Notifications', 'canvas'),
										'link'		=> canvas_notifications_link(),
										'enabled'	=> canvas_is_notifications_link_enabled(),
										'position'	=> 20,
										'count'		=> canvas_unread_notifications_count()
									),
			'messages'			=> array(
										'icon'		=> array( 'name' => 'mail', 'size' => 'sm' ),
										'text'		=> __('Messages', 'canvas'),
										'link'		=> canvas_messages_link(),
										'enabled'	=> canvas_is_messages_link_enabled(),
										'position'	=> 30,
										'count'		=> canvas_unread_messages_count()
									),
			'account'			=> array(
										'icon'		=> array( 'name' => 'settings', 'size' => 'sm' ),
										'text'		=> __('Account', 'canvas'),
										'link'		=> canvas_account_link(),
										'enabled'	=> canvas_is_account_link_enabled(),
										'position'	=> 40
									),
			'logout'			=> array(
										'icon'		=> array( 'name' => 'log-out', 'size' => 'sm', 'direction' => 'right' ),
										'text'		=> __('Log out', 'canvas'),
										'link'		=> wp_logout_url( get_permalink( ) ),
										'enabled'	=> canvas_is_logout_link_enabled(),
										'position'  => 50
									),
			'login'				=> array(
										'icon'		=> array( 'name' => 'log-in', 'size' => 'sm' ),
										'text'		=> __( 'Log in', 'canvas' ),
										'link'		=> wp_login_url( get_permalink() ),
										'enabled'	=> canvas_is_login_link_enabled(),
										'position'	=> 10

									),
			'register'			=> array(
										'icon'		=> array( 'name' => 'user-plus', 'size' => 'sm' ),
										'text'		=> __( 'Register', 'canvas' ),
										'link'		=> wp_registration_url( get_permalink() ),
										'enabled'	=> canvas_is_registration_link_enabled(),
										'position'	=> 20

									)

		) );

	}

}


if( ! function_exists( 'canvas_get_user_links' ) ) {

	function canvas_get_user_links( $args = array() ) {

		$default = array( 
			'notification_count'	=> false,
			'message_count'			=> false
		 );

		$args = wp_parse_args( $args, $default );

		$container = '';

		$user_links = canvas_sort_user_links( canvas_get_user_link_items() );

		foreach( $user_links as $key => $user_link ) {

			$key = $user_link['key'];

			$user_link = apply_filters( 'canvas_'. $key .'_link_item', $user_link );

			$icon = canvas_get_svg_icon( array(

				'icon'	=> $user_link['icon']['name'],
				'size'	=> $user_link['icon']['size']

			) );

			$unread_badge = '';

			if( ( $key == 'notifications' && $user_link['count'] > 0 ) || ( $key == 'messages' && $user_link['count'] > 0 ) ) {

				$count = ( $user_link['count'] ) ? $user_link['count'] : 0;

				$unread_count = ( $count > 99 ) ? '99+' : $count;

				$unread_badge = '<div class="badge badge-md badge-danger badge-inline">'.esc_html( $unread_count ).'</div>';

			}

			$direction = ( isset( $user_link['icon']['direction'] ) ) ? $user_link['icon']['direction'] : 'left';

			switch ( $direction ) {

				case 'left':
					$rol = 'left';
					break;

				case 'right':
					$rol = 'right';
					break;
				
				default:
					$rol = 'left';
					break;

			}

			$icon = '<span class="icon icon-'.$rol.' icon-sm">'.$icon.'</span>';

			$item_text = esc_html( $user_link['text'] );

			$item_content = ( $rol == 'left' ) ? $icon . $item_text : $item_text .  $icon;

			$user_meta_item = '<a href="'.esc_url( $user_link['link'] ).'" class="link link-secondary '. esc_attr( $key ) .'-link">'. $item_content .' '. $unread_badge .'</a>';

			$container .= '<span class="'.esc_attr( $key ).' user-dropdown-item">'.$user_meta_item.'</span>';


		}

		return $container;

	}

}

if( !function_exists( 'canvas_sort_comment_links' ) ) {


	function canvas_sort_comment_links( $items ) {

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


}

if( ! function_exists( 'canvas_comment_is_unapproved' ) ) {

	function canvas_comment_is_unapproved( $comment ) {

		if ( '0' == $comment->comment_approved ) {

			return true;

		}

		return false;

	}

}


if( ! function_exists( 'canvas_comment_belongs_to_current_user' ) ) {

	function canvas_comment_belongs_to_current_user( $comment, $user_id ) {

		if( $comment->user_id != $user_id ) {

			return false;

		}

		return true;

	}

}


if( ! function_exists( 'canvas_user_can_edit_comment' ) ) {

	function canvas_user_can_edit_comment( $comment, $args = array() ) {

		if( is_user_logged_in() ) {

			$user_id = get_current_user_id();

			if( current_user_can( 'moderate_comments' ) || canvas_comment_belongs_to_current_user( $comment, $user_id ) ) {

				return true;

			}

			return false;

		}

		return false;

	}

}


if( ! function_exists( 'canvas_comment_permalink' ) ) {

	function canvas_comment_permalink( $comment, $args = array() ) {

		// $comment_permalink = ( $csort ) ? add_query_arg( 'csort', $csort, get_comment_link( $comment->comment_ID, $comment_link_args ) ) : get_comment_link( $comment->comment_ID, $comment_link_args );

		$comment_permalink = add_query_arg( 'cid', $comment->comment_ID, get_comment_link( $comment, $args ) );

		return apply_filters( 'canvas_comment_permalink', $comment_permalink, $comment, $args );

	}

}


if( ! function_exists( 'canvas_flag_comment_link' ) ) {

	function canvas_flag_comment_link( $comment, $args = array() ) {

		$comment_flag_link = wp_nonce_url( add_query_arg( array( 

								'cid'		=> $comment->comment_ID,
								'caction' 	=> 'flag',

							), get_comment_link( $comment, $args ) ), 'flag_comment_'.$comment->comment_ID, '_flag_comment' );

		return apply_filters( 'canvas_flag_comment_link',  $comment_flag_link, $comment, $args );

	}

}


if( ! function_exists( 'canvas_get_comment_action_links' ) ) {


	function canvas_get_comment_action_links( $comment, $args = array() ) {

		$comment = ( is_object( $comment ) ) ? $comment : get_comment( $comment );


    	return apply_filters( 'canvas_comment_action_links', array(

					'approve'		=> array(

						'link'				=> get_edit_comment_link( $comment ),
						'title'				=> __( 'Approve', 'canvas' ),
						'icon'				=> array( 'name' => 'check-circle', 'size' => 'sm' ),
						'user_has_access'	=> ( current_user_can( 'moderate_comments' ) && canvas_comment_is_unapproved( $comment ) ),
						'position'			=> 10

					),
					'flag'			=> array(

						'link'				=> canvas_flag_comment_link( $comment ),
						'title'				=> __( 'Flag', 'canvas' ),
						'icon'				=> array( 'name' => 'flag', 'size' => 'sm' ),
						'user_has_access'	=> ( is_user_logged_in() && ! canvas_comment_belongs_to_current_user($comment, get_current_user_id()) ),
						'position'			=> 20

					),
					'edit'			=> array(

						'link'				=> get_edit_comment_link( $comment ),
						'title'				=> __( 'Edit', 'canvas' ),
						'icon'				=> array( 'name' => 'edit', 'size' => 'sm' ),
						'user_has_access'	=> canvas_user_can_edit_comment( $comment ),
						'position'			=> 30

					),
					'delete'		=> array(

						'link'				=> get_edit_comment_link( $comment ),
						'title'				=> __( 'Delete', 'canvas' ),
						'icon'				=> array( 'name' => 'trash', 'size' => 'sm' ),
						'user_has_access'	=> canvas_user_can_edit_comment( $comment ),
						'position'			=> 40

					),
					'permalink'		=> array(

						'link'				=> canvas_comment_permalink( $comment ),
						'title'				=> __( 'Permalink', 'canvas' ),
						'icon'				=> array( 'name' => 'link', 'size' => 'sm' ),
						'user_has_access'	=> true,
						'position'			=> 50

					),

				) );

	}


}


if( ! function_exists( 'canvas_get_comment_links' ) ) {


	function canvas_get_comment_links( $comment, $args = array() ) {

		$action_links = canvas_get_comment_action_links( $comment, $args );

		$sorted_action_links = canvas_sort_comment_links( $action_links );

		$comment_links = '';

		foreach ( $sorted_action_links as $key => $action_link ) {

			$user_has_access = (bool) $action_link['user_has_access'];

			if( $user_has_access ) {

				$action_link = apply_filters( 'canvas_'. $action_link['key'] .'_action_link', $action_link );

				if( isset( $action_link['icon'] ) && ! empty( $action_link['icon'] )  ) {

					$icon = apply_filters( 'canvas_'. $action_link['key'] .'_action_link_icon', canvas_get_svg_icon( array( 
			            'icon'  => $action_link['icon']['name'],
			            'size'  => $action_link['icon']['size']
			         ) ) );

				}

				$comment_links .= sprintf(
					            __('%s', 'canvas'),
					            '<li class="'. esc_attr( $action_link['key'] ) .'-action-item"><a href="' . esc_url( $action_link['link'] ) . '" class="link link-secondary '. esc_attr( $action_link['key'] ) .'-action-link"><span class="icon icon-left">'. $icon .'</span>' . esc_html( $action_link['title'] ) . '</a></li>'
					        );

			}

		}


		return apply_filters( 'canvas_get_comment_links', $comment_links, $comment, $args );

	}


}


if( ! function_exists( 'canvas_comment_links' ) ) {


	function canvas_comment_links( $comment, $args = array() ) {

		echo canvas_get_comment_links( $comment, $args );

	}


}

if ( ! function_exists( 'canvas_comment' ) ) {
	/**
	 * Canvas comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args the comment args.
	 * @param int   $depth the comment depth.
	 * @since 1.0.0
	 */
	function canvas_comment( $comment, $args, $depth ) {

		$reply_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'corner-down-right',
			'size'		=> 'xs'

		 ) );

		$more_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'more-vertical',
			'size'		=> 'sm'

		 ) );

		$chevron_right_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'chevron-right',
			'size'		=> 'xs'

		 ) );

		$comment_link_args = ( isset( $_POST['cpage'] ) ) ? array( 'cpage' => $_POST['cpage'] ) : array();

		// $before_edit = '<span class="icon icon-left">' . $edit_icn . '</span>' . __( 'Edit', 'canvas' );

		$before = '<span class="icon icon-left">' . $reply_icn . '</span>' . __( 'Reply', 'canvas' );

		// $permalink = '<span class="icon icon-left">' . $link_icn . '</span>' . __( 'Permalink', 'canvas' );

		$post_id = $comment->comment_post_ID;

		if ( 'div' == $args['style'] ) {

			$tag = 'div';

			$add_below = 'comment';

		} else {

			$tag = 'li';

			$add_below = 'div-comment';

		}

		$comment_classes = '';

		$parent_class = empty( $args['has_children'] ) ? '' : $comment_classes .= ' parent';

		$awaiting_moderation_class = ( '0' == $comment->comment_approved ) ? $comment_classes .= ' awaiting-moderation' : '';

		?>

		<<?php echo esc_attr( $tag ); ?> <?php comment_class( $comment_classes, $comment, $post_id ) ?> id="comment-<?php comment_ID() ?>">

		<article class="comment-body">

				<header class="comment-header">
					
					<div class="comment-author vcard">

						<?php if( get_comment_author_url() && get_avatar( $comment ) ) : ?>

							<a href="<?php echo get_comment_author_url(); ?>" class="comment-avatar"><?php echo get_avatar( $comment, 90 ); ?></a>

						<?php elseif( get_avatar( $comment ) ) : ?>

							<span class="comment-avatar"><?php echo get_avatar( $comment, 90 ); ?></span>

						<?php endif; ?>

						<div class="citation">

							<?php 

								$display_name = ( function_exists('bp_is_active') && $comment->user_id ) ? bp_core_get_userlink( $comment->user_id ) : get_comment_author_link( $comment );

								$comment_author = apply_filters( 'canvas_get_comment_author_link', $display_name, $comment, $args, $depth  );

								printf( wp_kses_post( '<cite class="fn">%s</cite>', 'canvas' ), $comment_author );

								$sep = ' <span class="icon icon-xs">' . $chevron_right_icn . '</span>';

								if( $comment->comment_parent ) {

									$comment_author_parent = $sep . ' ' . get_comment_author( $comment->comment_parent );

									$comment_id = $comment->comment_parent;

									$comment_author_parent = '<a class="link link-secondary" href="#comment-'.$comment_id.'">' . $comment_author_parent .'</a>';

									printf( wp_kses_post( '<small class="ref">%s</small>' ), $comment_author_parent );

								}


							?>

						</div>

						<div class="comment-actions" >

							<?php do_action( 'comment_actions' ); ?>

							<div class="mini-dropdown">

								<button class="btn btn-link btn-link-secondary">
									
									<span class="icon"><?php echo $more_icn; ?></span>

								</button>

								<label>

								    <input type="checkbox">

								    <ul class="right">

								    	<?php canvas_comment_links( $comment ); ?>								   
								      
								    </ul>

								</label>

							</div>
						
						</div>

						<div class="comment-meta commentmetadata">

							<small>

								<?php echo '<time class="graphite" datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date( 'M j, Y g:i a' ) . '</time>'; ?>

							</small>

						</div>

					</div>

				</header>

			<?php if ( 'div' != $args['style'] ) : ?>

			<div id="div-comment-<?php comment_ID() ?>" class="comment-content">

			<?php endif; ?>

				<div class="comment-text">


					<?php if ( '0' == $comment->comment_approved ) : ?>

						<p><small><em class="comment-awaiting-moderation"><?php esc_attr_e( 'Comment is awaiting moderation.', 'canvas' ); ?></em></small></p>

						<?php comment_text(); ?>

					<?php else : ?>

							<?php comment_text(); ?>

					<?php endif; ?>

				</div>

				<div class="reply">

					<small><strong><?php comment_reply_link( array_merge( $args, array( 'reply_text' => $before, 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></strong></small>

				</div>

			</div>

		<?php if ( 'div' != $args['style'] ) : ?>

			</article>

		<?php endif; ?>

	<?php
	}
}


function canvas_search_action() {

	$action = apply_filters( 'canvas_default_search_action', array(

		'action'			=>	get_search_link(),

		'placeholder'		=> __( 'Search', 'canvas' )

	) );

	if( ( ! is_front_page() && is_home() ) || is_single() || is_archive() ) {

		$action = array( 

			'action'			=> get_post_type_archive_link( 'post' ),

			'placeholder'		=> __( 'Search Blog', 'canvas' )

		 );

	}

	return apply_filters( 'canvas_search_action', $action );

}


function canvas_get_comment_order() {

	$order = get_option( 'default_comments_page' );

	if( $order == 'newest' ) {

		$order = 'DESC';

	} elseif( $order == 'oldest' ) {

		$order = 'ASC';

	}

	return apply_filters( 'canvas_comment_order', $order );

}

function canvas_sort_comments_by( $order, $default_page ) {


	switch ( $order ) {

		case 'newest':

			$sorted = array( 

				'reverse_top_level'		=> false,
				'order'					=> 'DESC'

			 );

			break;

		case 'oldest':

			$sorted = array( 

				'reverse_top_level'		=> false,
				'order'					=> 'ASC'

			 );

			break;
		
		default:

			$sorted = array( 

				'reverse_top_level'		=> false,
				'order'					=> 'DESC'

			 );

			break;
	}

	return apply_filters( 'canvas_sort_filter', $sorted, $order );

}


function canvas_list_comments( $args = array() ) {

	global $post;

	$defaults = apply_filters( 'canvas_comment_arg_defaults', array( 

		'style'			=> 'ol',
		'short_ping'	=> true,
		'callback'		=> 'canvas_comment',
		'post_id'		=> ( isset( $_POST['post_id'] ) ) ? get_post( $_POST['post_id'] ) : $post->ID,
		'sort_by'		=> get_query_var( 'csort', null ),
		'page'			=> ( isset( $_POST['cpage'] ) ) ? $_POST['cpage'] : false,
		'per_page'		=> get_option( 'comments_per_page' ),
		'default_page'	=> get_option( 'comments_default_page' )


	 ) );

	$args = apply_filters( 'canvas_comment_args', wp_parse_args( $args, $defaults ) );

	$sort_by = canvas_sort_comments_by( $args['sort_by'], $args['default_page'] );

	$comment_args = array( 'post_id' => $args['post_id'], 'order' => $sort_by['order'] );

	wp_list_comments( array(

			'style'      			=> $args['style'],
			'short_ping' 			=> $args['short_ping'],
			'callback'   			=> $args['callback'],
			'reverse_top_level'		=> $sort_by['reverse_top_level'],
			'page'					=> $args['page'],
			'per_page'				=> $args['per_page']

	), get_comments( $comment_args ) ); 

}


function canvas_comment_sort_filters( $filters = array() ) {

	$defaults = apply_filters( 'canvas_comment_sort_filters_default', array(

		'newest' 	=> array(

						'title'		=> __( 'Newest', 'canvas' ),
						'url'		=> add_query_arg( 'csort', 'newest', get_comments_link() )

					),

		'oldest' 	=> array(

						'title'		=> __( 'Oldest', 'canvas' ),
						'url'		=> add_query_arg( 'csort', 'oldest', get_comments_link() )

					),

	) );

	$filters = wp_parse_args( $filters, $defaults );

	return apply_filters( 'canvas_comment_sort_filters', $filters );

}

function canvas_comment_sort_filter_title() {

	$csort = ( get_query_var( 'csort' ) ) ? get_query_var( 'csort' ) : 'newest';

	$filter = canvas_comment_sort_filters();

	if( ! array_key_exists( $csort ,  $filter ) ) {

		$csort = 'newest'; // channge to option set in plugin

	}

	return apply_filters( 'canvas_comment_sort_filter', $filter[ $csort ]['title'], $csort );

}


function canvas_flag_comment() {

	if( isset( $_REQUEST['cid'] ) && isset( $_REQUEST['caction'] ) && isset( $_REQUEST['_flag_comment'] ) ) {

		$comment_id = get_query_var( 'cid' );

		$action = get_query_var( 'caction' );

		if( ! empty( $comment_id ) && ! empty( $action ) ) {

			$actions = canvas_get_comment_action_links( $comment_id );

			if( ! wp_verify_nonce( $_REQUEST['_flag_comment'], 'flag_comment_' . $comment_id ) ) {

				die( 'Not allowed to do that' );

			}

			if( array_key_exists( $action, $actions ) ) {

				if( array_key_exists( 'user_has_access', $actions[ $action ] ) ) {

					if( $actions[ $action ]['user_has_access'] ) {

						print( 'Hello World' );

					}

				}

			}

		}

	}

}