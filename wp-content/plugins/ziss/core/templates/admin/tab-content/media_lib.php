<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $ziss;

$image_size   = '640x640';
$image_size   = explode( 'x', $image_size );
$image_size_w = $image_size[0];
$image_size_h = $image_size[1];

$media_items_class   = '';
$dont_show_used_imgs = get_option( 'ziss_dont_show_used_media_imgs', 'yes' );
if ( $dont_show_used_imgs == 'yes' ) {
	$media_items_class .= ' dont-show-used-imgs';
}

$cur_page = 1;

$query_images_args = array(
	'post_type'      => 'attachment',
	'post_mime_type' => 'image',
	'post_status'    => 'inherit',
	'posts_per_page' => 20,
	'paged'          => $cur_page
);

$query_images    = new WP_Query( $query_images_args );
$load_more_nonce = wp_create_nonce( 'ziss_load_more' );

if ( $query_images->have_posts() ) {
	?>
    <label class="ziss-info-lb"><input <?php checked( true, $dont_show_used_imgs == 'yes' ); ?>
                type="checkbox" name="ziss_dont_show_media_used_imgs"
                class="ziss-dont-show-media-used-imgs ziss-dont-show-used-imgs"
                value="1"><?php esc_html_e( 'Don\'t show used images', 'ziss' ); ?>
    </label>
    <div class="media-items img-items row <?php echo esc_attr( $media_items_class ); ?>">
		<?php
		while ( $query_images->have_posts() ) {
			$query_images->the_post();
			$img = ziss_resize_image( get_the_ID(), null, $image_size_w, $image_size_h, true, true, false );
			?>
            <div class="media-item img-item col-md-3">
                <a class="ziss-add-image" data-img_id="<?php echo esc_attr( get_the_ID() ); ?>"
                   data-social_source="media"
                   data-src="<?php echo esc_url( $img['url'] ); ?>"
                   target="_blank" href="<?php echo esc_url( $img['url'] ); ?>"
                   style="background-image: url(<?php echo esc_url( $img['url'] ); ?>);">
                    <img width="<?php echo esc_attr( $img['width'] ); ?>"
                         height="<?php echo esc_attr( $img['height'] ); ?>"
                         src="<?php echo esc_url( $img['url'] ); ?>"
                         alt="Media">
                </a>
            </div>
			<?php
		}
		?>
    </div>
    <div class="social-pagi-wrap">
        <input type="hidden" class="ziss-load-more-nonce" value="<?php echo esc_attr( $load_more_nonce ); ?>">
        <a href="#" data-page="<?php echo esc_attr( $cur_page ); ?>"
           class="button ziss-btn ziss-load-more-media-btn"><?php esc_html_e( 'Load more', 'ziss' ); ?></a>
    </div>
	<?php
}

wp_reset_postdata();