<?php
/*
 Name:  Header Menu Left - Logo Center - Icons Right
 */
$menu_sticky          = ciloe_get_option( 'enable_sticky_menu', 'smart' );
$header_pos           = ciloe_get_option( 'header_position', 'relative' );
$header_class         = '';
$header_color         = ciloe_get_option( 'header_text_color', '#000' );
$header_bg_color      = ciloe_get_option( 'header_bg_color', '#fff' );
$single_id            = ciloe_get_single_page_id();
$enable_custom_header = false;
if ( $single_id > 0 ) {
	$meta_data = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
	// Override custom header (if request from url)
	if ( isset( $_GET['enable_custom_header'] ) ) {
		$meta_data['enable_custom_header'] = $_GET['enable_custom_header'] == 'yes';
	}
	if ( isset( $meta_data['enable_custom_header'] ) ) {
		$enable_custom_header = $meta_data['enable_custom_header'];
	}
	if ( $enable_custom_header ) {
		$header_color    = $meta_data['header_text_color'];
		$header_bg_color = $meta_data['header_bg_color'];
		$header_pos      = isset( $meta_data['header_position'] ) ? $meta_data['header_position'] : $header_pos;
		$menu_sticky     = $meta_data['enable_sticky_menu'];
	}
}

if ( $menu_sticky == 'normal' ) {
	$header_class = ' menu-sticky-nomal';
} elseif ( $menu_sticky == 'smart' ) {
	$header_class = ' menu-sticky-smart';
}
if ( ciloe_get_option( 'enable_topbar' ) ) {
	$header_class .= ' topbar-enabled';
}

$header_class .= ' header-pos-' . esc_attr( $header_pos );

$header_shadow = false;
if ( $enable_custom_header ) {
	$header_shadow = $meta_data['header_shadow'];
} else {
	$header_shadow = ciloe_get_option( 'header_shadow', false );
}
$class_shadow = '';
if ( $header_shadow ) {
	$class_shadow = 'has-shadow';
}

$enable_info_product_single = ciloe_get_option( 'enable_info_product_single', false );
if ( $enable_info_product_single ) {
	$header_class .= ' sticky-info_single';
}
?>
<header id="header"
        class="site-header header header-mn_l-lg_c-ic_r mn_l lg_c ic_r <?php echo esc_attr( $header_class ); ?>">
    <div class="header-wrap"
         style="background-color: <?php echo esc_attr( $header_bg_color ); ?>; color: <?php echo esc_attr( $header_color ); ?>;">
		<?php get_template_part( 'template-parts/header', 'topbar' ); ?>
        <div class="header-wrap-stick">
            <div class="header-position" style="background-color: <?php echo esc_attr( $header_bg_color ); ?>;">
                <div class="header-container">
                    <div class="main-menu-wrapper"></div>
                    <div class="row">
                        <div class="header-menu horizon-menu col-md-5 col-sm-2">
                            <nav class="main-navigation">
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
								?>
                            </nav>
                        </div>
                        <div class="header-logo col-md-2 col-sm-2">
                            <div class="logo">
								<?php ciloe_get_logo(); ?>
                            </div>
                        </div>
                        <div class="header-control-right col-md-5 col-sm-10">
                            <div class="header-control-wrap">
								<?php if ( class_exists( 'SitePress' ) ) { ?>
                                    <ul class="currency-language">
										<?php
										get_template_part( 'template-parts/header', 'language' );
										?>
                                    </ul>
								<?php } ?>

								<?php /*
								<div class="header-search-box">
								<span class="flaticon-search-1 icons"></span>
								<?php ciloe_search_form(); ?>
								</div>
								*/ ?>
								<?php if ( class_exists( 'WooCommerce' ) ) { ?>
                                    <div class="block-account">
										<?php if ( is_user_logged_in() ) { ?>
											<?php $currentUser = wp_get_current_user(); ?>
                                            <a class="header-userlink"
                                               href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                                               title="<?php esc_attr_e( 'My Account', 'ciloe' ); ?>">
                                                <span class="screen-reader-text"><?php echo sprintf( esc_html__( 'Hi, %s', 'ciloe' ), $currentUser->display_name ); ?></span>
                                                <span class="flaticon-user"></span>
                                            </a>
										<?php } else { ?>
                                            <a href="#login-popup" data-effect="mfp-zoom-in" class="acc-popup">
                                                <span>
                                                    <span class="flaticon-user"></span>
                                                </span>
                                            </a>
										<?php } ?>
                                    </div>
									<?php get_template_part( 'template-parts/header', 'minicart' ); ?>
								<?php }; ?>
                                <a class="menu-bar mobile-navigation" href="javascript:void(0)">
                                    <span class="menu-btn-icon">
                                        <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                                        <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                                        <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<?php
	get_template_part( 'template-parts/hero', 'section' );
	?>

</header>
