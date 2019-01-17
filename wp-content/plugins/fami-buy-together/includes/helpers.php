<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'famibt_get_all_options' ) ) {
	/**
	 * @param string $option_name
	 *
	 * @return null
	 */
	function famibt_get_all_options() {
		return get_option( 'famibt_all_settings', array() );
	}
}

if ( ! function_exists( 'famibt_get_option' ) ) {
	/**
	 * @param string $option_name
	 *
	 * @return null
	 */
	function famibt_get_option( $option_name = '', $default = null ) {
		$all_options = famibt_get_all_options();
		$option_val  = isset( $all_options[ $option_name ] ) ? $all_options[ $option_name ] : $default;
		
		return $option_val;
	}
}

if ( ! function_exists( 'famibt_col_select_html' ) ) {
	function famibt_col_select_html( $selected = 'display_col_4', $class = '', $name = '', $id = '', $echo = true ) {
		$args = array(
			'display_col_6' => esc_html__( '6 Columns', 'famibt' ),
			'display_col_5' => esc_html__( '5 Columns', 'famibt' ), // No 5 columns options
			'display_col_4' => esc_html__( '4 Columns', 'famibt' ),
			'display_col_3' => esc_html__( '3 Columns', 'famibt' ),
			'display_col_2' => esc_html__( '2 Columns', 'famibt' ),
			'display_col_1' => esc_html__( '1 Column', 'famibt' ),
		);
		
		$args = apply_filters( 'famibt_col_select_args', $args );
		
		$html = famibt_select_html( $args, $selected, $class, $name, $id, false );
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famibt_select_html' ) ) {
	function famibt_select_html( $args = array(), $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		$html = '';
		if ( empty( $args ) ) {
			return '';
		}
		
		foreach ( $args as $val => $display ) {
			$html .= '<option ' . selected( true, $val == $selected, false ) . ' value="' . esc_attr( $val ) . '">' . esc_html( $display ) . '</option>';
		}
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famibt-select ' . esc_attr( $class ) . '" ';
		
		$html = '<select ' . $html_atts . '>' . $html . '</select>';
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famibt_resize_image' ) ) {
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
	function famibt_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
		/*If is singular and has post thumbnail and $attach_id is null, so we get post thumbnail id automatic*/
		if ( is_singular() && ! $attach_id ) {
			if ( has_post_thumbnail() && ! post_password_required() ) {
				$attach_id = get_post_thumbnail_id();
			}
		}
		/*this is an attachment, so we have the ID*/
		$image_src = array();
		if ( $attach_id ) {
			$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
			$actual_file_path = get_attached_file( $attach_id );
			/*this is not an attachment, let's use the image url*/
		} else if ( $img_url ) {
			$file_path        = str_replace( get_site_url(), get_home_path(), $img_url );
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
		if ( ! empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
			$file_info = pathinfo( $actual_file_path );
			$extension = '.' . $file_info['extension'];
			/*the image path without the extension*/
			$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
			$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			/*checking if the file size is larger than the target size*/
			/*if it is smaller or the same size, stop right here and return*/
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
				/*the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)*/
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					$vt_image        = array(
						'url'    => $cropped_img_url,
						'width'  => $width,
						'height' => $height,
					);
					
					return $vt_image;
				}
				
				if ( $crop == false ) {
					/*calculate the size proportionaly*/
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
					/*checking if the file already exists*/
					if ( file_exists( $resized_img_path ) ) {
						$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
						$vt_image        = array(
							'url'    => $resized_img_url,
							'width'  => $proportional_size[0],
							'height' => $proportional_size[1],
						);
						
						return $vt_image;
					}
				}
				/*no cache files - let's finally resize it*/
				$img_editor = wp_get_image_editor( $actual_file_path );
				if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
					);
				}
				$new_img_path = $img_editor->generate_filename();
				if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
					);
				}
				if ( ! is_string( $new_img_path ) ) {
					return array(
						'url'    => '',
						'width'  => '',
						'height' => '',
					);
				}
				$new_img_size = getimagesize( $new_img_path );
				$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
				/*resized output*/
				$vt_image = array(
					'url'    => $new_img,
					'width'  => $new_img_size[0],
					'height' => $new_img_size[1],
				);
				
				return $vt_image;
			}
			/*default output - without resizing*/
			$vt_image = array(
				'url'    => $image_src[0],
				'width'  => $image_src[1],
				'height' => $image_src[2],
			);
			
			return $vt_image;
		} else {
			if ( $place_hold ) {
				$width  = intval( $width );
				$height = intval( $height );
				/*Real image place hold (https://unsplash.it/)*/
				if ( $use_real_img_hold ) {
					$random_time = time() + rand( 1, 100000 );
					$vt_image    = array(
						'url'    => 'https://unsplash.it/' . $width . '/' . $height . '?random&time=' . $random_time,
						'width'  => $width,
						'height' => $height,
					);
				} else {
					$vt_image = array(
						'url'    => 'http://placehold.it/' . $width . 'x' . $height,
						'width'  => $width,
						'height' => $height,
					);
				}
				
				return $vt_image;
			}
		}
		
		return false;
	}
}

if ( ! function_exists( 'famibt_get_current_editing_product_id' ) ) {
	/**
	 * @return int
	 */
	function famibt_get_current_editing_product_id() {
		$screen     = get_current_screen();
		$product_id = 0;
		
		if ( is_admin() && ( $screen->id == 'product' ) ) {
			global $post;
			$product_id = $post->ID;
		}
		
		return $product_id;
	}
}

if ( ! function_exists( 'famibt_no_image' ) ) {
	/**
	 * No image generator
	 *
	 * @since 1.0
	 *
	 * @param $size : array, image size
	 * @param $echo : bool, echo or return no image url
	 **/
	function famibt_no_image( $size = array( 'width' => 500, 'height' => 500 ), $echo = false, $transparent = false
	) {
		$noimage_dir = FAMIBT_PATH . 'assets';
		$noimage_uri = FAMIBT_URI . 'assets';
		$suffix      = ( $transparent ) ? '_transparent' : '';
		if ( ! is_array( $size ) || empty( $size ) ):
			$size = array( 'width' => 500, 'height' => 500 );
		endif;
		if ( ! is_numeric( $size['width'] ) && $size['width'] == '' || $size['width'] == null ):
			$size['width'] = 'auto';
		endif;
		if ( ! is_numeric( $size['height'] ) && $size['height'] == '' || $size['height'] == null ):
			$size['height'] = 'auto';
		endif;
		
		if ( file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) ) {
			if ( $echo ) {
				echo esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
			}
			
			return esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
		}
		
		// base image must be exist
		$img_base_fullpath = $noimage_dir . '/images/noimage/no_image' . $suffix . '.png';
		$no_image_src      = $noimage_uri . '/images/noimage/no_image' . $suffix . '.png';
		// Check no image exist or not
		if ( ! file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) && is_writable( $noimage_dir . '/images/noimage/' ) ):
			$no_image = wp_get_image_editor( $img_base_fullpath );
			if ( ! is_wp_error( $no_image ) ):
				$no_image->resize( $size['width'], $size['height'], true );
				$no_image_name = $no_image->generate_filename( $size['width'] . 'x' . $size['height'], $noimage_dir . '/images/noimage/', null );
				$no_image->save( $no_image_name );
			endif;
		endif;
		// Check no image exist after resize
		$noimage_path_exist_after_resize = $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
		if ( file_exists( $noimage_path_exist_after_resize ) ):
			$no_image_src = $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
		endif;
		
		if ( $echo ) {
			echo esc_url( $no_image_src );
		}
		
		return esc_url( $no_image_src );
	}
}

if ( ! function_exists( 'famibt_img_lazy' ) ) {
	function famibt_img_lazy( $width = 1, $height = 1 ) {
		// $img_lazy = 'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20' . $width . '%20' . $height . '%27%2F%3E';
		// $img_lazy = 'https://via.placeholder.com/' . $width . 'x' . $height . '/fff/fff';
		$img_lazy = famibt_no_image(
			array(
				'width'  => $width,
				'height' => $height
			), false, true );
		
		return $img_lazy;
	}
}

if ( ! function_exists( 'famibt_img_output' ) ) {
	/**
	 * @param array  $img
	 * @param string $class
	 * @param string $alt
	 * @param string $title
	 *
	 * @return string
	 */
	function famibt_img_output( $img, $class = '', $alt = '', $title = '' ) {
		
		$img_default = array(
			'width'  => '',
			'height' => '',
			'url'    => ''
		);
		$img         = wp_parse_args( $img, $img_default );
		$enable_lazy = famibt_is_enable_lazy_load();
		
		if ( $enable_lazy ) {
			$img_lazy = famibt_img_lazy( $img['width'], $img['height'] );
			$img_html = '<img class="fami-img fami-lazy lazy ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . $img_lazy . '" data-src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		} else {
			$img_html = '<img class="fami-img ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		}
		
		return $img_html;
	}
}

if ( ! function_exists( 'famibt_is_enable_lazy_load' ) ) {
	function famibt_is_enable_lazy_load() {
		return get_option( 'famibt_enable_lazy', true );
	}
}