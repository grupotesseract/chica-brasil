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
$banner_class = '';
$page_banner_type     = ciloe_get_option( 'shop_banner_type');
$page_banner_type     = ciloe_get_option( 'shop_banner_type');
if ( $page_banner_type == 'no_background' ) {
    $banner_class = 'no-banner';
}
?>
<div id="page-wrapper" class="page-wrapper <?php echo esc_attr( $banner_class ); ?>">
    <div class="body-overlay"></div>
    <div class="minicart-canvas-overlay"></div>
	<?php if ( ! $enable_header_mobile || ( $enable_header_mobile && ! ciloe_is_mobile() ) ) { ?>
        <div id="box-mobile-menu" class="box-mobile-menu full-height">
            <a href="javascript:void(0);" id="back-menu" class="back-menu"><i class="pe-7s-angle-left"></i></a>
            <span class="box-title"><?php echo esc_html__( 'Menu', 'ciloe' ); ?></span>
            <a href="javascript:void(0);" class="close-menu"><i class="pe-7s-close"></i></a>
            <div class="box-inner"></div>
        </div>
	<?php } ?>
    <div class="wrap-page">
		<?php ciloe_get_header(); ?>
