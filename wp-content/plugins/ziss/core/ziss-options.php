<?php
/**
 * For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( ! class_exists( 'ZissReduxFrameworkConfig' ) ) {
	
	class ZissReduxFrameworkConfig {
		
		public $args     = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;
		
		public function __construct() {
			
			if ( ! class_exists( 'ReduxFramework' ) ) {
				return;
			}
			
			$this->initSettings();
		}
		
		public function initSettings() {
			
			// Just for demo purposes. Not needed per say.
			$this->theme = wp_get_theme();
			
			// Set the default arguments
			$this->setArguments();
			
			// Set a few help tabs so you can see how it's done
			$this->setHelpTabs();
			
			// Create the sections and fields
			$this->setSections();
			
			if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
				return;
			}
			
			// If Redux is running as a plugin, this will remove the demo notice and links
			//add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
			
			// Function to test the compiler hook and demo CSS output.
			//add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
			// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
			// Change the arguments after they've been declared, but before the panel is created
			//add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
			// Change the default value of a field after it's been set, but before it's been useds
			//add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
			// Dynamically add a section. Can be also used to modify sections/fields
			add_filter( 'redux/options/' . $this->args['opt_name'] . '/sections', array( $this, 'dynamic_section' ) );
			
			$this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
		}
		
		/**
		 *
		 * This is a test function that will let you see when the compiler hook occurs.
		 * It only runs if a field   set with compiler=>true is changed.
		 * */
		function compiler_action( $options, $css ) {
			
		}
		
		function ts_redux_update_options_user_can_register( $options, $css ) {
			global $ziss;
			$users_can_register = isset( $ziss['opt-users-can-register'] ) ? $ziss['opt-users-can-register'] : 0;
			update_option( 'users_can_register', $users_can_register );
		}
		
		/**
		 *
		 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
		 * Simply include this function in the child themes functions.php file.
		 *
		 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
		 * so you must use get_template_directory_uri() if you want to use any of the built in icons
		 * */
		function dynamic_section( $sections ) {
			//$sections = array();
			$sections[] = array(
				'title'  => esc_html__( 'Section via hook', 'ziss' ),
				'desc'   => wp_kses( __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'ziss' ), array( 'p' => array( 'class' => array() ) ) ),
				'icon'   => 'el-icon-paper-clip',
				// Leave this as a blank section, no options just some intro text set above.
				'fields' => array(),
			);
			
			return $sections;
		}
		
		/**
		 *
		 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
		 * */
		function change_arguments( $args ) {
			//$args['dev_mode'] = true;
			
			return $args;
		}
		
		/**
		 *
		 * Filter hook for filtering the default value of any given field. Very useful in development mode.
		 * */
		function change_defaults( $defaults ) {
			$defaults['str_replace'] = "Testing filter hook!";
			
			return $defaults;
		}
		
		// Remove the demo link and the notice of integrated demo from the redux-framework plugin
		function remove_demo() {
			
			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
				remove_filter( 'plugin_row_meta', array(
					ReduxFrameworkPlugin::instance(),
					'plugin_metalinks'
				), null, 2 );
				
				// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
				remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
				
			}
		}
		
		public function setSections() {
			
			/**
			 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
			 * */
			// Background Patterns Reader
			$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
			$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
			$sample_patterns      = array();
			
			if ( is_dir( $sample_patterns_path ) ) :
				
				if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
					$sample_patterns = array();
					
					while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {
						
						if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
							$name              = explode( ".", $sample_patterns_file );
							$name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
							$sample_patterns[] = array(
								'alt' => $name,
								'img' => $sample_patterns_url . $sample_patterns_file
							);
						}
					}
				endif;
			endif;
			
			ob_start();
			
			$ct          = wp_get_theme();
			$this->theme = $ct;
			$item_name   = $this->theme->get( 'Name' );
			$tags        = $this->theme->Tags;
			$screenshot  = $this->theme->get_screenshot();
			$class       = $screenshot ? 'has-screenshot' : '';
			
			$customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'ziss' ), $this->theme->display( 'Name' ) );
			?>
            <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
				<?php if ( $screenshot ) : ?>
					<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                           title="<?php echo esc_attr( $customize_title ); ?>">
                            <img src="<?php echo esc_url( $screenshot ); ?>"
                                 alt="<?php esc_attr_e( 'Current theme preview', 'ziss' ); ?>"/>
                        </a>
					<?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                         alt="<?php esc_attr_e( 'Current theme preview', 'ziss' ); ?>"/>
				<?php endif; ?>

                <h4>
					<?php echo sanitize_text_field( $this->theme->display( 'Name' ) ); ?>
                </h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf( __( 'By %s', 'ziss' ), $this->theme->display( 'Author' ) ); ?></li>
                        <li><?php printf( __( 'Version %s', 'ziss' ), $this->theme->display( 'Version' ) ); ?></li>
                        <li><?php echo '<strong>' . esc_html__( 'Tags', 'ziss' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo esc_attr( $this->theme->display( 'Description' ) ); ?></p>
					<?php
					if ( $this->theme->parent() ) {
						printf(
							' <p class="howto">' . wp_kses( __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'ziss' ), array( 'a' => array( 'href' => array() ) ) ) . '</p>', esc_html__( 'http://codex.wordpress.org/Child_Themes', 'ziss' ), $this->theme->parent()
							                                                                                                                                                                                                                                                          ->display( 'Name' )
						);
					}
					?>

                </div>

            </div>
			
			<?php
			$item_info = ob_get_contents();
			
			ob_end_clean();
			
			$sampleHTML = '';
			
			$allowed_tag = array(
				'a'      => array(
					'href'   => array(),
					'title'  => array(),
					'target' => array()
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
			);
			
			$general_settings_fields = array(
				'general_introduction' => array(
					'id'    => 'general_introduction',
					'type'  => 'info',
					'style' => 'success',
					'title' => esc_html__( 'Welcome to WooCommerce Product Pinner options panel', 'ziss' ),
					'icon'  => 'el-icon-info-sign',
				),
				array(
					'id'      => 'enable_custom_scroll',
					'type'    => 'switch',
					'title'   => esc_html__( 'Enable Custom Scroll', 'ziss' ),
					'on'      => esc_html__( 'On', 'ziss' ),
					'off'     => esc_html__( 'Off', 'ziss' ),
					'default' => '1',
					'desc'    => esc_html__( 'Enable custom scroll for popup window.', 'ziss' ),
				)
			);
			
			$general_settings_fields[] = array(
				'id'       => 'custom_css_code',
				'type'     => 'ace_editor',
				'title'    => esc_html__( 'Custom CSS', 'ziss' ),
				'subtitle' => esc_html__( 'Paste your custom CSS code here.', 'ziss' ),
				'mode'     => 'css',
				'theme'    => 'monokai',
				'desc'     => esc_html__( 'Custom css code.', 'ziss' ),
				'default'  => '',
			);
			
			$general_settings_fields[] = array(
				'id'       => 'custom_js_code',
				'type'     => 'ace_editor',
				'title'    => esc_html__( 'Custom JS', 'ziss' ),
				'subtitle' => esc_html__( 'Paste your custom JS code here.', 'ziss' ),
				'mode'     => 'javascript',
				'theme'    => 'chrome',
				'desc'     => esc_html__( 'Custom javascript code', 'ziss' ),
				//'default' => "jQuery(document).ready(function(){\n\n});"
			);
			
			/*-- General Settings--*/
			$this->sections[] = array(
				'icon'   => 'el-icon-cogs',
				'title'  => esc_html__( 'General Settings', 'ziss' ),
				'fields' => $general_settings_fields
			);
			
			/*-- Instagram Settings--*/
			$this->sections[] = array(
				'icon'       => 'el-icon-instagram',
				'title'      => esc_html__( 'Instagram Settings', 'ziss' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'instagram_id',
						'type'     => 'text',
						'title'    => esc_html__( 'Instagram ID', 'ziss' ),
						//'desc'     => wp_kses( __( 'Your Instagram ID. How to find?', 'ziss' ), $allowed_tag ),
						'desc'     => sprintf( __( 'Your Instagram ID. Ex: 2267639447. %s', 'ziss' ), '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?' ) . '</a>' ),
						'default'  => '',
						'validate' => 'no_html',
					),
					array(
						'id'       => 'instagram_token',
						'type'     => 'text',
						'title'    => esc_html__( 'Instagram Token', 'ziss' ),
						//'desc'     => wp_kses( __( 'Your Instagram ID. How to find?', 'ziss' ), $allowed_tag ),
						'desc'     => sprintf( __( 'Your Instagram token. Ex: 1677ed0.eade9f2bbe8245ea8bdedab984f3b4c3. %s', 'ziss' ), '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?' ) . '</a>' ),
						'default'  => '',
						'validate' => 'no_html',
					),
					array(
						'id'       => 'instagram_img_limit',
						'type'     => 'text',
						'title'    => esc_html__( 'Instagram Images Per Page', 'ziss' ),
						'default'  => 20,
						'validate' => 'numeric',
					),
				),
			);
			
			/*-- Facebook Settings--*/
			$this->sections[] = array(
				'icon'       => 'el-icon-facebook',
				'title'      => esc_html__( 'Facebook Settings', 'ziss' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'facebook_id',
						'type'     => 'text',
						'title'    => esc_html__( 'Facebook ID', 'ziss' ),
						//'desc'     => wp_kses( __( 'Your Instagram ID. How to find?', 'ziss' ), $allowed_tag ),
						'desc'     => sprintf( __( 'Your Facebook ID. Ex: 876410027050458. %s', 'ziss' ), '<a href="https://findmyfbid.com" target="_blank">' . esc_html__( 'How to find?' ) . '</a>' ),
						'default'  => '',
						'validate' => 'no_html',
					),
					array(
						'id'       => 'facebook_token',
						'type'     => 'text',
						'title'    => esc_html__( 'Access Token', 'ziss' ),
						//'desc'     => wp_kses( __( 'Your Instagram ID. How to find?', 'ziss' ), $allowed_tag ),
						'desc'     => sprintf( __( 'Your Facebook access token. %s', 'ziss' ), '<a href="https://developers.facebook.com/tools/explorer/145634995501895/?method=GET&path=me%2Fphotos%3Ffields%3Dalbum&version=v2.9" target="_blank">' . esc_html__( 'How to find?' ) . '</a>' ),
						'default'  => '',
						'validate' => 'no_html',
					),
					array(
						'id'       => 'facebook_img_limit',
						'type'     => 'text',
						'title'    => esc_html__( 'Facebook Images Per Page', 'ziss' ),
						'default'  => 20,
						'validate' => 'numeric',
					),
				),
			);
			
			/*-- Vendors Settings--*/
			$this->sections[] = array(
				'icon'   => 'el-icon-cogs',
				'title'  => esc_html__( 'Vendors Settings', 'ziss' ),
				// 'subsection' => true,
				'fields' => array(
					array(
						'id'      => 'load_font_awesome',
						'type'    => 'switch',
						'title'   => esc_html__( 'Load Font Awesome', 'ziss' ),
						'on'      => esc_html__( 'On', 'ziss' ),
						'off'     => esc_html__( 'Off', 'ziss' ),
						'default' => '1',
						'desc'    => esc_html__( 'Load font Awesome on the frontend.', 'ziss' ),
					),
				),
			);
			
		}
		
		public function setHelpTabs() {
			
			// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
			$this->args['help_tabs'][] = array(
				'id'      => 'redux-opts-1',
				'title'   => esc_html__( 'Theme Information 1', 'ziss' ),
				'content' => wp_kses( __( '<p>This is the tab content, HTML is allowed.</p>', 'ziss' ), array( 'p' ) ),
			);
			
			$this->args['help_tabs'][] = array(
				'id'      => 'redux-opts-2',
				'title'   => esc_html__( 'Theme Information 2', 'ziss' ),
				'content' => wp_kses( __( '<p>This is the tab content, HTML is allowed.</p>', 'ziss' ), array( 'p' ) ),
			);
			
			// Set the help sidebar
			$this->args['help_sidebar'] = wp_kses( __( '<p>This is the tab content, HTML is allowed.</p>', 'ziss' ), array( 'p' ) );
		}
		
		/**
		 *
		 * All the possible arguments for Redux.
		 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
		 * */
		public function setArguments() {
			
			$theme = wp_get_theme(); // For use with some settings. Not necessary.
			
			$this->args = array(
				// TYPICAL -> Change these values as you need/desire
				'opt_name'           => 'ziss',
				// This is where your data is stored in the database and also becomes your global variable name.
				'display_name'       => '<span class="zan-plugin-name">' . esc_html__( 'WooCommerce Product Pinner', 'ziss' ) . '</span>',
				// Name that appears at the top of your panel
				'display_version'    => ZISS_VERSION,
				// Version that appears at the top of your panel
				//'menu_type'          => 'submenu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'     => false,
				// Show the sections below the admin menu item or not
				'menu_title'         => esc_html__( 'Ziss Options', 'ziss' ),
				'page_title'         => esc_html__( 'Ziss Options', 'ziss' ),
				// You will need to generate a Google API key to use this feature.
				// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
				'google_api_key'     => '',
				// Must be defined to add google fonts to the typography module
				//'async_typography'    => true, // Use a asynchronous font on the front end or font string
				//'admin_bar'           => false, // Show the panel pages on the admin bar
				'global_variable'    => 'ziss',
				// Set a different name for your global variable other than the opt_name
				'dev_mode'           => false,
				// Show the time the page took to load, etc
				'customizer'         => false,
				// Enable basic customizer support
				// OPTIONAL -> Give you extra features
				//'page_priority'      => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				//'page_parent'        => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions'   => 'manage_options',
				// Permissions needed to access the options panel.
				'menu_icon'          => '',
				// Specify a custom URL to an icon
				'last_tab'           => '',
				// Force your panel to always open to a specific tab (by id)
				'page_icon'          => 'icon-themes',
				// Icon displayed in the admin panel next to your menu_title
				'page_slug'          => 'ziss_options',
				// Page slug used to denote the panel
				'save_defaults'      => true,
				// On load save the defaults to DB before user clicks save or not
				'default_show'       => false,
				// If true, shows the default value next to each field that is not the default value.
				'default_mark'       => '',
				// What to print by the field's title if the value shown is default. Suggested: *
				// CAREFUL -> These options are for advanced use only
				'transient_time'     => 60 * MINUTE_IN_SECONDS,
				'output'             => true,
				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag'         => true,
				// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				//'domain'              => 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
				'footer_credit'      => esc_html__( 'Zan Themes WordPress Team', 'ziss' ),
				// Disable the footer credit of Redux. Please leave if you can help it.
				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database'           => '',
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'show_import_export' => true,
				// REMOVE
				'system_info'        => false,
				// REMOVE
				'help_tabs'          => array(),
				'help_sidebar'       => '',
				// esc_html__( '', $this->args['domain'] );
				'hints'              => array(
					'icon'          => 'icon-question-sign',
					'icon_position' => 'right',
					'icon_color'    => 'lightgray',
					'icon_size'     => 'normal',
					
					'tip_style'    => array(
						'color'   => 'light',
						'shadow'  => true,
						'rounded' => false,
						'style'   => '',
					),
					'tip_position' => array(
						'my' => 'top left',
						'at' => 'bottom right',
					),
					'tip_effect'   => array(
						'show' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'mouseover',
						),
						'hide' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'click mouseleave',
						),
					),
				),
			);
			
			$this->args['share_icons'][] = array(
				'url'   => 'https://www.facebook.com/thuydungcafe',
				'title' => 'Like us on Facebook',
				'icon'  => 'el-icon-facebook',
			);
			$this->args['share_icons'][] = array(
				'url'   => 'http://twitter.com/',
				'title' => 'Follow us on Twitter',
				'icon'  => 'el-icon-twitter',
			);
			
			// Panel Intro text -> before the form
			if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
				if ( ! empty( $this->args['global_variable'] ) ) {
					$v = $this->args['global_variable'];
				} else {
					$v = str_replace( "-", "_", $this->args['opt_name'] );
				}
				
			} else {
				
			}
			
		}
		
	}
	
	global $ZissReduxFrameworkConfig;
	$ZissReduxFrameworkConfig = new ZissReduxFrameworkConfig();
}


/**
 *
 * Custom function for the callback referenced above
 */
if ( ! function_exists( 'redux_my_custom_field' ) ):
	
	function redux_my_custom_field( $field, $value ) {
		print_r( $field );
		print_r( $value );
	}

endif;

/**
 *
 * Custom function for the callback validation referenced above
 * */
if ( ! function_exists( 'redux_validate_callback_function' ) ):
	
	function redux_validate_callback_function( $field, $value, $existing_value ) {
		$error = false;
		$value = 'just testing';
		
		$return['value'] = $value;
		if ( $error == true ) {
			$return['error'] = $field;
		}
		
		return $return;
	}

endif;