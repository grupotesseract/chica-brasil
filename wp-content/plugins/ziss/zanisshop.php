<?php
/*
 * Plugin Name: Ziss - WooCommerce Product Pinner
 * Plugin URI: http://zanisshop.zanthemes.net/
 * Description: Excellent product pinning for WooCommerce with social shop supported
 * Author: Le Manh Linh
 * Version: 1.1
 * Author URI: http://zanisshop.zanthemes.net/
 * Text Domain: ziss
 * Domain Path: languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

define( 'ZISS_VERSION', '1.1' );
define( 'ZISS_BASE_URL', trailingslashit( plugins_url( 'ziss' ) ) );
define( 'ZISS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ZISS_CLASSES', ZISS_DIR_PATH . '/classes/' );
define( 'ZISS_CLASSES_URL', ZISS_BASE_URL . '/classes/' );
define( 'ZISS_CORE', ZISS_DIR_PATH . '/core/' );
define( 'ZISS_VENDORS', ZISS_DIR_PATH . '/assets/vendors/' );
define( 'ZISS_VENDORS_URL', ZISS_BASE_URL . 'assets/vendors/' );
define( 'ZISS_CSS_URL', ZISS_BASE_URL . 'assets/css/' );
define( 'ZISS_SKINS_URL', ZISS_CSS_URL . 'menu-skins/' );
define( 'ZISS_JS_URL', ZISS_BASE_URL . 'assets/js/' );
define( 'ZISS_IMG_URL', ZISS_BASE_URL . 'assets/images/' );


/**
 * Load Redux Framework
 */
if ( ! class_exists( 'ReduxFramework' ) && file_exists( ZISS_DIR_PATH . 'reduxframework/ReduxCore/framework.php' ) ) {
	require_once( ZISS_DIR_PATH . 'reduxframework/ReduxCore/framework.php' );
}

/**
 * Load plugin textdomain
 */
if ( ! function_exists( 'ziss_load_textdomain' ) ) {
	function ziss_load_textdomain() {
		load_plugin_textdomain( 'ziss', false, ZISS_DIR_PATH . 'languages' );
	}
	
	add_action( 'plugins_loaded', 'ziss_load_textdomain' );
}

function ziss_add_admin_caps() {
	// gets the administrator role
	$role = get_role( 'administrator' );
	
	if ( class_exists( 'Vc_Manager' ) ) {
		if ( isset( $role->capabilities['vc_access_rules_post_types'] ) ) {
			if ( $role->capabilities['vc_access_rules_post_types'] !== 'custom' ) {
				$role->add_cap( 'vc_access_rules_post_types', 'custom' );
				$role->add_cap( 'vc_access_rules_post_types/zaniss', true );
			}
		}
	}
	
	if ( class_exists( 'KingComposer' ) ) {
		global $kc;
		$kc->add_content_type( 'ziss' );
	}
}

add_action( 'init', 'ziss_add_admin_caps' );


/**
 * Require file
 **/
function ziss_require_once( $file_path ) {
	
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
}

if ( ! function_exists( 'ziss_load_options' ) ) {
	function ziss_load_options() {
		ziss_require_once( ZISS_CORE . 'ziss-options.php' );
	}
}
add_action( 'init', 'ziss_load_options', 99 );

if ( ! function_exists( 'ziss_fonts_url' ) ) {
	/**
	 * Register Google fonts for Twenty Fifteen.
	 *
	 * @since Lucky Shop 1.0
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function ziss_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';
		
		/*
		 * Translators: If there are characters in your language that are not supported
		 * by Open Sans, translate this to 'off'. Do not translate into your own language.
		 */
		if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'ziss' ) ) {
			$fonts[] = 'Open Sans:400,400italic,700,700italic';
		}
		
		/*
		 * Translators: To add an additional character subset specific to your language,
		 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'ziss' );
		
		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' == $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'devanagari' == $subset ) {
			$subsets .= ',devanagari';
		} elseif ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}
		
		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family' => urlencode( implode( '|', $fonts ) ),
					'subset' => urlencode( $subsets ),
				), 'https://fonts.googleapis.com/css'
			);
		}
		
		return $fonts_url;
	}
};

function ziss_enqueue_script() {
	global $ziss;
	
	$ziss_localize     = array(
		'has_woocommerce' => 'no',
		'mini_cart_html'  => '',
		'cart_count'      => 0,
		'cart_url'        => '#'
	);
	$load_font_awesome = isset( $ziss['load_font_awesome'] ) ? $ziss['load_font_awesome'] == 1 : true;
	if ( $load_font_awesome ) {
		wp_register_style( 'font-awesome.min', ZISS_VENDORS_URL . 'font-awesome/css/font-awesome.min.css', false, ZISS_VERSION, 'all' );
		wp_enqueue_style( 'font-awesome.min' );
	}
	
	wp_register_style( 'ziss-frontend', ZISS_CSS_URL . 'frontend.css', false, ZISS_VERSION, 'all' );
	wp_enqueue_style( 'ziss-frontend' );
	
	$enable_custom_scroll = isset( $ziss['enable_custom_scroll'] ) ? $ziss['enable_custom_scroll'] == 1 : false;
	if ( $enable_custom_scroll ) {
		wp_register_script( 'enscroll-0.6.2.min', ZISS_VENDORS_URL . 'enscroll/enscroll-0.6.2.min.js', array( 'jquery' ), '0.6.2', true );
		wp_enqueue_script( 'enscroll-0.6.2.min' );
	}
	
	wp_register_script( 'ziss-frontend', ZISS_JS_URL . 'frontend.js', array( 'jquery' ), ZISS_VERSION, true );
	
	//wp_localize_script( 'ziss-frontend', 'ziss_ajaxurl', get_admin_url() . '/admin-ajax.php' );
	$ziss_localize = ziss_localize_frontend();
	wp_localize_script( 'ziss-frontend', 'ziss', $ziss_localize );
	wp_enqueue_script( 'ziss-frontend' );
	wp_localize_script( 'ziss-frontend', 'ziss', $ziss_localize );
	wp_enqueue_script( 'ziss-frontend' );
	
	$script = '';
	if ( isset( $ziss['custom_js_code'] ) ) {
		$script .= stripslashes( $ziss['custom_js_code'] );
	}
	
	if ( trim( $script ) != '' ) {
		$custom_js = 'jQuery(document).ready(function($){
		                    ' . stripslashes( $script ) . '
		                });';
		wp_add_inline_script( 'ziss-frontend', $custom_js );
	}
	
}

add_action( 'wp_enqueue_scripts', 'ziss_enqueue_script', 20 );

function ziss_core_enqueue_admin_script() {
	global $ziss, $pagenow, $post_type;
	
	wp_register_style( 'ziss-backend', ZISS_CSS_URL . 'backend.css', false, ZISS_VERSION, 'all' );
	wp_enqueue_style( 'ziss-backend' );
	
	$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
	
	if ( $page == 'ziss_options' ) {
		wp_register_style( 'ziss-redux', ZISS_CSS_URL . 'redux.css', false, ZISS_VERSION, 'all' );
		wp_enqueue_style( 'ziss-redux' );
	}
	
	if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
		if ( 'ziss' == $post_type ) {
			
			wp_register_style( 'font-awesome.min', ZISS_VENDORS_URL . 'font-awesome/css/font-awesome.min.css', false, ZISS_VERSION, 'all' );
			wp_enqueue_style( 'font-awesome.min' );
			
			wp_register_style( 'ziss-bootstrap-admin', ZISS_CSS_URL . 'bootstrap-admin.css', false, ZISS_VERSION, 'all' );
			wp_enqueue_style( 'ziss-bootstrap-admin' );
			
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			
			wp_register_script( 'ziss-backend', ZISS_JS_URL . 'backend.js', array( 'jquery' ), ZISS_VERSION, true );
			$ziss_localize = ziss_localize_backend();
			wp_localize_script( 'ziss-backend', 'ziss', $ziss_localize );
			wp_enqueue_script( 'ziss-backend' );
			
		}
	}
}

add_action( 'admin_enqueue_scripts', 'ziss_core_enqueue_admin_script', 99 );


/**
 * @return array
 */
function ziss_localize_backend() {
	// hotspot_type is not used yet
	
	$ziss_localize = array(
		'text' => array(),
		'html' => array(
			'popup'             => '<div class="ziss-popup-wrap">
									<div class="ziss-popup-inner">
										<div class="ziss-popup-content">
											<div class="ziss-popup-body">
												<a href="#" class="ziss-close-popup"><i class="fa fa-times"></i></a>
												<div class="ziss-popup-body-left">
												</div>
												<div class="ziss-popup-body-right">
												</div>
											</div>
											<div class="ziss-single-shortcode-wrap">
												<label>' . esc_html__( 'Shortcode for single image:', 'ziss' ) . '</label>
												<div class="ziss-single-shortcode"></div>
											</div>
										</div>
									</div>
									<div class="ziss-popup-backdrop"></div>
								</div>',
			'hotspot_type'      => '<label>' . esc_html__( 'Type', 'ziss' ) . '</label>
								<select class="hotspot-type-select">
									<option value="woocommerce">' . esc_html__( 'WooCommerce', 'ziss' ) . '</option>
									<option value="custom">' . esc_html__( 'Custom', 'ziss' ) . '</option>
								</select>',
			'select_product'    => '<div class="add-select-product-wrap">
										<label class="select-product-lb">' . esc_html__( 'Select Product', 'ziss' ) . '</label>
										' . ziss_products_select( 0, 'ziss-select', false ) . '
									</div>',
			'right_popup_title' => '<h3>' . esc_html__( 'Hotspot', 'ziss' ) . '</h3>'
		)
	);
	
	return apply_filters( 'ziss_localize_backend', $ziss_localize );
}

/**
 * @return array
 */
function ziss_localize_frontend() {
	global $ziss;
	
	$enable_custom_scroll = isset( $ziss['enable_custom_scroll'] ) ? $ziss['enable_custom_scroll'] == 1 : false;
	$custom_scroll_class  = $enable_custom_scroll ? 'ziss-custom-scroll' : '';
	
	$ziss_localize = array(
		'text' => array(),
		'html' => array(
			'popup'             => '<div class="ziss-popup-wrap">
								<div class="ziss-popup-inner">
									<div class="ziss-popup-content">
										<div class="ziss-popup-body">
											<a href="#" class="ziss-close-popup"><i class="fa fa-times"></i></a>
											<div class="ziss-popup-body-left">
											</div>
											<div class="ziss-popup-body-right ' . $custom_scroll_class . '">
											</div>
										</div>
										<a href="#" class="ziss-popup-nav ziss-popup-nav-prev"><i class="fa fa-angle-left"></i></a>
										<a href="#" class="ziss-popup-nav ziss-popup-nav-next"><i class="fa fa-angle-right"></i></a>
									</div>
								</div>
								<div class="ziss-popup-backdrop"></div>
							</div>',
			'popup_product_tmp' => '<div data-product_id="{{product_id}}" class="ziss-product-wrap ziss-product-wrap-{{product_id}}">
										<div class="ziss-product-thumb-wrap"><img class="ziss-product-thumb" width="{{thumb_width}}" height="{{thumb_height}}" src="{{thumb_src}}" alt="" /></div>
										<h4 class="ziss-product-title">{{title}}</h4>
										<div class="ziss-product-info-wrap">
											<div class="ziss-price-wrap">{{price_html}}</div>
											<div class="ziss-rating-wrap">{{rating_html}}</div>
											<div class="ziss-add-to-cart-wrap">{{add_to_cart_html}}</div>
										</div>
									</div>'
		)
	);
	
	return apply_filters( 'ziss_localize_frontend', $ziss_localize );
}

/**
 * Load Post type metaboxes
 */
//include_once ZISS_CORE . 'metaboxes/post-type-metaboxes/global-metaboxes.php';

//$postTypeMetaboxesArgs = array( 'ziss' );
//if ( ! empty( $postTypeMetaboxesArgs ) ):
//	foreach ( $postTypeMetaboxesArgs as $post_type ):
//		$post_type = sanitize_key( $post_type );
//		$filePath  = ZISS_CORE . 'metaboxes/post-type-metaboxes/metaboxes-' . $post_type . '.php';
//		if ( file_exists( $filePath ) ):
//			include_once $filePath;
//		endif;
//	endforeach;
//endif;

ziss_require_once( ZISS_CORE . 'post-types/post-zaniss.php' );

/*
 * Load Ziss functions
 */
ziss_require_once( ZISS_CORE . 'functions.php' );

/**
 * Load Ziss shortcodes
 */
ziss_require_once( ZISS_CORE . 'shortcodes/shortcodes.php' );