<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

class Stock_Manager_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = Stock_Manager::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		add_action( 'admin_init', array( $this, 'output_buffer' ) );
    
		include_once( 'includes/wcm-class-stock.php' );
        include_once( 'includes/wcm-class-table.php' );

    
    	//$this->includes();
    
    	add_action( 'admin_init', array( $this, 'generate_csv_file' ) );
    
    
    
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
	 * Include required core files used in admin.
	 * 
	 * @since     1.0.0      
	 */
	private function includes() {
    	//if( isset( $_GET['page'] ) && ( $_GET['page'] == 'stock-manager' || $_GET['page'] == 'stock-manager-import-export' ) ){
        	
    	//}
  
  	}
  
  	/**
	 * Get stock class
	 * @return WCM_Stock
	 * 
	 * @since     1.0.0      
	 */
	public function stock() {
		return WCM_Stock::get_instance();
    
    
	}
  
	/**
	 * Register and enqueue admin-specific CSS.
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
    if( isset( $_GET['page'] ) && ( $_GET['page'] == 'stock-manager' || $_GET['page'] == 'stock-manager-import-export' ) ){
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Stock_Manager::VERSION );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
     if( isset( $_GET['page'] ) && ( $_GET['page'] == 'stock-manager' || $_GET['page'] == 'stock-manager-import-export' ) ){

     		$params = array(
  				'ajax_nonce' => wp_create_nonce('wsm_update'),
			);
			wp_localize_script( $this->plugin_slug . '-admin-script', 'ajax_object', $params );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Stock_Manager::VERSION );
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$value = 'manage_woocommerce';

		$manage = apply_filters( 'stock_manager_manage', $value );

		add_menu_page(
			__( 'WooCommerce Stock Manager', $this->plugin_slug ),
			__( 'WooCommerce Stock Manager', $this->plugin_slug ),
			$manage,
			'stock-manager',
			array( $this, 'display_plugin_admin_page' ),
			'dashicons-book-alt'
		);
    	
    	add_submenu_page(
			'stock-manager',
      		__( 'Import/Export', $this->plugin_slug ),
			__( 'Import/Export', $this->plugin_slug ),
			$manage,
			'stock-manager-import-export',
			array( $this, 'display_import_export_page' )
		);
		
		add_submenu_page(
			'stock-manager',
      		__( 'Setting', $this->plugin_slug ),
			__( 'Setting', $this->plugin_slug ),
			$manage,
			'stock-manager-setting',
			array( $this, 'display_setting_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
  	/**
	 * Render the impoer export page for this plugin.
	 *
	 * @since    1.0.0
	 */
  	
	public function display_import_export_page() {
		include_once( 'views/import-export.php' );
	}
	
	/**
	 * Render the setting page for this plugin.
	 *
	 * @since    1.2.2
	 */
	public function display_setting_page() {
		include_once( 'views/setting.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=stock-manager' ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

  	/**
  	 * Create csv array and download cvs file
  	 *
  	 * @since 1.0.0  
 	 */        
  	public function stock_convert_to_csv($input_array, $output_file_name, $delimiter){
    
    	$temp_memory = fopen('php://memory', 'w');
    
    	foreach ($input_array as $line) {
        	fputcsv($temp_memory, $line, $delimiter);
    	}
    	fseek($temp_memory, 0);
    
    	header('Content-Type: application/csv');
    	header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    
    	fpassthru($temp_memory);
    	exit();
  	}
  
  
  	/**
  	 * Generate csv data
  	 *
  	 * @since 1.0.0  
  	 */        
  	public function generate_csv_file(){

    if(isset($_GET['action']) && $_GET['action'] == 'export'){
    
    	$stock = $this->stock();
    
      $array_to_csv = array();
      //First line
      $array_to_csv[] = array('id','sku','Product name','Manage stock','Stock status','Backorders','Stock','Type','Parent ID'); 
       
      $products = $stock->get_products_for_export(); 
      
      foreach( $products as $item ){ 
        $product_meta = get_post_meta($item->ID);
        $item_product = wc_get_product($item->ID);
        $product_type = $item_product->get_type();
        $id = $item->ID;
        if(!empty($product_meta['_sku'][0])){          $sku = $product_meta['_sku'][0]; }else{ $sku = ''; }
        $product_name = $item->post_title;
        if(!empty($product_meta['_manage_stock'][0])){ $manage_stock = $product_meta['_manage_stock'][0]; }else{ $manage_stock = ''; }
        if(!empty($product_meta['_stock_status'][0])){ $stock_status = $product_meta['_stock_status'][0]; }else{ $stock_status = ''; }
        if(!empty($product_meta['_backorders'][0])){   $backorders = $product_meta['_backorders'][0]; }else{ $backorders = ''; }
        if(!empty($product_meta['_stock'][0])){        $stock = $product_meta['_stock'][0]; }else{ $stock = '0'; }
        if($product_type == 'variable'){               $product_type = 'variable'; }else{ $product_type = 'simple'; }
        
        $array_to_csv[] = array($id,$sku,$product_name,$manage_stock,$stock_status,$backorders,$stock,$product_type,'');
       
        if($product_type == 'variable'){
                $args = array(
	               'post_parent' => $item->ID,
	               'post_type'   => 'product_variation', 
	               'numberposts' => -1,
	               'post_status' => 'publish' 
                ); 
                $variations_array = get_children( $args );
                foreach($variations_array as $vars){
             
                    $var_meta = get_post_meta($vars->ID);
                    $item_product = wc_get_product($vars->ID);
       
                    $id = $vars->ID;
                    if(!empty($var_meta['_sku'][0])){          $sku = $var_meta['_sku'][0]; }else{ $sku = ''; }
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
                    
                    if(!empty($var_meta['_manage_stock'][0])){ $manage_stock = $var_meta['_manage_stock'][0]; }else{ $manage_stock = ''; }
                    if(!empty($var_meta['_stock_status'][0])){ $stock_status = $var_meta['_stock_status'][0]; }else{ $stock_status = ''; }
                    if(!empty($var_meta['_backorders'][0])){   $backorders = $var_meta['_backorders'][0]; }else{ $backorders = ''; }
                    if(!empty($var_meta['_stock'][0])){        $stock = $var_meta['_stock'][0]; }else{ $stock = '0'; }
                    $product_type = 'product-variant';
                    $parent_id = $item->ID; 
                    
                    $array_to_csv[] = array($id,$sku,$product_name,$manage_stock,$stock_status,$backorders,$stock,$product_type,$parent_id);                    
                    
                }
         }
        } 
        
        
        $this->stock_convert_to_csv($array_to_csv, 'stock-manager-export.csv', ';');
        
      }
  }

	/**
	 * Headers allready sent fix
	 *
	 */        
  public function output_buffer() {
		ob_start();
  } 
  

}//End class
