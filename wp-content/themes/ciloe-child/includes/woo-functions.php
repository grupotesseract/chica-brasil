<?php

// importa o form de login
function woocommerce_checkout_login_form() {
    wc_get_template( 'checkout/form-login.php', array(
        'checkout' => WC()->checkout(),
    ) );
}

remove_action( 'woocommerce_before_checkout_form', 'checkout_coupon_open');
add_action( 'woocommerce_before_checkout_form', 'tesseract_checkout_coupon_open', 7 );

function tesseract_checkout_coupon_open() {
    if ( is_user_logged_in() ) {
        echo '<div class="container"><div class="ciloe-checkout-coupon">';
    } else {
        echo '<div class="container"><div class="ciloe-checkout-coupon" style="display: none">';
    }
}

remove_action( 'woocommerce_cart_is_empty', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_lost_password_form', 'woocommerce_output_all_notices' );
remove_action( 'before_woocommerce_pay', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_before_reset_password_form', 'woocommerce_output_all_notices' );
remove_action( 'woocommerce_single_product_summary', 'ciloe_close_product_mobile_more_detail_wrap' );



?>
