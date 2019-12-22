<?php
/**
 * Plugin Name
 *
 * @package   Ackee_WP
 * @author    Brooke.
 * @copyright 2019 Brooke.
 * @license   GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Ackee WP
 * Plugin URI:        https://brooke.codes/plugins/ackee-wp
 * Description:       Adds the Ackee JavaScript to your WordPress site.
 * Version:           1.0.0
 * Requires at least: 4.5
 * Requires PHP:      7.0
 * Author:            Brooke.
 * Author URI:        https://broo.ke
 * Text Domain:       ackeewp
 * Domain Path:       /languages
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt

 * Ackee WP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Ackee WP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 * along with Ackee WP. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main AckeeWP class.
 */
class AckeeWP {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Ackee WP constants.
		define( 'ACKEE_WP_FILE', __FILE__ );
		define( 'ACKEE_WP_DIR', trailingslashit( dirname( __FILE__ ) ) );
		define( 'ACKEE_WP_VERSION', '1.0.0' );

		register_activation_hook( basename( ACKEE_WP_DIR ) . '/' . basename( ACKEE_WP_FILE ), array( $this, 'activate' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		register_uninstall_hook( ACKEE_WP_FILE, 'uninstall' );
	}
	/**
	 * Textdomain.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ackeewp', false, dirname( plugin_basename( ACKEE_WP_FILE ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 */
	public function includes() {
		include_once ACKEE_WP_DIR . 'includes/class-admin-settings.php';
		include_once ACKEE_WP_DIR . 'includes/class-frontend.php';
	}

	/**
	 * Remove option at uninstall
	 */
	private static function uninstall() {
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			die;
		}
		$option_name = 'ackeewp';
		delete_option( $option_name );
	}
}
new AckeeWP();
