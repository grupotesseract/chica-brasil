<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Zoom Magnifier
 * @version 1.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


if ( ! class_exists( 'YITH_WooCommerce_Zoom_Magnifier' ) ) {
	/**
	 * YITH WooCommerce Zoom Magnifier
	 *
	 * @since 1.0.0
	 */
	class YITH_WooCommerce_Zoom_Magnifier {

		/**
		 * Plugin object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $obj = null;

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-zoom-magnifier/';

        /**
         * @var string Plugin official documentation
         */
        protected $_official_documentation = 'https://docs.yithemes.com/yith-woocommerce-zoom-magnifier/';

        /**
         * @var string Plugin panel page
         */
        protected $_panel_page = 'yith_woocommerce_zoom-magnifier_panel';

		/**
		 * Constructor
		 *
		 * @return mixed|YITH_WCMG_Admin|YITH_WCMG_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {
			/** Stop the plugin on mobile devices */
			if ( ( 'yes' != get_option( 'yith_wcmg_enable_mobile' ) ) && wp_is_mobile() ) {
				return;
			}

            add_action( 'wp_ajax_nopriv_yith_wc_zoom_magnifier_get_main_image', array(
                $this,
                'yith_wc_zoom_magnifier_get_main_image_call_back'
            ), 10 );

            add_action( 'wp_ajax_yith_wc_zoom_magnifier_get_main_image', array(
                $this,
                'yith_wc_zoom_magnifier_get_main_image_call_back'
            ), 10 );

			// actions
			add_action( 'init', array( $this, 'init' ) );

			if ( is_admin() && ( ! isset( $_REQUEST['action'] ) || ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] != 'yith_load_product_quick_view' ) ) ) {
				$this->obj = new YITH_WCMG_Admin(  );
			} else {
				$this->obj = new YITH_WCMG_Frontend(  );
			}

			return $this->obj;
		}

        /**
         * Ajax method to retrieve the product main imavge
         *
         * @access public
         * @author Daniel Sanchez Saez
         * @since  1.3.3
         */
        public function yith_wc_zoom_magnifier_get_main_image_call_back(){

		    $product_id = ( isset( $_POST[ 'product_id' ] ) ? $_POST[ 'product_id' ] : 0 );

            $url	= wp_get_attachment_image_src ( get_post_thumbnail_id ( $product_id ), "full" );

            $response = array(
                "url" => $url[ 0 ],
            );
            wp_send_json( $response );

        }

		/**
		 * Init method:
		 *  - default options
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function init() {

			$this->_image_sizes();

            /* === Show Plugin Information === */

            add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWZM_DIR . '/' . basename( YITH_YWZM_FILE ) ), array( $this, 'action_links' ) );

            add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

		}


		/**
		 * Add image sizes
		 *
		 * Init images
		 *
		 * @access protected
		 * @return void
		 * @since 1.0.0
		 */
		protected function _image_sizes() {
			$size   = get_option( 'woocommerce_magnifier_image' );
			$width  = $size['width'];
			$height = $size['height'];
			$crop   = isset( $size['crop'] ) ? true : false;

			add_image_size( 'shop_magnifier', $width, $height, $crop );
		}

        /**
         * Action links
         *
         *
         * @return void
         * @since    1.3.5
         * @author   Daniel Sanchez <daniel.sanchez@yithemes.com>
         */
        public function action_links( $links ) {
            $links = yith_add_action_links( $links, $this->_panel_page, false );
            return $links;
        }
        /**
         * Plugin Row Meta
         *
         *
         * @return void
         * @since    1.3.5
         * @author   Daniel Sanchez <daniel.sanchez@yithemes.com>
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_YWZM_FREE_INIT' ) {
            if ( defined( $init_file ) && constant( $init_file ) == $plugin_file ) {
                $new_row_meta_args['slug'] = YITH_YWZM_SLUG;
            }

            return $new_row_meta_args;
        }

	}
}