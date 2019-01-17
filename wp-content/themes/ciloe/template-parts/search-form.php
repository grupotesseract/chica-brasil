<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_type                     = class_exists( 'WooCommerce' ) ? 'product' : '';
$enable_instant_product_search = ciloe_get_option( 'enable_instant_product_search', false );
$search_form_class             = 'instant-search';
if ( ! $enable_instant_product_search ) {
	$post_type = '';
}

if ( $post_type != 'product' ) {
	$search_form_class .= ' instant-search-disabled';
}

?>
<div class="instant-search-modal">
    <div class="instant-search-wrap">
        <div class="instant-search-close"><span></span></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <form method="get" class="<?php echo esc_attr( $search_form_class ); ?>"
                          action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-fields">
                            <div class="search-input">
                                <input type="text" name="s" class="search-field" autocomplete="off">
                                <span class="text-search"><?php esc_attr_e( 'Start typing...', 'ciloe' ); ?></span>
								<?php if ( $post_type != '' ) { ?>
                                    <input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
								<?php } ?>
                            </div>
                            <div class="search-results-container search-results-croll scrollbar-macosx">
                                <div class="search-results-container-inner">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>