<<<<<<< HEAD
<?php // phpcs:ignore -- ignore class naming
=======
<?php  // phpcs:ignore -- ignore class naming
>>>>>>> dev
/**
 * Frontend Output
 *
 * This file is used to store functions that control the Ackee output on the front end of a site.
 *
 * @package   Ackee_WP
 * @author    Brooke.
 * @copyright 2019 Brooke.
 * @license   GPL-3.0-or-later
<<<<<<< HEAD
 * @link      https://brooke.codes/projects/ackee-wp
=======
 * @link      https://brooke.codes/projects/soapberry
>>>>>>> dev
 */

namespace Soapberry;

<<<<<<< HEAD
if ( ! class_exists( 'AckeeWP_Frontend' ) ) :
	/**
	 * Class to Handle the front end display of the JavaScript output.
	 */
	class AckeeWP_Frontend {
=======
if ( ! class_exists( 'Soapberry_Frontend' ) ) :
	/**
	 * Class to Handle the front end display of the JavaScript output.
	 */
	class Soapberry_Frontend {
>>>>>>> dev

		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
<<<<<<< HEAD
			add_action( 'wp_enqueue_scripts', array( $this, 'ackeewp_maybe_show_script' ) );
=======
			add_action( 'wp_enqueue_scripts', array( $this, 'soapberry_maybe_show_script' ) );
>>>>>>> dev
		}

		/**
		 * Hooks into wp_enqueue_script to add the script to the footer.
		 *
		 * @since  1.0.0
		 * @access public
		 */
<<<<<<< HEAD
		public function ackeewp_maybe_show_script() {
			/* Bail if the options haven't been saved yet */
			if ( ! get_option( 'ackeewp_settings' ) ) {
=======
		public function soapberry_maybe_show_script() {
			/* Bail if the options haven't been saved yet */
			if ( ! get_option( 'soapberry_settings' ) ) {
>>>>>>> dev
				return;
			}

			/* Don't display the script if we are not tracking logged in visits and the user is logged in. */
<<<<<<< HEAD
			if ( true === (bool) $this->ackeewp_get_options( 'exclude_logged_in' ) && is_user_logged_in() ) {
=======
			if ( true === (bool) $this->soapberry_get_options( 'exclude_logged_in' ) && is_user_logged_in() ) {
>>>>>>> dev
				return;
			}

			/* All conditions are met to display the script to proceed with register and enqueue the script. */
<<<<<<< HEAD
			add_filter( 'script_loader_tag', array( $this, 'ackeewp_generate_script' ), 10, 3 );

			$ackee_tracking_url = trailingslashit( $this->ackeewp_get_options( 'instance_url' ) ) . $this->ackeewp_get_options( 'tracking_script' );

			wp_register_script( 'ackeewp', $ackee_tracking_url, '', ACKEE_WP_VERSION, 'true' );
			wp_enqueue_script( 'ackeewp' );
=======
			add_filter( 'script_loader_tag', array( $this, 'soapberry_generate_script' ), 10, 3 );

			$ackee_tracking_url = trailingslashit( $this->soapberry_get_options( 'instance_url' ) ) . $this->soapberry_get_options( 'tracking_script' );

			wp_register_script( 'soapberry', $ackee_tracking_url, '', SOAPBERRY_VERSION, 'true' );
			wp_enqueue_script( 'soapberry' );
>>>>>>> dev
		}

		/**
		 * Renders the Ackee script based on the options
		 *
		 * @since  1.0.0
		 * @access private
		 * @link https://developer.wordpress.org/reference/hooks/script_loader_tag/
		 * @param string $tag    The `<script>` tag for the enqueued script.
		 * @param string $handle The script's registered handle.
		 * @param string $src    The script's source URL.
		 * @return string
		 */
<<<<<<< HEAD
		public function ackeewp_generate_script( $tag, $handle, $src ) {
			if ( 'ackeewp' === $handle ) {
				$tag = '<script async src="' . $src . '" data-ackee-server="' . $this->ackeewp_get_options( 'instance_url' ) . '" data-ackee-domain-id="' . $this->ackeewp_get_options( 'domain_id' ) . '"></script>'; // phpcs:ignore
=======
		public function soapberry_generate_script( $tag, $handle, $src ) {
			if ( 'soapberry' === $handle ) {
				$tag = '<script async src="' . $src . '" data-ackee-server="' . $this->soapberry_get_options( 'instance_url' ) . '" data-ackee-domain-id="' . $this->soapberry_get_options( 'domain_id' ) . '"></script>'; // phpcs:ignore -- False postive, not outputing script.
>>>>>>> dev
			}
			return $tag;
		}

		/**
		 * Renders the Ackee script based on the options
		 *
		 * @since  1.0.0
		 * @access private
<<<<<<< HEAD
		 * @param string $option_key The key of the value we want from the ackeewp_settings option.
		 * @return string
		 */
		private function ackeewp_get_options( $option_key ) {
			$ackee_settings = get_option( 'ackeewp_settings' );

			/* Bail if the supplied option key is not in the settings array or the setting is not an array. */
			if ( ! is_array( $ackee_settings ) || ! array_key_exists( $option_key, $ackee_settings ) ) {
=======
		 * @param string $option_key The key of the value we want from the soapberry_settings option.
		 * @return string
		 */
		private function soapberry_get_options( $option_key ) {
			$soapberry_settings = get_option( 'soapberry_settings' );

			/* Bail if the supplied option key is not in the settings array or the setting is not an array. */
			if ( ! is_array( $soapberry_settings ) || ! array_key_exists( $option_key, $soapberry_settings ) ) {
>>>>>>> dev
				return;
			}

			/* Escape the URL if we are getting the instance_url otherwise escape the value */
			if ( 'instance_url' === $option_key ) {
<<<<<<< HEAD
				$option_value = esc_url_raw( $ackee_settings[ $option_key ] );
			} else {
				$option_value = sanitize_text_field( $ackee_settings[ $option_key ] );
=======
				$option_value = esc_url_raw( $soapberry_settings[ $option_key ] );
			} else {
				$option_value = sanitize_text_field( $soapberry_settings[ $option_key ] );
>>>>>>> dev
			}

			return $option_value;
		}
	} //end of class
<<<<<<< HEAD
	new AckeeWP_Frontend();
=======
	new Soapberry_Frontend();
>>>>>>> dev
endif;

