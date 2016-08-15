# Admin Notice Dismissal

Simple plugin for a feature project that persists the dismissal of admin notices across pages in WordPress dashboard.

## Installation

Activate the plugin.

## How to Use
Say you have the following markup as your admin notice,

```php
function sample_admin_notice__success() {
	?>
	<div class="updated notice notice-success is-dismissible">
    	<p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'sample_admin_notice__success' );
```

To make it hidden forever when dismissed, add the following data attribute `data-dismissible="disable-done-notice-forever"` to the div markup like so:


```php
function sample_admin_notice__success() {
	if ( ! Admin_Notice_Dismissal::instance()->is_admin_notice_active( 'disable-done-notice-forever' ) ) {
		return;
	}
	
	?>
	<div data-dismissible="disable-done-notice-forever" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'sample_admin_notice__success' );
```

#### Usage Instructions and Examples
If you have two notices displayed when certain actions are triggered; firstly, choose a string to uniquely identify them, e.g. `notice-one` and `notice-two`

To make the first notice never appear once dismissed, its `data-dismissible` attribute will be `data-dismissible="notice-one-forever"` where `notice-one` is its unique identifier and `forever` is the dismissal time period.

To make the second notice only hidden for 2 days, its `data-dismissible` attribute will be `data-dismissible="notice-two-2"` where `notice-two` is its unique identifier and the `2`, the number of days it will be hidden is the dismissal time period.

You **must** append the dismissal time period to the end of your unique identifier with a hyphen (`-`) and this value must be an integer. The only exception is the string `forever`.

To actually make the dismissed admin notice not to appear, use the `is_admin_notice_active()` function like so:


```php
function sample_admin_notice__success1() {
	if ( ! Admin_Notice_Dismissal::instance()->is_admin_notice_active( 'notice-one-forever' ) ) {
		return;
	}

	?>
	<div data-dismissible="notice-one-forever" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done 1!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

function sample_admin_notice__success2() {
	if ( ! Admin_Notice_Dismissal::instance()->is_admin_notice_active( 'notice-two-2' ) ) {
		return;
	}

	?>
	<div data-dismissible="notice-two-2" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done 2!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

add_action( 'admin_notices', 'sample_admin_notice__success1' );
add_action( 'admin_notices', 'sample_admin_notice__success2' );
```


Cool beans. Isn't it?
