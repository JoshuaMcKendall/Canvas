<?php
/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'buddypress' ); ?>" role="navigation">
	<ul>

		<?php bp_get_options_nav(); ?>

	</ul>
</div><!-- .item-list-tabs -->

<div id="activity-filter-select" class="row">
	<div class="column col-xs-12 col-sm-12 col-md-pull-6 col-lg-pull-5">
		<label for="activity-filter-by" class="assistive-text"><?php _e( 'Show:', 'buddypress' ); ?></label>
		<select id="activity-filter-by" class="form-control">
			<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'buddypress' ); ?></option>

			<?php bp_activity_show_filters(); ?>

			<?php

			/**
			 * Fires inside the select input for member activity filter options.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_member_activity_filter_options' ); ?>

		</select>		
	</div>
</div>

<?php

/**
 * Fires before the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_post_form' ); ?>

<?php
if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
	bp_get_template_part( 'activity/post-form' );

/**
 * Fires after the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_post_form' );

/**
 * Fires before the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_content' ); ?>

<div class="activity" aria-live="polite" aria-atomic="true" aria-relevant="all">

	<?php bp_get_template_part( 'activity/activity-loop' ) ?>

</div><!-- .activity -->

<?php

/**
 * Fires after the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_content' );
