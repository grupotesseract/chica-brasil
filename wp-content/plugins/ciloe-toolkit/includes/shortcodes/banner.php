<?php

if ( ! class_exists( 'Ciloe_Shortcode_banner' ) ) {
	class Ciloe_Shortcode_banner extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'banner';
		
		
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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_banner', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			
			if ( $atts['style'] == 'default' || $atts['style'] == 'style2' ) {
				$css_class = array( 'ciloe-banner' );
			} else {
				$css_class = array( 'ciloe-banner ciloe-countdown' );
			}
			$css_class[] = $atts['style'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['title_type'];
			$css_class[] = 'text-' . $atts['title_align'];
			$css_class[] = 'position-' . $atts['position_align'];
			$css_class[] = $atts['banner_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[]  = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
				$css_custom[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			ob_start();
			
			$img_size_x = 440;
			$img_size_y = 363;
			$img_size   = $atts['img_size'];
			if ( trim( $img_size ) != '' ) {
				$img_size = explode( 'x', $img_size );
			}
			$img_size_x = isset( $img_size[0] ) ? max( 0, intval( $img_size[0] ) ) : $img_size_x;
			$img_size_y = isset( $img_size[1] ) ? max( 0, intval( $img_size[1] ) ) : $img_size_y;
			$bg_image   = array(
				'url'    => '',
				'width'  => 0,
				'height' => 0
			);
			if ( $atts['bg_simple_image'] > 0 ) {
				$bg_image = ciloe_toolkit_resize_image( $atts['bg_simple_image'], null, $img_size_x, $img_size_y, true, true, false );
			}
			
			$args         = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
			);
			$link_default = array(
				'url'    => '',
				'title'  => '',
				'target' => '_self',
			);
			
			if ( function_exists( 'vc_build_link' ) ):
				$link = wp_parse_args( vc_build_link( $atts['link'] ), $link_default );
			else:
				$link = $link_default;
			endif;
			
			// Fix empty target attribute
			if ( trim( $link['target'] ) == '' ) {
				$link['target'] = '_self';
			}
			
			?>
			<?php if ( $atts['style'] == 'style1' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
					<?php echo ciloe_toolkit_img_output( $bg_image ); ?>
                    <div class="block-content">
                        <?php if ( $atts['title'] ): ?>
                            <h3 class="block-title"><?php echo wp_kses( $atts['title'], $args ); ?></h3>
						<?php endif; ?>
                        <?php if ( $atts['sub_title'] ): ?>
                            <span class="block-smtitle"><?php echo wp_kses( $atts['sub_title'], $args ); ?></span>
						<?php endif; ?>
                        <div class="timers" data-date="<?php echo esc_attr( $atts['date'] ); ?>">
                            <div class="timer-day box"><span class="time day"></span><span
                                        class="time-title"><?php echo esc_html__( 'Days', 'ciloe' ); ?></span></div>
                            <div class="timer-hour box"><span class="time hour"></span><span
                                        class="time-title"><?php echo esc_html__( 'Hours', 'ciloe' ); ?></span></div>
                            <div class="timer-min box"><span class="time min"></span><span
                                        class="time-title"><?php echo esc_html__( 'Mins', 'ciloe' ); ?></span></div>
                            <div class="timer-secs box"><span class="time secs"></span><span
                                        class="time-title"><?php echo esc_html__( 'Sec', 'ciloe' ); ?></span></div>
                        </div>
                        <a class="block-link" href="<?php echo esc_url( $link['url'] ); ?>"
                           target="<?php echo esc_attr( $link['target'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
                    </div>
                </div>
            <?php elseif ( $atts['style'] == 'style2' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                     style="background-image: url('<?php echo esc_url( $bg_image['url'] ); ?>');">
					<?php
					if ( $bg_image['url'] != '' ) {
						echo ciloe_toolkit_img_output( $bg_image );
					}
					?>
                    <div class="block-content <?php echo esc_attr( implode( ' ', $css_custom ) ); ?>">
                    	<?php if ( $atts['sub_title'] ): ?>
                            <span class="block-smtitle"><?php echo wp_kses( $atts['sub_title'], $args ); ?></span>
						<?php endif; ?>
						<?php if ( $atts['title'] ): ?>
                            <h3 class="block-title"><?php echo wp_kses( $atts['title'], $args ); ?></h3>
						<?php endif; ?>
						<?php if ( $link['url'] != '' ): ?>
                            <a class="block-link button" href="<?php echo esc_url( $link['url'] ); ?>"
                               target="<?php echo esc_attr( $link['target'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
						<?php endif; ?>
                    </div>
                </div>
            <?php elseif ( $atts['style'] == 'style3' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                     style="background-image: url('<?php echo esc_url( $bg_image['url'] ); ?>');">
					<?php
					if ( $bg_image['url'] != '' ) {
						echo ciloe_toolkit_img_output( $bg_image );
					}
					?>
                    <div class="block-content <?php echo esc_attr( implode( ' ', $css_custom ) ); ?>">
						<?php if ( $atts['title'] ): ?>
                            <h3 class="block-title"><?php echo wp_kses( $atts['title'], $args ); ?></h3>
						<?php endif; ?>
						<?php if ( $link['url'] != '' ): ?>
                            <a class="block-link button" href="<?php echo esc_url( $link['url'] ); ?>"
                               target="<?php echo esc_attr( $link['target'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
						<?php endif; ?>
                    </div>
                </div>
			<?php elseif ( $atts['style'] == 'style4' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                     style="background-image: url('<?php echo esc_url( $bg_image['url'] ); ?>');">
					<?php
					if ( $bg_image['url'] != '' ) {
						echo ciloe_toolkit_img_output( $bg_image );
					}
					?>
                    <div class="block-content <?php echo esc_attr( implode( ' ', $css_custom ) ); ?>">
                    	<?php if ( $atts['sub_title'] ): ?>
                            <span class="block-smtitle"><?php echo wp_kses( $atts['sub_title'], $args ); ?></span>
						<?php endif; ?>
						<?php if ( $atts['title'] ): ?>
                            <h3 class="block-title"><?php echo wp_kses( $atts['title'], $args ); ?></h3>
						<?php endif; ?>
						<?php if ( $link['url'] != '' ): ?>
                            <a class="block-link button" href="<?php echo esc_url( $link['url'] ); ?>"
                               target="<?php echo esc_attr( $link['target'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
						<?php endif; ?>
                    </div>
                </div>
            <?php elseif ( $atts['style'] == 'style5' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                    <a href="<?php echo esc_url( $link['url'] ); ?>" class="media_thumb">
                    	<figure>
                    		<?php
								if ( $bg_image['url'] != '' ) {
									echo ciloe_toolkit_img_output( $bg_image );
								}
							?>
                    	</figure>		
                    </a>
					<?php if ( $atts['title'] ): ?>
                        <h3 class="banner-title"><a
                                    href="<?php echo esc_url( $link['url'] ); ?>"><?php echo wp_kses( $atts['title'], $args ); ?></a>
                        </h3>
					<?php endif; ?>

                </div>
            <?php elseif ( $atts['style'] == 'style6' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                     style="background-image: url('<?php echo esc_url( $bg_image['url'] ); ?>');">
					<?php
					if ( $bg_image['url'] != '' ) {
						echo ciloe_toolkit_img_output( $bg_image );
					}
					?>
                    <div class="block-content <?php echo esc_attr( implode( ' ', $css_custom ) ); ?>">
						<?php if ( $link['url'] != '' ): ?>
                            <a class="block-link button" href="<?php echo esc_url( $link['url'] ); ?>"
                               target="<?php echo esc_attr( $link['target'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
						<?php endif; ?>
                    </div>
                </div>
            <?php elseif ( $atts['style'] == 'style7' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                     style="background-image: url('<?php echo esc_url( $bg_image['url'] ); ?>');">
					<?php
					if ( $bg_image['url'] != '' ) {
						echo ciloe_toolkit_img_output( $bg_image );
					}
					?>
                    <div class="block-content <?php echo esc_attr( implode( ' ', $css_custom ) ); ?>">
						<?php if ( $atts['title'] ): ?>
                            <h3 class="block-title"><?php echo wp_kses( $atts['title'], $args ); ?></h3>
						<?php endif; ?>
						<?php if ( $link['url'] != '' ): ?>
                            <a class="block-link button" href="<?php echo esc_url( $link['url'] ); ?>"
                               target="<?php echo esc_attr( $link['target'] ); ?>"><?php echo esc_attr( $link['title'] ); ?></a>
						<?php endif; ?>
                    </div>
                </div>
			<?php endif; ?>
			
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Ciloe_Shortcode_banner', force_balance_tags( $html ), $atts, $content );
		}
	}
}