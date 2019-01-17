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
 * @version       3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;

$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
$enable_info_product_single   = ciloe_get_option( 'enable_info_product_single', false );
if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
	wc_get_template_part( 'content', 'single-product-mobile' );
	
	return;
}

$product_style                 = ciloe_get_option( 'ciloe_woo_single_product_layout', 'default' );
$show_sum_border               = ciloe_get_option( 'single_product_sum_border', false );
$title_price_stars_outside_sum = ciloe_get_option( 'single_product_title_price_stars_outside_sum', false );
$img_bg_color                  = ciloe_get_option( 'single_product_img_bg_color', 'transparent' );
$product_meta                  = get_post_meta( get_the_ID(), '_custom_product_metabox_theme_options', true );

$product_class     = '';
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
		$product_class .= ' has-title-outside-summary';
		$summary_class .= ' title-price-stars-outside-summary';
	}
}
if ( $product_style != 'big_images' ) {
	$img_bg_color = '';
}

$product_class .= ' ' . esc_attr( $product_style );

?>
<div id="product-<?php the_ID(); ?>" <?php post_class( $product_class ); ?>>
	
	<?php
	$size_guide_id    = isset( $product_meta['ciloe_sizeguide_options'] ) ? $product_meta['ciloe_sizeguide_options'] : 0;
	$on_sizeguide     = isset( $product_meta['size_guide'] ) ? $product_meta['size_guide'] : false;
	$size_guide_query = new WP_Query( array( 'p'              => $size_guide_id,
	                                         'post_type'      => 'sizeguide',
	                                         'posts_per_page' => 1
	                                  ) );
	if ( $on_sizeguide ) :
		if ( $size_guide_query->have_posts() ):
			while ( $size_guide_query->have_posts() ): $size_guide_query->the_post(); ?>
                <div class="modal fade" id="popup-size-guide" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="<?php echo esc_attr__( 'Close', 'ciloe' ); ?>">
								<?php echo esc_html__( 'x', 'ciloe' ); ?>
                            </button>
                            <div class="modal-inner row">
                                <div class="size-guide-content col-lg-12">
                                    <div class="size-guide-inner">
										<?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endwhile;
		endif;
	endif;
	wp_reset_postdata();
	?>
    <div class="product-top-inner">
		<?php if ( ( $product_style != 'gallery_detail' ) && ( $product_style != 'big_images' ) ){ ?>
        <div class="container">
			<?php }; ?>
            <div class="main-content-product clearfix">
                <div class="content-product-inner">
                    <div class="single-left" <?php if ( $product_style == 'big_images' ) {
						echo 'style="background-color: ' . esc_attr( $img_bg_color ) . ';"';
					} ?>>
						<?php if ( $product_style == 'sticky_detail' ) { ?>
							<?php wc_get_template_part( 'single-product/content-detail', 'sticky' ); ?>
						<?php } elseif ( $product_style == 'gallery_detail' ) { ?>
							<?php wc_get_template_part( 'single-product/content-detail', 'gallery' ); ?>
						<?php } elseif ( $product_style == 'big_images' ) { ?>
							<?php wc_get_template_part( 'single-product/content-detail-big', 'image' ); ?>
						<?php } else { ?>
							<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
						<?php }; ?>
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
                        <div class="summary entry-summary <?php echo esc_attr( $summary_class ); ?>">
							<?php
							/**
							 * woocommerce_single_product_summary hook.
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked woocommerce_template_single_rating - 10
							 * @hooked woocommerce_template_single_price - 10
							 * @hooked woocommerce_template_single_excerpt - 20
							 * @hooked woocommerce_template_single_add_to_cart - 30
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 * @hooked WC_Structured_Data::generate_product_data() - 60
							 */
							do_action( 'woocommerce_single_product_summary' );
							?>

                        </div><!-- .summary -->
                    </div> <!--End .detail-content -->
                </div>
            </div><!--End .main-content-product -->
			<?php if ( ( $product_style != 'gallery_detail' ) && ( $product_style != 'big_images' ) ){ ?>
        </div>
	<?php }; ?>
    </div>
    <div class="produc-bottom-inner">
        <div class="container">
			<?php
			/**
			 * woocommerce_after_single_product_summary hook.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 */
			do_action( 'woocommerce_after_single_product_summary' );
			?>
        </div>
    </div>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php if ( $enable_info_product_single ) { ?>
<div class="sticky_info_single_product">
    <div class="container">
        <div class="sticky-thumb-left">
			<?php
			do_action( 'sticky_thumbnail_product_summary' );
			?>
        </div>
        <div class="sticky-info-right">
            <div class="sticky-title">
				<?php
				do_action( 'sticky_info_product_summary' );
				?>
            </div>
			<?php if ( $product->is_purchasable() || $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) { ?>
				<?php if ( $product->is_in_stock() ) { ?>
                    <button type="button"
                            class="ciloe-single-add-to-cart-fixed-top ciloe-single-add-to-cart-btn btn button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                    </button>
				<?php } else { ?>
                    <button type="button"
                            class="ciloe-single-add-to-cart-fixed-top ciloe-single-add-to-cart-btn add-to-cart-out-of-stock btn button"><?php esc_html_e( 'Out Of Stock', 'ciloe' ); ?>
                    </button>
				<?php } ?>
			<?php } ?>
        </div>
    </div>
	<?php } ?>
</div>
