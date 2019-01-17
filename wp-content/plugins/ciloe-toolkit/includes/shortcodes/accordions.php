<?php

if ( !class_exists( 'Ciloe_Shortcode_Accordions' ) ) {
	class Ciloe_Shortcode_Accordions extends Ciloe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'accordions';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array(
			'style'                => '',
			'el_class'             => '',
			'css'                  => '',
			'accordions_custom_id' => '',
			'tab_title'            => '',
			'active_tab'           => '1',
			'ajax_check'           => '0',
		);


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );

			return '';
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_accordions', $atts ) : $atts;
			// Extract shortcode parameters.
			extract(
				shortcode_atts(
					$this->default_atts,
					$atts
				)
			);

			$css_class = 'ciloe-accordions panel-group ' . $atts[ 'el_class' ] . ' ' . $atts[ 'style' ];
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class .= ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$sections = $this->get_all_attributes( 'vc_tta_section', $content );

			ob_start();
			?>
            <div class="<?php echo esc_attr( $css_class ); ?>" role="tablist"
                 aria-multiselectable="true">
				<?php $i = 0; ?>
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
					<?php if ( $atts['tab_title'] ) : ?>
                        <h3 class="title"><?php echo esc_html( $atts['tab_title'] ); ?></h3>
					<?php endif; ?>
					<?php foreach ( $sections as $section ): ?>
						<?php
						$i++;
						$content_shortcode = '';

						if ( $atts[ 'ajax_check' ] == 1 ) {
							$content_shortcode = base64_encode( do_shortcode( $section[ 'content' ] ) );
						}

						/* Get icon from section tabs */
						$type_icon  = isset( $section[ 'i_type' ] ) ? $section[ 'i_type' ] : '';
						$add_icon   = isset( $section[ 'add_icon' ] ) ? $section[ 'add_icon' ] : '';

						if ( $type_icon == 'fontflaticon' ) {
							$class_icon = isset( $section[ 'icon_ciloecustomfonts' ] ) ? $section[ 'icon_ciloecustomfonts' ] : '';
						} else {
							$class_icon = isset( $section[ 'icon_fontawesome' ] ) ? $section[ 'icon_fontawesome' ] : '';
						}

						$position_icon = isset( $section[ 'i_position' ] ) ? $section[ 'i_position' ] : '';
						$icon = '';
						if ( $add_icon == true ) {
							$icon = '<i class="' . esc_attr( $class_icon ) . '"></i>';
						}

						?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab"
                                 id="accordion-<?php echo esc_attr( $section[ 'tab_id' ] ); ?>">
                                <h4 class="panel-title">
									<?php if ( $add_icon == true && $position_icon != 'right' ) : echo balanceTags( $icon ); endif; ?>
                                    <a class="<?php if ( $i == $atts['active_tab'] ): ?>loaded<?php endif; ?>"
                                       role="button" data-toggle="collapse"
                                       data-ajax="<?php echo esc_attr( $atts[ 'ajax_check' ] ) ?>"
                                       data-shortcode='<?php echo esc_attr( $content_shortcode ); ?>'
                                       data-parent=".ciloe-accordions"
                                       href="#<?php echo esc_attr( $section[ 'tab_id' ] ); ?>"
                                       aria-expanded="false"
                                       aria-controls="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>">
										<?php echo esc_html( $section[ 'title' ] ); ?>
                                    </a>
									<?php if ( $add_icon == true && $position_icon == 'right' ) : echo balanceTags( $icon ); endif; ?>
                                </h4>
                            </div>
                            <div id="<?php echo esc_attr( $section[ 'tab_id' ] ); ?>"
                                 class="panel-collapse collapse <?php if ( $i == $atts['active_tab'] ): ?>in<?php endif; ?>"
                                 role="tabpanel"
                                 aria-labelledby="accordion-<?php echo esc_attr( $section[ 'tab_id' ] ); ?>">
                                <div class="panel-body">
									<?php
									if ( $atts[ 'ajax_check' ] == '1' ) {
										if ( $i == $atts['active_tab'] ) {
											echo do_shortcode( $section[ 'content' ] );
										}
									} else {
										echo do_shortcode( $section[ 'content' ] );
									}
									?>
                                </div>
                            </div>
                        </div>
					<?php endforeach; ?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ciloe_Shortcode_accordions', force_balance_tags( $html ), $atts, $content );
		}
	}
}