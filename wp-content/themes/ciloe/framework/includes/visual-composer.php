<?php
if ( ! class_exists( 'Ciloe_Visual_Composer' ) ) {
	class Ciloe_Visual_Composer {
		public function __construct() {
			$this->define_constants();
			add_filter( 'vc_google_fonts_get_fonts_filter', array( $this, 'vc_fonts' ) );
//			add_action( 'init', array( &$this, 'params' ) );
//			add_action( 'init', array( &$this, 'autocomplete' ) );
			$this->params();
			$this->autocomplete();
			/* Custom font Icon*/
			add_filter( 'vc_iconpicker-type-ciloecustomfonts', array( &$this, 'iconpicker_type_ciloe_customfonts' ) );
			$this->map_shortcode();
		}
		
		/**
		 * Define  Constants.
		 */
		private function define_constants() {
			$this->define( 'CILOE_SHORTCODE_PREVIEW', get_theme_file_uri( '/framework/assets/images/shortcode-previews/' ) );
			$this->define( 'CILOE_SHORTCODES_ICONS_URI', get_theme_file_uri( '/framework/assets/images/vc-shortcodes-icons/' ) );
			$this->define( 'CILOE_PRODUCT_STYLE_PREVIEW', get_theme_file_uri( '/woocommerce/product-styles/' ) );
			
		}
		
		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		
		function params() {
			if ( function_exists( 'ciloe_toolkit_vc_param' ) ) {
				ciloe_toolkit_vc_param( 'taxonomy', array( $this, 'taxonomy_field' ) );
				ciloe_toolkit_vc_param( 'uniqid', array( $this, 'uniqid_field' ) );
				ciloe_toolkit_vc_param( 'select_preview', array( $this, 'select_preview_field' ) );
				ciloe_toolkit_vc_param( 'number', array( $this, 'number_field' ) );
			}
		}
		
		/**
		 * load param autocomplete render
		 * */
		public function autocomplete() {
			add_filter( 'vc_autocomplete_ciloe_products_ids_callback', array(
				$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_products_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_product_ids_callback', array(
				&$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_product_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_deal_ids_callback', array(
				$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_deal_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_productsimple_ids_callback', array(
				$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_productsimple_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_pinmap_ids_callback', array(
				$this,
				'pinmapIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_ciloe_pinmap_ids_render', array(
				$this,
				'pinmapIdAutocompleteRender'
			), 10, 1 );
			
		}
		
		/*
         * taxonomy_field
         * */
		public function taxonomy_field( $settings, $value ) {
			$dependency = '';
			$value_arr  = $value;
			if ( ! is_array( $value_arr ) ) {
				$value_arr = array_map( 'trim', explode( ',', $value_arr ) );
			}
			$output = '';
			if ( isset( $settings['hide_empty'] ) && $settings['hide_empty'] ) {
				$settings['hide_empty'] = 1;
			} else {
				$settings['hide_empty'] = 0;
			}
			if ( ! empty( $settings['taxonomy'] ) ) {
				$terms_fields = array();
				if ( isset( $settings['placeholder'] ) && $settings['placeholder'] ) {
					$terms_fields[] = "<option value=''>" . $settings['placeholder'] . "</option>";
				}
				$terms = get_terms( $settings['taxonomy'], array(
					'parent'     => $settings['parent'],
					'hide_empty' => $settings['hide_empty']
				) );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$selected       = ( in_array( $term->slug, $value_arr ) ) ? ' selected="selected"' : '';
						$terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
					}
				}
				$size     = ( ! empty( $settings['size'] ) ) ? 'size="' . $settings['size'] . '"' : '';
				$multiple = ( ! empty( $settings['multiple'] ) ) ? 'multiple="multiple"' : '';
				$uniqeID  = uniqid();
				$output   = '<select style="width:100%;" id="vc_taxonomy-' . $uniqeID . '" ' . $multiple . ' ' . $size . ' name="' . $settings['param_name'] . '" class="ciloe_vc_taxonomy wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" ' . $dependency . '>'
				            . implode( $terms_fields )
				            . '</select>';
			}
			
			return $output;
		}
		
		public function uniqid_field( $settings, $value ) {
			if ( ! $value ) {
				$value = uniqid( hash( 'crc32', $settings['param_name'] ) . '-' );
			}
			$output = '<input type="text" class="wpb_vc_param_value textfield" name="' . $settings['param_name'] . '" value="' . esc_attr( $value ) . '" />';
			
			return $output;
		}
		
		public function number_field( $settings, $value ) {
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( ! $value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$output = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="wpb_vc_param_value textfield ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . esc_attr( $value ) . '" ' . $dependency . ' style="max-width:100px; margin-right: 10px;" />' . $suffix;
			
			return $output;
		}
		
		public function select_preview_field( $settings, $value ) {
			ob_start();
			// Get menus list
			$options = $settings['value'];
			$default = $settings['default'];
			if ( is_array( $options ) && count( $options ) > 0 ) {
				$uniqeID = uniqid();
				$i       = 0;
				?>
                <div class="container-select_preview">
                    <select id="ciloe_select_preview-<?php echo esc_attr( $uniqeID ); ?>"
                            name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                            class="ciloe_select_preview vc_select_image wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field">
						<?php foreach ( $options as $k => $option ): ?>
							<?php
							if ( $i == 0 ) {
								$first_value = $k;
							}
							$i ++;
							?>
							<?php $selected = ( $k == $value ) ? ' selected="selected"' : ''; ?>
                            <option data-img="<?php echo esc_url( $option['img'] ); ?>"
                                    value='<?php echo esc_attr( $k ) ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['alt'] ) ?></option>
						<?php endforeach; ?>
                    </select>
                    <div class="image-preview">
						<?php if ( isset( $options[ $value ] ) && $options[ $value ] && ( isset( $options[ $value ]['img'] ) ) ): ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $value ]['img'] ); ?>">
						<?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $default ]['img'] ); ?>">
						<?php endif; ?>
                    </div>
                </div>
				<?php
			}
			
			return ob_get_clean();
		}
		
		/**
		 * Suggester for autocomplete by id/name/title/sku
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 * @return array - id's from products with title/sku.
		 */
		public function productIdAutocompleteSuggester( $query ) {
			global $wpdb;
			$product_id      = (int) $query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
    					FROM {$wpdb->posts} AS a
    					LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
    					WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'ciloe' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'ciloe' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'ciloe' ) . ': ' . $value['sku'] : '' );
					$results[]     = $data;
				}
			}
			
			return $results;
		}
		
		/**
		 * Find product by id
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function productIdAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get product
				$product_object = wc_get_product( (int) $query );
				if ( is_object( $product_object ) ) {
					$product_sku         = $product_object->get_sku();
					$product_title       = $product_object->get_title();
					$product_id          = $product_object->get_id();
					$product_sku_display = '';
					if ( ! empty( $product_sku ) ) {
						$product_sku_display = ' - ' . esc_html__( 'Sku', 'ciloe' ) . ': ' . $product_sku;
					}
					$product_title_display = '';
					if ( ! empty( $product_title ) ) {
						$product_title_display = ' - ' . esc_html__( 'Title', 'ciloe' ) . ': ' . $product_title;
					}
					$product_id_display = esc_html__( 'Id', 'ciloe' ) . ': ' . $product_id;
					$data               = array();
					$data['value']      = $product_id;
					$data['label']      = $product_id_display . $product_title_display . $product_sku_display;
					
					return ! empty( $data ) ? $data : false;
				}
				
				return false;
			}
			
			return false;
		}
		
		/**
		 * Suggester for autocomplete by id/name/title
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 * @return array - id's from post_types with title/.
		 */
		public function pinmapIdAutocompleteSuggester( $query ) {
			global $wpdb;
			$post_type_id    = (int) $query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title 
    					FROM {$wpdb->posts} AS a 
    					WHERE a.post_type = 'ciloe_mapper' AND ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )", $post_type_id > 0 ? $post_type_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'ciloe' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'ciloe' ) . ': ' . $value['title'] : '' );
					$results[]     = $data;
				}
			}
			
			return $results;
		}
		
		/**
		 * Find product by id
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function pinmapIdAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get post_type
				$post_type_object = wc_get_post_type( (int) $query );
				if ( is_object( $post_type_object ) ) {
					$post_type_title = $post_type_object->get_title();
					$post_type_id    = $post_type_object->get_id();
					
					$post_type_title_display = '';
					if ( ! empty( $post_type_title ) ) {
						$post_type_title_display = ' - ' . esc_html__( 'Title', 'ciloe' ) . ': ' . $post_type_title;
					}
					$post_type_id_display = esc_html__( 'Id', 'ciloe' ) . ': ' . $post_type_id;
					$data                 = array();
					$data['value']        = $post_type_id;
					$data['label']        = $post_type_id_display . $post_type_title_display;
					
					return ! empty( $data ) ? $data : false;
				}
				
				return false;
			}
			
			return false;
		}
		
		public function vc_fonts( $fonts_list ) {
			/* Gotham */
			$Gotham              = new stdClass();
			$Gotham->font_family = "Gotham";
			$Gotham->font_styles = "100,300,400,600,700";
			$Gotham->font_types  = "300 Light:300:light,400 Normal:400:normal";
			
			$fonts = array( $Gotham );
			
			return array_merge( $fonts_list, $fonts );
		}
		
		/* Custom Font icon*/
		function iconpicker_type_ciloe_customfonts( $icons ) {
			$icons['Flaticon'] = array(
				array( 'flaticon-01search' => '01' ),
				array( 'flaticon-02arrows' => '02' ),
				array( 'flaticon-03wishlist' => '03' ),
				array( 'flaticon-04shopcart' => '04' ),
				array( 'flaticon-05menu' => '05' ),
				array( 'flaticon-06accessories' => '06' ),
				array( 'flaticon-07furniture' => '07' ),
				array( 'flaticon-08women-shoes' => '08' ),
				array( 'flaticon-09handbag' => '09' ),
				array( 'flaticon-10watch' => '10' ),
				array( 'flaticon-11sport-shoes' => '11' ),
				array( 'flaticon-12clothes' => '12' ),
				array( 'flaticon-13male-telemarketer' => '13' ),
				array( 'flaticon-14credit-card-security' => '14' ),
				array( 'flaticon-15return-of-investment' => '15' ),
				array( 'flaticon-16transport' => '16' ),
			);
			
			return $icons;
		}
		
		public static function map_shortcode() {
			/* Simple Product */
			$allowed_tags = array(
				'em'     => array(),
				'i'      => array(),
				'b'      => array(),
				'strong' => array(),
				'a'      => array(
					'href'   => array(),
					'target' => array(),
					'class'  => array(),
					'id'     => array(),
					'title'  => array(),
				),
			);
			
			/* Full page elem */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Full Page Element', 'ciloe' ),
					'base'        => 'ciloe_fullpageelem', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display text and an image for full page.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'fullpage.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'elem-text-right' => array(
									'alt' => esc_html__( 'Text Right', 'ciloe' ),
									'img' => CILOE_SHORTCODE_PREVIEW . 'fullpageelem/full-page-elem-text-right.jpg',
								),
								'elem-text-left'  => array(
									'alt' => esc_html__( 'Text Left', 'ciloe' ),
									'img' => CILOE_SHORTCODE_PREVIEW . 'fullpageelem/full-page-elem-text-left.jpg',
								),
							),
							'default'     => 'elem-text-left',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'class'      => '',
							'heading'    => esc_html__( 'Title', 'ciloe' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'class'      => '',
							'heading'    => esc_html__( 'Sub Title', 'ciloe' ),
							'param_name' => 'sub_title'
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Button Link', 'ciloe' ),
							'param_name' => 'link',
						),
						array(
							'type'        => 'attach_image',
							'heading'     => esc_html__( 'Image', 'ciloe' ),
							'param_name'  => 'img_id',
							'admin_label' => false,
						),
						
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Title Color', 'ciloe' ),
							'param_name' => 'title_color',
							'value'      => '#000',
							'group'      => esc_html__( 'Text Options', 'ciloe' )
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Sub Title Color', 'ciloe' ),
							'param_name' => 'sub_title_color',
							'value'      => '#919191',
							'group'      => esc_html__( 'Text Options', 'ciloe' )
						),
						
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Image Block Background Color', 'ciloe' ),
							'param_name' => 'img_block_bg_color',
							'value'      => '#f4f4f4',
							'group'      => esc_html__( 'Image Options', 'ciloe' )
						),
						
						array(
							"type"        => 'textfield',
							"heading"     => esc_html__( 'Extra class name', 'ciloe' ),
							"param_name"  => 'el_class',
							"description" => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'type'        => 'css_editor',
							'heading'     => esc_html__( 'Css', 'ciloe' ),
							'param_name'  => 'css',
							'group'       => esc_html__( 'Design Options', 'ciloe' ),
							'description' => esc_html__( 'NOTE: This design option only apply for text block!!!', 'ciloe' ),
						),
						array(
							'param_name'       => 'fami_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					)
				)
			);
			
			/* Map New Banner */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Banner', 'ciloe' ),
					'base'        => 'ciloe_banner', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a Banner list.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'banner.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'style1' => array(
									'alt' => 'Banner Deal Of The Day', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style1.jpg',
								),
								'style2' => array(
									'alt' => 'Banner Title, Button layout 1', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style2.jpg',
								),
								'style4' => array(
									'alt' => 'Banner Title, Subtitle, Button layout 2', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style4.jpg',
								),
								'style3' => array(
									'alt' => 'Banner Title, Button layout 3', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style3.jpg',
								),
								'style7' => array(
									'alt' => 'Banner, Button 4', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style7.jpg',
								),
								'style5' => array(
									'alt' => 'Banner Title', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style5.jpg',
								),
								'style6' => array(
									'alt' => 'Banner, Button', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'banner/style6.jpg',
								),
							
							),
							'default'     => 'style1',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textarea',
							'class'       => '',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'ciloe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2', 'style4', 'style3', 'style5', 'style7' ),
							),
						),
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'class'      => '',
							'heading'    => esc_html__( 'Sub Title', 'ciloe' ),
							'param_name' => 'sub_title',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style1', 'style2', 'style4' ),
							),
						),
						array(
							'param_name' => 'title_align',
							'heading'    => esc_html__( 'Title Align', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'ciloe' )   => 'left',
								esc_html__( 'Right', 'ciloe' )  => 'right',
								esc_html__( 'Center', 'ciloe' ) => 'center',
							),
							'sdt'        => 'center',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style4' ),
							),
						),
						array(
							'param_name' => 'position_align',
							'heading'    => esc_html__( 'Position Align', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'ciloe' )   => 'left',
								esc_html__( 'Right', 'ciloe' )  => 'right',
								esc_html__( 'Center', 'ciloe' ) => 'center',
							),
							'sdt'        => 'left',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style4' ),
							),
						),
						array(
							'param_name' => 'title_type',
							'heading'    => esc_html__( 'Title Type', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Dark', 'ciloe' )  => 'dark',
								esc_html__( 'Light', 'ciloe' ) => 'light',
							),
							'sdt'        => 'light',
						),
						array(
							'heading'     => esc_html__( 'Date', 'ciloe' ),
							'description' => esc_html__( 'Enter the date in format: YYYY/MM/DD', 'ciloe' ),
							'admin_label' => true,
							'type'        => 'textfield',
							'param_name'  => 'date',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1' ),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Banner Link', 'ciloe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add banner link.', 'ciloe' ),
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image", "ciloe" ),
							"param_name"  => "bg_simple_image",
							"admin_label" => false,
						),
						array(
							'type'        => 'textfield',
							'holder'      => 'div',
							'class'       => '',
							'heading'     => esc_html__( 'Image Size', 'ciloe' ),
							'param_name'  => 'img_size',
							'std'         => '681x804',
							'description' => esc_html__( '{width}x{height}. Example: 950x950, 1280x667 etc...', 'ciloe' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'banner_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Map New Section tabs */
			vc_map(
				array(
					'name'                      => esc_html__( 'Section', 'ciloe' ),
					'base'                      => 'vc_tta_section',
					'icon'                      => 'icon-wpb-ui-tta-section',
					'allowed_container_element' => 'vc_row',
					'is_container'              => true,
					'show_settings_on_create'   => false,
					'as_child'                  => array(
						'only' => 'vc_tta_tour,vc_tta_tabs,vc_tta_accordion',
					),
					'category'                  => esc_html__( 'Content', 'ciloe' ),
					'description'               => esc_html__( 'Section for Tabs, Tours, Accordions.', 'ciloe' ),
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'title',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'description' => esc_html__( 'Enter section title (Note: you can leave it empty).', 'ciloe' ),
						),
						array(
							'type'        => 'el_id',
							'param_name'  => 'tab_id',
							'settings'    => array(
								'auto_generate' => true,
							),
							'heading'     => esc_html__( 'Section ID', 'ciloe' ),
							'description' => esc_html__( 'Enter section ID (Note: make sure it is unique and valid according to w3c specification.', 'ciloe' )
						),
						array(
							'type'        => 'checkbox',
							'param_name'  => 'add_icon',
							'heading'     => esc_html__( 'Add icon?', 'ciloe' ),
							'description' => esc_html__( 'Add icon next to section title.', 'ciloe' ),
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'i_position',
							'value'       => array(
								esc_html__( 'Before title', 'ciloe' ) => 'left',
								esc_html__( 'After title', 'ciloe' )  => 'right',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'heading'     => esc_html__( 'Icon position', 'ciloe' ),
							'description' => esc_html__( 'Select icon position.', 'ciloe' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'ciloe' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'ciloe' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'ciloe' ) => 'fontflaticon',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'ciloe' ),
						),
						array(
							'param_name'  => 'icon_ciloecustomfonts',
							'heading'     => esc_html__( 'Icon', 'ciloe' ),
							'description' => esc_html__( 'Select icon from library.', 'ciloe' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'ciloecustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'ciloe' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							// default value to backend editor admin_label
							'settings'    => array(
								'emptyIcon'    => false,
								// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,
								// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'ciloe' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
					),
					'js_view'                   => 'VcBackendTtaSectionView',
					'custom_markup'             => '
                    <div class="vc_tta-panel-heading">
                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
                    </div>
                    <div class="vc_tta-panel-body">
                        {{ editor_controls }}
                        <div class="{{ container-class }}">
                        {{ content }}
                        </div>
                    </div>',
					'default_content'           => '',
				)
			);
			
			/*Map New section title */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Section Title', 'ciloe' ),
					'base'        => 'ciloe_title', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a custom title.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'section-title.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'title/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'title/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'title/style2.jpg',
								),
							
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'ciloe' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Descriptions', 'ciloe' ),
							'param_name'  => 'des',
							'description' => esc_html__( 'The Descriptions of shortcode', 'ciloe' ),
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default', 'style2' ),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'ciloe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link.', 'ciloe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default', 'style2' ),
							),
						),
						
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'title_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Title And Short Description */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Title And Short Description', 'ciloe' ),
					'base'        => 'ciloe_titleandshortdesc', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Displays a short description area with a title and an action button', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'title-short-desc.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default', //CILOE_SHORTCODE_PREVIEW
								),
								'style1'  => array(
									'alt' => 'Style 01', //CILOE_SHORTCODE_PREVIEW
								),
							
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Align', 'ciloe' ),
							'param_name'  => 'text_align',
							'value'       => array(
								esc_html__( 'Left', 'ciloe' )   => 'text-left',
								esc_html__( 'Right', 'ciloe' )  => 'text-right',
								esc_html__( 'Center', 'ciloe' ) => 'text-center',
							),
							'admin_label' => true,
							'std'         => 'text-left',
						),
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'class'      => '',
							'heading'    => esc_html__( 'Title', 'ciloe' ),
							'param_name' => 'title',
						),
						array(
							'type'       => 'textfield',
							'holder'     => 'div',
							'class'      => '',
							'heading'    => esc_html__( 'Sub Title', 'ciloe' ),
							'param_name' => 'subtitle',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style1' ),
							),
						),
						array(
							'type'       => 'textarea',
							'holder'     => 'div',
							'class'      => '',
							'heading'    => esc_html__( 'Short Description', 'ciloe' ),
							'param_name' => 'short_desc'
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Button Link', 'ciloe' ),
							'param_name' => 'link',
						),
						
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Title Color', 'ciloe' ),
							'param_name' => 'title_color',
							'value'      => '#fff',
							'group'      => esc_html__( 'Text Options', 'ciloe' )
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Short Description Color', 'ciloe' ),
							'param_name' => 'short_desc_color',
							'value'      => '#fff',
							'group'      => esc_html__( 'Text Options', 'ciloe' )
						),
						
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'fami_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			
			// Map new Tabs element.
			vc_map(
				array(
					'name'                    => esc_html__( 'Ciloe: Tabs', 'ciloe' ),
					'base'                    => 'ciloe_tabs',
					'icon'                    => CILOE_SHORTCODES_ICONS_URI . 'tabs.png',
					'is_container'            => true,
					'show_settings_on_create' => false,
					'as_parent'               => array(
						'only' => 'vc_tta_section',
					),
					'category'                => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description'             => esc_html__( 'Tabs content', 'ciloe' ),
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'tabs/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style1', //CILOE_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . 'tabs/style1.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						vc_map_add_css_animation(),
						array(
							'param_name' => 'ajax_check',
							'heading'    => esc_html__( 'Using Ajax Tabs', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'ciloe' ) => '1',
								esc_html__( 'No', 'ciloe' )  => '0',
							),
							'std'        => '0',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Active Section', 'ciloe' ),
							'param_name' => 'active_section',
							'std'        => '1',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Padding Tabs', 'ciloe' ),
							'param_name'  => 'padding_tabs',
							'std'         => '0',
							'description' => esc_html__( 'Ex: 60px', 'ciloe' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'tabs_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'collapsible_all',
							'heading'          => esc_html__( 'Allow collapse all?', 'ciloe' ),
							'description'      => esc_html__( 'Allow collapse all accordion sections.', 'ciloe' ),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                 => 'VcBackendTtaTabsView',
					'custom_markup'           => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
					                             . '<ul class="vc_tta-tabs-list">'
					                             . '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
					                             . '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
					'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'ciloe' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'ciloe' ), 2 ) . '"][/vc_tta_section]
                    ',
					'admin_enqueue_js'        => array(
						vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
					),
				)
			);
			
			/* Map New Accordion */
			vc_map(
				array(
					'name'                    => esc_html__( 'Ciloe: Accordions', 'ciloe' ),
					'base'                    => 'ciloe_accordions',
					'icon'                    => CILOE_SHORTCODES_ICONS_URI . 'accordion.png',
					'is_container'            => true,
					'show_settings_on_create' => false,
					'as_parent'               => array(
						'only' => 'vc_tta_section',
					),
					'category'                => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description'             => esc_html__( 'Accordions content', 'ciloe' ),
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'default', //ciloe_SHORTCODE_PREVIEW
									'img' => CILOE_SHORTCODE_PREVIEW . '/accordion/default.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Tabs title', 'ciloe' ),
							'param_name'  => 'tab_title',
							'admin_label' => true,
						),
						array(
							'param_name' => 'ajax_check',
							'heading'    => esc_html__( 'Using Ajax Tabs', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'ciloe' ) => '1',
								esc_html__( 'No', 'ciloe' )  => '0',
							),
							'std'        => '0',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Tabs active', 'ciloe' ),
							'param_name' => 'active_tab',
							'sdt'        => '1',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'accordions_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'collapsible_all',
							'heading'          => esc_html__( 'Allow collapse all?', 'ciloe' ),
							'description'      => esc_html__( 'Allow collapse all accordion sections.', 'ciloe' ),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                 => 'VcBackendTtaAccordionView',
					'custom_markup'           => '
                        <div class="vc_tta-container" data-vc-action="collapseAll">
                            <div class="vc_general vc_tta vc_tta-accordion vc_tta-color-backend-accordion-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-controls-align-left vc_tta-gap-2">
                               <div class="vc_tta-panels vc_clearfix {{container-class}}">
                                  {{ content }}
                                  <div class="vc_tta-panel vc_tta-section-append">
                                     <div class="vc_tta-panel-heading">
                                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left">
                                           <a href="javascript:;" aria-expanded="false" class="vc_tta-backend-add-control">
                                               <span class="vc_tta-title-text">' . esc_html__( 'Add Section', 'ciloe' ) . '</span>
                                                <i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i>
                                            </a>
                                        </h4>
                                     </div>
                                  </div>
                               </div>
                            </div>
                        </div>',
					'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Section', 'ciloe' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Section', 'ciloe' ), 2 ) . '"][/vc_tta_section]
					',
				)
			);
			
			// Map new Products
			// CUSTOM PRODUCT SIZE
			$product_size_width_list = array();
			$width                   = 300;
			$height                  = 300;
			$crop                    = 1;
			if ( function_exists( 'wc_get_image_size' ) ) {
				$size   = wc_get_image_size( 'shop_catalog' );
				$width  = isset( $size['width'] ) ? $size['width'] : $width;
				$height = isset( $size['height'] ) ? $size['height'] : $height;
				$crop   = isset( $size['crop'] ) ? $size['crop'] : $crop;
			}
			for ( $i = 100; $i < $width; $i = $i + 10 ) {
				array_push( $product_size_width_list, $i );
			}
			$product_size_list                           = array();
			$product_size_list[ $width . 'x' . $height ] = $width . 'x' . $height;
			foreach ( $product_size_width_list as $k => $w ) {
				$w = intval( $w );
				if ( isset( $width ) && $width > 0 ) {
					$h = round( $height * $w / $width );
				} else {
					$h = $w;
				}
				$product_size_list[ $w . 'x' . $h ] = $w . 'x' . $h;
			}
			$product_size_list['Custom'] = 'custom';
			$attributes_tax              = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			
			$attributes = array();
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Products', 'ciloe' ),
					'base'        => 'ciloe_products', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a product list or grid.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'product.png',
					'params'      => array(
						
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Product List style', 'ciloe' ),
							'param_name'  => 'productsliststyle',
							'value'       => array(
								esc_html__( 'Grid Bootstrap', 'ciloe' ) => 'grid',
								esc_html__( 'Owl Carousel', 'ciloe' )   => 'owl',
							),
							'description' => esc_html__( 'Select a style for list', 'ciloe' ),
							'admin_label' => true,
							'std'         => 'grid',
						),
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Product style', 'ciloe' ),
							'value'       => array(
								'1' => array(
									'alt' => esc_html__( 'Style 01', 'ciloe' ),
									'img' => CILOE_PRODUCT_STYLE_PREVIEW . 'content-product-style-1.jpg',
								),
							),
							'default'     => '1',
							'admin_label' => true,
							'param_name'  => 'product_style',
							'description' => esc_html__( 'Select a style for product item', 'ciloe' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Total items', 'ciloe' ),
							'param_name' => 'per_page',
							'value'      => 10,
							"dependency" => array(
								"element" => "target",
								"value"   => array(
									'best-selling',
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'product_attribute',
									'on_sale',
									'on_new'
								)
							),
						),
						array(
							'heading'    => esc_html__( 'Enable Load More', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Show All', 'ciloe' )         => 'showall',
								esc_html__( 'Load More Button', 'ciloe' ) => 'loadmore',
							),
							'std'        => false,
							'param_name' => 'enable_loadmore',
							'dependency' => array( 'element' => 'productsliststyle', 'value' => 'grid' ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Button Link', 'ciloe' ),
							'param_name'  => 'btn_link',
							'description' => esc_html__( 'Leave button link empty if you don\'t want to show the button.', 'ciloe' ),
							'dependency'  => array(
								'element' => 'productsliststyle',
								'value'   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image size', 'ciloe' ),
							'param_name'  => 'product_image_size',
							'value'       => $product_size_list,
							'description' => esc_html__( 'Select a size for product', 'ciloe' ),
							'std'         => '320x387',
							'admin_label' => true,
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Width", 'ciloe' ),
							"param_name" => "product_custom_thumb_width",
							"value"      => $width,
							"suffix"     => esc_html__( "px", 'ciloe' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Height", 'ciloe' ),
							"param_name" => "product_custom_thumb_height",
							"value"      => $height,
							"suffix"     => esc_html__( "px", 'ciloe' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						/*Products */
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'ciloe' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => true,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'ciloe' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'ciloe' ),
							'std'         => '',
							'group'       => esc_html__( 'Products options', 'ciloe' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Target', 'ciloe' ),
							'param_name'  => 'target',
							'value'       => array(
								esc_html__( 'Best Selling Products', 'ciloe' ) => 'best-selling',
								esc_html__( 'Top Rated Products', 'ciloe' )    => 'top-rated',
								esc_html__( 'Recent Products', 'ciloe' )       => 'recent-product',
								esc_html__( 'Product Category', 'ciloe' )      => 'product-category',
								esc_html__( 'Products', 'ciloe' )              => 'products',
								esc_html__( 'Featured Products', 'ciloe' )     => 'featured_products',
								esc_html__( 'On Sale', 'ciloe' )               => 'on_sale',
								esc_html__( 'On New', 'ciloe' )                => 'on_new',
							),
							'description' => esc_html__( 'Choose the target to filter products', 'ciloe' ),
							'std'         => 'recent-product',
							'group'       => esc_html__( 'Products options', 'ciloe' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'ciloe' ),
							"param_name"  => "orderby",
							"value"       => array(
								'',
								esc_html__( 'Date', 'ciloe' )          => 'date',
								esc_html__( 'ID', 'ciloe' )            => 'ID',
								esc_html__( 'Author', 'ciloe' )        => 'author',
								esc_html__( 'Title', 'ciloe' )         => 'title',
								esc_html__( 'Modified', 'ciloe' )      => 'modified',
								esc_html__( 'Random', 'ciloe' )        => 'rand',
								esc_html__( 'Comment count', 'ciloe' ) => 'comment_count',
								esc_html__( 'Menu order', 'ciloe' )    => 'menu_order',
								esc_html__( 'Sale price', 'ciloe' )    => '_sale_price',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort.", 'ciloe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array(
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'on_sale',
									'on_new',
									'product_attribute'
								)
							),
							'group'       => esc_html__( 'Products options', 'ciloe' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'ciloe' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'ciloe' )  => 'ASC',
								esc_html__( 'DESC', 'ciloe' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'ciloe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array(
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'on_sale',
									'on_new',
									'product_attribute'
								)
							),
							'group'       => esc_html__( 'Products options', 'ciloe' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Products', 'ciloe' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => true,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
							'description' => esc_html__( 'Enter List of Products', 'ciloe' ),
							"dependency"  => array( "element" => "target", "value" => array( 'products' ) ),
							'group'       => esc_html__( 'Products options', 'ciloe' ),
						),
						/* OWL Settings */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'ciloe' )  => '1',
								esc_html__( '2 Rows', 'ciloe' ) => '2',
								esc_html__( '3 Rows', 'ciloe' ) => '3',
								esc_html__( '4 Rows', 'ciloe' ) => '4',
								esc_html__( '5 Rows', 'ciloe' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'ciloe' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'ciloe' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'ciloe' ) => 'rows-space-0',
								esc_html__( '10px', 'ciloe' )    => 'rows-space-10',
								esc_html__( '20px', 'ciloe' )    => 'rows-space-20',
								esc_html__( '30px', 'ciloe' )    => 'rows-space-30',
								esc_html__( '40px', 'ciloe' )    => 'rows-space-40',
								esc_html__( '50px', 'ciloe' )    => 'rows-space-50',
								esc_html__( '60px', 'ciloe' )    => 'rows-space-60',
								esc_html__( '70px', 'ciloe' )    => 'rows-space-70',
								esc_html__( '80px', 'ciloe' )    => 'rows-space-80',
								esc_html__( '90px', 'ciloe' )    => 'rows-space-90',
								esc_html__( '100px', 'ciloe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'ciloe' ),
							"dependency" => array(
								"element" => "owl_number_row",
								"value"   => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'ciloe' ),
							'param_name'  => 'owl_autoplay',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Navigation', 'ciloe' ),
							'param_name'  => 'owl_navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Circle', 'ciloe' ) => 'circle',
								esc_html__( 'Caret', 'ciloe' )  => 'caret',
								esc_html__( 'Angle', 'ciloe' )  => 'angle',
							),
							'std'         => 'caret',
							'heading'     => esc_html__( 'Nav Type', 'ciloe' ),
							'param_name'  => 'nav_type',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "owl_navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Enable Dots', 'ciloe' ),
							'param_name'  => 'owl_dots',
							'description' => esc_html__( "Show buton dots", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Light', 'ciloe' ) => 'light',
								esc_html__( 'Dark', 'ciloe' )  => 'dark',
							),
							'std'         => 'dark',
							'heading'     => esc_html__( 'Dots Type', 'ciloe' ),
							'param_name'  => 'dots_type',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "owl_dots",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Loop', 'ciloe' ),
							'param_name'  => 'owl_loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'ciloe' ),
							"param_name"  => "owl_slidespeed",
							"value"       => "200",
							"suffix"      => esc_html__( "milliseconds", 'ciloe' ),
							"description" => esc_html__( 'Slide speed in milliseconds', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'ciloe' ),
							"param_name"  => "owl_margin",
							"value"       => "0",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'ciloe' ),
							"param_name"  => "owl_ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px and < 1500px )", 'ciloe' ),
							"param_name"  => "owl_lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'ciloe' ),
							"param_name"  => "owl_md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'ciloe' ),
							"param_name"  => "owl_sm_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'ciloe' ),
							"param_name"  => "owl_xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'ciloe' ),
							"param_name"  => "owl_ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						/* Bostrap setting */
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'ciloe' ),
							'param_name' => 'boostrap_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'ciloe' ) => 'rows-space-0',
								esc_html__( '10px', 'ciloe' )    => 'rows-space-10',
								esc_html__( '20px', 'ciloe' )    => 'rows-space-20',
								esc_html__( '30px', 'ciloe' )    => 'rows-space-30',
								esc_html__( '40px', 'ciloe' )    => 'rows-space-40',
								esc_html__( '50px', 'ciloe' )    => 'rows-space-50',
								esc_html__( '60px', 'ciloe' )    => 'rows-space-60',
								esc_html__( '70px', 'ciloe' )    => 'rows-space-70',
								esc_html__( '80px', 'ciloe' )    => 'rows-space-80',
								esc_html__( '90px', 'ciloe' )    => 'rows-space-90',
								esc_html__( '100px', 'ciloe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Boostrap settings', 'ciloe' ),
							"dependency" => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'ciloe' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '1 item', 'ciloe' )  => '12',
								esc_html__( '2 items', 'ciloe' ) => '6',
								esc_html__( '3 items', 'ciloe' ) => '4',
								esc_html__( '4 items', 'ciloe' ) => '3',
								esc_html__( '5 items', 'ciloe' ) => '15',
								esc_html__( '6 items', 'ciloe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '15',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'ciloe' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '1 item', 'ciloe' )  => '12',
								esc_html__( '2 items', 'ciloe' ) => '6',
								esc_html__( '3 items', 'ciloe' ) => '4',
								esc_html__( '4 items', 'ciloe' ) => '3',
								esc_html__( '5 items', 'ciloe' ) => '15',
								esc_html__( '6 items', 'ciloe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on landscape tablet', 'ciloe' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '1 item', 'ciloe' )  => '12',
								esc_html__( '2 items', 'ciloe' ) => '6',
								esc_html__( '3 items', 'ciloe' ) => '4',
								esc_html__( '4 items', 'ciloe' ) => '3',
								esc_html__( '5 items', 'ciloe' ) => '15',
								esc_html__( '6 items', 'ciloe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on portrait tablet', 'ciloe' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '1 item', 'ciloe' )  => '12',
								esc_html__( '2 items', 'ciloe' ) => '6',
								esc_html__( '3 items', 'ciloe' ) => '4',
								esc_html__( '4 items', 'ciloe' ) => '3',
								esc_html__( '5 items', 'ciloe' ) => '15',
								esc_html__( '6 items', 'ciloe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '4',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'ciloe' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '1 item', 'ciloe' )  => '12',
								esc_html__( '2 items', 'ciloe' ) => '6',
								esc_html__( '3 items', 'ciloe' ) => '4',
								esc_html__( '4 items', 'ciloe' ) => '3',
								esc_html__( '5 items', 'ciloe' ) => '15',
								esc_html__( '6 items', 'ciloe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '6',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'ciloe' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '1 item', 'ciloe' )  => '12',
								esc_html__( '2 items', 'ciloe' ) => '6',
								esc_html__( '3 items', 'ciloe' ) => '4',
								esc_html__( '4 items', 'ciloe' ) => '3',
								esc_html__( '5 items', 'ciloe' ) => '15',
								esc_html__( '6 items', 'ciloe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '12',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'products_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			// Single Product
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Product Wide', 'ciloe' ),
					'base'        => 'ciloe_product', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'For displaying full width product', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'product.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Product style', 'ciloe' ),
							'value'       => array(
								'ir_tl' => array(
									'alt' => esc_html__( 'Product Info Box Left', 'ciloe' ),
									'img' => CILOE_SHORTCODE_PREVIEW . 'product/product-img-right-text-left.jpg',
								),
								'il_tr' => array(
									'alt' => esc_html__( 'Product Info Box Right', 'ciloe' ),
									'img' => CILOE_SHORTCODE_PREVIEW . 'product/product-img-left-text-right.jpg',
								)
							),
							'default'     => 'ir_tl',
							'admin_label' => true,
							'param_name'  => 'product_style',
							'description' => esc_html__( 'Select product style', 'ciloe' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Product', 'ciloe' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => false,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
						),
						array(
							'type'        => 'attach_image',
							'heading'     => esc_html__( 'Image', 'ciloe' ),
							'param_name'  => 'img_id',
							'admin_label' => false,
						),
						array(
							'type'        => 'textfield',
							'holder'      => 'div',
							'class'       => '',
							'heading'     => esc_html__( 'Image Size', 'ciloe' ),
							'param_name'  => 'img_size',
							'std'         => '550x730',
							'description' => esc_html__( '{width}x{height}. Example: 550x730, 700x610 etc...', 'ciloe' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Flash Text', 'ciloe' ),
							'param_name'  => 'flash_text',
							'description' => esc_html__( 'For example: HOT, SALE OF 30%, NEW. Note: this is not WooCommerce\'s default flash text', 'ciloe' ),
						),
						array(
							'type'        => 'colorpicker',
							'heading'     => esc_html__( 'Flash Text Background Color', 'ciloe' ),
							'param_name'  => 'flash_text_bg_color',
							'value'       => '#f45757', //Default Red color
							'description' => esc_html__( 'For example: Red: #f45757, Green: #6ae08c, Light Blue: #67c6ea', 'ciloe' )
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra Class Name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'product_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			vc_map(
				array(
					'name'     => esc_html__( 'Ciloe: Product Simple', 'ciloe' ),
					'base'     => 'ciloe_productsimple',
					'category' => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'icon'     => CILOE_SHORTCODES_ICONS_URI . 'product.png',
					'params'   => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'products/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Conten Left',
									'img' => CILOE_SHORTCODE_PREVIEW . 'products/style1.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Products', 'ciloe' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => false,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
							'description' => esc_html__( 'Enter List of Products', 'ciloe' ),
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image custom", "ciloe" ),
							"param_name"  => "bg_simple_image",
							"admin_label" => false,
						),
						array(
							'type'        => 'textfield',
							'holder'      => 'div',
							'class'       => '',
							'heading'     => esc_html__( 'Image Size', 'ciloe' ),
							'param_name'  => 'img_size',
							'std'         => '681x804',
							'description' => esc_html__( '{width}x{height}. Example: 950x950, etc...', 'ciloe' )
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'productsimple_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					
					
					),
				)
			);
			
			/* Category list item */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Category List item', 'ciloe' ),
					'base'        => 'ciloe_categorylist', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display Categories.', 'ciloe' ),
					"as_child"    => array( 'only' => 'ciloe_container' ),
					'params'      => array(
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'ciloe' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => false,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'ciloe' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'ciloe' ),
							'std'         => '',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Background", "ciloe" ),
							"param_name"  => "bg_cat",
							"admin_label" => true,
						),
						array(
							"type"             => "colorpicker",
							"heading"          => esc_html__( "Mask Overlay Color", 'ciloe' ),
							"param_name"       => "mask_overlay_color",
							"value"            => '#ff4949', //Default Red color
							"description"      => esc_html__( "Choose color", 'ciloe' ),
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							'type'             => 'vc_link',
							'holder'           => 'div',
							'class'            => '',
							'heading'          => esc_html__( 'Link & Text', 'ciloe' ),
							"description"      => esc_html__( " Add link and text readmore.", "ciloe" ),
							'param_name'       => 'link',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" )
						),
						array(
							'param_name'       => 'categories_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
					)
				)
			);
			$allowed_tags = array(
				'em'     => array(),
				'i'      => array(),
				'b'      => array(),
				'strong' => array(),
				'br'     => array(),
				'code'   => array(),
				'a'      => array(
					'href'   => array(),
					'target' => array(),
					'class'  => array(),
					'id'     => array(),
					'title'  => array(),
				),
			);
			/* Instagram */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Instagram', 'ciloe' ),
					'base'        => 'ciloe_instagram', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a instagram photo list.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'instagram.png',
					'params'      => array(
						array(
							'param_name' => 'style',
							'heading'    => esc_html__( 'Select style', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Default', 'ciloe' ) => 'default',
								esc_html__( 'Style1', 'ciloe' )  => 'style1',
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of instagram', 'ciloe' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style1' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Images limit', 'ciloe' ),
							'param_name'  => 'limit',
							'std'         => '6',
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Instagram user ID', 'ciloe' ),
							'param_name'  => 'id',
							'admin_label' => true,
							'description' => esc_html__( 'Your Instagram ID. Ex: 2267639447. ', 'ciloe' ) . '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?', 'ciloe' ) . '</a>',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Access token', 'ciloe' ),
							'param_name'  => 'token',
							'description' => esc_html__( 'Your Instagram token. Ex: 1677ed0.eade9f2bbe8245ea8bdedab984f3b4c3. ', 'ciloe' ) . '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?', 'ciloe' ) . '</a>',
							'admin_label' => true,
						),
						
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'ciloe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'ciloe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'ciloe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'ciloe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'ciloe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'ciloe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px )", 'ciloe' ),
							"param_name"  => "lg_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'ciloe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'ciloe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'ciloe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'ciloe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'instagram_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			
			/* Map New blog */
			$categories_array = array(
				esc_html__( 'All', 'ciloe' ) => '',
			);
			$args             = array();
			$categories       = get_categories( $args );
			foreach ( $categories as $category ) {
				$categories_array[ $category->name ] = $category->slug;
			}
			
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Blog', 'ciloe' ),
					'base'        => 'ciloe_blog', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a blog list.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'blog.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'style-1' => array(
									'alt' => 'Style 01',
									'img' => CILOE_SHORTCODE_PREVIEW . 'blog/style1.jpg',
								),
								'style-2' => array(
									'alt' => 'Style 02',
									'img' => CILOE_SHORTCODE_PREVIEW . 'blog/style2.jpg',
								),
							),
							'default'     => 'style-1',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Number Post', 'ciloe' ),
							'param_name'  => 'per_page',
							'std'         => 10,
							'admin_label' => true,
							'description' => esc_html__( 'Number post in a slide', 'ciloe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-2' ),
							),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'ciloe' ),
							"param_name"  => "per_row",
							"value"       => array(
								esc_html__( '1 item', 'ciloe' ) => '1',
								esc_html__( '2 item', 'ciloe' ) => '2',
								esc_html__( '3 item', 'ciloe' ) => '3',
								esc_html__( '4 item', 'ciloe' ) => '4',
							),
							'std'         => '2',
							"description" => esc_html__( "Select how item posts on 1 row", 'ciloe' ),
						),
						array(
							'param_name'  => 'category_slug',
							'type'        => 'dropdown',
							'value'       => $categories_array, // here I'm stuck
							'heading'     => esc_html__( 'Category filter:', 'ciloe' ),
							"admin_label" => true,
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'ciloe' ),
							"param_name"  => "orderby",
							"value"       => array(
								esc_html__( 'None', 'ciloe' )     => 'none',
								esc_html__( 'ID', 'ciloe' )       => 'ID',
								esc_html__( 'Author', 'ciloe' )   => 'author',
								esc_html__( 'Name', 'ciloe' )     => 'name',
								esc_html__( 'Date', 'ciloe' )     => 'date',
								esc_html__( 'Modified', 'ciloe' ) => 'modified',
								esc_html__( 'Rand', 'ciloe' )     => 'rand',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort retrieved posts.", 'ciloe' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'ciloe' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'ciloe' )  => 'ASC',
								esc_html__( 'DESC', 'ciloe' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'ciloe' ),
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'ciloe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'ciloe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'ciloe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'ciloe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'ciloe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'ciloe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'ciloe' ),
							"param_name"  => "ls_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'ciloe' ),
							"param_name"  => "lg_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'ciloe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'ciloe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'ciloe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'ciloe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'blog_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			
			/*Map new Container */
			vc_map(
				array(
					'name'                    => esc_html__( 'Ciloe: Container', 'ciloe' ),
					'base'                    => 'ciloe_container',
					'category'                => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'is_container'            => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => CILOE_SHORTCODES_ICONS_URI . 'container.png',
					'params'                  => array(
						array(
							'param_name'  => 'content_width',
							'heading'     => esc_html__( 'Content width', 'ciloe' ),
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Default', 'ciloe' )         => 'container',
								esc_html__( 'Custom Boostrap', 'ciloe' ) => 'custom_col',
								esc_html__( 'Custom Width', 'ciloe' )    => 'custom_width',
							),
							'admin_label' => true,
							'std'         => 'container',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Desktop', 'ciloe' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'ciloe' ) => '12',
								esc_html__( '11 column - 11/12', 'ciloe' ) => '11',
								esc_html__( '10 column - 10/12', 'ciloe' ) => '10',
								esc_html__( '9 column - 9/12', 'ciloe' )   => '9',
								esc_html__( '8 column - 8/12', 'ciloe' )   => '8',
								esc_html__( '7 column - 7/12', 'ciloe' )   => '7',
								esc_html__( '6 column - 6/12', 'ciloe' )   => '6',
								esc_html__( '5 column - 5/12', 'ciloe' )   => '5',
								esc_html__( '4 column - 4/12', 'ciloe' )   => '4',
								esc_html__( '3 column - 3/12', 'ciloe' )   => '3',
								esc_html__( '2 column - 2/12', 'ciloe' )   => '2',
								esc_html__( '1 column - 1/12', 'ciloe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'ciloe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'ciloe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >= 1500px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '15',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Desktop', 'ciloe' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'ciloe' ) => '12',
								esc_html__( '11 column - 11/12', 'ciloe' ) => '11',
								esc_html__( '10 column - 10/12', 'ciloe' ) => '10',
								esc_html__( '9 column - 9/12', 'ciloe' )   => '9',
								esc_html__( '8 column - 8/12', 'ciloe' )   => '8',
								esc_html__( '7 column - 7/12', 'ciloe' )   => '7',
								esc_html__( '6 column - 6/12', 'ciloe' )   => '6',
								esc_html__( '5 column - 5/12', 'ciloe' )   => '5',
								esc_html__( '4 column - 4/12', 'ciloe' )   => '4',
								esc_html__( '3 column - 3/12', 'ciloe' )   => '3',
								esc_html__( '2 column - 2/12', 'ciloe' )   => '2',
								esc_html__( '1 column - 1/12', 'ciloe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'ciloe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'ciloe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >= 1200px and < 1500px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on landscape tablet', 'ciloe' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'ciloe' ) => '12',
								esc_html__( '11 column - 11/12', 'ciloe' ) => '11',
								esc_html__( '10 column - 10/12', 'ciloe' ) => '10',
								esc_html__( '9 column - 9/12', 'ciloe' )   => '9',
								esc_html__( '8 column - 8/12', 'ciloe' )   => '8',
								esc_html__( '7 column - 7/12', 'ciloe' )   => '7',
								esc_html__( '6 column - 6/12', 'ciloe' )   => '6',
								esc_html__( '5 column - 5/12', 'ciloe' )   => '5',
								esc_html__( '4 column - 4/12', 'ciloe' )   => '4',
								esc_html__( '3 column - 3/12', 'ciloe' )   => '3',
								esc_html__( '2 column - 2/12', 'ciloe' )   => '2',
								esc_html__( '1 column - 1/12', 'ciloe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'ciloe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'ciloe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=992px and < 1200px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on portrait tablet', 'ciloe' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'ciloe' ) => '12',
								esc_html__( '11 column - 11/12', 'ciloe' ) => '11',
								esc_html__( '10 column - 10/12', 'ciloe' ) => '10',
								esc_html__( '9 column - 9/12', 'ciloe' )   => '9',
								esc_html__( '8 column - 8/12', 'ciloe' )   => '8',
								esc_html__( '7 column - 7/12', 'ciloe' )   => '7',
								esc_html__( '6 column - 6/12', 'ciloe' )   => '6',
								esc_html__( '5 column - 5/12', 'ciloe' )   => '5',
								esc_html__( '4 column - 4/12', 'ciloe' )   => '4',
								esc_html__( '3 column - 3/12', 'ciloe' )   => '3',
								esc_html__( '2 column - 2/12', 'ciloe' )   => '2',
								esc_html__( '1 column - 1/12', 'ciloe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'ciloe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'ciloe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=768px and < 992px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Mobile', 'ciloe' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'ciloe' ) => '12',
								esc_html__( '11 column - 11/12', 'ciloe' ) => '11',
								esc_html__( '10 column - 10/12', 'ciloe' ) => '10',
								esc_html__( '9 column - 9/12', 'ciloe' )   => '9',
								esc_html__( '8 column - 8/12', 'ciloe' )   => '8',
								esc_html__( '7 column - 7/12', 'ciloe' )   => '7',
								esc_html__( '6 column - 6/12', 'ciloe' )   => '6',
								esc_html__( '5 column - 5/12', 'ciloe' )   => '5',
								esc_html__( '4 column - 4/12', 'ciloe' )   => '4',
								esc_html__( '3 column - 3/12', 'ciloe' )   => '3',
								esc_html__( '2 column - 2/12', 'ciloe' )   => '2',
								esc_html__( '1 column - 1/12', 'ciloe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'ciloe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'ciloe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=480  add < 768px )', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Mobile', 'ciloe' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'ciloe' ) => '12',
								esc_html__( '11 column - 11/12', 'ciloe' ) => '11',
								esc_html__( '10 column - 10/12', 'ciloe' ) => '10',
								esc_html__( '9 column - 9/12', 'ciloe' )   => '9',
								esc_html__( '8 column - 8/12', 'ciloe' )   => '8',
								esc_html__( '7 column - 7/12', 'ciloe' )   => '7',
								esc_html__( '6 column - 6/12', 'ciloe' )   => '6',
								esc_html__( '5 column - 5/12', 'ciloe' )   => '5',
								esc_html__( '4 column - 4/12', 'ciloe' )   => '4',
								esc_html__( '3 column - 3/12', 'ciloe' )   => '3',
								esc_html__( '2 column - 2/12', 'ciloe' )   => '2',
								esc_html__( '1 column - 1/12', 'ciloe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'ciloe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'ciloe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device < 480px)', 'ciloe' ),
							'group'       => esc_html__( 'Boostrap settings', 'ciloe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'param_name'  => 'number_width',
							'heading'     => esc_html__( 'width', 'ciloe' ),
							"description" => esc_html__( "you can width by px or %, ex: 100%", "ciloe" ),
							'std'         => '50%',
							'admin_label' => true,
							'type'        => 'textfield',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_width' ),
							),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'param_name'       => 'container_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/*Map New Newsletter*/
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Newsletter', 'ciloe' ),
					'base'        => 'ciloe_newsletter', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a newsletter box.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'newllter.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'newsletter/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 1',
									'img' => CILOE_SHORTCODE_PREVIEW . 'newsletter/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 2',
									'img' => CILOE_SHORTCODE_PREVIEW . 'newsletter/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style 3',
									'img' => CILOE_SHORTCODE_PREVIEW . 'newsletter/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style 4',
									'img' => CILOE_SHORTCODE_PREVIEW . 'newsletter/style4.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'ciloe' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Sub title', 'ciloe' ),
							'param_name'  => 'subtitle',
							'description' => esc_html__( 'The sub title of shortcode, using element "strong" for hight text', 'ciloe' ),
							'std'         => '',
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Description', 'ciloe' ),
							'param_name'  => 'description',
							'description' => esc_html__( 'The description of shortcode, using element "strong" for hight text', 'ciloe' ),
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style4' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Placeholder text", 'ciloe' ),
							"param_name"  => "placeholder_text",
							"admin_label" => false,
							'std'         => 'Email address here',
						),
						array(
							'param_name' => 'newsletter_type',
							'heading'    => esc_html__( 'Newsletter Type', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Dark', 'ciloe' )  => 'dark',
								esc_html__( 'Light', 'ciloe' ) => 'light',
							),
							'sdt'        => 'light',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'newsletter_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/*Map New Custom menu*/
			
			$all_menu = array();
			$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			if ( $menus && count( $menus ) > 0 ) {
				foreach ( $menus as $m ) {
					$all_menu[ $m->name ] = $m->slug;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Custom Menu', 'ciloe' ),
					'base'        => 'ciloe_custommenu', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a custom menu.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'custom-menu.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'custom_menu/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style1',
									'img' => CILOE_SHORTCODE_PREVIEW . 'custom_menu/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style2',
									'img' => CILOE_SHORTCODE_PREVIEW . 'custom_menu/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style3',
									'img' => CILOE_SHORTCODE_PREVIEW . 'custom_menu/style3.jpg',
								),
								'style4'  => array(
									'alt' => 'Style4',
									'img' => CILOE_SHORTCODE_PREVIEW . 'custom_menu/style4.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'layout',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'ciloe' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image Banner", 'ciloe' ),
							"param_name"  => "menu_banner",
							"admin_label" => true,
							'dependency'  => array(
								'element' => 'layout',
								'value'   => array( 'layout1' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Menu', 'ciloe' ),
							'param_name'  => 'menu',
							'value'       => $all_menu,
							'description' => esc_html__( 'Select menu to display.', 'ciloe' ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'ciloe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link.', 'ciloe' ),
							'dependency'  => array(
								'element' => 'layout',
								'value'   => array( 'layout1' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'custommenu_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Map New Category */
			$allowed_tags = array(
				'em'     => array(),
				'i'      => array(),
				'b'      => array(),
				'strong' => array(),
				'a'      => array(
					'href'   => array(),
					'target' => array(),
					'class'  => array(),
					'id'     => array(),
					'title'  => array(),
				),
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Category', 'ciloe' ),
					'base'        => 'ciloe_categories', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display Category.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'cat.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'categories/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style 01',
									'img' => CILOE_SHORTCODE_PREVIEW . 'categories/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style 02',
									'img' => CILOE_SHORTCODE_PREVIEW . 'categories/style2.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Photo Category", "ciloe" ),
							"param_name"  => "bg_cat",
							"admin_label" => true,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Image Size", "ciloe" ),
							"param_name"  => "image_size",
							'admin_label' => true,
							'std'         => '350x356',
							'description' => esc_html__( '{width}x{height}. Example: 152x152, etc...', 'ciloe' )
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Descriptions", "ciloe" ),
							"param_name"  => "des",
							'admin_label' => true,
							"description" => esc_html__( "Descriptions of shortcode.", "ciloe" ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style2' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Fontsize", "ciloe" ),
							"param_name"  => "fontsize",
							'admin_label' => true,
							'std'         => '22',
							"description" => esc_html__( "Fontsize for title of shortcode.", "ciloe" ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Description position', 'ciloe' ),
							'param_name' => 'des_position',
							'value'      => array(
								esc_html__( 'Left', 'ciloe' )  => 'left',
								esc_html__( 'Right', 'ciloe' ) => 'right',
							),
							'std'        => 'left',
							"dependency" => array( "element" => "style", "value" => array( 'style2' ) ),
						),
						array(
							'param_name' => 'text_type',
							'heading'    => esc_html__( 'Text Type', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Dark', 'ciloe' )  => 'dark',
								esc_html__( 'Light', 'ciloe' ) => 'light',
							),
							'sdt'        => 'dark',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'default' ),
							),
						),
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'ciloe' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => false,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'ciloe' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'ciloe' ),
							'std'         => '',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'categories_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Slider*/
			vc_map(
				array(
					'name'                    => esc_html__( 'Ciloe: Slider', 'ciloe' ),
					'base'                    => 'ciloe_slider',
					'category'                => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description'             => esc_html__( 'Display a custom slide.', 'ciloe' ),
					'as_parent'               => array( 'only' => 'vc_single_image,ciloe_singlelookbook,ciloe_categories,ciloe_banner' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => CILOE_SHORTCODES_ICONS_URI . 'slide.png',
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'slide/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Banner Style 1',
									'img' => CILOE_SHORTCODE_PREVIEW . 'slide/style1.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Owl', 'ciloe' )   => 'owl',
								esc_html__( 'Slick', 'ciloe' ) => 'slick',
								esc_html__( 'List', 'ciloe' )  => 'list',
							),
							'std'        => 'owl',
							'heading'    => esc_html__( 'Type show', 'ciloe' ),
							'param_name' => 'type_show',
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'ciloe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'ciloe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Circle', 'ciloe' ) => 'circle',
								esc_html__( 'Caret', 'ciloe' )  => 'caret',
								esc_html__( 'Angle', 'ciloe' )  => 'angle',
							),
							'std'         => 'caret',
							'heading'     => esc_html__( 'Nav Type', 'ciloe' ),
							'param_name'  => 'nav_type',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'ciloe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots buttons.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Light', 'ciloe' ) => 'light',
								esc_html__( 'Dark', 'ciloe' )  => 'dark',
							),
							'std'         => 'dark',
							'heading'     => esc_html__( 'Control Type( Type for dots,navigation)', 'ciloe' ),
							'param_name'  => 'control_type',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'ciloe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'ciloe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'ciloe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'ciloe' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'ciloe' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'ciloe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'ciloe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'ciloe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'ciloe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'type_show',
								'value'   => array( 'owl' ),
							),
						),
						array(
							'heading'     => esc_html__( 'Extra Class Name', 'ciloe' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'ciloe' ),
							'type'        => 'textfield',
							'param_name'  => 'el_class',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'slider_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Single Lookbook', 'ciloe' ),
					'base'        => 'ciloe_singlelookbook', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display Single LookBook.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'single-lookbook.png',
					'params'      => array(
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image", "ciloe" ),
							"param_name"  => "bg_lookbook",
							"admin_label" => true,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Image Size", "ciloe" ),
							"param_name"  => "image_size",
							'admin_label' => true,
							'std'         => '608x631',
							'description' => esc_html__( '{width}x{height}. Example: 152x152, etc...', 'ciloe' )
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title", "ciloe" ),
							"param_name"  => "title",
							"description" => esc_html__( "Add title for Shortcodes.", "ciloe" ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Descriptions", "ciloe" ),
							"param_name"  => "des",
							'admin_label' => true,
							"description" => esc_html__( "Descriptions of shortcode.", "ciloe" ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'ciloe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link and text button.', 'ciloe' ),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'ciloe' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'singlelookbook_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section Testimonial*/
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Testimonial', 'ciloe' ),
					'base'        => 'ciloe_testimonials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display testimonial info.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'ciloe' ),
							'value'       => array(
								'style-1' => array(
									'alt' => 'Style 1',
									'img' => CILOE_SHORTCODE_PREVIEW . 'testimonials/testimonial_style_1.jpg'
								),
							),
							'default'     => 'style-1',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "param_group",
							"heading"     => esc_html__( "Testimonial Item", "ciloe" ),
							"admin_label" => false,
							"param_name"  => "testimonial_item",
							"params"      => array(
								array(
									'param_name'  => 'avatar',
									'heading'     => esc_html__( 'Avatar', 'ciloe' ),
									'type'        => 'attach_image',
									'admin_label' => true
								),
								array(
									'type'        => 'textfield',
									'holder'      => 'div',
									'class'       => '',
									'heading'     => esc_html__( 'Image Size', 'ciloe' ),
									'param_name'  => 'img_size',
									'std'         => '105x105',
									'description' => esc_html__( '{width}x{height}. Example: 105x105, etc...', 'ciloe' )
								),
								array(
									'type'        => 'textfield',
									'heading'     => esc_html__( 'Name', 'ciloe' ),
									'param_name'  => 'name',
									'description' => esc_html__( 'The name of testimonial.', 'ciloe' ),
									'admin_label' => true,
									'std'         => '',
								),
								array(
									'type'        => 'textfield',
									'heading'     => esc_html__( 'Position', 'ciloe' ),
									'param_name'  => 'position',
									'description' => esc_html__( 'The position of testimonial.', 'ciloe' ),
									'admin_label' => true,
									'std'         => '',
								),
								array(
									'type'        => 'textarea',
									'heading'     => esc_html__( 'Description', 'ciloe' ),
									'param_name'  => 'description',
									'description' => esc_html__( 'The description of testimonial.', 'ciloe' ),
									'admin_label' => true,
									'std'         => '',
								),
							)
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'ciloe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'ciloe' )  => 'false',
								esc_html__( 'Yes', 'ciloe' ) => 'true'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'ciloe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'ciloe' ) => 'true',
								esc_html__( 'No', 'ciloe' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'ciloe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'ciloe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'ciloe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'ciloe' ),
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px )", 'ciloe' ),
							"param_name"  => "lg_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'ciloe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'ciloe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'ciloe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'ciloe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'ciloe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'testimonials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/*Section IconBox*/
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Icon Box', 'ciloe' ),
					'base'        => 'ciloe_iconbox', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display Iconbox.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'iconbox.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'ciloe' ),
							'value'       => array(
								'style'  => array(
									'alt' => 'Default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'icon_box/default.jpg'
								),
								'style1' => array(
									'alt' => 'Style 1',
									'img' => CILOE_SHORTCODE_PREVIEW . 'icon_box/style1.jpg'
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'param_name'  => 'image_icon',
							'heading'     => esc_html__( 'Image', 'ciloe' ),
							'type'        => 'attach_image',
							'admin_label' => true
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Title', 'ciloe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The Title of IconBox.', 'ciloe' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'        => 'checkbox',
							'param_name'  => 'add_icon',
							'heading'     => esc_html__( 'Has Separator', 'ciloe' ),
							'description' => esc_html__( 'Separator between of box', 'ciloe' ),
							'value'       => array(
								esc_html__( 'Separator between of box', 'ciloe' ) => 'has-icon',
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'ciloe' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'iconbox_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					
					)
				)
			);
			
			/* Map Google Map */
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Google Map', 'ciloe' ),
					'base'        => 'ciloe_googlemap', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a google map.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'gmap.png',
					'params'      => array(
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Pin", "ciloe" ),
							"param_name"  => "pin_icon",
							"admin_label" => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title", 'ciloe' ),
							"param_name"  => "title",
							'admin_label' => true,
							"description" => esc_html__( "title.", 'ciloe' ),
							'std'         => 'Tic themes',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Phone", 'ciloe' ),
							"param_name"  => "phone",
							'admin_label' => true,
							"description" => esc_html__( "phone.", 'ciloe' ),
							'std'         => '088-465 9965 02',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Email", 'ciloe' ),
							"param_name"  => "email",
							'admin_label' => true,
							"description" => esc_html__( "email.", 'ciloe' ),
							'std'         => 'famithemes@gmail.com',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Map Height", 'ciloe' ),
							"param_name"  => "map_height",
							'admin_label' => true,
							'std'         => '400',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Maps type', 'ciloe' ),
							'param_name' => 'map_type',
							'value'      => array(
								esc_html__( 'ROADMAP', 'ciloe' )   => 'ROADMAP',
								esc_html__( 'SATELLITE', 'ciloe' ) => 'SATELLITE',
								esc_html__( 'HYBRID', 'ciloe' )    => 'HYBRID',
								esc_html__( 'TERRAIN', 'ciloe' )   => 'TERRAIN',
							),
							'std'        => 'ROADMAP',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Show info content?', 'ciloe' ),
							'param_name' => 'info_content',
							'value'      => array(
								esc_html__( 'Yes', 'ciloe' ) => '1',
								esc_html__( 'No', 'ciloe' )  => '2',
							),
							'std'        => '1',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Address", 'ciloe' ),
							"param_name"  => "address",
							'admin_label' => true,
							"description" => esc_html__( "address.", 'ciloe' ),
							'std'         => 'Hoang Van Thu, TP. Thai Nguyen',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Longitude", 'ciloe' ),
							"param_name"  => "longitude",
							'admin_label' => true,
							"description" => esc_html__( "longitude.", 'ciloe' ),
							'std'         => '105.800286',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Latitude", 'ciloe' ),
							"param_name"  => "latitude",
							'admin_label' => true,
							"description" => esc_html__( "latitude.", 'ciloe' ),
							'std'         => '21.587001',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Zoom", 'ciloe' ),
							"param_name"  => "zoom",
							'admin_label' => true,
							"description" => esc_html__( "zoom.", 'ciloe' ),
							'std'         => '14',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'ciloe' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'googlemap_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Map New Social */
			$socials     = array();
			$all_socials = ciloe_get_option( 'user_all_social' );
			$i           = 1;
			if ( $all_socials ) {
				foreach ( $all_socials as $social ) {
					$socials[ $social['title_social'] ] = $i ++;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Ciloe: Socials', 'ciloe' ),
					'base'        => 'ciloe_socials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'description' => esc_html__( 'Display a social list.', 'ciloe' ),
					'icon'        => CILOE_SHORTCODES_ICONS_URI . 'socials.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'ciloe' ),
							'value'       => array(
								'default' => array(
									'alt' => 'Default',
									'img' => CILOE_SHORTCODE_PREVIEW . 'socials/default.jpg',
								),
								'style1'  => array(
									'alt' => 'Style1',
									'img' => CILOE_SHORTCODE_PREVIEW . 'socials/style1.jpg',
								),
								'style2'  => array(
									'alt' => 'Style2',
									'img' => CILOE_SHORTCODE_PREVIEW . 'socials/style2.jpg',
								),
								'style3'  => array(
									'alt' => 'Style3',
									'img' => CILOE_SHORTCODE_PREVIEW . 'socials/style3.jpg',
								),
							),
							'default'     => 'default',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Title', 'ciloe' ),
							'param_name' => 'title',
						),
						array(
							'param_name' => 'text_align',
							'heading'    => esc_html__( 'Text align', 'ciloe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Left', 'ciloe' )   => 'text-left',
								esc_html__( 'Right', 'ciloe' )  => 'text-right',
								esc_html__( 'Center', 'ciloe' ) => 'text-center',
							),
							'std'        => 'text-left',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__( 'Display on', 'ciloe' ),
							'param_name' => 'use_socials',
							'class'      => 'checkbox-display-block',
							'value'      => $socials,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "ciloe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ciloe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
						array(
							'param_name'       => 'socials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Pin Mapper */
			$all_pin_mappers      = get_posts(
				array(
					'post_type'      => 'ciloe_mapper',
					'posts_per_page' => '-1'
				)
			);
			$all_pin_mappers_args = array(
				esc_html__( ' ---- Choose a pin mapper ---- ', 'ciloe' ) => '0',
			);
			if ( ! empty( $all_pin_mappers ) ) {
				foreach ( $all_pin_mappers as $pin_mapper ) {
					$all_pin_mappers_args[ $pin_mapper->post_title ] = $pin_mapper->ID;
				}
			} else {
				$all_pin_mappers_args = array(
					esc_html__( ' ---- No pin mapper to choose ---- ', 'ciloe' ) => '0',
				);
			}
			vc_map(
				array(
					'name'     => esc_html__( 'Ciloe: Pin Mapper', 'ciloe' ),
					'base'     => 'ciloe_pinmap',
					'category' => esc_html__( 'Ciloe Elements', 'ciloe' ),
					'icon'     => CILOE_SHORTCODES_ICONS_URI . 'pinmapper.png',
					'params'   => array(
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose Pin Mapper', 'ciloe' ),
							'param_name' => 'ids',
							'value'      => $all_pin_mappers_args
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'ciloe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciloe' ),
						),
						array(
							'param_name'       => 'custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'ciloe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Show info content?', 'ciloe' ),
							'param_name' => 'show_info_content',
							'value'      => array(
								esc_html__( 'Yes', 'ciloe' ) => 'yes',
								esc_html__( 'No', 'ciloe' )  => 'no',
							),
							'std'        => 'no',
							'group'      => esc_html__( 'Info Text', 'ciloe' ),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Title', 'ciloe' ),
							'param_name' => 'title',
							'dependency' => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'      => esc_html__( 'Info Text', 'ciloe' ),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Short Description', 'ciloe' ),
							'param_name'  => 'short_desc',
							'description' => esc_html__( 'Short description display under the title', 'ciloe' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'       => esc_html__( 'Info Text', 'ciloe' ),
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Button Link', 'ciloe' ),
							'param_name' => 'btn_link',
							'dependency' => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'      => esc_html__( 'Info Text', 'ciloe' ),
						),
						array(
							'type'        => 'textfield',
							'holder'      => 'div',
							'class'       => '',
							'heading'     => esc_html__( 'Position', 'ciloe' ),
							'param_name'  => 'pos',
							'std'         => '200:800',
							'description' => esc_html__( '{top}:{left}. Example: 200:800, etc...', 'ciloe' ),
							'dependency'  => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'       => esc_html__( 'Info Text', 'ciloe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'ciloe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'ciloe' ),
						),
					
					),
				)
			);
			
		}
	}
	
	new Ciloe_Visual_Composer();
}

if ( class_exists( 'Vc_Manager' ) ) {
	function change_vc_row() {
		$args = array(
			array(
				"type"        => "checkbox",
				"group"       => "Additions",
				"holder"      => "div",
				"class"       => "custom-checkbox",
				"heading"     => esc_html__( 'Parallax effect: ', 'ciloe' ),
				"description" => esc_html__( 'Chosen for using Paralax scroll', 'ciloe' ),
				"param_name"  => "paralax_class",
				'admin_label' => true,
				"value"       => array(
					esc_html__( 'paralax-slide', 'ciloe' ) => "type_paralax",
				),
			),
			array(
				"type"        => "checkbox",
				"group"       => "Additions",
				"heading"     => esc_html__( 'Slide Class: ', 'ciloe' ),
				"description" => esc_html__( 'Chosen for using slide scroll', 'ciloe' ),
				"param_name"  => "section_class",
				'admin_label' => true,
				"value"       => array(
					esc_html__( 'section-slide', 'ciloe' ) => "section-slide",
				),
			),
		);
		foreach ( $args as $value ) {
			// vc_add_param( "vc_row", $value );
			vc_add_param( "vc_section", $value );
		}
	}
	
	change_vc_row();
	get_template_part( 'vc_templates/vc_row.php' );
	get_template_part( 'vc_templates/vc_section.php' );
}

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_Ciloe_Tabs extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Ciloe_Accordions extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Ciloe_Container extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_Ciloe_Slider extends WPBakeryShortCodesContainer {
}
