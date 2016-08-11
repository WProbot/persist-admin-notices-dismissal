<?php

/**
 * Admin Notice Dismissal
 *
 * Copyright (C) 2016  Agbonghama Collins <http://w3guy.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Admin Notice Dismissal
 * @author  Agbonghama Collins
 * @author  Andy Fragen
 * @license http://www.gnu.org/licenses GNU General Public License
 * @version 1.1.0
 */

/**
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Don't run during heartbeat.
 */
if ( isset( $_REQUEST['action'] ) && 'heartbeat' === $_REQUEST['action'] ) {
	return;
}

if ( ! class_exists( 'Admin_Notice_Dismissal' ) ) {

	/**
	 * Class PAnD
	 */
	class Admin_Notice_Dismissal {

		/**
		 * Variable for random hash so transients don't collide.
		 *
		 * @var bool|mixed
		 */
		private $hash;

		/**
		 * Singleton variable.
		 *
		 * @var bool
		 */
		private static $instance = false;

		/**
		 * PAnD constructor.
		 */
		public function __construct() {
			$hash = get_site_option( 'admin_notice_hash' );
			if ( ! $hash ) {
				$this->hash = update_site_option( 'admin_notice_hash', md5( uniqid( rand(), true ) ) );
			}
		}

		/**
		 * Singleton instance.
		 *
		 * @return \Admin_Notice_Dismissal|bool
		 */
		public static function instance() {
			if ( false === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Init hooks.
		 */
		public function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_script' ) );
			add_action( 'wp_ajax_admin_notice_dismissal', array( $this, 'dismiss_admin_notice' ) );
		}

		/**
		 * Enqueue javascript and variables.
		 */
		public function load_script() {
			// Need to dequeue if using PAnD
			wp_dequeue_script('dismissible-notices');

			wp_enqueue_script(
				'admin-notice-dismissal',
				plugins_url( 'js/dismiss-notice.js', __FILE__ ),
				array( 'jquery', 'common' ),
				false,
				true
			);

			wp_localize_script(
				'admin-notice-dismissal',
				'dismissible_notice',
				array(
					'nonce' => wp_create_nonce( 'dismissible-notice' ),
				)
			);
		}

		/**
		 * Handles Ajax request to persist notices dismissal.
		 * Uses check_ajax_referer to verify nonce.
		 */
		public function dismiss_admin_notice() {
			$option_name        = sanitize_text_field( $_POST['option_name'] );
			$dismissible_length = sanitize_text_field( $_POST['dismissible_length'] );
			$transient          = 0;

			if ( 'forever' != $dismissible_length ) {
				$transient = $dismissible_length * DAY_IN_SECONDS;
				$dismissible_length = strtotime( absint( $dismissible_length ) . ' days' );
			}

			// @TODO remove this before commit;
			$transient = is_string( $dismissible_length) ? 60 : $dismissible_length * 60;

			check_ajax_referer( 'dismissible-notice', 'nonce' );
			set_site_transient( md5( $this->hash . $option_name ), $dismissible_length, $transient );
			wp_die();
		}

		/**
		 * Is admin notice active?
		 *
		 * @param string $arg data-dismissible content of notice.
		 *
		 * @return bool
		 */
		public function is_admin_notice_active( $arg ) {
			$array       = explode( '-', $arg );
			$length      = array_pop( $array );
			$option_name = implode( '-', $array );
			$db_record   = get_site_transient( md5( $this->hash . $option_name ) );

			if ( 'forever' == $db_record ) {
				return false;
			} elseif ( absint( $db_record ) >= time() ) {
				return false;
			} else {
				return true;
			}
		}

	}

}
