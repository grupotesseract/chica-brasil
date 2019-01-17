<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $ziss;

$fb_id    = isset( $ziss['facebook_id'] ) ? trim( $ziss['facebook_id'] ) : '';
$fb_token = isset( $ziss['facebook_token'] ) ? trim( $ziss['facebook_token'] ) : '';
$limit    = isset( $ziss['facebook_img_limit'] ) ? intval( $ziss['facebook_img_limit'] ) : 20;

$fb_items_class         = '';
$dont_show_fb_used_imgs = get_option( 'ziss_dont_show_fb_used_imgs', 'yes' );
if ( $dont_show_fb_used_imgs == 'yes' ) {
	$fb_items_class .= ' dont-show-used-imgs';
}

$fb_api_url = '';
if ( $fb_id != '' && $fb_token != '' ) {
	// curl -i -X GET \
	// $fb_api_url = "https://graph.facebook.com/v2.9/me?fields=id%2Cname%2Cphotos.limit(" . $limit . ")%7Balbum%2Cimages%7D&access_token=" . $fb_token;
	$fb_api_url = "https://graph.facebook.com/v2.9/" . $fb_id . "?fields=id%2Cname%2Cphotos.limit(" . $limit . ")%7Balbum%2Cimages%7D&access_token=" . $fb_token;
}

$has_err = false;
$response = wp_remote_get( $fb_api_url );

if ( ! is_wp_error( $response ) ) {
	$response_body = json_decode( $response['body'] );
	$photos        = isset( $response_body->photos ) ? $response_body->photos : null;
	if ( ! empty( $photos->data ) ) {
		?>
        <label class="ziss-info-lb"><input <?php checked( true, $dont_show_fb_used_imgs == 'yes' ); ?>
                    type="checkbox" name="ziss_dont_show_fb_used_imgs"
                    class="ziss-dont-show-fb-used-imgs ziss-dont-show-used-imgs"
                    value="1"><?php esc_html_e( 'Don\'t show used images', 'ziss' ); ?>
        </label>
        <div class="fb-items img-items row <?php echo esc_attr( $fb_items_class ); ?>">
			<?php
			foreach ( $photos->data as $photo_data ) {
				if ( isset( $photo_data->images[0] ) ) {
					$photo_info = array(
						'id'     => $photo_data->id,
						'src'    => $photo_data->images[0]->source,
						'width'  => $photo_data->images[0]->width,
						'height' => $photo_data->images[0]->height
					);
					?>
                    <div class="fb-item img-item col-md-3">
                        <a class="ziss-add-image"
                           data-fb_img_id="<?php echo esc_attr( $photo_info['id'] ); ?>"
                           data-social_source="fb"
                           data-src="<?php echo esc_url( $photo_info['src'] ); ?>" target="_blank"
                           href="<?php echo esc_url( $photo_info['src'] ) ?>"
                           style="background-image: url(<?php echo esc_url( $photo_info['src'] ); ?>);">
                            <img width="<?php echo esc_attr( $photo_info['width'] ); ?>"
                                 height="<?php echo esc_attr( $photo_info['height'] ); ?>"
                                 src="<?php echo esc_url( $photo_info['src'] ); ?>" alt="Facebook"/>
                        </a>
                    </div>
					<?php
				}
			}
			?>
        </div>
		<?php if ( isset( $photos->paging ) ) { ?>
			<?php
			$load_more_nonce = wp_create_nonce( 'ziss_load_more' );
			?>
            <div class="social-pagi-wrap">
                <input type="hidden" class="ziss-load-more-nonce"
                       value="<?php echo esc_attr( $load_more_nonce ); ?>">
                <a href="<?php echo esc_url( $photos->paging->next ); ?>"
                   class="button ziss-btn ziss-load-more-fb-btn"><?php esc_html_e( 'Load more', 'ziss' ); ?></a>
            </div>
		<?php } ?>
		<?php
	} else {
		$has_err = true;
		echo '<p class="updated notice notice-error">' . esc_html__( 'Token expired. Please get the token key again to display the images', 'ziss' ) . '</p>';
	}
	
}
else{
	$has_err = true;
}

if ($has_err) {
	$fb_id_message = sprintf( wp_kses( __( 'You can find the Facebook ID <a href="%s" target="_blank">here</a>. You don\'t need update the Facebook ID if you don\'t (want to) change the Facebook account for this shop.', 'zanmb' ), array(
		'a' => array(
			'href'   => array(),
			'target' => array()
		)
	) ), 'https://findmyfbid.com' );
	if ( $fb_id == '' ) {
		$fb_id_message = sprintf( wp_kses( __( 'You can find the Facebook ID <a href="%s" target="_blank">here</a>.', 'zanmb' ), array(
			'a' => array(
				'href'   => array(),
				'target' => array()
			)
		) ), 'https://findmyfbid.com' );
	}
	?>
    <div class="ziss-get-fb-token-wrap">
        <h3><?php esc_html_e( 'Get New Facebook Access Token', 'ziss' ); ?></h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ziss_db_id"><?php esc_html_e( 'Facebook ID:', 'ziss' ); ?></label>
                    <input type="text" class="form-control ziss-fb-id" id="ziss_db_id" name="ziss_db_id"
                           value="<?php echo esc_attr( $fb_id ); ?>">
                    <p class="help-block"><?php echo $fb_id_message; ?></p>
                </div>
                <div class="form-group">
                    <label for="ziss_fb_token"><?php esc_html_e( 'Access Token Key:', 'ziss' ); ?></label>
                    <input type="text" class="form-control ziss-fb-token" id="ziss_fb_token" name="ziss_fb_token"
                           value="">
                    <p class="help-block"><?php echo sprintf( wp_kses( __( 'Please get the token key <a href="%s" target="_blank">here</a> to display the images', 'zanmb' ), array(
							'a' => array(
								'href'   => array(),
								'target' => array()
							)
						) ), 'https://developers.facebook.com/tools/explorer/145634995501895/?method=GET&path=me%2Fphotos%3Ffields%3Dalbum&version=v2.9' ); ?></p>
                </div>
            </div>
        </div>
    </div>
	<?php
}