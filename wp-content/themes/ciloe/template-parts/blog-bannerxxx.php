<?php
/**
 *  Hero Section
 **/

$post_id = get_the_ID();
$title_blog = ciloe_get_option('blog-page-title', 'Blog');
$css = '';
$type_banner = ciloe_get_option('banner_type', 'no_background');
$img_banner = ciloe_get_option('banner-image');

$post_meta = get_post_meta($post_id, '_custom_metabox_theme_options', true);

if(isset($post_meta['page_height_banner']) && $post_meta['page_height_banner']){
    $ciloe_blog_heading_height = (int)$post_meta['page_height_banner'];
}else{
    $ciloe_blog_heading_height = ciloe_get_option('blog_height_banner', '260');
}

if(isset($post_meta['page_margin_top']) && $post_meta['page_margin_top']){
    $ciloe_blog_margin_top = (int)$post_meta['page_margin_top'];
}else{
    $ciloe_blog_margin_top = ciloe_get_option('blog_margin_top', 0);
}
if(isset($post_meta['page_margin_bottom']) && $post_meta['page_margin_bottom']){
    $ciloe_blog_margin_bottom = (int)$post_meta['page_margin_bottom'];
}else{
    $ciloe_blog_margin_bottom = ciloe_get_option('blog_margin_bottom', 0);
}

if ($type_banner == 'has_background') {
    $css .= 'background-image:  url("' . esc_url($img_banner['image']) . '");';
    $css .= 'background-repeat: ' . esc_attr($img_banner['repeat']) . ';';
    $css .= 'background-position:   ' . esc_attr($img_banner['position']) . ';';
    $css .= 'background-attachment: ' . esc_attr($img_banner['attachment']) . ';';
    $css .= 'background-size:   ' . esc_attr($img_banner['size']) . ';';
    $css .= 'background-color:  ' . esc_attr($img_banner['color']) . ';';
}
$css .= 'min-height:' . $ciloe_blog_heading_height . 'px;';
$css .= 'margin-top:' . $ciloe_blog_margin_top . 'px;';
$css .= 'margin-bottom:' . $ciloe_blog_margin_bottom . 'px;';

?>

<div class="banner-page <?php echo esc_attr($type_banner); ?>" style="<?php echo esc_attr($css); ?>">
    <div class="content-banner">
        <?php if (is_home()): ?>
            <div class="title-page"><?php echo esc_attr($title_blog); ?></div>
        <?php else : ?>
            <?php echo ciloe_get_title(); ?>
        <?php endif; ?>
        <?php if (ciloe_get_option('enable_breadcrumb')): ?>
            <?php get_template_part('template-parts/part', 'breadcrumb'); ?>
        <?php endif; ?>
    </div>
</div>