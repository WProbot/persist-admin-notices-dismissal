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

require __DIR__ . 'class-admin-notice-dismissal.php';

$PAnD = new Admin_Notice_Dismissal();

function and_test_admin_notice__success1( $PAnD ) {
	if ( ! $PAnD->is_admin_notice_active( 'data-notice-one-forever' ) ) {
		return;
	}

	?>
	<div data-dismissible="data-notice-one-forever" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done 1!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

function and_test_admin_notice__success2( $PAnD ) {
	if ( ! $PAnD->is_admin_notice_active( 'data-notice-two-2' ) ) {
		return;
	}

	?>
	<div data-dismissible="data-notice-two-2" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done 2!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

add_action( 'admin_notices', 'and_test_admin_notice__success1' );
add_action( 'admin_notices', 'and_test_admin_notice__success2' );

