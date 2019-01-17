<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$ciloe_woo_product_style = ciloe_get_option( 'ciloe_shop_product_style', 1 );
$enable_products_sizes   = ciloe_get_option( 'enable_products_sizes', false );
/*
 * 5 items: col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6
 * 4 items: col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-12
 * 3 items: col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-12
 */
$ciloe_woo_bg_items = 3;     // 15
$ciloe_woo_lg_items = 3;     // 15
$ciloe_woo_md_items = 4;     // 15
$ciloe_woo_sm_items = 5;     // 3
$ciloe_woo_xs_items = 6;     // 4
$ciloe_woo_ts_items = 12;    // 6

$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
	$ciloe_woo_bg_items      = 15;     // 15
	$ciloe_woo_lg_items      = 15;     // 15
	$ciloe_woo_md_items      = 15;     // 15
	$ciloe_woo_sm_items      = 3;      // 3
	$ciloe_woo_xs_items      = 4;      // 4
	$ciloe_woo_ts_items      = 6;      // 6
	$ciloe_woo_product_style = 1;      // Always use product style 1 on real mobile
}

// Custom columns
if ( ! $enable_products_sizes ) {
	$ciloe_woo_bg_items = ciloe_get_option( 'ciloe_woo_bg_items', 3 );
	$ciloe_woo_lg_items = ciloe_get_option( 'ciloe_woo_lg_items', 3 );
	$ciloe_woo_md_items = ciloe_get_option( 'ciloe_woo_md_items', 4 );
	$ciloe_woo_sm_items = ciloe_get_option( 'ciloe_woo_sm_items', 4 );
	$ciloe_woo_xs_items = ciloe_get_option( 'ciloe_woo_xs_items', 6 );
	$ciloe_woo_ts_items = ciloe_get_option( 'ciloe_woo_ts_items', 12 );
}

$classes[] = 'product-item';
$classes[] = 'col-bg-' . $ciloe_woo_bg_items;
$classes[] = 'col-lg-' . $ciloe_woo_lg_items;
$classes[] = 'col-md-' . $ciloe_woo_md_items;
$classes[] = 'col-sm-' . $ciloe_woo_sm_items;
$classes[] = 'col-xs-' . $ciloe_woo_xs_items;
$classes[] = 'col-ts-' . $ciloe_woo_ts_items;

$template_style = 'style-' . $ciloe_woo_product_style;
$classes[]      = 'style-' . $ciloe_woo_product_style;
?>

<li <?php post_class( $classes ); ?>>
	<?php wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
</li>
