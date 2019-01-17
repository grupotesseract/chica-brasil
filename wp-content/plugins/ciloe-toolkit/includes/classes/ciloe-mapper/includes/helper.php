<?php
/**
 * @version    1.0.0
 * @package    Ciloe_Mapper
 */

/**
 * Class that provides common helper functions.
 */
class Ciloe_Mapper_Helper {
	/**
	 * Check Gravityforms attach on product
	 *
	 * @param   number  $product_id
	 *
	 * @return  array
	 */
	public static function check_gravityforms( $product_id ) {
		$active_plugin = ( is_plugin_active( 'gravityforms/gravityforms.php' ) && is_plugin_active( 'woocommerce-gravityforms-product-addons/gravityforms-product-addons.php' ) ) ? true : false;

		if( ! $active_plugin ) {
			return false;
		}

		$gravity_form_data = apply_filters( 'woocommerce_gforms_get_product_form_data', get_post_meta( $product_id, '_gravity_form_data', true ), $product_id );

		if ( ! empty( $gravity_form_data['id'] ) ) {
			global $wpdb;

			$gravity_id = intval( $gravity_form_data['id'] );
			$check_active = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "rg_form WHERE id={$gravity_id} AND is_active=1 AND is_trash=0" );

			if( $check_active == 1 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check YITH WooCommerce Product Add-Ons attach on product
	 *
	 * @param   number  $product_id
	 *
	 * @return  array
	 */
	public static function yith_wc_product_add_ons( $product_id ) {
		$active_plugin = is_plugin_active( 'yith-woocommerce-product-add-ons/init.php' ) ? true : false;

		if( ! $active_plugin ) {
			return false;
		}

		$product = wc_get_product( $product_id );

      	if( is_object( $product ) && $product->get_id() > 0 ) {
			$product_type_list = YITH_WAPO::getAllowedProductTypes();

			if ( in_array( $product->get_type(), $product_type_list ) ) {
				$types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product->get_id() );

                if ( !empty( $types_list ) ) {
               		return true;
                }
			}
      	}

		return false;
	}

	/**
	 * Check YITH WooCommerce Product Add-Ons attach on product
	 *
	 * @param   number  $product_id
	 *
	 * @return  array
	 */
	public static function wc_measurement_price_calculator( $product_id ) {
		$active_plugin = is_plugin_active( 'woocommerce-measurement-price-calculator/woocommerce-measurement-price-calculator.php' ) ? true : false;

		if( ! $active_plugin ) {
			return false;
		}

		$product = wc_get_product( $product_id );

      	if(  WC_Price_Calculator_Product::pricing_calculator_enabled( $product ) ) {
			return true;
      	}

		return false;
	}
}
