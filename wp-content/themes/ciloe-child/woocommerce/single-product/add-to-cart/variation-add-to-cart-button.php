<?php
/**
 * Single variation cart button
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
	<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
		) );

		do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>
	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="tesseract-add-to-cart-button button alt">
        Adicionar ao carrinho
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28.11 36.83"><defs><style>.cls-1{fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:2.83px;}</style></defs><title>Asset 1</title><g data-name="Layer 2"><g data-name="Camada 1"><path class="cls-1" d="M26.08,35.42H2a.61.61,0,0,1-.61-.66L3.28,10.61A.61.61,0,0,1,3.89,10H24.22a.61.61,0,0,1,.61.57l1.86,24.15A.61.61,0,0,1,26.08,35.42Z"/><path class="cls-1" d="M9,6.49a5.07,5.07,0,1,1,10.15,0"/></g></g></svg>
    </button>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
