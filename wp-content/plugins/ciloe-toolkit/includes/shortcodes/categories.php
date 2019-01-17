<?php

if ( ! class_exists( 'Ciloe_Shortcode_categories' ) ) {
	
	class Ciloe_Shortcode_categories extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'categories';
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		
		public static function generate_css( $atts ) {
			$atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('ciloe_categories', $atts) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( $atts['style'] == 'default' ) {
				if ( trim( $atts['fontsize'] ) != '' ) {
					$css .= ' .' . $atts['categories_custom_id'] . ' .info .category-name{font-size:' . $atts['fontsize'] . 'px;}';
				}
			}
			
			return $css;
		}
		
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_categories', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'ciloe-categories' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['style'];
			$css_class[] = $atts['text_type'];
			$css_class[] = $atts['des_position'];
			$css_class[] = $atts['categories_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
//				echo '<pre>';
//				print_r($css_class);
//				echo '</pre>';
			}
			
			$img_size_x = 350;
			$img_size_y = 356;
			$img_size   = $atts['image_size'];
			if ( trim( $img_size ) != '' ) {
				$img_size = explode( 'x', $img_size );
			}
			$img_size_x = isset( $img_size[0] ) ? max( 0, intval( $img_size[0] ) ) : $img_size_x;
			$img_size_y = isset( $img_size[1] ) ? max( 0, intval( $img_size[1] ) ) : $img_size_y;
			if ( ! empty( $atts['taxonomy'] ) ):
				$product_term = get_term_by( 'slug', $atts['taxonomy'], 'product_cat' );
				$cat_link     = get_term_link( $atts['taxonomy'], 'product_cat' );
			endif;
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['style'] != 'style2' ): ?>
					<?php if ( $atts['bg_cat'] ) : ?>
                        <div class="thumb">
                            <figure>
								<?php $image_thumb = ciloe_toolkit_resize_image( $atts['bg_cat'], null, $img_size_x, $img_size_y, true, true, false ); ?>
								<?php echo ciloe_toolkit_img_output( $image_thumb ); ?>
                            </figure>
							<?php if ( $atts['style'] == 'style1' ): ?>
								<?php if ( ! empty( $atts['taxonomy'] ) ) : ?>
                                    <div class="info"><h3 class="category-name"><a
                                                    href="<?php echo esc_url( $cat_link ); ?>"><?php echo $product_term->name; ?></a>
                                        </h3>
                                    </div>
								<?php endif; ?>
							<?php endif; ?>
                        </div>
					<?php endif; ?>
					<?php if ( ! empty( $atts['taxonomy'] ) ): ?>
						<?php if ( $atts['style'] == 'default' ): ?>
                            <div class="info">
                                <h3 class="category-name"><a
                                            href="<?php echo esc_url( $cat_link ); ?>"><?php echo $product_term->name; ?></a>
                                </h3>
                                <a href="<?php echo esc_url( $cat_link ); ?>"
                                   class="category-link"><?php echo esc_html__( 'Shop Now' ); ?></a>
                            </div>
						<?php endif; ?>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( $atts['bg_cat'] ) : ?>
                        <div class="thumb">
                            <figure>
								<?php $image_thumb = ciloe_toolkit_resize_image( $atts['bg_cat'], null, $img_size_x, $img_size_y, true, true, false ); ?>
								<?php echo ciloe_toolkit_img_output( $image_thumb ); ?>
                            </figure>
							<?php if ( ! empty( $atts['taxonomy'] ) ) : ?>
                                <div class="category-link-wrap"><a href="<?php echo esc_url( $cat_link ); ?>"
                                                                   class="category-link"><?php echo esc_html__( 'Shop Now' ); ?></a>
                                </div>
							<?php endif; ?>
                        </div>
					<?php endif; ?>
					<?php if ( ! empty( $atts['taxonomy'] ) ): ?>
                        <div class="info">
                            <h3 class="category-name"><a
                                        href="<?php echo esc_url( $cat_link ); ?>"><?php echo $product_term->name; ?></a>
                            </h3>
                        </div>
					<?php endif; ?>
					<?php if ( $atts['des'] != '' ): ?>
                        <div class="cat-des">
							<?php echo esc_attr( $atts['des'] ); ?>
                        </div>
					<?php endif; ?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Ciloe_Shortcode_categories', force_balance_tags( $html ), $atts, $content );
		}
	}
}