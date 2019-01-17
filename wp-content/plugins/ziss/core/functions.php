<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function ziss_post_exist_by_id( $post_id, $post_status = 'publish' ) {
	global $wpdb;
	$sql = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE ID='%d' AND post_status='%s' LIMIT 0, 1", array(
		$post_id,
		$post_status
	) );
	$row = $wpdb->get_row( $sql );
	
	return isset( $row );
}

/**
 * @return array
 */
function ziss_get_woocommerce_products_list() {
	global $wpdb;
	
	$full_products_list = array();
	
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $full_products_list;
	}
	
	$sql  = "SELECT * FROM {$wpdb->prefix}posts WHERE post_type='product' AND post_status='publish'";
	$rows = $wpdb->get_results( $sql );
	
	if ( $rows ) {
		foreach ( $rows as $row ) {
			$product              = new WC_Product( $row->ID );
			$full_products_list[] = array(
				'title'      => $product->get_title(),
				'price_html' => $product->get_price_html(),
				'id'         => $product->get_id()
			);
		}
	}
	
	return $full_products_list;
}

function ziss_products_select( $selected = 0, $class = '', $echo = true ) {
	$html = '';
	
	$products_list = ziss_get_woocommerce_products_list();
	
	if ( ! empty( $products_list ) ) {
		$html = '<option data-thumb_src="" value="0">' . esc_html__( ' ------ Chose Product ------ ', 'ziss' ) . '</option>';
		foreach ( $products_list as $product ) {
			$thumb = ziss_resize_image( get_post_thumbnail_id( $product['id'] ), null, 150, 150, true, true, false );
			$html  .= '<option data-thumb_src="' . esc_url( $thumb['url'] ) . '" ' . selected( $selected == $product['id'], true, false ) . ' value="' . esc_attr( $product['id'] ) . '">' . esc_html( $product['title'] ) . '</option>';
		}
	} else {
		$html = '<option data-thumb_src="" value="0">' . esc_html__( ' ------ No Product ------ ', 'ziss' ) . '</option>';
	}
	
	$html = '<select class="ziss-product-select ' . esc_attr( $class ) . '">' . $html . '</select>';
	
	if ( $echo ) {
		echo $html;
	}
	
	return $html;
}

if ( ! function_exists( 'ziss_no_image' ) ) {
	
	/**
	 * No image generator
	 *
	 * @since 1.0
	 *
	 * @param $size : array, image size
	 * @param $echo : bool, echo or return no image url
	 **/
	function ziss_no_image(
		$size = array( 'width' => 500, 'height' => 500 ), $echo = false, $transparent = false
	) {
		
		$noimage_dir = ZISS_DIR_PATH;
		$noimage_uri = ZISS_BASE_URL;
		
		$suffix = ( $transparent ) ? '_transparent' : '';
		
		if ( ! is_array( $size ) || empty( $size ) ):
			$size = array( 'width' => 500, 'height' => 500 );
		endif;
		
		if ( ! is_numeric( $size['width'] ) && $size['width'] == '' || $size['width'] == null ):
			$size['width'] = 'auto';
		endif;
		
		if ( ! is_numeric( $size['height'] ) && $size['height'] == '' || $size['height'] == null ):
			$size['height'] = 'auto';
		endif;
		
		// base image must be exist
		$img_base_fullpath = $noimage_dir . '/assets/images/noimage/no_image' . $suffix . '.png';
		$no_image_src      = $noimage_uri . '/assets/images/noimage/no_image' . $suffix . '.png';
		
		
		// Check no image exist or not
		if ( ! file_exists( $noimage_dir . '/assets/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) && is_writable( $noimage_dir . '/assets/images/noimage/' ) ):
			
			$no_image = wp_get_image_editor( $img_base_fullpath );
			
			if ( ! is_wp_error( $no_image ) ):
				$no_image->resize( $size['width'], $size['height'], true );
				$no_image_name = $no_image->generate_filename( $size['width'] . 'x' . $size['height'], $noimage_dir . '/assets/images/noimage/', null );
				$no_image->save( $no_image_name );
			endif;
		
		endif;
		
		// Check no image exist after resize
		$noimage_path_exist_after_resize = $noimage_dir . '/assets/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
		
		if ( file_exists( $noimage_path_exist_after_resize ) ):
			$no_image_src = $noimage_uri . '/assets/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
		endif;
		
		if ( $echo ):
			echo esc_url( $no_image_src );
		else:
			return esc_url( $no_image_src );
		endif;
		
	}
}

if ( ! function_exists( 'ziss_resize_image' ) ) {
	/**
	 * @param int    $attach_id
	 * @param string $img_url
	 * @param int    $width
	 * @param int    $height
	 * @param bool   $crop
	 * @param bool   $place_hold        Using place hold image if the image does not exist
	 * @param bool   $use_real_img_hold Using real image for holder if the image does not exist
	 * @param string $solid_img_color   Solid placehold image color (not text color). Random color if null
	 *
	 * @since 1.0
	 * @return array
	 */
	function ziss_resize_image(
		$attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true,
		$use_real_img_hold = true, $solid_img_color = null
	) {
		// this is an attachment, so we have the ID
		$image_src = array();
		if ( $attach_id ) {
			$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
			$actual_file_path = get_attached_file( $attach_id );
			// this is not an attachment, let's use the image url
		} else {
			if ( $img_url ) {
				$file_path        = str_replace( get_site_url(), ABSPATH, $img_url );
				$actual_file_path = rtrim( $file_path, '/' );
				if ( ! file_exists( $actual_file_path ) ) {
					$file_path        = parse_url( $img_url );
					$actual_file_path = rtrim( ABSPATH, '/' ) . $file_path['path'];
				}
				if ( file_exists( $actual_file_path ) ) {
					$orig_size    = getimagesize( $actual_file_path );
					$image_src[0] = $img_url;
					$image_src[1] = $orig_size[0];
					$image_src[2] = $orig_size[1];
				} else {
					$image_src[0] = '';
					$image_src[1] = 0;
					$image_src[2] = 0;
				}
			}
		}
		if ( ! empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
			$file_info = pathinfo( $actual_file_path );
			$extension = '.' . $file_info['extension'];
			
			// the image path without the extension
			$no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];
			
			$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			
			// checking if the file size is larger than the target size
			// if it is smaller or the same size, stop right here and return
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
				
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					$vt_image        = array( 'url' => $cropped_img_url, 'width' => $width, 'height' => $height, );
					
					return $vt_image;
				}
				
				// $crop = false
				if ( $crop == false ) {
					// calculate the size proportionaly
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
					
					// checking if the file already exists
					if ( file_exists( $resized_img_path ) ) {
						$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
						
						$vt_image = array(
							'url'    => $resized_img_url,
							'width'  => $proportional_size[0],
							'height' => $proportional_size[1],
						);
						
						return $vt_image;
					}
				}
				
				// no cache files - let's finally resize it
				$img_editor = wp_get_image_editor( $actual_file_path );
				
				if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
					return array( 'url' => '', 'width' => '', 'height' => '', );
				}
				
				$new_img_path = $img_editor->generate_filename();
				
				if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
					return array( 'url' => '', 'width' => '', 'height' => '', );
				}
				if ( ! is_string( $new_img_path ) ) {
					return array( 'url' => '', 'width' => '', 'height' => '', );
				}
				
				$new_img_size = getimagesize( $new_img_path );
				$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
				
				// resized output
				$vt_image = array( 'url' => $new_img, 'width' => $new_img_size[0], 'height' => $new_img_size[1], );
				
				return $vt_image;
			}
			
			// default output - without resizing
			$vt_image = array( 'url' => $image_src[0], 'width' => $image_src[1], 'height' => $image_src[2], );
			
			return $vt_image;
		} else {
			if ( $place_hold ) {
				$width  = intval( $width );
				$height = intval( $height );
				
				// Real image place hold (https://unsplash.it/)
				if ( $use_real_img_hold ) {
					$random_time = time() + rand( 1, 100000 );
					$vt_image    = array(
						'url'    => 'https://unsplash.it/' . $width . '/' . $height . '?random&time=' . $random_time,
						'width'  => $width,
						'height' => $height,
					);
				} else {
					$color = $solid_img_color;
					if ( is_null( $color ) || trim( $color ) == '' ) {
						
						// Show no image (gray)
						$vt_image = array(
							'url'    => ziss_no_image( array(
								                           'width'  => $width,
								                           'height' => $height
							                           ) ),
							'width'  => $width,
							'height' => $height,
						);
					} else {
						if ( $color == 'transparent' ) { // Show no image transparent
							$vt_image = array(
								'url'    => ziss_no_image( array(
									                           'width'  => $width,
									                           'height' => $height
								                           ), false, true ),
								'width'  => $width,
								'height' => $height,
							);
						} else { // No image with color from placehold.it
							$vt_image = array(
								'url'    => 'http://placehold.it/' . $width . 'x' . $height . '/' . $color . '/ffffff/',
								'width'  => $width,
								'height' => $height,
							);
						}
					}
				}
				
				return $vt_image;
			}
		}
		
		return false;
	}
}

function ziss_load_more_media_images_via_ajax() {
	global $ziss;
	
	$response = array(
		'html'      => '',
		'err'       => 'no',
		'has_more'  => 'no',
		'next_page' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_load_more' ) ) {
		$response['err'] = 'yes';
		wp_send_json( $response );
	}
	
	$image_size   = '640x640';
	$image_size   = explode( 'x', $image_size );
	$image_size_w = $image_size[0];
	$image_size_h = $image_size[1];
	
	$paged = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 0;
	
	if ( $paged > 0 ) {
		$query_images_args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => 20,
			'paged'          => $paged
		);
		
		$query_images = new WP_Query( $query_images_args );
		ob_start();
		if ( $query_images->have_posts() ) {
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
			$response['has_more']  = 'yes';
			$response['next_page'] = $paged + 1;
		} else {
			$response['has_more']  = 'no';
			$response['next_page'] = '';
		}
		wp_reset_postdata();
		$response['html'] .= ob_get_clean();
	}
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_load_more_media_images_via_ajax', 'ziss_load_more_media_images_via_ajax' );

function ziss_load_more_instagram_images_via_ajax() {
	global $ziss;
	
	$response = array(
		'html'     => '',
		'err'      => 'no',
		'has_more' => 'no',
		'next_url' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_load_more' ) ) {
		$response['err'] = 'yes';
		wp_send_json( $response );
	}
	
	$next_page_url = isset( $_POST['next_page_url'] ) ? trim( $_POST['next_page_url'] ) : '';

//	$instagram_id    = isset( $ziss['instagram_id'] ) ? trim( $ziss['instagram_id'] ) : '';
//	$instagram_token = isset( $ziss['instagram_token'] ) ? $instagram_id . '.' . trim( $ziss['instagram_token'] ) : '';
//	$limit           = isset( $ziss['instagram_limit'] ) ? intval( $ziss['instagram_limit'] ) : 100;
//
//	$transient_var     = $instagram_id . '_' . $limit;
	
	if ( $next_page_url == '' ) {
		$response['err'] = 'yes';
	}
	
	if ( $response['err'] == 'yes' ) {
		wp_send_json( $response );
	}
	
	$remote_data = wp_remote_get( $next_page_url );
	ob_start();
	if ( ! is_wp_error( $remote_data ) ) {
		$remote_data_body = json_decode( $remote_data['body'] );
		
		if ( $remote_data_body->meta->code !== 200 ) {
			echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'cosre' ) . '</p>';
		}
		
		$items_as_objects = $remote_data_body->data;
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
		
		// set_transient( $transient_var, $items, 60 * 60 );
		
		if ( isset( $items ) ) {
			if ( ! empty( $items ) ) {
				?>
				<?php foreach ( $items as $item ) { ?>
                    <div class="instagram-item img-item col-md-3">
                        <a class="ziss-add-image hover-zoom-img"
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
				<?php if ( isset( $remote_data_body->pagination->next_url ) ) {
					$response['has_more'] = 'yes';
					$response['next_url'] = $remote_data_body->pagination->next_url;
				} ?>
				<?php
			}
		}
		
	} else {
		$error_string = $remote_data->get_error_message();
		echo '<div class="error ziss-error"><p>' . $error_string . '</p></div>';
	}
	$response['html'] .= ob_get_clean();
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_load_more_instagram_images_via_ajax', 'ziss_load_more_instagram_images_via_ajax' );

function ziss_load_more_fb_images_via_ajax() {
	global $ziss;
	
	$response = array(
		'html'     => '',
		'err'      => 'no',
		'has_more' => 'no',
		'next_url' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_load_more' ) ) {
		$response['err'] = 'yes';
		wp_send_json( $response );
	}
	
	$next_page_url = isset( $_POST['next_page_url'] ) ? trim( $_POST['next_page_url'] ) : '';
	if ( $next_page_url == '' ) {
		$response['err'] = 'yes';
	}
	
	if ( $response['err'] == 'yes' ) {
		wp_send_json( $response );
	}
	
	$remote_data = wp_remote_get( $next_page_url );
	ob_start();
	if ( ! is_wp_error( $remote_data ) ) {
		$remote_data_body = json_decode( $remote_data['body'] );
		$photos_data      = null;
		$photos           = isset( $remote_data_body->photos ) ? $remote_data_body->photos : null;
		if ( ! $photos ) {
			$photos_data = isset( $remote_data_body->data ) ? $remote_data_body->data : null;
		} else {
			$photos_data = isset( $photos->data ) ? $photos->data : null;
		}
		
		if ( ! empty( $photos_data ) ) {
			foreach ( $photos_data as $photo_data ) {
				if ( isset( $photo_data->images[0] ) ) {
					$photo_info = array(
						'id'     => $photo_data->id,
						'src'    => $photo_data->images[0]->source,
						'width'  => $photo_data->images[0]->width,
						'height' => $photo_data->images[0]->height
					);
					?>
                    <div class="fb-item img-item col-md-3">
                        <a class="ziss-add-image hover-zoom-img"
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
			<?php if ( isset( $remote_data_body->paging ) ) {
				if ( trim( $remote_data_body->paging->next ) != '' ) {
					$response['has_more'] = 'yes';
					$response['next_url'] = $remote_data_body->paging->next;
				}
			} ?>
			<?php
		}
	} else {
		$error_string = $remote_data->get_error_message();
		echo '<div class="error ziss-error"><p>' . $error_string . '</p></div>';
	}
	$response['html'] .= ob_get_clean();
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_load_more_fb_images_via_ajax', 'ziss_load_more_fb_images_via_ajax' );

function ziss_update_media_used_img_display_admin() {
	
	$response = array(
		'err'     => 'no',
		'html'    => '',
		'message' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_edit_nonce' ) ) {
		$response['err']     = 'yes';
		$response['message'] = esc_html__( 'Security check error!!', 'ziss' );
		wp_send_json( $response );
	}
	
	$dont_show_media_used_imgs = isset( $_POST['dont_show_media_used_imgs'] ) ? trim( $_POST['dont_show_media_used_imgs'] ) : 'yes';
	$dont_show_media_used_imgs == 'no' ? $dont_show_media_used_imgs : 'yes';
	
	update_option( 'ziss_dont_show_used_media_imgs', $dont_show_media_used_imgs );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_update_media_used_img_display_admin', 'ziss_update_media_used_img_display_admin' );

function ziss_update_insta_used_img_display_admin() {
	
	$response = array(
		'err'     => 'no',
		'html'    => '',
		'message' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_edit_nonce' ) ) {
		$response['err']     = 'yes';
		$response['message'] = esc_html__( 'Security check error!!', 'ziss' );
		wp_send_json( $response );
	}
	
	$dont_show_insta_used_imgs = isset( $_POST['dont_show_insta_used_imgs'] ) ? trim( $_POST['dont_show_insta_used_imgs'] ) : 'yes';
	$dont_show_insta_used_imgs == 'no' ? $dont_show_insta_used_imgs : 'yes';
	
	update_option( 'ziss_dont_show_insta_used_imgs', $dont_show_insta_used_imgs );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_update_insta_used_img_display_admin', 'ziss_update_insta_used_img_display_admin' );

function ziss_update_fb_used_img_display_admin() {
	
	$response = array(
		'err'     => 'no',
		'html'    => '',
		'message' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_edit_nonce' ) ) {
		$response['err']     = 'yes';
		$response['message'] = esc_html__( 'Security check error!!', 'ziss' );
		wp_send_json( $response );
	}
	
	$dont_show_fb_used_imgs = isset( $_POST['dont_show_fb_used_imgs'] ) ? trim( $_POST['dont_show_fb_used_imgs'] ) : 'yes';
	$dont_show_fb_used_imgs == 'no' ? $dont_show_fb_used_imgs : 'yes';
	
	update_option( 'ziss_dont_show_fb_used_imgs', $dont_show_fb_used_imgs );
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_update_fb_used_img_display_admin', 'ziss_update_fb_used_img_display_admin' );

function ziss_update_fb_access_token_via_ajax() {
	global $ZissReduxFrameworkConfig, $ziss;
	
	$response = array(
		'err'     => 'no',
		'html'    => '',
		'message' => ''
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'ziss_edit_nonce' ) ) {
		$response['err']     = 'yes';
		$response['message'] = esc_html__( 'Security check error!!', 'ziss' );
		wp_send_json( $response );
	}
	
	$fb_id        = isset( $_POST['fb_id'] ) ? esc_attr( $_POST['fb_id'] ) : '';
	$access_token = isset( $_POST['access_token'] ) ? esc_attr( $_POST['access_token'] ) : '';
	
	$ZissReduxFrameworkConfig->ReduxFramework->set( 'facebook_id', $fb_id );
	$ZissReduxFrameworkConfig->ReduxFramework->set( 'facebook_token', $access_token );
	
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
	
	$remote_data = wp_remote_get( $fb_api_url );
	ob_start();
	if ( ! is_wp_error( $remote_data ) ) {
		$remote_data_body = json_decode( $remote_data['body'] );
		$photos_data      = null;
		$photos           = isset( $remote_data_body->photos ) ? $remote_data_body->photos : null;
		if ( ! $photos ) {
			$photos_data = isset( $remote_data_body->data ) ? $remote_data_body->data : null;
		} else {
			$photos_data = isset( $photos->data ) ? $photos->data : null;
		}
		
		if ( ! empty( $photos_data ) ) {
			$fb_items_class         = '';
			$dont_show_fb_used_imgs = get_option( 'ziss_dont_show_fb_used_imgs', 'yes' );
			if ( $dont_show_fb_used_imgs == 'yes' ) {
				$fb_items_class .= ' dont-show-used-imgs';
			}
			?>
            <label class="ziss-info-lb"><input <?php checked( true, $dont_show_fb_used_imgs == 'yes' ); ?>
                        type="checkbox" name="ziss_dont_show_fb_used_imgs"
                        class="ziss-dont-show-fb-used-imgs ziss-dont-show-used-imgs"
                        value="1"><?php esc_html_e( 'Don\'t show used images', 'ziss' ); ?>
            </label>
            <div class="fb-items img-items row <?php echo esc_attr( $fb_items_class ); ?>">
				<?php
				foreach ( $photos_data as $photo_data ) {
					if ( isset( $photo_data->images[0] ) ) {
						$photo_info = array(
							'id'     => $photo_data->id,
							'src'    => $photo_data->images[0]->source,
							'width'  => $photo_data->images[0]->width,
							'height' => $photo_data->images[0]->height
						);
						?>
                        <div class="fb-item img-item col-md-3">
                            <a class="ziss-add-image hover-zoom-img"
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
			<?php if ( isset( $remote_data_body->paging ) ) {
				if ( trim( $remote_data_body->paging->next ) != '' ) {
					$response['has_more'] = 'yes';
					$response['next_url'] = $remote_data_body->paging->next;
				}
			} ?>
			<?php
		} else {
			$response['err']  = 'yes';
			$response['html'] .= '<div class="notice notice-error error ziss-error"><p>' . esc_html__( 'Invalid Facebook ID or token.', 'ziss' ) . '</p></div>';
		}
	} else {
		$response['err']  = 'yes';
		$error_string     = $remote_data->get_error_message();
		$response['html'] .= '<div class="notice notice-error error ziss-error"><p>' . $error_string . '</p></div>';
	}
	$response['html'] .= ob_get_clean();
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ziss_update_fb_access_token_via_ajax', 'ziss_update_fb_access_token_via_ajax' );