<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$enable_header_mini_cart_mobile      = ciloe_get_option( 'enable_header_mini_cart_mobile', false );
$enable_header_product_search_mobile = ciloe_get_option( 'enable_header_product_search_mobile', false );
$enable_wishlist_mobile              = ciloe_get_option( 'enable_wishlist_mobile', false );

?>

<header class="site-header header site-header-primary site-header-mobile site-header-mobile-default">
    <div class="header-content">
        <div class="main-header header-mobile">
            <nav class="main-navigation" id="site-navigation">
                <div class="main-menu">
                    <div id="box-mobile-menu" class="box-mobile-menu full-height box-mobile-menu-tabs box-tabs">
                        <div class="box-mibile-overlay"></div>
                        <div class="box-mobile-menu-inner">
                            <a href="#" class="close-menu"><span
                                        class=""><?php esc_html_e( 'Fechar', 'ciloe' ); ?></span></a>

                            <div id="mobile-menu-content-tab"
                                 class="box-inner mn-mobile-content-tab box-tab-content active">

                                <div class="mobile-back-nav-wrap">
                                    <a href="#" id="back-menu" class="back-menu"><i
                                                class="pe-7s-angle-left"></i></a>
                                    <span class="box-title"><?php echo esc_html__( 'Menu', 'ciloe' ); ?></span>
                                </div>

    							<?php
    							wp_nav_menu( array(
    								             'menu'            => 'primary',
    								             'theme_location'  => 'primary',
    								             'depth'           => 3,
    								             'container'       => '',
    								             'container_class' => '',
    								             'container_id'    => '',
    								             'menu_class'      => 'clone-main-menu ciloe-nav main-menu',
    								             'fallback_cb'     => 'Ciloe_navwalker::fallback',
    								             'walker'          => new Ciloe_navwalker(),
    							             )
    							);

    							if ( $enable_wishlist_mobile ) {
    								$wish_list_url = ciloe_wisth_list_url();
    								if ( trim( $wish_list_url ) != '' ) {
    									echo '<div class="wish-list-mobile-menu-link-wrap"><a href="' . esc_url( $wish_list_url ) . '" class="wish-list-mobile-menu-link">' . esc_html__( 'My Wishlist', 'ciloe' ) . '</a> <span class="icon icon-heart"></span></div>';
    								}
    							}

    							?>
                            </div>

                            <div id="mobile-login-content-tab" class="box-inner mn-mobile-content-tab box-tab-content">
                                <div class="my-account-wrap">
    								<div class="woocommerce">
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
                            </div>

                            <div class="box-tabs-nav-wrap">
                                <div class="box-tabs-nav">
                                    <a href="#mobile-menu-content-tab" class="box-tab-nav active">
                                        <div class="hamburger hamburger--collapse js-hamburger">
                                            <div class="hamburger-box">
                                                <div class="hamburger-inner"></div>
                                            </div>
                                        </div>
                                        <span class="nav-text"><?php esc_html_e( 'Menu', 'ciloe' ); ?></span>
                                    </a>
                                    <a href="#mobile-login-content-tab"
                                       class="box-tab-nav">
                                        <i class="flaticon-user"></i>
                                        <div class="nav-text account-text">
    										<?php
    										if ( is_user_logged_in() ) {
    											esc_html_e( 'Minha Conta', 'ciloe' );
    										} else {
    											esc_html_e( 'Login', 'ciloe' );
    										}
    										?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="menu-mobile-hamburger-wrap">
                <a href="#" class="mobile-hamburger-navigation">
                    <div class="hamburger hamburger--collapse js-hamburger">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="logo"><?php ciloe_get_logo(); ?></div>
            <div class="header-right">
				<?php
				/*
				if ( $enable_header_product_search_mobile ) {
					?>
                    <div class="header-search-box">
                        <span class="icon-magnifier icons"></span>
						<?php ciloe_search_form(); ?>
                    </div>
					<?php
				}
				*/
				if ( $enable_header_mini_cart_mobile && class_exists( 'WooCommerce' ) ) {
					get_template_part( 'template-parts/header', 'minicart' );
				}
				?>
            </div><!--End .header-right-->
			<?php // ciloe_search_from_mobile(); ?>
        </div> <!-- End .main-header -->
    </div>
	<?php get_template_part( 'template-parts/hero', 'section' ); ?>
</header>
