<?php
/*
     Name: Product style 1
     Slug: content-product-style-1
*/

$args = isset($args) ? $args : null;

?>
<div class="product-inner">
    <div class="product-thumb">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action( 'woocommerce_before_shop_loop_item_title', $args );
        ?>
        <div class="button-loop-action">
        <?php
            do_action( 'woocommerce_after_shop_loop_item' );
            do_action('ciloe_function_shop_loop_item_quickview');
            
        
        ?>
        </div>
    </div>
    <div class="product-info equal-elem">
        <div class="info-top">
    		<?php
    		/**
    		 * woocommerce_after_shop_loop_item_title hook.
    		 *
    		 * @hooked woocommerce_template_loop_rating - 5
    		 * @hooked woocommerce_template_loop_price - 10
    		 */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            do_action( 'ciloe_product_short_description' );
            ?>
            <div class="list-button-action">
                <?php
                do_action('ciloe_function_shop_loop_item_wishlist');
                do_action( 'woocommerce_after_shop_loop_item' );
                do_action('ciloe_function_shop_loop_item_quickview');
                
                ?>
            </div>
        </div>
        <?php
            do_action( 'ciloe_shop_loop_rating' );
        ?>
    </div>
</div>