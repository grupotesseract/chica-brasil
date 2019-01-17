<?php
/**
 * Plugin Name: Fami Sales Popup
 * Plugin URI: https://themeforest.net/user/fami_themes
 * Description: <strong>Fami Sales Popup</strong> is an influential selling tool which helps to boost your sales. Built with the concept of social proof, the app displays purchase activities on your store via real-time notification popups. When customers know what other people are buying from your store, it creates a positive influence and motivates them to buy your products.
 * Author: Fami Themes
 * Author URI: https://themeforest.net/user/fami_themes
 * Version: 1.0.0
 * Text Domain: famirsb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'famiSalesPopup' ) ) {
	
	class  famiSalesPopup {
		
		public         $version = '1.0.0';
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof famiSalesPopup ) ) {
				
				self::$instance = new famiSalesPopup;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
				
			}
			
			return self::$instance;
		}
		
		public function after_setup_theme() {
			
		}
		
		public function setup_constants() {
			$this->define( 'FAMISP_VERSION', $this->version );
			$this->define( 'FAMISP_URI', plugin_dir_url( __FILE__ ) );
			$this->define( 'FAMISP_PATH', plugin_dir_path( __FILE__ ) );
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
			require_once FAMISP_PATH . 'includes/menu-scripts-styles.php';
			require_once FAMISP_PATH . 'includes/helpers.php';
			require_once FAMISP_PATH . 'includes/load-products-data.php';
			require_once FAMISP_PATH . 'includes/backend.php';
			require_once FAMISP_PATH . 'includes/frontend.php';
		}
		
		public function load_textdomain() {
			load_plugin_textdomain( 'famirsb', false, FAMISP_URI . 'languages' );
		}
		
	}
}

if ( ! function_exists( 'famirsb_init' ) ) {
	function famirsb_init() {
		return famiSalesPopup::instance();
	}
	
	famirsb_init();
}