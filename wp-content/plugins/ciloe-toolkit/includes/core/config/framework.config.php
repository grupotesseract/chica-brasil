<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( ! class_exists( 'Ciloe_ThemeOption' ) ) {
	class Ciloe_ThemeOption {
		public $sidebars        = array();
		public $header_options  = array();
		public $product_options = array();
		
		public function __construct() {
			$this->get_sidebars();
			$this->get_footer_options();
			$this->get_header_options();
			$this->ciloe_rev_slide_options_for_redux();
			$this->get_product_options();
			$this->init_settings();
			add_action( 'admin_bar_menu', array( $this, 'ciloe_custom_menu' ), 1000 );
		}
		
		public function get_header_options() {
			$layoutDir      = get_template_directory() . '/templates/headers/';
			$header_options = array();
			
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'header-', '', $fileInfo['filename'] );
								$header_options[ $file_name ] = array(
									'title'   => $file_data['Name'],
									'preview' => get_template_directory_uri() . '/templates/headers/header-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->header_options = $header_options;
		}
		
		/* GET REVOLOTION */
		public function ciloe_rev_slide_options_for_redux() {
			$ciloe_herosection_revolutions = array( '' => esc_html__( '--- Choose Revolution Slider ---', 'ciloe-toolkit' ) );
			if ( class_exists( 'RevSlider' ) ) {
				global $wpdb;
				if ( shortcode_exists( 'rev_slider' ) ) {
					$rev_sql  = $wpdb->prepare(
						"SELECT *
                    FROM {$wpdb->prefix}revslider_sliders
                    WHERE %d", 1
					);
					$rev_rows = $wpdb->get_results( $rev_sql );
					if ( count( $rev_rows ) > 0 ) {
						foreach ( $rev_rows as $rev_row ):
							$ciloe_herosection_revolutions[ $rev_row->alias ] = $rev_row->title;
						endforeach;
					}
				}
			}
			
			$this->herosection_options = $ciloe_herosection_revolutions;
		}
		
		public function get_product_options() {
			$layoutDir       = get_template_directory() . '/woocommerce/product-styles/';
			$product_options = array();
			
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                     = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                     = str_replace( 'content-product-style-', '', $fileInfo['filename'] );
								$product_options[ $file_name ] = array(
									'title'   => $file_data['Name'],
									'preview' => get_template_directory_uri() . '/woocommerce/product-styles/content-product-style-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->product_options = $product_options;
		}
		
		public function ciloe_attributes_options() {
			$attributes     = array();
			$attributes_tax = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attribute_name                = 'pa_' . $attribute->attribute_name;
					$attributes[ $attribute_name ] = $attribute->attribute_label;
				}
			}
			
			return $attributes;
		}
		
		public function get_sidebars() {
			global $wp_registered_sidebars;
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}
			$this->sidebars = $sidebars;
		}
		
		public function ciloe_custom_menu() {
			global $wp_admin_bar;
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'theme_option',
				'title' => esc_html__( 'Theme Options', 'ciloe-toolkit' ),
				'href'  => admin_url( 'admin.php?page=ciloe-toolkit' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}
		
		public function get_footer_options() {
			$footer_options = array(
				'default' => esc_html__( 'Default', 'ciloe-toolkit' ),
			);
			$layoutDir      = get_template_directory() . '/templates/footers/';
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'footer-', '', $fileInfo['filename'] );
								$footer_options[ $file_name ] = $file_data['Name'];
							}
						}
					}
				}
			}
			$this->footer_options = $footer_options;
		}
		
		public function init_settings() {
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK SETTINGS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$settings = array(
				'menu_title'      => 'Theme Options',
				'menu_type'       => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'       => 'ciloe-toolkit',
				'ajax_save'       => true,
				'menu_parent'     => 'ciloe_menu',
				'show_reset_all'  => true,
				'menu_position'   => 2,
				'framework_title' => '<a href="http://ciloe.famithemes.com/" target="_blank"><img src="' . esc_url( CILOE_TOOLKIT_URL . 'assets/images/logo-backend.png' ) . '" alt=""></a> <small>by <a href="https://famithemes.com" target="_blank">FamiThemes</a></small>',
			);
			
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK OPTIONS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$options = array();
			
			// ----------------------------------------
			// a option section for options overview  -
			// ----------------------------------------
			$options[] = array(
				'name'     => 'general',
				'title'    => esc_html__( 'General', 'ciloe-toolkit' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'main_settings',
						'title'  => esc_html__( 'Main Settings', 'ciloe-toolkit' ),
						'fields' => array(
							array(
								'id'        => 'ciloe_logo',
								'type'      => 'image',
								'title'     => esc_html__( 'Logo', 'ciloe-toolkit' ),
								'add_title' => esc_html__( 'Add Logo', 'ciloe-toolkit' ),
								'desc'      => esc_html__( 'Add custom logo for your website.', 'ciloe-toolkit' ),
							),
							array(
								'id'      => 'ciloe_main_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Main Color', 'ciloe-toolkit' ),
								'default' => '#ffa749',
								'rgba'    => true,
							),
							array(
								'id'      => 'ciloe_body_text_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Body Text Color', 'ciloe-toolkit' ),
								'default' => '#999',
								'rgba'    => true,
							),
							array(
								'id'    => 'gmap_api_key',
								'type'  => 'text',
								'title' => esc_html__( 'Google Map API Key', 'ciloe-toolkit' ),
								'desc'  => wp_kses( sprintf( __( 'Enter your Google Map API key. <a href="%s" target="_blank">How to get?</a>', 'ciloe-toolkit' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ), array(
									'a' => array(
										'href'   => array(),
										'target' => array()
									)
								) ),
							),
							array(
								'id'         => 'load_gmap_js_target',
								'type'       => 'select',
								'title'      => esc_html__( 'Load GMap JS On', 'ciloe-toolkit' ),
								'options'    => array(
									'all_pages'      => esc_html__( 'All Pages', 'ciloe-toolkit' ),
									'selected_pages' => esc_html__( 'Selected Pages', 'ciloe-toolkit' ),
									'disabled'       => esc_html__( 'Don\'t Load Gmap JS', 'ciloe-toolkit' ),
								),
								'default'    => 'all_pages',
								'dependency' => array( 'gmap_api_key', '!=', '' ),
							),
							array(
								'id'         => 'load_gmap_js_on',
								'type'       => 'select',
								'title'      => esc_html__( 'Select Pages To Load GMap JS', 'ciloe-toolkit' ),
								'options'    => 'pages',
								'query_args' => array(
									'post_type'      => 'page',
									'orderby'        => 'post_date',
									'order'          => 'ASC',
									'posts_per_page' => - 1
								),
								'attributes' => array(
									'multiple' => 'multiple',
									'style'    => 'width: 500px; height: 125px;',
								),
								'class'      => 'chosen',
								'desc'       => esc_html__( 'Load Google Map JS on selected pages', 'ciloe-toolkit' ),
								'dependency' => array(
									'gmap_api_key|load_gmap_js_target',
									'!=|==',
									'|selected_pages'
								),
							),
							array(
								'id'      => 'ciloe_enable_lazy',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Lazy Load Images', 'ciloe-toolkit' ),
								'default' => true,
								'desc'    => esc_html__( 'Enables lazy load to reduce page requests.', 'ciloe-toolkit' ),
							),
							array(
								'id'      => 'enable_smooth_scroll',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Smooth Scroll', 'ciloe-toolkit' ),
								'default' => false,
								'desc'    => esc_html__( 'Turn on if you want to smooth out when scrolling', 'ciloe-toolkit' ),
							),
							array(
								'id'      => 'main_menu_res_break_point',
								'type'    => 'text',
								'title'   => esc_html__( 'Main Menu Responsive Break Point', 'ciloe-toolkit' ),
								'default' => 1199,
								'desc'    => esc_html__( 'Break point of the main menu when resizing the browser. Ex: 991, 1199 ...', 'ciloe-toolkit' )
							),
						),
					),
					array(
						'name'   => 'theme_js_css',
						'title'  => 'Customs JS',
						'fields' => array(
							array(
								'id'         => 'ciloe_custom_js',
								'type'       => 'ace_editor',
								'title'      => esc_html__( 'Custom Js', 'ciloe-toolkit' ),
								'attributes' => array(
									'data-theme' => 'twilight',  // the theme for ACE Editor
									'data-mode'  => 'javascript',     // the language for ACE Editor
								),
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'newsletter',
				'title'  => esc_html__( 'Newsletter Popup', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-envelope-o',
				'fields' => array(
					array(
						'id'      => 'enable_newsletter',
						'type'    => 'switcher',
						'title'   => esc_html__( 'Enable Newsletter', 'ciloe-toolkit' ),
						'default' => true,
					),
					array(
						'id'         => 'ciloe_newsletter_popup',
						'type'       => 'select',
						'title'      => esc_html__( 'Select Newsletter Popup', 'ciloe-toolkit' ),
						'options'    => 'posts',
						'value'=>'The title1 bar',
						'dependency' => array( 'enable_newsletter', '==', true ),
						'query_args' => array(
							'post_type'      => 'newsletter',
							'orderby'        => 'post_date',
							'order'          => 'ASC',
							'posts_per_page' => - 1
						),

					),
					array(
						'id'      => 'disable_on_mobile',
						'type'    => 'switcher',
						'title'   => esc_html__( 'On Mobile', 'ciloe-toolkit' ),
						'default' => false,
						'dependency' => array( 'enable_newsletter', '==', true ), 
					),

				),
			);
			$options[] = array( 
				'name'     => 'header',
				'title'    => esc_html__( 'Header Settings', 'ciloe-toolkit' ),
				'icon'     => 'fa fa-folder-open-o',
				'sections' => array(
					array(
						'name'   => 'header_general_settings',
						'title'  => esc_html__( 'General Header Settings', 'ciloe-toolkit' ),
						'fields' => array(
							array(
								'id'      => 'enable_sticky_menu',
								'type'    => 'select',
								'title'   => esc_html__( 'Sticky Header', 'ciloe-toolkit' ),
								'options' => array(
									'none'  => esc_html__( 'Disable', 'ciloe-toolkit' ),
									'smart' => esc_html__( 'Sticky Header', 'ciloe-toolkit' ),
								),
								'default' => 'smart',
							),
							array(
								'id'      => 'enable_topbar',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Topbar', 'ciloe-toolkit' ),
								'default' => false,
							),
							array(
								'id'         => 'topbar-text',
								'type'       => 'text',
								'title'      => esc_html__( 'Text Topbar', 'ciloe-toolkit' ),
								'dependency' => array( 'enable_topbar', '==', true ),
							),
							array(
								'id'      => 'ciloe_used_header',
								'type'    => 'select_preview',
								'title'   => esc_html__( 'Header Layout', 'ciloe-toolkit' ),
								'desc'    => esc_html__( 'Select a header layout', 'ciloe-toolkit' ),
								'options' => $this->header_options,
								'default' => 'logo_l_menu_c_icons_r_bg_trans',
							),
							array(
								'id'         => 'header_shadow', 
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Shadow', 'ciloe-toolkit' ),
								'default'    => false,
							),
							array(
								'id'      => 'header_text_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Header Text Color', 'ciloe-toolkit' ),
								'default' => '#000',
								'rgba'    => true,
							),
							array(
								'id'      => 'header_bg_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Header Background Color', 'ciloe-toolkit' ),
								'default' => 'rgba(0,0,0,0)',
								'rgba'    => true,
							),
							array(
								'id'      => 'header_position',
								'type'    => 'select',
								'title'   => esc_html__( 'Header Type', 'ciloe-toolkit' ),
								'options' => array(
									'relative' => esc_html__( 'Header No Transparent', 'ciloe-toolkit' ),
									'absolute' => esc_html__( 'Header Transparent', 'ciloe-toolkit' ),
								),
								'default' => 'relative',
							),
						),
					),
					array(
						'name'   => 'page_banner_settings',
						'title'  => esc_html__( 'Page Banner Settings', 'ciloe-toolkit' ),
						'fields' => array(
							array(
								'id'      => 'page_banner_type',
								'type'    => 'select',
								'title'   => esc_html__( 'Banner Type', 'ciloe-toolkit' ),
								'options' => array(
									'has_background' => esc_html__( 'Has Background', 'ciloe-toolkit' ),
									'no_background'  => esc_html__( 'No Background ', 'ciloe-toolkit' ),
								),
								'default' => 'has_background'
							),
							array(
								'id'         => 'page_banner_image',
								'type'       => 'background',
								'title'      => esc_html__( 'Banner Image', 'ciloe-toolkit' ),
								'add_title'  => esc_html__( 'Upload', 'ciloe-toolkit' ),
								'dependency' => array( 'page_banner_type', '==', 'has_background' ),
							),
							array(
								'id'         => 'colortext_banner_page',
								'type'       => 'color_picker',
								'title'      => esc_html__( 'Banner Text Color', 'ciloe-toolkit' ),
								'default'    => '#ffffff',
								'rgba'       => true,
								'dependency' => array( 'page_banner_type', '==', 'has_background' ),
							),
							array(
								'id'         => 'page_banner_full_width',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Banner Full Width', 'ciloe-toolkit' ),
								'default'    => false,
								'dependency' => array( 'page_banner_type', '==', 'has_background' ),
							),
							array(
								'id'      => 'page_height_banner',
								'type'    => 'number',
								'title'   => esc_html__( 'Banner Height', 'ciloe-toolkit' ),
								'default' => '280'
							),
							array(
								'id'      => 'page_margin_top',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Top', 'ciloe-toolkit' ),
								'default' => 0
							),
							array(
								'id'      => 'page_margin_bottom',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Bottom', 'ciloe-toolkit' ),
								'default' => 0,
							),
						
						)
					),
					array(
						'name'   => 'header_mobile',
						'title'  => esc_html__( 'Header Mobile', 'ciloe-toolkit' ),
						'fields' => array(
							array(
								'id'      => 'enable_header_mobile',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Header Mobile', 'ciloe-toolkit' ),
								'default' => false,
							),
							array(
								'id'         => 'ciloe_mobile_logo',
								'type'       => 'image',
								'title'      => esc_html__( 'Mobile Logo', 'ciloe-toolkit' ),
								'add_title'  => esc_html__( 'Add Mobile Logo', 'ciloe-toolkit' ),
								'desc'       => esc_html__( 'Add custom logo for mobile. If no mobile logo is selected, the default logo will be used or custom logo if placed in the page', 'ciloe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_header_mini_cart_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Mini Cart Icon', 'ciloe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide header mini cart icon on mobile', 'ciloe-toolkit' ),
								'default'    => true,
								'on'         => esc_html__( 'On', 'ciloe-toolkit' ),
								'off'        => esc_html__( 'Off', 'ciloe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_header_product_search_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Products Search Icon', 'ciloe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide header product search icon on mobile', 'ciloe-toolkit' ),
								'default'    => true,
								'on'         => esc_html__( 'On', 'ciloe-toolkit' ),
								'off'        => esc_html__( 'Off', 'ciloe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_wishlist_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Wish List Icon', 'ciloe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide wish list icon on siding menu mobile', 'ciloe-toolkit' ),
								'default'    => false,
								'on'         => esc_html__( 'Show', 'ciloe-toolkit' ),
								'off'        => esc_html__( 'Hide', 'ciloe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
						),
					),
				)
			);
			$options[] = array(
				'name'   => 'footer',
				'title'  => esc_html__( 'Footer Settings', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-folder-open-o',
				'fields' => array(
					array(
						'id'         => 'ciloe_footer_options',
						'type'       => 'select',
						'title'      => esc_html__( 'Select Footer Builder', 'ciloe-toolkit' ),
						'options'    => 'posts',
						'query_args' => array(
							'post_type'      => 'footer',
							'orderby'        => 'post_date',
							'order'          => 'ASC',
							'posts_per_page' => - 1
						),
					),
				),
			);
			
			$options[] = array(
				'name'     => 'blog',
				'title'    => esc_html__( 'Blog Settings', 'ciloe-toolkit' ),
				'icon'     => 'fa fa-rss',
				'sections' => array(
					array(
						'name'   => 'shop_page',
						'title'  => esc_html__( 'Blog Page', 'ciloe-toolkit' ),
						'fields' => array(
							array(
								'type'    => 'subheading',
								'content' => esc_html__( 'General Settings', 'ciloe-toolkit' ),
							),
							
							array(
								'id'         => 'blog-style',
								'type'       => 'image_select',
								'title'      => esc_html__( 'Style', 'ciloe-toolkit' ),
								'radio'      => true,
								'options'    => array(
									'standard'   => CS_URI . '/assets/images/layout/standard.jpg',
									'classic'    => CS_URI . '/assets/images/layout/standard.jpg',
									'grid'       => CS_URI . '/assets/images/layout/grid.png',
								),
								'default'    => 'classic',
								'attributes' => array(
									'data-depend-id' => 'blog-style',
								),
							),
							array(
								'type'       => 'subheading',
								'content'    => esc_html__( 'Grid Column Settings', 'ciloe-toolkit' ),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ciloe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ciloe-toolkit' ),
								'id'         => 'ciloe_blog_bg_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							
							),
							array(
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ciloe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ciloe-toolkit' ),
								'id'         => 'ciloe_blog_lg_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'ciloe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ciloe-toolkit' ),
								'id'         => 'ciloe_blog_md_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'ciloe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ciloe-toolkit' ),
								'id'         => 'ciloe_blog_sm_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'ciloe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ciloe-toolkit' ),
								'id'         => 'ciloe_blog_xs_items',
								'type'       => 'select',
								'default'    => '6',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'ciloe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ciloe-toolkit' ),
								'id'         => 'ciloe_blog_ts_items',
								'type'       => 'select',
								'default'    => '12',
								'options'    => array(
									'12' => '1 items',
									'6'  => '2 items',
									'4'  => '3 items',
									'3'  => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'id'      => 'ciloe_blog_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Blog Sidebar Position', 'ciloe-toolkit' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'ciloe-toolkit' ),
								'options' => array(
									'left'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
									'right' => CILOE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
									'full'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
								),
								'default' => 'full',
							),
							array(
								'id'         => 'blog_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Blog Sidebar', 'ciloe-toolkit' ),
								'options'    => $this->sidebars,
								'default'    => 'sidebar-1',
								'dependency' => array( 'sidebar_shop_layout_full', '==', false ),
							),
							array(
								'id'      => 'enable_breadcrumb',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Breadcrumb', 'ciloe-toolkit' ),
								'default' => false,
							),
							array(
								'id'      => 'enable-sharing',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Sharing', 'ciloe-toolkit' ),
								'default' => false,
							),
							array(
								'id'         => 'social-sharing',
								'type'       => 'select',
								'title'      => esc_html__( 'Social Sharing', 'ciloe-toolkit' ),
								'options'    => array(
									'facebook'   => esc_html__( 'Facebook', 'ciloe-toolkit' ),
									'twitter'    => esc_html__( 'Twitter', 'ciloe-toolkit' ),
									'googleplus' => esc_html__( 'Google Plus', 'ciloe-toolkit' ),
									'pinterest'  => esc_html__( 'Pinterest', 'ciloe-toolkit' ),
									'tumblr'     => esc_html__( 'Tumblr', 'ciloe-toolkit' ),
								),
								'attributes' => array(
									'multiple' => 'multiple',
									'style'    => 'width: 500px; height: 125px;',
								),
								'class'      => 'chosen',
								'default'    => array( 'facebook', 'twitter', 'googleplus', 'pinterest', 'tumblr' ),
								'dependency' => array( 'enable-sharing', '==', true ),
							),
						),
					),
					array(
						'name'   => 'single_post',
						'title'  => 'Single Post',
						'fields' => array(
							array(
								'title'   => esc_html__( 'Featured Image Size', 'ciloe-toolkit' ),
								'id'      => 'featured_img_size',
								'type'    => 'text',
								'default' => '1400x817',
								'desc'    => esc_html__( 'Featured image size option. Format {width}x{height}, width and height in the form of positive integer. Default: 1400x817', 'ciloe-toolkit' ),
							),
							array(
								'id'      => 'post-meta-cats',
								'type'    => 'select',
								'options' => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'title'   => esc_html__( 'Show Categories In Single', 'ciloe-toolkit' ),
								'default' => 'yes',
							),
							array(
								'id'      => 'post-meta-tags',
								'type'    => 'select',
								'options' => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'title'   => esc_html__( 'Show Tags In Single', 'ciloe-toolkit' ),
								'default' => 'no',
							),
							array(
								'id'      => 'show_post_author',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Show Post Author Bio', 'ciloe-toolkit' ),
								'default' => true,
							),
							array(
								'id'         => 'show_post_author_socials',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Author Socials Networks', 'ciloe-toolkit' ),
								'default'    => false,
								'dependency' => array( 'show_post_author', '==', true ),
							),
							array(
								'id'      => 'sidebar_single_post_position',
								'type'    => 'image_select',
								'title'   => 'Single Post Sidebar Position',
								'desc'    => 'Select sidebar position on Single Post.',
								'options' => array(
									'left'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
									'right' => CILOE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
									'full'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
								),
								'default' => 'left',
							),
							array(
								'id'      => 'show_single_related_posts',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Show Related Posts', 'ciloe-toolkit' ),
								'default' => true,
							),
							array(
								'id'         => 'single_post_sidebar',
								'type'       => 'select',
								'title'      => 'Single Post Sidebar',
								'options'    => $this->sidebars,
								'default'    => 'blue',
								'dependency' => array( 'sidebar_single_post_position_full', '==', false ),
							),
						),
					),
				),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$options[] = array(
					'name'     => 'wooCommerce',
					'title'    => esc_html__( 'WooCommerce', 'ciloe-toolkit' ),
					'icon'     => 'fa fa-shopping-cart',
					'sections' => array(
						array(
							'name'   => 'shop_product',
							'title'  => esc_html__( 'Shop Page', 'ciloe-toolkit' ),
							'fields' => array(
								array(
									'type'    => 'subheading',
									'content' => esc_html__( 'Shop Settings', 'ciloe-toolkit' ),
								),
								array(
									'id'      => 'shop_banner_type',
									'type'    => 'select',
									'title'   => esc_html__( 'Shop Banner Type', 'ciloe-toolkit' ),
									'options' => array(
										'has_background' => esc_html__( 'Has Background', 'ciloe-toolkit' ),
										'no_background'  => esc_html__( 'No Background ', 'ciloe-toolkit' ),
									),
									'default' => 'no_background',
									'desc'    => esc_html__( 'Banner for Shop page, archive, search results page...', 'ciloe-toolkit' ),
								),
								array(
									'id'         => 'shop_banner_image',
									'type'       => 'background',
									'title'      => esc_html__( 'Banner Image', 'ciloe-toolkit' ),
									'add_title'  => esc_html__( 'Upload', 'ciloe-toolkit' ),
									'dependency' => array( 'shop_banner_type', '==', 'has_background' ),
								),
								array(
									'id'         => 'colortext_shop_page',
									'type'       => 'color_picker',
									'title'      => esc_html__( 'Banner Text Color', 'ciloe-toolkit' ),
									'default'    => '#ffffff',
									'rgba'       => true,
									'dependency' => array( 'shop_banner_type', '==', 'has_background' ),
								),
								array(
									'id'      => 'shop_banner_height',
									'type'    => 'number',
									'title'   => esc_html__( 'Banner Height', 'ciloe-toolkit' ),
									'default' => 280,
								),
								array(
									'id'      => 'shop_margin_top',
									'type'    => 'number',
									'title'   => esc_html__( 'Margin Top', 'ciloe-toolkit' ),
									'default' => 0,
								),
								array(
									'id'      => 'shop_margin_bottom',
									'type'    => 'number',
									'title'   => esc_html__( 'Margin Bottom', 'ciloe-toolkit' ),
									'default' => 0,
								),
								array(
									'id'      => 'shop_panel',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Top Panel', 'ciloe-toolkit' ),
									'default' => false,
								),
								array(
									'id'      => 'enable_shop_mobile',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Mobile Layout', 'ciloe-toolkit' ),
									'default' => true,
									'desc'    => esc_html__( 'Use the dedicated mobile interface on a real device instead of responsive. Note, this option is not available for desktop browsing and uses resize the screen.', 'ciloe-toolkit' ),
								),
								array(
									'id'             => 'panel-categories',
									'type'           => 'select',
									'title'          => esc_html__( 'Select Categories', 'ciloe-toolkit' ),
									'options'        => 'categories',
									'query_args'     => array(
										'type'           => 'product',
										'taxonomy'       => 'product_cat',
										'orderby'        => 'post_date',
										'order'          => 'DESC',
										'posts_per_page' => - 1
									),
									'attributes'     => array(
										'multiple' => 'multiple',
										'style'    => 'width: 500px; height: 125px;',
									),
									'class'          => 'chosen',
									'default_option' => esc_html__( 'Select Categories', 'ciloe-toolkit' ),
									'desc'           => esc_html__( 'Product categories displayed on the shop page', 'ciloe-toolkit' ),
									'dependency'     => array( 'shop_panel', '==', true ),
								),
								array(
									'id'      => 'enable_instant_product_search',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Instant Products Search', 'ciloe-toolkit' ),
									'default' => false,
									'desc'    => esc_html__( 'Enabling "Instant Product Search" to displays product search results at the same time you type', 'ciloe-toolkit' ),
								),
								array(
									'id'      => 'sidebar_shop_page_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Shop Page Sidebar Position', 'ciloe-toolkit' ),
									'desc'    => esc_html__( 'Select sidebar position on Shop Page.', 'ciloe-toolkit' ),
									'options' => array(
										'left'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
										'right' => CILOE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
										'full'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
									),
									'default' => 'full',
								),
								array(
									'id'         => 'shop_page_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Shop Sidebar', 'ciloe-toolkit' ),
									'options'    => $this->sidebars,
									'dependency' => array( 'sidebar_shop_page_position_full', '==', false ),
								),
								array(
									'id'       => 'shop_display_mode',
									'type'     => 'image_select',
									'compiler' => true,
									'title'    => esc_html__( 'Shop Layout', 'ciloe' ),
									'subtitle' => esc_html__( 'Select default layout for shop, product category archive.', 'ciloe' ),
									'options'  => array(
										'grid'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/grid-display.png',
										'list' => CILOE_TOOLKIT_URL . '/includes/core/assets/images/list-display.png',
									),
									'default'  => 'grid',
								),
								array(
									'id'      => 'product_per_page',
									'type'    => 'number',
									'title'   => esc_html__( 'Products perpage', 'ciloe-toolkit' ),
									'desc'    => 'Number of products on shop page.',
									'default' => '12',
								),
								array(
									'id'      => 'ciloe_enable_loadmore',
									'type'    => 'select',
									'options' => array(
										'default'  => esc_html__( 'Default', 'ciloe-toolkit' ),
										'loadmore' => esc_html__( 'Load More', 'ciloe-toolkit' ),
										'infinity' => esc_html__( 'Infinity', 'ciloe-toolkit' ),
									
									),
									'title'   => esc_html__( 'Choose Pagination', 'ciloe-toolkit' ),
									'desc'    => esc_html__( 'Choose pagination type for shop page.', 'ciloe-toolkit' ),
									'default' => 'default',
								),
								array(
									'id'      => 'ciloe_shop_product_style',
									'type'    => 'select_preview',
									'title'   => esc_html__( 'Product Shop Layout', 'ciloe-toolkit' ),
									'desc'    => esc_html__( 'Select a Product layout in shop page', 'ciloe-toolkit' ),
									'options' => $this->product_options,
									'default' => '1',
								),
								array(
									'id'         => 'products_loop_attributes_display',
									'type'       => 'select',
									'title'      => esc_html__( 'Products Attribute Display On Loop', 'ciloe-toolkit' ),
									'options'    => $this->ciloe_attributes_options(),
									'attributes' => array(
										'multiple' => 'multiple',
										'style'    => 'width: 500px; height: 125px;',
									),
									'class'      => 'chosen',
									'default'    => array( 'pa_color' )
								),
								array(
									'type'    => 'subheading',
									'content' => 'Grid Column Settings',
								),
								array(
									'id'      => 'enable_products_sizes',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Show Products Size', 'ciloe-toolkit' ),
									'default' => true,
								),
								array(
									'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_bg_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_md_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_sm_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_xs_items',
									'type'       => 'select',
									'default'    => '6',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_ts_items',
									'type'       => 'select',
									'default'    => '12',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
							),
						),
						array(
							'name'   => 'single_product',
							'title'  => esc_html__( 'Single Product', 'ciloe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'sidebar_product_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Single Product Sidebar Position', 'ciloe-toolkit' ),
									'desc'    => esc_html__( 'Select sidebar position on single product page.', 'ciloe-toolkit' ),
									'options' => array(
										'left'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
										'right' => CILOE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
										'full'  => CILOE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
									),
									'default' => 'left',
								),
								array(
									'id'      => 'enable_single_product_mobile',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Product Mobile Layout', 'ciloe-toolkit' ),
									'default' => true,
									'desc'    => esc_html__( 'Use the dedicated mobile interface on a real device instead of responsive. Note, this option is not available for desktop browsing and uses resize the screen.', 'ciloe-toolkit' ),
								),
								array(
									'id'      => 'enable_info_product_single',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Sticky Info Product Single', 'ciloe-toolkit' ),
									'default' => true,
									'desc'    => esc_html__( 'On or Off Sticky Info Product Single.', 'ciloe-toolkit' ),
								),
								array(
									'id'         => 'single_product_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Single Product Sidebar', 'ciloe-toolkit' ),
									'options'    => $this->sidebars,
									'default'    => 'blue',
									'dependency' => array( 'sidebar_product_position_full', '==', false ),
								),
								array(
									'id'      => 'ciloe_woo_single_product_layout',
									'type'    => 'select',
									'title'   => esc_html__( 'Choose Single Style', 'ciloe-toolkit' ),
									'desc'    => esc_html__( 'Choose Single Product Style', 'ciloe-toolkit' ),
									'options' => array(
										'default'           => esc_html__( 'Default', 'ciloe-toolkit' ),
										'vertical_thumnail' => esc_html__( 'Thumbnail Vertical', 'ciloe-toolkit' ),
										'sticky_detail'     => esc_html__( 'Sticky Detail', 'ciloe-toolkit' ),
										'gallery_detail'    => esc_html__( 'Gallery Detail', 'ciloe-toolkit' ),
										'big_images'        => esc_html__( 'Big Images', 'ciloe-toolkit' ),
									),
									'default' => 'vertical_thumnail',
								),
								array(
									'id'         => 'single_product_img_bg_color',
									'type'       => 'color_picker',
									'title'      => esc_html__( 'Image Background Color', 'ciloe-toolkit' ),
									'default'    => 'rgba(0,0,0,0)',
									'rgba'       => true,
									'dependency' => array(
										'ciloe_woo_single_product_layout',
										'==',
										'big_images'
									),
									'desc'       => esc_html__( 'For "Big Images" style only. Default: transparent', 'ciloe-toolkit' ),
								),
								array(
									'id'         => 'single_product_sum_border',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Summary Border', 'ciloe-toolkit' ),
									'default'    => false,
									'dependency' => array(
										'ciloe_woo_single_product_layout',
										'any',
										'default,vertical_thumnail,sticky_detail'
									),
								),
								array(
									'id'         => 'single_product_title_price_stars_outside_sum',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Title, Price And Stars Outside Sumary', 'ciloe-toolkit' ),
									'default'    => false,
									'dependency' => array(
										'ciloe_woo_single_product_layout',
										'any',
										'default,vertical_thumnail,sticky_detail'
									),
								),
								array(
									'id'      => 'enable_single_product_sharing',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Product Sharing', 'ciloe-toolkit' ),
									'default' => false,
								),
								array(
									'id'         => 'enable_single_product_sharing_fb',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Facebook Sharing', 'ciloe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
								array(
									'id'         => 'enable_single_product_sharing_tw',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Twitter Sharing', 'ciloe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
								array(
									'id'         => 'enable_single_product_sharing_pinterest',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Pinterest Sharing', 'ciloe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
								array(
									'id'         => 'enable_single_product_sharing_gplus',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Google Plus Sharing', 'ciloe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
							),
						),
						array(
							'name'   => 'cross_sell',
							'title'  => esc_html__( 'Cross Sell', 'ciloe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'enable_cross_sell',
									'type'    => 'select',
									'options' => array(
										'yes' => esc_html__( 'Yes', 'ciloe-toolkit' ),
										'no'  => esc_html__( 'No', 'ciloe-toolkit' ),
									),
									'title'   => esc_html__( 'Enable Cross Sell', 'ciloe-toolkit' ),
									'default' => 'yes',
								),
								array(
									'title'      => esc_html__( 'Cross sell title', 'ciloe-toolkit' ),
									'id'         => 'ciloe_cross_sells_products_title',
									'type'       => 'text',
									'default'    => esc_html__( 'You may be interested in...', 'ciloe-toolkit' ),
									'desc'       => esc_html__( 'Cross sell title', 'ciloe-toolkit' ),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								
								array(
									'title'      => esc_html__( 'Cross sell items per row on Desktop', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_crosssell_ls_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on Desktop', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_crosssell_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on landscape tablet', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_crosssell_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on portrait tablet', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_crosssell_sm_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on Mobile', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_crosssell_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on Mobile', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_crosssell_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
							),
						),
						array(
							'name'   => 'related_product',
							'title'  => 'Related Products',
							'fields' => array(
								array(
									'id'      => 'enable_relate_products',
									'type'    => 'select',
									'options' => array(
										'yes' => esc_html__( 'Yes', 'ciloe-toolkit' ),
										'no'  => esc_html__( 'No', 'ciloe-toolkit' ),
									),
									'title'   => esc_html__( 'Enable Related Products', 'ciloe-toolkit' ),
									'default' => 'yes',
								),
								array(
									'title'      => esc_html__( 'Related products title', 'ciloe-toolkit' ),
									'id'         => 'ciloe_related_products_title',
									'type'       => 'text',
									'default'    => 'Related Products',
									'desc'       => esc_html__( 'Related products title', 'ciloe-toolkit' ),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'    => esc_html__( 'Limit Number Of Products', 'ciloe' ),
									'id'       => 'ciloe_related_products_perpage',
									'type'     => 'text',
									'default'  => '8',
									'validate' => 'numeric',
									'subtitle' => esc_html__( 'Number of products on shop page', 'ciloe' ),
								),	
								array(
									'title'      => esc_html__( 'Related products items per row on Desktop', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_related_ls_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Desktop', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_related_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on landscape tablet', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_related_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related product items per row on portrait tablet', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_related_sm_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Mobile', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_related_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Mobile', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_related_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
							),
						),
						array(
							'name'   => 'upsells_product',
							'title'  => esc_html__( 'Up sells Products', 'ciloe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'enable_up_sell',
									'type'    => 'select',
									'options' => array(
										'yes' => esc_html__( 'Yes', 'ciloe-toolkit' ),
										'no'  => esc_html__( 'No', 'ciloe-toolkit' ),
									),
									'title'   => esc_html__( 'Enable Up Sell', 'ciloe-toolkit' ),
									'default' => 'yes',
								),
								array(
									'title'      => esc_html__( 'Up sells title', 'ciloe-toolkit' ),
									'id'         => 'ciloe_upsell_products_title',
									'type'       => 'text',
									'default'    => esc_html__( 'You may also like...', 'ciloe-toolkit' ),
									'desc'       => esc_html__( 'Up sells products title', 'ciloe-toolkit' ),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								
								array(
									'title'      => esc_html__( 'Up sells items per row on Desktop', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_upsell_ls_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on Desktop', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_upsell_lg_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on landscape tablet', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_upsell_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on portrait tablet', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_upsell_sm_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on Mobile', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_upsell_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on Mobile', 'ciloe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'ciloe-toolkit' ),
									'id'         => 'ciloe_woo_upsell_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
							),
						),
					),
				);
			}
			
			$options[] = array(
				'name'   => 'social_settings',
				'title'  => esc_html__( 'Social Settings', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-users',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Socials Networks', 'ciloe-toolkit' ),
					),
					array(
						'id'              => 'user_all_social',
						'type'            => 'group',
						'title'           => esc_html__( 'Socials', 'ciloe-toolkit' ),
						'button_title'    => esc_html__( 'Add New Social', 'ciloe-toolkit' ),
						'accordion_title' => esc_html__( 'Social Settings', 'ciloe-toolkit' ),
						'fields'          => array(
							array(
								'id'      => 'title_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Social Title', 'ciloe-toolkit' ),
								'default' => esc_html__( 'Facebook', 'ciloe-toolkit' ),
							),
							array(
								'id'      => 'link_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Social Link', 'ciloe-toolkit' ),
								'default' => 'https://facebook.com',
							),
							array(
								'id'      => 'icon_social',
								'type'    => 'icon',
								'title'   => esc_html__( 'Social Icon', 'ciloe-toolkit' ),
								'default' => 'fa fa-facebook',
							),
						),
					),
				),
			);
			
			$options[] = array(
				'name'   => 'typography',
				'title'  => esc_html__( 'Typography Options', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					array(
						'id'      => 'enable_google_font',
						'type'    => 'switcher',
						'title'   => esc_html__( 'Enable Google Font', 'ciloe-toolkit' ),
						'default' => false,
						'on'      => esc_html__( 'Enable', 'ciloe-toolkit' ),
						'off'     => esc_html__( 'Disable', 'ciloe-toolkit' )
					),
					array(
						'id'         => 'typography_themes',
						'type'       => 'typography',
						'title'      => esc_html__( 'Body Typography', 'ciloe-toolkit' ),
						'default'    => array(
							'family'  => 'Open Sans',
							'variant' => '400',
							'font'    => 'google',
						),
						'dependency' => array( 'enable_google_font', '==', true )
					),
					array(
						'id'         => 'fontsize-body',
						'type'       => 'number',
						'title'      => esc_html__( 'Body Font Size', 'ciloe-toolkit' ),
						'default'    => '15',
						'after'      => ' <i class="cs-text-muted">px</i>',
						'dependency' => array( 'enable_google_font', '==', true )
					)
				),
			);
			
			$options[] = array(
				'name'   => 'backup_option',
				'title'  => esc_html__( 'Backup Options', 'ciloe-toolkit' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					array(
						'type'  => 'backup',
						'title' => esc_html__( 'Backup Field', 'ciloe-toolkit' ),
					),
				),
			);
			
			
			CSFramework::instance( $settings, $options );
		}
	}
	
	new Ciloe_ThemeOption();
}
