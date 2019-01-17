<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ZanissPostType' ) ) {
	class ZanissPostType {
		public function __construct() {
			add_action( 'init', array( $this, 'initialize' ), 99 );
		}
		
		public static function initialize() {
			$args = array(
				'labels'              => array(
					'name'          => __( 'Product Pin', 'ziss' ),
					'singular_name' => __( 'Product Pin', 'ziss' ),
					'add_new'       => __( 'Add New', 'ziss' ),
					'add_new_item'  => __( 'Add new Product Pin', 'ziss' ),
					'edit_item'     => __( 'Edit Product Pin', 'ziss' ),
					'new_item'      => __( 'New Product Pin', 'ziss' ),
					'view_item'     => __( 'View Product Pin', 'ziss' ),
					'menu_name'     => __( 'Product Pin', 'ziss' ),
				),
				'supports'            => array( 'page-attributes' ),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				// 'show_in_menu'        => '',
				'menu_position'       => 40,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'post',
			);
			register_post_type( 'ziss', $args );
			
			
			// Check if zaniss page is requested.
			global $pagenow, $post_type, $post;
			
			if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
				// Get current post type.
				if ( ! isset( $post_type ) ) {
					$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : null;
				}
				
				if ( empty( $post_type ) && ( isset( $post ) || isset( $_REQUEST['post'] ) ) ) {
					$post_type = isset( $post ) ? $post->post_type : get_post_type( $_REQUEST['post'] );
				}
				
				if ( 'ziss' == $post_type ) {
					add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ), 9999 );
					
					if ( 'edit.php' == $pagenow ) {
						// Register necessary actions / filters to customize All Items screen.
						add_filter( 'bulk_actions-edit-ziss', array( __CLASS__, 'bulk_actions' ) );
						
						add_filter( 'manage_ziss_posts_columns', array( __CLASS__, 'register_columns' ) );
						add_action( 'manage_posts_custom_column', array( __CLASS__, 'display_columns' ), 10, 2 );
					} else if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
						if ( ! isset( $_REQUEST['action'] ) || 'trash' != $_REQUEST['action'] ) {
							// Register necessary actions / filters to override Item Details screen.
							add_action( 'admin_footer', array( __CLASS__, 'load_edit_form' ) );
							add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
						}
					}
				}
			}
			
			// Register Ajax actions / filters.
			//add_filter('woocommerce_json_search_found_products', array(__CLASS__, 'search_products'));
			
		}
		
		/**
		 * Setup bulk actions for in stock alert subscription screen.
		 *
		 * @param   array $actions Current actions.
		 *
		 * @return  array
		 */
		public static function bulk_actions( $actions ) {
			// Remove edit action.
			unset( $actions['edit'] );
			
			return $actions;
		}
		
		/**
		 * Register columns for in stock alert subscription screen.
		 *
		 * @param   array $columns Current columns.
		 *
		 * @return  array
		 */
		public static function register_columns( $columns ) {
			$columns = array(
				'cb'        => '<input type="checkbox" />',
				'title'     => __( 'Name', 'ziss' ),
				'images'    => __( 'Images', 'ziss' ),
				'shortcode' => __( 'Shortcode', 'ziss' ),
				'date'      => __( 'Time', 'ziss' ),
			);
			
			return $columns;
		}
		
		/**
		 * Display columns for in stock alert subscription screen.
		 *
		 * @param   array $column  Column to display content for.
		 * @param   int   $post_id Post ID to display content for.
		 *
		 * @return  array
		 */
		public static function display_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'images' :
					
					break;
				
				case 'shortcode' :
					?>
                    <span>[ziss id="<?php echo absint( $post_id ); ?>"]</span>
					<?php
					break;
			}
		}
		
		/**
		 * Enqueue assets for custom add/edit item form.
		 *
		 * @return  string
		 */
		public static function enqueue_assets() {
			// Check if WR Mapper page is requested.
			global $pagenow, $post_type;
			wp_dequeue_script( 'select2' );
			
			if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
				if ( 'ziss' == $post_type ) {
					
					if ( 'edit.php' == $pagenow ) {
						// Register action to print inline initialization script.
						add_action( 'admin_print_footer_scripts', array( __CLASS__, 'print_footer_scripts' ) );
					} else if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
						// Enqueue media.
						wp_enqueue_media();
					}
				}
			}
		}
		
		/**
		 * Method to print inline initialization script for items list screen.
		 *
		 * @return  void
		 */
		public static function print_footer_scripts() {
			?>
            <script type="text/javascript">
                jQuery(function ($) {
                    console.log('Footer edit script is running...');
                });
            </script>
			<?php
		}
		
		/**
		 * Load custom add/edit item form.
		 *
		 * @return  void
		 */
		public static function load_edit_form() {
			// Load template file.
			require_once ZISS_CORE . 'templates/admin/edit-ziss-form.php';
		}
		
		/**
		 * Save custom post type extra data.
		 *
		 * @param   int $id Current post ID.
		 *
		 * @return  void
		 */
		public static function save_post( $id ) {
			
			// Publish post if needed.
			if ( ! defined( 'DOING_AUTOSAVE' ) || ! DOING_AUTOSAVE ) {
				$post = get_post( $id );
				
				if ( __( 'Auto Draft' ) != $post->post_title && 'publish' != $post->post_status ) {
					wp_publish_post( $post );
				}
			}
			
			// Check Autosave
			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
				return $id;
			}
			// Don't save if only a revision
			if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
				return $id;
			}
			// Check permissions
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return $id;
			}
			
			if ( ! isset( $_POST['ziss_edit_nonce'] ) ) {
				return $id;
			}
			
			if ( ! wp_verify_nonce( $_POST['ziss_edit_nonce'], 'ziss_edit_nonce' ) ) {
				return $id;
			}
			
			if ( isset( $_POST['social_shop_pin'] ) ) {
				$pin_data = $_POST['social_shop_pin'];
				update_post_meta( $id, 'ziss_pin_data', $pin_data );
			} else {
				delete_post_meta( $id, 'ziss_pin_data' );
			}
			
			// Save shortcode options
			if ( isset( $_POST['ziss_use_custom_responsive'] ) ) {
				$use_custom_responsive = $_POST['ziss_use_custom_responsive'];
				update_post_meta( $id, 'ziss_use_custom_responsive', $use_custom_responsive );
			} else {
				update_post_meta( $id, 'ziss_use_custom_responsive', 'no' );
			}
			
			$items_on_screen_meta_keys = array(
				'ziss_items_on_lg',
				'ziss_items_on_md',
				'ziss_items_on_sm',
				'ziss_items_on_xs',
				'ziss_items_on_xxs'
			);
			
			foreach ( $items_on_screen_meta_keys as $items_on_screen_meta_key ) {
				if ( isset( $_POST[ $items_on_screen_meta_key ] ) ) {
					$items_on_screen = max( 1, min( 6, intval( $_POST[ $items_on_screen_meta_key ] ) ) );
					update_post_meta( $id, $items_on_screen_meta_key, $items_on_screen );
				}
			}
		}
		
	}
	
	new ZanissPostType();
}