<?php
if ( ! class_exists( 'Ciloe_MailChimp_Settings' ) ) {
	class Ciloe_MailChimp_Settings {
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;
		
		/**
		 * Start up
		 */
		public function __construct() {
			$this->options = get_option( 'ciloe_mailchimp_option' );
			
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 999 );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}
		
		/**
		 * Add options page
		 */
		
		public function add_plugin_page()
		{
			// This page will be under "Settings"
			add_submenu_page( 'ciloe_menu', __( 'MailChimp Settings', 'ciloe-toolkit' ), __( 'MailChimp', 'ciloe-toolkit' ), 'manage_options', 'mailchimp-settings', array( $this, 'create_admin_page' ) );
		}
		/**
		 * Options page callback
		 */
		public function create_admin_page() {
			?>
            <div class="wrap">
                <h2><?php _e( 'Mail Chimp Settings', 'ciloe' ); ?></h2>
                <form method="post" action="options.php">
					<?php
					// This prints out all hidden setting fields
					settings_fields( 'ciloe_mailchimp_group' );
					do_settings_sections( 'mailchimp-settings' );
					submit_button();
					?>
                </form>
            </div>
			<?php
		}
		
		/**
		 * Register and add settings
		 */
		public function page_init() {
			register_setting(
				'ciloe_mailchimp_group', // Option group
				'ciloe_mailchimp_option', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
			
			add_settings_section(
				'setting_section_id', // ID
				__( 'Settings', 'ciloe' ), // Title
				array( $this, 'print_section_info' ), // Callback
				'mailchimp-settings' // Page
			);
			
			add_settings_field(
				'api_key', // ID
				__( 'Mail Chimp API Key', 'ciloe' ), // Title
				array( $this, 'api_key_callback' ), // Callback
				'mailchimp-settings', // Page
				'setting_section_id' // Section
			);
			
			$api_key = $this->options['api_key'];
			
			if ( isset ( $api_key ) && ! empty ( $api_key ) ) {
				add_settings_field(
					'email_lists', // ID
					__( 'Email Lists', 'ciloe' ), // Title
					array( $this, 'email_lists_callback' ), // Callback
					'mailchimp-settings', // Page
					'setting_section_id' // Section
				);
				
				add_settings_field(
					'success_message', // ID
					__( 'Success Message', 'ciloe' ), // Title
					array( $this, 'success_message_option_callback' ), // Callback
					'mailchimp-settings', // Page
					'setting_section_id' // Section
				);
			}
			
		}
		
		/**
		 * Sanitize each setting field as needed
		 *
		 * @param array $input Contains all settings fields as array keys
		 */
		public function sanitize( $input ) {
			if ( isset( $input['api_key'] ) ) {
				$new_input['api_key'] = sanitize_text_field( $input['api_key'] );
			}
			
			if ( isset( $input['list'] ) ) {
				$new_input['list'] = sanitize_text_field( $input['list'] );
			}
			
			if ( isset( $input['success_message'] ) ) {
				$new_input['success_message'] = sanitize_text_field( $input['success_message'] );
			}
			
			return $new_input;
		}
		
		/**
		 * Print the Section text
		 */
		public function print_section_info() { }
		
		/**
		 * Get the settings option array and print one of its values
		 */
		public function api_key_callback() {
			printf(
				'<input type="text" id="api_key" size="40" name="ciloe_mailchimp_option[api_key]" value="%s" />',
				isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
			);
			printf(
				'<p class="description">%s</p>',
				__( 'Enter your mail Chimp API key to enable a newsletter signup option with the registration form.', 'ciloe' )
			);
			printf( wp_kses( __( '<a href="%s" target="__blank">Click here to get your Mailchimp API key</a>', 'ciloe' ), array( 'a' => array( 'href' => array() ) ) ), 'https://admin.mailchimp.com/account/api' );
			
		}
		
		public function email_lists_callback() {
			$api_key     = $this->options['api_key'];
			$select_list = '';
			if ( isset( $this->options['list'] ) && $this->options['list'] ) {
				$select_list = $this->options['list'];
			}
			
			$selected = '';
			if ( isset ( $api_key ) && ! empty ( $api_key ) ) {
				$mcapi = new MCAPI( $api_key );
				$lists = $mcapi->get_lists();
				echo '<select name="ciloe_mailchimp_option[list]">';
				foreach ( $lists as $list_item ) {
					echo '<option ' . selected( $select_list == $list_item->id, true, false ) . ' value="' . $list_item->id . '">' . $list_item->name . '</option>';
				}
				echo '</select>';
				
			}
		}
		
		public function success_message_option_callback() {
			printf(
				'<input type="text" id="success_message" size="40" name="ciloe_mailchimp_option[success_message]" value="%s" />',
				isset( $this->options['success_message'] ) ? esc_attr( $this->options['success_message'] ) : ''
			);
		}
	}
}


if ( is_admin() ) {
	new Ciloe_MailChimp_Settings();
}