<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;

$product_style                 = ciloe_get_option( 'ciloe_woo_single_product_layout', 'default' );
$show_sum_border               = ciloe_get_option( 'single_product_sum_border', false );
$title_price_stars_outside_sum = ciloe_get_option( 'single_product_title_price_stars_outside_sum', false );
$img_bg_color                  = ciloe_get_option( 'single_product_img_bg_color', 'transparent' );
$product_meta                  = get_post_meta( get_the_ID(), '_custom_product_metabox_theme_options', true );

$summary_class     = '';
$single_left_style = '';
if ( isset( $product_meta['product_style'] ) ) {
	if ( trim( $product_meta['product_style'] != '' ) && $product_meta['product_style'] != 'global' ) {
		$product_style                 = $product_meta['product_style'];
		$show_sum_border               = isset( $product_meta['product_sum_border'] ) ? $product_meta['product_sum_border'] : false;
		$title_price_stars_outside_sum = isset( $product_meta['title_price_stars_outside_sum'] ) ? $product_meta['title_price_stars_outside_sum'] : false;
		$img_bg_color                  = isset( $product_meta['product_img_bg_color'] ) ? $product_meta['product_img_bg_color'] : 'transparent';
	}
}

if ( in_array( $product_style, array( 'default', 'vertical_thumnail', 'sticky_detail' ) ) ) {
	if ( $show_sum_border ) {
		$summary_class .= 'has-border';
	}
	if ( $title_price_stars_outside_sum ) {
		$summary_class .= ' title-price-stars-outside-summary';
	}
}
$class_variable = '';
if ( $product->is_type( 'variable' ) ) { 
	$class_variable = 'has-variable';
}
if ( $product_style != 'big_images' ) {
	$img_bg_color = '';
}


?>
<div class="ciloe-content-single-product-mobile">
    <div id="product-<?php the_ID(); ?>" <?php post_class( $product_style . ' product-mobile-layout' ); ?>>
        <div class="main-content-product clearfix">
            <div class="content-product-inner">
                <div class="single-left" <?php if ( $product_style == 'big_images' ) {
					echo 'style="background-color: ' . esc_attr( $img_bg_color ) . ';"';
				} ?> >
					<?php wc_get_template_part( 'single-product/product', 'image-mobile' ); ?>
					<?php
					/**
					 * @hooked ciloe_show_product_360deg
					 * @hooked ciloe_show_product_video.
					 */
					?>
                    <div class="ciloe-product-button">
						<?php
						do_action( 'ciloe_product_360deg' );
						do_action( 'ciloe_product_video' );
						?>
                    </div>
                </div> <!--End .Single-left -->
                <div class="detail-content">
                    <div class="summary entry-summary <?php echo esc_attr( $class_variable ); ?><?php echo esc_attr( $summary_class ); ?>">
                    	<?php if ( $product->is_type( 'variable' ) ) { ?>
	                    	<div class="variable-mobile">
	                    		<?php do_action( 'fami_variable' );?>
	                    	</div>
						
						<?php }
						/**
						 * woocommerce_single_product_summary hook.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked ciloe_open_product_mobile_more_detail_wrap - 25
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 * @hooked fami_woocommerce_output_product_data_tabs_mobile() - 115
						 * @hooked ciloe_close_product_mobile_more_detail_wrap() - 120
						 */
						do_action( 'woocommerce_single_product_summary' );
						?>
                    </div><!-- .summary -->
                </div> <!--End .detail-content -->
            </div>
        </div><!--End .main-content-product -->
		<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10 // Removed on mobile
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>
    </div><!-- #product-<?php the_ID(); ?> -->
	<?php do_action( 'woocommerce_after_single_product' ); ?>
	<?php if ( $product->is_purchasable() || $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) { ?>
		<?php if ( $product->is_in_stock() ) { ?>
            <button type="button"
                    class="ciloe-single-add-to-cart-btn add-to-cart-fixed-btn btn button"><span
                        class="icon icon-basket"></span> <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
            </button>
		<?php } else { ?>
            <button type="button"
                    class="ciloe-single-add-to-cart-btn add-to-cart-out-of-stock add-to-cart-fixed-btn btn button"><span
                        class="icon icon-basket"></span> <?php esc_html_e( 'Out Of Stock', 'ciloe' ); ?>
            </button>
		<?php } ?>
	<?php } ?>
</div>
