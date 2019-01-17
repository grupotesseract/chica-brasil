<?php if ( ! is_product() ) { ?>
	<?php if ( ! ciloe_get_option( 'shop_panel' ) ) {
		return false;
	} ?>
	<?php
	$page_id               = wc_get_page_id( 'shop' );
	$page_url              = get_permalink( $page_id );
	$list_categories       = ciloe_get_option( 'panel-categories', array() );
	$enable_products_sizes = ciloe_get_option( 'enable_products_sizes', false );
	$product_size_active   = isset( $_REQUEST['products_size'] ) ? $_REQUEST['products_size'] : 'size-5';
	if ( ! in_array( $product_size_active, array( 'size-3', 'size-4', 'size-5' , 'size-6' ) ) ) {
		$product_size_active = 'size-5';
	}
	$shop_display_mode  = ciloe_get_option( 'shop_display_mode', 'grid' );
	$shop_mode_grid_url = add_query_arg( 'shop_display_mode', 'grid' );
	$shop_mode_list_url = add_query_arg( 'shop_display_mode', 'list' );
 
	?>
    <div class="toolbar-products toolbar-top">
    	<div class="part-wrap filter-ordering"> 
    		<?php ciloe_woocommerce_catalog_ordering(); ?>
    	</div>
    	<div class="part-wrap grid-mode">
            <a data-mode="grid"
               class="display-mode <?php if ( $shop_display_mode == "grid" ): ?>active<?php endif; ?>"
               href="<?php echo esc_url( $shop_mode_grid_url ); ?>">grid</a>
        </div>
        <div class="part-wrap part-filter-wrap">
			<?php if ( class_exists( 'PrdctfltrInit' ) ) { ?>
                <div class="actions-wrap">
                    <a class="filter-toggle" href="#"><i class="flaticon-filter"></i></a>
                </div>
			<?php } ?>
        </div>
        <div class="part-wrap list-mode">
            <a data-mode="list"
               class="display-mode <?php if ( $shop_display_mode == "list" ): ?>active<?php endif; ?>"
               href="<?php echo esc_url( $shop_mode_list_url ); ?>">list</a>
        </div>
        <?php if ( $shop_display_mode == "grid" ):?>
        	<?php if ( $enable_products_sizes ) { ?>
	            <div class="part-wrap part-products-size-wrap">
	                <div class="products-sizes">
	                	<a href="#" data-products_num="3" class="products-size <?php if ( $product_size_active == 'size-3' ) {
						   echo 'active';
					   } ?>">3</a>
	                    <a href="#" data-products_num="4" class="products-size size-4 <?php if ( $product_size_active == 'size-4' ) {
						   echo 'active';
					   } ?>">4</a>
	                    <a href="#" data-products_num="5" class="products-size size-5 <?php if ( $product_size_active == 'size-5' ) {
						   echo 'active';
					   } ?>">5</a>
	                    <a href="#" data-products_num="6" class="products-size size-6 <?php if ( $product_size_active == 'size-6' ) {
						   echo 'active';
					   } ?>">6</a>
	                </div>
	            </div>
	        <?php } ?>
		<?php endif; ?>
    </div>
	


<?php }; ?>