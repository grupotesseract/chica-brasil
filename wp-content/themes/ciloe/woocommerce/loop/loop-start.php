<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version       4.0.0
 */
$shop_display_mode  = ciloe_get_option( 'shop_display_mode', 'grid' );
$shop_mode_grid_url = add_query_arg( 'shop_display_mode', 'grid' );
$shop_mode_list_url = add_query_arg( 'shop_display_mode', 'list' );

$products_class = 'product-grid';
if ( $shop_display_mode == "list" ){
	$products_class = 'product-list';
}
?>
<ul class="row products auto-clear equal-container <?php echo esc_attr($products_class); ?> better-height products_list-size-default">
