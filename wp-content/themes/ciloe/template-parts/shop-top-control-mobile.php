<?php if ( ! is_product() ) { ?>
	<?php if ( ! ciloe_get_option( 'shop_panel' ) ) {
		return false;
	}
	
	$shop_page_id          = wc_get_page_id( 'shop' );
	$shop_page_url         = get_permalink( $shop_page_id );
	$list_categories       = ciloe_get_option( 'panel-categories', array() );
	$enable_products_sizes = ciloe_get_option( 'enable_products_sizes', false );
	
	$owl_item_open        = ''; // '<div class="cat-items cat-items-3">';
	$owl_item_close       = ''; // </div>';
	$list_categories_html = $owl_item_open . '<span data-cat_slug="" class="active all-cats cat-item"><a href="' . esc_url( $shop_page_url ) . '">' . esc_html__( 'All', 'ciloe' ) . '</a></span>';
	$total_cats_show      = 1;
	$list_cats_class      = 'category-filter category-filter-mobile';
	$shop_display_mode  = ciloe_get_option( 'shop_display_mode', 'grid' );
	$shop_mode_grid_url = add_query_arg( 'shop_display_mode', 'grid' );
	$shop_mode_list_url = add_query_arg( 'shop_display_mode', 'list' );
	$data_responsive = array(
		'0'   => array(
			'items' => 2,
		),
		'480' => array(
			'items' => 3
		),
		'768' => array(
			'items' => 6
		)
	);
	
	$data_responsive = json_encode( $data_responsive );
	
	if ( ! empty( $list_categories ) ) {
		$total_cats = count( $list_categories ) + 1; // +1 for all products
		foreach ( $list_categories as $list_category ) {
			$term = get_term_by( 'id', $list_category, 'product_cat' );
			if ( $term ) {
				$url = '#';
				$url = get_term_link( $term->term_id, 'product_cat' );
				if ( ! is_wp_error( $url ) ) {
					$total_cats_show ++;
					$list_categories_html .= '<span data-cat_slug="' . esc_attr( $term->slug ) . '" class="cat-item"><a href="' . esc_url( $url ) . '">' . esc_html( $term->name ) . '</a></span>';
					// Open item wrap every 3 categories
					if ( $total_cats_show % 3 == 0 && $total_cats_show < $total_cats ) {
						$list_categories_html .= $owl_item_close;
						$list_categories_html .= $owl_item_open;
					}
				} else {
					$total_cats --;
				}
			}
		}
	}
	
	$list_categories_html .= $owl_item_close;
	
	// Active slideshow if total cats greater than 2
	if ( $total_cats_show >= 3 ) {
		$list_cats_class .= ' owl-carousel';
	}
	
	?>
    <div class="container">
        <div class="row mobile-shop-real">
            <div class="col-xs-8">
                <h2 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h2>
            </div>
            <div class="col-xs-4">
            	<div class="action-layout">
            		<div class="action-layout-item part-wrap list-mode">
			            <a data-mode="list"
			               class="flaticon-lists display-mode <?php if ( $shop_display_mode == "list" ): ?>active<?php endif; ?>"
			               href="<?php echo esc_url( $shop_mode_list_url ); ?>"></a>
			        </div>
            		<div class="action-layout-item part-wrap grid-mode">
			            <a data-mode="grid"
			               class="flaticon-grid display-mode <?php if ( $shop_display_mode == "grid" ): ?>active<?php endif; ?>"
			               href="<?php echo esc_url( $shop_mode_grid_url ); ?>"></a>
			        </div>
            	</div>
            </div>
        </div>
    </div>
    <div class="toolbar-products toolbar-products-mobile toolbar-top">
        <div class="part-wrap part-filter-wrap">
			<?php if ( class_exists( 'PrdctfltrInit' ) ) { ?>
                <div class="actions-wrap">
                	<div class="action-mini">
                		<?php ciloe_woocommerce_catalog_ordering(); ?>
                	</div>
                	<div class="action-mini">
                    	<a class="filter-toggle" href="javascripti:void(0);"><?php esc_html_e( 'Filter', 'ciloe' ); ?></a>
                    </div>
                </div>
			<?php } ?>
        </div>
    </div>
	
	<?php if ( class_exists( 'PrdctfltrInit' ) ) { ?>
        <div class="shop-prdctfltr-filter-wrap">
			<?php woocommerce_catalog_ordering(); ?>
        </div>
	<?php } ?>

<?php }; ?>