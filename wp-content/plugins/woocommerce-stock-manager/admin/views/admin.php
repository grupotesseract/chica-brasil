<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

$stock = $this->stock();

/**
 * Save all data
 *
 */   
if( isset( $_POST['product_id'] ) ){
    $stock->save_all( $_POST );
    //add redirect
  
}

/**
 * Save display option
 *
 */   
if( isset( $_POST['page-filter-display'] ) ){
    $stock->save_filter_display( $_POST ); 
}


?>


<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  
  

  
<div class="t-col-12">
  <div class="toret-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Stock manager','woocommerce-stock-manager'); ?></h3>
    </div>
  <div class="box-body">
      
      <?php include('components/filter.php'); ?>
      
      <div class="clear"></div>
    <form method="post" action="" style="position:relative;">
    <div class="lineloader"></div>  
      <table class="table-bordered">
        <tr>
          <?php WCM_Table::table_header_line(); ?>
        </tr>
      <?php $products = $stock->get_products( $_GET ); 
      
      if( !empty( $products->posts ) ){
        foreach( $products->posts as $item ){ 
        $product_meta = get_post_meta($item->ID);
        $item_product = wc_get_product($item->ID);
        $product_type = $item_product->get_type();
      ?>
        <tr>
        <?php if($product_type == 'variable'){
            echo '<td></td>';
        }else{
        ?>
            <td class="td_center"><input type="checkbox" name="cd[<?php echo $item->ID; ?>]" /></td>
        <?php } ?>  
          <?php WCM_Table::hidden_box( $item->ID ); ?>
          <?php WCM_Table::thumbnail_box( $item->ID ); ?>
          <?php WCM_Table::sku_box( $product_meta, $item->ID ); ?>
          <?php WCM_Table::id_box( $item->ID ); ?>
          <?php WCM_Table::name_box( $item->ID ); ?>
          <td class="td_center">
            <?php if($product_type == 'variable'){
              echo '<span class="btn btn-info btn-sm show-variable" data-variable="'.$item->ID.'">'.__('Show variables','woocommerce-stock-manager').'</span>';
            }else{ 
              echo $product_type; 
            } ?>
          </td>
          <td></td>
          <?php WCM_Table::table_simple_line( $product_meta, $item->ID, $product_type, $item_product ); ?>
          <?php do_action( 'stock_manager_table_simple_td', $item->ID ); ?>
          <?php WCM_Table::line_nonce_box( $item->ID ); ?>
          <?php WCM_Table::line_save_box( $item->ID ); ?>
       </tr>
        
        <?php 
            if($product_type == 'variable'){
                $args = array(
	               'post_parent' => $item->ID,
	               'post_type'   => 'product_variation', 
	               'numberposts' => -1,
	               'post_status' => 'publish', 
                 'order_by' => 'menu_order'
                ); 

                //var_dump( $item_product->get_children() );

                $variations_array = $item_product->get_children();
                foreach($variations_array as $vars){
             
        $product_meta = get_post_meta($vars);
        $item_product = wc_get_product($vars);
        $product_type = 'product variation' ;
        
      ?>
        <tr class="variation-line variation-item-<?php echo $item->ID; ?>">
          <td class="td_center"><input type="checkbox" name="cd[<?php echo $vars; ?>]" /></td>
          <?php WCM_Table::hidden_box( $vars ); ?>
          <?php WCM_Table::thumbnail_box( $vars ); ?>
          <?php WCM_Table::sku_box( $product_meta, $vars ); ?>
          <?php WCM_Table::id_box( $vars ); ?>
         
           <td><?php 
          foreach($item_product->get_variation_attributes() as $k => $v){ 
             $tag = get_term_by('slug', $v, str_replace('attribute_','',$k));
             if($tag == false ){
               echo $v.' ';
             }else{
             if(is_array($tag)){
              echo $tag['name'].' ';
             }else{
              echo $tag->name.' ';
             }
             }
          } 
          ?></td>
          <td><?php echo $product_type; ?></td>
          <td><?php echo $item->ID; ?></td>
          <?php WCM_Table::table_variation_line( $product_meta, $vars, $item_product ); ?>
          <?php do_action( 'stock_manager_table_variation_td', $vars ); ?>
          <?php WCM_Table::line_nonce_box($vars); ?>
          <?php WCM_Table::line_save_box($vars); ?>
        </tr>      
        <?php        
                }
            }
        ?>
        
      <?php }

        }
       ?>
      
      </table>
      <div class="clear"></div>
      <input type="submit" name="save-all" class="btn btn-danger" value="<?php _e('Save all','woocommerce-stock-manager') ?>" />
      </form>
      <div class="clear"></div>
      
      <?php echo $stock->pagination( $products ); ?>
  </div>
</div>  
  

</div>
