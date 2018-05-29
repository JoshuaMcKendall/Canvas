<?php
/**
 * BuddyPress - Members Notifications Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<form action="" method="post" id="notifications-bulk-management">
	<table class="notifications">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="bulk-select-all"><label for="select-all-notifications"><input id="select-all-notifications" type="checkbox"><span class="bp-screen-reader-text"><?php
					/* translators: accessibility text */
					_e( 'Select all', 'buddypress' );
				?></span></label></th>
				<th class="title"><?php _e( 'Notification', 'buddypress' ); ?></th>
				<th class="delete-actions"></th>
				<th class="read-actions"></th>
			</tr>
		</thead>

		<tbody>

			<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

				<tr>
					<td></td>
					<td class="bulk-select-check"><label for="<?php bp_the_notification_id(); ?>" ><input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check"><span class="bp-screen-reader-text"><?php
						/* translators: accessibility text */
						_e( 'Select this notification', 'buddypress' );
					?></span></label></td>
					<td class="notification-description"><?php bp_the_notification_description();  ?><div><small><?php bp_the_notification_time_since();   ?></small></div></td>
					<!-- <td class="notification-since"></td> -->
					<?php bp_the_notification_action_links( array( 'sep' => '  ' ) ); ?>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

	<div class="notifications-options-nav row">
		<?php canvas_notifications_bulk_management_dropdown(); ?>		
	</div><!-- .notifications-options-nav -->

	<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
</form>
