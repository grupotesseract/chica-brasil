<?php

if ( ! class_exists( 'Ciloe_Shortcode_Testimonials' ) ) {
	class Ciloe_Shortcode_Testimonials extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'testimonials';
		
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		
		public static function generate_css( $atts ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_testimonials', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			
			return $css;
		}
		
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_testimonials', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			$social_html = '';
			$css_class   = array( 'ciloe-testimonials' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['style'];
			$css_class[] = $atts['testimonials_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			$img_size_x         = 260;
			$img_size_y         = 260;
			$testimonials_items = vc_param_group_parse_atts( $atts['testimonial_item'] );
			$owl_settings       = $this->generate_carousel_data_attributes( '', $atts );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="owl-carousel nav-center equal-container better-height" <?php echo $owl_settings; ?>>
					<?php foreach ( $testimonials_items as $testimonials_item ): ?>
						<?php
						$img_size = $testimonials_item['img_size'];
						if ( trim( $img_size ) != '' ) {
							$img_size = explode( 'x', $img_size );
						}
						$img_size_x          = isset( $img_size[0] ) ? max( 0, intval( $img_size[0] ) ) : $img_size_x;
						$img_size_y          = isset( $img_size[1] ) ? max( 0, intval( $img_size[1] ) ) : $img_size_y;
						$testimonials_avatar = ciloe_resize_image( $testimonials_item['avatar'], null, $img_size_x, $img_size_y, true, true, false );
						$stars               = isset( $testimonials_item['stars'] ) ? intval( $testimonials_item['stars'] ) : 5;
						?>
                        <div class="testimonial-item equal-elem">
                        	<div class="testimonial-item-inner">
	                        	<span class="quote-icon"></span>
	                            <div class="description">
									<?php echo balanceTags( $testimonials_item['description'] ); ?>
	                            </div>
	                            <div class="testimonial-info-wrap">
									<?php echo ciloe_toolkit_img_output( $testimonials_avatar, esc_attr( $testimonials_item['name'] ) ); ?>
	                                <div class="member-info">
	                                    <h4><?php echo esc_html( $testimonials_item['name'] ); ?></h4>
	                                    <span><?php echo esc_html( $testimonials_item['position'] ); ?></span>
	                                </div>
	                            </div>
	                        </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'ciloe_toolkit_shortcode_testimonials', force_balance_tags( $html ), $atts, $content );
		}
	}
}