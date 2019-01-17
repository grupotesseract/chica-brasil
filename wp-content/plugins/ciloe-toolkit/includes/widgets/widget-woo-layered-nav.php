<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Layered Navigation Widget.
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.6.0
 * @extends  WC_Widget
 */
class Smarket_Layered_Nav_Widget extends WC_Widget {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'smarket_widget_layered_nav widget_layered_nav';
		$this->widget_description = esc_html__( 'Shows a custom attribute in a widget which lets you narrow down the list of products when viewing product categories.', 'smarket' );
		$this->widget_id          = 'smarket_woocommerce_layered_nav';
		$this->widget_name        = esc_html__( '1 - Smarket: WooCommerce Layered Nav', 'smarket' );
		parent::__construct();
	}
	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget->update
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$this->init_settings();
		return parent::update( $new_instance, $old_instance );
	}
	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget->form
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {
		$defaults = array( 'title' => esc_html__('Filter by', 'smarket'), 'attribute' => '', 'display_type' => 'list','query_type'=>'AND');
		$instance = wp_parse_args( (array) $instance, $defaults );
		$attribute_array      = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$attribute_default = $instance['attribute'];
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if (taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					if(!$attribute_default) $attribute_default = $tax->attribute_name;
					$attribute_array[ $tax->attribute_name ] = array(
						'id'        =>  $tax->attribute_id,
						'name'      =>  $tax->attribute_name,
						'label'     =>  $tax->attribute_label,
						'type'      =>  $tax->attribute_type,
						'orderby'   =>  $tax->attribute_orderby,
					);
				}
			}
		}
		?>
        <div class="smarket_layered_container">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e('Title:', 'smarket'); ?></label>
                <input  type="text" class="widefat maxstoreplus_layered_title" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'attribute' ); ?>"><?php esc_html_e('Product attribute:', 'smarket'); ?></label>
                <select class="widefat smarket_layered_attribute" id="<?php echo $this->get_field_id( 'attribute' );?>" name="<?php echo $this->get_field_name( 'attribute' ); ?>"  >
					<?php foreach ($attribute_array as $attribute): ?>
                        <option value="<?php echo esc_attr($attribute['name']) ?>" data-type="<?php echo esc_attr($attribute['type']); ?>"><?php echo esc_html($attribute['label']) ?></option>
					<?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php esc_html_e('Display type:', 'smarket'); ?></label>
                <select class="widefat smarket_layered_display_type" id="<?php echo $this->get_field_id( 'display_type' );?>" name="<?php echo $this->get_field_name( 'display_type' ); ?>"  >
                    <option value="list" <?php echo esc_attr($instance['display_type'] == 'list' ? 'selected' : ''); ?>><?php esc_html_e('List', 'smarket') ?></option>
                    <option value="dropdown" <?php echo esc_attr($instance['display_type'] == 'dropdown' ? 'selected' : ''); ?>><?php esc_html_e('Dropdown', 'smarket') ?></option>
                    <option value="inline" <?php echo esc_attr($instance['display_type'] == 'inline' ? 'selected' : ''); ?>><?php esc_html_e('Inline', 'smarket') ?></option>
                    <option value="color" <?php echo esc_attr($instance['display_type'] == 'color' ? 'selected' : ''); ?> <?php echo esc_attr($attribute_array[$attribute_default]['type'] != 'color' ? 'disabled' : '') ?> ><?php esc_html_e('Color', 'smarket') ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'query_type' ); ?>"><?php esc_html_e('Query type', 'smarket'); ?></label>
                <select class="widefat smarket_layered_query_type" id="<?php echo $this->get_field_id( 'query_type' );?>" name="<?php echo $this->get_field_name( 'query_type' ); ?>"  >
                    <option value="AND" <?php echo esc_attr($instance['display_type'] == 'AND' ? 'selected' : '');  ?>><?php esc_html_e('AND', 'smarket') ?></option>
                    <option value="OR" <?php echo esc_attr($instance['display_type'] == 'OR' ? 'selected' : '');; ?>><?php esc_html_e('OR', 'smarket') ?></option>
                </select>
            </p>
        </div>
        <script type="text/javascript">
            jQuery('.smarket_layered_attribute').on('change', function() {
                var type =  jQuery(this).find(':selected').data('type'), p = jQuery(this).closest('.smarket_layered_container');
                if(type == 'color'){
                    p.find('.smarket_layered_display_type option[value="color"]').removeAttr('disabled');
                }else{
                    p.find('.smarket_layered_display_type option[value="color"]').attr('disabled', 'disabled');
                    if(p.find('.smarket_layered_display_type option[value="color"]').is(':selected')){
                        p.find('.smarket_layered_display_type option[value="list"]').prop('selected', true);
                    }
                }
            });
        </script>
		<?php
		/*$this->init_settings();
		parent::form( $instance );*/
	}
	/**
	 * Init settings after post types are registered.
	 */
	public function init_settings() {
		$attribute_array      = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				print_r($tax);
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
		}
		$this->settings = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Filter by', 'smarket' ),
				'label' => esc_html__( 'Title', 'smarket' )
			),
			'attribute' => array(
				'type'    => 'select',
				'std'     => '',
				'label'   => esc_html__( 'Attribute', 'smarket' ),
				'options' => $attribute_array
			),
			'display_type' => array(
				'type'    => 'select',
				'std'     => 'list',
				'label'   => esc_html__( 'Display type', 'smarket' ),
				'options' => array(
					'list'     => esc_html__( 'List', 'smarket' ),
					'dropdown' => esc_html__( 'Dropdown', 'smarket' )
				)
			),
			'query_type' => array(
				'type'    => 'select',
				'std'     => 'and',
				'label'   => esc_html__( 'Query type', 'smarket' ),
				'options' => array(
					'and' => esc_html__( 'AND', 'smarket' ),
					'or'  => esc_html__( 'OR', 'smarket' )
				)
			),
		);
	}
	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
		$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];
		$display_type       = isset( $instance['display_type'] ) ? $instance['display_type'] : $this->settings['display_type']['std'];
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}
		$get_terms_args = array( 'hide_empty' => '1' );
		$orderby = wc_attribute_orderby( $taxonomy );
		switch ( $orderby ) {
			case 'name' :
				$get_terms_args['orderby']    = 'name';
				$get_terms_args['menu_order'] = false;
				break;
			case 'id' :
				$get_terms_args['orderby']    = 'id';
				$get_terms_args['order']      = 'ASC';
				$get_terms_args['menu_order'] = false;
				break;
			case 'menu_order' :
				$get_terms_args['menu_order'] = 'ASC';
				break;
		}
		$terms = get_terms( $taxonomy, $get_terms_args );
		if ( 0 === sizeof( $terms ) ) {
			return;
		}
		switch ( $orderby ) {
			case 'name_num' :
				usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
				break;
			case 'parent' :
				usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
				break;
		}
		ob_start();
		$this->widget_start( $args, $instance );
		if ( 'dropdown' === $display_type ) {
			$found = $this->layered_nav_dropdown($terms, $taxonomy, $query_type);
		}elseif('color' === $display_type) {
			$found = $this->layered_nav_color($terms, $taxonomy, $query_type);
		}elseif('inline' === $display_type){
			$found = $this->layered_nav_inline( $terms, $taxonomy, $query_type );
		} else {
			$found = $this->layered_nav_list( $terms, $taxonomy, $query_type );
		}
		$this->widget_end( $args );
		// Force found when option is selected - do not force found on taxonomy attributes
		if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
			$found = true;
		}
		if ( ! $found ) {
			ob_end_clean();
		} else {
			echo ob_get_clean();
		}
	}
	/**
	 * Return the currently viewed taxonomy name.
	 * @return string
	 */
	protected function get_current_taxonomy() {
		return is_tax() ? get_queried_object()->taxonomy : '';
	}
	/**
	 * Return the currently viewed term ID.
	 * @return int
	 */
	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}
	/**
	 * Return the currently viewed term slug.
	 * @return int
	 */
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}
	/**
	 * Show dropdown layered nav.
	 * @param  array $terms
	 * @param  string $taxonomy
	 * @param  string $query_type
	 * @return bool Will nav display?
	 */
	protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
		$found = false;
		if ( $taxonomy !== $this->get_current_taxonomy() ) {
			$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy_filter_name = str_replace( 'pa_', '', $taxonomy );
			echo '<select class="dropdown_layered_nav_' . esc_attr( $taxonomy_filter_name ) . '">';
			echo '<option value="">' . sprintf( esc_html__( 'Any %s', 'smarket' ), wc_attribute_label( $taxonomy ) ) . '</option>';
			foreach ( $terms as $term ) {
				// If on a term page, skip that term in widget list
				if ( $term->term_id === $this->get_current_term_id() ) {
					continue;
				}
				// Get count based on current view
				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
					continue;
				}
				echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $option_is_set, true, false ) . '>' . esc_html( $term->name ) . '</option>';
			}
			echo '</select>';
			wc_enqueue_js( "
				jQuery( '.dropdown_layered_nav_". esc_js( $taxonomy_filter_name ) . "' ).change( function() {
					var slug = jQuery( this ).val();
					location.href = '" . preg_replace( '%\/page\/[0-9]+%', '', str_replace( array( '&amp;', '%2C' ), array( '&', ',' ), esc_js( add_query_arg( 'filtering', '1', remove_query_arg( array( 'page', 'filter_' . $taxonomy_filter_name ) ) ) ) ) ) . "&filter_". esc_js( $taxonomy_filter_name ) . "=' + slug;
				});
			" );
		}
		return $found;
	}
	/**
	 * Get current page URL for layered nav items.
	 * @return string
	 */
	protected function get_page_base_url( $taxonomy ) {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$link = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		}
		// Min/Max
		if ( isset( $_GET['min_price'] ) ) {
			$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
		}
		if ( isset( $_GET['max_price'] ) ) {
			$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
		}
		// Orderby
		if ( isset( $_GET['orderby'] ) ) {
			$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
		}
		/**
		 * Search Arg.
		 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
		 */
		if ( get_search_query() ) {
			$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
		}
		// Post Type Arg
		if ( isset( $_GET['post_type'] ) ) {
			$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
		}
		// Min Rating Arg
		if ( isset( $_GET['min_rating'] ) ) {
			$link = add_query_arg( 'min_rating', wc_clean( $_GET['min_rating'] ), $link );
		}
		// All current filters
		if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
			foreach ( $_chosen_attributes as $name => $data ) {
				if ( $name === $taxonomy ) {
					continue;
				}
				$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
				if ( ! empty( $data['terms'] ) ) {
					$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
				}
				if ( 'or' == $data['query_type'] ) {
					$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
				}
			}
		}
		return $link;
	}
	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 * @param  array $term_ids
	 * @param  string $taxonomy
	 * @param  string $query_type
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		global $wpdb;
		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();
		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}
		$meta_query      = new WP_Meta_Query( $meta_query );
		$tax_query       = new WP_Tax_Query( $tax_query );
		$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
		// Generate query
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];
		$query['where']   = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";
		$query['group_by'] = "GROUP BY terms.term_id";
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );
		$results           = $wpdb->get_results( $query );
		return wp_list_pluck( $results, 'term_count', 'term_count_id' );
	}
	/**
	 * Show list based layered nav.
	 * @param  array $terms
	 * @param  string $taxonomy
	 * @param  string $query_type
	 * @return bool Will nav display?
	 */
	protected function layered_nav_list( $terms, $taxonomy, $query_type ) {
		// List display
		echo '<ul class="">';
		$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$found              = false;
		foreach ( $terms as $term ) {
			$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
			$option_is_set     = in_array( $term->slug, $current_values );
			$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
			// skip the term for the current archive
			if ( $this->get_current_term_id() === $term->term_id ) {
				continue;
			}
			// Only show options with count > 0
			if ( 0 < $count ) {
				$found = true;
			} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
				continue;
			}
			$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
			$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
			$current_filter = array_map( 'sanitize_title', $current_filter );
			if ( ! in_array( $term->slug, $current_filter ) ) {
				$current_filter[] = $term->slug;
			}
			$link = $this->get_page_base_url( $taxonomy );
			// Add current filters to URL.
			foreach ( $current_filter as $key => $value ) {
				// Exclude query arg for current term archive term
				if ( $value === $this->get_current_term_slug() ) {
					unset( $current_filter[ $key ] );
				}
				// Exclude self so filter can be unset on click.
				if ( $option_is_set && $value === $term->slug ) {
					unset( $current_filter[ $key ] );
				}
			}
			if ( ! empty( $current_filter ) ) {
				$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );
				// Add Query type Arg to URL
				if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
					$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
				}
			}
			echo '<li class="wc-layered-nav-term ' . ( $option_is_set ? 'chosen' : '' ) . '">';
			echo ( $count > 0 || $option_is_set ) ? '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">' : '<span>';
			echo esc_html( $term->name );
			echo ( $count > 0 || $option_is_set ) ? '</a> ' : '</span> ';
			echo apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );
			echo '</li>';
		}
		echo '</ul>';
		return $found;
	}
	protected function layered_nav_color( $terms, $taxonomy, $query_type ) {
		global $smarket_toolkit, $woocommerce;
		// List display
		?>
        <div class="color-group">
			<?php
			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;
			foreach ( $terms as $term ) {
				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}
				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
					continue;
				}
				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );
				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}
				$link = $this->get_page_base_url( $taxonomy );
				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}
					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}
				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );
					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}
				$type = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_attribute_swatch_type', true);
				if($type == 'photo' ){
					$thumbnail_id = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_attribute_swatch_photo', true);
					if($thumbnail_id){
						$imgsrc = wp_get_attachment_image_src($thumbnail_id, 'attribute_swatch');
						if ($imgsrc && is_array($imgsrc)) {
							$thumbnail_src = current($imgsrc);
						} else {
							$thumbnail_src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
						}
						?>
                        <a class="term-color <?php if( $option_is_set):?> selected <?php endif;?>"  href="<?php echo esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ); ?>">
                            <i style="background-image: url('<?php echo esc_url($thumbnail_src); ?>')"></i>
                            <span class="term-name"><?php echo esc_html( $term->name ); ?></span>
                            <span class="term-count"><?php echo apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term ); ?></span>
                        </a>
						<?php
					}else{
						$color = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_'.$smarket_toolkit->product_attribute_color. '_color', true);
						?>
                        <a class="term-color <?php if( $option_is_set):?> selected <?php endif;?>"  href="<?php echo esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ); ?>">
                            <i style="background-color: <?php echo esc_attr($color) ?>"></i>
                            <span class="term-name"><?php echo esc_html( $term->name ); ?></span>
							<?php echo apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term ); ?>
                        </a>
						<?php
					}

				}else{
					$color = get_woocommerce_term_meta($term->term_id, $term->taxonomy.'_attribute_swatch_color', true);
					?>
                    <a class="term-color <?php if( $option_is_set):?> selected <?php endif;?>"  href="<?php echo esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ); ?>">
                        <i style="background-color: <?php echo esc_attr($color) ?>"></i>
                        <span class="term-name"><?php echo esc_html( $term->name ); ?></span>
						<?php echo apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term ); ?>
                    </a>
					<?php
				}
			}
			?>
        </div>
		<?php
		return $found;
	}
	protected function layered_nav_inline( $terms, $taxonomy, $query_type ) {
		global $smarket_toolkit, $woocommerce;
		// List display
		?>
        <div class="inline-group">
			<?php
			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;
			foreach ( $terms as $term ) {
				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}
				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
					continue;
				}
				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );
				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}
				$link = $this->get_page_base_url( $taxonomy );
				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}
					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}
				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );
					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}
				?>
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ); ?>">
                    <span class="term-name"><?php echo esc_html( $term->name ); ?></span>
					<?php echo apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term ); ?>
                </a>
				<?php
			}
			?>
        </div>
		<?php
		return $found;
	}

}
add_action( 'widgets_init', 'Smarket_Layered_Nav_Widget' );
function Smarket_Layered_Nav_Widget() {
	register_widget( 'Smarket_Layered_Nav_Widget' );
}