<?php

if ( ! class_exists( 'Ciloe_Shortcode_pinmap' ) ) {
	class Ciloe_Shortcode_pinmap extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'pinmap';
		
		
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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_pinmap', $atts ) : $atts;
			
			$html = '';
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'ciloe-pinmap' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['custom_id'];
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			
			$mapper_id = intval( $ids );
			if ( $mapper_id > 0 ) {
				$html .= do_shortcode( '[ciloe_mapper id="' . esc_attr( $mapper_id ) . '"]' );
				if ( $show_info_content == 'yes' ) {
					$info_content_html = '';
					$top               = 0;
					$left              = 0;
					$pos_lg            = $atts['pos'];
					if ( trim( $pos_lg ) != '' ) {
						$pos_lg = explode( ':', $pos_lg );
					}
					$top  = isset( $pos_lg[0] ) ? max( 0, $pos_lg[0] ) : $top;
					$left = isset( $pos_lg[1] ) ? max( 0, $pos_lg[1] ) : $left;
					if ( is_numeric( $top ) ) {
						$top .= 'px';
					}
					if ( is_numeric( $left ) ) {
						$left .= 'px';
					}
					
					$content_style   = 'style="position: absolute; z-index: 10; top: ' . $top . '; left: ' . $left . ';"';
					$title_html      = trim( $title ) != '' ? '<h3 class="ciloe-mapper-title slideInLeft">' . $title . '</h3>' : '';
					$short_desc_html = trim( $title ) != '' ? '<div class="ciloe-mapper-short-desc slideInDown">' . $short_desc . '</div>' : '';
					$link_default    = array(
						'url'    => '',
						'title'  => '',
						'target' => '_self',
					);
					$btn_link        = $link_default;
					if ( function_exists( 'vc_build_link' ) ):
						$btn_link = vc_build_link( $atts['btn_link'] );
					else:
						$btn_link = $link_default;
					endif;
					
					// Fix empty target attribute
					if ( trim( $btn_link['target'] ) == '' ) {
						$btn_link['target'] = '_self';
					}
					
					$btn_link_html = '';
					if ( $btn_link['url'] != '' ) {
						$btn_link_html = '<a class="btn-link ciloe-mapper-btn-link slideInUp" href="' . esc_url( $btn_link['url'] ) . '" title="' . esc_attr( $btn_link['title'] ) . '" target="' . esc_attr( $btn_link['target'] ) . '">' . esc_html( $btn_link['title'] ) . '</a>';
					}
					$info_content_html = '<div class="ciloe-mapper-short-content-wrap" ' . $content_style . '>' . $short_desc_html . $title_html . $btn_link_html . '</div>';
					$html              .= $info_content_html;
				}
			}
			
			if ( $html != '' ) {
				$html = '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '">' . $html . '</div>';
			}
			
			return apply_filters( 'Ciloe_Shortcode_pinmap', force_balance_tags( $html ), $atts, $content );
		}
	}
}