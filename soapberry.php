<?php // phpcs:ignore -- ignore class naming
/**
 * Plugin Name
 *
 * @package   Soapberry
 * @author    Brooke.
 * @copyright 2020 Brooke.
 * @license   GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Soapberry
 * Plugin URI:        https://brooke.codes/projects/soabperry
 * Description:       Adds the tracking JavaScript from a self-hosted Ackee instance to your WordPress.
 * Version:           1.0.0
 * Requires at least: 4.1
 * Requires PHP:      5.6
 * Author:            Brooke.
 * Author URI:        https://broo.ke
 * Text Domain:       soapberry
 * Domain Path:       /languages
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt

 * Soapberry is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Soapberry is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 * along with Soapberry. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

namespace Soapberry;

/**
 * The main Soapberry class which includes our other classes and sets things up.
 */
class Soapberry {

	/**
	 * Constructor.
	 */
	public function __construct() {
		define( 'SOAPBERRY_FILE', __FILE__ );
		define( 'SOAPBERRY_DIR', trailingslashit( dirname( __FILE__ ) ) );
		define( 'SOAPBERRY_VERSION', '1.0.0' );

		register_activation_hook( basename( SOAPBERRY_DIR ) . '/' . basename( SOAPBERRY_FILE ), array( $this, 'activate' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		register_uninstall_hook( SOAPBERRY_FILE, 'uninstall' );
	}
	/**
	 * Textdomain.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'soapberry', false, dirname( plugin_basename( SOAPBERRY_FILE ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 */
	public function includes() {
		include_once SOAPBERRY_DIR . 'includes/class-admin-settings.php';
		include_once SOAPBERRY_DIR . 'includes/class-frontend.php';
	}

	/**
	 * Remove option at uninstall
	 */
	private static function uninstall() {
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			die;
		}
		$option_name = 'soapberry';
		delete_option( $option_name );
	}
}
new Soapberry();
