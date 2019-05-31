<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2018 Toret.cz
 */

class WCM_Save {

    /**
     * Save data for one product
     *
     * @since     1.2.2
     */
    public static function save_one( $data, $product_id ){ 

        $values = self::prepare_one_data( $data );

        if( !empty( $values ) ){

            self::save_data( $values, $product_id );

        }

    }

    /**
     * Save data for one item (usedn in foreach, for save all button)
     *
     * @since     1.2.2
     */
    public static function save_one_item( $data, $product_id ){ 

        $values = self::prepare_data( $data, $product_id );

        if( !empty( $values ) ){

            self::save_data( $values, $product_id );

        }

    }

    /**
     * Prepare data
     *
     * @since     1.2.2
     */
    public static function prepare_data( $data, $item ){ 

        $values  = array();
        $defaults = self::get_default();

        foreach( $defaults as $default ){

            if( isset( $data[$default][$item] ) ) {

                $values[$default]  = sanitize_text_field( $data[$default][$item] );

            }

        }

       return $values;

    }

    /**
     * Prepare one data
     *
     * @since     1.2.2
     */
    public static function prepare_one_data( $data ){ 

        $values  = array();

        $defaults = self::get_default();

        foreach( $defaults as $default ){

            if( isset( $data[$default] ) ) {

                $values[$default]  = sanitize_text_field( $data[$default] );

            }

        }

        return $values;
 

    }

    /**
     * Get default
     *
     * @since     1.2.2
     */
    public static function get_default(){ 

        $values = array(
            'sku',
            'manage_stock',
            'backorders',
            'stock_status',
            'stock',
            'tax_status',
            'tax_class',
            'shipping_class',
            'weight',
            'regular_price',
            'sales_price',
        );

        return $values;

    }

    /**
     * Save data
     *
     * @since     1.2.2
     */
    public static function save_data( $data, $product_id ){ 

        $display_option = get_option( 'wsm_display_option' );

        $_product = wc_get_product( $product_id );

        if( isset( $data['sku'] ) ) {
            $_product->set_sku( $data['sku'] );
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            if( isset( $data['manage_stock'] ) ) {
                $_product->set_manage_stock( $data['manage_stock'] );
            }
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            if( isset( $data['backorders'] ) ) {
                $_product->set_backorders( $data['backorders'] );
            }
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            if( isset( $data['stock_status'] ) ) {
                $_product->set_stock_status( $data['stock_status'] );
            }
        }
        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            if( isset( $data['stock'] ) ) {
                $_product->set_stock_quantity( $data['stock'] );
            }
        }

        if(!empty( $display_option['tax_status'] ) && $display_option['tax_status'] == 'display' ){
            if( isset( $data['tax_status'] ) ) {
                $_product->set_tax_status( $data['tax_status'] );
            }
        }
        if(!empty( $display_option['tax_class'] ) && $display_option['tax_class'] == 'display' ){
            if( isset( $data['tax_class'] ) ) {
                $_product->set_tax_class( $data['tax_class'] );
            }
        }
        if(!empty( $display_option['shipping_class'] ) && $display_option['shipping_class'] == 'display' ){
            if( isset( $data['shipping_class'] ) ) {
                $_product->set_shipping_class_id( $data['shipping_class'] );
            }
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            if( isset( $data['weight'] ) ){
                $_product->set_weight( $data['weight'] );
            }else{
                $_product->set_weight( '' );
            }
        }
        
         /*   if( !empty( $data['regular_price'] ) ){
                $price = sanitize_text_field($data['regular_price']);
                if( !empty( $data['sales_price'] ) ){
                    $sale_price   = sanitize_text_field($data['sales_price']);
                    wsm_save_price( $product_id, $price, $sale_price );
                }else{
                    wsm_save_price( $product_id, $price );
                }
            }   
*/
        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            if( !empty( $data['regular_price'] ) ){
                $_product->set_price( $data['regular_price'] );
                $_product->set_regular_price( $data['regular_price'] );                            
            }else{
                $_product->set_price( '' );
                $_product->set_regular_price( '' );
            }         
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            if( !empty( $data['sales_price'] ) ){
                $_product->set_sale_price( $data['sales_price'] );
            }else{
                $_product->set_sale_price( '' );
            }        
        }
        $_product->save();

        wc_delete_product_transients( $product_id );    

    }

  
}//End class  