<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'famibtMenuScriptsStyles' ) ) {
	class famibtMenuScriptsStyles {
		public $version = '1.0.0';
		
		public function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'famibt_admin_bar_menu' ), 1000 );
			add_action( 'admin_menu', array( $this, 'famibt_menu_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}
		
		public function famibt_admin_bar_menu() {
			global $wp_admin_bar;
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'famibt_option',
				'title' => esc_html__( 'Buy Together', 'famibt' ),
				'href'  => admin_url( 'admin.php?page=famibt' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}
		
		public function famibt_menu_page() {
			// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$menu_args = array(
				array(
					'page_title' => esc_html__( 'Buy Together', 'famibt' ),
					'menu_title' => esc_html__( 'Buy Together', 'famibt' ),
					'cap'        => 'manage_options',
					'menu_slug'  => 'famibt',
					'function'   => array( $this, 'famibt_menu_page_callback' ),
					'icon'       => FAMIBT_URI . 'assets/images/logo.png',
					'parrent'    => '',
					'position'   => 4
				)
			);
			foreach ( $menu_args as $menu_arg ) {
				if ( $menu_arg['parrent'] == '' ) {
					add_menu_page( $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'], $menu_arg['icon'], $menu_arg['position'] );
				} else {
					add_submenu_page( $menu_arg['parrent'], $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'] );
				}
			}
		}
		
		public function famibt_menu_page_callback() {
			$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
			if ( trim( $page ) != '' ) {
				$file_path = FAMIBT_PATH . 'includes/admin-pages/' . $page . '.php';
				if ( file_exists( $file_path ) ) {
					require_once FAMIBT_PATH . 'includes/admin-pages/' . $page . '.php';
				}
			}
		}
		
		function is_url_exist( $url ) {
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_NOBODY, true );
			curl_exec( $ch );
			$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			if ( $code == 200 ) {
				$status = true;
			} else {
				$status = false;
			}
			curl_close( $ch );
			
			return $status;
		}
		
		function admin_scripts( $hook ) {
			wp_enqueue_style( 'famibt-backend', FAMIBT_URI . 'assets/css/backend.css' );
			
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'famibt-backend', FAMIBT_URI . 'assets/js/backend.js', array(), null );
			wp_localize_script( 'famibt-backend', 'famibt',
			                    array(
				                    'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				                    'security'           => wp_create_nonce( 'famibt_backend_nonce' ),
				                    'editing_product_id' => famibt_get_current_editing_product_id()
			                    )
			);
		}
		
		function frontend_scripts( $hook ) {
			wp_enqueue_style( 'bootstrap', FAMIBT_URI . 'assets/vendors/bootstrap/bootstrap.min.css' );
			wp_enqueue_style( 'famibt-frontend', FAMIBT_URI . 'assets/css/frontend.css' );
			
			$enable_lazy = famibt_is_enable_lazy_load();
			if ( $enable_lazy ) {
				wp_enqueue_script( 'lazy', FAMIBT_URI . 'assets/vendors/jquery-lazy/jquery.lazy.min.js', array(), null );
			}
			
			wp_enqueue_script( 'famibt-frontend', FAMIBT_URI . 'assets/js/frontend.js', array(), null );
			
			$all_options              = famibt_get_all_options();
			$add_to_cart_text         = isset( $all_options['famibt_add_to_cart_text'] ) ? $all_options['famibt_add_to_cart_text'] : esc_html__( 'Add All To Cart', 'famibt' );
			$adding_to_cart_text      = isset( $all_options['famibt_adding_to_cart_text'] ) ? $all_options['famibt_adding_to_cart_text'] : esc_html__( 'Adding To Cart...', 'famibt' );
			$view_cart_text           = isset( $all_options['famibt_view_cart_text'] ) ? $all_options['famibt_view_cart_text'] : esc_html__( 'View cart', 'famibt' );
			$no_product_selected_text = isset( $all_options['famibt_no_product_selected_text'] ) ? $all_options['famibt_no_product_selected_text'] : esc_html__( 'You must select at least one product', 'famibt' );
			
			$famibt_args = array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'famibt_nonce' ),
				'text'     => array(
					'for_num_of_items'         => esc_html__( 'For {{number}} item(s)', 'famibt' ),
					'add_to_cart_text'         => $add_to_cart_text,
					'adding_to_cart_text'      => $adding_to_cart_text,
					'view_cart'                => $view_cart_text,
					'no_product_selected_text' => $no_product_selected_text,
					'add_to_cart_success'      => esc_html__( '{{number}} product(s) was successfully added to your cart.', 'famibt' ),
					'add_to_cart_fail_single'  => esc_html__( 'One product is out of stock.', 'famibt' ),
					'add_to_cart_fail_plural'  => esc_html__( '{{number}} products were out of stocks.', 'famibt' )
				)
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$famibt_args['price_format']             = get_woocommerce_price_format();
				$famibt_args['price_decimals']           = wc_get_price_decimals();
				$famibt_args['price_thousand_separator'] = wc_get_price_thousand_separator();
				$famibt_args['price_decimal_separator']  = wc_get_price_decimal_separator();
				$famibt_args['currency_symbol']          = get_woocommerce_currency_symbol();
				$famibt_args['wc_tax_enabled']           = wc_tax_enabled();
				$famibt_args['cart_url']                 = wc_get_cart_url();
				if ( wc_tax_enabled() ) {
					$famibt_args['ex_tax_or_vat'] = WC()->countries->ex_tax_or_vat();
				} else {
					$famibt_args['ex_tax_or_vat'] = '';
				}
			}
			wp_localize_script( 'famibt-frontend', 'famibt', $famibt_args );
		}
	}
	
	new famibtMenuScriptsStyles();
}