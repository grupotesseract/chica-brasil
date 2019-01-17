<?php get_header(); ?>
<?php

$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );

/*Shop layout*/
$ciloe_woo_shop_layout  = ciloe_get_option( 'sidebar_shop_page_position', 'left' );
$ciloe_woo_shop_sidebar = ciloe_get_option( 'shop_page_sidebar', 'shop-widget-area' );

if ( is_product() ) {
	$ciloe_woo_shop_layout  = ciloe_get_option( 'sidebar_product_position', 'left' );
	$ciloe_woo_shop_sidebar = ciloe_get_option( 'single_product_sidebar', 'product-widget-area' );
}

// Always full width on real mobile
if ( $enable_single_product_mobile && ciloe_is_mobile() ) {
	$ciloe_woo_shop_layout = 'full';
}

if ( ! is_active_sidebar( $ciloe_woo_shop_sidebar ) ) {
	$ciloe_woo_shop_layout = 'full';
}

/*Main container class*/
$main_container_class   = array();
$main_container_class[] = 'main-container shop-page';
if ( $ciloe_woo_shop_layout == 'full' ) {
	$main_container_class[] = 'no-sidebar';
} else {
	$main_container_class[] = $ciloe_woo_shop_layout . '-sidebar';
}

/*Setting single product*/

$main_content_class   = array();
$main_content_class[] = 'main-content';
if ( $ciloe_woo_shop_layout == 'full' ) {
	$main_content_class[] = 'col-sm-12';
} else {
	$main_content_class[] = 'col-md-9 col-sm-8 has-sidebar';
}

$slidebar_class   = array();
$slidebar_class[] = 'sidebar';
if ( $ciloe_woo_shop_layout != 'full' ) {
	$slidebar_class[] = 'col-md-3 col-sm-4 sidebar-' . $ciloe_woo_shop_layout;
}
?>
    <div class="<?php echo esc_attr( implode( ' ', $main_container_class ) ); ?>">
		<?php if ( ! is_single() ) { ?>
        
			<?php }else{ ?>
            <div class="ciloe-single-container">
				<?php }; ?>
                <div class="row">
                	<?php
					/**
					 * ciloe_before_shop_loop hook.
					 *
					 * @hooked ciloe_shop_top_control - 10
					 */
					if ( ! is_search() ):
						do_action( 'ciloe_before_shop_loop' );
					endif;
					?>
                    <div class="<?php echo esc_attr( implode( ' ', $main_content_class ) ); ?>">
						
                        <div class="main-product">
							<?php
							/**
							 * ciloe_woocommerce_before_loop_start hook
							 */
							do_action( 'ciloe_woocommerce_before_loop_start' );
							
							woocommerce_content();
							
							/**
							 * ciloe_woocommerce_before_loop_start hook
							 */
							do_action( 'ciloe_woocommerce_fater_loop_start' );
							?>
                        </div> <!-- End .main-product-->
                    </div>
					<?php
					/**
					 * woocommerce_after_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action( 'woocommerce_after_main_content' );
					?>
					<?php if ( $ciloe_woo_shop_layout != "full" ): ?>
                        <div class="<?php echo esc_attr( implode( ' ', $slidebar_class ) ); ?>">
							<?php if ( is_active_sidebar( $ciloe_woo_shop_sidebar ) ) : ?>
                                <div id="widget-area" class="widget-area shop-sidebar">
									<?php dynamic_sidebar( $ciloe_woo_shop_sidebar ); ?>
                                </div><!-- .widget-area -->
							<?php endif; ?>
                        </div>
					<?php endif; ?>
                </div>
				<?php if ( ! is_single() ) { ?>
            
			<?php }else{ ?>
        </div>
	<?php }; ?>
    </div>
<?php get_footer(); ?>