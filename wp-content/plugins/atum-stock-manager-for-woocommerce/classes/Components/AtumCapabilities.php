<?php
/**
 * Add capabilities to WP user roles
 *
 * @package        Atum
 * @subpackage     Components
 * @author         Be Rebel - https://berebel.io
 * @copyright      ©2019 Stock Management Labs™
 *
 * @since          1.3.1
 */

namespace Atum\Components;

defined( 'ABSPATH' ) || die;


class AtumCapabilities {

	/**
	 * The singleton instance holder
	 *
	 * @var AtumCapabilities
	 */
	private static $instance;

	/**
	 * List of custom ATUM capabilities
	 *
	 * @var array
	 */
	private $capabilities = array(

		// Purchase price caps.
		'edit_purchase_price',
		'view_purchase_price',

		// Purchase Orders caps.
		'edit_purchase_order',
		'read_purchase_order',
		'delete_purchase_order',
		'edit_purchase_orders',
		'edit_others_purchase_orders',
		'create_purchase_orders',
		'delete_purchase_orders',
		'delete_other_purchase_orders',

		// Inventory Logs caps.
		'edit_inventory_log',
		'read_inventory_log',
		'delete_inventory_log',
		'edit_inventory_logs',
		'edit_others_inventory_logs',
		'create_inventory_logs',
		'delete_inventory_logs',
		'delete_other_inventory_logs',

		// Inbound Stock caps.
		'read_inbound_stock',

		// Out Stock Threshold.
		'edit_out_stock_threshold',

		// Suppliers caps.
		'edit_supplier',
		'read_supplier',
		'delete_supplier',
		'edit_suppliers',
		'edit_others_suppliers',
		'publish_suppliers',
		'read_private_suppliers',
		'create_suppliers',
		'delete_suppliers',
		'delete_private_suppliers',
		'delete_published_suppliers',
		'delete_other_suppliers',
		'edit_private_suppliers',
		'edit_published_suppliers',

		// ATUM menus caps.
		'view_admin_menu',
		'view_admin_bar_menu',

		// ATUM Order notes caps.
		'read_order_notes',
		'create_order_notes',
		'delete_order_notes',

		// Other caps.
		'export_data',
		'view_statistics',
	);
	
	/**
	 * Capabilities used in WP_Query when searching for more than one post type not included by default in WordPress.
	 *
	 * @var array
	 */
	private $wp_capabilities = array(
		'edit_others_multiple_post_types',
		'read_private_multiple_post_types',
	);

	/**
	 * Singleton constructor
	 *
	 * @since 1.3.1
	 */
	private function __construct() {

		// Add the ATUM prefix to all the capabilities.
		$this->capabilities = array_merge( preg_filter( '/^/', ATUM_PREFIX, $this->capabilities ), $this->wp_capabilities );

		$admin_roles = (array) apply_filters( 'atum/capabilities/admin_roles', [ get_role( 'administrator' ) ] );

		foreach ( $admin_roles as $admin_role ) {

			if ( is_a( $admin_role, '\WP_Role' ) ) {
				foreach ( $this->capabilities as $cap ) {
					$admin_role->add_cap( $cap );
				}
			}

		}

	}

	/**
	 * Check whether the current user has ATUM capabilities
	 *
	 * @since 1.3.6
	 *
	 * @param string $capability
	 *
	 * @return bool
	 */
	public static function current_user_can( $capability ) {
		return current_user_can( ATUM_PREFIX . $capability );
	}


	/*******************
	 * Instance methods
	 *******************/

	/**
	 * Cannot be cloned
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', ATUM_TEXT_DOMAIN ), '1.0.0' );
	}

	/**
	 * Cannot be serialized
	 */
	public function __sleep() {
		_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', ATUM_TEXT_DOMAIN ), '1.0.0' );
	}

	/**
	 * Get Singleton instance
	 *
	 * @return AtumCapabilities instance
	 */
	public static function get_instance() {

		if ( ! ( self::$instance && is_a( self::$instance, __CLASS__ ) ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
