<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

class WCM_Stock {

  /**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
  
    /**
	 * Constructor for the stock class.
	 *
	 * @since     1.0.0
	 */
    public $limit = 100; 
   

	/**
	 * Constructor for the stock class.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
    
        $limit = get_option( 'woocommerce_stock_limit' );
        if( !empty( $limit ) ){
            $this->limit = $limit;
        }

	}
  
  /**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
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
  	 * Return products
  	 *
  	 *   
  	 * @since 1.0.0  
  	 */        
  	public function get_products($data = array()){
  
    	if( isset( $_GET['sku'] ) ){ 
            return $this->get_product_by_sku( $_GET['sku'] ); 
        }

        if( isset( $_GET['product-title'] ) ){ 
            return $this->get_product_by_product_title( $_GET['product-title'] ); 
        }
  
    	$args = array();
    	$args['post_type'] = 'product';

    	//Inicialize tax_query array
    	if( !empty( $_GET['product-type'] ) ||  !empty( $_GET['product-category'] ) ){
    			$args['tax_query'] = array();	
    	

    		if(isset($_GET['product-type'])){
      			if($_GET['product-type'] == 'variable'){
        		        
        			$args['tax_query'][] = array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'variable',
										'field' 	  => 'slug'
								
								);
        
      			}else{
        		
        			$args['tax_query'] = array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'simple',
										'field' 	  => 'slug'
								);
      			}

    		}

    	

    	/**
    	 * Product category filter
    	 */         
    	if( isset( $_GET['product-category'] ) ){
      		if( $_GET['product-category'] != 'all' ){
      
      			$category = $_GET['product-category'];
      
      			$args['tax_query'][] = array(
										'taxonomy' 	=> 'product_cat',
										'terms' 	  => $category,
										'field' 	  => 'term_id'
								);   
      		}
    	}
   
    	}	

    	//Inicialize meta_query array
    	if( !empty( $_GET['stock-status'] ) || !empty( $_GET['manage-stock'] ) ){
    		$args['meta_query'] = array();	
    	

   			if(!empty($_GET['stock-status'])){ 
      			$status = $_GET['stock-status'];
   
      			$args['meta_query'][] = array(
      					'key'     => '_stock_status',
						'value'   => $status,
						'compare' => '=',
      			);

   			}
   
   			if(!empty($_GET['manage-stock'])){ 
      			$manage = $_GET['manage-stock'];
      
      			$args['meta_query'][] = array(
      					'key'     => '_manage_stock',
						'value'   => $manage,
						'compare' => '=',
      			);

   			}
   		}

   		if(isset($_GET['order-by'])){ 
      		$order_by = $_GET['order-by'];

      		if( $order_by == 'name-asc' ){

      			$args['orderby'] = 'title';
				$args['order'] = 'ASC';

      		}
      		elseif( $order_by == 'name-desc' ){

      			$args['orderby'] = 'title';
				$args['order'] = 'DESC';

   			}
   			elseif( $order_by == 'sku-asc' ){

      			$args['meta_key'] = '_sku';
      			$args['orderby'] = 'meta_value';
				$args['order'] = 'ASC';   				

   			}
   			elseif( $order_by == 'sku-desc' ){

   				$args['meta_key'] = '_sku';
      			$args['orderby'] = 'meta_value';
				$args['order'] = 'DESC';

   			}


   		}

  
    	$args['posts_per_page'] = $this->limit;


    	if(!empty($_GET['offset'])){
      		$offset = $_GET['offset'] - 1;
      		$offset = $offset * $this->limit;
      		$args['offset'] = $offset;

    	}
  	
  
    	$the_query = new WP_Query( $args );
    
    	return $the_query;
  	} 
  
  /**
   * Return all products
   *
   *   
   * @since 1.0.0  
   */        
  	public function get_all_products(){
  
    
    
    
    $args = array();
    
    if(isset($_GET['product-type'])){
      if($_GET['product-type'] == 'variable'){
        $args['post_type'] = 'product';
        
        $args['tax_query'] = array(
									array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'variable',
										'field' 	  => 'slug'
									)
								);
        
      }else{
        $args['post_type'] = 'product';
        $args['tax_query'] = array(
									array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'simple',
										'field' 	  => 'slug'
									)
								);
      }
    }else{
        $args['post_type'] = 'product';
    }
    
    
    /**
     * Product category filter
     */         
    if(isset($_GET['product-category'])){
      if($_GET['product-category'] != 'all'){
      
      $category = $_GET['product-category'];
      
      $args['tax_query'] = array(
									array(
										'taxonomy' 	=> 'product_cat',
										'terms' 	  => $category,
										'field' 	  => 'term_id'
									)
								);   
      }
    }
   
   if(isset($_GET['stock-status'])){ 
      $status = $_GET['stock-status'];
   
      $args['meta_key']   = '_stock_status';
      $args['meta_value'] = $status;
   }
   
   if(isset($_GET['manage-stock'])){ 
      $manage = $_GET['manage-stock'];
      
      $args['meta_key']   = '_manage_stock';
      $args['meta_value'] = $manage;
   }
    
    
    
    
    
    $args['posts_per_page'] = -1;
    

    $the_query = new WP_Query( $args );
    
    return $the_query->posts;
  }   
  
  /**
   * Return all products
   *
   *   
   * @since 1.0.0  
   */        
  public function get_products_for_export(){
  
    $args = array();
    $args['post_type'] = 'product';
    $args['posts_per_page'] = -1;
    
    $the_query = new WP_Query( $args );
    
    return $the_query->posts;
  }   
  
  /**
   * Return pagination
   *
   */        
  public function pagination( $query ){
     
     if( isset( $_GET['sku'] ) ){ return false; }
     
     $all = $query->found_posts;

     $pages = ceil($all / $this->limit);
     if( !empty( $_GET['offset'] ) ){
       $current = $_GET['offset'];
     }else{
       $current = 1;
     }
     
     $html = '';
     $html .= '<div class="stock-manager-pagination">';
     $query_string = $_SERVER['QUERY_STRING'];
     if($pages != 1){
     
      for ($i=1; $i <= $pages; $i++){
        if($current == $i){
            $html .= '<span class="btn btn-default">'.$i.'</span>';
        }else{
            $html .= '<a class="btn btn-primary" href="'.admin_url().'admin.php?'.$query_string.'&offset='.$i.'">'.$i.'</a>';
        }
      }
     
     }
     
     $html .= '</div>';
     
     return $html;
  }  
  
  /**
   * Save all meta data
   *
   */        
    public function save_all( $data ){

        foreach( $data['product_id'] as $key => $item ){
  
            $product_id = sanitize_text_field( $item );

            WCM_Save::save_one_item( $_POST, $product_id );
            
        }   
    }

    /**
   * Save all meta data
   *
   */        
    public function save_filter_display($data){

        $option = array();
        
        if( !empty( $data['thumbnail'] ) ){ $option['thumbnail'] = 'display'; }else{ $option['thumbnail'] = 'no'; }
        if( !empty( $data['price'] ) ){ $option['price'] = 'display'; }else{ $option['price'] = 'no'; }
        if( !empty( $data['sales_price'] ) ){ $option['sales_price'] = 'display'; }else{ $option['sales_price'] = 'no'; }
        if( !empty( $data['weight'] ) ){ $option['weight'] = 'display'; }else{ $option['weight'] = 'no'; }
        if( !empty( $data['manage_stock'] ) ){ $option['manage_stock'] = 'display'; }else{ $option['manage_stock'] = 'no'; }
        if( !empty( $data['stock_status'] ) ){ $option['stock_status'] = 'display'; }else{ $option['stock_status'] = 'no'; }
        if( !empty( $data['backorders'] ) ){ $option['backorders'] = 'display'; }else{ $option['backorders'] = 'no'; }
        if( !empty( $data['stock'] ) ){ $option['stock'] = 'display'; }else{ $option['stock'] = 'no'; }
        if( !empty( $data['tax_status'] ) ){ $option['tax_status'] = 'display'; }else{ $option['tax_status'] = 'no'; }
        if( !empty( $data['tax_class'] ) ){ $option['tax_class'] = 'display'; }else{ $option['tax_class'] = 'no'; }
        if( !empty( $data['shipping_class'] ) ){ $option['shipping_class'] = 'display'; }else{ $option['shipping_class'] = 'no'; }


        if( !empty( $option ) ){
            update_option( 'wsm_display_option', $option );
        }
     
        
    }
  
  /**
   *
   * Get prduct categories 
   *
   */   
  public function products_categories($selected = null){
    $out = '';
    
    
    
    
    $terms = get_terms(
                      'product_cat', 
                      array(
                            'hide_empty' => 0, 
                            'orderby' => 'ASC'
                      )
    );
    if(count($terms) > 0)
    {
        foreach ($terms as $term)
        {
            if(!empty($selected) && $selected == $term->term_id){ $sel = 'selected="selected"'; }else{ $sel = ''; }
            $out .= '<option value="'.$term->term_id.'" '.$sel.'>'.$term->name.'</option>';
        }
        return $out;
    }
    return;
  }
  
    /**
     * Get products by sku
     *
     */
    private function get_product_by_sku( $sku ){
        $args = array();
        $args['post_type']  = array( 'product', 'product_variation' );
        $args['meta_query'] = array(
            array(
                'key'       => '_sku',
                'value'     => $sku,
                'compare'   => 'LIKE'
            )
        );
        $args['posts_per_page'] = $this->limit;
   
        $the_query = new WP_Query( $args );
    
        return $the_query;
  
    }   

        /**
     * Get products by sku
     *
     */
    private function get_product_by_product_title( $title ){

        add_filter( 'posts_search', 'wsm_search_by_title_only', 500, 2 );

        $args = array();
        $args['post_type']      = 'product';
        $args['s']              = $title;
        $args['post_status']    = 'publish';
        $args['orderby']        = 'title';
        $args['order']          = 'ASC';
        $args['posts_per_page'] = $this->limit;

        if( !empty( $_GET['offset'] ) ){
            $offset = $_GET['offset'] - 1;
            $offset = $offset * $this->limit;
            $args['offset'] = $offset;

        }
 
        $the_query = new WP_Query( $args );

        remove_filter( 'posts_search', 'wsm_search_by_title_only' );
    
        return $the_query;
  
    }         
  
  
  
}//End class  