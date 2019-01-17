<?php
/**
 * @version    1.0
 * @package    Ciloe_Toolkit
 * @author     FamiThemes
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if ( !class_exists( 'Ciloe_Toolkit_Posttype' ) ) {
	class Ciloe_Toolkit_Posttype
	{

		public function __construct()
		{
			add_action( 'init', array( &$this, 'init' ), 9999 );
		}

		public static function init()
		{
			/*Mega menu */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Mega Builder', 'ciloe-toolkit' ),
					'singular_name'      => __( 'Mega menu item', 'ciloe-toolkit' ),
					'add_new'            => __( 'Add new', 'ciloe-toolkit' ),
					'add_new_item'       => __( 'Add new menu item', 'ciloe-toolkit' ),
					'edit_item'          => __( 'Edit menu item', 'ciloe-toolkit' ),
					'new_item'           => __( 'New menu item', 'ciloe-toolkit' ),
					'view_item'          => __( 'View menu item', 'ciloe-toolkit' ),
					'search_items'       => __( 'Search menu items', 'ciloe-toolkit' ),
					'not_found'          => __( 'No menu items found', 'ciloe-toolkit' ),
					'not_found_in_trash' => __( 'No menu items found in trash', 'ciloe-toolkit' ),
					'parent_item_colon'  => __( 'Parent menu item:', 'ciloe-toolkit' ),
					'menu_name'          => __( 'Menu Builder', 'ciloe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'Mega Menus.', 'ciloe-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'ciloe_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'megamenu', $args );

			/* Footer */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Footers', 'ciloe-toolkit' ),
					'singular_name'      => __( 'Footers', 'ciloe-toolkit' ),
					'add_new'            => __( 'Add New', 'ciloe-toolkit' ),
					'add_new_item'       => __( 'Add new footer', 'ciloe-toolkit' ),
					'edit_item'          => __( 'Edit footer', 'ciloe-toolkit' ),
					'new_item'           => __( 'New footer', 'ciloe-toolkit' ),
					'view_item'          => __( 'View footer', 'ciloe-toolkit' ),
					'search_items'       => __( 'Search template footer', 'ciloe-toolkit' ),
					'not_found'          => __( 'No template items found', 'ciloe-toolkit' ),
					'not_found_in_trash' => __( 'No template items found in trash', 'ciloe-toolkit' ),
					'parent_item_colon'  => __( 'Parent template item:', 'ciloe-toolkit' ),
					'menu_name'          => __( 'Footer Builder', 'ciloe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Footer.', 'ciloe-toolkit' ),
				'supports'            => array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'ciloe_menu',
				'menu_position'       => 4,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);
			register_post_type( 'footer', $args );
			/*NewsLetter */
			$args = array(
				'labels'              => array(
					'name'               => __( 'NewsLetter', 'ciloe-toolkit' ),
					'singular_name'      => __( 'NewsLetter item', 'ciloe-toolkit' ),
					'add_new'            => __( 'Add new', 'ciloe-toolkit' ),
					'add_new_item'       => __( 'Add new NewsLetter', 'ciloe-toolkit' ),
					'edit_item'          => __( 'Edit NewsLetter', 'ciloe-toolkit' ),
					'new_item'           => __( 'New NewsLetter', 'ciloe-toolkit' ),
					'view_item'          => __( 'View NewsLetter', 'ciloe-toolkit' ),
					'search_items'       => __( 'Search NewsLetter', 'ciloe-toolkit' ),
					'not_found'          => __( 'No NewsLetter found', 'ciloe-toolkit' ),
					'not_found_in_trash' => __( 'No NewsLetter found in trash', 'ciloe-toolkit' ),
					'parent_item_colon'  => __( 'Parent NewsLetter:', 'ciloe-toolkit' ),
					'menu_name'          => __( 'NewsLetter Builder', 'ciloe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template NewsLetter.', 'ciloe-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'ciloe_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'newsletter', $args );

			/*Size Guide */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Size Guide', 'ciloe-toolkit' ),
					'singular_name'      => __( 'Size Guide item', 'ciloe-toolkit' ),
					'add_new'            => __( 'Add new', 'ciloe-toolkit' ),
					'add_new_item'       => __( 'Add new Size Guide', 'ciloe-toolkit' ),
					'edit_item'          => __( 'Edit Size Guide', 'ciloe-toolkit' ),
					'new_item'           => __( 'New Size Guide', 'ciloe-toolkit' ),
					'view_item'          => __( 'View Size Guide', 'ciloe-toolkit' ),
					'search_items'       => __( 'Search Size Guide', 'ciloe-toolkit' ),
					'not_found'          => __( 'No Size Guide found', 'ciloe-toolkit' ),
					'not_found_in_trash' => __( 'No Size Guide found in trash', 'ciloe-toolkit' ),
					'parent_item_colon'  => __( 'Parent Size Guide:', 'ciloe-toolkit' ),
					'menu_name'          => __( 'Size Guide Builder', 'ciloe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Size Guide.', 'ciloe-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'ciloe_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'sizeguide', $args ); 
            /* Project */

            $labels = array(
                'name'               => _x( 'Project', 'ciloe-toolkit' ),
                'singular_name'      => _x( 'Project', 'ciloe-toolkit' ),
                'add_new'            => __( 'Add New', 'ciloe-toolkit' ),
                'all_items'          => __( 'Projects', 'ciloe-toolkit' ),
                'add_new_item'       => __( 'Add New Project', 'ciloe-toolkit' ),
                'edit_item'          => __( 'Edit Project', 'ciloe-toolkit' ),
                'new_item'           => __( 'New Project', 'ciloe-toolkit' ),
                'view_item'          => __( 'View Project', 'ciloe-toolkit' ),
                'search_items'       => __( 'Search Project', 'ciloe-toolkit' ),
                'not_found'          => __( 'No Project found', 'ciloe-toolkit' ),
                'not_found_in_trash' => __( 'No Project found in Trash', 'ciloe-toolkit' ),
                'parent_item_colon'  => __( 'Parent Project', 'ciloe-toolkit' ),
                'menu_name'          => __( 'Projects', 'ciloe-toolkit' ),
            );
            $args   = array(
                'labels'              => $labels,
                'description'         => 'Post type Project',
                'supports'            => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                ),
                'hierarchical'        => false,
                'rewrite'             => true,
                'public'              => true,
                'show_ui'             => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 4,
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'post',
                'menu_icon'           => 'dashicons-images-alt2',
            );

            //register_post_type( 'project', $args );
			
		}
	}

	new Ciloe_Toolkit_Posttype();
}
