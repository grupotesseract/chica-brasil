<?php
/**
 * Legacy trait for Atum List Table component
 *
 * @package         Atum\Legacy
 * @author          Be Rebel - https://berebel.io
 * @copyright       ©2019 Stock Management Labs™
 *
 * @deprecated      This legacy class is only here for backwards compatibility and will be removed in a future version.
 *
 * @since           1.5.0
 */

namespace Atum\Legacy;

defined( 'ABSPATH' ) || die;

use Atum\Components\AtumCache;
use Atum\Components\AtumCapabilities;
use Atum\Inc\Globals;
use Atum\Inc\Helpers;
use Atum\Suppliers\Suppliers;


trait ListTableLegacyTrait {

	/**
	 * Prepare the table data
	 *
	 * @since 0.0.1
	 */
	public function prepare_items_legacy() {

		/**
		 * Define our column headers
		 */
		$columns             = $this->get_columns();
		$posts               = array();
		$sortable            = $this->get_sortable_columns();
		$hidden              = get_hidden_columns( $this->screen );
		$this->group_columns = $this->calc_groups( $this->group_members, $hidden );

		/**
		 * REQUIRED. Build an array to be used by the class for column headers
		 */
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$args = array(
			'post_type'      => $this->post_type,
			'post_status'    => current_user_can( 'edit_private_products' ) ? [ 'private', 'publish' ] : [ 'publish' ],
			'posts_per_page' => $this->per_page,
			'paged'          => $this->get_pagenum(),
		);

		/**
		 * Get Controlled or Uncontrolled items
		 */
		$this->set_controlled_query_data();

		/**
		 * Tax filter
		 */

		// Add product category to the tax query.
		if ( ! empty( $_REQUEST['product_cat'] ) ) {

			$this->taxonomies[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => esc_attr( $_REQUEST['product_cat'] ),
			);

		}

		// Change the product type tax query (initialized in constructor) to the current queried type.
		if ( ! empty( $_REQUEST['product_type'] ) ) {

			$type = esc_attr( $_REQUEST['product_type'] );

			foreach ( $this->taxonomies as $index => $taxonomy ) {

				if ( 'product_type' === $taxonomy['taxonomy'] ) {

					if ( in_array( $type, [ 'downloadable', 'virtual' ] ) ) {

						$this->taxonomies[ $index ]['terms'] = 'simple';

						$this->extra_meta = array(
							'key'   => "_$type",
							'value' => 'yes',
						);

					}
					else {
						$this->taxonomies[ $index ]['terms'] = $type;
					}

					break;
				}

			}

		}

		if ( $this->taxonomies ) {
			$args['tax_query'] = (array) apply_filters( 'atum/list_table/taxonomies', $this->taxonomies );
		}

		/**
		 * Supplier filter
		 */
		if ( ! empty( $_REQUEST['supplier'] ) && AtumCapabilities::current_user_can( 'read_supplier' ) ) {

			$supplier = absint( $_REQUEST['supplier'] );

			if ( ! empty( $this->atum_query_data['where'] ) ) {
				$this->atum_query_data['where']['relation'] = 'AND';
			}

			$this->atum_query_data['where'][] = array(
				'key'   => 'supplier_id',
				'value' => $supplier,
				'type'  => 'NUMERIC',
			);

			// This query does not get product variations and as each variation may have a distinct supplier,
			// we have to get them separately and to add their variables to the results.
			$this->supplier_variation_products = Suppliers::get_supplier_products( $supplier, [ 'product_variation' ] );

			if ( ! empty( $this->supplier_variation_products ) ) {
				add_filter( 'atum/list_table/views_data_products', array( $this, 'add_supplier_variables_to_query' ), 10, 2 );
				add_filter( 'atum/list_table/items', array( $this, 'add_supplier_variables_to_query' ), 10, 2 );
				add_filter( 'atum/list_table/views_data_variations', array( $this, 'add_supplier_variations_to_query' ), 10, 2 );
			}

		}

		/**
		 * Extra meta args
		 */
		if ( ! empty( $this->extra_meta ) ) {
			$args['meta_query'][] = $this->extra_meta;
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {

			$order                 = ( isset( $_REQUEST['order'] ) && 'asc' === $_REQUEST['order'] ) ? 'ASC' : 'DESC';
			$atum_sortable_columns = apply_filters( 'atum/list_table/atum_sortable_columns', $this->atum_sortable_columns );

			// Columns starting by underscore are based in meta keys, so can be sorted.
			if ( '_' === substr( $_REQUEST['orderby'], 0, 1 ) ) {

				if ( array_key_exists( $_REQUEST['orderby'], $atum_sortable_columns ) ) {

					$this->atum_query_data['order']          = $atum_sortable_columns[ $_REQUEST['orderby'] ];
					$this->atum_query_data['order']['order'] = $order;

				}
				// All the meta key based columns are numeric except the SKU.
				else {

					if ( '_sku' === $_REQUEST['orderby'] ) {
						$args['orderby'] = 'meta_value';
					}
					else {
						$args['orderby'] = 'meta_value_num';
					}

					$args['meta_key'] = $_REQUEST['orderby'];
					$args['order']    = $order;

				}

			}
			// Standard Fields.
			else {
				$args['orderby'] = $_REQUEST['orderby'];
				$args['order']   = $order;
			}

		}
		else {
			$args['orderby'] = 'title';
			$args['order']   = 'ASC';
		}

		/**
		 * Searching
		 */
		if ( ! empty( $_REQUEST['search_column'] ) ) {
			$args['search_column'] = esc_attr( $_REQUEST['search_column'] );
		}
		if ( ! empty( $_REQUEST['s'] ) ) {
			$args['s'] = esc_attr( $_REQUEST['s'] );
		}

		// Let others play.
		$args = apply_filters( 'atum/list_table/prepare_items/args', $args );

		// Build "Views Filters" and calculate totals.
		$this->set_views_data_legacy( $args );

		$allow_query = TRUE;

		/**
		 * REQUIRED. Register our pagination options & calculations
		 */
		$found_posts = isset( $this->count_views['count_all'] ) ? $this->count_views['count_all'] : 0;

		if ( ! empty( $_REQUEST['view'] ) ) {

			$view        = esc_attr( $_REQUEST['view'] );
			$allow_query = FALSE;

			foreach ( $this->id_views as $key => $post_ids ) {

				if ( $view === $key ) {

					$this->supplier_variation_products = array_intersect( $this->supplier_variation_products, $post_ids );

					if ( ! empty( $post_ids ) ) {

						$get_parents = FALSE;
						foreach ( Globals::get_inheritable_product_types() as $inheritable_product_type ) {

							if ( ! empty( $this->container_products[ $inheritable_product_type ] ) ) {
								$get_parents = TRUE;
								break;
							}

						}

						// Add the parent products again to the query.
						if ( $get_parents ) {

							$parents = $this->get_parents( $post_ids );

							// Exclude the parents with no children.
							// For example: the current list may have the "Out of stock" filter applied and a variable product
							// may have all of its variations in stock, but its own stock could be 0. The shouldn't appear empty.
							$empty_variables = array_diff( $this->container_products['variable'], $parents );

							foreach ( $empty_variables as $empty_variable ) {
								if ( in_array( $empty_variable, $post_ids ) ) {
									unset( $post_ids[ array_search( $empty_variable, $post_ids ) ] );
								}
							}

							$args['post__in'] = array_merge( $parents, $post_ids );

						}
						else {
							$args['post__in'] = $post_ids;
						}

						$allow_query = TRUE;
						$found_posts = $this->count_views[ "count_$key" ];
					}

				}

			}
		}

		if ( $allow_query ) {

			if ( ! empty( $this->excluded ) ) {

				if ( isset( $args['post__not_in'] ) ) {
					$args['post__not_in'] = array_merge( $args['post__not_in'], $this->excluded );
				}
				else {
					$args['post__not_in'] = $this->excluded;
				}

			}

			// Setup the WP query.
			global $wp_query;

			// Pass through the ATUM query data filter.
			add_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );
			$wp_query = new \WP_Query( $args );
			remove_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );

			$posts = $wp_query->posts;

			if ( $found_posts > 0 && empty( $posts ) ) {

				$args['paged']     = 1;
				$_REQUEST['paged'] = $args['paged'];

				// Pass through the ATUM query data filter.
				add_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );
				$wp_query = new \WP_Query( $args );
				remove_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );

				$posts = $wp_query->posts;

			}

			$product_ids = wp_list_pluck( $posts, 'ID' );

			$this->current_products = $product_ids;
			// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$total_pages = ( - 1 == $this->per_page || ! $wp_query->have_posts() ) ? 0 : ceil( $wp_query->found_posts / $this->per_page );

		}
		else {
			$found_posts = $total_pages = 0;
		}

		/**
		 * REQUIRED!!!
		 * Save the sorted data to the items property, where can be used by the rest of the class.
		 */
		$this->items = apply_filters( 'atum/list_table/items', $posts, 'posts' );

		$this->set_pagination_args( array(
			'total_items' => $found_posts,
			'per_page'    => $this->per_page,
			'total_pages' => $total_pages,
			'orderby'     => ! empty( $_REQUEST['orderby'] ) ? $_REQUEST['orderby'] : 'date',
			'order'       => ! empty( $_REQUEST['order'] ) ? $_REQUEST['order'] : 'desc',
		) );

	}

	/**
	 * Set views for table filtering and calculate total value counters for pagination
	 *
	 * @since 0.0.2
	 *
	 * @param array $args WP_Query arguments.
	 */
	protected function set_views_data_legacy( $args ) {

		global $wpdb;

		if ( $this->show_unmanaged_counters ) {

			$this->id_views = array_merge( $this->id_views, array(
				'managed'        => [],
				'unm_in_stock'   => [],
				'unm_out_stock'  => [],
				'unm_back_order' => [],
				'all_in_stock'   => [],
				'all_out_stock'  => [],
				'all_back_order' => [],
			) );

			$this->count_views = array_merge( $this->count_views, array(
				'count_managed'        => 0,
				'count_unm_in_stock'   => 0,
				'count_unm_out_stock'  => 0,
				'count_unm_back_order' => 0,
				'count_all_in_stock'   => 0,
				'count_all_out_stock'  => 0,
				'count_all_back_order' => 0,
			) );

		}

		// Get all the IDs in the two queries with no pagination.
		$args['fields']         = 'ids';
		$args['posts_per_page'] = - 1;
		unset( $args['paged'] );

		// TODO: PERHAPS THE TRANSIENT CAN BE USED MORE GENERALLY TO AVOID REPETITIVE WORK.
		$all_transient = AtumCache::get_transient_key( 'list_table_all', array_merge( $args, $this->atum_query_data ) );
		$products      = AtumCache::get_transient( $all_transient );

		if ( ! $products ) {

			global $wp_query;

			// Pass through the ATUM query data filter.
			add_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );
			$wp_query = new \WP_Query( apply_filters( 'atum/list_table/set_views_data/all_args', $args ) );
			remove_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );

			$products = $wp_query->posts;

			// Save it as a transient to improve the performance.
			AtumCache::set_transient( $all_transient, $products );

		}

		// Let others play here.
		$products = (array) apply_filters( 'atum/list_table/views_data_products', $products );

		$this->count_views['count_all'] = count( $products );

		if ( $this->is_filtering && empty( $products ) ) {
			return;
		}

		// If it's a search or a product filtering, include only the filtered items to search for children.
		$post_in = $this->is_filtering ? $products : array();

		foreach ( $this->taxonomies as $index => $taxonomy ) {

			if ( 'product_type' === $taxonomy['taxonomy'] ) {

				if ( in_array( 'variable', (array) $taxonomy['terms'] ) ) {

					$variations = apply_filters( 'atum/list_table/views_data_variations', $this->get_children( 'variable', $post_in, 'product_variation' ), $post_in );

					// Remove the variable containers from the array and add the variations.
					$products = array_unique( array_merge( array_diff( $products, $this->container_products['all_variable'] ), $variations ) );

				}

				if ( in_array( 'grouped', (array) $taxonomy['terms'] ) ) {

					$group_items = apply_filters( 'atum/list_table/views_data_grouped', $this->get_children( 'grouped', $post_in ), $post_in );

					// Remove the grouped containers from the array and add the group items.
					$products = array_unique( array_merge( array_diff( $products, $this->container_products['all_grouped'] ), $group_items ) );

				}

				// WC Subscriptions compatibility.
				if ( class_exists( '\WC_Subscriptions' ) && in_array( 'variable-subscription', (array) $taxonomy['terms'] ) ) {

					$sc_variations = apply_filters( 'atum/list_table/views_data_sc_variations', $this->get_children( 'variable-subscription', $post_in, 'product_variation' ), $post_in );

					// Remove the variable subscription containers from the array and add the subscription variations.
					$products = array_unique( array_merge( array_diff( $products, $this->container_products['all_variable_subscription'] ), $sc_variations ) );

				}

				// WC Product Bundle compatibility.
				if ( class_exists( '\WC_Product_Bundle' ) && in_array( 'bundle', (array) $taxonomy['terms'] ) ) {

					$sc_bundles = apply_filters( 'atum/list_table/views_data_bundle', $this->get_children( 'bundle', $post_in ), $post_in );

					// Remove the bundle containers from the array and add the subscription variations.
					$products = array_unique( array_merge( array_diff( $products, $this->container_products['all_bundle'] ), $sc_bundles ) );

				}

				// Re-count the resulting products.
				$this->count_views['count_all'] = count( $products );

				// The grouped items must count once per group they belongs to and once individually.
				if ( ! empty( $group_items ) ) {
					$this->count_views['count_all'] += count( $group_items );
				}

				do_action( 'atum/list_table/after_children_count', $taxonomy['terms'], $this );

				break;
			}

		}

		// For the Uncontrolled items, we don't need to calculate stock totals.
		if ( ! $this->show_controlled ) {
			return;
		}

		if ( $products ) {

			$post_types = ( ! empty( $variations ) || ! empty( $sc_variations ) ) ? [ $this->post_type, 'product_variation' ] : [ $this->post_type ];

			/*
			 * Unmanaged products
			 */
			if ( $this->show_unmanaged_counters ) {

				$products_unmanaged        = array();
				$products_unmanaged_status = Helpers::get_unmanaged_products( $post_types, TRUE );

				if ( ! empty( $products_unmanaged_status ) ) {

					// Filter the unmanaged (also removes uncontrolled).
					$products_unmanaged_status = array_filter( $products_unmanaged_status, function ( $row ) use ( $products ) {
						return in_array( $row[0], $products );
					} );

					$this->id_views['unm_in_stock'] = array_column( array_filter( $products_unmanaged_status, function ( $row ) {
						return 'instock' === $row[1];
					} ), 0 );

					$this->count_views['count_unm_in_stock'] = count( $this->id_views['unm_in_stock'] );

					$this->id_views['unm_out_stock'] = array_column( array_filter( $products_unmanaged_status, function ( $row ) {
						return 'outofstock' === $row[1];
					} ), 0 );

					$this->count_views['count_unm_out_stock'] = count( $this->id_views['unm_out_stock'] );

					$this->id_views['unm_back_order'] = array_column( array_filter( $products_unmanaged_status, function ( $row ) {
						return 'onbackorder' === $row[1];
					} ), 0 );

					$this->count_views['count_unm_back_order'] = count( $this->id_views['unm_back_order'] );

					$products_unmanaged = array_column( $products_unmanaged_status, 0 );

					$this->id_views['managed']          = array_diff( $products, $products_unmanaged );
					$this->count_views['count_managed'] = count( $this->id_views['managed'] );

				}

			}
			else {
				$products_unmanaged = array_column( Helpers::get_unmanaged_products( $post_types ), 0 );
			}

			// Remove the unmanaged from the products list.
			if ( ! empty( $products_unmanaged ) ) {

				// Filter the unmanaged (also removes uncontrolled).
				$products_unmanaged = array_intersect( $products, $products_unmanaged );

				$this->id_views['unmanaged']          = $products_unmanaged;
				$this->count_views['count_unmanaged'] = count( $products_unmanaged );

				if ( ! empty( $products_unmanaged ) ) {
					$products = ! empty( $this->count_views['count_managed'] ) ? $this->id_views['managed'] : array_diff( $products, $products_unmanaged );
				}

			}

			/*
			 * Products in stock
			 */
			$in_stock_args = array(
				'post_type'      => $post_types,
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_stock',
						'value'   => 0,
						'type'    => 'numeric',
						'compare' => '>',
					),
				),
				'post__in'       => $products,
			);

			$in_stock_transient = AtumCache::get_transient_key( 'list_table_in_stock', array_merge( $in_stock_args, $this->atum_query_data ) );
			$products_in_stock  = AtumCache::get_transient( $in_stock_transient );

			if ( empty( $products_in_stock ) ) {
				// As this query does not contain ATUM params, doesn't need the filters.
				$products_in_stock = new \WP_Query( apply_filters( 'atum/list_table/set_views_data/in_stock_args', $in_stock_args ) );
				AtumCache::set_transient( $in_stock_transient, $products_in_stock );
			}

			$products_in_stock = $products_in_stock->posts;

			$this->id_views['in_stock']          = (array) $products_in_stock;
			$this->count_views['count_in_stock'] = is_array( $products_in_stock ) ? count( $products_in_stock ) : 0;

			$products_not_stock = array_diff( (array) $products, (array) $products_in_stock, (array) $products_unmanaged );

			/**
			 * Products on Back Order
			 */
			$back_order_args = array(
				'post_type'      => $post_types,
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => '_stock',
						'value'   => 0,
						'type'    => 'numeric',
						'compare' => '<=',
					),
					array(
						'key'     => '_backorders',
						'value'   => array( 'yes', 'notify' ),
						'type'    => 'char',
						'compare' => 'IN',
					),

				),
				'post__in'       => $products_not_stock,
			);

			$back_order_transient = AtumCache::get_transient_key( 'list_table_back_order', array_merge( $back_order_args, $this->atum_query_data ) );
			$products_back_order  = AtumCache::get_transient( $back_order_transient );

			if ( empty( $products_back_order ) && ! empty( $products_not_stock ) ) {
				// As this query does not contain ATUM params, doesn't need the filters.
				$products_back_order = new \WP_Query( apply_filters( 'atum/list_table/set_views_data/back_order_args', $back_order_args ) );
				$products_back_order = $products_back_order->posts;
				AtumCache::set_transient( $back_order_transient, $products_back_order );
			}
			else {
				$products_back_order = array();
			}

			$this->id_views['back_order']          = (array) $products_back_order;
			$this->count_views['count_back_order'] = is_array( $products_back_order ) ? count( $products_back_order ) : 0;

			// As the Group items might be displayed multiple times, we should count them multiple times too.
			if ( ! empty( $group_items ) && ( empty( $_REQUEST['product_type'] ) || 'grouped' !== $_REQUEST['product_type'] ) ) {
				$this->count_views['count_in_stock']   += count( array_intersect( $group_items, (array) $products_in_stock ) );
				$this->count_views['count_back_order'] += count( array_intersect( $group_items, (array) $products_back_order ) );

			}

			/**
			 * Products with low stock
			 */
			if ( ! empty( $products_in_stock ) ) {

				$low_stock_transient = AtumCache::get_transient_key( 'list_table_low_stock', array_merge( $args, $this->atum_query_data ) );
				$products_low_stock  = AtumCache::get_transient( $low_stock_transient );

				if ( empty( $products_low_stock ) ) {

					// Compare last seven days average sales per day * re-order days with current stock.
					$str_sales = "
						(SELECT (
					        SELECT MAX(CAST( meta_value AS SIGNED )) AS q
					        FROM {$wpdb->prefix}woocommerce_order_itemmeta
					        WHERE meta_key IN('_product_id', '_variation_id')
					        AND order_item_id = itm.order_item_id
				        ) AS IDs,
				        CEIL(SUM((
				                SELECT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta
				                WHERE meta_key = '_qty' AND order_item_id = itm.order_item_id
				            ))/7*$this->days_to_reorder
			            ) AS qty
						FROM $wpdb->posts AS orders
					    INNER JOIN {$wpdb->prefix}woocommerce_order_items AS itm ON (orders.ID = itm.order_id)
						INNER JOIN $wpdb->postmeta AS order_meta ON (orders.ID = order_meta.post_id)
						WHERE orders.post_type = 'shop_order'
						AND orders.post_status IN ('wc-completed', 'wc-processing') AND itm.order_item_type ='line_item'
						AND order_meta.meta_key = '_paid_date'
						AND order_meta.meta_value >= '" . Helpers::date_format( '-7 days' ) . "'
						GROUP BY IDs) AS sales
					";

					$str_statuses = "
						(SELECT p.ID, IF(
							CAST( IFNULL(sales.qty, 0) AS DECIMAL(10,2) ) <= 
							CAST( IF( LENGTH({$wpdb->postmeta}.meta_value) = 0 , 0, {$wpdb->postmeta}.meta_value) AS DECIMAL(10,2) ), TRUE, FALSE
						) AS status
						FROM $wpdb->posts AS p
					    LEFT JOIN $wpdb->postmeta ON (p.ID = {$wpdb->postmeta}.post_id)
					    LEFT JOIN " . $str_sales . " ON (p.ID = sales.IDs)
						WHERE {$wpdb->postmeta}.meta_key = '_stock'
			            AND p.post_type IN ('" . implode( "', '", $post_types ) . "')
			            AND p.ID IN (" . implode( ', ', $products_in_stock ) . ') 
			            ) AS statuses';

					$str_sql = apply_filters( 'atum/list_table/set_views_data/low_stock', "SELECT ID FROM $str_statuses WHERE status IS FALSE;" );

					$products_low_stock = $wpdb->get_results( $str_sql ); // WPCS: unprepared SQL ok.
					$products_low_stock = wp_list_pluck( $products_low_stock, 'ID' );
					AtumCache::set_transient( $low_stock_transient, $products_low_stock );

				}

				$this->id_views['low_stock']          = (array) $products_low_stock;
				$this->count_views['count_low_stock'] = is_array( $products_low_stock ) ? count( $products_low_stock ) : 0;

			}

			/**
			 * Products out of stock
			 */
			$products_out_stock = array_diff( (array) $products_not_stock, (array) $products_back_order );

			$this->id_views['out_stock']          = $products_out_stock;
			$this->count_views['count_out_stock'] = $this->count_views['count_all'] - $this->count_views['count_in_stock'] - $this->count_views['count_back_order'] - $this->count_views['count_unmanaged'];

			/**
			 * Calculate totals
			 */
			if ( $this->show_unmanaged_counters ) {

				$this->id_views['all_in_stock']          = array_merge( $this->id_views['in_stock'], $this->id_views['unm_in_stock'] );
				$this->count_views['count_all_in_stock'] = $this->count_views['count_in_stock'] + $this->count_views['count_unm_in_stock'];

				$this->id_views['all_out_stock']          = array_merge( $this->id_views['out_stock'], $this->id_views['unm_out_stock'] );
				$this->count_views['count_all_out_stock'] = $this->count_views['count_out_stock'] + $this->count_views['count_unm_out_stock'];

				$this->id_views['all_back_order']          = array_merge( $this->id_views['back_order'], $this->id_views['unm_back_order'] );
				$this->count_views['count_all_back_order'] = $this->count_views['count_back_order'] + $this->count_views['count_unm_back_order'];

			}

		}

	}

	/**
	 * Get all the available children products in the system
	 *
	 * @since 1.1.1
	 *
	 * @param string $parent_type   The parent product type.
	 * @param array  $post_in       Optional. If is a search query, get only the children from the filtered products.
	 * @param string $post_type     Optional. The children post type.
	 *
	 * @return array|bool
	 */
	protected function get_children_legacy( $parent_type, $post_in = array(), $post_type = 'product' ) {

		// Get the published products of the same type first.
		$parent_args = array(
			'post_type'      => 'product',
			'post_status'    => current_user_can( 'edit_private_products' ) ? [ 'private', 'publish' ] : [ 'publish' ],
			'posts_per_page' => - 1,
			'fields'         => 'ids',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $parent_type,
				),
			),
		);

		if ( ! empty( $post_in ) ) {
			$parent_args['post__in'] = $post_in;
		}

		// As this query does not contain ATUM params, doesn't need the filters.
		$parents = new \WP_Query( apply_filters( 'atum/list_table/get_children/parent_args', $parent_args ) );

		$parents_with_child = $grouped_products = $bundle_children = array();

		if ( $parents->found_posts ) {

			switch ( $parent_type ) {
				case 'variable':
					$this->container_products['all_variable'] = array_unique( array_merge( $this->container_products['all_variable'], $parents->posts ) );
					break;

				case 'grouped':
					$this->container_products['all_grouped'] = array_unique( array_merge( $this->container_products['all_grouped'], $parents->posts ) );

					// Get all the children from their corresponding meta key.
					foreach ( $parents->posts as $parent_id ) {
						$children = get_post_meta( $parent_id, '_children', TRUE );

						if ( ! empty( $children ) && is_array( $children ) ) {
							$grouped_products     = array_merge( $grouped_products, $children );
							$parents_with_child[] = $parent_id;
						}
					}

					break;

				// WC Subscriptions compatibility.
				case 'variable-subscription':
					$this->container_products['all_variable_subscription'] = array_unique( array_merge( $this->container_products['all_variable_subscription'], $parents->posts ) );
					break;

				// WC Bundle Producs compatibility.
				case 'bundle':
					$this->container_products['all_bundle'] = array_unique( array_merge( $this->container_products['all_bundle'], $parents->posts ) );

					$bundle_children = Helpers::get_bundle_items( array(
						'return'    => 'id=>product_id',
						'bundle_id' => $parents->posts,
					) );

					foreach ( $parents->posts as $parent_id ) {

						if ( ! empty( $bundle_children ) && is_array( $bundle_children ) ) {
							$parents_with_child[] = $parent_id;
						}
					}
					break;
			}

			$children_args = array(
				'post_type'      => $post_type,
				'post_status'    => current_user_can( 'edit_private_products' ) ? [ 'private', 'publish' ] : [ 'publish' ],
				'posts_per_page' => - 1,
				'fields'         => 'id=>parent',
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			);

			if ( 'grouped' === $parent_type ) {
				$children_args['post__in'] = $grouped_products;
			}
			else {
				$children_args['post_parent__in'] = $parents->posts;
			}

			/*
			 * NOTE: we should apply here all the query filters related to individual child products
			 * like the ATUM control switch or the supplier
			 */
			$this->set_controlled_query_data();

			if ( ! empty( $this->supplier_variation_products ) ) {

				$this->atum_query_data['where'][] = array(
					'key'   => 'supplier_id',
					'value' => absint( $_REQUEST['supplier'] ),
					'type'  => 'NUMERIC',
				);

				$this->atum_query_data['where']['relation'] = 'AND';

			}

			add_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );
			$children = new \WP_Query( apply_filters( 'atum/list_table/get_children/children_args', $children_args ) );
			remove_filter( 'posts_clauses', array( $this, 'atum_product_data_query_clauses' ) );

			if ( $children->found_posts ) {

				if ( 'grouped' !== $parent_type ) {
					$parents_with_child = wp_list_pluck( $children->posts, 'post_parent' );
				}

				switch ( $parent_type ) {
					case 'variable':
						$this->container_products['variable'] = array_unique( array_merge( $this->container_products['variable'], $parents_with_child ) );

						// Exclude all those variations with no children from the list.
						$this->excluded = array_unique( array_merge( $this->excluded, array_diff( $this->container_products['all_variable'], $this->container_products['variable'] ) ) );
						break;

					case 'grouped':
						$this->container_products['grouped'] = array_unique( array_merge( $this->container_products['grouped'], $parents_with_child ) );

						// Exclude all those grouped with no children from the list.
						$this->excluded = array_unique( array_merge( $this->excluded, array_diff( $this->container_products['all_grouped'], $this->container_products['grouped'] ) ) );
						break;

					case 'variable-subscription':
						$this->container_products['variable_subscription'] = array_unique( array_merge( $this->container_products['variable_subscription'], $parents_with_child ) );

						// Exclude all those subscription variations with no children from the list.
						$this->excluded = array_unique( array_merge( $this->excluded, array_diff( $this->container_products['all_variable_subscription'], $this->container_products['variable_subscription'] ) ) );
						break;
				}

				$children_ids            = wp_list_pluck( $children->posts, 'ID' );
				$this->children_products = array_unique( array_merge( $this->children_products, $children_ids ) );

				return $children_ids;

			}
			elseif ( class_exists( '\WC_Product_Bundle' ) && 'bundle' === $parent_type ) {

				foreach ( $bundle_children as $key => $bundle_child ) {

					$product_child = Helpers::get_atum_product( $bundle_child );

					if ( $product_child ) {
						if ( 'yes' === Helpers::get_atum_control_status( $product_child ) ) {

							if ( ! $this->show_controlled ) {
								unset( $bundle_children[ $key ] );
							}

						}
						elseif ( $this->show_controlled ) {
							unset( $bundle_children[ $key ] );
						}
					}

				}

				if ( empty( $bundle_children ) ) {
					$parents_with_child = [];
				}
				else {

					$bundle_parents = [];
					foreach ( $bundle_children as $bundle_child ) {
						$bundle_parents = array_merge( $bundle_parents, wc_pb_get_bundled_product_map( $bundle_child ) );
					}

					$parents_with_child = $bundle_parents;

				}

				$this->container_products['bundle'] = array_unique( array_merge( $this->container_products['bundle'], $parents_with_child ) );

				// Exclude all those subscription variations with no children from the list.
				$this->excluded = array_unique( array_merge( $this->excluded, array_diff( $this->container_products['all_bundle'], $this->container_products['bundle'] ) ) );

				$this->children_products = array_unique( array_merge( $this->children_products, array_map( 'intval', $bundle_children ) ) );

				return $bundle_children;

			}
			else {
				$this->excluded = array_unique( array_merge( $this->excluded, $parents->posts ) );
			}

		}

		return array();

	}

	/**
	 * Set the query data for filtering the Controlled/Uncontrolled products.
	 *
	 * @since 1.5.0
	 */
	protected function set_controlled_query_data() {

		if ( $this->show_controlled ) {

			$this->atum_query_data['where'] = array(
				array(
					'key'   => 'atum_controlled',
					'value' => 1,
					'type'  => 'NUMERIC',
				),
			);

		}
		else {

			$this->atum_query_data['where'] = array(
				array(
					'relation' => 'OR',
					array(
						'key'   => 'atum_controlled',
						'value' => 0,
						'type'  => 'NUMERIC',
					),
					array(
						'key'   => 'inheritable',
						'value' => 1,
						'type'  => 'NUMERIC',
					),
				),
			);

		}

	}

}
