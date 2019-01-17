<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;
$class_gallery = '';
$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
$post_thumbnail_id = get_post_thumbnail_id($post->ID);
$full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
$attachment_ids = $product->get_gallery_image_ids();
$gallery_img_ft = ciloe_resize_image($post_thumbnail_id, null, 759, 918, true, true, false);

if (count($attachment_ids) > 1 && has_post_thumbnail()) {
    $class_gallery = 'ciloe-center-mode';
}
?>
<div class="ciloe-product-single-gallery <?php echo esc_attr($class_gallery);?>">
    <?php
    if ($attachment_ids && has_post_thumbnail()) {
            $html = '<div class="ciloe-product-gallery__image"><a href="javascript:void(0)">';
            $html .= '<img width="' . esc_attr($gallery_img_ft['width']) . '" height="' . esc_attr($gallery_img_ft['height']) . '" src="' . esc_attr($gallery_img_ft['url']) . '">';
            $html .= '</a></div>';
        foreach ($attachment_ids as $attachment_id) {
            $gallery_img_thumbn = ciloe_resize_image($attachment_id, null, 759, 918, true, true, false);
            $html .= '<div class="ciloe-product-gallery__image"><a href="javascript:void(0)">';
            $html .= '<img width="' . esc_attr($gallery_img_thumbn['width']) . '" height="' . esc_attr($gallery_img_thumbn['height']) . '" src="' . esc_attr($gallery_img_thumbn['url']) . '">';
            $html .= '</a></div>';
        }
        echo apply_filters('ciloe_single_product_image', $html);
    }elseif(has_post_thumbnail() && empty($attachment_ids)){
        $html = '<div class="ciloe-product-gallery__image"><a href="javascript:void(0)">';
        $html .= '<img width="' . esc_attr($gallery_img_ft['width']) . '" height="' . esc_attr($gallery_img_ft['height']) . '" src="' . esc_attr($gallery_img_ft['url']) . '">';
        $html .= '</a></div>';
        echo apply_filters('ciloe_single_product_image', $html);
    }
    ?>
</div>
