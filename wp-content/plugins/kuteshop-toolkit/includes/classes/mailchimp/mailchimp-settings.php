<?php
if ( !class_exists( 'Toolkit_MailChimp_Settings' ) ) {
	class Toolkit_MailChimp_Settings
	{
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;

		/**
		 * Start up
		 */
		public function __construct()
		{
			$this->options = get_option( 'toolkit_mailchimp_option' );
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 999 );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}

		/**
		 * Add options page
		 */
		public function add_plugin_page()
		{
			// This page will be under "Settings"
			add_submenu_page(
				'kuteshop_menu',
				esc_html__( 'MailChimp Settings', 'kuteshop-toolkit' ),
				esc_html__( 'MailChimp', 'kuteshop-toolkit' ),
				'manage_options',
				'mailchimp-settings',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Options page callback
		 */
		public function create_admin_page()
		{
			?>
            <div class="wrap">
                <h2><?php esc_html_e( 'Mail Chimp Settings', 'kuteshop-toolkit' ); ?></h2>
                <form method="post" action="options.php">
					<?php
					// This prints out all hidden setting fields
					settings_fields( 'toolkit_mailchimp_group' );
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
		public function page_init()
		{
			register_setting(
				'toolkit_mailchimp_group', // Option group
				'toolkit_mailchimp_option', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
			add_settings_section(
				'setting_section_id', // ID
				esc_html__( 'Settings', 'kuteshop-toolkit' ), // Title
				array( $this, 'print_section_info' ), // Callback
				'mailchimp-settings' // Page
			);
			add_settings_field(
				'api_key', // ID
				esc_html__( 'Mail Chimp API Key', 'kuteshop-toolkit' ), // Title
				array( $this, 'api_key_callback' ), // Callback
				'mailchimp-settings', // Page
				'setting_section_id' // Section           
			);
			$api_key = $this->options['api_key'];
			if ( isset ( $api_key ) && !empty ( $api_key ) ) {
				add_settings_field(
					'email_lists', // ID
					esc_html__( 'Email Lists', 'kuteshop-toolkit' ), // Title
					array( $this, 'email_lists_callback' ), // Callback
					'mailchimp-settings', // Page
					'setting_section_id' // Section           
				);
				add_settings_field(
					'success_message', // ID
					esc_html__( 'Success message', 'kuteshop-toolkit' ), // Title
					array( $this, 'success_message_option_callback' ), // Callback
					'mailchimp-settings', // Page
					'setting_section_id' // Section           
				);
			}
		}

		/**
		 * Sanitize each setting field as needed
		 **/
		public function sanitize( $input )
		{
			if ( isset( $input['api_key'] ) )
				$new_input['api_key'] = sanitize_text_field( $input['api_key'] );
			if ( isset( $input['list'] ) )
				$new_input['list'] = sanitize_text_field( $input['list'] );
			if ( isset( $input['success_message'] ) )
				$new_input['success_message'] = sanitize_text_field( $input['success_message'] );

			return $new_input;
		}

		/**
		 * Print the Section text
		 */
		public function print_section_info()
		{
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function api_key_callback()
		{
			printf(
				'<input type="text" id="api_key" size="40" name="toolkit_mailchimp_option[api_key]" value="%s" />',
				isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
			);
			printf(
				'<p class="description">%s</p>',
				esc_html__( 'Enter your mail Chimp API key to enable a newsletter signup option with the registration form.', 'kuteshop-toolkit' )
			);
			printf( wp_kses( __( '<a href="%s" target="_blank">Click here to get your Mailchimp API key</a>', 'kuteshop-toolkit' ), array( 'a' => array( 'href' => array() ) ) ), 'https://admin.mailchimp.com/account/api' );
		}

		public function email_lists_callback()
		{
			$list    = '';
			$api_key = $this->options['api_key'];
			if ( isset( $this->options['list'] ) && $this->options['list'] ) {
				$list = $this->options['list'];
			}
			if ( isset ( $api_key ) && !empty ( $api_key ) ) {
				$mcapi = new MCAPI( $api_key );
				$lists = $mcapi->get_lists();
				echo '<select name="toolkit_mailchimp_option[list]">';
				if ( !empty( $lists ) ) {
					foreach ( $lists as $key => $item ) {
						$selected = '';
						if ( $list == $item->id ) {
							$selected = 'selected';
						}
						echo '<option ' . $selected . ' value="' . $item->id . '">' . $item->name . '</option>';
					}
				}
				echo '</select>';
			}
		}

		public function success_message_option_callback()
		{
			printf(
				'<input type="text" id="success_message" size="40" name="toolkit_mailchimp_option[success_message]" value="%s" />',
				isset( $this->options['success_message'] ) ? esc_attr( $this->options['success_message'] ) : ''
			);
		}
	}
}
if ( is_admin() ) {
	new Toolkit_MailChimp_Settings();
}