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

		if ( function_exists( 'bp_is_active' ) && is_user_logged_in() ) {

			$type 		= $args['type'];
			$height 	= $args['height'];
			$width		= $args['width'];

			$user_avatar = bp_get_loggedin_user_avatar( 'type='.$type.'&width='.$width.'&height='.$height );

		} elseif ( is_user_logged_in() ) {

			$user_id = get_current_user_id();
		
			$user_avatar = get_avatar( $user_id, $args['height'] );

		} else {
			
			$user_avatar = canvas_get_svg_icon( array( 'icon' => $args['icon'], 'size' => $args['icon_size'] ) );

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


if( ! function_exists( 'canvas_get_user_link_items' ) ) {

	function canvas_get_user_link_items( $args = array() ) {

		$defaults = apply_filters( 'canvas_default_user_link_items', array( 

			'user_id'				=> false,
			'notification_count'	=> 0,
			'message_count'			=> 0

		) );

		$args = wp_parse_args( $args, $defaults );

		if( is_user_logged_in() ) {

			if( function_exists( 'bp_is_active' ) ) {

				$bp_messages_link = '';

				$unread_messages_count = '';

				$user_id = ( $args['user_id'] ) ? $args['user_id'] : bp_loggedin_user_id();

				$unread_notifications_count = ( $args['notification_count'] ) ? $args['notification_count'] : bp_notifications_get_unread_notification_count( $user_id );

				if( bp_is_active( 'messages' ) ) {

					$bp_messages_link = bp_loggedin_user_domain().bp_get_messages_slug();

					$unread_messages_count = ( $args['message_count'] ) ? $args['message_count'] : bp_get_total_unread_messages_count( $user_id );

				}

				$user_links = apply_filters( 'canvas_user_links', array( 

					'wp-admin'			=> array(
												'icon'		=> 'sliders',
												'text'		=> __('Admin', 'canvas'),
												'link'		=> get_admin_url(),
												'enabled'	=> current_user_can( 'edit_posts' )
											),


					'profile'			=> array(
												'icon'		=> 'user',
												'text'		=> __('Profile', 'canvas'),
												'link'		=> bp_get_loggedin_user_link(),
												'enabled'	=> bp_is_active( 'xprofile' )
											),
					'notifications'		=> array(
												'icon'		=> 'bell',
												'text'		=> __('Notifications', 'canvas'),
												'link'		=> bp_get_notifications_permalink( $user_id ),
												'enabled'	=> bp_is_active( 'notifications' ),
												'count'		=> $unread_notifications_count
											),
					'messages'			=> array(
												'icon'		=> 'mail',
												'text'		=> __('Messages', 'canvas'),
												'link'		=> $bp_messages_link,
												'enabled'	=> bp_is_active( 'messages' ),
												'count'		=> $unread_messages_count
											),
					'account'			=> array(
												'icon'		=> 'settings',
												'text'		=> __('Account', 'canvas'),
												'link'		=> bp_loggedin_user_domain().bp_get_settings_slug(),
												'enabled'	=> bp_is_active('settings')
											),
					'logout'			=> array(
												'icon'		=> 'log-out',
												'text'		=> __('Log out', 'canvas'),
												'link'		=> wp_logout_url( get_permalink( ) ),
												'enabled'	=> true
											)

				) );


			} elseif ( canvas_is_woocommerce_activated() ) {

				$user_id = ( $args['user_id'] ) ? $args['user_id'] : get_current_user_id();

				$user_links = apply_filters( 'canvas_user_links', array( 

					'wp-admin'			=> array(
												'icon'		=> 'sliders',
												'text'		=> __('Admin', 'canvas'),
												'link'		=> get_admin_url(),
												'enabled'	=> current_user_can( 'edit_posts' )
											),
					'account'			=> array(
												'icon'		=> 'settings',
												'text'		=> __('Account', 'canvas'),
												'link'		=> get_permalink( get_option('woocommerce_myaccount_page_id') ),
												'enabled'	=> get_option('woocommerce_myaccount_page_id')
											),
					'logout'			=> array(
												'icon'		=> 'log-out',
												'text'		=> __('Log out', 'canvas'),
												'link'		=> wp_logout_url( get_permalink() ),
												'enabled'	=> true
											)

				) );

			}

		}

			return $user_links;
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

		if( is_user_logged_in() ) {

			if( function_exists( 'bp_is_active' ) ) {

				$user_id = bp_loggedin_user_id();

				$user_links = canvas_get_user_link_items();

				foreach( $user_links as $key => $user_link ) {

					$user_link = apply_filters( 'canvas_'. $key .'_link_item', $user_link );

					if( $user_link['enabled'] ) {

						$icon = canvas_get_svg_icon( array(

							'icon'	=> $user_link['icon'],
							'size'	=> 'sm'

						) );

						$unread_badge = '';

						if( ( $key == 'notifications' && $user_link['count'] > 0 ) || ( $key == 'messages' && $user_link['count'] > 0 ) ) {

							$count = ( $user_link['count'] ) ? $user_link['count'] : 0;

							$unread_count = ( $count > 99 ) ? '99+' : $count;

							$unread_badge = '<div class="badge badge-md badge-danger badge-inline">'.$unread_count.'</div>';

						}

						$user_meta_item = '<a href="'.esc_url( $user_link['link'] ).'" class="link link-secondary"><span class="icon icon-left icon-sm">'.$icon.'</span>'.$user_link['text'].' '. $unread_badge .'</a>';

						$container .= '<span class="'.esc_attr($key).' user-dropdown-item">'.$user_meta_item.'</span>';

					}

				}

			} elseif( canvas_is_woocommerce_activated() ) {

				$user_id = get_current_user_id();

				$user_links = canvas_get_user_link_items();

				foreach( $user_links as $key => $user_link ) {

					if( $user_link['enabled'] ) {

						$icon = canvas_get_svg_icon( array(

							'icon'	=> $user_link['icon'],
							'size'	=> 'sm'

						) );

						$user_meta_item = '<a href="'.esc_url( $user_link['link'] ).'" class="link link-secondary"><span class="icon icon-left">'.$icon.'</span>'.$user_link['text'] .'</a>';

						$container .= '<span class="'.esc_attr( $key ).' user-dropdown-item">'.$user_meta_item.'</span>';

					}

				}			

			} 

		} else {

			$log_in_icn = canvas_get_svg_icon( array(

				'icon'		=> 'log-in',
				'size'		=> 'sm'

			) );


			$log_in_link = '<a href="'. esc_url( wp_login_url( get_permalink() ) ) .'" class="link link-secondary" ><span class="icon icon-left">'.$log_in_icn.'</span>'. __('Log In', 'canvas') .'</a>';

			$container .= '<span class="user-dropdown-item">'.$log_in_link.'</span>';


			if( get_option( 'users_can_register' ) ) {


				$user_plus_icn = canvas_get_svg_icon( array(

					'icon'		=> 'user-plus',
					'size'		=> 'sm'

				) );

				$register_link = '<a href="'. esc_url( wp_registration_url( get_permalink() ) ) .'" class="link link-secondary" ><span class="icon icon-left">'.$user_plus_icn.'</span>'. __('Register', 'canvas') .'</a>';

				$container .= '<span class="user-dropdown-item">'.$register_link.'</span>';

			}

		}

		return $container;

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

		global $post;

		$reply_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'corner-down-right',
			'size'		=> 'xs'

		 ) );

		$more_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'more-vertical',
			'size'		=> 'sm'

		 ) );

		$flag_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'flag',
			'size'		=> 'sm'

		 ) );

		$chevron_right_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'chevron-right',
			'size'		=> 'xs'

		 ) );


		$edit_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'edit',
			'size'		=> 'sm'

		 ) );

		$link_icn = canvas_get_svg_icon( array( 

			'icon'		=> 'link',
			'size'		=> 'sm'

		 ) );

		$comment_link_args = ( isset( $_POST['cpage'] ) ) ? array( 'cpage' => $_POST['cpage'] ) : array();

		$before_edit = '<span class="icon icon-left">' . $edit_icn . '</span>' . __( 'Edit', 'canvas' );

		$before = '<span class="icon icon-left">' . $reply_icn . '</span>' . __( 'Reply', 'canvas' );

		$permalink = '<span class="icon icon-left">' . $link_icn . '</span>' . __( 'Permalink', 'canvas' );

		$post_id = ( $post ) ? $post->ID : $args['post_id'];

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

								$comment_author = apply_filters( 'canvas_get_comment_author_link', get_comment_author_link( $comment ), $comment, $args, $depth  );

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

								    <?php if ( current_user_can( 'manage_comments' ) ) : ?>

								      <li><?php edit_comment_link( $before_edit, ' ', '' ); ?></li>

								    <?php endif; ?>

								      <!-- <li><a href="#flag" class="link"><span class="icon icon-left"><?php echo $flag_icn; ?></span><?php _e('Flag', 'canvas'); ?></a></li> -->

								      <?php 

								      	$csort = get_query_var( 'csort' );

								      	$comment_permalink = ( $csort ) ? add_query_arg( 'csort', $csort, get_comment_link( $comment->comment_ID, $comment_link_args ) ) : get_comment_link( $comment->comment_ID, $comment_link_args );

								      ?>

								      <li><a href="<?php echo esc_url( htmlspecialchars( $comment_permalink ) ); ?>" class="comment-date link link-secondary"><?php echo $permalink;  ?></a></li>
								      
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