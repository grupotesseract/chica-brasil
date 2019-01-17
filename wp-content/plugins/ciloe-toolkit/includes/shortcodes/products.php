<?php

if ( ! class_exists( 'Ciloe_Shortcode_Products' ) ) {
	class Ciloe_Shortcode_Products extends Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'products';
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		public $product_thumb_width  = 590;
		public $product_thumb_height = 590;
		
		
		public static function generate_css( $atts ) {
			extract( $atts );
			$css = '';
			
			return $css;
		}
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_products', $atts ) : $atts;
			
			extract( $atts );
			$css_class   = array( 'ciloe-products' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['products_custom_id'];
			$css_class[] = 'style-' . $atts['product_style'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}
			
			$product_size_args = array(
				'width'  => 320,
				'height' => 320
			);
			
			/* Product Size */
			if ( $atts['product_image_size'] ) {
				if ( $atts['product_image_size'] == 'custom' ) {
					$this->product_thumb_width  = $atts['product_custom_thumb_width'];
					$this->product_thumb_height = $atts['product_custom_thumb_height'];
				} else {
					$product_image_size         = explode( "x", $atts['product_image_size'] );
					$this->product_thumb_width  = $product_image_size[0];
					$this->product_thumb_height = $product_image_size[1];
				}
//				if ( $this->product_thumb_width > 0 ) {
//
//
//					// add_filter( 'ciloe_shop_pruduct_thumb_width', create_function( '', 'return ' . $thumb_width . ';' ) );
//					add_filter(
//						'ciloe_shop_pruduct_thumb_width',
//						function() {
//							return $this->product_thumb_width;
//						}
//					);
//				}
//				if ( $this->product_thumb_height > 0 ) {
//					// add_filter( 'ciloe_shop_pruduct_thumb_height', create_function( '', 'return ' . $thumb_height . ';' ) );
//					add_filter(
//						'ciloe_shop_pruduct_thumb_height',
//						function() {
//							return $this->product_thumb_height;
//						}
//					);
//				}
			}
			
			$product_size_args['width']  = $this->product_thumb_width;
			$product_size_args['height'] = $this->product_thumb_height;
			
			$products      = $this->getProducts( $atts );
			$total_product = $products->post_count;
			
			$product_item_class   = array( 'product-item', $atts['target'] );
			$product_item_class[] = 'style-' . $atts['product_style'];
			
			
			$show_button  = false;
			$max_num_page = $products->max_num_pages;
			$query_paged  = $products->query_vars['paged'];
			if ( $query_paged >= 0 && ( $query_paged < $max_num_page ) ) {
				$show_button = true;
			} else {
				$show_button = false;
			}
			if ( $max_num_page <= 1 ) {
				$show_button = false;
			}
			
			$btn_link_owl_html = '';
			$link_default      = array(
				'url'    => '',
				'title'  => '',
				'target' => '',
			);
			$btn_link          = $link_default;
			if ( function_exists( 'vc_build_link' ) ):
				$btn_link = vc_build_link( $atts['btn_link'] );
			else:
				$btn_link = $link_default;
			endif;
			
			// Fix empty target attribute
			if ( trim( $btn_link['target'] ) == '' ) {
				$btn_link['target'] = '_self';
			}
			
			$product_list_class = array();
			$owl_settings       = '';
			if ( $productsliststyle == 'grid' ) {
				if ( $atts['enable_loadmore'] == 'loadmore' ) {
					$product_list_class[] = 'type-loadmore';
				}
				$product_list_class[] = 'product-grid row auto-clear equal-container better-height ';
				$product_item_class[] = $boostrap_rows_space;
				$product_item_class[] = 'col-bg-' . $boostrap_bg_items;
				$product_item_class[] = 'col-lg-' . $boostrap_lg_items;
				$product_item_class[] = 'col-md-' . $boostrap_md_items;
				$product_item_class[] = 'col-sm-' . $boostrap_sm_items;
				$product_item_class[] = 'col-xs-' . $boostrap_xs_items;
				$product_item_class[] = 'col-ts-' . $boostrap_ts_items;
			}
			if ( $productsliststyle == 'owl' ) {
				if ( $total_product < $owl_lg_items ) {
					$atts['owl_loop'] = 'false';
				}
				$product_list_class[] = 'product-grid product-list-owl owl-carousel equal-container better-height nav-' . $atts['nav_type'] . ' dot-' . $atts['dots_type'];
				$product_item_class[] = $owl_rows_space;
				$owl_settings         = $this->generate_carousel_data_attributes( 'owl_', $atts );
			}
			
			$style_css = '';
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>" <?php echo force_balance_tags( $style_css ); ?>>
				<?php if ( $products->have_posts() ): ?>
					<?php if ( $productsliststyle == 'grid' ): ?>
                        <ul id="<?php echo esc_attr( $atts['products_custom_id'] ); ?>"
                            class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>">
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                <li id="post-<?php echo get_the_ID(); ?>" <?php post_class( $product_item_class ); ?>>
									<?php wc_get_template( 'product-styles/content-product-style-' . $atts['product_style'] . '.php', $product_size_args ); ?>
                                </li>
							<?php endwhile; ?>
                        </ul>
						<?php if ( $atts['enable_loadmore'] == 'loadmore' && $show_button == true ) : ?>
                            <div class="more-items">
                                <a data-id="<?php echo esc_attr__( $atts['products_custom_id'] ); ?>"
                                   data-attribute='<?php echo base64_encode( wp_json_encode( $atts ) ); ?>'
                                   data-cats="<?php echo esc_attr( $atts['taxonomy'] ) ?>" page="2"
                                   class="woo-product-loadmore loadmore-button"
                                   href="javascript:void(0);"><?php echo esc_html__( 'Load More', 'cosre' ); ?></a>
                            </div>
						<?php endif; ?>
					<?php elseif ( $productsliststyle == 'owl' ) : ?>
                        <!-- OWL Products -->
						<?php $i = 1; ?>
                        <div class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>" <?php echo force_balance_tags( $owl_settings ); ?>>
                            <div class="owl-one-row">
								<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                    <div <?php post_class( $product_item_class ); ?>>
										<?php wc_get_template( 'product-styles/content-product-style-' . $product_style . '.php', $product_size_args ); ?>
                                    </div>
									<?php
									if ( $i % $owl_number_row == 0 && $i < $total_product ) {
										echo '</div><div class="owl-one-row">';
									}
									$i ++;
									?>
								<?php endwhile; ?>
                            </div>
                        </div>
						<?php if ( $btn_link['url'] != '' ) { ?>
                            <div class="btn-wrap owl-btn-wrap">
                                <a class="button btn ciloe-button owl-btn-link"
                                   href="<?php echo esc_url( $btn_link['url'] ); ?>"
                                   target="<?php echo esc_attr( $btn_link['target'] ); ?>"><?php echo esc_html( $btn_link['title'] ); ?></a>
                            </div>
						<?php } ?>
					<?php endif; ?>
				<?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'ciloe-toolkit' ); ?></strong>
                    </p>
				<?php endif; ?>
            </div>
			<?php
			wp_reset_postdata();
			$html = ob_get_clean();
			
			return apply_filters( 'Ciloe_Shortcode_products', force_balance_tags( $html ), $atts, $content );
		}
	}
}