<?php
/* ==================== HOOK SHOP ==================== */

/* Remove Div cover content shop */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/* Custom shop control */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_before_shop_loop' );
//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
//remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );


add_action( 'ciloe_before_shop_loop', 'ciloe_shop_top_control', 10 );
add_action( 'woocommerce_before_main_content', 'ciloe_woocommerce_breadcrumb', 20 );

/* Custom product per page */
add_filter( 'loop_shop_per_page', 'ciloe_loop_shop_per_page', 20 );

/* Custom product categories cat thumbnails */
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'ciloe_woocommerce_subcategory_thumbnail', 'ciloe_woocommerce_subcategory_thumbnail', 10 );

/* Remove CSS */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );

add_action( 'wp_enqueue_scripts', 'ciloe_wp_enqueue_scripts' );
function ciloe_wp_enqueue_scripts() {
	wp_dequeue_style( 'woocommerce_admin_styles' );
}

/**  Cusstom number related **/
add_filter( 'woocommerce_output_related_products_args', 'ciloe_related_products_args' );
function ciloe_related_products_args( $args ) {
	$limit                  = ciloe_get_option( 'ciloe_related_products_perpage', 8 );
	$args['posts_per_page'] = $limit; // 4 related products
	
	return $args;
}

/* Custom Product Thumbnail */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'ciloe_template_loop_product_thumbnail', 10, 1 );

/* ==================== HOOK SHOP ==================== */

remove_action( 'woocommerce_shortcode_before_product_cat_loop', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
/* ==================== CART PAGE ==================== */

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 30 );

/* ==================== CART PAGE ==================== */

/* ==================== SINGLE PRODUCT =============== */

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 1 );
add_action( 'ciloe_product_flash', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_after_single_product', 'ciloe_product_share', 1 );
add_action( 'woocommerce_single_product_summary', 'ciloe_open_product_mobile_more_detail_wrap', 25 );
// After single excerpt and before single add to cart
add_action( 'woocommerce_single_product_summary', 'fami_woocommerce_output_product_data_tabs_mobile', 115 );
add_action( 'woocommerce_single_product_summary', 'ciloe_close_product_mobile_more_detail_wrap', 120 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'fami_woocommerce_output_product_data_tabs', 10 );
add_action( 'fami_variable', 'woocommerce_template_single_title', 8 );
add_action( 'fami_variable', 'woocommerce_template_single_rating', 9 );
add_action( 'fami_variable', 'woocommerce_template_single_add_to_cart', 10 );


/* ==================== HOOK PRODUCT ================= */

/*Remove woocommerce_template_loop_product_link_open */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/*Custom product name*/
add_action( 'woocommerce_after_shop_loop_item_title', 'ciloe_template_loop_product_title_open', 4 );
add_action( 'woocommerce_after_shop_loop_item_title', 'ciloe_template_loop_product_title', 6 );
add_action( 'woocommerce_after_shop_loop_item_title', 'ciloe_template_loop_product_title_close', 7 );

add_action( 'ciloe_shop_loop_rating', 'woocommerce_template_loop_rating', 8 );

/*ciloe button product*/
add_action( 'ciloe_product_video', 'ciloe_show_product_video', 11 );
add_action( 'ciloe_product_360deg', 'ciloe_show_product_360deg', 12 );


/* Ciloe Custom Checkout Page */

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_action( 'woocommerce_before_checkout_form', 'checkout_login_open', 1 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 5 );
add_action( 'woocommerce_before_checkout_form', 'checkout_login_close', 6 );
add_action( 'woocommerce_before_checkout_form', 'checkout_coupon_open', 7 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'checkout_coupon_close', 11 );

// Quickview
remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_rating', 10 );

add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_title', 10 );
add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_rating', 5 );

add_action( 'yith_wcqv_product_summary', 'ciloe_wc_loop_product_wishlist_btn', 26 );
// Sticky on single
add_action( 'sticky_thumbnail_product_summary', 'ciloe_woocommerce_thumbnail_sticky', 5 );
add_action( 'sticky_info_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'sticky_info_product_summary', 'woocommerce_template_single_rating', 10 );
/* Ciloe _add_filter */
add_filter( 'woocommerce_show_page_title', 'ciloe_woocommerce_page_title' );

function checkout_login_open() {
	if ( ! is_user_logged_in() ) {
		echo '<div class="ciloe-checkout-login">';
	}
}

function checkout_login_close() {
	if ( ! is_user_logged_in() ) {
		echo '</div>';
	}
}

function checkout_coupon_open() {
	echo '<div class="ciloe-checkout-coupon">';
}

function checkout_coupon_close() {
	echo '</div>';
}


// Add action Single Product hook : woocommerce_before_single_product_summary
if ( ! function_exists( 'ciloe_show_product_360deg' ) ) {
	function ciloe_show_product_360deg() {
		global $product;
		$meta_360 = get_post_meta( $product->get_id(), '_custom_product_woo_options', '' );
		
		if ( empty( $meta_360 ) || empty( $meta_360[0]['360gallery'] ) ) {
			return;
		}
		$images = $meta_360[0]['360gallery'];
		$images = explode( ',', $images );
		if ( empty( $images ) ) {
			return;
		}
		$id               = rand( 0, 999 );
		$title            = '';
		$frames_count     = count( $images );
		$images_js_string = '';
		?>
        <div id="product-360-view" class="product-360-view-wrapper mfp-hide">
            <div class="ciloe-threed-view threed-id-<?php echo esc_attr( $id ); ?>">
				<?php if ( ! empty( $title ) ): ?>
                    <h3 class="threed-title"><span><?php echo esc_html( $title ); ?></span></h3>
				<?php endif ?>
                <ul class="threed-view-images">
					<?php if ( count( $images ) > 0 ): ?>
						<?php $i = 0;
						foreach ( $images as $img_id ): $i ++; ?>
							<?php
							$img              = wp_get_attachment_image_src( $img_id, 'full' );
							$images_js_string .= "'" . $img[0] . "'";
							$width            = $img[1];
							$height           = $img[2];
							if ( $i < $frames_count ) {
								$images_js_string .= ",";
							}
							?>
						<?php endforeach ?>
					<?php endif ?>
                </ul>
                <div class="spinner">
                    <span>0%</span>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('.threed-id-<?php echo esc_attr( $id ); ?>').ThreeSixty({
                        totalFrames: <?php echo esc_attr( $frames_count ); ?>,
                        endFrame: <?php echo esc_attr( $frames_count ); ?>,
                        currentFrame: 1,
                        imgList: '.threed-view-images',
                        progress: '.spinner',
                        imgArray: [<?php printf( '%s', $images_js_string ); ?>],
                        height: <?php echo esc_attr( $height ); ?>,
                        width: <?php echo esc_attr( $width ); ?>,
                        responsive: true,
                        navigation: true
                    });
                });
            </script>
        </div>
        <div class="product-360-button">
            <a href="#product-360-view"><span><?php echo esc_html__( '360 Degree', 'ciloe' ); ?></span></a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'ciloe_show_product_video' ) ) {
	function ciloe_show_product_video() {
		global $product;
		$video_url = get_post_meta( $product->get_id(), '_custom_product_woo_options', '' );
		if ( ! empty( $video_url[0]['youtube_url'] ) ) {
			echo '<div class="ciloe-bt-video"><a href="' . esc_url( $video_url[0]['youtube_url'] ) . '">' . esc_html__( 'Play Video', 'ciloe' ) . '</a></div>';
		}
	}
}


function ciloe_template_loop_product_title_open() {
	echo '<div class="ciloe-loop-title-rate">';
}

function ciloe_template_loop_product_title_close() {
	echo '</div>';
}

/* Add countdown in product */
add_action( 'ciloe_display_product_countdown_in_loop', 'ciloe_display_product_countdown_in_loop', 1 );

/* Stock status */
add_action( 'ciloe_woo_get_stock_status', 'ciloe_woo_get_stock_status', 1 );

/* Short Product description */
add_action( 'ciloe_product_short_description', 'ciloe_product_short_description', 15 );

/* Custom flash icon */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'ciloe_group_flash', 5 );


/* Add categories to product */
add_action( 'ciloe_add_categories_product', 'ciloe_add_categories_product', 1 );

/* ==================== HOOK PRODUCT ==================== */

/* WC_Vendors */
if ( class_exists( 'WC_Vendors' ) && class_exists( 'WCV_Vendor_Shop' ) ) {
	// Add sold by to product loop before add to cart
	if ( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 9 );
		add_action( 'woocommerce_shop_loop_item_title', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 1 );
	}
}


/* CUSTOM PRODUCT TITLE */
if ( ! function_exists( 'ciloe_template_loop_product_title' ) ) {
	function ciloe_template_loop_product_title() {
		$title_class = array( 'product-title' );
		?>
        <h3 class="<?php echo esc_attr( implode( ' ', $title_class ) ); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
		<?php
	}
}

/* CUSTOM PAGINATION */
//remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
//add_action( 'woocommerce_after_shop_loop', 'ciloe_custom_pagination', 10 );

// Will remove
if ( ! function_exists( 'ciloe_custom_pagination' ) ) {
	function ciloe_custom_pagination() {
		global $wp_query;
		$enable_loadmore = ciloe_get_option( 'ciloe_enable_loadmore', 'default' );
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		?>
		<?php if ( $enable_loadmore != 'default' ): ?>
			<?php
			
			if ( class_exists( 'PrdctfltrInit' ) ) {
				echo '<nav class="woocommerce-pagination prdctfltr-pagination prdctfltr-pagination-load-more">
                        <a href="#" class="button">Load More</a>
                    </nav>';
			} else {
				global $wp_query;
				echo '<div class="ciloe-ajax-load" data-mode="grid" data-2nd_page_url="' . esc_url( get_next_posts_page_link( $wp_query->max_num_pages ) ) . '" data-cur_page="1" data-total_page="' . esc_attr( $wp_query->max_num_pages ) . '" data-load-more=\'{"page":"' . esc_attr( $wp_query->max_num_pages ) . '","container":"product-grid","layout":"' . esc_attr( $enable_loadmore ) . '"}\'>';
				next_posts_link( esc_html__( 'Load More', 'ciloe' ), $wp_query->max_num_pages );
				echo '</div>';
			}
			
			?>
		<?php else: ?>
            <nav class="woocommerce-pagination pagination">
				<?php
				echo paginate_links(
					apply_filters( 'woocommerce_pagination_args',
					               array(
						               'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
						               'format'    => '',
						               'add_args'  => false,
						               'current'   => max( 1, get_query_var( 'paged' ) ),
						               'total'     => $wp_query->max_num_pages,
						               'prev_text' => esc_html__( 'Previous', 'ciloe' ),
						               'next_text' => esc_html__( 'Next', 'ciloe' ),
						               'type'      => 'plain',
						               'end_size'  => 3,
						               'mid_size'  => 3,
					               )
					)
				);
				?>
            </nav>
		<?php endif; ?>
		<?php
	}
}

/* CUSTOM RATTING */
add_filter( "woocommerce_product_get_rating_html", "ciloe_get_rating_html", 10, 2 );
if ( ! function_exists( 'ciloe_get_rating_html ' ) ) {
	function ciloe_get_rating_html( $rating_html, $rating ) {
		global $product;
		$count = $product->get_review_count();
		if ( $rating > 0 ) {
			$rating_html = '<div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'ciloe' ), $rating ) . '">';
			$rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong></span>';
			if ( $count < 2 ) {
				$rating_html .= '<p class="preview-count">(' . intval( $count ) . esc_html__( ' Preview', 'ciloe' ) . ')</p>';
			} else {
				$rating_html .= '<p class="preview-count">(' . intval( $count ) . esc_html__( ' Previews', 'ciloe' ) . ')</p>';
			}
			$rating_html .= '</div>';
		} else {
			$rating_html = '';
		}
		
		return $rating_html;
	}
}

/* SINGLE PRODUCT MOBILE MORE DETAIL OPEN */
function ciloe_open_product_mobile_more_detail_wrap() {
	$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
		echo '<div class="product-mobile-more-detail-wrap">';
	}
}

/* SINGLE PRODUCT MOBILE MORE DETAIL CLOSE */
function ciloe_close_product_mobile_more_detail_wrap() {
	$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
		echo '</div> <!-- .product-mobile-more-detail-wrap -->';
		echo '<a href="#" class="product-toggle-more-detail">' . esc_html__( 'More Details', 'ciloe' ) . '</a>';
	}
}

/* SING PRODUCT TABS */
function fami_woocommerce_output_product_data_tabs() {
	$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
	if ( ! $enable_single_product_mobile || ! ciloe_is_mobile() ) {
		woocommerce_output_product_data_tabs();
	}
}

function fami_woocommerce_output_product_data_tabs_mobile() {
	$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
		woocommerce_output_product_data_tabs();
	}
}

/* CUSTOM PRODUCT CATEGORIES THUMBNAILS */
if ( ! function_exists( 'ciloe_woocommerce_thumbnail_sticky' ) ) {
	function ciloe_woocommerce_thumbnail_sticky() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}
		$dimensions   = array(
			'width'  => 50,
			'height' => 50
		);
		$thumbnail_id = get_post_thumbnail_id();
		
		$image = ciloe_resize_image( $thumbnail_id, null, $dimensions['width'], $dimensions['height'], true, true, false );
		echo ciloe_img_output( $image, '', esc_attr( get_the_title() ) );
	}
}
/* CUSTOM PRODUCT THUMBNAIL */
if ( ! function_exists( 'ciloe_template_loop_product_thumbnail' ) ) {
	
	function ciloe_template_loop_product_thumbnail( $args = array() ) {
		global $product;
		
		// GET SIZE IMAGE SETTING
//		$w    = 514;
//		$h    = 651;
		$crop      = true;
		$size      = wc_get_image_size( 'shop_catalog' );
		$wc_width  = 514;
		$wc_height = 651;
		if ( $size ) {
			$wc_width  = $size['width'];
			$wc_height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}
		
		$w = isset( $args['width'] ) ? intval( $args['width'] ) : $wc_width;
		$h = isset( $args['height'] ) ? intval( $args['height'] ) : $wc_height;
//		$w = apply_filters( 'ciloe_shop_pruduct_thumb_width', $w );
//		$h = apply_filters( 'ciloe_shop_pruduct_thumb_height', $h );
		
		$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
		$atts_swatches_mobile         = false;
		if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
			$atts_swatches_mobile = true;
		}
		
		ob_start();
		?>
        <a class="thumb-link" href="<?php the_permalink(); ?>">
			<?php
			$image_thumb        = ciloe_resize_image( get_post_thumbnail_id(
				                                          $product->get_id()
			                                          ), null, $w, $h, $crop, true, false
			);
			$class_img_thumb    = 'attachment-post-thumbnail';
			$secondary_img_html = $class = '';
			$attachment_ids     = $product->get_gallery_image_ids();
			if ( isset( $attachment_ids[0] ) ) {
				$secondary_class = 'product-secondary-img';
				if ( ! $atts_swatches_mobile ) {
					$secondary_class .= ' wp-post-image';
				} else {
					$class_img_thumb .= ' wp-post-image';
				}
				$secondary_img      = ciloe_resize_image( $attachment_ids[0], null, $w, $h, $crop, true, false );
				$secondary_img_html .= '<figure class="product-second-figure product-second-fadeinDown">';
				$secondary_img_html .= '<div class="woocommerce-product-gallery__image">';
				$secondary_img_html .= ciloe_img_output( $secondary_img, $secondary_class, esc_attr( get_post_meta( $attachment_ids[0], '_wp_attachment_image_alt', true ) ) );
				$secondary_img_html .= '</div>';
				$secondary_img_html .= '</figure>';
			} else {
				$class_img_thumb .= ' wp-post-image';
			}
			
			echo '<div class="images">';
			echo '<div class="woocommerce-product-gallery__image--placeholder">';
			echo ciloe_img_output( $image_thumb, $class_img_thumb, get_the_title(), get_the_title() );
			echo ciloe_html_output( $secondary_img_html );
			echo '</div>';
			echo '</div>';
			?>
        </a>
		<?php
		echo ob_get_clean();
	}
}

/* CUSTOM PRODUCT CATEGORIES THUMBNAILS */
if ( ! function_exists( 'ciloe_woocommerce_subcategory_thumbnail' ) ) {
	function ciloe_woocommerce_subcategory_thumbnail( $category ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );
		
		$image = ciloe_resize_image( $thumbnail_id, null, $dimensions['width'], $dimensions['height'], true, true, false );
		echo ciloe_img_output( $image, '', esc_attr( $category->name ) );
	}
}


/* ADD CATEGORIES LIST IN PRODUCT */
if ( ! function_exists( 'ciloe_add_categories_product' ) ) {
	
	function ciloe_add_categories_product() {
		$html = '';
		$html .= '<span class="cat-list">';
		$html .= wc_get_product_category_list( get_the_ID() );
		$html .= '</span>';
		printf( '%s', $html );
	}
}

/* CUSTOM TITLE TAB DESCRIPTTION */
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );

/* CUSTOM BREADCRUMB */
if ( ! function_exists( 'ciloe_woocommerce_breadcrumb' ) ) {
	function ciloe_woocommerce_breadcrumb() {
		$args = array(
			'delimiter'   => '',
			'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs"><ul class="breadcrumb">',
			'wrap_after'  => '</ul></nav>',
			'before'      => '<li>',
			'after'       => '</li>',
		);
		woocommerce_breadcrumb( $args );
	}
}
/* HOOK CONTROL */
if ( ! function_exists( 'ciloe_shop_top_control' ) ) {
	function ciloe_shop_top_control() {
		$enable_shop_mobile = ciloe_get_option( 'enable_shop_mobile', true );
		if ( $enable_shop_mobile && ciloe_is_mobile() ) {
			get_template_part( 'template-parts/shop-top', 'control-mobile' );
		} else {
			get_template_part( 'template-parts/shop-top', 'control' );
		}
	}
}

/* VIEW MORE */
if ( ! function_exists( 'ciloe_shop_view_more' ) ) {
	function ciloe_shop_view_more() {
		$shop_display_mode = ciloe_get_option( 'woo_shop_list_style', 'grid' );
		if ( isset( $_SESSION['shop_display_mode'] ) ) {
			$shop_display_mode = $_SESSION['shop_display_mode'];
		}
		?>
        <div class="grid-view-mode">
            <a data-mode="grid"
               class="modes-mode mode-grid display-mode <?php if ( $shop_display_mode == "grid" ): ?>active<?php endif; ?>"
               href="javascript:void(0)">
                <i class="flaticon-17grid"></i>
				<?php echo esc_html__( 'Grid', 'ciloe' ) ?>
            </a>
            <a data-mode="list"
               class="modes-mode mode-list display-mode <?php if ( $shop_display_mode == "list" ): ?>active<?php endif; ?>"
               href="javascript:void(0)">
                <i class="flaticon-18list"></i>
				<?php echo esc_html__( 'List', 'ciloe' ) ?>
            </a>
        </div>
		<?php
	}
}

/*----------------------
Product view style
----------------------*/
if ( ! function_exists( 'wp_ajax_frontend_set_products_view_style_callback' ) ) {
	function wp_ajax_frontend_set_products_view_style_callback() {
		check_ajax_referer( 'ciloe_ajax_frontend', 'security' );
		$mode                          = $_POST['mode'];
		$_SESSION['shop_display_mode'] = $mode;
		
		die();
	}
}
add_action( 'wp_ajax_frontend_set_products_view_style', 'wp_ajax_frontend_set_products_view_style_callback' );
add_action( 'wp_ajax_nopriv_frontend_set_products_view_style', 'wp_ajax_frontend_set_products_view_style_callback' );

if ( ! function_exists( 'ciloe_loop_shop_per_page' ) ) {
	function ciloe_loop_shop_per_page() {
		$ciloe_woo_products_perpage = ciloe_get_option( 'product_per_page', '12' );
		
		return $ciloe_woo_products_perpage;
	}
}

/*----------------------
Product per page
----------------------*/
if ( ! function_exists( 'wp_ajax_fronted_set_products_perpage_callback' ) ) {
	function wp_ajax_fronted_set_products_perpage_callback() {
		check_ajax_referer( 'ciloe_ajax_frontend', 'security' );
		$mode                                   = $_POST['mode'];
		$_SESSION['ciloe_woo_products_perpage'] = $mode;
		die();
	}
}
add_action( 'wp_ajax_fronted_set_products_perpage', 'wp_ajax_fronted_set_products_perpage_callback' );
add_action( 'wp_ajax_nopriv_fronted_set_products_perpage', 'wp_ajax_fronted_set_products_perpage_callback' );

/* QUICK VIEW */
if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
	// Class frontend
	$enable           = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
	$enable_on_mobile = get_option( 'yith-wcqv-enable-mobile' ) == 'yes' ? true : false;
	// Class frontend
	if ( ( ! ciloe_is_mobile() && $enable ) || ( ciloe_is_mobile() && $enable_on_mobile && $enable ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array(
			YITH_WCQV_Frontend::get_instance(),
			'yith_add_quick_view_button'
		), 15 );
		add_action( 'ciloe_function_shop_loop_item_quickview', array(
			YITH_WCQV_Frontend::get_instance(),
			'yith_add_quick_view_button'
		), 5 );
	}
}

/* WISH LIST */
if ( class_exists( 'YITH_WCWL' ) && get_option( 'yith_wcwl_enabled' ) == 'yes' ) {
	if ( ! function_exists( 'ciloe_wc_loop_product_wishlist_btn' ) ) {
		function ciloe_wc_loop_product_wishlist_btn() {
			if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . get_the_ID() . '"]' );
			}
		}
	}
	add_action( 'ciloe_function_shop_loop_item_wishlist', 'ciloe_wc_loop_product_wishlist_btn', 1 );
}

/* COMPARE */
if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) {
	global $yith_woocompare;
	$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	if ( $yith_woocompare->is_frontend() || $is_ajax ) {
		if ( $is_ajax ) {
			if ( ! class_exists( 'YITH_Woocompare_Frontend' ) ) {
				if ( file_exists( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' ) ) {
					require_once( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' );
				}
			}
			$yith_woocompare->obj = new YITH_Woocompare_Frontend();
		}
		/* Remove button */
		remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
		/* Add compare button */
		if ( ! function_exists( 'ciloe_wc_loop_product_compare_btn' ) ) {
			function ciloe_wc_loop_product_compare_btn() {
				if ( shortcode_exists( 'yith_compare_button' ) ) {
					echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
				} // End if ( shortcode_exists( 'yith_compare_button' ) )
				else {
					if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
						$YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();
						echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
					}
				}
			}
		}
		add_action( 'ciloe_function_shop_loop_item_compare', 'ciloe_wc_loop_product_compare_btn', 1 );
	}
}

if ( ! function_exists( 'ciloe_wisth_list_url' ) ) {
	function ciloe_wisth_list_url() {
		$url = '';
		if ( function_exists( 'yith_wcwl_object_id' ) ) {
			$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
			$url              = get_the_permalink( $wishlist_page_id );
		}
		
		return $url;
	}
}

/* GROUP NEW FLASH */

if ( ! function_exists( 'ciloe_group_flash' ) ) {
	function ciloe_group_flash() {
		global $product;
		?>
        <div class="flash">
			<?php
			woocommerce_show_product_loop_sale_flash();
			ciloe_show_product_loop_new_flash();
			?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'ciloe_show_product_loop_new_flash' ) ) {
	/**
	 * Get the sale flash for the loop.
	 *
	 * @subpackage    Loop
	 */
	function ciloe_show_product_loop_new_flash() {
		wc_get_template( 'loop/new-flash.php' );
	}
}

add_filter( 'woocommerce_sale_flash', 'ciloe_custom_sale_flash' );

if ( ! function_exists( 'ciloe_custom_sale_flash' ) ) {
	function ciloe_custom_sale_flash( $text ) {
		$percent = ciloe_get_percent_discount();
		if ( $percent != '' ) {
			return '<span class="onsale">' . $percent . '</span>';
		} else {
			return '';
		}
		
	}
}

if ( ! function_exists( 'ciloe_get_percent_discount' ) ) {
	function ciloe_get_percent_discount() {
		global $product;
		$percent = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;
				
				for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
					$variation_id = $available_variations[ $i ]['variation_id'];
					
					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 0 );
					}
					
					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}
					
					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$percent .= '-' . $minimumper . '%';
				} else {
					$percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
				}
				
			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = round( ( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ), 0 );
					$percent    .= '-' . $percentage . '%';
				}
			}
		}
		
		return $percent;
	}
}

/* GROUP NEW FLASH */

/* STOCK STATUS */
if ( ! function_exists( 'ciloe_woo_get_stock_status' ) ) {
	function ciloe_woo_get_stock_status() {
		global $product;
		?>
        <div class="product-info-stock-sku">
            <div class="stock available">
                <span class="label-available"><?php esc_html_e( 'Avaiability: ', 'ciloe' ); ?> </span><?php $product->is_in_stock() ? esc_html_e( 'In Stock', 'ciloe' ) : esc_html_e( 'Out Of Stock', 'ciloe' ); ?>
            </div>
        </div>
		<?php
	}
}

/* CUSTOM DESCRIPTION */
if ( ! function_exists( 'ciloe_product_short_description' ) ) {
	function ciloe_product_short_description() {
		global $post;
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( ! $post->post_excerpt ) {
				return;
			}
			?>
            <div class="product-des">
				<?php the_excerpt(); ?>
            </div>
			<?php
		}
	}
}

/* COUNTDOWN IN LOOP */
if ( ! function_exists( 'ciloe_display_product_countdown_in_loop' ) ) {
	function ciloe_display_product_countdown_in_loop() {
		global $product;
		$date  = ciloe_get_max_date_sale( $product->get_id() );
		?>
		<?php if ( $date > 0 ):
			$y = date( 'Y', $date );
			$m = date( 'm', $date );
			$d = date( 'd', $date );
			$h = date( 'h', $date );
			$i = date( 'i', $date );
			$s = date( 's', $date );
			?>
            <div class="product-count-down">
                <div class="ciloe-countdown" data-y="<?php echo esc_attr( $y ); ?>"
                     data-m="<?php echo esc_attr( $m ); ?>"
                     data-d="<?php echo esc_attr( $d ); ?>" data-h="<?php echo esc_attr( $h ); ?>"
                     data-i="<?php echo esc_attr( $i ); ?>" data-s="<?php echo esc_attr( $s ); ?>"></div>
            </div>
		<?php endif; ?>
		<?php
	}
}

// GET DATE SALE
if ( ! function_exists( 'ciloe_get_max_date_sale' ) ) {
	function ciloe_get_max_date_sale( $product_id ) {
		$time = 0;
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => - 1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		
		if ( ! empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "
        SELECT
        meta_value
        FROM $wpdb->postmeta
        WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ")
        ORDER BY meta_value DESC
        LIMIT 1
    "
			);
			
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		
		if ( ! $sale_price_dates_to ) {
			$sale_price_dates_to = get_post_meta( $product_id, '_sale_price_dates_to', true );
			
			if ( $sale_price_dates_to == '' ) {
				$sale_price_dates_to = '0';
			}
			
			return $sale_price_dates_to;
		}
	}
}

/* AJAX MINI CART */

add_filter( 'woocommerce_add_to_cart_fragments', 'ciloe_header_add_to_cart_fragment' );

if ( ! function_exists( ( 'ciloe_header_add_to_cart_fragment' ) ) ) {
	function ciloe_header_add_to_cart_fragment( $fragments ) {
		ob_start();
		
		get_template_part( 'template-parts/header', 'minicart' );
		
		$fragments['div.ciloe-minicart'] = ob_get_clean();
		
		return $fragments;
	}
}

/* AJAX UPDATE WISH LIST */
if ( ! function_exists( ( 'ciloe_update_wishlist_count' ) ) ) {
	function ciloe_update_wishlist_count() {
		if ( function_exists( 'YITH_WCWL' ) ) {
			wp_send_json( YITH_WCWL()->count_products() );
		}
	}
	
	// Wishlist ajaxify update
	add_action( 'wp_ajax_ciloe_update_wishlist_count', 'ciloe_update_wishlist_count' );
	add_action( 'wp_ajax_nopriv_ciloe_update_wishlist_count', 'ciloe_update_wishlist_count' );
}

// Share Single
function ciloe_product_share() {
	if ( function_exists( 'ciloe_toolkit_product_share' ) ) {
		ciloe_toolkit_product_share();
	}
}

// Login
if ( ! function_exists( 'ciloe_login_modal' ) ) {
	/**
	 * Add login modal to footer
	 */
	function ciloe_login_modal() {
		if ( ! shortcode_exists( 'woocommerce_my_account' ) ) {
			return;
		}
		
		if ( is_user_logged_in() ) {
			return;
		}
		
		// Don't load login popup on real mobile when header mobile is enabled
		$enable_header_mobile = ciloe_get_option( 'enable_header_mobile', false );
		if ( $enable_header_mobile && ciloe_is_mobile() ) {
			return;
		}
		
		?>

        <div id="login-popup" class="woocommerce-account md-content mfp-with-anim mfp-hide">
            <div class="ciloe-modal-content">
				<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
            </div>
        </div>
		
		<?php
	}
	
	add_action( 'wp_footer', 'ciloe_login_modal' );
};

// Top Cart
function add_order_tracking_setting( $settings ) {
	$new_settings = array();
	foreach ( $settings as $index => $setting ) {
		$new_settings[ $index ] = $setting;
		
		if ( isset( $setting['id'] ) && 'woocommerce_terms_page_id' == $setting['id'] ) {
			$new_settings['order_tracking_page_id'] = array(
				'title'    => esc_html__( 'Order Tracking Page', 'ciloe' ),
				'desc'     => esc_html__( 'Page content: [woocommerce_order_tracking]', 'ciloe' ),
				'id'       => 'ciloe_order_tracking_page_id',
				'type'     => 'single_select_page',
				'class'    => 'wc-enhanced-select-ciloed',
				'css'      => 'min-width:300px;',
				'desc_tip' => true,
			);
		}
	}
	
	return $new_settings;
}

add_filter( 'woocommerce_get_settings_checkout', 'add_order_tracking_setting', 10 );
if ( ! function_exists( 'ciloe_is_order_tracking_page' ) ) :
	/**
	 * Check if current page is order tracking page
	 *
	 * @return bool
	 */
	function ciloe_is_order_tracking_page() {
		$page_id = get_option( 'ciloe_order_tracking_page_id' );
		$page_id = ciloe_get_translated_object_id( $page_id );
		
		if ( ! $page_id ) {
			return false;
		}
		
		return is_page( $page_id );
	}
endif;

if ( ! function_exists( 'ciloe_is_wishlist_page' ) ) {
	function ciloe_is_wishlist_page() {
		$page_id = get_option( 'yith_wcwl_wishlist_page_id' );
		$page_id = ciloe_get_translated_object_id( $page_id );
		
		if ( ! $page_id ) {
			return false;
		}
		
		return is_page( $page_id );
	}
}

if ( ! function_exists( 'ciloe_get_translated_object_id' ) ) :
	/**
	 * Get translated object ID if the WPML plugin is installed
	 * Return the original ID if this plugin is not installed
	 *
	 * @param int    $id            The object ID
	 * @param string $type          The object type 'post', 'page', 'post_tag', 'category' or 'attachment'. Default is 'page'
	 * @param bool   $original      Set as 'true' if you want WPML to return the ID of the original language element if the translation is missing.
	 * @param bool   $language_code If set, forces the language of the returned object and can be different than the displayed language.
	 *
	 * @return mixed
	 */
	function ciloe_get_translated_object_id( $id, $type = 'page', $original = true, $language_code = false ) {
		if ( function_exists( 'wpml_object_id_filter' ) ) {
			return wpml_object_id_filter( $id, $type, $original, $language_code );
		} elseif ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $id, $type, $original, $language_code );
		}
		
		return $id;
	}
endif;

/**
 * Display a special page header for WooCommerce pages
 */
function ciloe_woocommerce_pages_header() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}
	
	$allow = is_cart() || is_account_page() || ciloe_is_order_tracking_page();
	
	if ( function_exists( 'yith_wcwl_is_wishlist_page' ) ) {
		$allow = $allow || yith_wcwl_is_wishlist_page();
	}
	
	if ( ! $allow ) {
		return;
	}
	
	$page_id = ciloe_get_single_page_id();
	
	$pages = array();
	
	// Prepare for cart links
	$pages['cart'] = sprintf(
		'<li class="shopping-cart-link line-hover %s"><a href="%s">%s<span class="count cart-counter">(%d)</span></a></li>',
		is_cart() ? 'active' : '',
		esc_url( wc_get_cart_url() ),
		esc_html__( 'Shopping Cart', 'ciloe' ),
		WC()->cart->get_cart_contents_count()
	);
	
	// Prepare for wishlist link
	if ( function_exists( 'yith_wcwl_count_products' ) ) {
		$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
		
		$pages['wishlist'] = sprintf(
			'<li class="wishlist-link line-hover %s"><a href="%s">%s<span class="count wishlist-counter">(%d)</span></a></li>',
			yith_wcwl_is_wishlist_page() ? 'active' : '',
			esc_url( get_permalink( $wishlist_page_id ) ),
			esc_html__( 'Wishlist', 'ciloe' ),
			yith_wcwl_count_products()
		);
	}
	
	// Prepare for order tracking link
	if ( $tracking_page_id = get_option( 'ciloe_order_tracking_page_id' ) ) {
		$pages['order_tracking'] = sprintf(
			'<li class="order-tracking-link line-hover %s"><a href="%s">%s</a></li>',
			ciloe_is_order_tracking_page() ? 'active' : '',
			esc_url( get_permalink( ciloe_get_translated_object_id( $tracking_page_id ) ) ),
			esc_html__( 'Order Tracking', 'ciloe' )
		);
	}
	
	// Prepare for account link
	if ( is_user_logged_in() ) {
		$pages['account'] = sprintf(
			'<li class="account-link line-hover %s"><a href="%s">%s</a></li>',
			is_account_page() ? 'active' : '',
			esc_url( wc_get_page_permalink( 'myaccount' ) ),
			esc_html__( 'My Account', 'ciloe' )
		);
	}
	
	// Prepare for login/logout link
	if ( is_user_logged_in() ) {
		$pages['logout'] = sprintf(
			'<li class="logout-link line-hover"><a href="%s">%s</a></li>',
			esc_url( wc_logout_url( wc_get_account_endpoint_url( 'customer-logout' ) ) ),
			esc_html__( 'Logout', 'ciloe' )
		);
		
	} else {
		$pages['login'] = sprintf(
			'<li class="login-link line-hover %s"><a href="%s">%s</a></li>',
			is_account_page() ? 'active' : '',
			esc_url( wc_get_page_permalink( 'myaccount' ) ),
			esc_html__( 'Login', 'ciloe' )
		);
	}
	
	$pages = apply_filters( 'ciloe_woocomemrce_page_header_links', $pages );
	if ( ciloe_is_mobile() ) {
		if ( ! empty( $pages ) ) {
			$page_title = $page_id > 0 ? get_the_title( $page_id ) : '';
			?>
            <div class="woocommerce-page-headermid">
                <div class="container">
					<?php printf( '<h2 class="title-page">%s</h2>', $page_title ); ?>
					<?php get_template_part( 'template-parts/part', 'breadcrumb' ); ?>
					<?php printf( '<div class="woocommerce-page-header"><div class="container"><ul>%s</ul></div></div>', implode( "\n", $pages ) ); ?>
                </div>
            </div>
			<?php
		}
	}
}

add_action( 'ciloe_after_header', 'ciloe_woocommerce_pages_header', 20 );


// Topbar single
add_action( 'ciloe_product_toolbar', 'ciloe_product_toolbar', 5 );
function ciloe_product_toolbar() {
	$ciloe_first_post = get_previous_post();
	$ciloe_last_post  = get_next_post();
	$thumbnail_prev   = array(
		'url'    => '',
		'width'  => 100,
		'height' => 120,
	);
	$thumbnail_next   = array(
		'url'    => '',
		'width'  => 100,
		'height' => 120,
	);
	
	if ( ! empty( $ciloe_first_post ) ) {
		$thumbnail_prev = ciloe_resize_image( get_post_thumbnail_id( $ciloe_first_post->ID ), null, 100, 120, true, true, false );
	}
	if ( ! empty( $ciloe_last_post ) ) {
		$thumbnail_next = ciloe_resize_image( get_post_thumbnail_id( $ciloe_last_post->ID ), null, 100, 120, true, true, false );
	}
	?>

    <div class="product-toolbar">
		<?php
		get_template_part( 'template-parts/part', 'breadcrumb' );
		the_post_navigation(
			array(
				'screen_reader_text' => esc_html__( 'Product navigation', 'ciloe' ),
				'prev_text'          => '<span class="flaticon-back"></span><span class="screen-reader-text">%title</span><figure class="img-thumb-nav">' . ciloe_img_output( $thumbnail_prev ) . '</figure>',
				'next_text'          => '<span class="screen-reader-text">%title</span><figure class="img-thumb-nav"><img src="' . esc_url( $thumbnail_next['url'] ) . '" alt="' . esc_attr__( 'Next', 'ciloe' ) . '" width="' . esc_attr( $thumbnail_next['width'] ) . '" height="' . esc_attr( $thumbnail_next['height'] ) . '"></figure><span class="flaticon-next"></span>',
			) );
		?>
    </div>
	
	<?php
}

// Login Social

if ( ! function_exists( 'ciloe_social_login' ) ) {
	function ciloe_social_login() {
		if ( ! class_exists( 'APSL_Lite_Class' ) ) {
			return;
		}
		echo '<span class="divider">' . esc_attr__( 'OR', 'ciloe' ) . '</span>';
		echo do_shortcode( '[apsl-login-lite login_text=""]' );
	}
	
	add_action( 'woocommerce_login_form_end', 'ciloe_social_login', 10 );
}
// REMOVE CART ITEM

if ( ! function_exists( 'ciloe_remove_cart_item_via_ajax' ) ) {
	function ciloe_remove_cart_item_via_ajax() {
		
		$response = array(
			'message'        => '',
			'fragments'      => '',
			'cart_hash'      => '',
			'mini_cart_html' => '',
			'err'            => 'no'
		);
		
		$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
		$nonce         = isset( $_POST['nonce'] ) ? trim( $_POST['nonce'] ) : '';
		
		if ( $cart_item_key == '' || $nonce == '' ) {
			$response['err'] = 'yes';
			wp_send_json( $response );
		}
		
		if ( ( wp_verify_nonce( $nonce, 'woocommerce-cart' ) ) ) {
			
			if ( $cart_item = WC()->cart->get_cart_item( $cart_item_key ) ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		} else {
			$response['message'] = esc_html__( 'Security check error!', 'ciloe' );
			$response['err']     = 'yes';
			wp_send_json( $response );
		}
		
		ob_start();
		
		get_template_part( 'template-parts/header', 'minicart' );
		
		$mini_cart = ob_get_clean();
		
		$response['fragments']      = apply_filters( 'woocommerce_add_to_cart_fragments', array(
			                                                                                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
		                                                                                )
		);
		$response['cart_hash']      = apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() );
		$response['mini_cart_html'] = $mini_cart;
		
		wp_send_json( $response );
		
		die();
	}
	
	add_action( 'wp_ajax_ciloe_remove_cart_item_via_ajax', 'ciloe_remove_cart_item_via_ajax' );
	add_action( 'wp_ajax_nopriv_ciloe_remove_cart_item_via_ajax', 'ciloe_remove_cart_item_via_ajax' );
}
function ciloe_ajax_add_to_cart_redirect_template() {
	if ( isset( $_REQUEST['ciloe-ajax-add-to-cart'] ) ) {
		get_template_part( 'template-parts/header', 'minicart' );
		exit;
	}
}

add_action( 'wp', 'ciloe_ajax_add_to_cart_redirect_template', 1000 );


// ====================================
/**
 * Wislist in single product
 */
add_action( 'woocommerce_after_add_to_cart_button', 'woocommerce_template_single_sharing', 50 );
add_filter( 'yith_wcwl_positions', 'ciloe_single_product_wislist_button_positions', 999, 1 );
if ( ! function_exists( 'ciloe_single_product_wislist_button_positions' ) ) {
	function ciloe_single_product_wislist_button_positions( $positions ) {
		global $product;
		if ( isset( $positions['add-to-cart']['hook'] ) ) {
			if ( ( gettype( $product ) == "object" ) && $product->is_type( 'variable' ) ) {
				$positions['add-to-cart']['hook'] = 'woocommerce_after_single_variation';
			} else {
				$positions['add-to-cart']['hook'] = 'woocommerce_after_add_to_cart_button';
			}
		}
		if ( isset( $positions['add-to-cart']['priority'] ) ) {
			$positions['add-to-cart']['priority'] = 1;
		}
		
		return $positions;
	}
}
/**
 * Custom title woo
 */

if ( ! function_exists( 'ciloe_woocommerce_page_title' ) ) {
	
	/**
	 * ciloe_woocommerce_page_title function.
	 *
	 * @param  bool $echo
	 *
	 * @return string
	 */
	function ciloe_woocommerce_page_title( $echo = true ) {
		
		if ( is_search() ) {
			$page_title = '<h1>' . esc_html__( 'Search results for: ', 'ciloe' ) . '</h1><span>' . esc_html( get_search_query() ) . '</span>';
			
			if ( get_query_var( 'paged' ) ) {
				$page_title .= sprintf( esc_html__( '&nbsp;&ndash; Page %s', 'ciloe' ), get_query_var( 'paged' ) );
			}
		} elseif ( is_tax() ) {
			
			$page_title = '';
			
		} else {
			$page_title = '';
		}
		
		$page_title = apply_filters( 'ciloe_woocommerce_page_title', $page_title );
		
		if ( $echo ) {
			return $page_title;
		} else {
			return $page_title;
		}
	}
}
if ( ! function_exists( 'ciloe_woocommerce_catalog_ordering' ) ) {
	
	/**
	 * Output the product sorting options.
	 */
	function ciloe_woocommerce_catalog_ordering() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}
		$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
			'menu_order' => __( 'Sort by', 'ciloe' ),
			'popularity' => __( 'Popularity', 'ciloe' ),
			'rating'     => __( 'Rating', 'ciloe' ),
			'date'       => __( 'Newness', 'ciloe' ),
			'price'      => __( 'Price: low', 'ciloe' ),
			'price-desc' => __( 'Price: high', 'ciloe' ),
		) );
		
		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
		$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.
		
		if ( wc_get_loop_prop( 'is_search' ) ) {
			$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'ciloe' ) ), $catalog_orderby_options );
			
			unset( $catalog_orderby_options['menu_order'] );
		}
		
		if ( ! $show_default_orderby ) {
			unset( $catalog_orderby_options['menu_order'] );
		}
		
		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			unset( $catalog_orderby_options['rating'] );
		}
		
		if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
			$orderby = current( array_keys( $catalog_orderby_options ) );
		}
		
		wc_get_template( 'loop/fami-orderby.php', array(
			'catalog_orderby_options' => $catalog_orderby_options,
			'orderby'                 => $orderby,
			'show_default_orderby'    => $show_default_orderby,
		) );
	}
}