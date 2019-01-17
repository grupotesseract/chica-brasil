<?php

/*
 * Header banner (Hero section)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_singular( 'post' ) ) {
	return;
}

$single_id            = ciloe_get_single_page_id();
$enable_custom_banner = false;
$meta_data            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
$page_title           = '';
if ( $single_id > 0 ) {
	$page_title = get_the_title( $single_id );
} else {
	if ( is_archive() || is_tag() ) {
		if ( is_category() ) {
			$page_title = single_cat_title( '', false );
		} else {
			$page_title = get_the_archive_title();
		}
	}
	if ( is_search() ) {
		if ( have_posts() ) {
			$page_title = sprintf( esc_html__( 'Search Results for: %s', 'ciloe' ), get_search_query() );
		} else {
			$page_title = esc_html__( 'Nothing Found', 'ciloe' );
		}
	}
}

$page_title = strip_tags( $page_title );

if ( isset( $meta_data['enable_custom_banner'] ) ) {
	$enable_custom_banner = $meta_data['enable_custom_banner'];
	// Check request hero_section_type for custom banner
	if ( isset( $_GET['hero_section_type'] ) ) {
		$meta_data['hero_section_type'] = $_GET['hero_section_type'];
	}
}

if ( class_exists( 'WooCommerce' ) ) {
	if ( is_shop() ) {
		$enable_shop_mobile = ciloe_get_option( 'enable_shop_mobile', true );
		if ( $enable_shop_mobile && ciloe_is_mobile() ) {
			return;
		}
	}
	if ( is_product() || ciloe_is_order_tracking_page() ) {
		return;
	}
	if ( is_woocommerce() ) {
		$page_title = woocommerce_page_title( false );
	}
}

$header_color = '';

if ( $enable_custom_banner ) {
	$enable_header_mobile                 = ciloe_get_option( 'enable_header_mobile', false );
	$show_hero_section                    = true;
	
	// Check hero section on mobile is enabled or disabled
	if ( $enable_header_mobile && ciloe_is_mobile() ) {
		$enable_hero_section_mobile = isset( $meta_data['show_hero_section_on_header_mobile'] ) ? $meta_data['show_hero_section_on_header_mobile'] : false;
		if ( ! $enable_hero_section_mobile ) {
			$show_hero_section = false;
		}
	}
	
	if ( ! $show_hero_section ) {
		return;
	}
	
	switch ( $meta_data['hero_section_type'] ) {
		case 'rev_background':
			if ( $meta_data['ciloe_metabox_header_rev_slide'] != '' && class_exists( 'RevSliderOutput' ) ) {
				?>
                <div class="slider-rev-wrap">
					<?php RevSliderOutput::putSlider( $meta_data['ciloe_metabox_header_rev_slide'] ); ?>
                </div>
				<?php
			}
			break;
		case 'has_background':
		case 'no_background' :
			$page_banner_type = $meta_data['hero_section_type'];
			$page_img_banner              = $meta_data['bg_banner_page'];
			$ciloe_page_heading_height    = $meta_data['page_height_banner'];
			$ciloe_page_margin_top        = $meta_data['page_margin_top'];
			$ciloe_page_margin_bottom     = $meta_data['page_margin_bottom'];
			$ciloe_page_banner_breadcrumb = $meta_data['page_banner_breadcrumb'];
			$is_banner_full_width         = $meta_data['page_banner_full_width'];
			$css                          = '';
			if ( $page_banner_type == 'has_background' ) {
				$css          .= 'background-image:  url("' . esc_url( $page_img_banner['image'] ) . '");';
				$css          .= 'background-repeat: ' . esc_attr( $page_img_banner['repeat'] ) . ';';
				$css          .= 'background-position:   ' . esc_attr( $page_img_banner['position'] ) . ';';
				$css          .= 'background-attachment: ' . esc_attr( $page_img_banner['attachment'] ) . ';';
				$css          .= 'background-size:   ' . esc_attr( $page_img_banner['size'] ) . ';';
				$css          .= 'background-color:  ' . esc_attr( $page_img_banner['color'] ) . ';';
				$header_color = $meta_data['colortext_banner_page'];
			}
			$css .= 'min-height:' . esc_attr( $ciloe_page_heading_height ) . 'px;';
			$css .= 'margin-top:' . esc_attr( $ciloe_page_margin_top ) . 'px;';
			$css .= 'margin-bottom:' . esc_attr( $ciloe_page_margin_bottom ) . 'px;';
			
			if ( ! $is_banner_full_width ) { ?>
                <div class="container">
                <div class="row">
			<?php } ?>
            <div class="rev_slider banner-page <?php echo esc_attr( $page_banner_type ); ?>"
                 style='<?php echo esc_attr( $css ); ?>'>
                <div class="content-banner" <?php if ( $page_banner_type == 'has_background' ) {
					echo 'style="color: ' . esc_attr( $header_color ) . ';"';
				} ?>>
                    <div class="container">
						<?php if ( ! is_front_page() ) { ?>
                            <h2 class="title-page page-title" <?php if ( $page_banner_type == 'has_background' ) {
								echo 'style="color: ' . esc_attr( $header_color ) . ';"';
							} ?>><?php echo esc_html( $page_title ); ?></h2>
						<?php } ?>
						<?php if ( ! is_front_page() && $ciloe_page_banner_breadcrumb ) {
							get_template_part( 'template-parts/part', 'breadcrumb' );
						}; ?>
                    </div>
                </div>
            </div>
			<?php
			if ( ! $is_banner_full_width ) { ?>
                </div>
                </div>
			<?php }
			break;
		case 'disable':
			break;
		default:
			break;
	}
} else {
	$default_page_banner_height = 280;
	if ( is_front_page() && is_home() ) {
		$default_page_banner_height = 50;
	}
	
	$page_banner_type     = ciloe_get_option( 'page_banner_type', 'no_background' );
	$page_banner_image    = ciloe_get_option( 'page_banner_image' );
	$is_banner_full_width = ciloe_get_option( 'page_banner_full_width', true );
	$page_banner_height   = ciloe_get_option( 'page_height_banner', $default_page_banner_height );
	$page_margin_top      = ciloe_get_option( 'page_margin_top', 0 );
	$page_margin_bottom   = ciloe_get_option( 'page_margin_bottom', 40 );
	$header_color         = ciloe_get_option( 'colortext_banner_page' );
	
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$page_banner_type     = ciloe_get_option( 'shop_banner_type', 'no_background' );
			$page_banner_image    = ciloe_get_option( 'shop_banner_image' );
			$is_banner_full_width = true;
			$page_banner_height   = ciloe_get_option( 'shop_banner_height', 280 );
			$page_margin_top      = ciloe_get_option( 'shop_margin_top', 0 );
			$page_margin_bottom   = ciloe_get_option( 'shop_margin_bottom', 40 );
			$header_color         = ciloe_get_option( 'colortext_shop_page' );
		}
	}
	
	$css = '';
	if ( $page_banner_type == 'has_background' ) {
		$css .= 'background-image:  url("' . esc_url( $page_banner_image['image'] ) . '");';
		$css .= 'background-repeat: ' . esc_attr( $page_banner_image['repeat'] ) . ';';
		$css .= 'background-position:   ' . esc_attr( $page_banner_image['position'] ) . ';';
		$css .= 'background-attachment: ' . esc_attr( $page_banner_image['attachment'] ) . ';';
		$css .= 'background-size:   ' . esc_attr( $page_banner_image['size'] ) . ';';
		$css .= 'background-color:  ' . esc_attr( $page_banner_image['color'] ) . ';';
	}
	$css .= 'min-height:' . intval( $page_banner_height ) . 'px;';
	$css .= 'margin-top:' . intval( $page_margin_top ) . 'px;';
	$css .= 'margin-bottom:' . intval( $page_margin_bottom ) . 'px;';
	
	if ( ! $is_banner_full_width ) { ?>
        <div class="container">
        <div class="row">
	<?php } ?>
    <div class="banner-page hero-banner-page <?php echo esc_attr( $page_banner_type ); ?>"
         style='<?php echo esc_attr( $css ); ?>'>
        <div class="content-banner" <?php if ( $page_banner_type == 'has_background' ) {
			echo 'style="color: ' . esc_attr( $header_color ) . ';"';
		} ?>>
            <div class="container">
				<?php if ( ! is_front_page() ) { ?>
                    <h2 class="title-page page-title" <?php if ( $page_banner_type == 'has_background' ) {
						echo 'style="color: ' . esc_attr( $header_color ) . ';"';
					} ?>><?php echo esc_html( $page_title ); ?></h2>
				<?php } ?>
				<?php get_template_part( 'template-parts/part', 'breadcrumb' ); ?>
            </div>
        </div>
    </div>
	<?php
	if ( ! $is_banner_full_width ) { ?>
        </div>
        </div>
	<?php }
}

