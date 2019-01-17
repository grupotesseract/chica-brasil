<?php

if ( ! class_exists( 'Ciloe_Shortcode_Product' ) ) {
	class Ciloe_Shortcode_Product extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'product';
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		
		public static function generate_css( $atts ) {
			extract( $atts );
			$css = '';
			
			return $css;
		}
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_product', $atts ) : $atts;
			
			extract( $atts );
			$css_class   = array( 'ciloe-product ciloe-single-product' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['product_custom_id'];
			$css_class[] = 'style-' . $atts['product_style'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			
			$img_html          = '';
			$product_info_html = '';
			
			$img_id = intval( $img_id );
			
			if ( $img_id > 0 ) {
				$img_size_x = 440;
				$img_size_y = 363;
				$img_size   = $atts['img_size'];
				if ( trim( $img_size ) != '' ) {
					$img_size = explode( 'x', $img_size );
				}
				$img_size_x = isset( $img_size[0] ) ? max( 0, intval( $img_size[0] ) ) : $img_size_x;
				$img_size_y = isset( $img_size[1] ) ? max( 0, intval( $img_size[1] ) ) : $img_size_y;
				$img        = ciloe_toolkit_resize_image( $img_id, null, $img_size_x, $img_size_y, true, true, false );
				$img_html   = '<div class="image-wrap">' . ciloe_toolkit_img_output( $img ) . '</div>';
			}
			
			$product_id = intval( $ids );
			if ( $product_id > 0 && function_exists( 'wc_get_product' ) ) {
				$product = wc_get_product( $product_id );
				if ( $product ) {
					$product_title_html      = '<h3 class="product-title">' . get_the_title( $product_id ) . '</h3>';
					$product_price_html      = '<div class="product-price-wrap"><div class="product-price-content">' . $product->get_price_html() . '</div></div>';
					$shop_now_link_html      = '<a href="' . esc_url( get_permalink( $product_id ) ) . '" class="shop-now-link">' . esc_html__( 'Shop Now', 'ciloe-toolkit' ) . '</a>';
					$product_short_desc_html = '';
					$flash_text_html         = '';
					$short_desc              = apply_filters( 'woocommerce_short_description', $product->get_short_description() );
					if ( trim( $short_desc ) != '' ) {
						$product_short_desc_html = '<div class="short-desc">' . $short_desc . '</div>';
					}
					if ( trim( $flash_text ) != '' ) {
						$flash_text_html = '<span class="flash-text" style="background-color: ' . esc_attr( $flash_text_bg_color ) . ';">' . esc_html( $flash_text ) . '</span>';
					}
					$product_info_html = '<div class="container"><div class="product-info-wrap">' . $product_title_html . $product_short_desc_html . $product_price_html . $shop_now_link_html . $flash_text_html . '</div></div>';
				}
			}
			
			$html = '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '">' . $img_html . $product_info_html . '</div>';
			
			return apply_filters( 'Ciloe_Shortcode_products', force_balance_tags( $html ), $atts, $content );
		}
	}
}