<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="ciloe_account_navigation">
                <h3 class="title"><?php esc_html_e('Minha conta','ciloe');?></h3>
                <?php do_action( 'woocommerce_account_navigation' );?>
            </div>

        </div>
        <div class="col-sm-8">
            <div class="woocommerce-MyAccount-content">

                <?php
                /**
                 * My Account content.
                 * @since 2.6.0
                 */
                do_action( 'woocommerce_account_content' );
                ?>
            </div>
        </div>
    </div>
</div>
