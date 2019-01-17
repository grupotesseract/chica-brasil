<?php

if ( !class_exists( 'Ciloe_Shortcode_Slider' ) ) {
	class Ciloe_Shortcode_Slider extends Ciloe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'slider';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			$css = '';
			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_slider', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );


			$css_class   = array( 'ciloe-slider' );
			$owl_class   = array();
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'slider_custom_id' ];
			$css_class[] = $atts[ 'type_show' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
			}

			if ( $atts[ 'type_show' ] == 'list' ) {
				$owl_class[]  = 'list-slider';
				$owl_settings = '';
			}elseif($atts[ 'type_show' ] == 'slick'){
                $owl_class[] = 'slick-slide-wrap';
                $owl_settings = '';
            }else{
                $owl_class[] = 'owl-carousel nav-'.$atts['nav_type'].' control-'.$atts['control_type'];
                $owl_settings = $this->generate_carousel_data_attributes( '', $atts );
            }

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="<?php echo esc_attr( implode( ' ', $owl_class ) ); ?>" <?php echo $owl_settings; ?>>
					<?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ciloe_Shortcode_slider', force_balance_tags( $html ), $atts, $content );
		}
	}
}