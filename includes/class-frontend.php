<?php
/**
 * Frontend Output
 *
 * This file is used to store functions that control the Ackee output on the front end of a site.
 *
 * @package   Ackee_WP
 * @author    Brooke.
 * @copyright 2019 Brooke.
 * @license   GPL-3.0-or-later
 * @link      https://brooke.codes/plugins/ackee-wp
 */

namespace AckeeWP;

/**
 * Adds the WordPress settings page
 */
if ( ! class_exists( 'AckeeWP_Frontend' ) ) :
	class AckeeWP_Frontend {

		/**
		 * Constructor.
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
			// NOTE TO SELF If NO SETTING.

			// return if we are ignoring logged in visits and the user is logged in.
			if ( true === (bool) $this->ackeewp_get_options( 'exclude_logged_in' ) && is_user_logged_in() ) {
				return;
			}
			// otherwise  register and enqueue the script.
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
		 */
		public function ackeewp_generate_script( $tag, $handle, $src ) {
			if ( 'ackeewp' === $handle ) {
				$tag = '<script async src="' . $src . '" data-ackee-server="' . $this->ackeewp_get_options( 'instance_url' ) . '"data-ackee-domain-id="' . $this->ackeewp_get_options( 'domain_id' ) . '"></script>'; // phpcs:ignore
			}
			return $tag;
		}

		/**
		 * Renders the Ackee script based on the options
		 *
		 * @since  1.0.0
		 * @access private
		 *
		 * $option_key
		 */
		private function ackeewp_get_options( $option_key ) {
			$ackee_settings = get_option( 'ackeewp_settings' );
			// reutrn if option key is not in the settings array.
			if ( ! is_array( $ackee_settings ) || ! array_key_exists( $option_key, $ackee_settings ) ) {
				return;
			}

			return ( 'instance_url' === $option_key ) ? esc_url_raw( $ackee_settings[ $option_key ] ) : sanitize_text_field( $ackee_settings[ $option_key ] );
		}
	} //end of class
	new AckeeWP_Frontend();
endif;

