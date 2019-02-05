<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link       https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package    WordPress
 * @subpackage Ciloe
 * @since      1.0
 * @version    1.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,700" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
$enable_header_mobile = ciloe_get_option( 'enable_header_mobile', false );
$wrapper_class        = '';
$menu_sticky          = ciloe_get_option( 'enable_sticky_menu', 'smart' );
$single_id            = ciloe_get_single_page_id();
if ( $single_id > 0 ) {
	$enable_custom_header = false;
	$meta_data            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
	if ( $enable_custom_header ) {
		$menu_sticky = $meta_data['enable_sticky_menu'];
	}
}
if ( $menu_sticky == 'normal' ) {
	$wrapper_class = 'wrapper_menu-sticky-nomal';
} elseif ( $menu_sticky == 'smart' ) {
	$wrapper_class = ' wrapper_menu-sticky';
}
$sticky_info_w              = '';
$enable_info_product_single = ciloe_get_option( 'enable_info_product_single', false );
if ( $enable_info_product_single ) {
	$sticky_info_w = 'sticky-info_single_wrap';
}
?>
<div id="page-wrapper"
     class="page-wrapper <?php echo esc_attr( $wrapper_class ); ?> <?php echo esc_attr( $sticky_info_w ); ?>">
    <div class="body-overlay"></div>
    <div class="sidebar-canvas-overlay"></div>
	<?php if ( ! $enable_header_mobile || ( $enable_header_mobile && ! ciloe_is_mobile() ) ) { ?>
        <div id="box-mobile-menu" class="box-mobile-menu full-height">
            <a href="javascript:void(0);" id="back-menu" class="back-menu"><i class="pe-7s-angle-left"></i></a>
            <span class="box-title"><?php echo esc_html__( 'Menu', 'ciloe' ); ?></span>
            <a href="javascript:void(0);" class="close-menu"><i class="pe-7s-close"></i></a>
            <div class="box-inner"></div>
        </div>
	<?php } ?>
	<?php ciloe_get_header(); ?>
	<?php if ( is_search() && class_exists( 'WooCommerce' ) ) {
		get_template_part( 'template_parts/search', 'heading' );
	} ?>
	<?php
	if ( is_singular( 'product' ) ):
		do_action( 'ciloe_product_toolbar' );
	endif;
	?>
