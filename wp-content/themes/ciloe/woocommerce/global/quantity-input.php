<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woothemes.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.4.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $max_value && $min_value === $max_value ) {
	?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty"
               name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>"/>
    </div>
	<?php
} else {
	?>
    <div class="quantity">
        <div class="control">
            
            <input type="text" data-step="<?php echo esc_attr( $step ); ?>"
                   data-min="<?php echo esc_attr( $min_value ); ?>" data-max="<?php echo esc_attr( $max_value ); ?>"
                   name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>"
                   title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'ciloe' ) ?>"
                   class="input-qty qty" size="4"/>
            <a class="btn-number qtyplus quantity-plus" href="#"><i class="fa fa-caret-up"
                                                                    aria-hidden="true"></i></a>
            <a class="btn-number qtyminus quantity-minus" href="#"><i class="fa fa-caret-down"
                                                                      aria-hidden="true"></i></a>
            

        </div>
    </div>
	<?php
}
