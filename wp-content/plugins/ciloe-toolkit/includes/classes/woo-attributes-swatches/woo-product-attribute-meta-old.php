<?php
if ( ! class_exists( 'Ciloe_Attribute_Product_Meta' ) ) {
	class Ciloe_Attribute_Product_Meta {
		public $screen;
		public $taxonomy;
		public $meta_key;
		public $image_size   = 'shop_thumb';
		public $image_width  = 32;
		public $image_height = 32;
		
		/**
		 * Constructor.
		 *
		 * Sets up a new Product Attribute image type
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param string $attribute_image_key a meta key to store the custom image for
		 * @param string $image_size          a registered image size to use for this product attribute image
		 *
		 * @return Ciloe_Attribute_Product_Meta
		 */
		
		public function __construct( $attribute_image_key = 'attribute_swatch', $image_size = 'shop_thumb' ) {
			$this->meta_key   = $attribute_image_key;
			$this->image_size = $image_size;
			
			if ( is_admin() ) {
				add_action( 'admin_enqueue_scripts', array( &$this, 'on_admin_scripts' ) );
				add_action( 'current_screen', array( &$this, 'init_attribute_image_selector' ) );
				add_action( 'created_term', array( &$this, 'woocommerce_attribute_thumbnail_field_save' ), 10, 3 );
				add_action( 'edit_term', array( &$this, 'woocommerce_attribute_thumbnail_field_save' ), 10, 3 );
				add_action( 'woocommerce_product_option_terms', array(
					&$this,
					'fami_woocommerce_product_option_terms'
				), 10, 3 );
				
			}
			add_filter( 'product_attributes_type_selector', array( $this, 'product_attributes_type_selector' ) );
			add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array(
				$this,
				'wc_variation_attribute_options'
			), 99, 2 );
			add_action( 'woocommerce_before_shop_loop_item_title', array(
				$this,
				'wc_loop_variation_attribute_options'
			) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		}
		
		public function scripts() {
//			if ( is_product() ) {
//				wp_enqueue_script( 'woo-attributes-swatches', CILOE_TOOLKIT_URL . 'includes/classes/woo-attributes-swatches/woo-attribute.js', array( 'jquery' ), '1.0', true );
//			}
			
		}
		
		public function product_attributes_type_selector( $types ) {
			$famishop_types = array(
				'box_style' => esc_html__( 'Box Style', 'ciloe' ),
			);
			
			return array_merge( $types, $famishop_types );
		}
		
		public function fami_woocommerce_product_option_terms( $attribute_taxonomy, $i ) {
			global $post, $thepostid;
			$taxonomy = 'pa_' . $attribute_taxonomy->attribute_name;
			if ( ! $thepostid ) {
				$thepostid = $post->ID;
			}
			?>
			<?php if ( 'box_style' === $attribute_taxonomy->attribute_type || 'list' === $attribute_taxonomy->attribute_type ) : ?>
                <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'ciloe' ); ?>"
                        class="multiselect attribute_values wc-enhanced-select"
                        name="attribute_values[<?php echo $i; ?>][]">
					<?php
					$args      = array(
						'orderby'    => 'name',
						'hide_empty' => 0,
					);
					$all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
					if ( $all_terms ) {
						foreach ( $all_terms as $term ) {
							echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $thepostid ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
						}
					}
					?>
                </select>
                <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'ciloe' ); ?></button>
                <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'ciloe' ); ?></button>
                <button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'ciloe' ); ?></button>
			<?php endif; ?>
			<?php
		}
		
		//Enqueue the scripts if on a product attribute page
		public function on_admin_scripts() {
			global $woocommerce_swatches;
			$screen = get_current_screen();
			if ( strpos( $screen->id, 'pa_' ) !== false ) :
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_style( 'wp-color-picker' );
				if ( function_exists( 'wp_enqueue_media' ) ) {
					wp_enqueue_media();
				}
			endif;
		}
		
		//Initalize the actions for all product attribute taxonomoies
		public function init_attribute_image_selector() {
			global $woocommerce, $_wp_additional_image_sizes, $koolshop_toolkit;
			
			$screen = get_current_screen();
			if ( strpos( $screen->id, 'pa_' ) !== false ) :
				$this->taxonomy = $_REQUEST['taxonomy'];
				if ( taxonomy_exists( $_REQUEST['taxonomy'] ) ) {
					$term_id = term_exists( isset( $_REQUEST['tag_ID'] ) ? $_REQUEST['tag_ID'] : 0, $_REQUEST['taxonomy'] );
					$term    = 0;
					if ( $term_id ) {
						$term = get_term( $term_id, $_REQUEST['taxonomy'] );
					}
					$this->image_size = apply_filters( 'woocommerce_get_swatches_image_size', $this->image_size, $_REQUEST['taxonomy'], $term_id );
				}
				$the_size = isset( $_wp_additional_image_sizes[ $this->image_size ] ) ? $_wp_additional_image_sizes[ $this->image_size ] : '';
				
				if ( isset( $the_size['width'] ) && isset( $the_size['height'] ) ) {
					$this->image_width  = $the_size['width'];
					$this->image_height = $the_size['height'];
				} else {
					$this->image_width  = 32;
					$this->image_height = 32;
				}
				$attribute_taxonomies = $this->wc_get_attribute_taxonomies();
				if ( $attribute_taxonomies ) {
					foreach ( $attribute_taxonomies as $tax ) {
						if ( $tax->attribute_type == 'box_style' ) {
							add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array(
								&$this,
								'woocommerce_add_attribute_thumbnail_field'
							) );
							add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array(
								&$this,
								'woocommerce_edit_attributre_thumbnail_field'
							), 10, 2 );
							add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array(
								&$this,
								'woocommerce_product_attribute_columns'
							) );
							add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array(
								&$this,
								'woocommerce_product_attribute_column'
							), 10, 3 );
						}
					}
				}
			endif;
		}
		
		//The field used when adding a new term to an attribute taxonomy
		public function woocommerce_add_attribute_thumbnail_field() {
			global $woocommerce;
			?>
            <div class="form-field ">
                <label for="product_attribute_type_<?php echo $this->meta_key; ?>"><?php esc_html_e( 'Type', 'ciloe' ) ?></label>
                <select name="product_attribute_meta[<?php echo $this->meta_key; ?>][type]"
                        id="product_attribute_type_<?php echo $this->meta_key; ?>" class="postform">
                    <option value="-1"><?php esc_html_e( 'None', 'ciloe' ) ?></option>
                    <option value="color"><?php esc_html_e( 'Color', 'ciloe' ) ?></option>
                    <option value="photo"><?php esc_html_e( 'Photo', 'ciloe' ) ?></option>
                    <option value="label"><?php esc_html_e( 'Label', 'ciloe' ) ?></option>
                </select>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $('#product_attribute_type_<?php echo $this->meta_key; ?>').change(function () {
                            $('.field-active').hide().removeClass('field-active');
                            $('.field-' + $(this).val()).slideDown().addClass('field-active');
                        });
                        $('.woo-color').wpColorPicker();
                    });
                </script>
            </div>
            <div class="form-field swatch-field field-color section-color-swatch"
                 style="overflow:visible;display:none;">
                <div id="swatch-color" class="<?php echo sanitize_title( $this->meta_key ); ?>-color">
                    <label><?php esc_html_e( 'Color', 'ciloe' ); ?></label>
                    <div id="product_attribute_color_<?php echo $this->meta_key; ?>_picker" class="colorSelector">
                        <div></div>
                    </div>
                    <input class="woo-color"
                           id="product_attribute_color_<?php echo $this->meta_key; ?>"
                           type="text" class="text"
                           name="product_attribute_meta[<?php echo $this->meta_key; ?>][color]"
                           value="#000000"/>

                </div>
            </div>
            <div class="form-field swatch-field field-photo" style="overflow:visible;display:none;">
                <div id="swatch-photo" class="<?php echo sanitize_title( $this->meta_key ); ?>-photo">
                    <label><?php esc_html_e( 'Thumbnail', 'ciloe' ); ?></label>
                    <div id="product_attribute_thumbnail_<?php echo $this->meta_key; ?>"
                         style="float:left;margin-right:10px;">
                        <img src="<?php echo $woocommerce->plugin_url() . '/assets/images/placeholder.png' ?>"
                             width="<?php echo $this->image_width; ?>px" height="<?php echo $this->image_height; ?>px"/>
                    </div>
                    <div style="line-height:60px;">
                        <input type="hidden" id="product_attribute_<?php echo $this->meta_key; ?>"
                               name="product_attribute_meta[<?php echo $this->meta_key; ?>][photo]"/>
                        <button type="submit"
                                class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'ciloe' ); ?></button>
                        <button type="submit"
                                class="remove_image_button button"><?php esc_html_e( 'Remove image', 'ciloe' ); ?></button>
                    </div>
                    <script type="text/javascript">
                        window.send_to_termmeta = function (html) {
                            jQuery('body').append('<div id="temp_image">' + html + '</div>');
                            var img = jQuery('#temp_image').find('img');
                            imgurl = img.attr('src');
                            imgclass = img.attr('class');
                            imgid = parseInt(imgclass.replace(/\D/g, ''), 10);
                            jQuery('#product_attribute_<?php echo $this->meta_key; ?>').val(imgid);
                            jQuery('#product_attribute_thumbnail_<?php echo $this->meta_key; ?> img').attr('src', imgurl);
                            jQuery('#temp_image').remove();
                            tb_remove();
                        }
                        jQuery('.upload_image_button').live('click', function () {
                            var post_id = 0;
                            window.send_to_editor = window.send_to_termmeta;
                            tb_show('', 'media-upload.php?post_id=' + post_id + '&amp;type=image&amp;TB_iframe=true');
                            return false;
                        });
                        jQuery('.remove_image_button').live('click', function () {
                            jQuery('#product_attribute_thumbnail_<?php echo $this->meta_key; ?> img').attr('src', '<?php echo $woocommerce->plugin_url() . '/assets/images/placeholder.png'; ?>');
                            jQuery('#product_attribute_<?php echo $this->meta_key; ?>').val('');
                            return false;
                        });
                    </script>
                    <div class="clear"></div>
                </div>
            </div>
			<?php
		}
		
		//The field used when editing an existing proeuct attribute taxonomy term
		public function woocommerce_edit_attributre_thumbnail_field( $term, $taxonomy ) {
			global $woocommerce;
			$swatch_term = new Ciloe_Term( $this->meta_key, $term->term_id, $taxonomy, false, $this->image_size );
			$image       = '';
			?>
            <tr class="form-field ">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Type', 'ciloe' ); ?></label></th>
                <td>
                    <select name="product_attribute_meta[<?php echo $this->meta_key; ?>][type]"
                            id="product_attribute_swatchtype_<?php echo $this->meta_key; ?>" class="postform">
                        <option <?php selected( 'none', $swatch_term->get_type() ); ?>
                                value="-1"><?php esc_html_e( 'None', 'ciloe' ); ?></option>
                        <option <?php selected( 'color', $swatch_term->get_type() ); ?>
                                value="color"><?php esc_html_e( 'Color', 'ciloe' ); ?></option>
                        <option <?php selected( 'photo', $swatch_term->get_type() ); ?>
                                value="photo"><?php esc_html_e( 'Photo', 'ciloe' ); ?></option>
                        <option <?php selected( 'label', $swatch_term->get_type() ); ?>
                                value="label"><?php esc_html_e( 'Label', 'ciloe' ); ?></option>
                    </select>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            $('#product_attribute_swatchtype_<?php echo $this->meta_key; ?>').change(function () {
                                $('.swatch-field-active').hide().removeClass('swatch-field-active');
                                $('.swatch-field-' + $(this).val()).show().addClass('swatch-field-active');
                            });
                            $('.woo-color').wpColorPicker();
                        });
                    </script>
                </td>
            </tr>
			<?php $style = $swatch_term->get_type() != 'color' ? 'display:none;' : ''; ?>
            <tr class="form-field swatch-field swatch-field-color section-color-swatch"
                style="overflow:visible;<?php echo $style; ?>">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Color', 'ciloe' ); ?></label></th>
                <td>
                    <div id="swatch-color" class="<?php echo sanitize_title( $this->meta_key ); ?>-color">
                        <div id="product_attribute_color_<?php echo $this->meta_key; ?>_picker" class="colorSelector">
                            <div></div>
                        </div>
                        <input class="woo-color"
                               id="product_attribute_color_<?php echo $this->meta_key; ?>"
                               type="text" class="text"
                               name="product_attribute_meta[<?php echo $this->meta_key; ?>][color]"
                               value="<?php echo $swatch_term->get_color(); ?>"/>
                    </div>
                </td>
            </tr>
			<?php $style = $swatch_term->get_type() != 'photo' ? 'display:none;' : ''; ?>
            <tr class="form-field swatch-field swatch-field-photo" style="overflow:visible;<?php echo $style; ?>">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Photo', 'ciloe' ); ?></label></th>
                <td>
                    <div id="product_attribute_thumbnail_<?php echo $this->meta_key; ?>"
                         style="float:left;margin-right:10px;">
                        <img src="<?php echo $swatch_term->get_image_src(); ?>"
                             width="<?php echo $swatch_term->get_width(); ?>px"
                             height="<?php echo $swatch_term->get_height(); ?>px"/>
                    </div>
                    <div style="line-height:60px;">
                        <input type="hidden" id="product_attribute_<?php echo $this->meta_key; ?>"
                               name="product_attribute_meta[<?php echo $this->meta_key; ?>][photo]"
                               value="<?php echo $swatch_term->get_image_id(); ?>"/>
                        <button type="submit"
                                class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'ciloe' ); ?></button>
                        <button type="submit"
                                class="remove_image_button button"><?php esc_html_e( 'Remove image', 'ciloe' ); ?></button>
                    </div>
                    <script type="text/javascript">
                        window.send_to_termmeta = function (html) {
                            jQuery('body').append('<div id="temp_image">' + html + '</div>');
                            var img = jQuery('#temp_image').find('img');
                            imgurl = img.attr('src');
                            imgclass = img.attr('class');
                            imgid = parseInt(imgclass.replace(/\D/g, ''), 10);
                            jQuery('#product_attribute_<?php echo $this->meta_key; ?>').val(imgid);
                            jQuery('#product_attribute_thumbnail_<?php echo $this->meta_key; ?> img').attr('src', imgurl);
                            jQuery('#temp_image').remove();
                            tb_remove();
                        }
                        jQuery('.upload_image_button').live('click', function () {
                            var post_id = 0;
                            window.send_to_editor = window.send_to_termmeta;
                            tb_show('', 'media-upload.php?post_id=' + post_id + '&amp;type=image&amp;TB_iframe=true');
                            return false;
                        });
                        jQuery('.remove_image_button').live('click', function () {
                            jQuery('#product_attribute_thumbnail_<?php echo $this->meta_key; ?> img').attr('src', '<?php echo $woocommerce->plugin_url() . '/assets/images/placeholder.png'; ?>');
                            jQuery('#product_attribute_<?php echo $this->meta_key; ?>').val('');
                            return false;
                        });
                    </script>
                    <div class="clear"></div>
                </td>
            </tr>
			<?php
		}
		
		//Saves the product attribute taxonomy term data
		public function woocommerce_attribute_thumbnail_field_save( $term_id, $tt_id, $taxonomy ) {
			
			if ( isset( $_POST['product_attribute_meta'] ) ) {
				$metas = $_POST['product_attribute_meta'];
				
				if ( isset( $metas[ $this->meta_key ] ) ) {
					$data  = $metas[ $this->meta_key ];
					$photo = isset( $data['photo'] ) ? $data['photo'] : '';
					$color = isset( $data['color'] ) ? $data['color'] : '';
					$type  = isset( $data['type'] ) ? $data['type'] : '';
					update_term_meta( $term_id, $taxonomy . '_' . $this->meta_key . '_type', $type );
					update_term_meta( $term_id, $taxonomy . '_' . $this->meta_key . '_photo', $photo );
					update_term_meta( $term_id, $taxonomy . '_' . $this->meta_key . '_color', $color );
				}
			}
		}
		
		//Registers a column for this attribute taxonomy for this image
		public function woocommerce_product_attribute_columns( $columns ) {
			$new_columns                    = array();
			$new_columns['cb']              = $columns['cb'];
			$new_columns[ $this->meta_key ] = esc_html__( 'Thumbnail', 'ciloe' );
			unset( $columns['cb'] );
			$columns = array_merge( $new_columns, $columns );
			
			return $columns;
		}
		
		//Renders the custom column as defined in woocommerce_product_attribute_columns
		public function woocommerce_product_attribute_column( $columns, $column, $id ) {
			if ( $column == $this->meta_key ) :
				$swatch_term = new Ciloe_Term( $this->meta_key, $id, $this->taxonomy, false, $this->image_size );
				$columns     .= $swatch_term->get_output();
			endif;
			
			return $columns;
		}
		
		/**
		 * Get attribute
		 *
		 * @return array
		 */
		public function wc_get_attribute_taxonomies() {
			global $woocommerce;
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				return wc_get_attribute_taxonomies();
			} else {
				return $woocommerce->get_attribute_taxonomies();
			}
		}
		/**
		 * Override WooCommerce function famishop_variation_attribute_options (Locate in wc-template-functions.php)
		 * Output a list of variation attributes for use in the cart forms.
		 *
		 * @param array $args
		 *
		 * @since  1.0
		 * @author Gordon Freeman
		 */
		/**
		 * Output a list of variation attributes for use in the cart forms.
		 *
		 * @param array $args
		 *
		 * @since 2.4.0
		 */
		public function wc_variation_attribute_options( $html = '', $args = '' ) {
			$attribute_swatch_width  = 40;
			$attribute_swatch_height = 40;
			$attribute_swatch_width  = apply_filters( 'attribute_swatch_width', $attribute_swatch_width );
			$attribute_swatch_height = apply_filters( 'attribute_swatch_height', $attribute_swatch_height );
			$args                    = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
				                                                                                                                         'options'          => false,
				                                                                                                                         'attribute'        => false,
				                                                                                                                         'product'          => false,
				                                                                                                                         'selected'         => false,
				                                                                                                                         'name'             => '',
				                                                                                                                         'id'               => '',
				                                                                                                                         'class'            => '',
				                                                                                                                         'show_option_none' => __( 'Choose an option', 'ciloe' ),
			                                                                                                                         )
			);
			
			$options               = $args['options'];
			$product               = $args['product'];
			$attribute             = $args['attribute'];
			$name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
			$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
			$class                 = $args['class'];
			$show_option_none      = $args['show_option_none'] ? true : false;
			$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'ciloe' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.
			
			if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			}
			
			if ( ! empty( $options ) ) {
				if ( $product && taxonomy_exists( $attribute ) ) {
					$attribute_taxonomy = $this->get_product_attribute( $attribute );
					$html               = '<select data-attributetype="' . $attribute_taxonomy['type'] . '" data-id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
					$html               .= '<option data-type="" data-' . esc_attr( $id ) . '="" value="">' . esc_html( $show_option_none_text ) . '</option>';
					// Get terms if this is a taxonomy - ordered. We need the names too.
					$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options ) ) {
							// For color attribute
							
							$data_type  = get_term_meta( $term->term_id, $term->taxonomy . '_attribute_swatch_type', true );
							$data_color = get_term_meta( $term->term_id, $term->taxonomy . '_attribute_swatch_color', true );
							$data_photo = get_term_meta( $term->term_id, $term->taxonomy . '_attribute_swatch_photo', true );
							if ( function_exists( 'ciloe_toolkit_resize_image' ) ) {
								$image_thumb = ciloe_toolkit_resize_image( $data_photo, null, $attribute_swatch_width, $attribute_swatch_height, true, true, false );
								$photo_url   = $image_thumb['url'];
							} else {
								$photo_url = wp_get_attachment_url( $data_photo );
							}
							
							if ( $data_type == 'color' ) {
								$html .= '<option data-width="' . $attribute_swatch_width . '" data-height="' . $attribute_swatch_height . '" data-type="' . esc_attr( $data_type ) . '" data-' . esc_attr( $id ) . '="' . esc_attr( $data_color ) . '" data-name="' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
							} elseif ( $data_type == 'photo' ) {
								$html .= '<option data-width="' . $attribute_swatch_width . '" data-height="' . $attribute_swatch_height . '" data-type="' . esc_attr( $data_type ) . '" data-' . esc_attr( $id ) . '=" url(' . esc_url( $photo_url ) . ') " data-name="' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
							} elseif ( $data_type == 'label' ) {
								$html .= '<option data-width="' . $attribute_swatch_width . '" data-height="' . $attribute_swatch_height . '" data-type="' . esc_attr( $data_type ) . '" data-' . esc_attr( $id ) . '="" data-name="' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '" value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
							} else {
								$html .= '<option data-type="' . esc_attr( $data_type ) . '" data-' . esc_attr( $id ) . '="' . esc_attr( $term->slug ) . '" data-name="' . esc_attr( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '"  value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
							}
						}
					}
				} else {
					return $html;
				}
				$html .= '</select>';
				$html .= '<div class="data-val attribute-' . esc_attr( $id ) . '" data-attributetype="' . $attribute_taxonomy['type'] . '"></div>';
			}
			
			return $html;
		}
		
		public function wc_loop_variation_attribute_options() {
			global $product;
			$products_loop_attributes_display = ciloe_get_option( 'products_loop_attributes_display', array() );
			if ( empty( $products_loop_attributes_display ) ) {
				return;
			}
			
			if ( $product->get_type() == 'variable' ) {
				$attribute_array      = array();
				$attributes           = $product->get_variation_attributes();
				$attribute_keys       = array_keys( $attributes );
				$get_variations       = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
				$available_variations = $get_variations ? $product->get_available_variations() : false;
				
				// GET SIZE IMAGE SETTING
				$width  = 590;
				$height = 590;
				$size   = wc_get_image_size( 'shop_catalog' );
				if ( $size ) {
					$width  = $size['width'];
					$height = $size['height'];
				}				
				foreach ( $available_variations as $available_variation ) {
					$image_variable                            = ciloe_toolkit_resize_image( $available_variation['image_id'], null, $width, $height, true, true, false );
					$available_variation['image']['src']       = $image_variable['url'];
					$available_variation['image']['url']       = $image_variable['url'];
					$available_variation['image']['full_src']  = $image_variable['url'];
					$available_variation['image']['thumb_src'] = $image_variable['url'];
					$available_variation['image']['src_w']     = $width;
					$available_variation['image']['src_h']     = $height;
					$attribute_array[]                         = $available_variation;
				}
				if ( ! empty( $attributes ) ) {
					?>
                    <form class="variations_form cart" method="post" enctype='multipart/form-data'
                          data-product_id="<?php echo absint( $product->get_id() ); ?>"
                          data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $attribute_array ) ) ?>">
                        <table class="variations">
                            <tbody>
							<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                                <tr style="display: <?php echo ( ! in_array( $attribute_name, $products_loop_attributes_display ) ) ? 'none;' : 'table-row;'; ?>">
                                    <td class="value">
										<?php
										$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
										wc_dropdown_variation_attribute_options( array(
											                                         'options'   => $options,
											                                         'attribute' => $attribute_name,
											                                         'product'   => $product,
											                                         'selected'  => $selected
										                                         ) );
										echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'antive' ) . '</a>' ) : '';
										?>
                                    </td>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
					<?php
				};
			}
		}
		
		public function get_product_attribute( $attribute ) {
			global $wpdb;
			$attribute_name = str_replace( 'pa_', '', $attribute );
			try {
				
				$attribute = $wpdb->get_row( $wpdb->prepare( "
				SELECT *
				FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
				WHERE attribute_name = %s
			    ", $attribute_name
				)
				);
				
				if ( is_wp_error( $attribute ) || is_null( $attribute ) ) {
					throw new WC_API_Exception( 'woocommerce_api_invalid_product_attribute_id', __( 'A product attribute with the provided ID could not be found', 'ciloe' ), 404 );
				}
				
				$product_attribute = array(
					'id'           => intval( $attribute->attribute_id ),
					'name'         => $attribute->attribute_label,
					'slug'         => wc_attribute_taxonomy_name( $attribute->attribute_name ),
					'type'         => $attribute->attribute_type,
					'order_by'     => $attribute->attribute_orderby,
					'has_archives' => (bool) $attribute->attribute_public,
				);
				
				return $product_attribute;
			}
			catch ( WC_API_Exception $e ) {
				return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
			}
		}
	}
}
new Ciloe_Attribute_Product_Meta();