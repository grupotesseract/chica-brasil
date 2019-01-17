<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}

$enable_loadmore = ciloe_get_option( 'ciloe_enable_loadmore', 'default' );

if ( $enable_loadmore != 'default' ) {
	if ( class_exists( 'PrdctfltrInit' ) ) {
		global $wp_query;
		$pf_found_posts = ! isset( $wp_query->found_posts ) ? WC_Prdctfltr_Shortcodes::$settings['instance']->found_posts : $wp_query->found_posts;
		$pf_per_page    = ! isset( $wp_query->query_vars['posts_per_page'] ) ? WC_Prdctfltr_Shortcodes::$settings['instance']->query_vars['posts_per_page'] : $wp_query->query_vars['posts_per_page'];
		$pf_offset      = isset( $wp_query->query_vars['offset'] ) ? $wp_query->query_vars['offset'] : 0;
		
		$loadmore_nav_class = 'prdctfltr-pagination-load-more';
		if ( $enable_loadmore == 'infinite' || $enable_loadmore == 'infinity' ) {
			$loadmore_nav_class .= ' prdctfltr-pagination-infinite-load';
		}
		
		?>
        <nav class="woocommerce-pagination prdctfltr-pagination <?php echo esc_attr( $loadmore_nav_class ); ?>">
			<?php
			if ( $pf_found_posts > 0 && $pf_found_posts > $pf_per_page + $pf_offset ) {
				?>
                <a href="#" class="button"><?php esc_html_e( 'Load More', 'ciloe' ); ?></a>
				<?php
			} else {
				?>
                <span class="button disabled"><?php esc_html_e( 'No More Products!', 'ciloe' ); ?></span>
				<?php
			}
			?>
        </nav>
		<?php
		
	} else {
		// Theme load more function
		echo '<div class="woocommerce-pagination woocommerce-pagination-div">';
		echo '<div class="ciloe-ajax-load" data-mode="grid" data-2nd_page_url="' . esc_url( get_next_posts_page_link( $total ) ) . '" data-cur_page="1" data-total_page="' . esc_attr( $total ) . '" data-load-more=\'{"page":"' . esc_attr( $total ) . '","container":"product-grid","layout":"' . esc_attr( $enable_loadmore ) . '"}\'>';
		next_posts_link( esc_html__( 'Load More', 'ciloe' ), $total );
		echo '</div>';
		echo '</div>';
	}
} else {
	// Back walk to default WooCommerce pagination
	?>
    <nav class="woocommerce-pagination">
		<?php
		echo paginate_links(
			apply_filters( 'woocommerce_pagination_args',
			               array( // WPCS: XSS ok.
			                      'base'      => $base,
			                      'format'    => $format,
			                      'add_args'  => false,
			                      'current'   => max( 1, $current ),
			                      'total'     => $total,
			                      'prev_text' => '&larr;',
			                      'next_text' => '&rarr;',
			                      'type'      => 'list',
			                      'end_size'  => 3,
			                      'mid_size'  => 3,
			               ) ) );
		?>
    </nav>
	<?php
}



