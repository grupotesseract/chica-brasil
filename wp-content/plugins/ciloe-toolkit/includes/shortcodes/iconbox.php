<?php

if ( !class_exists( 'Ciloe_Shortcode_Iconbox' ) ) {
	class Ciloe_Shortcode_Iconbox extends Ciloe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'iconbox';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array(
			'banner_image'     => '',
			'style'            => '',
			'content_position' => 'left',
			'text_align'       => 'text-left',
			'el_class'         => '',
			'css'              => '',
			'banner_custom_id' => '',
			'icon_type'        => '',
		);


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_iconbox', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'ciloe-iconbox' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'add_icon' ];
			$css_class[] = $atts[ 'iconbox_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$img_size_x = 90;
		    $img_size_y = 60;
		    
		    $img = ciloe_toolkit_resize_image( $atts['image_icon'], null, $img_size_x, $img_size_y, true, true, false );
			
			$allowed_html = array(
				'strong' => array()
			);
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="iconbox-inner">
					<?php if ( $image_icon ): ?>
                        <div class="icon-image">
                        	<?php echo ciloe_toolkit_img_output( $img ); ?>
                        </div>
					<?php endif; ?>
                    <div class="content">
						<?php if ($atts['title']): ?>
                            <h4 class="title">
								<?php echo wp_kses($atts['title'], $allowed_html); ?>
                            </h4>
						<?php endif; ?> 
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ciloe_Shortcode_iconbox', force_balance_tags( $html ), $atts, $content );
		}
	}
}