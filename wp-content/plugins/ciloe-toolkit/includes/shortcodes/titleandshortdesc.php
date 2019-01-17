<?php

if ( ! class_exists( 'Ciloe_Shortcode_titleandshortdesc' ) ) {
	class Ciloe_Shortcode_titleandshortdesc extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'titleandshortdesc';
		
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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_titleandshortdesc', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'ciloe-title-short-desc', 'block-content' );
			$css_class[] = $atts['text_align'];
			$css_class[] = $atts['style'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['fami_custom_id'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
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
			
			$html            = '';
			$title_html      = '';
			$sub_title_html  = '';
			$short_desc_html = '';
			$button_html     = '';
			$has_line        = '';
			if(( trim( $title ) != '' ) || ( trim( $subtitle ) != '' )) {
				$has_line = 'has-line';
			}
			if ( trim( $title ) != '' ) {
				$title_html = '<h3 class="block-title" style="color: ' . esc_attr( $title_color ) . ';">' . esc_html( $title ) . '</h3>';
			}
			if ( trim( $subtitle ) != '' ) {
				$sub_title_html = '<h4 class="block-sub-title">' . esc_html( $subtitle ) . '</h3>';
			}
			if ( trim( $short_desc ) != '' ) {
				$short_desc_html = '<div class="block-short_desc" style="color: ' . esc_attr( $short_desc_color ) . ';">' . do_shortcode( wpautop( $short_desc ) ) . '</div>';
			}
			
			if ( $link['url'] != '' ) {
				$button_html = '<a style="color: ' . esc_attr( $title_color ) . ';" class="block-link" href="' . esc_url( $link['url'] ) . '" target="' . esc_attr( $link['target'] ) . '">' . esc_html( $link['title'] ) . '</a>';
			}
			
			$html = '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '"><div class="'. esc_attr($has_line) .'"> ' . $title_html . $sub_title_html .'</div> ' . $short_desc_html . $button_html . '</div>';
			
			return apply_filters( 'Ciloe_Shortcode_titleandshortdesc', force_balance_tags( $html ), $atts, $content );
		}
	}
}