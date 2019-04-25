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

// redirect to home page after logout
if ( ! function_exists('auto_redirect_after_logout') ) {
    function auto_redirect_after_logout(){
        wp_redirect( home_url() );
        exit();
    }

    add_action('wp_logout','auto_redirect_after_logout');
}

// login modal
remove_action( 'wp_footer', 'ciloe_login_modal' );
if ( ! function_exists( 'tesseract_login_modal' ) ) {
	/**
	 * Add login modal to footer
	 */
	function tesseract_login_modal() {
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
            <div class="tesseract-modal-content">
                <form method="post" class="login col-md-12">

                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <div class="negative mg-hz-15 clearfix">
                        <p class="woocommerce-FormRow woocommerce-FormRow--wide col-md-6">
                            <label for="">Email <span class="required">*</span></label>
                            <input type="text"
                                   class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                                   id="<?php echo esc_attr( uniqid( 'username_' ) ); ?>"
                                   value="<?php if ( ! empty( $_POST['username'] ) ) {
                                       echo esc_attr( $_POST['username'] );
                                   } ?>"/>
                        </p>
                        <p class="woocommerce-FormRow woocommerce-FormRow--wide col-md-6">
                            <label for="">Senha <span class="required">*</span></label>
                            <input type="password"
                                   class="woocommerce-Input woocommerce-Input--text input-text"
                                   name="password" id="<?php echo esc_attr( uniqid( 'password_' ) ); ?>"/>
                        </p>
                    </div>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <p>
                        <?php
                        $login_nonce = wp_create_nonce( 'woocommerce-login' );
                        ?>
                        <input type="hidden" id="<?php echo esc_attr( uniqid( 'woocommerce-login-nonce-' ) ); ?>"
                               name="woocommerce-login-nonce" value="<?php echo esc_attr( $login_nonce ); ?>"/>
                        <?php wp_referer_field(); ?>
                        <label for="<?php echo esc_attr( $rememberme_id ); ?>" class="rememberme">
                            <input class="woocommerce-Input woocommerce-Input--checkbox" name="rememberme"
                                   type="checkbox" id="<?php echo esc_attr( $rememberme_id ); ?>"
                                   value="forever"/>
                            <span><?php esc_html_e( 'Mantenha-me conectado', 'ciloe' ); ?></span>
                        </label>

                        <span class="login-cta-wrapper">
                            <input type="submit" class="main-loja-btn" name="login"
                                    value="<?php esc_attr_e( 'Login', 'ciloe' ); ?>"/>
                            <a class="woocommerce-LostPassword lost_password"
                               href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Esqueceu sua senha?', 'ciloe' ); ?></a>
                        </span>
                    </p>

                    <?php do_action( 'ciloe_action_social_login' ); ?>
                    <?php do_action( 'woocommerce_login_form_end' ); ?>

                </form>
            </div>
        </div>

		<?php
	}

	add_action( 'wp_footer', 'tesseract_login_modal' );
};

?>
