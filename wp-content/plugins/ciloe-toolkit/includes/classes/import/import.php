<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}
if ( ! class_exists( 'CILOE_IMPORTER' ) ) {
	class CILOE_IMPORTER {
		public $ajax_optionid;
		public $ajax_menu_import;
		public $ajax_attachments      = false;
		public $ajax_options_name     = array();
		public $ajax_main_content;
		public $ajax_options_posttype = array();
		public $data_demos            = array();
		public $content_path;
		public $widget_path;
		public $revslider_path;
		public $woo_pages;
		public $woo_catalog;
		public $woo_single;
		public $woo_thumbnail;
		public $item_import;
		
		public function __construct() {
			$this->define_constants();
			$registed_menu = array(
				'primary'     => esc_html__( 'Primary Menu', 'ciloe-toolkit' ),
				'double-menu' => esc_html__( 'Double Menu', 'ciloe-toolkit' ),
			);
			$menu_location = array(
				'primary'     => 'Primary Menu',
				'double-menu' => 'Double Menu',
			);
			$data_filter   = array(
				'data_demos'    => array(
					array(
						'name'           => esc_html__( 'Home Classic', 'ciloe-toolkit' ),
						'slug'           => 'home-classic',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Classic',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-classic.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-classic/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie',
						'revslider_path' => CILOE_IMPORTER_DIR . '/data/revsliders/',
					),
					array(
						'name'           => esc_html__( 'Home Clean', 'ciloe-toolkit' ),
						'slug'           => 'home-clean',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Clean',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-clean.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-clean/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie',
						'revslider_path' => CILOE_IMPORTER_DIR . '/data/revsliders/',
					),
					array(
						'name'           => esc_html__( 'Home Minimal', 'ciloe-toolkit' ),
						'slug'           => 'home-minimal',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Minimal',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-minimal.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-minimal/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie',
						'revslider_path' => CILOE_IMPORTER_DIR . '/data/revsliders/',
					),
					array(
						'name'           => esc_html__( 'Home Fullwidth', 'ciloe-toolkit' ),
						'slug'           => 'home-fullwidth',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Fullwidth',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-fullwidth.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-fullwidth/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie'
					),
					array(
						'name'           => esc_html__( 'Home Categories', 'ciloe-toolkit' ),
						'slug'           => 'home-categories',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Categories',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-categories.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-categories/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie',
						'revslider_path' => CILOE_IMPORTER_DIR . '/data/revsliders/',
					),
					array(
						'name'           => esc_html__( 'Home Pinmapper', 'ciloe-toolkit' ),
						'slug'           => 'home-pinmapper',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Pinmapper',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-pinmapper.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-pinmapper/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie',
						'revslider_path' => CILOE_IMPORTER_DIR . '/data/revsliders/',
					),
					array(
						'name'           => esc_html__( 'Home Modern', 'ciloe-toolkit' ),
						'slug'           => 'home-modern',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Modern',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-modern.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-modern/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie',
						'revslider_path' => CILOE_IMPORTER_DIR . '/data/revsliders/',
					),
					array(
						'name'           => esc_html__( 'Home Instagram', 'ciloe-toolkit' ),
						'slug'           => 'home-instagram',
						'menus'          => $registed_menu,
						'homepage'       => 'Home Instagram',
						'blogpage'       => 'Blog',
						'preview'        => CILOE_IMPORTER_URI . '/data/previews/home-instagram.jpg',
						'demo_link'      => 'https://ciloe.famithemes.com/home-instagram/',
						'menu_locations' => $menu_location,
						'theme_option'   => CILOE_IMPORTER_DIR . '/data/theme-options.txt',
						'content_path'   => CILOE_IMPORTER_DIR . '/data/content.xml',
						'widget_path'    => CILOE_IMPORTER_DIR . '/data/widgets.wie'
					),
				),
				'item_import'   => array(
					'fami_import_full_content'    => 'Import Full Content',
					'fami_import_page_content'    => 'Import Page',
					'fami_import_theme_options'   => 'Import Theme Options',
					'fami_import_post_content'    => 'Import Post',
					'fami_import_product_content' => 'Import Product',
					'fami_import_menu'            => 'Import Menu',
					'fami_import_widget'          => 'Import Widget',
					'fami_import_revslider'       => 'Import Revslider',
					'fami_import_attachments'     => 'Import Attachments',
				),
				'woo_pages'     => array(
					'woocommerce_shop_page_id'      => 'Shop',
					'woocommerce_cart_page_id'      => 'Cart',
					'woocommerce_checkout_page_id'  => 'Checkout',
					'woocommerce_myaccount_page_id' => 'My Account',
				),
				'woo_catalog'   => array(
					'width'  => '300',   // px
					'height' => '300',   // px
					'crop'   => 1        // true
				),
				'woo_single'    => array(
					'width'  => '600',   // px
					'height' => '600',   // px
					'crop'   => 1        // true
				),
				'woo_thumbnail' => array(
					'width'  => '180',   // px
					'height' => '180',   // px
					'crop'   => 1        // false
				),
			);
			$import_data   = apply_filters( 'ciloe_data_import', $data_filter );
			// SET DATA DEMOS
			$this->data_demos    = isset( $import_data['data_demos'] ) ? $import_data['data_demos'] : array();
			$this->item_import   = isset( $import_data['item_import'] ) ? $import_data['item_import'] : array();
			$this->woo_pages     = isset( $import_data['woo_pages'] ) ? $import_data['woo_pages'] : array();
			$this->woo_catalog   = isset( $import_data['woo_catalog'] ) ? $import_data['woo_catalog'] : array();
			$this->woo_single    = isset( $import_data['woo_single'] ) ? $import_data['woo_single'] : array();
			$this->woo_thumbnail = isset( $import_data['woo_thumbnail'] ) ? $import_data['woo_thumbnail'] : array();
			// JS and css
			add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
			add_action( 'importer_page_content', array( $this, 'importer_page_content' ) );
			
			$enable_import = true;
			if ( defined( 'FAMI_DISABLE_IMPORT_DEMO' ) ) {
				$enable_import = FAMI_DISABLE_IMPORT_DEMO === true;
			}
			
			if ( $enable_import ) {
				/* Register ajax action */
				add_action( 'wp_ajax_fami_import_menu', array( $this, 'import_menu' ) );
				add_action( 'wp_ajax_fami_import_widget', array( $this, 'import_widget' ) );
				add_action( 'wp_ajax_fami_import_config', array( $this, 'import_config' ) );
				add_action( 'wp_ajax_fami_import_revslider', array( $this, 'import_revslider' ) );
				add_action( 'wp_ajax_fami_import_full_content', array( $this, 'import_full_content' ) );
				add_action( 'wp_ajax_fami_import_post_content', array( $this, 'import_post_content' ) );
				add_action( 'wp_ajax_fami_import_page_content', array( $this, 'import_page_content' ) );
				add_action( 'wp_ajax_fami_import_product_content', array( $this, 'import_product_content' ) );
				add_action( 'wp_ajax_fami_import_single_page_content', array( $this, 'import_single_page_content' ) );
				add_action( 'wp_ajax_fami_import_attachments', array( $this, 'import_attachments' ) );
				add_action( 'wp_ajax_fami_import_theme_options', array( $this, 'import_theme_options' ) );
			}
		}
		
		/**
		 * Define  Constants.
		 */
		public function define_constants() {
			$this->define( 'CILOE_IMPORTER_DIR', CILOE_TOOLKIT_PATH . '/includes/classes/import' );
			$this->define( 'CILOE_IMPORTER_URI', CILOE_TOOLKIT_URL . '/includes/classes/import' );
		}
		
		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name
		 * @param  string|bool $value
		 */
		public function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		
		public function register_scripts( $hook_suffix ) {
			if ( $hook_suffix == 'toplevel_page_ciloe_menu' ) {
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_style( 'fami-importer-style', CILOE_IMPORTER_URI . '/assets/circle.css' );
				wp_enqueue_style( 'fami-importer-circle', CILOE_IMPORTER_URI . '/assets/import.css' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'fami-importer-script', CILOE_IMPORTER_URI . '/assets/import.js', array( 'jquery' ), false );
			}
		}
		
		public function importer_page_content() {
			$theme_name = wp_get_theme()->get( 'Name' );
			?>
            <div class="fami-importer-wrapper">
                <div class="progress_test" style="height: 5px; background-color: red; width: 0;"></div>
                <h1 class="heading"><?php echo ucfirst( esc_html( $theme_name ) ); ?> - Install Demo Content</h1>
                <div class="note">
                    <h3>Please read before importing:</h3>
                    <p>This importer will help you build your site look like our demo. Importing data is recommended
                        on fresh install.</p>
                    <p>Please ensure you have already installed and
                        activated Ciloe Toolkit, WooCommerce, WPBakery Page Builder and Revolution Slider plugins.</p>
                    <p>Please note that importing data only builds a frame for your website. <strong>It will
                            import all demo contents.</strong></p>
                    <p>It can take a few minutes to complete. <strong>Please don't close your browser while
                            importing.</strong></p>
                    <p>See recommendation for importer and WooCommerce to run fine here: <a target="_blank"
                                                                                            href="http://docs.famithemes.com/server-environment/">http://docs.famithemes.com/server-environment/</a>
                    </p>
                    <h3>Select the options below which you want to import:</h3>
                </div>
                <div class="all-demos-wrap">
					<?php if ( ! empty( $this->data_demos ) ) : ?>
                        <div class="options theme-browser">
							<?php foreach ( $this->data_demos as $key => $data ): ?>
                                <div id="option-<?php echo $key; ?>" class="option">
                                    <div class="inner">
                                        <div class="preview">
                                            <img src="<?php echo $data['preview']; ?>">
                                        </div>
                                        <span class="more-details">HAVE IMPORTED</span>
                                        <h3 class="demo-name theme-name"><?php echo $data['name']; ?></h3>
                                        <div class="group-control theme-actions">
                                            <div class="control-inner">
                                                <button data-id="<?php echo $key; ?>"
                                                        data-optionid="<?php echo $key; ?>"
                                                        class="button button-primary open-import">Install
                                                </button>
                                                <a target="_blank" class="button"
                                                   href="<?php echo $data['demo_link']; ?>">View demo</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="content-demo-<?php echo $key; ?>" class="option" style="display: none;">
                                        <div class="inner" data-option="<?php echo $key; ?>">
                                            <div class="plugin-check">
												<?php
												$can_import_demo = true;
												if ( defined( 'FAMI_DISABLE_IMPORT_DEMO' ) ) {
													if ( FAMI_DISABLE_IMPORT_DEMO === true ) {
														$can_import_demo = FAMI_DISABLE_IMPORT_DEMO !== true;
														echo '<p class="fami-warning">Can not perform import on this server! The administrator has either banned this or the server has been configured to not be allowed to perform the demo installation</p>';
													}
												}
												?>
												<?php if ( $can_import_demo ) { ?>
                                                    <strong>The Following Required To Import Content !</strong>
                                                    <p>
                                                        <span>PHP Version > 5.6, max_execution_time 180</span>
                                                        <span>( * )</span>
                                                    </p>
                                                    <p>
                                                        <span>Your host allow download file from other site and zip file</span>
                                                        <span>( * )</span>
                                                    </p>
                                                    <p>
                                                        <span>memory_limit 128M, post_max_size 32M, upload_max_filesize 32M</span>
                                                        <span>( * )</span>
                                                    </p>
												<?php } ?>
                                            </div>
                                            <div class="block-title">
                                                <h3 class="demo-name"><?php echo $data['name']; ?></h3>
                                                <a target="_blank" class="more"
                                                   href="<?php echo $data['demo_link']; ?>">View demo</a>
                                            </div>
                                            <div class="fami-control">
                                                <h4 class="import-title">Import content</h4>
                                                <div class="control-inner">
                                                    <div class="group-control">
														<?php foreach ( $this->item_import as $keys => $item ) : ?>
                                                            <label for="<?php echo esc_attr( $keys ); ?>-<?php echo $key; ?>">
                                                                <input id="<?php echo esc_attr( $keys ); ?>-<?php echo $key; ?>"
                                                                       type="checkbox"
                                                                       class="<?php echo esc_attr( $keys ); ?>"
                                                                       value="<?php echo $key; ?>">
																<?php echo esc_html( $item ); ?>
                                                            </label>
														<?php endforeach; ?>
                                                        <button data-id="<?php echo $key; ?>"
                                                                data-slug="<?php echo $data['slug']; ?>"
                                                                data-optionid="<?php echo $key; ?>"
                                                                class="button button-primary fami-button-import">Install
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress-wapper">
                                                <div class="progress-item">
													<?php foreach ( $this->item_import as $keys => $item ) : ?>
                                                        <div class="meter item <?php echo esc_attr( $keys ); ?>">
															<?php echo esc_html( $item ); ?>
                                                            <div class="checkmark">
                                                                <div class="checkmark_stem"></div>
                                                                <div class="checkmark_kick"></div>
                                                            </div>
                                                            <span style="width: 100%"></span>
                                                        </div>
													<?php endforeach; ?>
                                                    <div class="meter item fami_import_single_page_content">
                                                        Import this page content
                                                        <div class="checkmark">
                                                            <div class="checkmark_stem"></div>
                                                            <div class="checkmark_kick"></div>
                                                        </div>
                                                        <span style="width: 100%"></span>
                                                    </div>
                                                    <div class="meter item fami_import_config">
                                                        Import Config
                                                        <div class="checkmark">
                                                            <div class="checkmark_stem"></div>
                                                            <div class="checkmark_kick"></div>
                                                        </div>
                                                        <span style="width: 100%"></span>
                                                    </div>
                                                </div>
                                                <div class="progress-circle">
                                                    <div class="c100 p0 dark green" data-percent="1">
                                                        <span class="percent">0%</span>
                                                        <div class="slice">
                                                            <div class="bar"></div>
                                                            <div class="fill"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							<?php endforeach; ?>
                        </div>
					<?php else: ?>
                        <p>No data import</p>
					<?php endif; ?>
                </div>
                <div class="server-environment-wrap">
                    <h3>Server Environment</h3>
                    <p>Basic information about server parameters requirements</p>
                    <div class="server-params-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>FIELD NAME</th>
                                <th>EXAMPLE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><strong>Server Info</strong></td>
                                <td>nginx / apache</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Version</strong></td>
                                <td>5.6.x</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Post Max Size</strong></td>
                                <td>100 MB</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Time Limit</strong></td>
                                <td>300</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Max Input Vars</strong></td>
                                <td>6144</td>
                            </tr>
                            <tr>
                                <td><strong>cURL Version</strong></td>

                                <td>7.53.1, OpenSSL/1.0.1t</td>
                            </tr>
                            <tr>
                                <td><strong>SUHOSIN Installed</strong></td>
                                <td>–</td>
                            </tr>
                            <tr>
                                <td><strong>Max Upload Size</strong></td>
                                <td>100MB</td>
                            </tr>
                            <tr>
                                <td><strong>Default Timezone is UTC</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>fsockopen/cURL</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>SoapClient</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>DOMDocument</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>GZip</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>Multibyte String</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>Remote Post</strong></td>
                                <td>√</td>
                            </tr>
                            <tr>
                                <td><strong>Remote Get</strong></td>
                                <td>√</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			<?php
		}
		
		/* DOWNLOAD FILE */
		public function download( $url = "", $file_name = "" ) {
			$filepath = "";
			if ( $url != "" ) {
				$upload_dir = wp_upload_dir();
				$ch         = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				$data = curl_exec( $ch );
				curl_close( $ch );
				$destination = $upload_dir['path'] . "/" . $file_name;
				$file        = fopen( $destination, "w+" );
				fputs( $file, $data );
				fclose( $file );
				$filepath = $destination;
			}
			
			return $filepath;
		}
		
		/* Include Importer Classes */
		public function include_importer_classes() {
			if ( ! class_exists( 'WP_Importer' ) ) {
				include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			}
			if ( ! class_exists( 'KT_WP_Import' ) ) {
				if ( file_exists( dirname( __FILE__ ) . '/includes/wordpress-importer.php' ) ) {
					include_once dirname( __FILE__ ) . '/includes/wordpress-importer.php';
				}
			}
		}
		
		public function no_resize_image( $sizes ) {
			return array();
		}
		
		public function before_content_import() {
			if ( class_exists( 'WooCommerce' ) ) {
				global $wpdb;
				if ( current_user_can( 'administrator' ) ) {
					$attributes = array(
						array(
							'attribute_label'   => 'Color',
							'attribute_name'    => 'color',
							'attribute_type'    => 'box_style', // text, box_style, select
							'attribute_orderby' => 'menu_order',
							'attribute_public'  => '0',
						),
						array(
							'attribute_label'   => 'Size',
							'attribute_name'    => 'size',
							'attribute_type'    => 'box_style', // text, box_style, select
							'attribute_orderby' => 'menu_order',
							'attribute_public'  => '0',
						),
					);
					$attributes = apply_filters( 'ciloe_import_wooCommerce_attributes', $attributes );
					foreach ( $attributes as $attribute ):
						if ( empty( $attribute['attribute_name'] ) || empty( $attribute['attribute_label'] ) ) {
							return new WP_Error( 'error', __( 'Please, provide an attribute name and slug.', 'woocommerce' ) );
						} elseif ( ( $valid_attribute_name = $this->wc_valid_attribute_name( $attribute['attribute_name'] ) ) && is_wp_error( $valid_attribute_name ) ) {
							return $valid_attribute_name;
						} elseif ( taxonomy_exists( wc_attribute_taxonomy_name( $attribute['attribute_name'] ) ) ) {
							return new WP_Error( 'error', sprintf( __( 'Slug "%s" is already in use. Change it, please.', 'woocommerce' ), sanitize_title( $attribute['attribute_name'] ) ) );
						}
						$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
						do_action( 'woocommerce_attribute_added', $wpdb->insert_id, $attribute );
						$attribute_name = wc_sanitize_taxonomy_name( 'pa_' . $attribute['attribute_name'] );
						if ( ! taxonomy_exists( $attribute_name ) ) {
							$args = array(
								'hierarchical' => true,
								'show_ui'      => false,
								'query_var'    => true,
								'rewrite'      => false,
							);
							register_taxonomy( $attribute_name, array( 'product' ), $args );
						}
						flush_rewrite_rules();
						delete_transient( 'wc_attribute_taxonomies' );
					endforeach;
				}
			}
			do_action( 'ecome_before_content_import' );
		}
		
		public function wc_valid_attribute_name( $attribute_name ) {
			if ( ! class_exists( 'WooCommerce' ) ) {
				return false;
			}
			if ( strlen( $attribute_name ) >= 28 ) {
				return new WP_Error( 'error', sprintf( __( 'Slug "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce' ), sanitize_title( $attribute_name ) ) );
			} elseif ( wc_check_if_attribute_name_is_reserved( $attribute_name ) ) {
				return new WP_Error( 'error', sprintf( __( 'Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce' ), sanitize_title( $attribute_name ) ) );
			}
			
			return true;
		}
		
		public function import_full_content() {
			// Don't import data twice
			$already_imported_full_data = get_option( 'ciloe_already_imported_full_data', 'no' ) == 'yes';
			if ( $already_imported_full_data ) {
				wp_die();
			}
			$this->ajax_optionid         = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_options_posttype = array( 'page' );
			$this->ajax_main_content     = 1;
			$this->ajax_attachments      = true;
			$this->import_content();
			update_option( 'ciloe_already_imported_full_data', 'yes' );
			wp_die();
		}
		
		public function import_single_page_content() {
			$this->ajax_optionid         = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_options_posttype = array( 'page' );
			$this->ajax_options_name     = isset( $_POST['slug_home'] ) ? $_POST['slug_home'] : array();
			$this->import_content();
			wp_die();
		}
		
		public function import_page_content() {
			$already_imported_page = get_option( 'ciloe_already_imported_page', 'no' ) == 'yes';
			if ( $already_imported_page ) {
				wp_die();
			}
			$this->ajax_optionid         = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_options_posttype = array( 'page' );
			$this->import_content();
			update_option( 'ciloe_already_imported_page', 'yes' );
			wp_die();
		}
		
		public function import_post_content() {
			$already_imported_post = get_option( 'ciloe_already_imported_post', 'no' ) == 'yes';
			if ( $already_imported_post ) {
				wp_die();
			}
			$this->ajax_optionid         = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_options_posttype = array( 'post' );
			$this->import_content();
			update_option( 'ciloe_already_imported_post', 'yes' );
			wp_die();
		}
		
		public function import_attachments() {
			$already_imported_attachment = get_option( 'ciloe_already_imported_attachment', 'no' ) == 'yes';
			if ( $already_imported_attachment ) {
				wp_die();
			}
			$this->ajax_optionid         = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_options_posttype = array( 'attachment' );
			$this->ajax_attachments      = true;
			$this->import_content();
			update_option( 'ciloe_already_imported_attachment', 'yes' );
			wp_die();
		}
		
		public function import_product_content() {
			$already_imported_product = get_option( 'ciloe_already_imported_product', 'no' ) == 'yes';
			if ( $already_imported_product ) {
				wp_die();
			}
			$this->ajax_optionid         = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_options_posttype = array( 'product' );
			$this->import_content();
			
			update_option( 'ciloe_already_imported_product', 'yes' );
			wp_die();
		}
		
		public function import_menu() {
			global $wpdb;
			//			$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "posts` WHERE `post_type`='nav_menu_item'" );
			$already_imported_menu = get_option( 'ciloe_already_imported_menu', 'no' ) == 'yes';
			if ( $already_imported_menu ) {
				wp_die();
			}
			$this->ajax_optionid    = isset( $_POST['optionid'] ) ? $_POST['optionid'] : '';
			$this->ajax_menu_import = 1;
			$this->import_content();
			update_option( 'ciloe_already_imported_menu', 'yes' );
			wp_die();
		}
		
		public function import_theme_options() {
			$optionid = isset( $_POST['optionid'] ) ? $_POST['optionid'] : "";
			if ( $optionid != "" ) {
				$demo = $this->data_demos[ $optionid ];
				if ( ! is_array( $demo ) ) {
					return;
				}
			}
			if ( isset( $demo['theme_option'] ) && $demo['theme_option'] != "" ) {
				$data = file_get_contents( $demo['theme_option'] );
				update_option( '_cs_options', cs_decode_string( $data ) );
			}
			wp_die();
		}
		
		public function import_content() {
			set_time_limit( 0 );
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			}
			
			$ajax_optionid         = $this->ajax_optionid;
			$ajax_menu_import      = $this->ajax_menu_import;
			$ajax_options_posttype = $this->ajax_options_posttype;
			$ajax_options_name     = $this->ajax_options_name;
			$ajax_main_content     = $this->ajax_main_content;
			$ajax_attachments      = $this->ajax_attachments;
			add_filter( 'intermediate_image_sizes_advanced', array( $this, 'no_resize_image' ) );
			if ( $ajax_optionid != '' ) {
				$this->before_content_import();
				$this->include_importer_classes();
				$importer                        = new KT_WP_Import();
				$importer->fetch_attachments     = $ajax_attachments;
				$importer->ajax_options_posttype = $ajax_options_posttype;
				$importer->ajax_options_name     = $ajax_options_name;
				$importer->ajax_main_content     = $ajax_main_content;
				$importer->menu_import           = $ajax_menu_import;
				$importer->import( $this->data_demos[ $ajax_optionid ]['content_path'] );
				echo 'Successful Import Demo Content';
			}
		}
		
		/* import Sidebar Content */
		public function import_widget() {
			$optionid = isset( $_POST['optionid'] ) ? $_POST['optionid'] : "";
			if ( $optionid == "" ) {
				return;
			}
			
			// Don't import widgets twice
			$already_imported_widgets = get_option( 'ciloe_already_imported_widgets', 'no' ) == 'yes';
			if ( $already_imported_widgets ) {
				return;
			}
			
			$url  = $this->data_demos[ $optionid ]['widget_path'];
			$data = file_get_contents( $url );
			$data = json_decode( $data );
			global $wp_registered_sidebars;
			if ( empty( $data ) || ! is_object( $data ) ) {
				wp_die();
			}
			update_option( 'sidebars_widgets', array( false ) );
			do_action( 'wie_before_import' );
			$data              = apply_filters( 'wie_import_data', $data );
			$available_widgets = $this->available_widgets();
			$widget_instances  = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}
			$results = array();
			foreach ( $data as $sidebar_id => $widgets ) {
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}
				if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
					$sidebar_available    = true;
					$use_sidebar_id       = $sidebar_id;
					$sidebar_message_type = 'success';
					$sidebar_message      = '';
				} else {
					$sidebar_available    = false;
					$use_sidebar_id       = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
					$sidebar_message_type = 'error';
					$sidebar_message      = __( 'Sidebar does not exist in theme (using Inactive)', 'widget-importer-exporter' );
				}
				$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
				$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
				$results[ $sidebar_id ]['message']      = $sidebar_message;
				$results[ $sidebar_id ]['widgets']      = array();
				foreach ( $widgets as $widget_instance_id => $widget ) {
					$fail               = false;
					$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
					$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );
					if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
						$fail                = true;
						$widget_message_type = 'error';
						$widget_message      = __( 'Site does not support widget', 'widget-importer-exporter' );
					}
					$widget = apply_filters( 'wie_widget_settings', $widget );
					$widget = json_decode( json_encode( $widget ), true );
					$widget = apply_filters( 'wie_widget_settings_array', $widget );
					if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {
						$sidebars_widgets        = get_option( 'sidebars_widgets' );
						$sidebar_widgets         = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array();
						$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
						foreach ( $single_widget_instances as $check_id => $check_widget ) {
							if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
								$fail                = true;
								$widget_message_type = 'warning';
								$widget_message      = __( 'Widget already exists', 'widget-importer-exporter' );
								break;
							}
						}
					}
					if ( ! $fail ) {
						$single_widget_instances   = get_option( 'widget_' . $id_base );
						$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 );
						$single_widget_instances[] = $widget;
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number                             = 1;
							$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}
						update_option( 'widget_' . $id_base, $single_widget_instances );
						$sidebars_widgets                      = get_option( 'sidebars_widgets' );
						$new_instance_id                       = $id_base . '-' . $new_instance_id_number;
						$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id;
						update_option( 'sidebars_widgets', $sidebars_widgets );
						$after_widget_import = array(
							'sidebar'           => $use_sidebar_id,
							'sidebar_old'       => $sidebar_id,
							'widget'            => $widget,
							'widget_type'       => $id_base,
							'widget_id'         => $new_instance_id,
							'widget_id_old'     => $widget_instance_id,
							'widget_id_num'     => $new_instance_id_number,
							'widget_id_num_old' => $instance_id_number,
						);
						do_action( 'wie_after_widget_import', $after_widget_import );
						if ( $sidebar_available ) {
							$widget_message_type = 'success';
							$widget_message      = __( 'Imported', 'widget-importer-exporter' );
						} else {
							$widget_message_type = 'warning';
							$widget_message      = __( 'Imported to Inactive', 'widget-importer-exporter' );
						}
					}
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget['title'] ) ? $widget['title'] : __( 'No Title', 'widget-importer-exporter' ); // show "No Title" if widget instance is untitled
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;
				}
			}
			do_action( 'wie_after_import' );
			update_option( 'ciloe_already_imported_widgets', 'yes' );
			wp_die();
		}
		
		public function available_widgets() {
			global $wp_registered_widget_controls;
			$widget_controls   = $wp_registered_widget_controls;
			$available_widgets = array();
			foreach ( $widget_controls as $widget ) {
				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) { // no dupes
					$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
					$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];
				}
			}
			
			return apply_filters( 'wie_available_widgets', $available_widgets );
		}
		
		/* Import Revolution Slider */
		public function import_revslider() {
			$optionid = isset( $_POST['optionid'] ) ? $_POST['optionid'] : "";
			if ( $optionid == '' ) {
				return;
			}
			// Don't import widgets twice
			$already_imported_revslider = get_option( 'ciloe_already_imported_revslider', 'no' ) == 'yes';
			if ( $already_imported_revslider ) {
				return;
			}
			if ( class_exists( 'UniteFunctionsRev' ) && class_exists( 'ZipArchive' ) ) {
				global $wpdb;
				$updateAnim    = true;
				$updateStatic  = true;
				$rev_directory = $this->data_demos[ $optionid ]['revslider_path'];
				$rev_files     = array();
				$rev_db        = new RevSliderDB();
				foreach ( glob( $rev_directory . '*.zip' ) as $filename ) {
					$filename      = basename( $filename );
					$allow_import  = false;
					$arr_filename  = explode( '_', $filename );
					$slider_new_id = absint( $arr_filename[0] );
					if ( $slider_new_id > 0 ) {
						$response = $rev_db->fetch( RevSliderGlobals::$table_sliders, 'id=' + $slider_new_id );
						if ( empty( $response ) ) { /* not exists */
							$rev_files_ids[] = $slider_new_id;
							$allow_import    = true;
						}
					} else {
						$rev_files_ids[] = 0;
						$allow_import    = true;
					}
					if ( $allow_import ) {
						$rev_files[] = $rev_directory . $filename;
					}
				}
				foreach ( $rev_files as $index => $rev_file ) {
					$filepath  = $rev_file;
					$zip       = new ZipArchive;
					$importZip = $zip->open( $filepath, ZIPARCHIVE::CREATE );
					if ( $importZip === true ) {
						$slider_export     = $zip->getStream( 'slider_export.txt' );
						$custom_animations = $zip->getStream( 'custom_animations.txt' );
						$dynamic_captions  = $zip->getStream( 'dynamic-captions.css' );
						$static_captions   = $zip->getStream( 'static-captions.css' );
						$content           = '';
						$animations        = '';
						$dynamic           = '';
						$static            = '';
						while ( ! feof( $slider_export ) ) {
							$content .= fread( $slider_export, 1024 );
						}
						if ( $custom_animations ) {
							while ( ! feof( $custom_animations ) ) {
								$animations .= fread( $custom_animations, 1024 );
							}
						}
						if ( $dynamic_captions ) {
							while ( ! feof( $dynamic_captions ) ) {
								$dynamic .= fread( $dynamic_captions, 1024 );
							}
						}
						if ( $static_captions ) {
							while ( ! feof( $static_captions ) ) {
								$static .= fread( $static_captions, 1024 );
							}
						}
						fclose( $slider_export );
						if ( $custom_animations ) {
							fclose( $custom_animations );
						}
						if ( $dynamic_captions ) {
							fclose( $dynamic_captions );
						}
						if ( $static_captions ) {
							fclose( $static_captions );
						}
					} else {
						$content = @file_get_contents( $filepath );
					}
					if ( $importZip === true ) {
						$db         = new UniteDBRev();
						$animations = @unserialize( $animations );
						if ( ! empty( $animations ) ) {
							foreach ( $animations as $key => $animation ) {
								$exist = $db->fetch( GlobalsRevSlider::$table_layer_anims, "handle = '" . $animation['handle'] . "'" );
								if ( ! empty( $exist ) ) {
									if ( $updateAnim == 'true' ) {
										$arrUpdate           = array();
										$arrUpdate['params'] = stripslashes( json_encode( str_replace( "'", '"', $animation['params'] ) ) );
										$db->update( GlobalsRevSlider::$table_layer_anims, $arrUpdate, array( 'handle' => $animation['handle'] ) );
										$id = $exist['0']['id'];
									} else {
										$arrInsert           = array();
										$arrInsert["handle"] = 'copy_' . $animation['handle'];
										$arrInsert["params"] = stripslashes( json_encode( str_replace( "'", '"', $animation['params'] ) ) );
										$id                  = $db->insert( GlobalsRevSlider::$table_layer_anims, $arrInsert );
									}
								} else {
									$arrInsert           = array();
									$arrInsert["handle"] = $animation['handle'];
									$arrInsert["params"] = stripslashes( json_encode( str_replace( "'", '"', $animation['params'] ) ) );
									$id                  = $db->insert( GlobalsRevSlider::$table_layer_anims, $arrInsert );
								}
								$content = str_replace( array(
									                        'customin-' . $animation['id'],
									                        'customout-' . $animation['id']
								                        ), array( 'customin-' . $id, 'customout-' . $id ), $content );
							}
						}
						if ( ! empty( $static ) ) {
							if ( isset( $updateStatic ) && $updateStatic == 'true' ) {
								RevOperations::updateStaticCss( $static );
							} else {
								$static_cur = RevOperations::getStaticCss();
								$static     = $static_cur . "\n" . $static;
								RevOperations::updateStaticCss( $static );
							}
						}
						$dynamicCss = UniteCssParserRev::parseCssToArray( $dynamic );
						if ( is_array( $dynamicCss ) && $dynamicCss !== false && count( $dynamicCss ) > 0 ) {
							foreach ( $dynamicCss as $class => $styles ) {
								$class = trim( $class );
								if ( ( strpos( $class, ':hover' ) === false && strpos( $class, ':' ) !== false ) ||
								     strpos( $class, " " ) !== false ||
								     strpos( $class, ".tp-caption" ) === false ||
								     ( strpos( $class, "." ) === false || strpos( $class, "#" ) !== false ) ||
								     strpos( $class, ">" ) !== false
								) {
									continue;
								}
								if ( strpos( $class, ':hover' ) !== false ) {
									$class                 = trim( str_replace( ':hover', '', $class ) );
									$arrInsert             = array();
									$arrInsert["hover"]    = json_encode( $styles );
									$arrInsert["settings"] = json_encode( array( 'hover' => 'true' ) );
								} else {
									$arrInsert           = array();
									$arrInsert["params"] = json_encode( $styles );
								}
								$result = $db->fetch( GlobalsRevSlider::$table_css, "handle = '" . $class . "'" );
								if ( ! empty( $result ) ) {
									$db->update( GlobalsRevSlider::$table_css, $arrInsert, array( 'handle' => $class ) );
								} else {
									$arrInsert["handle"] = $class;
									$db->insert( GlobalsRevSlider::$table_css, $arrInsert );
								}
							}
						}
					}
					$content      = preg_replace_callback( '!s:(\d+):"(.*?)";!', array(
						'RevSliderSlider',
						'clear_error_in_string'
					), $content ); //clear errors in string
					$arrSlider    = @unserialize( $content );
					$sliderParams = $arrSlider["params"];
					if ( isset( $sliderParams["background_image"] ) ) {
						$sliderParams["background_image"] = UniteFunctionsWPRev::getImageUrlFromPath( $sliderParams["background_image"] );
					}
					$json_params         = json_encode( $sliderParams );
					$arrInsert           = array();
					$arrInsert["params"] = $json_params;
					$arrInsert["title"]  = UniteFunctionsRev::getVal( $sliderParams, "title", "Slider1" );
					$arrInsert["alias"]  = UniteFunctionsRev::getVal( $sliderParams, "alias", "slider1" );
					if ( $rev_files_ids[ $index ] != 0 ) {
						$arrInsert["id"] = $rev_files_ids[ $index ];
						$arrFormat       = array( '%s', '%s', '%s', '%d' );
					} else {
						$arrFormat = array( '%s', '%s', '%s' );
					}
					$sliderID = $wpdb->insert( GlobalsRevSlider::$table_sliders, $arrInsert, $arrFormat );
					$sliderID = $wpdb->insert_id;
					/* create all slides */
					$arrSlides       = $arrSlider["slides"];
					$alreadyImported = array();
					foreach ( $arrSlides as $slide ) {
						$params = $slide["params"];
						$layers = $slide["layers"];
						if ( isset( $params["image"] ) ) {
							if ( trim( $params["image"] ) !== '' ) {
								if ( $importZip === true ) {
									$image = $zip->getStream( 'images/' . $params["image"] );
									if ( ! $image ) {
										echo $params["image"] . ' not found!<br>';
									} else {
										if ( ! isset( $alreadyImported[ 'zip://' . $filepath . "#" . 'images/' . $params["image"] ] ) ) {
											$importImage = UniteFunctionsWPRev::import_media( 'zip://' . $filepath . "#" . 'images/' . $params["image"], $sliderParams["alias"] . '/' );
											if ( $importImage !== false ) {
												$alreadyImported[ 'zip://' . $filepath . "#" . 'images/' . $params["image"] ] = $importImage['path'];
												$params["image"]                                                              = $importImage['path'];
											}
										} else {
											$params["image"] = $alreadyImported[ 'zip://' . $filepath . "#" . 'images/' . $params["image"] ];
										}
									}
								}
							}
							$params["image"] = UniteFunctionsWPRev::getImageUrlFromPath( $params["image"] );
						}
						foreach ( $layers as $key => $layer ) {
							if ( isset( $layer["image_url"] ) ) {
								if ( trim( $layer["image_url"] ) !== '' ) {
									if ( $importZip === true ) {
										$image_url = $zip->getStream( 'images/' . $layer["image_url"] );
										if ( ! $image_url ) {
											echo $layer["image_url"] . ' not found!<br>';
										} else {
											if ( ! isset( $alreadyImported[ 'zip://' . $filepath . "#" . 'images/' . $layer["image_url"] ] ) ) {
												$importImage = UniteFunctionsWPRev::import_media( 'zip://' . $filepath . "#" . 'images/' . $layer["image_url"], $sliderParams["alias"] . '/' );
												if ( $importImage !== false ) {
													$alreadyImported[ 'zip://' . $filepath . "#" . 'images/' . $layer["image_url"] ] = $importImage['path'];
													$layer["image_url"]                                                              = $importImage['path'];
												}
											} else {
												$layer["image_url"] = $alreadyImported[ 'zip://' . $filepath . "#" . 'images/' . $layer["image_url"] ];
											}
										}
									}
								}
								$layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath( $layer["image_url"] );
								$layers[ $key ]     = $layer;
							}
						}
						/* create new slide */
						$arrCreate                = array();
						$arrCreate["slider_id"]   = $sliderID;
						$arrCreate["slide_order"] = $slide["slide_order"];
						$arrCreate["layers"]      = json_encode( $layers );
						$arrCreate["params"]      = json_encode( $params );
						$wpdb->insert( GlobalsRevSlider::$table_slides, $arrCreate );
					}
				}
			}
			update_option( 'ciloe_already_imported_revslider', 'yes' );
			wp_die();
		}
		
		public function import_config() {
			$optionid = isset( $_POST['optionid'] ) ? $_POST['optionid'] : "";
			if ( $optionid != "" ) {
				$demo = $this->data_demos[ $optionid ];
				if ( ! is_array( $demo ) ) {
					return;
				}
			}
			$this->woocommerce_settings();
			$this->menu_locations( $demo );
			$this->mega_menu( $demo );
			$this->update_options( $demo );
			wp_die();
		}
		
		public function mega_menu( $demo ) {
			if ( isset( $demo['mega_menu'] ) && ! empty( $demo['mega_menu'] ) ) {
				foreach ( $demo['mega_menu'] as $item ) {
					$menu = $menu = wp_get_nav_menu_object( $item['name'] );
					if ( ! empty( $menu ) && ! empty( $item['metas'] ) ) {
						foreach ( $item['metas'] as $key => $value ) {
							update_term_meta( $menu->term_id, $key, $value );
						}
					}
				}
			}
		}
		
		/* WooCommerce Settings */
		public function woocommerce_settings() {
			foreach ( $this->woo_pages as $woo_page_name => $woo_page_title ) {
				$woopage = get_page_by_title( $woo_page_title );
				if ( isset( $woopage->ID ) && $woopage->ID ) {
					update_option( $woo_page_name, $woopage->ID );
				}
			}
			if ( class_exists( 'YITH_Woocompare' ) ) {
				update_option( 'yith_woocompare_compare_button_in_products_list', 'yes' );
				update_option( 'yith_woocompare_is_button', 'link' );
			}
			if ( class_exists( 'WC_Admin_Notices' ) ) {
				WC_Admin_Notices::remove_notice( 'install' );
			}
			delete_transient( '_wc_activation_redirect' );
			// Image sizes
			update_option( 'shop_catalog_image_size', $this->woo_catalog );        // Product category thumbs
			update_option( 'shop_single_image_size', $this->woo_single );        // Single product image
			update_option( 'shop_thumbnail_image_size', $this->woo_thumbnail );    // Image gallery thumbs
			flush_rewrite_rules();
			
			// Import WooCommerce Attributes
			$this->import_woocommerce_attributes();
		}
		
		public function import_woocommerce_attributes() {
			// SELECT * FROM wp_terms wt INNER JOIN wp_term_taxonomy wtt ON wt.term_id = wtt.term_id WHERE wtt.taxonomy IN ('pa_color', 'pa_size')
			// SELECT wt.term_id, wt.slug, wtm.meta_value FROM wp_terms wt INNER JOIN wp_termmeta wtm ON wt.term_id = wtm.term_id WHERE wtm.meta_key IN ('pa_color_attribute_swatch_color', 'pa_size_attribute_swatch_photo' )
			$all_atts = array(
				'color' => array(
					array(
						'slug'  => 'black',
						'value' => '#000000'
					),
					array(
						'slug'  => 'blue',
						'value' => '#3763e2'
					),
					array(
						'slug'  => 'gray',
						'value' => '#b4b4b4'
					),
					array(
						'slug'  => 'green',
						'value' => '#008000'
					),
					array(
						'slug'  => 'red',
						'value' => '#e34848'
					),
					array(
						'slug'  => 'cyan',
						'value' => '#00ffff'
					),
					array(
						'slug'  => 'white',
						'value' => '#ffffff'
					),
					array(
						'slug'  => 'purple',
						'value' => '#800080'
					),
					array(
						'slug'  => 'yellow',
						'value' => '#ffff00'
					)
				),
				'size'  => array(
					array(
						'slug'  => 'small',
						'value' => ''
					),
					array(
						'slug'  => 'large',
						'value' => ''
					),
					array(
						'slug'  => 'medium',
						'value' => ''
					),
					array(
						'slug'  => 'extra-large',
						'value' => ''
					)
				)
			);
			
			foreach ( $all_atts as $key => $att_args ) {
				$key = trim( $key );
				if ( $key != '' && ! empty( $att_args ) ) {
					foreach ( $att_args as $att_arg ) {
						$att_slug = isset( $att_arg['slug'] ) ? trim( $att_arg['slug'] ) : '';
						if ( $att_slug != '' ) {
							$att_term = get_term_by( 'slug', $att_slug, 'pa_' . $key );
							if ( $att_term ) {
								$all_att_types = array( 'type', 'photo', 'color' );
								foreach ( $all_att_types as $att_type ) {
									$meta_key   = 'pa_' . $key . '_attribute_swatch_' . $att_type;
									$meta_value = isset( $att_arg['value'] ) ? $att_arg['value'] : '';
									if ( $key == 'size' && $att_type == 'type' ) {
										$meta_value = 'label';
									}
									if ( $key == 'color' && $att_type == 'type' ) {
										$meta_value = 'color';
									}
									$result = update_term_meta( $att_term->term_id, $meta_key, $meta_value );
									if ( $result !== true ) {
										add_term_meta( $att_term->term_id, $meta_key, $meta_value );
									}
								}
							}
						}
					}
				}
			}
		}
		
		/* Menu Locations */
		public function menu_locations( $demo ) {
			$menu_location = array();
			$locations     = get_theme_mod( 'nav_menu_locations' );
			$menus         = wp_get_nav_menus();
			if ( isset( $demo['menu_locations'] ) && is_array( $demo['menu_locations'] ) ) {
				if ( $menus ) {
					foreach ( $menus as $menu ) {
						foreach ( $demo['menu_locations'] as $key => $value ) {
							if ( $menu->name == $value ) {
								$menu_location[ $key ] = $menu->term_id;
							}
						}
					}
				}
				set_theme_mod( 'nav_menu_locations', $menu_location );
			} else if ( isset( $demo['menus'] ) && is_array( $demo['menus'] ) ) {
				$menu_location = $locations;
				set_theme_mod( 'nav_menu_locations', $menu_location );
			}
		}
		
		/* Update Options */
		public function update_options( $demo ) {
			if ( isset( $demo['homepage'] ) && $demo['homepage'] != "" ) {
				// Home page
				$homepage = get_page_by_title( $demo['homepage'] );
				if ( isset( $homepage ) && $homepage->ID ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $homepage->ID );
				}
			}
			// Blog page
			if ( isset( $demo['blogpage'] ) && $demo['blogpage'] != "" ) {
				$post_page = get_page_by_title( $demo['blogpage'] );
				if ( isset( $post_page ) && $post_page->ID ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_for_posts', $post_page->ID );
				}
			}
			
			// Update WooCommerce Products Filter Options
			$this->update_prdctfltr_settings();
			
			// Update Sales Popup data
			$this->update_sales_popup_settings();
		}
		
		public function update_prdctfltr_settings() {
			// Check class exists
			if ( class_exists( 'PrdctfltrInit' ) ) {
				$already_imported_prdctfltr_settings = get_option( 'ciloe_already_imported_prdctfltr_settings', 'no' ) == 'yes';
				if ( $already_imported_prdctfltr_settings ) {
					return;
				}
				// autoload default is "yes"
				$prdctfltr_settings = array(
					'wc_settings_prdctfltr_term_customization_5b23787abf535'   => array(
						'option_value' => unserialize( 'a:2:{s:5:"style";s:5:"color";s:8:"settings";a:16:{s:10:"term_black";s:7:"#000000";s:13:"tooltip_black";s:5:"Black";s:9:"term_blue";s:7:"#3763e2";s:12:"tooltip_blue";s:4:"Blue";s:9:"term_gray";s:7:"#b4b4b4";s:10:"term_green";s:7:"#008000";s:13:"tooltip_green";s:5:"Green";s:8:"term_red";s:7:"#e34848";s:11:"tooltip_red";s:3:"Red";s:9:"term_cyan";s:7:"#00ffff";s:12:"tooltip_cyan";s:4:"Cyan";s:11:"term_purple";s:7:"#800080";s:14:"tooltip_purple";s:6:"Purple";s:10:"term_white";s:7:"#ffffff";s:13:"tooltip_white";s:5:"White";s:11:"term_yellow";s:7:"#ffff00";}}' ),
						'autoload'     => 'no',
					),
					'prdctfltr_wc_default'                                     => array(
						'option_value' => stripslashes( '{\"wc_settings_prdctfltr_always_visible\":\"no\",\"wc_settings_prdctfltr_click_filter\":\"yes\",\"wc_settings_prdctfltr_show_counts\":\"no\",\"wc_settings_prdctfltr_show_search\":\"no\",\"wc_settings_prdctfltr_selection_area\":[\"topbar\"],\"wc_settings_prdctfltr_collector\":\"flat\",\"wc_settings_prdctfltr_selected_reorder\":\"no\",\"wc_settings_prdctfltr_tabbed_selection\":\"no\",\"wc_settings_prdctfltr_disable_bar\":\"no\",\"wc_settings_prdctfltr_disable_sale\":\"no\",\"wc_settings_prdctfltr_disable_instock\":\"no\",\"wc_settings_prdctfltr_disable_reset\":\"no\",\"wc_settings_prdctfltr_custom_action\":\"\",\"wc_settings_prdctfltr_noproducts\":\"\",\"wc_settings_prdctfltr_style_preset\":\"pf_default\",\"wc_settings_prdctfltr_style_mode\":\"pf_mod_masonry\",\"wc_settings_prdctfltr_max_columns\":\"6\",\"wc_settings_prdctfltr_limit_max_height\":\"no\",\"wc_settings_prdctfltr_max_height\":\"150\",\"wc_settings_prdctfltr_custom_scrollbar\":\"no\",\"wc_settings_prdctfltr_style_checkboxes\":\"prdctfltr_round\",\"wc_settings_prdctfltr_style_hierarchy\":\"prdctfltr_hierarchy_circle\",\"wc_settings_prdctfltr_button_position\":\"bottom\",\"wc_settings_prdctfltr_icon\":\"\",\"wc_settings_prdctfltr_title\":\"\",\"wc_settings_prdctfltr_submit\":\"\",\"wc_settings_prdctfltr_loader\":\"css-spinner-full-01\",\"wc_settings_prdctfltr_adoptive\":\"no\",\"wc_settings_prdctfltr_adoptive_mode\":\"permalink\",\"wc_settings_prdctfltr_adoptive_style\":\"pf_adptv_default\",\"wc_settings_prdctfltr_adoptive_depend\":\"\",\"wc_settings_prdctfltr_show_counts_mode\":\"default\",\"wc_settings_prdctfltr_adoptive_reorder\":\"yes\",\"wc_settings_prdctfltr_mobile_preset\":\"Mobile\",\"wc_settings_prdctfltr_mobile_resolution\":\"768\",\"wc_settings_prdctfltr_cat_termsearch\":\"show\",\"wc_settings_prdctfltr_cat_title\":\"\",\"wc_settings_prdctfltr_cat_description\":\"\",\"wc_settings_prdctfltr_include_cats\":null,\"wc_settings_prdctfltr_cat_orderby\":\"\",\"wc_settings_prdctfltr_cat_order\":\"ASC\",\"wc_settings_prdctfltr_cat_limit\":\"0\",\"wc_settings_prdctfltr_cat_hierarchy\":\"no\",\"wc_settings_prdctfltr_cat_mode\":\"showall\",\"wc_settings_prdctfltr_cat_hierarchy_mode\":\"no\",\"wc_settings_prdctfltr_cat_multi\":\"no\",\"wc_settings_prdctfltr_cat_relation\":\"IN\",\"wc_settings_prdctfltr_cat_selection\":\"no\",\"wc_settings_prdctfltr_cat_adoptive\":\"no\",\"wc_settings_prdctfltr_cat_none\":\"no\",\"wc_settings_prdctfltr_cat_term_customization\":\"\",\"wc_settings_prdctfltr_pa_color_title\":\"\",\"wc_settings_prdctfltr_pa_color_description\":\"\",\"wc_settings_prdctfltr_include_pa_color\":null,\"wc_settings_prdctfltr_pa_color\":\"pf_attr_text\",\"wc_settings_prdctfltr_pa_color_orderby\":\"\",\"wc_settings_prdctfltr_pa_color_order\":\"ASC\",\"wc_settings_prdctfltr_pa_color_limit\":\"0\",\"wc_settings_prdctfltr_pa_color_hierarchy\":\"no\",\"wc_settings_prdctfltr_pa_color_hierarchy_mode\":\"no\",\"wc_settings_prdctfltr_pa_color_multi\":\"no\",\"wc_settings_prdctfltr_pa_color_relation\":\"IN\",\"wc_settings_prdctfltr_pa_color_selection\":\"no\",\"wc_settings_prdctfltr_pa_color_adoptive\":\"no\",\"wc_settings_prdctfltr_pa_color_none\":\"no\",\"wc_settings_prdctfltr_pa_color_term_customization\":\"wc_settings_prdctfltr_term_customization_5b23787abf535\",\"wc_settings_prdctfltr_pa_size_title\":\"\",\"wc_settings_prdctfltr_pa_size_description\":\"\",\"wc_settings_prdctfltr_include_pa_size\":null,\"wc_settings_prdctfltr_pa_size\":\"pf_attr_text\",\"wc_settings_prdctfltr_pa_size_orderby\":\"\",\"wc_settings_prdctfltr_pa_size_order\":\"ASC\",\"wc_settings_prdctfltr_pa_size_limit\":\"0\",\"wc_settings_prdctfltr_pa_size_hierarchy\":\"no\",\"wc_settings_prdctfltr_pa_size_hierarchy_mode\":\"no\",\"wc_settings_prdctfltr_pa_size_multi\":\"no\",\"wc_settings_prdctfltr_pa_size_relation\":\"IN\",\"wc_settings_prdctfltr_pa_size_selection\":\"no\",\"wc_settings_prdctfltr_pa_size_adoptive\":\"no\",\"wc_settings_prdctfltr_pa_size_none\":\"no\",\"wc_settings_prdctfltr_pa_size_term_customization\":\"wc_settings_prdctfltr_term_customization_5b237d18cede3\",\"wc_settings_prdctfltr_tag_title\":\"\",\"wc_settings_prdctfltr_tag_description\":\"\",\"wc_settings_prdctfltr_include_tags\":null,\"wc_settings_prdctfltr_tag_orderby\":\"\",\"wc_settings_prdctfltr_tag_order\":\"ASC\",\"wc_settings_prdctfltr_tag_limit\":\"0\",\"wc_settings_prdctfltr_tag_multi\":\"no\",\"wc_settings_prdctfltr_tag_relation\":\"IN\",\"wc_settings_prdctfltr_tag_selection\":\"no\",\"wc_settings_prdctfltr_tag_adoptive\":\"no\",\"wc_settings_prdctfltr_tag_none\":\"no\",\"wc_settings_prdctfltr_tag_term_customization\":\"\",\"wc_settings_prdctfltr_orderby_title\":\"\",\"wc_settings_prdctfltr_orderby_description\":\"\",\"wc_settings_prdctfltr_include_orderby\":[\"menu_order\",\"popularity\",\"rating\",\"date\",\"price\",\"price-desc\"],\"wc_settings_prdctfltr_orderby_none\":\"no\",\"wc_settings_prdctfltr_orderby_term_customization\":\"\",\"wc_settings_prdctfltr_active_filters\":[\"cat\",\"pa_color\",\"pa_size\",\"tag\",\"sort\",\"range\"],\"wc_settings_prdctfltr_perpage_title\":\"\",\"wc_settings_prdctfltr_perpage_description\":\"\",\"wc_settings_prdctfltr_perpage_label\":\"\",\"wc_settings_prdctfltr_perpage_range\":\"20\",\"wc_settings_prdctfltr_perpage_range_limit\":\"5\",\"wc_settings_prdctfltr_perpage_term_customization\":\"\",\"wc_settings_prdctfltr_perpage_filter_customization\":\"\",\"wc_settings_prdctfltr_vendor_title\":\"\",\"wc_settings_prdctfltr_vendor_description\":\"\",\"wc_settings_prdctfltr_include_vendor\":null,\"wc_settings_prdctfltr_vendor_term_customization\":\"\",\"wc_settings_prdctfltr_instock_title\":\"\",\"wc_settings_prdctfltr_instock_description\":\"\",\"wc_settings_prdctfltr_instock_term_customization\":\"\",\"wc_settings_prdctfltr_search_title\":\"\",\"wc_settings_prdctfltr_search_description\":\"\",\"wc_settings_prdctfltr_search_placeholder\":\"\",\"wc_settings_prdctfltr_price_title\":\"Price\",\"wc_settings_prdctfltr_price_description\":\"\",\"wc_settings_prdctfltr_price_range\":\"100\",\"wc_settings_prdctfltr_price_range_add\":\"100\",\"wc_settings_prdctfltr_price_range_limit\":\"4\",\"wc_settings_prdctfltr_price_none\":\"no\",\"wc_settings_prdctfltr_price_term_customization\":\"\",\"wc_settings_prdctfltr_price_filter_customization\":\"\",\"wc_settings_prdctfltr_custom_tax_title\":\"\",\"wc_settings_prdctfltr_custom_tax_description\":\"\",\"wc_settings_prdctfltr_include_chars\":null,\"wc_settings_prdctfltr_custom_tax_orderby\":\"\",\"wc_settings_prdctfltr_custom_tax_order\":\"ASC\",\"wc_settings_prdctfltr_custom_tax_limit\":\"0\",\"wc_settings_prdctfltr_chars_multi\":\"no\",\"wc_settings_prdctfltr_custom_tax_relation\":\"IN\",\"wc_settings_prdctfltr_chars_selection\":\"no\",\"wc_settings_prdctfltr_chars_adoptive\":\"no\",\"wc_settings_prdctfltr_chars_none\":\"no\",\"wc_settings_prdctfltr_chars_term_customization\":\"\",\"wc_settings_prdctfltr_range_filters\":{\"pfr_title\":[\"\"],\"pfr_description\":[\"Price: $48 — $185\"],\"pfr_taxonomy\":[\"price\"],\"pfr_include\":[null],\"pfr_order\":[\"ASC\"],\"pfr_orderby\":[\"\"],\"pfr_style\":[\"flat\"],\"pfr_grid\":[\"no\"],\"pfr_custom\":[\"{\\\"min\\\":\\\"\\\",\\\"max\\\":\\\"\\\",\\\"prefix\\\":\\\"\\\",\\\"postfix\\\":\\\"\\\",\\\"step\\\":\\\"0\\\",\\\"grid_num\\\":\\\"\\\"}\"],\"pfr_adoptive\":[\"no\"]}}' ),
						'autoload'     => 'no',
					),
					'wc_settings_prdctfltr_enable'                             => array(
						'option_value' => 'yes',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_enable_action'                      => array(
						'option_value' => 'woocommerce_archive_description:50',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_default_templates'                  => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_enable_overrides'                   => array(
						'option_value' => unserialize( 'a:2:{i:0;s:7:"orderby";i:1;s:12:"result-count";}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_custom_tax'                         => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_use_variable_images'                => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_hideempty'                          => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_clearall'                           => array(
						'option_value' => unserialize( 'a:0:{}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_more_overrides'                     => array(
						'option_value' => unserialize( 'a:2:{i:0;s:11:"product_cat";i:1;s:11:"product_tag";}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_disable_scripts'                    => array(
						'option_value' => unserialize( 'a:0:{}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_use_ajax'                           => array(
						'option_value' => 'yes',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_class'                         => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_category_class'                => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_product_class'                 => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_pagination_class'              => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_pagination'                    => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_count_class'                   => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_orderby_class'                 => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_columns'                       => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_rows'                          => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_pagination_type'                    => array(
						'option_value' => 'default',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_product_animation'                  => array(
						'option_value' => 'default',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_after_ajax_scroll'                  => array(
						'option_value' => 'products',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_permalink'                     => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_templates'                     => array(
						'option_value' => unserialize( 'a:0:{}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_failsafe'                      => array(
						'option_value' => unserialize( 'a:1:{i:0;s:7:"wrapper";}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_ajax_js'                            => array(
						'option_value' => '',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_force_redirects'                    => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_remove_single_redirect'             => array(
						'option_value' => 'yes',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_force_product'                      => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_force_action'                       => array(
						'option_value' => 'no',
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_term_customization_5b237d18cede3'   => array(
						'option_value' => unserialize( 'a:2:{s:5:"style";s:4:"text";s:8:"settings";a:8:{s:4:"type";s:6:"border";s:6:"normal";s:7:"#bbbbbb";s:6:"active";s:7:"#333333";s:8:"disabled";s:7:"#eeeeee";s:13:"tooltip_large";s:5:"Large";s:14:"tooltip_medium";s:6:"Medium";s:13:"tooltip_small";s:5:"Small";s:19:"tooltip_extra-large";s:11:"Extra Large";}}' ),
						'autoload'     => 'no',
					),
					'prdctfltr_templates'                                      => array(
						'option_value' => unserialize( 'a:1:{s:6:"Mobile";a:0:{}}' ),
						'autoload'     => 'no',
					),
					'prdctfltr_wc_template_mobile'                             => array(
						'option_value' => unserialize( '{"wc_settings_prdctfltr_always_visible":"no","wc_settings_prdctfltr_click_filter":"yes","wc_settings_prdctfltr_show_counts":"no","wc_settings_prdctfltr_show_search":"no","wc_settings_prdctfltr_selection_area":["topbar"],"wc_settings_prdctfltr_collector":"flat","wc_settings_prdctfltr_selected_reorder":"no","wc_settings_prdctfltr_tabbed_selection":"no","wc_settings_prdctfltr_disable_bar":"no","wc_settings_prdctfltr_disable_sale":"no","wc_settings_prdctfltr_disable_instock":"no","wc_settings_prdctfltr_disable_reset":"no","wc_settings_prdctfltr_custom_action":"","wc_settings_prdctfltr_noproducts":"","wc_settings_prdctfltr_style_preset":"pf_sidebar_right","wc_settings_prdctfltr_style_mode":"pf_mod_multirow","wc_settings_prdctfltr_max_columns":"5","wc_settings_prdctfltr_limit_max_height":"no","wc_settings_prdctfltr_max_height":"150","wc_settings_prdctfltr_custom_scrollbar":"no","wc_settings_prdctfltr_style_checkboxes":"prdctfltr_round","wc_settings_prdctfltr_style_hierarchy":"prdctfltr_hierarchy_circle","wc_settings_prdctfltr_button_position":"bottom","wc_settings_prdctfltr_icon":"","wc_settings_prdctfltr_title":"","wc_settings_prdctfltr_submit":"","wc_settings_prdctfltr_loader":"css-spinner-full-01","wc_settings_prdctfltr_adoptive":"no","wc_settings_prdctfltr_adoptive_mode":"permalink","wc_settings_prdctfltr_adoptive_style":"pf_adptv_default","wc_settings_prdctfltr_adoptive_depend":"","wc_settings_prdctfltr_show_counts_mode":"default","wc_settings_prdctfltr_adoptive_reorder":"yes","wc_settings_prdctfltr_mobile_preset":"default","wc_settings_prdctfltr_mobile_resolution":"768","wc_settings_prdctfltr_orderby_title":"","wc_settings_prdctfltr_orderby_description":"","wc_settings_prdctfltr_include_orderby":["menu_order","popularity","rating","date","price","price-desc"],"wc_settings_prdctfltr_orderby_none":"no","wc_settings_prdctfltr_orderby_term_customization":"","wc_settings_prdctfltr_cat_termsearch":"show","wc_settings_prdctfltr_cat_title":"","wc_settings_prdctfltr_cat_description":"","wc_settings_prdctfltr_include_cats":null,"wc_settings_prdctfltr_cat_orderby":"","wc_settings_prdctfltr_cat_order":"ASC","wc_settings_prdctfltr_cat_limit":"0","wc_settings_prdctfltr_cat_hierarchy":"no","wc_settings_prdctfltr_cat_mode":"showall","wc_settings_prdctfltr_cat_hierarchy_mode":"no","wc_settings_prdctfltr_cat_multi":"no","wc_settings_prdctfltr_cat_relation":"IN","wc_settings_prdctfltr_cat_selection":"no","wc_settings_prdctfltr_cat_adoptive":"no","wc_settings_prdctfltr_cat_none":"no","wc_settings_prdctfltr_cat_term_customization":"","wc_settings_prdctfltr_pa_color_title":"","wc_settings_prdctfltr_pa_color_description":"","wc_settings_prdctfltr_include_pa_color":null,"wc_settings_prdctfltr_pa_color":"pf_attr_text","wc_settings_prdctfltr_pa_color_orderby":"","wc_settings_prdctfltr_pa_color_order":"ASC","wc_settings_prdctfltr_pa_color_limit":"0","wc_settings_prdctfltr_pa_color_hierarchy":"no","wc_settings_prdctfltr_pa_color_hierarchy_mode":"no","wc_settings_prdctfltr_pa_color_multi":"no","wc_settings_prdctfltr_pa_color_relation":"IN","wc_settings_prdctfltr_pa_color_selection":"no","wc_settings_prdctfltr_pa_color_adoptive":"no","wc_settings_prdctfltr_pa_color_none":"no","wc_settings_prdctfltr_pa_color_term_customization":"wc_settings_prdctfltr_term_customization_5b23787abf535","wc_settings_prdctfltr_pa_size_title":"","wc_settings_prdctfltr_pa_size_description":"","wc_settings_prdctfltr_include_pa_size":null,"wc_settings_prdctfltr_pa_size":"pf_attr_text","wc_settings_prdctfltr_pa_size_orderby":"","wc_settings_prdctfltr_pa_size_order":"ASC","wc_settings_prdctfltr_pa_size_limit":"0","wc_settings_prdctfltr_pa_size_hierarchy":"no","wc_settings_prdctfltr_pa_size_hierarchy_mode":"no","wc_settings_prdctfltr_pa_size_multi":"no","wc_settings_prdctfltr_pa_size_relation":"IN","wc_settings_prdctfltr_pa_size_selection":"no","wc_settings_prdctfltr_pa_size_adoptive":"no","wc_settings_prdctfltr_pa_size_none":"no","wc_settings_prdctfltr_pa_size_term_customization":"wc_settings_prdctfltr_term_customization_5b237d18cede3","wc_settings_prdctfltr_price_title":"Price","wc_settings_prdctfltr_price_description":"","wc_settings_prdctfltr_price_range":"100","wc_settings_prdctfltr_price_range_add":"100","wc_settings_prdctfltr_price_range_limit":"4","wc_settings_prdctfltr_price_none":"no","wc_settings_prdctfltr_price_term_customization":"","wc_settings_prdctfltr_price_filter_customization":"","wc_settings_prdctfltr_active_filters":["sort","cat","pa_color","pa_size","price"],"wc_settings_prdctfltr_perpage_title":"","wc_settings_prdctfltr_perpage_description":"","wc_settings_prdctfltr_perpage_label":"","wc_settings_prdctfltr_perpage_range":"20","wc_settings_prdctfltr_perpage_range_limit":"5","wc_settings_prdctfltr_perpage_term_customization":"","wc_settings_prdctfltr_perpage_filter_customization":"","wc_settings_prdctfltr_vendor_title":"","wc_settings_prdctfltr_vendor_description":"","wc_settings_prdctfltr_include_vendor":null,"wc_settings_prdctfltr_vendor_term_customization":"","wc_settings_prdctfltr_instock_title":"","wc_settings_prdctfltr_instock_description":"","wc_settings_prdctfltr_instock_term_customization":"","wc_settings_prdctfltr_search_title":"","wc_settings_prdctfltr_search_description":"","wc_settings_prdctfltr_search_placeholder":"","wc_settings_prdctfltr_tag_title":"","wc_settings_prdctfltr_tag_description":"","wc_settings_prdctfltr_include_tags":null,"wc_settings_prdctfltr_tag_orderby":"","wc_settings_prdctfltr_tag_order":"ASC","wc_settings_prdctfltr_tag_limit":"0","wc_settings_prdctfltr_tag_multi":"no","wc_settings_prdctfltr_tag_relation":"IN","wc_settings_prdctfltr_tag_selection":"no","wc_settings_prdctfltr_tag_adoptive":"no","wc_settings_prdctfltr_tag_none":"no","wc_settings_prdctfltr_tag_term_customization":"","wc_settings_prdctfltr_custom_tax_title":"","wc_settings_prdctfltr_custom_tax_description":"","wc_settings_prdctfltr_include_chars":null,"wc_settings_prdctfltr_custom_tax_orderby":"","wc_settings_prdctfltr_custom_tax_order":"ASC","wc_settings_prdctfltr_custom_tax_limit":"0","wc_settings_prdctfltr_chars_multi":"no","wc_settings_prdctfltr_custom_tax_relation":"IN","wc_settings_prdctfltr_chars_selection":"no","wc_settings_prdctfltr_chars_adoptive":"no","wc_settings_prdctfltr_chars_none":"no","wc_settings_prdctfltr_chars_term_customization":""}' ),
						'autoload'     => 'no',
					),
					'ciloe_already_imported_prdctfltr_settings'                => array(
						'option_value' => 'yes',
						'autoload'     => 'yes',
					),
					'widget_prdctfltr'                                         => array(
						'option_value' => unserialize( 'a:3:{i:2;a:4:{s:6:"preset";s:10:"pf_default";s:8:"template";s:7:"default";s:17:"disable_overrides";s:2:"no";s:13:"widget_action";s:0:"";}i:3;a:4:{s:6:"preset";s:10:"pf_default";s:8:"template";s:7:"default";s:17:"disable_overrides";s:2:"no";s:13:"widget_action";s:0:"";}s:12:"_multiwidget";i:1;}' ),
						'autoload'     => 'yes',
					),
					'wc_settings_prdctfltr_filter_customization_5b6bf1a90e65b' => array(
						'option_value' => unserialize( 'a:2:{s:6:"filter";s:5:"price";s:8:"settings";a:6:{s:5:"0-100";s:17:"£0.00 - £100.00";s:7:"100-200";s:19:"£100.00 - £200.00";s:7:"200-300";s:19:"£200.00 - £300.00";s:7:"300-400";s:19:"£300.00 - £400.00";s:7:"400-500";s:19:"£400.00 - £500.00";s:7:"500-600";s:9:"£500.00+";}}' ),
						'autoload'     => 'no',
					)
				
				);
				
				foreach ( $prdctfltr_settings as $option_key => $prdctfltr_setting ) {
					$autoload = isset( $prdctfltr_setting['autoload'] ) ? $prdctfltr_setting['autoload'] : 'yes';
					update_option( $option_key, $prdctfltr_setting['option_value'], $autoload );
				}
				
				update_option( 'ciloe_already_imported_prdctfltr_settings', 'yes' );
			}
		}
		
		public function update_sales_popup_settings() {
			// Check class exists
			if ( class_exists( 'famiSalesPopup' ) ) {
				// Import Sales Popup
				$already_imported_sales_popup = get_option( 'ciloe_already_imported_sales_popup', 'no' ) == 'yes';
				if ( ! $already_imported_sales_popup ) {
					return;
				}
				
				// CILOE_IMPORTER_URI
				if ( file_exists( CILOE_IMPORTER_DIR . 'data-sample/sale-popup-data.txt' ) ) {
					$sales_popup_response_data = wp_remote_get( CILOE_IMPORTER_URI . 'data-sample/sale-popup-data.txt' );
					if ( is_array( $sales_popup_response_data ) ) {
						$sales_popup_data = $sales_popup_response_data['body'];
						if ( maybe_serialize( $sales_popup_data ) ) {
							$sales_popup_data = unserialize( $sales_popup_data );
							update_option( 'famisp_all_settings', $sales_popup_data );
						}
					}
					update_option( 'ciloe_already_imported_sales_popup', 'yes' );
				}
			}
		}
	}
	
	new CILOE_IMPORTER();
}