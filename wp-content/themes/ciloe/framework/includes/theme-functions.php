<?php
/**
 *
 * Get option
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 */

if ( ! function_exists( 'ciloe_get_single_page_id' ) ) {
	/**
	 * Get single post, page, post type, shop page, my account ... id
	 */
	function ciloe_get_single_page_id() {
		$single_id = 0;
		
		if ( is_front_page() && is_home() ) {
			// Default homepage
			$single_id = 0;
		} elseif ( is_front_page() ) {
			// static homepage
			$single_id = get_option( 'page_on_front' );
		} elseif ( is_home() ) {
			// blog page
			$single_id = get_option( 'page_for_posts' );
		} else {
			//everyting else
			if ( is_singular( 'page' ) ) {
				$single_id = get_the_ID();
			} else {
				if ( class_exists( 'WooCommerce' ) ) {
					if ( is_shop() ) {
						$single_id = wc_get_page_id( 'shop' );
					}
					if ( is_account_page() ) {
						$single_id = wc_get_page_id( 'myaccount' );
					}
					if ( is_cart() ) {
						$single_id = wc_get_page_id( 'cart' );
					}
					if ( is_checkout() ) {
						$single_id = wc_get_page_id( 'checkout' );
					}
				}
			}
		}
		
		return $single_id;
	}
}

if ( ! function_exists( 'ciloe_get_option' ) ) {
	function ciloe_get_option( $option_name = '', $default = '' ) {
		$get_value = isset( $_GET[ $option_name ] ) ? $_GET[ $option_name ] : '';
		
		$cs_option = null;
		
		if ( defined( 'CS_VERSION' ) ) {
			$cs_option = get_option( CS_OPTION );
		}
		if ( isset( $_GET[ $option_name ] ) ) {
			$cs_option = $get_value;
			$default   = $get_value;
		}
		
		$options = apply_filters( 'cs_get_option', $cs_option, $option_name, $default );
		
		if ( ! empty( $option_name ) && ! empty( $options[ $option_name ] ) ) {
			return $options[ $option_name ];
		} else {
			return ( ! empty( $default ) ) ? $default : null;
		}
		
	}
}

/* BODY CLASS */
add_filter( 'body_class', 'ciloe_body_class' );
if ( ! function_exists( 'ciloe_body_class' ) ) {
	
	function ciloe_body_class( $classes ) {
		
		$the_theme = wp_get_theme();
		$classes[] = $the_theme->get( 'template' ) . "-" . $the_theme->get( 'Version' );
		
		if ( ciloe_is_mobile() ) {
			$classes[]            = 'ciloe-is-real-mobile';
			$enable_header_mobile = ciloe_get_option( 'enable_header_mobile', true );
			if ( $enable_header_mobile ) {
				$classes[] = 'enable-header-mobile';
			}
			if ( class_exists( 'WooCommerce' ) ) {
				$enable_shop_mobile = ciloe_get_option( 'enable_shop_mobile', true );
				$classes[]          = 'enable-shop-page-mobile';
				if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
					if ( $enable_shop_mobile ) {
						$classes[] = 'shop-page-mobile';
					}
				}
				if ( is_product() ) {
					$enable_single_product_mobile = ciloe_get_option( 'enable_single_product_mobile', true );
					if ( $enable_single_product_mobile ) {
						$classes[] = 'single-product-mobile';
					}
				}
			}
		} else {
			$classes[] = 'ciloe-none-mobile-device';
		}
		
		return $classes;
	}
}

function ciloe_set_post_views( $postID ) {
	$count_key = 'ciloe_post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		$count = 0;
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '0' );
	} else {
		$count ++;
		update_post_meta( $postID, $count_key, $count );
	}
}

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

if ( ! function_exists( 'ciloe_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @since Ciloe 1.0
	 *
	 * @global WP_Query   $wp_query   WordPress Query object.
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite object.
	 */
	function ciloe_paging_nav() {
		global $wp_query;
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}
		echo get_the_posts_pagination();
	}
}

// Post Products

if ( ! function_exists( 'ciloe_post_product_meta' ) ) {
	function ciloe_post_product_meta() {
		$product_post = get_post_meta( get_the_ID(), '_custom_post_woo_options', true );
		$html         = '';
		if ( class_exists( 'WooCommerce' ) ) {
			if ( ! empty( $product_post ) && isset( $product_post['ciloe_product_options'] ) ) {
				$html .= '<div class="post-product-carousel">';
				$html .= '<span class="flaticon-shopping-bag"></span>';
				$html .= '<div class="post-product-list">';
				$html .= '<div class="post-products-carousel owl-carousel" data-margin="15" data-dots="false" data-nav="false" >';
				foreach ( $product_post['ciloe_product_options'] as $post_product ):
					$thumbnail = ciloe_resize_image( get_post_thumbnail_id( $post_product ), null, 124, 150, true, true, false );
					$html      .= '<div class="post-product"><a href="' . esc_url( get_the_permalink( $post_product ) ) . '">' . ciloe_img_output( $thumbnail ) . '</a></div>';
				endforeach;//End foreach
				$html .= '</div></div></div>';
				echo apply_filters( 'ciloe_post_products', $html );
			};
		};
	}
}

// POST THUMBNAIL
if ( ! function_exists( 'ciloe_post_thumbnail' ) ) {
	/**
	 * Function display post thumb
	 *
	 * @since  ciloe 1.0
	 * @author FamiThemes
	 */
	function ciloe_post_thumbnail( $thumb_w = null, $thumb_h = null ) {
		
		$using_placeholder = ciloe_get_option( 'using_placeholder', 'no' );
		
		if ( $using_placeholder == 'no' && ! has_post_thumbnail() ) {
			return false;
		}
		
		if ( $thumb_w != null && $thumb_h != null ) {
			
		} else {
			if ( is_single() ) {
				$thumb_w = 1400;
				$thumb_h = 823;
			} else {
				$thumb_w = 1040;
				$thumb_h = 640;
			}
			
		}
		$image = ciloe_resize_image( get_post_thumbnail_id(), null, $thumb_w, $thumb_h, true, true, false );
		
		?>
        <div class="post-thumb">
			<?php if ( is_single() ) : ?>
				<?php echo ciloe_img_output( $image, 'img-responsive attachment-post-thumbnail wp-post-image', get_the_title() ); ?>
			<?php else: ?>
                <a class="thumb-link" href="<?php the_permalink(); ?>">
					<?php echo ciloe_img_output( $image, 'img-responsive attachment-post-thumbnail wp-post-image', get_the_title() ); ?>
                </a>
			<?php endif; ?>
        </div>
		<?php
	}
}

/* GET LOGO */
if ( ! function_exists( 'ciloe_get_logo' ) ) {
	/**
	 * Function get the site logo
	 *
	 * @since  ciloe 1.0
	 * @author FamiThemes
	 **/
	function ciloe_get_logo() {
		$single_id        = ciloe_get_single_page_id();
		$default_logo_url = get_template_directory_uri() . '/assets/images/logo-dark.svg';
		$logo_id          = ciloe_get_option( 'ciloe_logo' );
		
		$logo_img = array(
			'width'  => 64,
			'height' => 22,
			'url'    => $default_logo_url
		);
		
		if ( $single_id > 0 ) {
			$enable_custom_header = false;
			$meta_data            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
			if ( isset( $meta_data['enable_custom_header'] ) ) {
				// Override custom header (if request from url)
				if ( isset( $_GET['enable_custom_header'] ) ) {
					$meta_data['enable_custom_header'] = $_GET['enable_custom_header'] == 'yes';
				}
				$enable_custom_header = $meta_data['enable_custom_header'];
			}
			if ( $enable_custom_header ) {
				$logo_id = $meta_data['metabox_ciloe_logo'];
			}
		}
		
		$enable_header_mobile = ciloe_get_option( 'enable_header_mobile', false );
		if ( $enable_header_mobile && ciloe_is_mobile() ) {
			$mobile_logo = ciloe_get_option( 'ciloe_mobile_logo' );
			if ( $mobile_logo ) {
				$logo_id = $mobile_logo;
			}
		}
		
		$html = '';
		if ( $logo_id ) {
			$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
			$logo_ext = pathinfo( $logo_url, PATHINFO_EXTENSION );
			if ( $logo_ext != 'svg' ) {
				$logo_img = ciloe_resize_image( $logo_id, null, 300, 57, false, false, false );
			}
		}
		
		if ( $logo_img['url'] != '' ) {
			$html = '<a href="' . esc_url( get_home_url() ) . '">
                            ' . ciloe_img_output( $logo_img, '_rw', esc_attr( get_bloginfo( 'name' ) ) ) . '
                            </a>';
		}
		
		echo apply_filters( 'ciloe_site_logo', $html );
	}
}

/* GET SEARCH FORM */
if ( ! function_exists( 'ciloe_search_form' ) ) {
	/**
	 * Function get the search form template
	 *
	 * @since  ciloe 1.0
	 * @author FamiThemes
	 **/
	function ciloe_search_form( $suffix = '' ) {
		get_template_part( 'template-parts/search', 'form' . $suffix );
	}
}

if ( ! function_exists( 'ciloe_no_image' ) ) {
	/**
	 * No image generator
	 *
	 * @since 1.0
	 *
	 * @param $size : array, image size
	 * @param $echo : bool, echo or return no image url
	 **/
	function ciloe_no_image( $size = array( 'width' => 500, 'height' => 500 ), $echo = false, $transparent = false
	) {
		$noimage_dir = get_template_directory() . '/assets';
		$noimage_uri = get_template_directory_uri() . '/assets';
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

if ( ! function_exists( 'ciloe_resize_image' ) ) {
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
	function ciloe_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
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

if ( ! function_exists( 'ciloe_img_lazy' ) ) {
	function ciloe_img_lazy( $width = 1, $height = 1 ) {
		// $img_lazy = 'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20' . $width . '%20' . $height . '%27%2F%3E';
		// $img_lazy = 'https://via.placeholder.com/' . $width . 'x' . $height . '/fff/fff';
		$img_lazy = ciloe_no_image(
			array(
				'width'  => $width,
				'height' => $height
			), false, true );
		
		return $img_lazy;
	}
}

if ( ! function_exists( 'ciloe_img_output' ) ) {
	/**
	 * @param array  $img
	 * @param string $class
	 * @param string $alt
	 * @param string $title
	 *
	 * @return string
	 */
	function ciloe_img_output( $img, $class = '', $alt = '', $title = '' ) {
		
		$img_default = array(
			'width'  => '',
			'height' => '',
			'url'    => ''
		);
		$img         = wp_parse_args( $img, $img_default );
		$enable_lazy = ciloe_get_option( 'ciloe_enable_lazy', false );
		
		if ( $enable_lazy ) {
			$img_lazy = ciloe_img_lazy( $img['width'], $img['height'] );
			$img_html = '<img class="fami-img fami-lazy lazy ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . $img_lazy . '" data-src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		} else {
			$img_html = '<img class="fami-img ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		}
		
		return $img_html;
	}
}

/* GET HEADER */

if ( ! function_exists( 'ciloe_get_header' ) ) {
	/**
	 * Function get the header form template
	 *
	 * @since  ciloe 1.0
	 * @author FamiThemes
	 **/
	function ciloe_get_header() {
		/* Data MetaBox */
		$default_header_used = 'logo_l_menu_c_icons_r';
		
		$ciloe_used_header    = ciloe_get_option( 'ciloe_used_header', $default_header_used );
		$single_id            = ciloe_get_single_page_id();
		$data_meta            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
		$enable_custom_header = false;
		if ( $single_id > 0 ) {
			// Override custom header (if request from url)
			if ( isset( $_GET['enable_custom_header'] ) ) {
				$data_meta['enable_custom_header'] = $_GET['enable_custom_header'] == 'yes';
			}
			if ( isset( $data_meta['enable_custom_header'] ) ) {
				$enable_custom_header = $data_meta['enable_custom_header'];
			}
		}
		
		if ( ! empty( $data_meta ) && $enable_custom_header ) {
			$ciloe_used_header = isset( $data_meta['ciloe_metabox_used_header'] ) ? $data_meta['ciloe_metabox_used_header'] : $ciloe_used_header;
		}
		
		if ( trim( $ciloe_used_header ) == '' ) {
			$ciloe_used_header = $default_header_used;
		}
		
		$enable_header_mobile = ciloe_get_option( 'enable_header_mobile', false );
		if ( $enable_header_mobile && ciloe_is_mobile() ) {
			get_template_part( 'templates/header', 'mobile' );
		} else {
			get_template_part( 'templates/headers/header', $ciloe_used_header );
		}
		do_action( 'ciloe_after_header' );
	}
}
/*NEWSLETTER*/
add_action( 'ciloe_get_footer', 'ciloe_get_popup_newsletter' );
if ( ! function_exists( 'ciloe_get_popup_newsletter' ) ) {
	function ciloe_get_popup_newsletter() {
		$ciloe_newsletter_id = ciloe_get_option( 'ciloe_newsletter_popup', '' );
		/* Data MetaBox */
		$page_id           = ciloe_get_single_page_id();
		$enable_newsletter = ciloe_get_option( 'enable_newsletter', false );
		
		
		$on_mobile    = ciloe_get_option( 'disable_on_mobile', false );
		$class_enable = 'disable-on-mobile';
		if ( $on_mobile ) {
			$class_enable = 'enable-on-mobile';
		}
		
		
		$query = new WP_Query( array(
			                       'p'              => $ciloe_newsletter_id,
			                       'post_type'      => 'newsletter',
			                       'posts_per_page' => 1
		                       ) );
		if ( $enable_newsletter ) :
			if ( $query->have_posts() ):
				while ( $query->have_posts() ): $query->the_post(); ?>
                    <div class="modal fade <?php echo esc_attr( $class_enable ); ?>" id="popup-newsletter" tabindex="-1"
                         role="dialog" data-on-mobile="<?php echo esc_attr( $on_mobile ); ?>">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="<?php echo esc_attr__( 'Close', 'ciloe' ); ?>">
									<?php echo esc_html__( 'x', 'ciloe' ); ?>
                                </button>
                                <div class="modal-inner">
                                    <div class="newsletter-content">
										<?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile;
			endif;
		endif;
		wp_reset_postdata();
		
	}
}
//Get size guide
if ( ! function_exists( 'ciloe_get_sizegui' ) ) {
	function ciloe_get_sizegui() {
		
		?>
        <div>
            hihi
        </div>
		<?php
		
		$product_meta  = get_post_meta( get_the_ID(), '_custom_product_metabox_theme_options', true );
		$size_guide_id = ciloe_get_option( 'ciloe_sizeguide_options', '' );
		
		$size_guide_content = wpautop( do_shortcode( get_the_content( $size_guide_id ) ) );
		
		$query = new WP_Query( array( 'p' => $size_guide_id, 'post_type' => 'sizeguide', 'posts_per_page' => 1 ) );
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile;
		endif;
	}
}
/* GET FOOTER */

if ( ! function_exists( 'ciloe_get_footer' ) ) {
	function ciloe_get_footer() {
		$ciloe_footer_id = ciloe_get_option( 'ciloe_footer_options', '' );
		/* Data MetaBox */
		$page_id              = ciloe_get_single_page_id();
		$enable_custom_footer = false;
		
		$data_option_meta = get_post_meta( $page_id, '_custom_metabox_theme_options', true );
		if ( isset( $data_option_meta['enable_custom_footer'] ) ) {
			$enable_custom_footer = $data_option_meta['enable_custom_footer'] === true;
		}
		
		if ( $page_id > 0 && $enable_custom_footer ) {
			$ciloe_footer_id = $data_option_meta['ciloe_metabox_footer_options'];
			$data_meta       = get_post_meta( $data_option_meta['ciloe_metabox_footer_options'], '_custom_footer_options', true );;
		}
		
		if ( empty( $data_meta ) ) {
			$ciloe_template_style = 'default';
		} else {
			$ciloe_template_style = $data_meta['ciloe_footer_style'];
		}
		$allowed_html = array(
			'a' => array(
				'href' => array(),
			),
		);
		
		$query = new WP_Query( array( 'p' => $ciloe_footer_id, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post(); ?>
				<?php if ( $ciloe_template_style == 'default' ): ?>
                    <footer class="footer ciloe-footer-builder footer-id-<?php echo esc_attr( get_the_ID() ); ?>">
                        <div class="container">
							<?php the_content(); ?>
                        </div>
                    </footer>
				<?php else: ?>
					<?php get_template_part( 'templates/footers/footer', $ciloe_template_style ); ?>
				<?php endif; ?>
			<?php endwhile;
		else: ?>
            <footer class="footer wp-default">
                <div class="container">
					<?php printf( wp_kses( __( '&copy; 2018 <a href="%1$s">Famithemes</a>. All Rights Reserved.', 'ciloe' ), $allowed_html ), esc_url( 'https://famithemes.com' ) ); ?>
                </div>
            </footer>
			<?php
		endif;
		wp_reset_postdata();
	}
}

/* GET FOOTER */

if ( ! function_exists( 'ciloe_get_title' ) ) {
	function ciloe_get_title() {
		$output      = '';
		$output_html = '';
		if ( ! is_front_page() && ! is_home() ) {
			if ( is_page() ) {
				$output = get_the_title();
			} elseif ( is_single() ) {
				
			} elseif ( is_search() ) {
				
			} elseif ( is_404() ) {
				
			} else {
				$output = get_the_archive_title();
				if ( class_exists( 'WooCommerce' ) ) {
					if ( is_shop() ) {
						$shop_page_id = wc_get_page_id( 'shop' );
						$output       = get_the_title( $shop_page_id );
					}
				}
				
			}
			
		}
		
		if ( $output != '' ) {
			$output_html = '<div class="title-page">' . $output . '</div>';
		}
		
		return $output_html;
	}
}

if ( ! function_exists( 'ciloe_comments_list' ) ) {
	function ciloe_comments_list( $comment, $args, $depth ) {
// Globalize comment object
		$GLOBALS['comment'] = $comment;
		
		switch ( $comment->comment_type ) :
			
			case 'pingback'  :
			case 'trackback' :
				?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <p>
					<?php
					echo esc_html__( 'Pingback:', 'ciloe' );
					comment_author_link();
					edit_comment_link( esc_html__( 'Edit', 'ciloe' ), '<span class="edit-link">', '</span>' );
					?>
                </p>
				<?php
				break;
			
			default :
				global $post;
				?>
                <li <?php comment_class( 'mt__30' ); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>"
                         class="comment_container" <?php ciloe_schema_metadata( array( 'context' => 'comment' ) ); ?>>

                    <div class="ciloe-avatar"><?php echo get_avatar( $comment, 120 ); ?></div>

                    <div class="comment-text">
						<?php if ( '0' == $comment->comment_approved ) : ?>
                            <p class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'ciloe' ); ?></p>
						<?php endif; ?>
                        <div class="comment-top">
                            <div class="comment-meta">
								<?php
								printf(
									'<h5 class="comment-author" ' . ciloe_schema_metadata( array(
										                                                       'context' => 'comment_author',
										                                                       'echo'    => false
									                                                       ) ) . '><span ' . ciloe_schema_metadata( array(
										                                                                                                'context' => 'author_name',
										                                                                                                'echo'    => false
									                                                                                                ) ) . '>%1$s</span></h5>',
									get_comment_author_link(),
									( $comment->user_id == $post->post_author ) ? '<span class="author-post">' . esc_html__( 'Post author', 'ciloe' ) . '</span>' : ''
								);
								?>
								<?php
								printf(
									'<time class="grow" ' . ciloe_schema_metadata( array(
										                                               'context' => 'entry_time',
										                                               'echo'    => false
									                                               ) ) . '>%3$s</time>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									sprintf( wp_kses_post( '%1$s', 'ciloe' ), get_comment_date() )
								);
								?>
                            </div>
                            <div class="flex">
								<?php
								edit_comment_link( esc_html__( 'Edit', 'ciloe' ) );
								comment_reply_link(
									array_merge(
										$args,
										array(
											'reply_text' => esc_html__( 'Reply', 'ciloe' ),
											'depth'      => $depth,
											'max_depth'  => $args['max_depth'],
										)
									)
								);
								?>
                            </div><!-- .action-link -->
                        </div>
                        <div <?php ciloe_schema_metadata( array( 'context' => 'entry_content' ) ); ?>>
							<?php comment_text(); ?>
                        </div>
                    </div><!-- .comment-content -->

                </article><!-- #comment- -->
				<?php
				break;
		
		endswitch;
	}
}

if ( ! function_exists( 'ciloe_schema_metadata' ) ) {
	function ciloe_schema_metadata( $args ) {
		// Set default arguments
		$default_args = array(
			'post_type' => '',
			'context'   => '',
			'echo'      => true,
		);
		
		$args = apply_filters( 'ciloe_schema_metadata_args', wp_parse_args( $args, $default_args ) );
		
		if ( empty( $args['context'] ) ) {
			return;
		}
		
		// Markup string - stores markup output
		$markup     = ' ';
		$attributes = array();
		
		// Try to fetch the right markup
		switch ( $args['context'] ) {
			case 'body':
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/WebPage';
				break;
			
			case 'header':
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/WPHeader';
				break;
			
			case 'nav':
				$attributes['role']      = 'navigation';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/SiteNavigationElement';
				break;
			
			case 'content':
				$attributes['role']     = 'main';
				$attributes['itemprop'] = 'mainContentOfPage';
				
				// Frontpage, Blog, Archive & Single Post
				if ( is_singular( 'post' ) || is_archive() || is_home() ) {
					$attributes['itemscope'] = 'itemscope';
					$attributes['itemtype']  = 'http://schema.org/Blog';
				}
				
				// Search Results Pages
				if ( is_search() ) {
					$attributes['itemscope'] = 'itemscope';
					$attributes['itemtype']  = 'http://schema.org/SearchResultsPage';
				}
				break;
			
			case 'entry':
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/CreativeWork';
				break;
			
			case 'image':
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/ImageObject';
				break;
			
			case 'image_url':
				$attributes['itemprop'] = 'contentURL';
				break;
			
			case 'name':
				$attributes['itemprop'] = 'name';
				break;
			
			case 'email':
				$attributes['itemprop'] = 'email';
				break;
			
			case 'url':
				$attributes['itemprop'] = 'url';
				break;
			
			case 'author':
				$attributes['itemprop']  = 'author';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/Person';
				break;
			
			case 'author_link':
				$attributes['itemprop'] = 'url';
				break;
			
			case 'author_name':
				$attributes['itemprop'] = 'name';
				break;
			
			case 'author_description':
				$attributes['itemprop'] = 'description';
				break;
			
			case 'entry_time':
				$attributes['itemprop'] = 'datePublished';
				$attributes['datetime'] = get_the_time( 'c' );
				break;
			
			case 'entry_title':
				$attributes['itemprop'] = 'headline';
				break;
			
			case 'entry_content':
				$attributes['itemprop'] = 'text';
				break;
			
			case 'comment':
				$attributes['itemprop']  = 'comment';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/Comment';
				break;
			
			case 'comment_author':
				$attributes['itemprop']  = 'creator';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/Person';
				break;
			
			case 'comment_author_link':
				$attributes['itemprop']  = 'creator';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/Person';
				$attributes['rel']       = 'external nofollow';
				break;
			
			case 'comment_time':
				$attributes['itemprop']  = 'commentTime';
				$attributes['itemscope'] = 'itemscope';
				$attributes['datetime']  = get_the_time( 'c' );
				break;
			
			case 'comment_text':
				$attributes['itemprop'] = 'commentText';
				break;
			
			case 'sidebar':
				$attributes['role']      = 'complementary';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/WPSideBar';
				break;
			
			case 'search_form':
				$attributes['itemprop']  = 'potentialAction';
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/SearchAction';
				break;
			
			case 'footer':
				$attributes['itemscope'] = 'itemscope';
				$attributes['itemtype']  = 'http://schema.org/WPFooter';
				break;
		}
		
		$attributes = apply_filters( 'ciloe_schema_metadata_attributes', $attributes, $args );
		
		// If failed to fetch the attributes - let's stop
		if ( empty( $attributes ) ) {
			return;
		}
		
		// Cycle through attributes, build tag attribute string
		foreach ( $attributes as $key => $value ) {
			$markup .= $key . '="' . $value . '" ';
		}
		
		$markup = apply_filters( 'ciloe_schema_metadata_output', $markup, $args );
		
		if ( $args['echo'] ) {
			echo '' . $markup;
		} else {
			return $markup;
		}
	}
}
// GET REVO SLIDE /
if ( ! function_exists( 'ciloe_rev_slide_options' ) ) {
	function ciloe_rev_slide_options() {
		$ciloe_rev_slide_options = array( '' => esc_html__( '--- Choose Revolution Slider ---', 'ciloe' ) );
		if ( class_exists( 'RevSlider' ) ) {
			global $wpdb;
			if ( shortcode_exists( 'rev_slider' ) ) {
				$rev_sql  = $wpdb->prepare(
					"SELECT *
                FROM {$wpdb->prefix}revslider_sliders
                WHERE %d", 1
				);
				$rev_rows = $wpdb->get_results( $rev_sql );
				if ( count( $rev_rows ) > 0 ) {
					foreach ( $rev_rows as $rev_row ):
						$ciloe_rev_slide_options[ $rev_row->alias ] = $rev_row->title;
					endforeach;
				}
			}
		}
		
		return $ciloe_rev_slide_options;
	}
}

// Get search form

function my_search_form( $form ) {
	$form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
    <div class="ciloe-searchform"><label class="screen-reader-text" for="s">' . esc_html__( 'Search for:', 'ciloe' ) . '</label>
    <input type="text" value="' . get_search_query() . '" placeholder="' . esc_attr__( 'Enter your keywords...', 'ciloe' ) . '" name="s" id="s" />
    <button type="submit"><span class="icon-magnifier icons"></span></button>    
    </div>
    </form>';
	
	return $form;
}

add_filter( 'get_search_form', 'my_search_form', 100 );


/* INSTANT SEARCH */
function ciloe_instant_search_data() {
	$response = array(
		'array'   => '',
		'message' => '',
		'success' => 'no',
	);
	$args     = array(
		'post_type'      => 'product',
		'posts_per_page' => - 1,
	);
	$posts    = new WP_Query( $args );
	if ( $posts->have_posts() ) { ?>
		
		<?php while ( $posts->have_posts() ) { ?>
			<?php
			$posts->the_post();
			ob_start(); ?>
            <div <?php post_class( 'product-item-search col-bg-2 col-lg-2 col-md-2 col-sm-4 col-xs-6 col-ts-12 ' ); ?>>
                <div class="product-inner">
                    <div class="post-thumb">
						<?php
						$image   = ciloe_resize_image( get_post_thumbnail_id(), null, 320, 387, true, true, false );
						$product = new WC_Product( get_the_ID() );
						?>
                        <a href="<?php the_permalink() ?>">
                            <img width="<?php echo esc_attr( $image['width'] ); ?>"
                                 height="<?php echo esc_attr( $image['height'] ); ?>"
                                 class="attachment-post-thumbnail wp-post-image"
                                 src="<?php echo esc_url( $image['url'] ); ?>"
                                 alt="<?php echo esc_attr( get_the_title() ); ?>"/>
                        </a>
                    </div>
                    <div class="product-info">
						<?php
						printf( '<div class="rating"> %s </div>', wc_get_rating_html( $product->get_average_rating() ) );
						?>
                        <h3 class="product-title"><a
                                    href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
                        </h3>
						<?php
						printf( '<div class="price">' . esc_html__( 'Price', 'ciloe' ) . ' : %s </div>', $product->get_price_html() );
						
						
						$term_list = wp_get_post_terms( $product->get_id(), 'product_cat' );
						$arg_term  = array();
						if ( is_wp_error( $term_list ) ) {
							return $term_list;
						}
						foreach ( $term_list as $term ) {
							$arg_term[] = $term->slug;
						}
						$arg_term = implode( ',', $arg_term );
						?>
                    </div>
                </div>
            </div>
			<?php
			
			$post_html   = ob_get_clean();
			$cat_slugs   = $arg_term;
			$post_data[] = array(
				'post_title' => esc_html( get_the_title() ),
				'post_link'  => esc_url( get_permalink() ),
				'thumb'      => $image,
				'post_html'  => $post_html,
				'cat_slugs'  => $cat_slugs,
			);
			?>
		<?php } ?>
	
	<?php }
	wp_reset_postdata();
	$response['array']   = $post_data;
	$response['success'] = 'yes';
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_ciloe_instant_search_data', 'ciloe_instant_search_data' );
add_action( 'wp_ajax_nopriv_ciloe_instant_search_data', 'ciloe_instant_search_data' );

if ( ! function_exists( 'ciloe_change_buy_together_thumb_width' ) ) {
	function ciloe_change_buy_together_thumb_width( $thumb_w ) {
		$thumb_w = 180;
		
		return $thumb_w;
	}
	
	add_filter( 'famibt_thumb_w', 'ciloe_change_buy_together_thumb_width', 10, 1 );
}

if ( ! function_exists( 'ciloe_change_buy_together_thumb_height' ) ) {
	function ciloe_change_buy_together_thumb_height( $thumb_h ) {
		$thumb_h = 220;
		
		return $thumb_h;
	}
	
	add_filter( 'famibt_thumb_h', 'ciloe_change_buy_together_thumb_height', 10, 1 );
}

if ( ! function_exists( 'ciloe_is_mobile' ) ) {
	function ciloe_is_mobile() {
		$is_mobile = false;
		if ( function_exists( 'ciloe_toolkit_is_mobile' ) ) {
			$is_mobile = ciloe_toolkit_is_mobile();
		}
		
		$force_mobile = isset( $_REQUEST['force_mobile'] ) ? $_REQUEST['force_mobile'] == 'yes' || $_REQUEST['force_mobile'] == 'true' : false;
		if ( $force_mobile ) {
			$is_mobile = true;
		}
		
		$is_mobile = apply_filters( 'ciloe_is_mobile', $is_mobile );
		
		return $is_mobile;
	}
}