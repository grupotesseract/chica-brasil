<?php
if ( ! class_exists( 'Ciloe_Shortcode_Custommenu' ) ) {
	class Ciloe_Shortcode_Custommenu extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'custommenu';
		
		
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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_custommenu', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'ciloe-custommenu' );
			$css_class[] = $layout;
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['custommenu_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			$nav_menu = get_term_by( 'slug', $atts['menu'], 'nav_menu' );
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $layout == 'layout1' ) : ?>
					<?php if ( $menu_banner ) : ?>
                        <div class="thumb-menu">
							<?php $image_thumb = ciloe_toolkit_resize_image( $menu_banner, null, 326, 328, true, true, false ); ?>
                            <img width="<?php echo esc_attr( $image_thumb['width'] ); ?>"
                                 height="<?php echo esc_attr( $image_thumb['height'] ); ?>"
                                 class="attachment-post-thumbnail wp-post-image"
                                 src="<?php echo esc_attr( $image_thumb['url'] ) ?>" alt="<?php the_title(); ?>"/>
                        </div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( is_object( $nav_menu ) ): ?>
					<?php if ( $title ): ?>
                        <h2 class="widgettitle"><?php echo esc_html( $title ); ?></h2>
					<?php endif ?>
					<?php
					wp_nav_menu( array(
						             'menu'            => $nav_menu->slug,
						             'theme_location'  => $nav_menu->slug,
						             'container'       => '',
						             'container_class' => '',
						             'container_id'    => '',
						             'menu_class'      => 'menu',
						             'fallback_cb'     => 'ciloe_navwalker::fallback',
						             'walker'          => new ciloe_navwalker(),
					             )
					);
					?>
				<?php endif; ?>
				<?php if ( $layout == 'layout1' ) : ?>
					<?php if ( $link ) : ?>
						<?php $link = vc_build_link( $link ); ?>
                        <a href="<?php echo esc_url( $link['url'] ) ?>"
                           target="<?php echo esc_html( $link['target'] ) ?>">
							<?php echo esc_html( $link['title'] ); ?>
                        </a>
					<?php endif; ?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Ciloe_Shortcode_custommenu', force_balance_tags( $html ), $atts, $content );
		}
	}
}