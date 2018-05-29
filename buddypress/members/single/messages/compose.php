<?php
/**
 * BuddyPress - Members Single Messages Compose
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<h2 class="bp-screen-reader-text"><?php
	/* translators: accessibility text */
	_e( 'Compose Message', 'buddypress' );
?></h2>

<form action="<?php bp_messages_form_action('compose' ); ?>" method="post" id="send_message_form" class="standard-form" enctype="multipart/form-data">

	<?php

	/**
	 * Fires before the display of message compose content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_messages_compose_content' ); ?>

	<label for="send-to-input"><?php _e("Send To (Username or Friend's Name)", 'buddypress' ); ?></label>
	<noscript>
		<ul class="first acfb-holder">
			<li>
				<?php bp_message_get_recipient_tabs(); ?>
			</li>
		</ul>
	</noscript>

	<input type="text" name="send-to-input" class="send-to-input form-control" id="send-to-input" />


	<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
		<div class="toggle-container">
			<div class="form-toggle column col-unpadded col-xs-2 col-sm-2 col-md-2 col-lg-1">
				<input class="toggle-checkbox" id="send-notice" type="checkbox" name="send-notice" value="1">
				<label class="toggle" for="send-notice">
				  <span class="disc"></span>
				</label>
			</div>		
			<span class="label column col-xs-11 col-sm-10 col-md-10 col-lg-11"><?php _e( "This is a notice to all users.", 'buddypress' ); ?></span>	
		</div>
	<?php endif; ?>

	<label for="subject"><?php _e( 'Subject', 'buddypress' ); ?></label>
	<input type="text" name="subject" id="subject" class="form-control" value="<?php bp_messages_subject_value(); ?>" />

	<label for="message_content"><?php _e( 'Message', 'buddypress' ); ?></label>
	<textarea name="content" id="message_content" class="form-control" rows="15" cols="40"><?php bp_messages_content_value(); ?></textarea>

	<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />

	<?php

	/**
	 * Fires after the display of message compose content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_messages_compose_content' ); ?>

	<div class="submit">
		<input type="submit" value="<?php esc_attr_e( "Send Message", 'buddypress' ); ?>" name="send" id="send" class=" btn btn-pill btn-primary" />
	</div>

	<?php wp_nonce_field( 'messages_send_message' ); ?>
</form>

<script type="text/javascript">
	document.getElementById("send-to-input").focus();
</script>

