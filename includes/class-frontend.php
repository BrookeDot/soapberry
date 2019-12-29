<?php // phpcs:ignore -- ignore class naming
/**
 * Frontend Output
 *
 * This file is used to store functions that control the Ackee output on the front end of a site.
 *
 * @package   Ackee_WP
 * @author    Brooke.
 * @copyright 2019 Brooke.
 * @license   GPL-3.0-or-later
 * @link      https://brooke.codes/projects/ackee-wp
 */

namespace AckeeWP;

if ( ! class_exists( 'AckeeWP_Frontend' ) ) :
	/**
	 * Class to Handle the front end display of the JavaScript output.
	 */
	class AckeeWP_Frontend {

		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'ackeewp_maybe_show_script' ) );
		}

		/**
		 * Hooks into wp_enqueue_script to add the script to the footer.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function ackeewp_maybe_show_script() {
			/* Bail if the options haven't been saved yet */
			if ( ! get_option( 'ackeewp_settings' ) ) {
				return;
			}

			/* Don't display the script if we are not tracking logged in visits and the user is logged in. */
			if ( true === (bool) $this->ackeewp_get_options( 'exclude_logged_in' ) && is_user_logged_in() ) {
				return;
			}

			/* All conditions are met to display the script to proceed with register and enqueue the script. */
			add_filter( 'script_loader_tag', array( $this, 'ackeewp_generate_script' ), 10, 3 );

			$ackee_tracking_url = trailingslashit( $this->ackeewp_get_options( 'instance_url' ) ) . $this->ackeewp_get_options( 'tracking_script' );

			wp_register_script( 'ackeewp', $ackee_tracking_url, '', ACKEE_WP_VERSION, 'true' );
			wp_enqueue_script( 'ackeewp' );
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
		public function ackeewp_generate_script( $tag, $handle, $src ) {
			if ( 'ackeewp' === $handle ) {
				$tag = '<script async src="' . $src . '" data-ackee-server="' . $this->ackeewp_get_options( 'instance_url' ) . '" data-ackee-domain-id="' . $this->ackeewp_get_options( 'domain_id' ) . '"></script>'; // phpcs:ignore
			}
			return $tag;
		}

		/**
		 * Renders the Ackee script based on the options
		 *
		 * @since  1.0.0
		 * @access private
		 * @param string $option_key The key of the value we want from the ackeewp_settings option.
		 * @return string
		 */
		private function ackeewp_get_options( $option_key ) {
			$ackee_settings = get_option( 'ackeewp_settings' );

			/* Bail if the supplied option key is not in the settings array or the setting is not an array. */
			if ( ! is_array( $ackee_settings ) || ! array_key_exists( $option_key, $ackee_settings ) ) {
				return;
			}

			/* Escape the URL if we are getting the instance_url otherwise escape the value */
			if ( 'instance_url' === $option_key ) {
				$option_value = esc_url_raw( $ackee_settings[ $option_key ] );
			} else {
				$option_value = sanitize_text_field( $ackee_settings[ $option_key ] );
			}

			return $option_value;
		}
	} //end of class
	new AckeeWP_Frontend();
endif;

