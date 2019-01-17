<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'famiBuyTogetherFrontend' ) ) {
	class famiBuyTogetherFrontend {
		
		public $all_options = array();
		public $famibt_hook = 'woocommerce_product_tabs'; // Default hook
		
		public function __construct() {
			$this->all_options = famibt_get_all_options();
			$this->famibt_hook = isset( $this->all_options['famibt_hook'] ) ? $this->all_options['famibt_hook'] : $this->famibt_hook;
			
			switch ( $this->famibt_hook ) {
				case 'woocommerce_product_tabs':
					add_filter( $this->famibt_hook, array( $this, 'famibt_woo_product_tab' ) );
					break;
				default:
					add_action( $this->famibt_hook, array( $this, 'famibt_content' ) );
					break;
			}
		}
		
		/**
		 * Add a custom product data tab
		 */
		
		public function famibt_woo_product_tab( $tabs ) {
			
			$default_tab_title = esc_html__( 'Accessories', 'famibt' );
			$tab_title         = $default_tab_title;
			if ( is_singular( 'product' ) ) {
				global $product;
				if ( ! $product->is_type( 'simple' ) ) {
					return $tabs;
				}
				$product_id                 = $product->get_id();
				$famibt_enable_buy_together = get_post_meta( $product_id, 'famibt_enable_buy_together', true ) == 'yes';
				if ( ! $famibt_enable_buy_together ) {
					return $tabs;
				}
				$tab_title = get_post_meta( $product_id, 'famibt_title', true );
				if ( trim( $tab_title ) == '' ) {
					$tab_title = $default_tab_title;
				}
			}
			
			// Adds the new tab
			$tabs['famibt_tab'] = array(
				'title'    => $tab_title,
				'priority' => 1,
				'callback' => array( $this, 'famibt_woo_product_tab_content' )
			);
			
			return $tabs;
			
		}
		
		public function famibt_woo_product_tab_content() {
			$this->famibt_content();
		}
		
		public function famibt_content() {
			if ( is_singular( 'product' ) ) {
				global $product;
				if ( ! $product->is_type( 'simple' ) ) {
					return;
				}
				
				$product_id                 = $product->get_id();
				$famibt_enable_buy_together = get_post_meta( $product_id, 'famibt_enable_buy_together', true ) == 'yes';
				if ( ! $famibt_enable_buy_together ) {
					return;
				}
				
				$responsive_class = $this->famibt_get_responsive_class();
				
				$all_options      = $this->all_options;
				$add_to_cart_text = isset( $all_options['famibt_add_to_cart_text'] ) ? $all_options['famibt_add_to_cart_text'] : esc_html__( 'Add All To Cart', 'famibt' );
				
				$thumb_w = 145;
				$thumb_h = 145;
				$thumb_w = apply_filters( 'famibt_thumb_w', $thumb_w );
				$thumb_h = apply_filters( 'famibt_thumb_h', $thumb_h );
				
				$total_price = 0;
				$total_items = 0;
				
				// Check stock availability
				$availability       = $product->get_availability();
				$disabled           = '';
				$avai_text          = isset( $availability['availability'] ) ? $availability['availability'] : '';
				$avai_class         = isset( $availability['class'] ) ? $availability['class'] : '';
				$avai_class_product = $avai_class;
				$avai_text_html     = '';
				if ( ! $product->is_in_stock() ) {
					$avai_text_html     = '<span class="famibt-avai-text famibt-out-of-stock-splash out-of-stock-splash">' . $avai_text . '</span>';
					$avai_class         .= ' famibt-out-of-stock';
					$avai_class_product .= ' famibt-out-of-stock';
					$disabled           = 'disabled';
				} else {
					$total_items ++;
					$total_price += floatval( $product->get_price() );
				}
				
				$title      = get_post_meta( $product_id, 'famibt_title', true );
				$short_desc = get_post_meta( $product_id, 'famibt_short_desc', true );
				$after_text = get_post_meta( $product_id, 'famibt_after_text', true );
				
				$title_html                 = '';
				$short_desc_html            = '';
				$after_text_html            = '';
				$left_part_html             = '';
				$right_part_html            = '';
				$famibt_products_list_html  = '';
				$famibt_checkboxs_list_html = '';
				
				if ( trim( $title ) != '' && $this->famibt_hook != 'woocommerce_product_tabs' ) {
					$title_html = '<h3 class="famibt-title">' . esc_html( $title ) . '</h3>';
				}
				
				if ( trim( $short_desc ) != '' ) {
					$short_desc_html = '<div class="famibt-short-desc">' . wpautop( do_shortcode( $short_desc ) ) . '</div>';
				}
				if ( trim( $after_text ) != '' ) {
					$after_text_html = '<div class="famibt-after-text">' . wpautop( do_shortcode( $after_text ) ) . '</div>';
				}
				
				$main_product_thumb        = famibt_resize_image( get_post_thumbnail_id( $product_id ), null, $thumb_w, $thumb_h, true, true, false );
				$famibt_products_list_html .= '<div data-product_id="' . esc_attr( $product_id ) . '" class="famibt-product famibt-main-product ' . $avai_class_product . ' ' . $responsive_class . '">
												<div class="famibt-product-inner">
													<a href="' . esc_url( get_permalink( $product_id ) ) . '">
														<div class="famibt-thumb-wrap">
															' . famibt_img_output( $main_product_thumb, 'famibt-thumb' ) . '
														</div>
														<h3 class="famibt-product-title">' . get_the_title( $product_id ) . '</h3>
													</a>
													<div class="famibt-product-info">
														<div class="famibt-price">' . $product->get_price_html() . '</div>
													</div>
													' . $avai_text_html . '
												</div>
											</div>';
				
				$famibt_checkboxs_list_html .= '<div data-product_id="' . esc_attr( $product_id ) . '" class="famibt-item famibt-main-item ' . $avai_class . '">
										<label>
											<input data-price="' . floatval( $product->get_price() ) . '" data-product_id="' . esc_attr( $product_id ) . '" type="checkbox" ' . checked( true, $product->is_in_stock(), false ) . ' disabled />
											<span class="famibt-product-title"><strong>' . esc_html__( 'This product: ', 'famibt' ) . '</strong> ' . get_the_title( $product_id ) . '</span>
											<span class="famibt-price">' . $product->get_price_html() . '</span>
										</label>
										' . $avai_text_html . '
									</div>';
				
				$famibt_ids = get_post_meta( $product_id, 'famibt_ids', true );
				if ( trim( $famibt_ids ) != '' ) {
					$famibt_ids = explode( ',', $famibt_ids );
					if ( ! empty( $famibt_ids ) ) {
						foreach ( $famibt_ids as $famibt_id ) {
							$famibt_product = wc_get_product( $famibt_id );
							if ( ! $famibt_product || $famibt_id == $product_id ) {
								continue;
							}
							if ( ! $famibt_product->is_type( 'simple' ) ) {
								continue;
							}
							
							// Check stock availability
							$availability       = $famibt_product->get_availability();
							$disabled           = '';
							$avai_text          = isset( $availability['availability'] ) ? $availability['availability'] : '';
							$avai_class         = isset( $availability['class'] ) ? $availability['class'] : '';
							$avai_class_product = $avai_class;
							$avai_text_html     = '';
							if ( ! $famibt_product->is_in_stock() ) {
								$avai_text_html     = '<span class="famibt-avai-text famibt-out-of-stock-splash out-of-stock-splash">' . $avai_text . '</span>';
								$avai_class         .= ' famibt-out-of-stock';
								$avai_class_product .= ' famibt-out-of-stock famibt-hidden';
								$disabled           = 'disabled';
							} else {
								$total_items ++;
								$total_price += floatval( $famibt_product->get_price() );
							}
							
							$famibt_thumb = famibt_resize_image( get_post_thumbnail_id( $famibt_id ), null, $thumb_w, $thumb_h, true, true, false );
							
							$famibt_products_list_html .= '<div data-product_id="' . esc_attr( $famibt_id ) . '" class="famibt-product ' . $avai_class_product . ' ' . $responsive_class . '">
													<div class="famibt-product-inner">
														<a href="' . esc_url( get_permalink( $famibt_id ) ) . '">
															<div class="famibt-thumb-wrap">
																' . famibt_img_output( $famibt_thumb, 'famibt-thumb' ) . '
															</div>
															<h3 class="famibt-product-title">' . get_the_title( $famibt_id ) . '</h3>
														</a>
														<div class="famibt-product-info">
															<div class="famibt-price">' . $famibt_product->get_price_html() . '</div>
														</div>
														' . $avai_text_html . '
													</div>
												</div>';
							
							$famibt_checkboxs_list_html .= '<div data-product_id="' . esc_attr( $famibt_id ) . '" class="famibt-item ' . $avai_class . '">
													<label>
														<input data-price="' . floatval( $famibt_product->get_price() ) . '" data-product_id="' . esc_attr( $famibt_id ) . '" type="checkbox" ' . checked( true, $famibt_product->is_in_stock(), false ) . ' ' . $disabled . ' />
														<span class="famibt-product-title">' . get_the_title( $famibt_id ) . '</span>
														<span class="famibt-price">' . $famibt_product->get_price_html() . '</span>
													</label>
													' . $avai_text_html . '
												</div>';
							
						}
					}
				}
				
				$famibt_products_list_html  = '<div class="famibt-products-wrap"><div class="row">' . $famibt_products_list_html . '</div></div>';
				$famibt_checkboxs_list_html = '<div class="famibt-items-wrap">' . $famibt_checkboxs_list_html . '</div>';
				$left_part_html             = '<div class="famibt-left-part">' . $famibt_products_list_html . $famibt_checkboxs_list_html . '</div>';
				
				$total_price_html = '<div class="total-price-wrap">
										<div class="total-price-html">' . wc_price( $total_price ) . '</div>
										<span class="for-items-text">' . sprintf( esc_html__( 'For %s item(s)', 'famibt' ), $total_items ) . '</span>
									</div>';
				
				$add_all_to_cart_btn_html = '<div class="famibt-add-all-to-cart-btn-wrap"> <button data-count_success="0" data-count_fail="0" type="button" class="button btn btn-primary famibt-add-all-to-cart">' . $add_to_cart_text . '</button></div>';
				$right_part_html          = '<div class="famibt-right-part">' . $total_price_html . $add_all_to_cart_btn_html . '</div>';
				
				$buy_together_html = '<div class="famibt-wrap famibt-auto-clear">
								' . $title_html . '
								' . $short_desc_html . '
								<div class="row">
		                        	<div class="col-xs-12 col-sm-9">' . $left_part_html . '</div>
			                     	<div class="col-xs-12 col-sm-3">' . $right_part_html . '</div>
			                     </div>
			                     ' . $after_text_html . '
								</div>';
				
				echo $buy_together_html;
				
			}
		}
		
		public function famibt_get_responsive_class() {
			$all_options = $this->all_options;
			$col_xl      = isset( $all_options['famibt_col_xl'] ) ? $all_options['famibt_col_xl'] : 'display_col_4'; // Default display 4 columns on large screen
			$col_lg      = isset( $all_options['famibt_col_lg'] ) ? $all_options['famibt_col_lg'] : 'display_col_4'; // Default display 4 columns on large screen
			$col_md      = isset( $all_options['famibt_col_md'] ) ? $all_options['famibt_col_md'] : 'display_col_4'; // Default display 4 columns on medium screen
			$col_sm      = isset( $all_options['famibt_col_sm'] ) ? $all_options['famibt_col_sm'] : 'display_col_3'; // Default display 3 columns on small screen
			$col_xs      = isset( $all_options['famibt_col_xs'] ) ? $all_options['famibt_col_xs'] : 'display_col_2'; // Default display 2 columns on small screen
			$col_xxs     = isset( $all_options['famibt_col_xxs'] ) ? $all_options['famibt_col_xxs'] : 'display_col_1'; // Default display 1 column on small screen
			
			$class = '';
			$class .= $this->famibt_col_num_to_class( $col_xl, 'fxl' );
			$class .= $this->famibt_col_num_to_class( $col_lg, 'lg' );
			$class .= $this->famibt_col_num_to_class( $col_md, 'md' );
			$class .= $this->famibt_col_num_to_class( $col_sm, 'sm' );
			$class .= $this->famibt_col_num_to_class( $col_xs, 'xs' );
			$class .= $this->famibt_col_num_to_class( $col_xxs, 'fxxs' );
			
			return $class;
		}
		
		protected function famibt_parse_col( $col_text ) {
			$col = intval( str_replace( 'display_col_', '', $col_text ) );
			if ( $col <= 0 || $col > 12 ) {
				$col = 12;
			}
			
			return $col;
		}
		
		/**
		 * @param $col_num  mixed   Number of columns per row
		 * @param $screen   string  xl, lg, md, sm, xs, xxs
		 *
		 * @return string
		 */
		protected function famibt_col_num_to_class( $col_num, $screen ) {
			$class   = '';
			$col_num = $this->famibt_parse_col( $col_num );
			if ( ! in_array( $screen, array( 'fxl', 'lg', 'md', 'sm', 'xs', 'fxxs' ) ) ) {
				return '';
			}
			switch ( $col_num ) {
				case 6:
					$class = 'col-' . $screen . '-2';
					break;
				case 5:
					$class = 'col-' . $screen . '-15';
					break;
				case 4:
					$class = 'col-' . $screen . '-3';
					break;
				case 3:
					$class = 'col-' . $screen . '-4';
					break;
				case 2:
					$class = 'col-' . $screen . '-6';
					break;
				case 1:
					$class = 'col-' . $screen . '-12';
					break;
			}
			
			return $class . ' ';
		}
	}
	
	new famiBuyTogetherFrontend();
}

