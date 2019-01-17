<?php

if ( ! class_exists( 'Ciloe_Shortcode_Newsletter' ) ) {
	class Ciloe_Shortcode_Newsletter extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'newsletter';
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		
		public static function generate_css( $atts ) {
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			
			return $css;
		}
		
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_newsletter', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'ciloe-newsletter' );
			$css_class[] = $atts['style'];
			$css_class[] = $atts['newsletter_type'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['newsletter_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
			}
			
			$allowed_html = array(
				'strong' => array()
			);
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ): ?>
                    <h3 class="newsletter-title"><?php echo esc_html( $atts['title'] ); ?></h3>
				<?php endif; ?>
                <div class="newsletter-content">
					<?php if ( $atts['subtitle'] ): ?>
                        <div class="newsletter-subtitle"><?php echo wp_kses( $atts['subtitle'], $allowed_html ); ?></div>
					<?php endif; ?>
					<?php if ( $atts['description'] ): ?>
                        <div class="newsletter-description"><?php echo wp_kses( $atts['description'], $allowed_html ); ?></div>
					<?php endif; ?>
                    <form class="newsletter-form-wrap">
                        <div class="newsletter-form-wrap-inner">
                            <input class="email" type="email" name="email"
                                   placeholder="<?php echo esc_attr( $atts['placeholder_text'] ); ?>">
                            <button type="submit" name="submit_button"
                                    class="btn-submit submit-newsletter"><?php echo esc_html__( 'SUBSCRIBE', 'ciloe' ) ?></button>
                        </div>
                    </form>
                </div>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Ciloe_Shortcode_newsletter', force_balance_tags( $html ), $atts, $content );
		}
	}
}