<?php

	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<?php _e('This post is password protected. Enter the password to view comments.','canvas'); ?>
	<?php
		return;
	}
?>

<?php if ( comments_open() ) : ?>

<div class="comments-open">

	<span id="comments"><strong><?php _e( 'Comments', 'canvas' ); ?></strong><span class="badge badge-md badge-default comment-count"><?php comments_number( '0', '1', '%' ); ?></span></span>


	<div class="comments-settings">
		
		<div id="comments-sort-order" class="mini-dropdown">

			<button class="btn btn-link btn-link-secondary">

				<?php

					$filter_icn = canvas_get_svg_icon( array( 

						'icon'	=> 'filter',
						'size'	=> 'sm'

					 ) );

					$filter = '<span class="icon icon-sm">' . $filter_icn . '</span>';

				?>
				
				<span><?php echo $filter . ' ' . esc_html( canvas_comment_sort_filter_title() ); ?></span>

			</button>

			<label>

			    <input type="checkbox">

			    <ul class="right">

			    	<?php

			    		$filters = canvas_comment_sort_filters();

			    		foreach ( $filters as $key => $filter ) {

			    			$filter_title = $filter['title'];

			    			$filter_url = $filter['url'];
			    			
			    			echo '<li><a href="' . esc_url( htmlspecialchars( $filter_url ) ) . '" class="comment-filter link link-secondary">' . esc_html( $filter_title ) . '</a></li>';

			    		}


			    	?>
			      
			    </ul>

			</label>

		</div>

	</div> <!-- .comments-filter -->

	<div id="respond" class="respond-form">

		<div class="screen-reader-text" ><h2><?php comment_form_title( 'Leave a Reply', __('Leave a Reply to %s') ); ?></h2></div>

		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>

			<div class="comments-open-loggedout">
				
				<p><?php _e('You must be','canvas'); ?> <a href="<?php echo wp_login_url( get_permalink() . '#comments' ); ?>"><?php _e('logged in','canvas'); ?></a> <?php _e('to post a comment.','canvas'); ?></p>

			</div>

		<?php else : ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="comment-body">


			<?php if ( is_user_logged_in() ) : ?>

				<?php

					$edit_user_link = ( ! function_exists( 'bp_get_loggedin_user_link' ) ) ? get_edit_user_link( get_current_user_id() ) : bp_get_loggedin_user_link();

					$user_info = get_userdata( get_current_user_id() );

				?>

					
				<div class="comment-author vcard">

					<a class="comment-avatar" href="<?php echo $edit_user_link;  ?>"><?php echo get_avatar( get_current_user_id(), 30 ); ?></a>

					<div class="citation">

						<cite class="fn">
							
							<a href="<?php echo $edit_user_link;  ?>"><?php printf( wp_kses_post( '<cite class="fn">%s</cite>', 'canvas' ), $user_info->display_name ); ?></a>

						</cite>

					</div>

					<div class="comment-actions" >

						<?php $x_icn = canvas_get_svg_icon( array( 'icon' => 'x', 'size' => 'sm' ) ); ?>

						<?php cancel_comment_reply_link( '<span class="icon icon-sm" >'. $x_icn .'</span>' ); ?>

					</div>

					<div class="comment-meta commentmetadata">
						<small>

							<?php echo '<time class="graphite" datetime="' . current_time( 'c' ) . '">' . current_time( 'M j, Y' ) . '</time>'; ?>

						</small>
					</div>

				</div>

				<!-- <p>You can use these tags: <code><?php echo allowed_tags(); ?></code></p> -->

				<div>
					<textarea name="comment" id="comment" cols="58" rows="6" tabindex="4" class="form-control" placeholder="<?php _e('Leave a comment', 'canvas'); ?>"></textarea>
				</div>

			<?php else : ?>

				<!-- <p>You can use these tags: <code><?php echo allowed_tags(); ?></code></p> -->

				<div class="comment-cancel-action">

					<div class="comment-actions" >

						<?php $x_icn = canvas_get_svg_icon( array( 'icon' => 'x', 'size' => 'sm' ) ); ?>

						<?php cancel_comment_reply_link( '<span class="icon icon-sm" >'. $x_icn .'</span>' ); ?>
						
					</div>
					
				</div>

				<div>
					<textarea name="comment" id="comment" cols="58" rows="6" tabindex="1" class="form-control" placeholder="<?php _e('Leave a comment', 'canvas'); ?>"></textarea>
				</div>

				<div class="comment-author-info" >

					
					<div class="form-group">
						<label for="author" <?php echo ( $req ? 'class="required"' : '' ) ?>><?php _e('Name','canvas'); ?></label>
						<input type="text" name="author" id="author" class="form-control control-sm" placeholder="Name" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
					</div>

					<div class="form-group">
						<label for="email" <?php echo ( $req ? 'class="required"' : '' ) ?>><?php _e('Email','canvas'); ?> *</label>
						<input type="text" name="email" id="email" class="form-control control-sm" placeholder="Email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="3" <?php if ($req) echo "aria-required='true'"; ?> />
					</div>

					<div class="form-group">
						<label for="url"><?php _e('Website','canvas'); ?></label>
						<input type="text" name="url" id="url" class="form-control control-sm" placeholder="Website" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="4" />
					</div>

				</div> <!-- .comment-author-info -->

				<div>

					<p class="graphite"><small>* <?php _e( 'Your email will not be published', 'canvas' ); ?></small></p>

					<?php if( $req ) : ?>

						<p><span class="required-badge badge badge-empty badge-xs badge-danger"></span><small class="graphite"> <?php _e( 'Required', 'canvas' ); ?></small></p>
						
					<?php endif; ?>
					
				</div>

			<?php endif; ?>

			<div class="form-group">
				<input name="submit" type="submit" id="submit" class="btn btn-pill btn-primary" tabindex="5" value="<?php _e('Post Comment','canvas'); ?>" /><span class="ajax-loader" ></span>
				<?php comment_id_fields(); ?>

			</div>
			
			<?php do_action('comment_form', $post->ID); ?>

			<div id="comment-form-feedback" class="alert hidden"></div>

		</form>

		<?php endif; // If registration required and not logged in ?>
		
	</div>	

</div> <!-- .comments-open -->

<?php endif; ?>

<?php if ( have_comments() ) : ?>
	

	<ol class="comments-area">

		<?php 

			canvas_list_comments();

		?>		

	</ol>

	<div class="navigation comments nav-links">
		<div class="next-posts nav-link"><small><?php previous_comments_link() ?></small></div>
		<div class="prev-posts nav-link"><small><?php next_comments_link() ?></small></div>
	</div>

	<?php

		$cpage = get_query_var('cpage') ? get_query_var('cpage') : 1;
		$order = get_option( 'comment_order' );
		$max_page = get_option('thread_comments_depth');

		echo '<script>
			var cpage = ' . $cpage . ',
		    	page_count = ' . get_comment_pages_count() . ';
			</script>';
	 
		if( $cpage > 1 && get_comment_pages_count() > 1 ) {

			echo '<div class="canvas_comment_loadmore"><button class="canvas-comments-loadmore btn btn-pill btn-primary">Load More Comments</button></div>
			<script>
			var ajaxurl = \'' . site_url('wp-admin/admin-ajax.php') . '\',
		    	    cpage = ' . $cpage . ',
		    	    page_count = ' . get_comment_pages_count() . '
			</script>';

		} elseif( $cpage == 1 && get_comment_pages_count() > 1 ) {

			echo '<div class="canvas_comment_loadmore"><button class="canvas-comments-loadmore btn btn-pill btn-primary">Load More Comments</button></div>
			<script>
			var ajaxurl = \'' . site_url('wp-admin/admin-ajax.php') . '\',
		    	    cpage = ' . $cpage . ',
		    	    page_count = ' . get_comment_pages_count() . '
			</script>';


		} elseif( $cpage <= get_comment_pages_count() ) {

			echo '<script>
			var cpage = ' . $cpage . ',
		    	page_count = ' . get_comment_pages_count() . ';
			</script>';

		}

	?>
	
 <?php else : // this is displayed if there are no comments so far ?>

 	<?php

 		$cpage = get_query_var('cpage') ? get_query_var('cpage') : 1;

		echo '<script>
			var cpage = ' . $cpage . ',
				page_count = 0;
			</script>';

 	?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<p><?php _e('Comments are closed.','canvas'); ?></p>

	<?php endif; ?>
	
<?php endif; ?>
