<?php
if ( !class_exists( 'FamiMailChimp' ) ) {
	class FamiMailChimp
	{
		public         $options = array();
		private static $instance;

		public static function instance()
		{
			if ( !isset( self::$instance ) && !( self::$instance instanceof FamiMailChimp ) ) {
				self::$instance = new FamiMailChimp;
			}
			self::includes();
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'scripts' ) );
			add_action( 'wp_ajax_submit_mailchimp_via_ajax', array( self::$instance, 'submit_mailchimp_via_ajax' ) );
			add_action( 'wp_ajax_nopriv_submit_mailchimp_via_ajax', array( self::$instance, 'submit_mailchimp_via_ajax' ) );
			add_shortcode( "fami_mailchimp", array( self::$instance, 'mailchimp_shortcode' ) );

			return self::$instance;
		}

		public function __construct()
		{
			$mail_chimp_options_default= array(
				'api_key'         => '',
				'list'     => '',
				'success_message' => '',
			);
		    $mail_chimp_options = get_option('ciloe_mailchimp_option', $mail_chimp_options_default);
			$this->options = $mail_chimp_options;
		}

		public static function includes()
		{
			include_once( 'MCAPI.class.php' );
		}

		public function scripts()
		{
			wp_enqueue_script( 'fami-mailchimp', CILOE_TOOLKIT_URL . '/includes/classes/mailchimpv3/mailchimp.js', array( 'jquery' ), '1.0', true );
			wp_localize_script( 'fami-mailchimp', 'fami_mailchimp', array(
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'fami_mailchimp' ),
				)
			);
		}

		public function submit_mailchimp_via_ajax()
		{
			if ( !class_exists( 'MCAPI' ) ) {
				include_once( 'MCAPI.class.php' );
			}
			$response        = array(
				'html'    => '',
				'message' => '',
				'success' => 'no',
			);
			$email           = isset( $_POST['email'] ) ? $_POST['email'] : '';
			$list_id         = isset( $_POST['list_id'] ) ? $_POST['list_id'] : '';
			$fname           = isset( $_POST['fname'] ) ? $_POST['fname'] : '';
			$lname           = isset( $_POST['lname'] ) ? $_POST['lname'] : '';
			$api_key         = "";
			$success_message = esc_html__( 'Your email added...', 'fami-toolkit' );
			if ( $this->options ) {
				$api_key = isset( $this->options['api_key'] ) ? $this->options['api_key'] : '';
				if ( isset( $this->options['success_message'] ) && $this->options['success_message'] != "" ) {
					$success_message = $this->options['success_message'];
				}
			}
			if ( $list_id == '' && $this->options ) {
				$list_id = $this->options['list'];
			}
			$response['message'] = esc_html__( 'Failed', 'fami-toolkit' );
			$merge_vars          = array(
				'FNAME' => $fname,
				'LNAME' => $lname,
			);
			if ( class_exists( 'MCAPI' ) ) {
				$api = new MCAPI( $api_key );
				if ( $api->subscribe( $list_id, $email, $merge_vars ) === true ) {
					$response['message'] = sanitize_text_field( $success_message );
					$response['success'] = 'yes';
				} else {
					// Sending failed
					$response['message'] = $api->get_error_message();
				}
			}
			wp_send_json( $response );
			die();
		}

		public function email_lists_callback()
		{
			$lists   = array();
			$api_key = ciloe_toolkit_option( 'api_key', '' );
			if ( isset ( $api_key ) && !empty ( $api_key ) ) {
				$mcapi = new MCAPI( $api_key );
				if ( $mcapi->get_lists() )
					$lists = $mcapi->get_lists();
			}

			return $lists;
		}

		public function get_email_lists_options()
		{
			$lists          = $this->email_lists_callback();
			$select_options = array();
			if ( !empty( $lists ) ) {
				foreach ( $lists as $key => $list ) {
					$select_options[$list->id] = $list->name;
				}
			}

			return $select_options;
		}

		public function mailchimp_shortcode( $atts, $content = '' )
		{
			$default = array(
				'show_list'   => 'no',
				'field_name'  => 'no',
				'fname_text'  => 'First Name',
				'lname_text'  => 'Last Name',
				'placeholder' => 'Your email letter',
				'button_text' => 'Subscribe',
			);
			$atts    = shortcode_atts( $default, $atts );
			extract( $atts );
			$list_id       = $this->get_email_lists_options();
			$options       = $this->options;
			$list_selected = isset( $options['email_lists'] ) ? $options['email_lists'] : '';
			$class         = array( 'newsletter-form-wrap' );
			if ( $atts['show_list'] == 'yes' ) {
				$class[] = 'has-list-field';
			}
			if ( $atts['field_name'] == 'yes' ) {
				$class[] = 'has-name-field';
			}
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
				<?php if ( $atts['show_list'] == 'yes' && !empty( $list_id ) ): ?>
                    <div class="list">
						<?php foreach ( $list_id as $key => $value ): ?>
                            <label for="<?php echo esc_attr( $key ); ?>">
                                <input <?php if ( $list_selected == $key ): ?> checked="checked"<?php endif; ?>
                                        id="<?php echo esc_attr( $key ); ?>" name="list_id"
                                        value="<?php echo esc_attr( $key ); ?>" type="radio">
                                <span class="text"><?php echo esc_html( $value ); ?></span>
                            </label>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
				<?php if ( $atts['field_name'] == 'yes' ): ?>
                    <label class="text-field field-fname">
                        <input class="input-text fname" type="text" name="fname"
                               placeholder="<?php echo esc_html( $atts['fname_text'] ); ?>">
                    </label>
                    <label class="text-field field-lname">
                        <input class="input-text lname" type="text" name="lname"
                               placeholder="<?php echo esc_html( $atts['lname_text'] ); ?>">
                    </label>
				<?php endif; ?>
                <label class="text-field field-email">
                    <input class="input-text email email-newsletter" type="email" name="email"
                           placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>">
                </label>
                <a href="#" class="button btn-submit submit-newsletter">
					<?php echo esc_html( $atts['button_text'] ); ?>
                </a>
            </div>
			<?php
			$html = ob_get_clean();
			$args = array();

			return apply_filters( 'fami_output_mailchimp_form', $html, $atts );
		}
	}
}
$fami_mailchimp = new FamiMailChimp();
$fami_mailchimp::instance();
