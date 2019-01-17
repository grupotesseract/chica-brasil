<?php

if ( !class_exists( 'Ciloe_Shortcode_Tabs' ) ) {
	class Ciloe_Shortcode_Tabs extends Ciloe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'tabs';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array(
			'style'          => '',
			'css_animation'  => '',
			'el_class'       => '',
			'css'            => '',
			'ajax_check'     => 'no',
			'tabs_custom_id' => '',
			'active_section' => '',
			'title_style'    => '',
			'des'            => '',
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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_tabs', $atts ) : $atts;
			// Extract shortcode parameters.
			extract(
				shortcode_atts(
					$this->default_atts,
					$atts
				)
			);

			$css_class = 'ciloe-tabs ' . $atts[ 'el_class' ] . ' ' . $atts[ 'style' ] . ' ' . $atts[ 'tabs_custom_id' ];
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$sections = $this->get_all_attributes( 'vc_tta_section', $content );
			ob_start();
			?>
            <div class="<?php echo esc_attr( $css_class ); ?>">
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
                    <div class="tab-head">
                        <ul class="tab-link">
							<?php
							$i         = 0;
							$sum       = 0;
							$style_css = '';
							if ( $atts[ 'style' ] == 'style2' ) {
								foreach ( $sections as $section ) {
									$sum++;
								}
								$percent   = 100 / intval( $sum );
								$style_css = 'style="width: ' . esc_attr( $percent ) . '%"';
							}

							?>
							<?php foreach ( $sections as $section ): ?>
								<?php
								$icon              = '';
								$content_shortcode = '';
								$i++;
								if ( $atts[ 'ajax_check' ] == 1 ) {
									$content_shortcode = htmlentities2( $section[ 'content' ] );
								}

								/* Get icon from section tabs */
								$type_icon = isset( $section[ 'i_type' ] ) ? $section[ 'i_type' ] : '';
								$add_icon  = isset( $section[ 'add_icon' ] ) ? $section[ 'add_icon' ] : '';

								if ( $type_icon == 'fontflaticon' ) {
									$class_icon = isset( $section[ 'icon_ciloecustomfonts' ] ) ? $section[ 'icon_ciloecustomfonts' ] : '';
								} else {
									$class_icon = isset( $section[ 'icon_fontawesome' ] ) ? $section[ 'icon_fontawesome' ] : '';
								}

								$position_icon = isset( $section[ 'i_position' ] ) ? $section[ 'i_position' ] : '';

								?>
                                <li class="<?php if ( $i == $atts[ 'active_section' ] ): ?>active<?php endif; ?>"
									<?php if ( $atts[ 'style' ] == 'style2' ) : echo force_balance_tags( $style_css ); endif; ?>>
                                    <a <?php if ( $i == $atts[ 'active_section' ] ) {
										echo 'class="loaded"';
									} ?> data-ajax="<?php echo esc_attr( $atts[ 'ajax_check' ] ) ?>"
                                         data-id='<?php echo esc_attr( get_the_ID() ); ?>'
                                         data-animate="<?php echo esc_attr( $atts[ 'css_animation' ] ); ?>"
                                         data-toggle="tab"
                                         href="#<?php echo esc_attr( $section[ 'tab_id' ] ); ?>">
										<?php if ( $add_icon == true && $position_icon != 'right' ) : ?><i
                                            class="before-icon <?php echo esc_attr( $class_icon ); ?>"></i><?php endif; ?>
										<?php echo esc_html( $section[ 'title' ] ); ?>
										<?php if ( $add_icon == true && $position_icon == 'right' ) : ?><i
                                            class="after-icon <?php echo esc_attr( $class_icon ); ?>"></i><?php endif; ?>
                                    </a>
                                </li>
							<?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="tab-container">
						<?php $i = 0; ?>
						<?php foreach ( $sections as $section ): ?>
							<?php $i++; ?>
                            <div class="tab-panel <?php if ( $i == $atts[ 'active_section' ] ): ?>active<?php endif; ?>"
                                 id="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>">
								<?php
								if ( $atts[ 'ajax_check' ] == '1' ) {
									if ( $i == $atts[ 'active_section' ] ) {
										echo do_shortcode( $section[ 'content' ] );
									}
								} else {
									echo do_shortcode( $section[ 'content' ] );
								}
								?>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ciloe_Shortcode_tabs', force_balance_tags( $html ), $atts, $content );
		}
	}
}