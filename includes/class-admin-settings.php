<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- We don't use core's class naming convention
/**
 * Admin Settings
 *
 * This file registers and outputs the admin page dispalyed WP Admin.
 *
 * @package   Soapberry
 * @author    Brooke.
 * @copyright 2019-2020 Brooke.
 * @license   GPL-3.0-or-later
 * @link      https://brooke.codes/projects/soapberry
 */

namespace Soapberry;

if ( ! class_exists( 'Soapberry_Admin_Settings' ) ) :
	/**
	 * Class to display and register WordPress settings page and option array
	 */
	class Soapberry_Admin_Settings {

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'soapberry_settings_page' ) );
			add_action( 'admin_init', array( $this, 'soapberry_register_settings' ) );
		}

		/**
		 * Register the Soapberry settings page under Settings menu
		 * Adds the Ackee instance settings for use on script output
		 *
		 * @since  1.0.0
		 * @access public
		 * @link   https://developer.wordpress.org/reference/functions/add_options_page/
		 * @return void
		 */
		public function soapberry_settings_page() {
			add_options_page(
				__( 'Soapberry Settings', 'soapberry' ),
				__( 'Soapberry', 'soapberry' ),
				'manage_options',
				'soapberry',
				array( $this, 'soapberry_settings_display' )
			);
		}

		/**
		 * Saves the sanatized data into the soapberry_settings option
		 *
		 * @since  1.0.0
		 * @access public
		 * @link   https://developer.wordpress.org/reference/functions/register_setting/
		 * @return void
		 */
		public function soapberry_register_settings() {
			$args = array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'soapberry_validate_settings' ),
				'default'           => array(
					'instance_url'    => '',
					'tracking_script' => 'tracking.js',
					'domain_id'       => '',
				),
			);
			register_setting( 'soapberry_settings_group', 'soapberry_settings', $args );
		}

		/**
		 * Callback to sanatizes the user input before saving the option into the database.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param array $settings The data array from POST object.
		 * @return array
		 */
		public function soapberry_validate_settings( $settings ) {
			if ( ! isset( $_POST['soapberry_settings_options_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['soapberry_settings_options_nonce'] ), 'soapberry_settings_save_nonce' ) ) { // phpcs:ignore
				return;
			}
			$soapberry_settings                    = $settings;
			$soapberry_settings['instance_url']    = esc_url_raw( $settings['instance_url'] );
			$soapberry_settings['tracking_script'] = sanitize_text_field( $settings['tracking_script'] );
			$soapberry_settings['domain_id']       = sanitize_text_field( str_replace( ' ', '-', trim( $settings['domain_id'] ) ) );
			$soapberry_settings['consent_cookie']  = sanitize_text_field( str_replace( ' ', '_', strtolower( trim( $settings['consent_cookie'] ) ) ) );

			if ( isset( $settings['exclude_logged_in'] ) && 1 === $settings['exclude_logged_in'] ) {
				$soapberry_settings['exclude_logged_in'] = 1;
			}

			if ( isset( $settings['ignore_do_not_track'] ) && 1 === $settings['ignore_do_not_track'] ) {
					$soapberry_settings['ignore_do_not_track'] = 1;
			}

			if ( isset( $settings['detailed_enabled'] ) && 1 === $settings['detailed_enabled'] ) {
				$soapberry_settings['detailed_enabled'] = 1;
			}

			return $soapberry_settings;
		}

		/**
		 * HTML markup for the Settings page display. used by soapberry_settings_page()
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function soapberry_settings_display() {
			$soapberry_settings  = get_option( 'soapberry_settings' );
			$exclude_logged_in   = ( isset( $soapberry_settings['exclude_logged_in'] ) ) ? 1 : 0;
			$ignore_do_not_track = ( isset( $soapberry_settings['ignore_do_not_track'] ) ) ? 1 : 0;
			$detailed_enabled    = ( isset( $soapberry_settings['detailed_enabled'] ) ) ? 1 : 0;
			?>
			<div class="wrap">
			<h1><?php echo esc_html__( 'Soapberry Settings', 'soapberry' ); ?></h1>
			<table class="form-table" role="presentation">
				<form method="post" action="options.php">
				<?php settings_fields( 'soapberry_settings_group' ); ?>
					<tbody>
						<tr>
							<th scope="row"><label for="soapberry_instance_url"><?php esc_html_e( 'Ackee Install URL', 'soapberry' ); ?></label></th>
							<td>
								<input name="soapberry_settings[instance_url]" type="url" id="soapberry_instance_url" value="<?php echo ( esc_url( $soapberry_settings['instance_url'] ) ); ?>" class="regular-text" placeholder="Ackee Install URL" required>
								<p class="description" id="soapberry-tracking-script-description">
									<?php esc_html_e( 'The base URL for your Ackee install.', 'soapberry' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="soapberry_ackee_tracking_script"><?php esc_html_e( 'Ackee Tracker', 'soapberry' ); ?> </label></th>
							<td>
								<input name="soapberry_settings[tracking_script]" type="text" id="soapberry_ackee_tracking_script" value="<?php echo ( esc_attr( $soapberry_settings['tracking_script'] ) ); ?>" placeholder="tracking.js" class="regular-text ltr" required>
								<p class="description" id="soapberry_domain_id-description">
									<?php
									printf(
										/* translators: This adds a link to the Ackee GitHub repo instruction on Tracking URL and adds code tags  */
										esc_html__( '%1$s %2$s. %3$s.', 'soapberry' ),
										esc_html__( 'The name of your', 'soapberry' ),
										/* Link and anchor text*/
										sprintf(
											'<a href="%s" target="_blank">%s</a>',
											esc_url( 'https://docs.ackee.electerious.com/#/docs/Options#tracker' ),
											esc_html__( 'Ackee tracker', 'soapberry' )
										),
										/* Wrapping script name in code tags*/
										sprintf(
											'%s <code>%s</code>',
											esc_html__( 'The default value is', 'soapberry' ),
											esc_html__( 'tracking.js', 'soapberry' )
										)
									);
									?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="soapberry_ackee_domain_id"><?php esc_html_e( 'Ackee Domain ID', 'soapberry' ); ?></label></th>
							<td>
								<input name="soapberry_settings[domain_id]" type="text" id="soapberry_ackee_domain_id" value="<?php echo ( esc_attr( $soapberry_settings['domain_id'] ) ); ?>" placeholder="Domain ID" class="regular-text" required>
								<p class="description" id="soapberry_domain_id-description">
									<?php
									printf(
										/* translators: Requests unique Domain ID with current site URL  */
										esc_html__( 'The unique Domain ID for %s.', 'soapberry' ),
										esc_url_raw( home_url() )
									);
									?>
								</p>
							</td>
						</tr>
						<tr>
						<th scope="row"><label for="soapberry_ackee_consent_cookie"><?php esc_html_e( 'Consent Cookie Name', 'soapberry' ); ?></label></th>
							<td>
								<input name="soapberry_settings[consent_cookie]" type="text" id="soapberry_ackee_consent_cookie" value="<?php echo ( esc_attr( $soapberry_settings['consent_cookie'] ) ); ?>" placeholder="Consent Cookie Name" class="regular-text">
								<p class="description" id="soapberry_ackee_consent_cookie-description">
									<?php esc_html_e( 'If set Soapberry will require a cookie with this value to be set as true before outputing the Ackee tracker.', 'soapberry' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Exclude Logged In', 'soapberry' ); ?></th>
							<td>
								<label for="soapberry_exclude_logged_in">
									<input name="soapberry_settings[exclude_logged_in]" type="checkbox" id="soapberry_exclude_logged_in" value="1" <?php checked( $exclude_logged_in, 1 ); ?> >
									<?php esc_html_e( "If checked, the Ackee tracker won't be output for logged in visits.", 'soapberry' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Ignore Do Not Track Preference', 'soapberry' ); ?></th>
							<td>
								<label for="soapberry_ignore_do_not_track">
								<input name="soapberry_settings[ignore_do_not_track]" type="checkbox" id="soapberry_ignore_do_not_track" value="1" <?php checked( $ignore_do_not_track, 1 ); ?> >
								<?php
								printf(
									/* translators: This adds a link to the information on Do Not Track as a link.  */
									esc_html__( '%1$s %2$s %3$s', 'soapberry' ),
									esc_html__( 'The default behavior is to respect', 'soapberry' ),
									/* Link and anchor text */
									sprintf(
										'<a href="%s">%s</a>',
										esc_url( 'https://allaboutdnt.com/' ),
										esc_html__( 'Do Not Track', 'soapberry' )
									),
									sprintf(
										'%s<br>%s',
										esc_html__( 'headers sent by the browser.', 'soapberry' ),
										esc_html__( 'If checked, the Ackee tracker will still be output regrdless of the visitor\'s Do Not Track preference', 'soapberry' )
									)
								);
								?>
								</label>
							</td>
						</tr>
						<tr>
						<th scope="row"><?php echo esc_html__( 'Use Detailed Stats', 'soapberry' ); ?></th>
						<td>
							<label for="soapberry_detailed_enabled">
							<input name="soapberry_settings[detailed_enabled]" type="checkbox" id="soapberry_detailed_enabled" value="1" <?php checked( $detailed_enabled, 1 ); ?> >
							<?php
								printf(
									/* translators: This adds a link to the information Personal Data.  */
									esc_html__( '%1$s %2$s', 'soapberry' ),
									/* Wrap the word `detailed` in code since that's a paramater name */
									sprintf(
										'%s <code> %s</code> %s',
										esc_html__( 'If checked, the Ackee tracker will include the', 'soapberry' ),
										esc_html__( 'detailed' ),
										esc_html__( 'parameter.', 'soapberry' )
									),
									/* Output link to more information on the detailed view*/
									sprintf(
										'<br> %s <a href="%s">%s</a>%s',
										esc_html__( 'As this data may be considered', 'soapberry' ),
										esc_url( 'https://docs.ackee.electerious.com/#/docs/Anonymization#personal-data' ),
										esc_html__( 'Personal Data', 'soapberry' ),
										esc_html__( ', requiring a consent cookie is recommended.', 'soapberry' )
									)
								);
							?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php echo ( wp_nonce_field( 'soapberry_settings_save_nonce', 'soapberry_settings_options_nonce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Genrated field output by Core ?>
				<?php submit_button(); ?>
			</form>
			<?php
		} /* end of admin page settings */
	} /* end of class */
	new Soapberry_Admin_Settings();
endif;

