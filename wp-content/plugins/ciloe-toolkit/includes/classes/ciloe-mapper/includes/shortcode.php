<?php

class Ciloe_Mapper_Shortcode {
	/**
	 * Initialize.
	 *
	 * @return  void
	 */
	public static function initialize() {
		// Add shortcode
		add_shortcode( 'ciloe_mapper', array( __CLASS__, 'render_shortcode' ) );
		
		// Enqueue style and script
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}
	
	/**
	 * Generate HTML code based on shortcode parameters.
	 *
	 * @param   array  $atts    Shortcode parameters.
	 * @param   string $content Current content.
	 *
	 * @return  string
	 */
	public static function render_shortcode( $atts, $content = null ) {
		global $woocommerce;
		$html = '';
		
		// Extract shortcode parameters.
		if ( ! isset( $atts['id'] ) ) {
			return;
		}
		
		// Check is publish
		if ( get_post_status( $atts['id'] ) != 'publish' ) {
			return;
		}
		
		// Get current image.
		$attachment_id = get_post_meta( $atts['id'], 'ciloe_mapper_image', true );
		
		if ( ! $attachment_id ) {
			return;
		}
		
		// Get image source.
		$image_src  = wp_get_attachment_url( $attachment_id );
		$image_data = wp_get_attachment_metadata( $attachment_id );
		
		// Get general settings.
		$settings = get_post_meta( $atts['id'], 'ciloe_mapper_settings', true );
		
		// Get all pins.
		$pins = get_post_meta( $atts['id'], 'ciloe_mapper_pins', true );
		
		// Generate CSS.
		$html .= self::inline_style( $atts['id'], $settings, $pins );
		
		// Generate HTML.
		$html .= '
		<div id="ciloe-mapper-' . esc_attr( $atts['id'] ) . '" class="ciloe-mapper ' . esc_attr( $settings['tooltip-style'] ) . ' ' . esc_attr( $settings['popup-show-effect'] ) . ' ' . esc_attr( $settings['image-effect'] ) . '" data-width="' . esc_attr( $image_data['width'] ) . '" data-height="' . esc_attr( $image_data['height'] ) . '">
			<img src="' . esc_attr( $image_src ) . '" width="' . esc_attr( $image_data['width'] ) . '" height="' . esc_attr( $image_data['height'] ) . '" alt="' . esc_attr( basename( $image_src ) ) . '" />';
		
		if ( $settings['image-effect'] == 'mask' ) {
			$html .= '<div class="mask"></div>';
		}
		
		if ( $pins ) {
			foreach ( $pins as $pin ) {
				$html .= '<div id="csre-pin-' . esc_attr( $pin['settings']['id'] ) . '" class="csre-pin" data-top="' . esc_attr( $pin['top'] ) . '" data-left="' . esc_attr( $pin['left'] ) . '" data-position="' . esc_attr( $pin['settings']['popup-position'] ) . '">';
				if ( $pin['settings']['icon-type'] == 'icon-image' && ! empty( $pin['settings']['image-template'] ) ) {
					$html .= '<img class="action-pin image-pin" src="' . esc_attr( $pin['settings']['image-template'] ) . '" alt="Pin" />';
				} else {
					if ( $pin['settings']['area-text'] ) {
						$style = 'style="font-size: ' . esc_attr( $pin['settings']['area-text-size'] ) . 'px;color: ' . esc_attr( $pin['settings']['area-text-color'] ) . ';width: ' . esc_attr( $pin['settings']['area-width'] ) . 'px;height: ' . esc_attr( $pin['settings']['area-height'] ) . 'px;line-height: ' . esc_attr( $pin['settings']['area-height'] ) . 'px;border-width: ' . esc_attr( $pin['settings']['area-border-width'] ) . 'px;border-style: solid;border-radius: ' . esc_attr( $pin['settings']['area-border-radius'] ) . 'px;background: ' . esc_attr( $pin['settings']['area-bg-color'] ) . ';border-color: ' . esc_attr( $pin['settings']['area-border-color'] ) . ';"';
						$html  .= '<div class="text__area" ' . $style . '>' . esc_html( $pin['settings']['area-text'] ) . '</div>';
					}
					
				}
				
				// Product Item
				if ( class_exists( 'WooCommerce' ) && $pin['settings']['pin-type'] == 'woocommerce' && ( ! empty( $pin['settings']['product'] ) ) ) {
					$product_id = $pin['settings']['product'];
					$_product   = wc_get_product( $product_id );
					
					$html .= '<h3 class="csre-title">' . get_the_title( $product_id ) . '</h3>';
					
					$html .= '<div class="csre-popup csre-wc ' . esc_attr( $pin['settings']['popup-position'] ) . '">';
					$html .= '<div class="csre-popup-header">';
					$html .= '<h2><a href="' . esc_url( get_permalink( $product_id ) ) . '">' . get_the_title( $product_id ) . '</a></h2>';
					
					if ( $_product->get_price_html() ) {
						$html .= '<div class="csre-wc-info">';
						$html .= '<div class="csre-wc-price">' . $_product->get_price_html() . '</div>';
						if ( $pin['settings']['product-rate'] ) {
							$html .= '<div class="woocommerce-product-rating">';
							if ( $_product->get_rating_count() ) {
								$html .= '<div class="review-count">(';
								$html .= sprintf( __( '%s Reviews', 'ciloe-toolkit' ), $_product->get_rating_count() );
								$html .= ')</div>';
							}
							$html .= '</div>';
						}
						$html .= '</div>';
					}
					
					$html     .= '</div>';
					$img_size = ciloe_toolkit_resize_image( get_post_thumbnail_id( $product_id ), null, 95, 130, true, true, false );
					
					$html .= '<div class="csre-popup-main">';
					if ( $pin['settings']['product-thumbnail'] ) {
						$html .= '<div class="col-left csre-product-thumbnail">';
						$html .= '<a href="' . esc_url( get_permalink( $product_id ) ) . '">';
						$html .= '<img src="' . esc_url( $img_size['url'] ) . '" width="' . esc_attr( $img_size['width'] ) . '" height="' . esc_attr( $img_size['height'] ) . '" alt="">';
						$html .= '</a>';
						$html .= '</div>';
					}
					$html .= '<div class="col-right">';
					if ( $pin['settings']['product-description'] ) {
						if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
							$html .= '<p>' . wp_trim_words( $_product->post->post_excerpt, 10, '...' ) . '</p>';
						} else {
							$html .= '<p>' . wp_trim_words( $_product->get_short_description(), 10, '...' ) . '</p>';
						}
					}
					$html .= '</div>';
					$html .= '</div>';
					
					$html .= '<div class="csre-popup-footer">';
					if ( $_product->is_type( 'simple' ) && ! Ciloe_Mapper_Helper::yith_wc_product_add_ons( $product_id ) && ! Ciloe_Mapper_Helper::check_gravityforms( $product_id ) && ! Ciloe_Mapper_Helper::wc_measurement_price_calculator( $product_id ) ) {
						$html .= '<a href="' . esc_url( $_product->add_to_cart_url() ) . '" data-product_id="' . esc_attr( $product_id ) . '" data-quantity="1" class="ajax_add_to_cart add_to_cart_button button product_type_simple">' . __( 'Add To Cart', 'ciloe-toolkit' ) . '</a>';
					} else {
						$html .= '<a href="' . esc_url( get_permalink( $product_id ) ) . '" data-product_id="' . esc_attr( $product_id ) . '" data-quantity="1" class="add_to_cart_button button product_type_simple">' . __( 'Select Options', 'ciloe-toolkit' ) . '</a>';
					}
					$html .= '</div>';
					$html .= '</div>';
					
					// Image Item
				} elseif ( $pin['settings']['pin-type'] == 'image' ) {
					if ( ! empty( $pin['settings']['popup-title'] ) ) {
						$html .= '<h3 class="csre-title">' . $pin['settings']['popup-title'] . '</h3>';
					} else {
						$html .= '<h3 class="csre-title">' . __( 'Add your title in backend', 'ciloe-toolkit' ) . '</h3>';
					}
					
					$html .= '<div class="csre-popup csre-image ' . esc_attr( $pin['settings']['popup-position'] ) . '">';
					$html .= '<div class="csre-popup-header">';
					$html .= '<h2>' . $pin['settings']['popup-title'] . '</h2>';
					$html .= '</div>';
					
					$html .= '<div class="csre-popup-main">';
					if ( ! empty( $pin['settings']['image-link-to'] ) ) {
						$html .= '<a target="' . esc_attr( $pin['settings']['image-link-target'] ) . '" href="' . esc_url( $pin['settings']['image-link-to'] ) . '">';
					}
					if ( $pin['settings']['image'] ) {
						$html .= '<img src="' . esc_url( $pin['settings']['image'] ) . '"/>';
					}
					if ( ! empty( $pin['settings']['image-link-to'] ) ) {
						$html .= '</a>';
					}
					$html .= '</div>';
					$html .= '</div>';
					
					// Text Item
				} elseif ( $pin['settings']['pin-type'] == 'text' ) {
					if ( ! empty( $pin['settings']['popup-title'] ) ) {
						$html .= '<h3 class="csre-title">' . $pin['settings']['popup-title'] . '</h3>';
					} else {
						$html .= '<h3 class="csre-title">' . __( 'Add your title in backend', 'ciloe-toolkit' ) . '</h3>';
					}
					
					$html .= '<div class="csre-popup csre-text ' . esc_attr( $pin['settings']['popup-position'] ) . '">';
					$html .= '<div class="csre-popup-header">';
					$html .= '<h2>' . $pin['settings']['popup-title'] . '</h2>';
					$html .= '</div>';
					
					$html .= '<div class="csre-popup-main">';
					$html .= '<div class="content-text">';
					$html .= nl2br( do_shortcode( $pin['settings']['text'] ) );
					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
					
					// Link Item
				} elseif ( $pin['settings']['pin-type'] == 'link' ) {
					$html .= '<a class="csre-link" target="' . esc_attr( $pin['settings']['image-link-target'] ) . '" href="' . esc_url( $pin['settings']['image-link-to'] ) . '"></a>';
					
					if ( ! empty( $pin['settings']['popup-title'] ) ) {
						$html .= '<h3 class="csre-title">' . $pin['settings']['popup-title'] . '</h3>';
					} else {
						$html .= '<h3 class="csre-title">' . __( 'Add your title in backend', 'ciloe-toolkit' ) . '</h3>';
					}
				}
				$html .= '</div>';
			}
		}
		
		$html .= '
		</div>';
		
		return apply_filters( 'render_shortcode', force_balance_tags( $html ) );
	}
	
	/**
	 * Enqueue custom scripts / stylesheets.
	 *
	 * @return  void
	 */
	public static function enqueue_scripts() {
		global $post;
		if ( ! $post ) {
			return;
		}
		if ( has_shortcode( $post->post_content, "ciloe_mapper" ) || has_shortcode( $post->post_excerpt, "ciloe_mapper" ) || has_shortcode( $post->post_content, "ciloe_pinmap" ) || has_shortcode( $post->post_excerpt, "ciloe_pinmap" ) ) {
			// Enqueue required assets.
			wp_enqueue_style( 'ciloe-toolkit', CILOE_TOOLKIT_URL . '/includes/classes/ciloe-mapper/assets/css/frontend.css' );
			wp_enqueue_script( 'ciloe-toolkit', CILOE_TOOLKIT_URL . '/includes/classes/ciloe-mapper/assets/js/frontend.js', array(), false, true );
		}
	}
	
	/**
	 * Render inline style.
	 *
	 * @param   int   $id       Mapper ID.
	 * @param   array $settings Mapper settings.
	 * @param   array $pins     Mapper pins.
	 *
	 * @return  void
	 */
	public static function inline_style( $id, $settings, $pins ) {
		// Generate CSS rules for general settings.
		$css = '
		<style type="text/css">
			#ciloe-mapper-' . $id . ' .csre-popup {';
		$css .= 'padding: 10px;';
		if ( $settings['popup-width'] ) {
			$css .= 'width: ' . esc_attr( $settings['popup-width'] ) . 'px;';
		}
		
		if ( $settings['popup-height'] ) {
			$css .= 'height: ' . esc_attr( $settings['popup-height'] ) . 'px;';
		}
		
		if ( $settings['popup-box-shadow'] ) {
			$css .= 'box-shadow: 0px 2px 10px 0px #dfdfdf;';
		}
		
		if ( $settings['popup-border-radius'] ) {
			$css .= 'border-radius: ' . (int) $settings['popup-border-radius'] . 'px;';
		}
		
		if ( $settings['popup-border-width'] ) {
			$css .= 'border: ' . (int) $settings['popup-border-width'] . 'px solid;';
		}
		
		if ( $settings['popup-border-color'] ) {
			$css .= 'border-color: ' . esc_attr( $settings['popup-border-color'] ) . ';';
		}
		
		$css .= '}';
		
		if ( $settings['popup-border-radius'] ) {
			$css .= '
			#ciloe-mapper-' . $id . '.ciloe-mapper .csre-pin .csre-popup-footer a {border-radius: 0 0 ' . (int) $settings['popup-border-radius'] . 'px ' . (int) $settings['popup-border-radius'] . 'px;}';
		}
		
		if ( $settings['image-effect'] == 'mask' ) {
			$css .= '
			#ciloe-mapper-' . $id . ' .mask {';
			
			if ( $settings['mask-color'] ) {
				$css .= 'background: ' . esc_attr( $settings['mask-color'] ) . ';';
			}
			
			$css .= '}';
		}
		
		// Generate CSS rules for each pin.
		if ( $pins ) {
			foreach ( $pins as $pin ) {
				// Popup width & height
				$css .= '
			#ciloe-mapper-' . $id . ' #csre-pin-' . esc_attr( $pin['settings']['id'] ) . ' .csre-popup {';
				
				if ( isset( $pin['settings']['popup-width'] ) && (int) $pin['settings']['popup-width'] > 0 ) {
					$css .= 'width: ' . (int) $pin['settings']['popup-width'] . 'px;';
				}
				
				if ( isset( $pin['settings']['popup-height'] ) && (int) $pin['settings']['popup-height'] > 0 ) {
					$css .= 'height: ' . (int) $pin['settings']['popup-height'] . 'px;';
				}
				
				$css .= '}';
				
				// Pin style setting
				$css .= '
			#ciloe-mapper-' . $id . ' #csre-pin-' . esc_attr( $pin['settings']['id'] ) . ' .icon-pin {';
				
				if ( isset( $pin['settings']['bg-color'] ) ) {
					$css .= 'background: ' . esc_attr( $pin['settings']['bg-color'] ) . ';';
				}
				
				if ( isset( $pin['settings']['icon-color'] ) ) {
					$css .= 'color: ' . esc_attr( $pin['settings']['icon-color'] ) . ';';
				}
				
				if ( isset( $pin['settings']['icon-size'] ) ) {
					$css .= 'font-size: ' . (int) esc_attr( $pin['settings']['icon-size'] ) . 'px;';
					$css .= 'width: ' . (int) esc_attr( $pin['settings']['icon-size'] ) * 1.2 . 'px;';
					$css .= 'line-height: ' . (int) esc_attr( $pin['settings']['icon-size'] ) * 1.2 . 'px;';
				}
				
				if ( isset( $pin['settings']['border-width'] ) && (int) $pin['settings']['border-width'] > 0 ) {
					$css .= 'box-shadow: 0 0 0 ' . (int) esc_attr( $pin['settings']['border-width'] ) . 'px ' . esc_attr( $pin['settings']['border-color'] ) . ';';
				}
				
				$css .= '}';
				
				// Pin hover setting
				$css .= '
			#ciloe-mapper-' . $id . ' #csre-pin-' . esc_attr( $pin['settings']['id'] ) . ' .icon-pin:hover {';
				
				if ( isset( $pin['settings']['bg-color-hover'] ) ) {
					$css .= 'background: ' . esc_attr( $pin['settings']['bg-color-hover'] ) . ';';
				}
				if ( isset( $pin['settings']['icon-color-hover'] ) ) {
					$css .= 'color: ' . esc_attr( $pin['settings']['icon-color-hover'] ) . ';';
				}
				
				$css .= '}';
				// Hover text
				$css .= '
                #ciloe-mapper-' . $id . ' #csre-pin-' . esc_attr( $pin['settings']['id'] ) . ' .text__area:hover,
                 #ciloe-mapper-' . $id . ' #csre-pin-' . esc_attr( $pin['settings']['id'] ) . ' img:hover{ ';
				
				$css .= '}';
				
				
			}
		}
		
		$css .= '
		</style>';
		
		return $css;
	}
}
