<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $ziss;

$instagram_id    = isset( $ziss['instagram_id'] ) ? trim( $ziss['instagram_id'] ) : '';
$instagram_token = isset( $ziss['instagram_token'] ) ? $instagram_id . '.' . trim( $ziss['instagram_token'] ) : '';
$limit           = isset( $ziss['instagram_img_limit'] ) ? intval( $ziss['instagram_img_limit'] ) : 20;

// http://instagram.pixelunion.net/
// {id}.{token}
// 2267639447.1677ed0.eade9f2bbe8245ea8bdedab984f3b4c3

$instagram_items_class     = '';
$dont_show_insta_used_imgs = get_option( 'ziss_dont_show_insta_used_imgs', 'yes' );
if ( $dont_show_insta_used_imgs == 'yes' ) {
	$instagram_items_class .= ' dont-show-used-imgs';
}

?>

<?php if ( $instagram_id == '' || $instagram_token == '' ) {

} else {
	$transient_var     = $instagram_id . '_' . $limit;
	$instagram_api_url = 'https://api.instagram.com/v1/users/' . esc_attr( $instagram_id ) . '/media/recent/?access_token=' . esc_attr( $instagram_token ) . '&count=' . esc_attr( $limit );
	// $instagram_api_url = 'https://api.instagram.com/v1/tags/puppy/media/recent?access_token=' . esc_attr( $instagram_token );
	
	$response = wp_remote_get( $instagram_api_url );
	if ( ! is_wp_error( $response ) ) {
		$response_body = json_decode( $response['body'] );
		
		if ( $response_body->meta->code !== 200 ) {
			echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'cosre' ) . '</p>';
		}
		
		$items_as_objects = $response_body->data;
		$items            = array();
		foreach ( $items_as_objects as $item_object ) {
			if ( isset( $item_object->images->standard_resolution ) ) {
				$item['id']     = $item_object->id;
				$item['link']   = $item_object->link;
				$item['src']    = $item_object->images->standard_resolution->url;
				$item['width']  = $item_object->images->standard_resolution->width;
				$item['height'] = $item_object->images->standard_resolution->height;
				$items[]        = $item;
			} else {
				$item['id']     = $item_object->id;
				$item['link']   = $item_object->link;
				$item['src']    = $item_object->images->low_resolution->url;
				$item['width']  = $item_object->images->standard_resolution->width;
				$item['height'] = $item_object->images->standard_resolution->height;
				$items[]        = $item;
			}
		}
		
		set_transient( $transient_var, $items, 60 * 60 );
		
		if ( isset( $items ) ) {
			if ( ! empty( $items ) ) {
				?>
                <label class="ziss-info-lb"><input <?php checked( true, $dont_show_insta_used_imgs == 'yes' ); ?>
                            type="checkbox" name="ziss_dont_show_insta_used_imgs"
                            class="ziss-dont-show-insta-used-imgs ziss-dont-show-used-imgs"
                            value="1"><?php esc_html_e( 'Don\'t show used images', 'ziss' ); ?>
                </label>
                <div class="instagram-items img-items row <?php echo esc_attr( $instagram_items_class ); ?>">
					<?php foreach ( $items as $item ) { ?>
                        <div class="instagram-item img-item col-md-3">
                            <a class="ziss-add-image"
                               data-instagram_img_id="<?php echo esc_attr( $item['id'] ); ?>"
                               data-social_source="instagram"
                               data-src="<?php echo esc_url( $item['src'] ); ?>" target="_blank"
                               href="<?php echo esc_url( $item['link'] ) ?>"
                               style="background-image: url(<?php echo esc_url( $item['src'] ); ?>);">
                                <img width="<?php echo esc_attr( $item['width'] ); ?>"
                                     height="<?php echo esc_attr( $item['height'] ); ?>"
                                     src="<?php echo esc_url( $item['src'] ); ?>" alt="Instagram"/>
                            </a>
                        </div>
					<?php }; ?>
                </div>
				<?php if ( isset( $response_body->pagination->next_url ) ) { ?>
					<?php
					$load_more_nonce = wp_create_nonce( 'ziss_load_more' );
					?>
                    <div class="social-pagi-wrap">
                        <input type="hidden" class="ziss-load-more-nonce"
                               value="<?php echo esc_attr( $load_more_nonce ); ?>">
                        <a href="<?php echo esc_url( $response_body->pagination->next_url ); ?>"
                           class="button ziss-btn ziss-load-more-instagram-btn"><?php esc_html_e( 'Load more', 'ziss' ); ?></a>
                    </div>
				<?php } ?>
				<?php
			}
		}
		
	} else {
		$error_string = $response->get_error_message();
		echo '<div class="error ziss-error"><p>' . $error_string . '</p></div>';
	}
} ?>
