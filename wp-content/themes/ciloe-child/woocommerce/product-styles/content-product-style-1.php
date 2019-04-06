<?php
/*
     Name: Product style 1
     Slug: content-product-style-1
*/

$args = isset($args) ? $args : null;

$_product = wc_get_product( get_page_by_title( get_the_title(), OBJECT, 'product' ) );

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
    </div>
    <div class="product-info equal-elem">
        <div class="info-top">
            <div class="info-meta">
                <h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="product-price"><?php echo $_product->get_price_html(); ?></p>
            </div>
            <a href="<?php the_permalink(); ?>" class="info-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28.11 36.83"><defs><style>.cls-1{fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:2.83px;}</style></defs><g data-name="Layer 2"><g data-name="Camada 1"><path class="cls-1" d="M26.08,35.42H2a.61.61,0,0,1-.61-.66L3.28,10.61A.61.61,0,0,1,3.89,10H24.22a.61.61,0,0,1,.61.57l1.86,24.15A.61.61,0,0,1,26.08,35.42Z"/><path class="cls-1" d="M9,6.49a5.07,5.07,0,1,1,10.15,0"/></g></g></svg>
            </a>
        </div>
        <?php
            do_action( 'ciloe_shop_loop_rating' );
        ?>
    </div>
</div>
