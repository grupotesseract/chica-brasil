<?php
if ( ! class_exists( 'Ciloe_Shortcode' ) ) {
	class Ciloe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = '';
		/**
		 * Register shortcode with WordPress.
		 *
		 * @return  void
		 */
		/**
		 * Meta key.
		 *
		 * @var  string
		 */
		protected $metakey = '_Ciloe_Shortcode_custom_css';
		
		public function __construct() {
			if ( ! empty( $this->shortcode ) ) {
				// Add shortcode.
				add_shortcode( "ciloe_{$this->shortcode}", array( &$this, 'output_html' ) );
				
				// Hook into post saving.
				add_action( 'save_post', array( &$this, 'update_post' ) );
				
			}
		}
		
		/**
		 * Replace and save custom css to post meta.
		 *
		 * @param   int $post_id
		 *
		 * @return  void
		 */
		public function update_post( $post_id ) {
			if ( ! isset( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
				return;
			}
			
			// Set and replace content.
			$post = $this->replace_post( $post_id );
			if ( $post ) {
				// Generate custom CSS.
				$css = $this->get_css( $post->post_content );
				// Update post and save CSS to post meta.
				$this->save_post( $post );
				$this->save_postmeta( $post_id, $css );
			} else {
				$this->save_postmeta( $post_id, '' );
			}
		}
		
		/**
		 * Replace shortcode used in a post with real content.
		 *
		 * @param   int $post_id Post ID.
		 *
		 * @return  WP_Post object or null.
		 */
		public function replace_post( $post_id ) {
			// Get post.
			$post = get_post( $post_id );
			
			if ( $post ) {
				if ( has_shortcode( $post->post_content, "ciloe_{$this->shortcode}" ) ) {
					
					$post->post_content = preg_replace_callback(
						'/(' . $this->shortcode . '_custom_id)="[^"]+"/',
						'Ciloe_Shortcode_replace_post_callback',
						$post->post_content
					);
					
				}
			}
			
			return $post;
		}
		
		/**
		 * Parse shortcode custom css string.
		 *
		 * @param   string $content
		 * @param   string $shortcode
		 *
		 * @return  string
		 */
		public function get_css( $content ) {
			$css = '';
			if ( preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes ) ) {
				foreach ( $shortcodes[2] as $index => $tag ) {
					if ( strpos( $tag, 'ciloe_' ) !== false ) {
						$atts      = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
						$shortcode = explode( '_', $tag );
						$shortcode = end( $shortcode );
						
						$class = 'Ciloe_Shortcode_' . implode( '_', array_map( 'ucfirst', explode( '-', $shortcode ) ) );
						if ( class_exists( $class ) ) {
							$css .= $class::generate_css( $atts );
						}
					}
				}
				
				foreach ( $shortcodes[5] as $shortcode_content ) {
					$css .= $this->get_css( $shortcode_content );
				}
			}
			
			return $css;
		}
		
		/**
		 * Update post data content.
		 *
		 * @param   int $post WP_Post object.
		 *
		 * @return  void
		 */
		public function save_post( $post ) {
			// Sanitize post data for inserting into database.
			$data = sanitize_post( $post, 'db' );
			
			// Update post content.
			global $wpdb;
			
			$wpdb->query( "UPDATE {$wpdb->posts} SET post_content = '" . esc_sql( $data->post_content ) . "' WHERE ID = {$data->ID};" );
			
			// Update post cache.
			$data = sanitize_post( $post, 'raw' );
			
			wp_cache_replace( $data->ID, $data, 'posts' );
		}
		
		/**
		 * Update extra post meta.
		 *
		 * @param   int    $post_id Post ID.
		 * @param   string $css     Custom CSS.
		 *
		 * @return  void
		 */
		public function save_postmeta( $post_id, $css ) {
			if ( $post_id && $this->metakey ) {
				if ( empty( $css ) ) {
					delete_post_meta( $post_id, $this->metakey );
				} else {
					update_post_meta( $post_id, $this->metakey, preg_replace( '/[\t\r\n]/', '', $css ) );
				}
			}
		}
		
		/**
		 * Generate custom CSS.
		 *
		 * @param   array $atts Shortcode parameters.
		 *
		 * @return  string
		 */
		public static function generate_css( $atts ) {
			return '';
		}
		
		
		public function output_html( $atts, $content = null ) {
			return '';
		}
		
		function get_all_attributes( $tag, $text ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $text, $matches );
			$out               = array();
			$shortcode_content = array();
			if ( isset( $matches[5] ) ) {
				$shortcode_content = $matches[5];
			}
			
			if ( isset( $matches[2] ) ) {
				$i = 0;
				foreach ( (array) $matches[2] as $key => $value ) {
					if ( $tag === $value ) {
						$out[ $i ]            = shortcode_parse_atts( $matches[3][ $key ] );
						$out[ $i ]['content'] = $matches[5][ $key ];
					}
					$i ++;
				}
			}
			
			return $out;
		}
		
		public function generate_carousel_data_attributes( $prefix = '', $atts ) {
			$result = '';
			if ( isset( $atts[ $prefix . 'autoplay' ] ) ) {
				$result .= 'data-autoplay="' . $atts[ $prefix . 'autoplay' ] . '" ';
			}
			if ( isset( $atts[ $prefix . 'navigation' ] ) ) {
				$result .= 'data-nav="' . $atts[ $prefix . 'navigation' ] . '" ';
			}
			if ( isset( $atts[ $prefix . 'dots' ] ) ) {
				$result .= 'data-dots="' . $atts[ $prefix . 'dots' ] . '" ';
			}
			if ( isset( $atts[ $prefix . 'loop' ] ) ) {
				$result .= 'data-loop="' . $atts[ $prefix . 'loop' ] . '" ';
			}
			if ( isset( $atts[ $prefix . 'slidespeed' ] ) ) {
				$result .= 'data-slidespeed="' . $atts[ $prefix . 'slidespeed' ] . '" ';
			}
			if ( isset( $atts[ $prefix . 'items' ] ) ) {
				$result .= 'data-items="' . $atts[ $prefix . 'items' ] . '" ';
			}
			if ( isset( $atts[ $prefix . 'margin' ] ) ) {
				$margin = $atts[ $prefix . 'margin' ];
			}
			$result .= 'data-margin="' . $atts[ $prefix . 'margin' ] . '" ';
			
			$responsive = '';
			if ( isset( $atts[ $prefix . 'ts_items' ] ) ) {
				$responsive .= '"0":{"items":' . $atts[ $prefix . 'ts_items' ] . ', ';
				$responsive .= '"margin":' . $atts[ $prefix . 'margin' ] . '}, ';
			}
			if ( isset( $atts[ $prefix . 'xs_items' ] ) ) {
				$responsive .= '"481":{"items":' . $atts[ $prefix . 'xs_items' ] . ', ';
				$responsive .= '"margin":' . $atts[ $prefix . 'margin' ] . '}, ';
			}
			if ( isset( $atts[ $prefix . 'sm_items' ] ) ) {
				$responsive .= '"768":{"items":' . $atts[ $prefix . 'sm_items' ] . ', ';
				$responsive .= '"margin":' . $atts[ $prefix . 'margin' ] . '}, ';
			}
			if ( isset( $atts[ $prefix . 'md_items' ] ) ) {
				$responsive .= '"992":{"items":' . $atts[ $prefix . 'md_items' ] . ', ';
				$responsive .= '"margin":' . $atts[ $prefix . 'margin' ] . '}, ';
			}
			if ( isset( $atts[ $prefix . 'lg_items' ] ) ) {
				$responsive .= '"1200":{"items":' . $atts[ $prefix . 'lg_items' ] . ', ';
				$responsive .= '"margin":' . $atts[ $prefix . 'margin' ] . '}, ';
			}
			if ( isset( $atts[ $prefix . 'ls_items' ] ) ) {
				$responsive .= '"1500":{"items":' . $atts[ $prefix . 'ls_items' ] . ', ';
				$responsive .= '"margin":' . $atts[ $prefix . 'margin' ] . '}, ';
			}
			if ( $responsive ) {
				$responsive = substr( $responsive, 0, strlen( $responsive ) - 2 );
				$result     .= ' data-responsive = \'{' . $responsive . '}\'';
			}
			
			return $result;
		}
		
		/**
		 * Get Products
		 *
		 * @return  $products
		 */
		public function getProducts( $atts, $args = array(), $ignore_sticky_posts = 1 ) {
			extract( $atts );
			$target             = isset( $target ) ? $target : 'recent-product';
			$meta_query         = WC()->query->get_meta_query();
			$args['meta_query'] = $meta_query;
			$args['post_type']  = 'product';
			if ( isset( $taxonomy ) and $taxonomy ) {
				$args['tax_query'] =
					array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => array_map( 'sanitize_title', explode( ',', $taxonomy )
							),
						),
					);
			}
			$args['post_status']         = 'publish';
			$args['ignore_sticky_posts'] = $ignore_sticky_posts;
			$args['suppress_filter']     = true;
			
			if ( isset( $atts['per_page'] ) && $atts['per_page'] ) {
				
				$args['posts_per_page'] = $atts['per_page'];
			}
			
			if ( ! isset( $orderby ) ) {
				$ordering_args = WC()->query->get_catalog_ordering_args();
				$orderby       = $ordering_args['orderby'];
				$order         = $ordering_args['order'];
			}
			
			switch ( $target ):
				case 'best-selling' :
					$args['meta_key'] = 'total_sales';
					$args['orderby']  = 'meta_value_num';
					break;
				case 'top-rated' :
					$args['orderby'] = $orderby;
					$args['order']   = $order;
					break;
				case 'product-category' :
					$ordering_args   = WC()->query->get_catalog_ordering_args( $atts['orderby'], $atts['order'] );
					$args['orderby'] = $ordering_args['orderby'];
					$args['order']   = $ordering_args['order'];
					break;
				case 'products' :
					$args['posts_per_page'] = - 1;
					if ( ! empty( $ids ) ) {
						$args['post__in'] = array_map( 'trim', explode( ',', $ids ) );
						$args['orderby']  = 'post__in';
					}
					if ( ! empty( $skus ) ) {
						$args['meta_query'][] = array(
							'key'     => '_sku',
							'value'   => array_map( 'trim', explode( ',', $skus ) ),
							'compare' => 'IN',
						);
					}
					break;
				case 'featured_products' :
					$meta_query  = WC()->query->get_meta_query();
					$tax_query   = WC()->query->get_tax_query();
					$tax_query[] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					);
					
					$args['tax_query']  = $tax_query;
					$args['meta_query'] = $meta_query;
					break;
				case 'product_attribute' :
					//'recent-product'
					$args['tax_query'] = array(
						array(
							'taxonomy' => strstr( $atts['attribute'], 'pa_' ) ? sanitize_title( $atts['attribute'] ) : 'pa_' . sanitize_title( $atts['attribute'] ),
							'terms'    => array_map( 'sanitize_title', explode( ',', $atts['filter'] ) ),
							'field'    => 'slug',
						),
					);
					break;
				case 'on_new' :
					$newness = cs_get_option( 'product_newness', 7 );    // Newness in days as defined by option
					
					$args['date_query'] = array(
						array(
							'after'     => '' . $newness . ' days ago',
							'inclusive' => true,
						),
					);
					if ( $orderby == '_sale_price' ) {
						$orderby = 'date';
						$order   = 'DESC';
					}
					$args['orderby'] = $orderby;
					$args['order']   = $order;
					break;
				case 'on_sale' :
					$product_ids_on_sale = wc_get_product_ids_on_sale();
					$args['post__in']    = array_merge( array( 0 ), $product_ids_on_sale );
					if ( $orderby == '_sale_price' ) {
						$orderby = 'date';
						$order   = 'DESC';
					}
					$args['orderby'] = $orderby;
					$args['order']   = $order;
					break;
				default :
					//'recent-product'
					$args['orderby'] = $orderby;
					$args['order']   = $order;
					if ( isset( $ordering_args['meta_key'] ) ) {
						$args['meta_key'] = $ordering_args['meta_key'];
					}
					// Remove ordering query arguments
					WC()->query->remove_ordering_args();
					break;
			endswitch;
			
			return $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
		}
	}
}

if ( ! function_exists( 'Ciloe_Shortcode_replace_post_callback' ) ) {
	
	function Ciloe_Shortcode_replace_post_callback( $matches ) {
		// Generate a random string to use as element ID.
		$id = 'ciloe_custom_css_' . mt_rand();
		
		
		return $matches[1] . '="' . $id . '"';
	}
}

// Check plugin wc is activate
$active_plugin_wc = is_plugin_active( 'woocommerce/woocommerce.php' );
$shortcodes       = array(
	'tabs',
	'slider',
	'container',
	'custommenu',
	'categories',
	'blog',
	'iconbox',
	'instagram',
	'title',
	'titleandshortdesc',
	'socials',
	// 'productsimple',
	'accordions',
	'googlemap',
	'banner',
	'newsletter',
	// 'singlelookbook',
	'testimonials',
	'instagram',
	'pinmap',
	// 'fullpageelem',
	'categorylist'

);

if ( $active_plugin_wc ) {
	$shortcode_woo = array(
		'products',
		'product'
	);
	$shortcodes    = array_merge( $shortcodes, $shortcode_woo );
}

foreach ( $shortcodes as $shortcode ) {
	// Include shortcode class declaration file.
	$shortcode = str_replace( '_', '-', $shortcode );
	if ( is_file( CILOE_TOOLKIT_PATH . '/includes/shortcodes/' . $shortcode . '.php' ) ) {
		
		include_once CILOE_TOOLKIT_PATH . '/includes/shortcodes/' . $shortcode . '.php';
	}
	
	// Generate shortcode class name.
	$class = 'Ciloe_Shortcode_' . implode( '_', array_map( 'ucfirst', explode( '-', $shortcode ) ) );
	if ( class_exists( $class ) ) {
		$shortcode = new $class();
	}
}

if ( ! function_exists( 'Ciloe_Shortcode_print_inline_css' ) ) {
	function Ciloe_Shortcode_print_inline_css() {
		// Get all custom inline CSS.
		if ( is_singular() ) {
			$post_custom_css = get_post_meta( get_the_ID(), '_Ciloe_Shortcode_custom_css', true );
			$inline_css[]    = $post_custom_css;
			$inline_css      = apply_filters( 'ciloe-shortcode-inline-css', $inline_css );
			if ( count( $inline_css ) ) {
				echo '<style id="ciloe-toolkit-inline" type="text/css">' . trim( implode( ' ', $inline_css ) ) . "</style>\n";
			}
		}
		
		
	}
}

add_action( 'wp_head', 'Ciloe_Shortcode_print_inline_css', 99999 );

/** Loadmore Product Ajax  **/

/* Loadmore Product */
add_action( 'wp_ajax_ciloe_loadmore_product', 'ciloe_loadmore_product' );
add_action( 'wp_ajax_nopriv_ciloe_loadmore_product', 'ciloe_loadmore_product' );
function ciloe_loadmore_product() {
	$response        = array(
		'html'    => '',
		'message' => '',
		'success' => 'no',
		'show_bt' => 'no'
	);
	$except_post_ids = isset( $_POST['except_post_ids'] ) ? $_POST['except_post_ids'] : '';
	$attr            = isset( $_POST['attr'] ) ? $_POST['attr'] : '';
	$cats            = isset( $_POST['cats'] ) ? $_POST['cats'] : '';
	$page            = isset( $_POST['page'] ) ? $_POST['page'] : '';
	$attr_ajaxs      = json_decode( base64_decode( $attr ) );
	
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => $attr_ajaxs->per_page,
		'post__not_in'   => $except_post_ids,
		'post_status'    => 'publish',
		'page'           => $page
	);
	if ( isset( $cats ) ) {
		$args['tax_query'] =
			array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => array_map( 'sanitize_title', explode( ',', $cats )
					)
				)
			);
	}
	$product_item_class   = array( 'product-item', $attr_ajaxs->target );
	$product_item_class[] = 'style-' . $attr_ajaxs->product_style;
	$product_item_class[] = $attr_ajaxs->boostrap_rows_space;
	$product_item_class[] = 'col-bg-' . $attr_ajaxs->boostrap_bg_items;
	$product_item_class[] = 'col-lg-' . $attr_ajaxs->boostrap_lg_items;
	$product_item_class[] = 'col-md-' . $attr_ajaxs->boostrap_md_items;
	$product_item_class[] = 'col-sm-' . $attr_ajaxs->boostrap_sm_items;
	$product_item_class[] = 'col-xs-' . $attr_ajaxs->boostrap_xs_items;
	$product_item_class[] = 'col-ts-' . $attr_ajaxs->boostrap_ts_items;
	
	$loop         = new wp_query( $args );
	$max_num_page = $loop->max_num_pages;
	$query_paged  = $loop->query_vars['paged'];
	if ( $query_paged >= 0 && ( $query_paged < $max_num_page ) ) {
		$show_button = '1';
	} else {
		$show_button = '0';
	}
	if ( $max_num_page <= 1 ) {
		$show_button = 0;
	}
	
	$product_size_args = array(
		'width'  => 320,
		'height' => 320
	);
	
	if ( isset( $attr_ajaxs->product_image_size ) ) {
		if ( $attr_ajaxs->product_image_size == 'custom' ) {
			$product_size_args['width']  = $attr_ajaxs->product_custom_thumb_width;
			$product_size_args['height'] = $attr_ajaxs->product_custom_thumb_height;
		} else {
			$product_image_size          = explode( "x", $attr_ajaxs->product_image_size );
			$product_size_args['width']  = $product_image_size[0];
			$product_size_args['height'] = $product_image_size[1];
		}
	}
	
	ob_start();
	while ( $loop->have_posts() ) : $loop->the_post();
		?>
        <li id="post-<?php echo get_the_ID(); ?>" <?php post_class( $product_item_class ); ?>>
			<?php wc_get_template( 'product-styles/content-product-style-' . $attr_ajaxs->product_style . '.php', $product_size_args ); ?>
        </li>
		<?php
	endwhile;
	wp_reset_query();
	$response['html']    = ob_get_clean();
	$response['success'] = 'ok';
	$response['show_bt'] = $show_button;
	wp_send_json( $response );
	die();
}

// AJAX Tabs
function ciloe_detect_shortcode( $id, $tab_id ) {
	$post = get_post( $id );
	preg_match_all( '/\[vc_tta_section(.*?)vc_tta_section\]/', $post->post_content, $matches );
	if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
		foreach ( $matches[0] as $key => $value ) {
			preg_match_all( '/tab_id="([^"]+)"/', $value, $matches_ids );
			foreach ( $matches_ids[1] as $matches_id ) {
				if ( $tab_id == $matches_id ) {
					return $value;
				}
			}
		}
	}
}

/* AJAX TABS */
if ( ! function_exists( ( 'ciloe_ajax_tabs' ) ) ) {
	function ciloe_ajax_tabs() {
		$response   = array(
			'html'    => '',
			'message' => '',
			'success' => 'no',
		);
		$section_id = isset( $_POST['section_id'] ) ? $_POST['section_id'] : '';
		$id         = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$shortcode  = ciloe_detect_shortcode( $id, $section_id );
		WPBMap::addAllMappedShortcodes();
		$response['html']    = do_shortcode( $shortcode );
		$response['success'] = 'ok';
		
		wp_send_json( $response );
		die();
	}
	
	// TABS ajaxify update
	add_action( 'wp_ajax_ciloe_ajax_tabs', 'ciloe_ajax_tabs' );
	add_action( 'wp_ajax_nopriv_ciloe_ajax_tabs', 'ciloe_ajax_tabs' );
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