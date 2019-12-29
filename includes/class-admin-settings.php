<?php // phpcs:ignore -- ignore class naming
/**
 * Admin Settings
 *
 * This file registers and outputs the admin page dispalyed WP Admin.
 *
 * @package   Ackee_WP
 * @author    Brooke.
 * @copyright 2019 Brooke.
 * @license   GPL-3.0-or-later
 * @link      https://brooke.codes/projects/ackee-wp
 */

namespace AckeeWP;

if ( ! class_exists( 'AckeeWP_Admin_Settings' ) ) :
	/**
	 * Class to display and register WordPress settings page and option array
	 */
	class AckeeWP_Admin_Settings {

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'ackeewp_settings_page' ) );
			add_action( 'admin_init', array( $this, 'ackeewp_register_settings' ) );
		}

		/**
		 * Registers the Ackee settings page under Settings menu.
		 *
		 * @since  0.1.0
		 * @access public
		 * @link   https://developer.wordpress.org/reference/functions/add_options_page/
		 * @return void
		 */
		public function ackeewp_settings_page() {
			add_options_page(
				__( 'Ackee WP Settings', 'ackeewp' ),
				__( 'Ackee WP', 'ackeewp' ),
				'manage_options',
				'ackee-wp',
				array( $this, 'ackeewp_settings_display' )
			);
		}

		/**
		 * Saves the sanatized data into the ackeewp_settings option.
		 *
		 * @since  0.1.0
		 * @access public
		 * @link   https://developer.wordpress.org/reference/functions/register_setting/
		 * @return void
		 */
		public function ackeewp_register_settings() {
			$args = array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'ackeewp_validate_settings' ),
				'default'           => array(
					'instance_url'    => '',
					'tracking_script' => 'tracking.js',
					'domain_id'       => '',
				),
			);
			register_setting( 'ackeewp_settings_group', 'ackeewp_settings', $args );
		}

		/**
		 * Callback to sanatizes the user input before saving the option into the database.
		 *
		 * @since  0.1.0
		 * @access public
		 * @param array $settings The data array from POST object.
		 * @return array
		 */
		public function ackeewp_validate_settings( $settings ) {
			if ( ! isset( $_POST['ackeewp_settings_options_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['ackeewp_settings_options_nonce'] ), 'ackeewp_settings_save_nonce' ) ) { // phpcs:ignore
				return;
			}

			$ackeewp_settings                    = $settings;
			$ackeewp_settings['instance_url']    = esc_url_raw( $settings['instance_url'] );
			$ackeewp_settings['tracking_script'] = sanitize_text_field( $settings['tracking_script'] );
			$ackeewp_settings['domain_id']       = sanitize_text_field( $settings['domain_id'] );

			if ( isset( $settings['exclude_logged_in'] ) && 1 === $settings['exclude_logged_in'] ) {
				$ackeewp_settings['exclude_logged_in'] = 1;
			}

			return $ackeewp_settings;
		}

		/**
		 * HTML markup for the Settings page display. used by ackeewp_settings_page()
		 *
		 * @since  0.1.0
		 * @access public
		 */
		public function ackeewp_settings_display() {
			$ackeewp_settings  = get_option( 'ackeewp_settings' );
			$exclude_logged_in = ( isset( $ackeewp_settings['exclude_logged_in'] ) ) ? 1 : 0;
			?>
			<div class="wrap">
			<h1><?php echo esc_html__( 'Ackee WP Settings', 'ackeewp' ); ?></h1>
			<table class="form-table" role="presentation">
				<form method="post" action="options.php">
				<?php settings_fields( 'ackeewp_settings_group' ); ?>
					<tbody>
						<tr>
							<th scope="row"><label for="ackeewp_instance_url"><?php esc_html_e( 'Ackee Install URL', 'ackeewp' ); ?></label></th>
							<td>
								<input name="ackeewp_settings[instance_url]" type="url" id="ackeewp_instance_url" value="<?php echo ( esc_url( $ackeewp_settings['instance_url'] ) ); ?>" class="regular-text" placeholder="Ackee Install URL" required>
								<p class="description" id="ackeewp-tracking-script-description">
									<?php esc_html_e( 'The base URL for your Ackee install.', 'ackeewp' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="ackeewp_tracker"><?php esc_html_e( 'Ackee Tracker', 'ackeewp' ); ?> </label></th>
							<td>
								<input name="ackeewp_settings[tracking_script]" type="text" id="ackeewp_tracking_script" value="<?php echo ( esc_attr( $ackeewp_settings['tracking_script'] ) ); ?>" placeholder="tracking.js" class="regular-text ltr" required>
								<p class="description" id="ackeewp_domain_id-description">
									<?php
									printf(
										/* translators: This adds a link to the Ackee GitHub repo instruction on Tracking URL and adds code tags  */
										esc_html__( '%1$s %2$s. %3$s.', 'ackeewp' ),
										esc_html__( 'The name of your', 'ackeewp' ),
										/* Link and anchor text*/
										sprintf(
											'<a href="%s">%s</a>',
											esc_url( 'https://github.com/electerious/Ackee#tracker' ),
											esc_html__( 'Ackee Tracker', 'ackeewp' )
										),
										/* Wrapping script name in code tags*/
										sprintf(
											'%s <code>%s</code>',
											esc_html__( 'The default value is', 'ackeewp' ),
											esc_html__( 'tracking.js', 'ackeewp' )
										)
									);
									?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="ackeewp_domain_id"><?php esc_html_e( 'Ackee Domain ID', 'ackeewp' ); ?></label></th>
							<td>
								<input name="ackeewp_settings[domain_id]" type="text" id="ackeewp_domain_id" value="<?php echo ( esc_attr( $ackeewp_settings['domain_id'] ) ); ?>" placeholder="Domain ID" class="regular-text" required>
								<p class="description" id="ackeewp_domain_id-description">
									<?php
									printf(
										/* translators: Requests unique Domain ID with current site URL  */
										esc_html__( 'The unique Domain ID for %s.', 'ackeewp' ),
										esc_url_raw( home_url() )
									);
									?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Exclude Logged In', 'ackeewp' ); ?></th>
							<td>
								<label for="ackeewp_exclude_logged_in">
									<input name="ackeewp_settings[exclude_logged_in]" type="checkbox" id="ackeewp_exclude_logged_in" value="1" <?php checked( $exclude_logged_in, 1 ); ?> > 
									<?php esc_html_e( "If checked, the tracking code won't be output for logged in visits.", 'ackeewp' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php echo ( wp_nonce_field( 'ackeewp_settings_save_nonce', 'ackeewp_settings_options_nonce' ) ); // phpcs:ignore ?>
				<?php submit_button(); ?>
			</form>
			<?php
		} /* end of admin page settings */
	} /* end of class */
	new AckeeWP_Admin_Settings();
endif;

