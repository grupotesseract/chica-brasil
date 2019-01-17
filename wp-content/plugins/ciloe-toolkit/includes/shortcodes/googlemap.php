<?php
if ( ! class_exists( 'Ciloe_Shortcode_Googlemap' ) ) {
	class Ciloe_Shortcode_Googlemap extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'googlemap';
		
		public static function generate_css( $atts ) {
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( trim( $atts['map_height'] ) != '' ) {
				$map_height = $atts['map_height'];
			} else {
				$map_height = '555';
			}
			$css .= 'div#' . $atts['googlemap_custom_id'] . '{ min-height:' . $map_height . 'px;} ';
			
			return $css;
		}
		
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_googlemap', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'ciloe-google-maps' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['googlemap_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
			}
			$icon_url = wp_get_attachment_image_src( $atts['pin_icon'], 'full' );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                 id="<?php echo esc_attr( $atts['googlemap_custom_id'] ); ?>"
                 data-hue=""
                 data-lightness="1"
                 data-map-style="<?php echo esc_attr( $atts['info_content'] ); ?>"
                 data-saturation="-99"
                 data-title_maps="<?php echo esc_html( $atts['title'] ); ?>"
                 data-phone="<?php echo esc_html( $atts['phone'] ); ?>"
                 data-email="<?php echo esc_html( $atts['email'] ); ?>"
                 data-address="<?php echo esc_html( $atts['address'] ); ?>"
                 data-longitude="<?php echo esc_html( $atts['longitude'] ); ?>"
                 data-latitude="<?php echo esc_html( $atts['latitude'] ); ?>"
                 data-pin-icon="<?php echo esc_url( $icon_url[0] ); ?>"
                 data-zoom="<?php echo esc_html( $atts['zoom'] ); ?>"
                 data-map-type="<?php echo esc_attr( $atts['map_type'] ); ?>">
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'ciloe_shortcode_googlemap', force_balance_tags( $html ), $atts, $content );
		}
	}
}