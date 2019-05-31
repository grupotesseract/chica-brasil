<?php
/**
 * Plugin Name:       WooCommerce Stock Manager
 * Plugin URI:        http:/toret.cz
 * Description:       WooCommerce Stock Manager
 * Version:           1.2.8
 * Author:            Vladislav Musílek
 * Author URI:        http://toret.cz
 * Text Domain:       stock-manager
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * WC tested up to: 3.5.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'STOCKDIR', plugin_dir_path( __FILE__ ) );
define( 'STOCKURL', plugin_dir_url( __FILE__ ) );
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

include_once( plugin_dir_path( __FILE__ ) . 'admin/includes/wcm-class-save.php' );

require_once( plugin_dir_path( __FILE__ ) . 'public/class-stock-manager.php' );

register_activation_hook( __FILE__, array( 'Stock_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Stock_Manager', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Stock_Manager', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-stock-manager-admin.php' );
	add_action( 'plugins_loaded', array( 'Stock_Manager_Admin', 'get_instance' ) );

}




 
 

    add_action( 'wp_ajax_save_one_product', 'stock_manager_save_one_product_stock_data' ); 

    /**
     * Save one product stock data 
     *
     */        
    function stock_manager_save_one_product_stock_data(){
	
        if( current_user_can('manage_woocommerce') ){

            $product_id   = sanitize_text_field( $_POST['product'] );

            check_ajax_referer( 'wsm-ajax-nonce-'.$product_id, 'secure' );

            WCM_Save::save_one( $_POST, $product_id );
     
        }

        exit();

    }  


add_action( 'wp_ajax_wsm_save_title_product', 'stock_manager_wsm_save_title_product' ); 

  /**
   * Save product title
   *
   */        
  function stock_manager_wsm_save_title_product(){
    
    if( current_user_can('manage_woocommerce') ){

        //check_ajax_referer( 'wsm_update', 'security' );

        $item   = sanitize_text_field($_POST['item']);
        $title   = sanitize_text_field($_POST['title']);
        
        $args = array(
            'ID'           => $item,
            'post_title'   => $title,
        );

        $product_id = wp_update_post( $args );

        
     
    }
    echo $product_id;
    exit($product_id);
}  

add_action( 'wp_ajax_wsm_save_sku', 'stock_manager_wsm_save_sku' ); 

  /**
   * Save product title
   *
   */        
  function stock_manager_wsm_save_sku(){
    
    if( current_user_can('manage_woocommerce') ){

        //check_ajax_referer( 'wsm_update', 'security' );

        $item   = sanitize_text_field($_POST['item']);
        $sku   = sanitize_text_field($_POST['sku']);
        
        update_post_meta( $item, '_sku', $sku );

        
     
    }
    echo $item;
    exit();
}  
  
  
  /**
   * Get WooCommerce setting for number field step
   *
   */        
  function wsm_get_step(){
      $number = get_option('woocommerce_price_num_decimals');
      if( $number == '0' ){ $step = '1'; }
      if( $number == '1' ){ $step = '0.1'; }
      if( $number == '2' ){ $step = '0.01'; }
      if( $number == '3' ){ $step = '0.001'; }
      if( $number == '4' ){ $step = '0.0001'; }
      if( $number == '5' ){ $step = '0.00001'; }
      if( $number == '6' ){ $step = '0.000001'; }
  
      return $step;
  
  }  

    /**
     * Save stock
     *
     */
    function wsm_save_stock( $product_id, $regular_price, $sale_price = null ){

        
    
    }



    /**
     * Save price function
     *
     */
    function wsm_save_price( $product_id, $regular_price, $sale_price = null ){

        $product = wc_get_product( $product_id );

        if( !empty( $regular_price ) ){
                $price        = sanitize_text_field( $regular_price );
                //update_post_meta( $product_id, '_price', $price );
                //update_post_meta( $product_id, '_regular_price', $price ); 
                $product->set_price( $price );
                $product->set_regular_price( $price );                         
            }         

            if( !empty( $sale_price ) ){
                $sale_price   = sanitize_text_field($sale_price);
                //update_post_meta( $product_id, '_sale_price', $sale_price ); 
                $product->set_sale_price( $sale_price );
            }

            $product->save();
    
    }



    function wsm_search_by_title_only( $search, &$wp_query ){
        global $wpdb;
        if ( empty( $search ) )
            return $search; // skip processing - no search term in query
        $q = $wp_query->query_vars;
        $n = ! empty( $q['exact'] ) ? '' : '%';
        $search = '';
        $searchand = '';
        foreach ( (array) $q['search_terms'] as $term ) {
            $term = esc_sql( $wpdb->esc_like( $term ) );
            $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $searchand = ' AND ';
        }
        if ( ! empty( $search ) ) {
            $search = " AND ({$search}) ";
            if ( ! is_user_logged_in() )
                $search .= " AND ($wpdb->posts.post_password = '') ";
        }
        return $search;
    }


    /**
     * Get products for export
     *
     */        
    add_action( 'wp_ajax_wsm_get_products_or_export', 'wsm_get_products_or_export' ); 
    function wsm_get_products_or_export(){
    
        $offset = sanitize_text_field( $_POST['offset'] );
        $posts_per_page = 10;
        $args = array(
            'post_type'      => 'product',
            'posts_status'   => 'publish',
            'posts_per_page' => $posts_per_page,
            'offset'         => $offset
        );
        $_products = new WP_Query( $args );
        if( !empty( $_products->posts ) ){
            $data = array();
            $i = 1 + $offset;
            foreach( $_products->posts as $item ){

                $product = wc_get_product( $item->ID );
                if( !empty( $product->get_sku() ) ){ $sku = $product->get_sku(); }else{ $sku = ''; }
                $product_name = $item->post_title;
                if( !empty( $product->get_manage_stock() ) ){ $manage_stock = $product->get_manage_stock(); }else{ $manage_stock = ''; }
                if( !empty( $product->get_stock_status() ) ){ $stock_status = $product->get_stock_status(); }else{ $stock_status = ''; }
                if( !empty( $product->get_backorders() ) ){ $backorders = $product->get_backorders(); }else{ $backorders = ''; }
                if( !empty( $product->get_stock_quantity() ) ){ $stock = $product->get_stock_quantity(); }else{ $stock = ''; }
                $product_type = $product->get_type();

                $data[$i]['id']   = $item->ID; 
                $data[$i]['sku']          = $sku; 
                $data[$i]['product_name'] = $product_name; 
                $data[$i]['manage_stock'] = $manage_stock; 
                $data[$i]['stock_status'] = $stock_status; 
                $data[$i]['backorders']   = $backorders; 
                $data[$i]['stock']        = $stock; 
                $data[$i]['type']         = $product_type; 
                $data[$i]['parent_id']    = ''; 
                
                $i++;

                if($product_type == 'variable'){

                    $args = array(
                        'post_parent' => $item->ID,
                        'post_type'   => 'product_variation', 
                        'numberposts' => -1,
                        'post_status' => 'publish' 
                    ); 
                    $variations_array = get_children( $args );
                    foreach($variations_array as $vars){

                        $item_product = wc_get_product($vars->ID);
                        if( !empty( $item_product->get_sku() ) ){ $sku = $item_product->get_sku(); }else{ $sku = ''; }
                        
                        $product_name = '';
                        foreach($item_product->variation_data as $k => $v){ 
                            $tag = get_term_by('slug', $v, str_replace('attribute_','',$k));
                            if($tag == false ){
                                $product_name .= $v.' ';
                            }else{
                                if(is_array($tag)){
                                    $product_name .= $tag['name'].' ';
                                }else{
                                    $product_name .= $tag->name.' ';
                                }
                            }
                        }

                        if( !empty( $item_product->get_manage_stock() ) ){ $manage_stock = $item_product->get_manage_stock(); }else{ $manage_stock = ''; }
                        if( !empty( $item_product->get_stock_status() ) ){ $stock_status = $item_product->get_stock_status(); }else{ $stock_status = ''; }
                        if( !empty( $item_product->get_backorders() ) ){ $backorders = $item_product->get_backorders(); }else{ $backorders = ''; }
                        if( !empty( $item_product->get_stock_quantity() ) ){ $stock = $item_product->get_stock_quantity(); }else{ $stock = ''; }
                        $product_type = 'product-variant';

                        $data[$i]['id']   = ''; 
                        $data[$i]['sku']          = $sku; 
                        $data[$i]['product_name'] = $product_name; 
                        $data[$i]['manage_stock'] = $manage_stock; 
                        $data[$i]['stock_status'] = $stock_status; 
                        $data[$i]['backorders']   = $backorders; 
                        $data[$i]['stock']        = $stock; 
                        $data[$i]['type']         = $product_type; 
                        $data[$i]['parent_id']    = $item->ID; 
                
                        $i++;

                    }
                }


            }
            $offset = $offset + 10;
            //$data = json_encode( $data );
            $reponse = array(
                'status'    => 'continue',
                'data'      => $data,
                'offset'    => $offset
            );
            echo json_encode( $reponse );
            exit();


        }else{

            $reponse = array(
                'status'    => 'finish',
                'text'      => __( 'All done! Close.', 'woocommerce-stock-manager' )
            );
            echo json_encode( $reponse );
            exit();

        }

    }

    add_action( 'wp_ajax_wsm_get_csv_file', 'wsm_get_csv_file' ); 
    function wsm_get_csv_file(){

        $data = stripslashes( $_POST['data'] );
        $data = (array)json_decode( $data );

        //$temp_memory = fopen('php://memory', 'w');
        $string = array();
        foreach ($data as $line) {

            $line_array = array(
                'Id' => $line->id,
                'Sku' => $line->sku,
                'Product name' => $line->product_name,
                'Manage stock' => $line->manage_stock,
                'Stock status' => $line->stock_status,
                'Backorders' => $line->backorders,
                'Stock' => $line->stock,
                'Type' => $line->type,
                'Parent ID' => $line->parent_id
            );
            $string[] = $line_array;
            //fputcsv($temp_memory, $line_array, ';');
        }
        //fseek($temp_memory, 0);
    
        //header('Content-Type: application/csv');
        //header('Content-Disposition: attachement; filename="stock-manager-export.csv";');
    
        //fpassthru($temp_memory);

        echo json_encode( $string );

        exit();

    }


    /**
     * Get product data
     *
     */        
    add_action( 'wp_ajax_wsm_get_product_data', 'wsm_get_product_data' ); 
    function wsm_get_product_data(){
    
        $product_id = sanitize_text_field( $_POST['productid'] );        
        $product = wc_get_product( $product_id );
        $data = array();
        $data['productid'] = $product_id;

        if( !empty( $product->get_manage_stock() ) ){ $data['manage_stock'] = $product->get_manage_stock(); }
        if( !empty( $product->get_stock_status() ) ){ $data['stock_status'] = $product->get_stock_status(); }
        if( !empty( $product->get_backorders() ) ){ $data['backorders'] = $product->get_backorders(); }
        if( !empty( $product->get_stock_quantity() ) ){ $data['stock'] = $product->get_stock_quantity(); }
                
        echo json_encode( $data );
        exit();

    }