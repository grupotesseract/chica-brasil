<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$classes                 = array();
$ciloe_woo_product_style = ciloe_get_option( 'ciloe_shop_product_style', 1 );
$post_meta_data          = get_post_meta( get_the_ID(), '_custom_post_woo_options', true );

$data_reponsive = array(
	'0'    => array(
		'items' => 1
	),
	'360'  => array(
		'items' => 2
	),
	'768'  => array(
		'items' => 2
	),
	'992'  => array(
		'items' => 3
	),
	'1200' => array(
		'items' => 4
	),
);
$classes[]      = 'product-item style-' . $ciloe_woo_product_style;
$data_reponsive = wp_json_encode( $data_reponsive );
$dots           = 'false';
$data_margin    = '30';
if ( ciloe_is_mobile() ) {
	$data_margin = '0';
	$dots        = 'true';
}
if ( class_exists( 'WooCommerce' ) && isset( $post_meta_data['ciloe_product_options'] ) ) {
	if ( ! empty( $post_meta_data['ciloe_product_options'] ) ) {
		$product_style = '1';
		$product_ids   = $post_meta_data['ciloe_product_options'];
		$query_args    = array(
			'post_type' => 'product',
			'post__in'  => $product_ids
		);
		$products      = new WP_Query( $query_args );
		
		if ( $products->have_posts() ) { ?>
            <div class="single-post-products-carousel-wrap product-grid">
                <h3 class="single-post-products-carousel-title text-center"><?php esc_attr_e( 'Special Products', 'ciloe' ); ?></h3>
                <div class="single-post-products-carousel owl-carousel equal-container"
                     data-margin="<?php echo esc_attr( $data_margin ); ?>"
                     data-dots="<?php echo esc_attr( $dots ); ?>"
                     data-nav="true"
                     data-responsive="<?php echo esc_attr( htmlentities2( $data_reponsive ) ); ?>">
					<?php
					while ( $products->have_posts() ) { ?>
                        <div <?php post_class( $classes ) ?>>
							<?php $products->the_post();
							wc_get_template_part( 'product-styles/content-product-style', $product_style );
							?>
                        </div>
					<?php } ?>
                </div>
            </div>
			<?php
		}
		wp_reset_postdata();
	}
}