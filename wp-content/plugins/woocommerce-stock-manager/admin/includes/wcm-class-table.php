<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2018 Toret.cz
 */

class WCM_Table {

  /**
	 * Instance of this class.
	 *
	 * @since    1.0.5
	 *
	 * @var      object
	 */
	protected static $instance = null;
  
  
	/**
	 * Constructor for the stock class.
	 *
	 * @since     1.0.5
	 */
	private function __construct() {

		
    
	}
  
  /**
	 * Return an instance of this class.
	 *
	 * @since     1.0.5
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
  
  
	/**
   	 * Row filter
   	 *
  	 * @since     1.0.5
  	 */           
  	public static function row_filter($product_meta, $id){
  	
  
  	}  

    /**
     * Display option
     *
     * @since     1.1.2
     */           
    public static function display_option(){
        $display_option = get_option( 'wsm_display_option' );
        
        if( empty( $display_option ) ){
            $display_option = array(  
                'price'         => 'display',
                'sales_price'   => 'no',
                'weight'        => 'display',
                'manage_stock'  => 'display',
                'stock_status'  => 'display',
                'backorders'    => 'display',
                'stock'         => 'display',
            );
        }
        return $display_option;
    }

    /**
     * Table header
     *
     * @since     1.1.2
     */           
    public static function table_header_line(){

        $display_option = self::display_option(); 

        echo '<th></th>';

        if(!empty( $display_option['thumbnail'] ) && $display_option['thumbnail'] == 'display' ){
            self::table_head_thumbnail();
        }
        self::table_head_sku();
        self::table_head_id();
        self::table_head_name();
        self::table_head_product_type();
        self::table_head_parent_id();

        if(!empty( $display_option['tax_status'] ) && $display_option['tax_status'] == 'display' ){
             echo '<th>'.__('Tax status','woocommerce-stock-manager').'</th>';
        }
        if(!empty( $display_option['tax_class'] ) && $display_option['tax_class'] == 'display' ){
             echo '<th>'.__('Tax class','woocommerce-stock-manager').'</th>';
        }
        if(!empty( $display_option['shipping_class'] ) && $display_option['shipping_class'] == 'display' ){
            echo '<th>'.__('Shipping class','woocommerce-stock-manager').'</th>';
        }
        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            self::table_head_price();
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            self::table_head_sales_price();
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            self::table_head_weight();
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            self::table_head_manage_stock();
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            self::table_head_stock_status();
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            self::table_head_backorders();
        }

        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            self::table_head_stock();
        }
        do_action( 'stock_manager_table_th' );
        self::table_head_save();

    } 

    /**
     * Table simple line
     *
     * @since     1.1.2
     */           
    public static function table_simple_line( $product_meta, $item, $product_type, $product ){

        $display_option = self::display_option();
        
        if(!empty( $display_option['tax_status'] ) && $display_option['tax_status'] == 'display' ){
            self::tax_status_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['tax_class'] ) && $display_option['tax_class'] == 'display' ){
            self::tax_class_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['shipping_class'] ) && $display_option['shipping_class'] == 'display' ){
           self::shipping_class_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            self::price_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            self::sales_price_box( $product_meta, $item, $product_type ); 
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            self::weight_box($product_meta, $item); 
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            self::manage_stock_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            self::stock_status_box($product_meta, $item); 
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            self::backorders_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            self::qty_box( $product_meta, $item, $product );
        }


    }

    /**
     * Table variation line
     *
     * @since     1.1.2
     */           
    public static function table_variation_line( $product_meta, $item, $product ){

        $display_option = self::display_option();

        $product_type = 'variation';
        
        if(!empty( $display_option['tax_status'] ) && $display_option['tax_status'] == 'display' ){
            self::tax_status_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['tax_class'] ) && $display_option['tax_class'] == 'display' ){
            self::tax_class_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['shipping_class'] ) && $display_option['shipping_class'] == 'display' ){
           self::shipping_class_box( $product_meta, $item, $product_type );
        }
        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            self::price_box($product_meta, $item);
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            self::sales_price_box($product_meta, $item); 
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            self::weight_box($product_meta, $item); 
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            self::manage_stock_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            self::stock_status_box($product_meta, $item); 
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            self::backorders_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            self::qty_box($product_meta, $item, $product);
        }


    }

    /**
     * Table head Thumbnail
     *
     * @since     1.1.9
     */           
    public static function table_head_thumbnail(){
        ?>
        <th><?php _e('Thumbnail','woocommerce-stock-manager'); ?></th>
        <?php
    }
    /**
     * Table head Sku
     *
     * @since     1.1.2
     */           
    public static function table_head_sku(){
        ?>
        <th style="width:100px;"><?php _e('SKU','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head ID
     *
     * @since     1.1.2
     */           
    public static function table_head_id(){
        ?>
        <th><?php _e('ID','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Name
     *
     * @since     1.1.2
     */           
    public static function table_head_name(){
        ?>
        <th><?php _e('Name','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Product type
     *
     * @since     1.1.2
     */           
    public static function table_head_product_type(){
        ?>
        <th><?php _e('Product type','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Parent ID
     *
     * @since     1.1.2
     */           
    public static function table_head_parent_id(){
        ?>
        <th><?php _e('Parent ID','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Price
     *
     * @since     1.1.2
     */           
    public static function table_head_price(){
        ?>
        <th><?php _e('Price','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Price
     *
     * @since     1.1.2
     */           
    public static function table_head_sales_price(){
        ?>
        <th><?php _e('Sale price','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Weight
     *
     * @since     1.1.2
     */           
    public static function table_head_weight(){
        ?>
        <th><?php _e('Weight','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Manage stock
     *
     * @since     1.1.2
     */           
    public static function table_head_manage_stock(){
        ?>
        <th><?php _e('Manage stock','woocommerce-stock-manager'); ?></th>
        <?php
    }
    /**
     * Table head Stock status
     *
     * @since     1.1.2
     */           
    public static function table_head_stock_status(){
        ?>
        <th style="width:80px;"><?php _e('Stock status','woocommerce-stock-manager'); ?></th>
        <?php
    }
    /**
     * Table head Backorders
     *
     * @since     1.1.2
     */           
    public static function table_head_backorders(){
        ?>
        <th><?php _e('Backorders','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Stock
     *
     * @since     1.1.2
     */           
    public static function table_head_stock(){
        ?>
        <th style="width:50px;"><?php _e('Stock','woocommerce-stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Save
     *
     * @since     1.1.2
     */           
    public static function table_head_save(){
        ?>
        <th style="width:100px;"><?php _e('Save','woocommerce-stock-manager'); ?></th>
        <?php
    } 

 


    /**
     * Hidden box
     *
     * @since     1.1.2
     */           
    public static function hidden_box( $item ){
        ?>
        <input type="hidden" name="product_id[<?php echo $item; ?>]" value="<?php echo $item; ?>" />
        <?php
    } 

  	/**
  	 * Thumbnail box
  	 *
  	 * @since     1.1.9
  	 */           
  	public static function thumbnail_box( $item ) {
  		$display_option = self::display_option();
        if(!empty( $display_option['thumbnail'] ) && $display_option['thumbnail'] == 'display' ){
            $_product = wc_get_product( $item );
            $thumbnail = $_product->get_image( 'shop_thumbnail' );
        ?>
  		    <td class="item_thumbnail_box">
                <?php echo $thumbnail; ?>
            </td>
  		<?php
        }
  	} 

    /**
     * SKU box
     *
     * @since     1.1.2
     */           
    public static function sku_box($product_meta, $item){
        ?>
          <td class="item_sku_box">
            <span class="item-sku-text-<?php echo $item; ?>"><?php if(!empty($product_meta['_sku'][0])){ echo $product_meta['_sku'][0]; } ?></span>
                <span class="dashicons dashicons-edit" data-item="<?php echo $item; ?>"></span>
                <div class="item-sku-wrap item-sku-wrap-<?php echo $item; ?>">
                    <input type="text" name="sku[<?php echo $item; ?>]" style="width:100%;" class="item-sku sku_<?php echo $item; ?>" value="<?php if(!empty($product_meta['_sku'][0])){ echo $product_meta['_sku'][0]; } ?>" />
                    <span class="btn btn-info item-sku-button" data-item="<?php echo $item; ?>"><?php _e('Save', 'woocommerce-stock-manager'); ?></span>
                    <span class="btn btn-danger item-sku-button-close"><?php _e('Close', 'woocommerce-stock-manager'); ?></span>
                </div>
        </td>
        <?php
    } 

  	/**
  	 * ID box
  	 *
  	 * @since     1.1.2
  	 */           
  	public static function id_box( $item ){
  		?>
  		<td class="td_center"><?php echo $item; ?></td>
  		<?php
  	} 

        /**
         * Name box
         *
         * @since     1.1.2
         */           
        public static function name_box( $item ){
  		    ?>
  		    <td class="table_name_box">
                <a href="<?php echo admin_url().'post.php?post='.$item.'&action=edit'; ?>" target="_blank" class="item-post-link-<?php echo $item; ?>">
                    <?php echo get_the_title( $item ); ?>  
                </a>                
                <span class="dashicons dashicons-edit" data-item="<?php echo $item; ?>"></span>
                <div class="item-post-title-wrap item-post-title-wrap-<?php echo $item; ?>">
                    <input type="text" name="item-post-title" class="item-post-title item-post-title-<?php echo $item; ?>" value="<?php echo get_the_title( $item ); ?>" />
                    <span class="btn btn-info item-post-title-button" data-item="<?php echo $item; ?>"><?php _e('Save', 'woocommerce-stock-manager'); ?></span>
                    <span class="btn btn-danger item-post-title-button-close"><?php _e('Close', 'woocommerce-stock-manager'); ?></span>
                </div>
            </td>
            <?php
        } 

  	/**
  	 * Show variables box
  	 *
  	 * @since     1.1.2
  	 */           
  	public static function show_variables_box( $item, $product_type ){
  		?>
  		<td class="td_center">
            <?php if($product_type == 'variable'){
              echo '<span class="btn btn-info btn-sm show-variable" data-variable="'.$item.'">'.__('Show variables','woocommerce-stock-manager').'</span>';
            }else{ 
              echo $product_type; 
            } ?>
          </td>
  		<?php
  	} 

    /**
     * Tax status box
     *
     * @since     1.1.2
     */           
    public static function tax_status_box( $product_meta, $id, $product_type = null ){
        
        $product = wc_get_product( $id );

        if( $product_type == 'simple' || $product_type == 'variable' ){
            $value = $product->get_tax_status( 'edit' );
            $values = array(
                        'taxable'    => __( 'Taxable', 'woocommerce-woocommerce' ),
                        'shipping'   => __( 'Shipping only', 'woocommerce-woocommerce' ),
                        'none'       => _x( 'None', 'Tax status', 'woocommerce-woocommerce' ),
                    );
        ?>
        <td>
            <select class="tax_status<?php echo $id; ?>" name="tax_status[<?php echo $id; ?>]">
            <?php
                foreach( $values as $key => $item ){
                    ?>
                    <option value="<?php echo $key; ?>" <?php if( $value == $key ){ echo 'selected="selected"'; } ?>><?php echo $item; ?></option>
                    <?php 
                }
            ?>
            </select>           
        </td>
        <?php
        }else{
            ?><td></td><?php
        }
    }

    /**
     * Tax calss box
     *
     * @since     1.1.2
     */           
    public static function tax_class_box( $product_meta, $id, $product_type = null ){
        
        $product = wc_get_product( $id );
        
            $value = $product->get_tax_class( 'edit' );
            $values = wc_get_product_tax_class_options();
        ?>
        <td>
            <select class="tax_class<?php echo $id; ?>" name="tax_class[<?php echo $id; ?>]">
            <?php
                foreach( $values as $key => $item ){
                    ?>
                    <option value="<?php echo $key; ?>" <?php if( $value == $key ){ echo 'selected="selected"'; } ?>><?php echo $item; ?></option>
                    <?php 
                }
            ?>
            </select>           
        </td>
        <?php
    } 

    /**
     * Shipping class box
     *
     * @since     1.1.2
     */           
    public static function shipping_class_box( $product_meta, $id, $product_type = null ){
        
        $product = wc_get_product( $id );

        if( $product_type == 'variation' ){
            $label = _e( 'Same as parent', 'woocommerce-stock-manager' );
        }else{
            $label = _e( 'No shipping class', 'woocommerce-stock-manager' ); 
        }
     
            $args = array(
                'taxonomy'         => 'product_shipping_class',
                'hide_empty'       => 0,
                'show_option_none' => __( $label, 'woocommerce' ),
                'name'             => 'shipping_class['.$id.']',
                'id'               => 'shipping_class',
                'selected'         => $product->get_shipping_class_id( 'edit' ),
                'class'            => 'select short shipping_class'.$id,
            );
        ?>
        <td>
            <?php
                wp_dropdown_categories( $args );
            ?>
        </td>
        <?php
    } 
/*
    
        if(!empty( $display_option['tax_class'] ) && $display_option['tax_class'] == 'display' ){
            self::tax_class_box( $product_meta, $item->ID, $product_type );
        }
        if(!empty( $display_option['shipping_class'] ) && $display_option['shipping_class'] == 'display' ){
           self::shipping_class_box( $product_meta, $item->ID, $product_type );
        }
*/
    /**
     * Price box
     *
     * @since     1.1.2
     */           
    public static function price_box( $product_meta, $id, $product_type = null ){
        
        if( $product_type != null && $product_type == 'variable' ){
            ?>
        <td></td>
            <?php
        }else{

    ?>
        <td>
            <input class="line-price regular_price_<?php echo $id; ?>" name="regular_price[<?php echo $id; ?>]" type="number" min="<?php echo wsm_get_step(); ?>" step="<?php echo wsm_get_step(); ?>" <?php if(!empty($product_meta['_regular_price'][0])){ echo 'value="'.$product_meta['_regular_price'][0].'"'; } ?> />
        </td>
    <?php
        }
    }  

    /**
     * Sales Price box
     *
     * @since     1.1.2
     */           
    public static function sales_price_box( $product_meta, $id, $product_type = null ){
        if( $product_type != null && $product_type == 'variable' ){
            ?>
        <td></td>
            <?php
        }else{

    ?>
        <td>
            <input class="line-price sales_price_<?php echo $id; ?>" name="sales_price[<?php echo $id; ?>]" type="number" min="<?php echo wsm_get_step(); ?>" step="<?php echo wsm_get_step(); ?>" <?php if(!empty($product_meta['_sale_price'][0])){ echo 'value="'.$product_meta['_sale_price'][0].'"'; } ?> />
        </td>
        <?php
        }
    }  

    /**
     * Weight box
     *
     * @since     1.1.2
     */           
    public static function weight_box($product_meta, $id){
        ?>
        <td>
            <input class="line-price weight_<?php echo $id; ?> wc_input_decimal" name="weight[<?php echo $id; ?>]" <?php if(!empty($product_meta['_weight'][0])){ echo 'value="'.$product_meta['_weight'][0].'"'; } ?> />
        </td>
        <?php
    }  

    /**
     * Manage stock box
     *
     * @since     1.1.2
     */           
    public static function manage_stock_box($product_meta, $item){
        ?>
        <td>
            <select name="manage_stock[<?php echo $item; ?>]" class="manage_stock_<?php echo $item; ?> manage_stock_select" data-item="<?php echo $item; ?>">
              <option value="yes" <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'yes'){ echo 'selected="selected"'; } ?>><?php _e('Yes','woocommerce-stock-manager'); ?></option>
              <option value="no" <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'no'){ echo 'selected="selected"'; } ?>><?php _e('No','woocommerce-stock-manager'); ?></option>
            </select>
          </td>
        <?php
    }  
    /**
     * Stock status box
     *
     * @since     1.1.2
     */           
    public static function stock_status_box($product_meta, $item){
        ?>
        <td>
            <select name="stock_status[<?php echo $item; ?>]" class="stock_status_<?php echo $item; ?> stock_status_select" data-item="<?php echo $item; ?>" <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'yes'){ echo 'disabled'; } ?>>
              <option value="instock" <?php if(!empty($product_meta['_stock_status'][0]) && $product_meta['_stock_status'][0] == 'instock'){ echo 'selected="selected"'; } ?>><?php _e('In stock','woocommerce-stock-manager'); ?></option>
              <option value="outofstock" <?php if(!empty($product_meta['_stock_status'][0]) && $product_meta['_stock_status'][0] == 'outofstock'){ echo 'selected="selected"'; } ?>><?php _e('Out of stock','woocommerce-stock-manager'); ?></option>
              <option value="onbackorder" <?php if(!empty($product_meta['_stock_status'][0]) && $product_meta['_stock_status'][0] == 'onbackorder'){ echo 'selected="selected"'; } ?>><?php _e('On backorder','woocommerce-stock-manager'); ?></option>
            </select>
          </td>
        <?php
    }  
    /**
     * Backorders box
     *
     * @since     1.1.2
     */           
    public static function backorders_box($product_meta, $item){
        ?>
        <td>
            <select name="backorders[<?php echo $item; ?>]" class="backorders_<?php echo $item; ?> backorders_select" data-item="<?php echo $item; ?>" <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'no'){ echo 'disabled'; } ?>>
              <option value="no" <?php if(!empty($product_meta['_backorders'][0]) && $product_meta['_backorders'][0] == 'no'){ echo 'selected="selected"'; } ?>><?php _e('No','woocommerce-stock-manager'); ?></option>
              <option value="notify" <?php if(!empty($product_meta['_backorders'][0]) && $product_meta['_backorders'][0] == 'notify'){ echo 'selected="selected"'; } ?>><?php _e('Notify','woocommerce-stock-manager'); ?></option>
              <option value="yes" <?php if(!empty($product_meta['_backorders'][0]) && $product_meta['_backorders'][0] == 'yes'){ echo 'selected="selected"'; } ?>><?php _e('Yes','woocommerce-stock-manager'); ?></option>
            </select>
          </td>
        <?php
    }  

    /**
     * Qty box
     *
     * @since     1.1.2
     */           
    public static function qty_box($product_meta, $item, $product){
        
        $step = get_option( 'woocommerce_stock_qty_step' );
        if( empty( $step ) ){ $step = '1'; }

        $class = '';
        if( !empty( $product ) ){
            $stock_number = $product->get_stock_quantity();
            $class = self::get_stock_qty_class( $stock_number );
        }else{
            $class = '';
            $stock_number = '0';
        }
        if( empty( $stock_number ) ){ $stock_number = 0; }
        $_product = wc_get_product( $item );
        $product_type = $_product->get_type();
            if( $product_type == 'variable' ){

                $variable_stock = get_option( 'woocommerce_stock_variable_stock' );
                if( !empty( $variable_stock ) && $variable_stock == 'ok' ){
                    ?>
                    <td class="td_center <?php echo $class; ?>" style="width:70px;">
                        <input type="number" name="stock[<?php echo $item; ?>]" step="<?php echo $step; ?>" value="<?php echo $stock_number; ?>" class="stock_<?php echo $item; ?> stock_number" data-item="<?php echo $item; ?>" style="width:70px;"  <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'no'){ echo 'disabled'; } ?> />
                    </td>
                    <?php
                }else{
                    //Show count all variations stock.
                    $stock_number = self::get_all_variations_stock( $item );
                    $class = self::get_stock_qty_class( $stock_number );
                    ?>
                    <td class="td_center <?php echo $class; ?>" style="width:70px;">
                        <input type="number" name="stock[<?php echo $item; ?>]" step="<?php echo $step; ?>" value="<?php echo $stock_number; ?>" class="stock_<?php echo $item; ?> stock_number" data-item="<?php echo $item; ?>" style="width:70px;background:#ffffff;" disabled="disabled" />
                    </td>
                    <?php
                }
            }else{
            ?>
                <td class="td_center <?php echo $class; ?>" style="width:70px;">
                    <input type="number" name="stock[<?php echo $item; ?>]" step="<?php echo $step; ?>" value="<?php echo $stock_number; ?>" class="stock_<?php echo $item; ?> stock_number" data-item="<?php echo $item; ?>" style="width:70px;"  <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'no'){ echo 'disabled'; } ?> />
                </td>
            <?php
            }
    }  

    /**
     * Line nonce box
     *
     * @since     1.1.2
     */           
    public static function line_nonce_box($item){       
        ?>
        <input type="hidden" name="wsm-ajax-nonce-<?php echo $item; ?>" class="wsm-ajax-nonce_<?php echo $item; ?>" value="<?php echo wp_create_nonce( 'wsm-ajax-nonce-'.$item ); ?>" />
        </td>
        <?php
    } 
    /**
     * Line save box
     *
     * @since     1.1.2
     */           
    public static function line_save_box( $item ){       
        ?>
        <td class="td_center">
            <span class="btn btn-primary btn-sm save-product" data-product="<?php echo $item; ?>"><?php _e('Save','woocommerce-stock-manager'); ?></span>
        </td>
        <?php
    }  


    /**
     * Line save box
     *
     * @since     1.1.8
     */
    public static function get_all_variations_stock( $item ){ 

        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'product_variation',
            'post_parent'      => $item
        );
        $variations = get_posts( $args );

        if( !empty( $variations ) ){
            $stock = 0;
            foreach( $variations as $variation ){
                
                $product_meta = get_post_meta( $variation->ID );

                if( !empty( $product_meta['_stock'][0] ) ){

                    $stock = $stock + $product_meta['_stock'][0];
  
                }

            }
            return $stock;
        }else{
            return false;
        }
  
    }


    /**
     * Line save box
     *
     * @since     1.1.8
     */
    public static function get_stock_qty_class( $stock ){ 

        $class = 'outofstock';

        if( $stock < 1 ){ 
                $class = 'outofstock';
        }else{ 
            if( $stock < 5 ){ 
                $class = 'lowstock'; 
            }else{
                $class = 'instock';
            } 
        } 

        $class = apply_filters( 'woocommerce_stock_manager_qty_class', $class, $stock );

        return $class;
  
    }

  
}//End class  