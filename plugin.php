<?php
/**
 * Plugin Name: Admin Notice Dismissal
 * Plugin URI: https://github.com/afragen/persist-admin-notices-dismissal
 * Description: A feature project for persistant dismissal of admin notices.
 * Version: 1.0
 * Author: Agbonghama Collins, Andy Fragen
 * License: GPL2+
 * GitHub Plugin URI: https://github.com/afragen/persist-admin-notices-dismissal
 * GitHub Branch: feature-project
 */

require_once 'class-admin-notice-dismissal.php';
add_action( 'admin_init', array( Admin_Notice_Dismissal::instance(), 'init' ) );

function and_test_admin_notice__success1() {
	$PAnD = new Admin_Notice_Dismissal();
	if ( ! $PAnD->is_admin_notice_active( 'notice-one-forever' ) ) {
		return;
	}

	?>
	<div data-dismissible="notice-one-forever" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Dismiss Forever!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

function and_test_admin_notice__success2() {
	$PAnD = new Admin_Notice_Dismissal();
	if ( ! $PAnD->is_admin_notice_active( 'notice-two-1' ) ) {
		return;
	}

	?>
	<div data-dismissible="notice-two-1" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Dismiss for a Day!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

add_action( 'admin_notices', 'and_test_admin_notice__success1' );
add_action( 'admin_notices', 'and_test_admin_notice__success2' );
