<?php
/**
 * Two Factor Frontend Shortcode Handler
 *
 * @package Two_Factor
 */

/**
 * Class for handling frontend two-factor settings shortcode. 
 *
 * @since 0.15. 0
 * @package Two_Factor
 */
class Two_Factor_Shortcode {

	/**
	 * Initialize the shortcode.
	 */
	public static function init() {
		add_shortcode( 'two_factor_user_settings', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_assets' ) );
		add_action( 'template_redirect', array( __CLASS__, 'handle_form_submission' ) );
	}

	/**
	 * Render the two-factor settings shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function render_shortcode( $atts ) {
		// Parse shortcode attributes
		$atts = shortcode_atts(
			array(
				'title'      => __( 'Two-Factor Authentication Settings', 'two-factor' ),
				'show_title' => 'true',
			),
			$atts,
			'two_factor_user_settings'
		);

		// Check if user is logged in
		if ( ! is_user_logged_in() ) {
			return '<div class="two-factor-shortcode-notice two-factor-notice two-factor-info">' .  
				'<p>' .  esc_html__( 'Please log in to manage your two-factor authentication settings.', 'two-factor' ) . '</p>' . 
				'</div>';
		}

		$user = wp_get_current_user();

		// Start output buffering
		ob_start();

		?>
		<div class="two-factor-shortcode-wrapper" id="two-factor-frontend-settings">
			<?php
			if ( 'true' === $atts['show_title'] && !  empty( $atts['title'] ) ) {
				echo '<h2 class="two-factor-shortcode-title">' . esc_html( $atts['title'] ) . '</h2>';
			}

			// Display success/error messages
			self::display_messages();

			// Check if user can update options
			$can_update = Two_Factor_Core::current_user_can_update_two_factor_options();

			if ( !  $can_update ) {
				$url = add_query_arg(
					'redirect_to',
					urlencode( get_permalink() ),
					Two_Factor_Core::get_user_two_factor_revalidate_url()
				);
				?>
				<div class="two-factor-notice two-factor-warning">
					<p>
						<?php esc_html_e( 'To update your Two-Factor options, you must first re-validate your session.', 'two-factor' ); ?>
						<a class="button" style="margin: 12px 0 0 0; font-size:14px; box-shadow: 1px 1px 2px 0 #3f3f3f;" href="<?php echo esc_url( $url ); ?>">
							<?php esc_html_e( 'Re-Validate Now', 'two-factor' ); ?>
						</a>
					</p>
				</div>
				<?php
			}

			// Render the two-factor options form
			self::render_two_factor_form( $user, $can_update );
			?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render the two-factor authentication form. 
	 *
	 * @param WP_User $user The user object.
	 * @param bool    $can_update Whether the user can update settings. 
	 */
	private static function render_two_factor_form( $user, $can_update ) {
		$providers         = Two_Factor_Core::get_supported_providers_for_user( $user );
		$enabled_providers = Two_Factor_Core::get_enabled_providers_for_user( $user );
		$primary_provider  = get_user_meta( $user->ID, Two_Factor_Core:: PROVIDER_USER_META_KEY, true );

		if ( empty( $providers ) ) {
			echo '<div class="two-factor-notice two-factor-info">';
			echo '<p>' .  esc_html__( 'No two-factor authentication providers are available for your account.', 'two-factor' ) . '</p>';
			echo '</div>';
			return;
		}

		// Suggest enabling a backup method
		if ( count( $providers ) > 1 && 1 === count( $enabled_providers ) ) {
			?>
			<div class="two-factor-notice two-factor-warning">
				<p><?php esc_html_e( 'To prevent being locked out of your account, consider enabling a backup method like Recovery Codes in case you lose access to your primary method. ', 'two-factor' ); ?></p>
			</div>
			<?php
		}
		?>

		<form method="post" action="" class="two-factor-frontend-form">
			<?php wp_nonce_field( 'two_factor_frontend_update', 'two_factor_frontend_nonce' ); ?>
			<?php wp_nonce_field( 'user_two_factor_options', '_nonce_user_two_factor_options' ); ?>
			<input type="hidden" name="two_factor_frontend_action" value="update_settings" />

			<div class="two-factor-description">
				<p><?php esc_html_e( 'Configure a primary two-factor method along with a backup method, such as Recovery Codes, to avoid being locked out if you lose access to your primary method.', 'two-factor' ); ?></p>
			</div>

			<table class="two-factor-methods-table">
				<tbody>
				<?php foreach ( $providers as $provider_key => $provider ) : ?>
					<tr>
						<td class="two-factor-method-cell">
							<label class="two-factor-method-label">
								<input
									type="checkbox"
									name="<?php echo esc_attr( Two_Factor_Core::ENABLED_PROVIDERS_USER_META_KEY ); ?>[]"
									value="<?php echo esc_attr( $provider_key ); ?>"
									<?php checked( in_array( $provider_key, $enabled_providers, true ) ); ?>
									<?php disabled( ! $can_update ); ?>
								/>
								<strong><?php echo esc_html( $provider->get_label() ); ?></strong>
							</label>

							<?php
							/**
							 * Allow providers to add their configuration UI. 
							 */
							do_action( 'two_factor_user_options_' . $provider_key, $user );
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<div class="two-factor-primary-method">
				<h3><?php esc_html_e( 'Primary Method', 'two-factor' ); ?></h3>
				<p class="description"><?php esc_html_e( 'Select the primary method to use for two-factor authentication when signing in.', 'two-factor' ); ?></p>
				<select name="<?php echo esc_attr( Two_Factor_Core:: PROVIDER_USER_META_KEY ); ?>" <?php disabled( !  $can_update ); ?>>
					<option value=""><?php esc_html_e( 'Default', 'two-factor' ); ?></option>
					<?php foreach ( $providers as $provider_key => $provider ) : ?>
						<option
							value="<?php echo esc_attr( $provider_key ); ?>"
							<?php selected( $provider_key, $primary_provider ); ?>
							<?php disabled( ! in_array( $provider_key, $enabled_providers, true ) ); ?>
						>
							<?php echo esc_html( $provider->get_label() ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php if ( $can_update ) : ?>
				<div class="two-factor-submit">
					<input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'Update Two-Factor Settings', 'two-factor' ); ?>" />
				</div>
			<?php endif; ?>
		</form>

		<?php
		/**
		 * Allow providers to add additional settings sections.
		 */
		do_action( 'show_user_security_settings', $user, $providers );
	}

	/**
	 * Handle form submission on the frontend.
	 */
	public static function handle_form_submission() {
		// Only process if this is our form submission
		if ( ! isset( $_POST['two_factor_frontend_action'] ) || 'update_settings' !== $_POST['two_factor_frontend_action'] ) {
			return;
		}

		// Verify nonce
		if ( ! isset( $_POST['two_factor_frontend_nonce'] ) || ! wp_verify_nonce( $_POST['two_factor_frontend_nonce'], 'two_factor_frontend_update' ) ) {
			return;
		}

		// Check if user is logged in
		if ( ! is_user_logged_in() ) {
			return;
		}

		$user_id = get_current_user_id();

		// Check if user can update options
		if ( ! Two_Factor_Core:: current_user_can_update_two_factor_options( 'save' ) ) {
			self::add_message( 'error', __( 'You do not have permission to update these settings at this time.  Please revalidate your session. ', 'two-factor' ) );
			return;
		}

		// Process the update using the core method
		Two_Factor_Core::user_two_factor_options_update( $user_id );

		// Add success message
		self::add_message( 'success', __( 'Two-Factor settings updated successfully. ', 'two-factor' ) );

		// Redirect to avoid resubmission
		wp_safe_redirect( add_query_arg( 'updated', '1', remove_query_arg( array( 'two_factor_message', 'two_factor_message_type' ) ) ) );
		exit;
	}

	/**
	 * Add a message to be displayed. 
	 *
	 * @param string $type Message type (success, error, warning, info).
	 * @param string $message Message text.
	 */
	private static function add_message( $type, $message ) {
		set_transient( 'two_factor_frontend_message_' . get_current_user_id(), array(
			'type'    => $type,
			'message' => $message,
		), 30 );
	}

	/**
	 * Display any messages. 
	 */
	private static function display_messages() {
		if ( !  is_user_logged_in() ) {
			return;
		}

		$user_id = get_current_user_id();
		$message = get_transient( 'two_factor_frontend_message_' . $user_id );

		if ( $message ) {
			delete_transient( 'two_factor_frontend_message_' .  $user_id );

			$class = 'two-factor-notice two-factor-' . esc_attr( $message['type'] );
			echo '<div class="' . esc_attr( $class ) . '">';
			echo '<p>' . esc_html( $message['message'] ) . '</p>';
			echo '</div>';
		}
	}

	/**
	 * Enqueue frontend assets.
	 */
	public static function maybe_enqueue_assets() {
		// Check if we're on a page/post
		if ( ! is_singular() ) {
			return;
		}

		// Only enqueue on pages with the shortcode
		global $post;
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'two_factor_user_settings' ) ) {
			self::enqueue_frontend_assets();
		}
	}

	/**
	 * Enqueue the necessary assets. 
	 */
	private static function enqueue_frontend_assets() {
		// Enqueue the frontend CSS
		wp_enqueue_style(
			'two-factor-frontend',
			plugins_url( 'two-factor-frontend.css', __FILE__ ),
			array(),
			TWO_FACTOR_VERSION
		);

		// Enqueue scripts that providers might need
		wp_enqueue_script( 'jquery' );
	}
}