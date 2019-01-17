<?php
/**
 * Plugin Name: Fami Buy Together
 * Plugin URI: https://themeforest.net/user/fami_themes
 * Description: A complete product grouping toolkit for creating customizable product kits and assembled products
 * Author: Fami Themes
 * Author URI: https://themeforest.net/user/fami_themes
 * Version: 1.0.2
 * Text Domain: famibt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'famiBuyTogether' ) ) {
	
	class  famiBuyTogether {
		
		public         $version = '1.0.2';
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiBuyTogether ) ) {
				
				self::$instance = new famiBuyTogether;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
				
				// Add to selector
				// add_filter( 'product_type_selector', array( self::$instance, 'product_type_selector' ) );
				
				// Product data tabs
				add_filter( 'woocommerce_product_data_tabs', array( self::$instance, 'product_data_tabs' ) );
				
				// Product filters
				// add_filter( 'woocommerce_product_filters', array( self::$instance, 'product_filters' ) );
				
				// Product data panels
				add_action( 'woocommerce_product_data_panels', array( self::$instance, 'product_data_panels' ) );
				add_action( 'woocommerce_process_product_meta_simple', array(
					self::$instance,
					'process_product_meta_famibt'
				) );
				
			}
			
			return self::$instance;
		}
		
		public function after_setup_theme() {
		
		}
		
		public function setup_constants() {
			$this->define( 'FAMIBT_VERSION', $this->version );
			$this->define( 'FAMIBT_URI', plugin_dir_url( __FILE__ ) );
			$this->define( 'FAMIBT_PATH', plugin_dir_path( __FILE__ ) );
		}
		
		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		
		public function includes() {
			require_once FAMIBT_PATH . 'includes/menu-scripts-styles.php';
			require_once FAMIBT_PATH . 'includes/helpers.php';
			require_once FAMIBT_PATH . 'includes/load-products-data.php';
			require_once FAMIBT_PATH . 'includes/backend.php';
			require_once FAMIBT_PATH . 'includes/frontend.php';
		}
		
		public function load_textdomain() {
			load_plugin_textdomain( 'famibt', false, FAMIBT_URI . 'languages' );
		}
		
		public function product_data_tabs( $tabs ) {
			$tabs['famibt'] = array(
				'label'  => esc_html__( 'Buy Together', 'famibt' ),
				'target' => 'famibt_settings',
				'class'  => array( 'show_if_simple' ),
			);
			
			return $tabs;
		}
		
		public function product_data_panels() {
			global $post;
			$post_id                    = $post->ID;
			$famibt_enable_buy_together = get_post_meta( $post_id, 'famibt_enable_buy_together', true ) == 'yes';
			
			?>
            <div id='famibt_settings' class='panel woocommerce_options_panel famibt-table-wrap'>

                <table class="famibt-table">
                    <tr>
                        <td width="250"><?php esc_html_e( 'Enable Buy Together', 'famibt' ); ?></td>
                        <td>
                            <div class="famibt-inner-wrapper">
                                <label class="famibt-switch">
                                    <input id="famibt_enable_buy_together" name="famibt_enable_buy_together"
                                           type="checkbox" <?php echo( $famibt_enable_buy_together ? 'checked' : '' ); ?> >
                                    <span class="famibt-slider round"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250"><?php esc_html_e( 'Search', 'famibt' ); ?></td>
                        <td>
                            <div class="famibt-inner-wrapper">
                                <div class="famibt-search-product-wrapper">
                                    <input type="text" id="famibt_keyword"
                                           placeholder="<?php esc_html_e( 'Type any keyword to search', 'famibt' ); ?>"/>
                                    <div id="famibt-results" class="famibt-results"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250"><?php esc_html_e( 'Selected', 'famibt' ); ?></td>
                        <td>
                            <div class="famibt-inner-wrapper">
                                <input type="hidden" id="famibt_ids" class="famibt_ids" name="famibt_ids"
                                       value="<?php echo get_post_meta( $post_id, 'famibt_ids', true ); ?>"
                                       readonly/>
                                <div class="famibt-selected-products-list-wrap">
                                    <div class="famibt-selected-products-list famibt-sortable">
										<?php
										$famibt_total_price = 0;
										if ( get_post_meta( $post_id, 'famibt_ids', true ) ) {
											$famibt_ids = get_post_meta( $post_id, 'famibt_ids', true );
											if ( trim( $famibt_ids ) != '' ) {
												$famibt_ids = explode( ',', $famibt_ids );
												if ( is_array( $famibt_ids ) ) {
													foreach ( $famibt_ids as $famibt_id ) {
														$famibt_id      = absint( $famibt_id );
														$famibt_product = wc_get_product( $famibt_id );
														if ( ! $famibt_product || ! $famibt_product->is_type( 'simple' ) ) {
															continue;
														}
														$min_price          = $famibt_product->get_price();
														$max_price          = $min_price;
														$famibt_total_price += $famibt_product->get_price();
														if ( $famibt_product->is_type( 'variable' ) ) {
															$min_price = $famibt_product->get_variation_price( 'min' );
															$max_price = $famibt_product->get_variation_price( 'max' );
														}
														?>
                                                        <div class="selected-product-item"
                                                             data-product_id="<?php echo esc_attr( $famibt_id ); ?>"
                                                             data-min_price="<?php echo $min_price; ?>"
                                                             data-max_price="<?php echo $max_price; ?>">
                                                            <div class="product-inner">
                                                                <div class="post-thumb">
																	<?php
																	$image = famibt_resize_image( get_post_thumbnail_id( $famibt_id ), null, 60, 60, true, true, false );
																	?>
                                                                    <img width="<?php echo esc_attr( $image['width'] ); ?>"
                                                                         height="<?php echo esc_attr( $image['height'] ); ?>"
                                                                         class="attachment-post-thumbnail wp-post-image"
                                                                         src="<?php echo esc_url( $image['url'] ); ?>"
                                                                         alt="<?php echo esc_attr( $famibt_product->get_name() ); ?>"/>
                                                                </div>
                                                                <div class="product-info">
                                                                    <span class="product-title"><?php echo $famibt_product->get_name(); ?></span>
                                                                    <span>(<?php echo '#' . $famibt_id . ' - ' . $famibt_product->get_price_html(); ?>
                                                                        )</span>
                                                                </div>
                                                            </div>
                                                            <a href="#" class="remove-btn" title="Remove">x</a>
                                                        </div>
														<?php
													}
												}
											}
										}
										?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250"><?php esc_html_e( 'Buy Together Title', 'famibt' ); ?></td>
                        <td>
                            <div class="famibt-inner-wrapper">
                                <input id="famibt_title" name="famibt_title" type="text"
                                       placeholder="<?php esc_attr_e( 'Buy Together Title', 'famibt' ); ?>"
                                       value="<?php echo esc_attr( stripslashes( get_post_meta( $post_id, 'famibt_title', true ) ) ); ?>"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250"><?php esc_html_e( 'Short Description', 'famibt' ); ?>
                            <br><em><?php esc_attr_e( 'Display after "Buy Together Title"', 'famibt' ); ?></em></td>
                        <td>
                            <div class="famibt-inner-wrapper">
								<textarea id="famibt_short_desc" name="famibt_short_desc"
                                          placeholder="<?php esc_html_e( 'The text display after title and before list of products', 'famibt' ); ?>"><?php echo stripslashes( get_post_meta( $post_id, 'famibt_short_desc', true ) ); ?></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250"><?php esc_html_e( 'After Text', 'famibt' ); ?>
                            <br><em><?php esc_attr_e( 'Display after list of products', 'famibt' ); ?></em></td>
                        <td>
                            <div class="famibt-inner-wrapper">
								<textarea id="famibt_after_text" name="famibt_after_text"
                                          placeholder="<?php esc_html_e( 'The text display after list of products', 'famibt' ); ?>"><?php echo stripslashes( get_post_meta( $post_id, 'famibt_after_text', true ) ); ?></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
			<?php
		}
		
		/*
		 * Save bundle products data
		 */
		public function process_product_meta_famibt( $post_id ) {
			
			if ( isset( $_POST['famibt_ids'] ) ) {
				update_post_meta( $post_id, 'famibt_ids', $_POST['famibt_ids'] );
			}
			
			if ( isset( $_POST['famibt_enable_buy_together'] ) ) {
				update_post_meta( $post_id, 'famibt_enable_buy_together', 'yes' );
			} else {
				update_post_meta( $post_id, 'famibt_enable_buy_together', 'no' );
			}
			
			if ( isset( $_POST['famibt_title'] ) ) {
				update_post_meta( $post_id, 'famibt_title', $_POST['famibt_title'] );
			}
			
			if ( isset( $_POST['famibt_short_desc'] ) ) {
				update_post_meta( $post_id, 'famibt_short_desc', $_POST['famibt_short_desc'] );
			}
			if ( isset( $_POST['famibt_after_text'] ) ) {
				update_post_meta( $post_id, 'famibt_after_text', $_POST['famibt_after_text'] );
			}
			
		}
		
	}
}

if ( ! function_exists( 'famibt_init' ) ) {
	function famibt_init() {
		return famiBuyTogether::instance();
	}
	
	famibt_init();
	// add_action( 'plugins_loaded', 'famibt_init', 10 );
}