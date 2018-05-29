<?php
/**
 * BuddyPress - Members Settings Delete Account
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="row">
	<div class="column col-xs-12">
		<?php do_action( 'bp_before_member_settings_template' ); ?>
	</div>
</div>

<h2><?php
	/* translators: accessibility text */
	_e( 'Delete Account', 'buddypress' );
?></h2>

<div id="message" class="info">

	<?php if ( bp_is_my_profile() ) : ?>

		<p><?php _e( 'Deleting your account will delete all of the content you have created. It will be completely irrecoverable.', 'buddypress' ); ?></p>

	<?php else : ?>

		<p><?php _e( 'Deleting this account will delete all of the content it has created. It will be completely irrecoverable.', 'buddypress' ); ?></p>

	<?php endif; ?>

</div>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/delete-account'; ?>" name="account-delete-form" id="account-delete-form" class="standard-form" method="post">

	<?php

	/**
	 * Fires before the display of the submit button for user delete account submitting.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_members_delete_account_before_submit' ); ?>

	<div class="toggle-container">

		<div class="form-toggle column col-unpadded col-xs-2 col-sm-2 col-md-2 col-lg-1">

			<input type="checkbox" class="toggle-checkbox" name="delete-account-understand" id="delete-account-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-account-button').disabled = ''; } else { document.getElementById('delete-account-button').disabled = 'disabled'; }" />

			<label class="toggle" for="delete-account-understand">

			  <span class="disc"></span>

			</label>
		</div>

		<span class="label column col-xs-11 col-sm-10 col-md-10 col-lg-11"><?php _e( 'I understand the consequences.', 'buddypress' ); ?></span>	
	</div>

	<div class="submit">
		<input type="submit" disabled="disabled" value="<?php esc_attr_e( 'Delete Account', 'buddypress' ); ?>" id="delete-account-button" name="delete-account-button" class="btn btn-pill btn-danger" />
	</div>

	<?php

	/**
	 * Fires after the display of the submit button for user delete account submitting.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_members_delete_account_after_submit' ); ?>

	<?php wp_nonce_field( 'delete-account' ); ?>

</form>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_after_member_settings_template' );
