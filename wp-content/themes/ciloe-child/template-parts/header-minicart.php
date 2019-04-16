<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php global $woocommerce; ?>
<?php do_action( 'woocommerce_before_mini_cart' ); ?>
<div class="ciloe-minicart">
    <div class="minicart-canvas-overlay"></div>
    <span class="mini-cart-icon">
        <span class="flaticon-shopping-bag"></span>
        <span class="minicart-number"><?php printf( esc_html__( '%1$s', 'ciloe' ), WC()->cart->cart_contents_count ); ?></span>
    </span>
	<?php if ( ! WC()->cart->is_empty() ) : ?>
        <div class="minicart-content">
            <div class="minicart-content-inner">
                <h3 class="minicart-title">
					<?php echo esc_html__( 'Carrinho', 'ciloe' ); ?>
                </h3>
                <span class="minicart-number-items">
                    <?php printf( esc_html__( '%1$s', 'ciloe' ), WC()->cart->cart_contents_count ); ?>
                </span>
                <div class="close-minicart"></div>
                <div class="minicart-list-items scrollbar-macosx">
                    <ol class="minicart-items">
						<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ): ?>
							<?php $bag_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key ); ?>
							<?php $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key ); ?>

							<?php if ( $bag_product && $bag_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ): ?>

								<?php $product_name = apply_filters( 'woocommerce_cart_item_name', $bag_product->get_title(), $cart_item, $cart_item_key ); ?>
								<?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $bag_product->get_image( 'shop_thumbnail' ), $cart_item, $cart_item_key ); ?>
								<?php $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $bag_product ), $cart_item, $cart_item_key ); ?>
                                <li class="product-cart">
                                    <a class="product-media"
                                       href="<?php echo esc_url( get_permalink( $cart_item['product_id'] ) ) ?>">
										<?php printf( '%s', $bag_product->get_image( array( 100, 100 ) ) ); ?>
                                    </a>
                                    <div class="product-detail">
                                        <h3 class="product-name">
                                            <a href="<?php echo esc_url( get_permalink( $cart_item['product_id'] ) ) ?>"><?php echo esc_html( $product_name ); ?></a>
                                        </h3>
                                        <div class="product-detail-info">
                                            <span class="product-quantity"><?php printf( esc_html__( 'quantidade : %1$s', 'ciloe' ), $cart_item['quantity'] ); ?></span>
											<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="product-cost">' . sprintf( '%s', $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                                        </div>
                                    </div>
                                    <div class="product-remove">
										<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
											'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times"></i></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'ciloe' ),
											esc_attr( $product_id ),
											esc_attr( $bag_product->get_sku() )
										), $cart_item_key
										);
										?>
                                    </div>
                                </li>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ol>
                </div>
                <div class="subtotal">
                    <span class="total-title"><?php echo esc_html__( 'Total: ', 'ciloe' ); ?></span>
                    <span class="total-price"><?php printf( '%s', $woocommerce->cart->get_cart_subtotal() ); ?></span>
                </div>
				<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
                <div class="actions">
                    <a class="button button-viewcart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <span><?php esc_html_e( 'Carrinho', 'ciloe' ); ?></span>
                    </a>
                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                       class="button button-checkout"><span><?php echo esc_html__( 'Checkout', 'ciloe' ); ?></span></a>
                </div>
            </div>
        </div>
	<?php else : ?>
        <div class="minicart-content shopcart-empty">
            <div class="minicart-content-inner">
                <h3 class="minicart-title">
					<?php echo esc_html__( 'Carrinho', 'ciloe' ); ?>
                </h3>
                <span class="minicart-number-items">
                    <?php printf( esc_html__( '%1$s', 'ciloe' ), WC()->cart->cart_contents_count ); ?>
                </span>
                <div class="close-minicart"></div>
                <div class="minicart-list-items">
                    <div class="empty-wrap">
                        <div class="empty-title"><?php esc_html_e( 'Não há produtos no carrinho.', 'ciloe' ); ?></div>
                        <a href="<?php the_permalink( get_page_by_path('produtos') ) ?>" class="to-cart">Ir para a loja</a>
                    </div>
                </div>
                <div class="des-cart">
					<a href="<?php the_permalink( get_page_by_path('politica-de-troca-e-devolucao') ) ?>">Política de Troca e Devolução</a>
				</div>
            </div>
        </div>
	<?php endif; ?>
</div>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
