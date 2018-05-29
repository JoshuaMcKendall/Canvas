<?php
/**
 * BuddyPress - Users Notifications
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'buddypress' ); ?>" role="navigation">
	<ul>
		<?php bp_get_options_nav(); ?>

	</ul>
</div>



<div id="members-order-select" class="filter row">
	<div class="column col-xs-12 col-sm-12 col-md-pull-6 col-lg-pull-5">
		<?php canvas_notifications_sort_order_form(); ?>	
	</div>
</div>

<?php
switch ( bp_current_action() ) :

	case 'unread' :
		bp_get_template_part( 'members/single/notifications/unread' );
		break;

	case 'read' :
		bp_get_template_part( 'members/single/notifications/read' );
		break;

	// Any other actions.
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
