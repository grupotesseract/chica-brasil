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
	exit; // Exit if accessed directly
}
// Uniq ids
$customer_login_id = uniqid( 'customer_login_' );
$login_id          = uniqid( 'login_' );
$register_id       = uniqid( 'register_' );
$rememberme_id     = uniqid( 'rememberme_' );
$login_tab_id      = uniqid( 'login-tab-' );
$register_tab_id   = uniqid( 'register-tab-' );
$trap_id           = uniqid( 'trap_' );

?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="customer-form" id="<?php echo esc_attr( $customer_login_id ); ?>">

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="<?php echo esc_attr( $login_id ); ?>"
             aria-labelledby="<?php echo esc_attr( $login_tab_id ); ?>">
            <div class="login-icon"><span class="flaticon-user"></span></div>
            <p class="des-login"><?php esc_html_e( 'Create an account to expedite future checkouts, track order history & receive emails, discounts, & special offers', 'ciloe' ); ?></p>
            <div class="login-form block-form">
				
				<?php endif; ?>

                <form method="post" class="login">
					
					<?php do_action( 'woocommerce_login_form_start' ); ?>

                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <input placeholder="<?php esc_attr_e( 'Username', 'ciloe' ); ?> " type="text"
                               class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                               id="<?php echo esc_attr( uniqid( 'username_' ) ); ?>"
                               value="<?php if ( ! empty( $_POST['username'] ) ) {
							       echo esc_attr( $_POST['username'] );
						       } ?>"/>
                    </p>
                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <input placeholder="<?php esc_attr_e( 'Password', 'ciloe' ); ?>"
                               class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                               name="password" id="<?php echo esc_attr( uniqid( 'password_' ) ); ?>"/>
                    </p>
					
					<?php do_action( 'woocommerce_login_form' ); ?>

                    <p class="form-row">
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
                            <span><?php esc_html_e( 'Remember me', 'ciloe' ); ?></span>
                        </label>
                        <a class="woocommerce-LostPassword lost_password"
                           href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'ciloe' ); ?></a>

                        <input type="submit" class="woocommerce-Button button" name="login"
                               value="<?php esc_attr_e( 'Login', 'ciloe' ); ?>"/>
                    </p>
					
					<?php do_action( 'ciloe_action_social_login' ); ?>
					<?php do_action( 'woocommerce_login_form_end' ); ?>

                </form>
				
				<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="<?php echo esc_attr( $register_id ); ?>"
             aria-labelledby="<?php echo esc_attr( $register_tab_id ); ?>">
            <div class="login-icon">
                <span class="flaticon-login"></span>
                <p class="des-res"><?php esc_html_e( 'Register', 'ciloe' ); ?></p>
            </div>
            <div class="register-form block-form">

                <form method="post" class="register">
					
					<?php do_action( 'woocommerce_register_form_start' ); ?>
					
					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                            <input placeholder="<?php esc_attr_e( 'Username', 'ciloe' ); ?>" type="text"
                                   class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                                   id="<?php echo esc_attr( uniqid( 'reg_username_' ) ); ?>"
                                   value="<?php if ( ! empty( $_POST['username'] ) ) {
								       echo esc_attr( $_POST['username'] );
							       } ?>"/>
                        </p>
					
					<?php endif; ?>

                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <input placeholder="<?php esc_attr_e( 'Email address', 'ciloe' ); ?>" type="email"
                               class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                               id="<?php echo esc_attr( uniqid( 'reg_email_' ) ); ?>"
                               value="<?php if ( ! empty( $_POST['email'] ) ) {
							       echo esc_attr( $_POST['email'] );
						       } ?>"/>
                    </p>
					
					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                            <input placeholder="<?php esc_attr_e( 'Password', 'ciloe' ); ?>" type="password"
                                   class="woocommerce-Input woocommerce-Input--text input-text" name="password"
                                   id="<?php echo esc_attr( uniqid( 'reg_password_' ) ); ?>"/>
                        </p>
					
					<?php endif; ?>

                    <!-- Spam Trap -->
                    <div style="<?php echo( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label
                                for="<?php echo esc_attr( $trap_id ); ?>"><?php esc_html_e( 'Anti-spam', 'ciloe' ); ?></label><input
                                type="text"
                                name="email_2"
                                id="<?php echo esc_attr( $trap_id ); ?>"
                                tabindex="-1"
                                autocomplete="off"/>
                    </div>
					
					<?php do_action( 'woocommerce_register_form' ); ?>
					<?php do_action( 'register_form' ); ?>

                    <p class="woocomerce-FormRow form-row">
						<?php
						$register_nonce = wp_create_nonce( 'woocommerce-register' );
						?>
                        <input type="hidden" id="<?php echo esc_attr( uniqid( 'woocommerce-register-nonce-' ) ); ?>"
                               name="woocommerce-register-nonce" value="<?php echo esc_attr( $register_nonce ); ?>"/>
						<?php wp_referer_field(); ?>
                        <input type="submit" class="woocommerce-Button button" name="register"
                               value="<?php esc_attr_e( 'Register', 'ciloe' ); ?>"/>
                    </p>
					
					<?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>
            </div>
        </div>
    </div>
    <div class="spec"><span><?php esc_html_e( 'Or', 'ciloe' ); ?></span></div>
    <ul class="nav nav-tabs nav-tabs-responsive" role="tablist">
        <li role="presentation" class="active">
            <a href="#<?php echo esc_attr( $login_id ); ?>" role="tab"
               id="<?php echo esc_attr( $login_tab_id ); ?>" data-toggle="tab"
               aria-controls="<?php echo esc_attr( $login_id ); ?>">
				<?php esc_html_e( 'Back to login', 'ciloe' ); ?>
            </a>
        </li>
        <li role="presentation">
            <a href="#<?php echo esc_attr( $register_id ); ?>" id="<?php echo esc_attr( $register_tab_id ); ?>"
               role="tab" data-toggle="tab" aria-controls="<?php echo esc_attr( $register_id ); ?>">
				<?php esc_html_e( 'Create an account', 'ciloe' ); ?>
            </a>
        </li>
    </ul>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
