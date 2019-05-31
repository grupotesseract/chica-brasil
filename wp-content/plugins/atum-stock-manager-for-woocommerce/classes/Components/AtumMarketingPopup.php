<?php
/**
 * Add Marketing Popup
 *
 * @package        Atum
 * @subpackage     Components
 * @author         Be Rebel - https://berebel.io
 * @copyright      ©2019 Stock Management Labs™
 *
 * @since          1.5.3
 */

namespace Atum\Components;

defined( 'ABSPATH' ) || die;

use Atum\Inc\Helpers;


class AtumMarketingPopup {

	/**
	 * The marketing popup title
	 *
	 * @var object
	 */
	protected $title = [];

	/**
	 * The marketing popup description
	 *
	 * @var object
	 */
	protected $description = [];

	/**
	 * The marketing popup version
	 *
	 * @var object
	 */
	protected $version = [];

	/**
	 * The marketing popup buttons
	 *
	 * @var array
	 */
	protected $buttons = [];

	/**
	 * The marketing popup images
	 *
	 * @var object
	 */
	protected $images = [];

	/**
	 * The marketing popup background
	 *
	 * @var object
	 */
	protected $background = [];

	/**
	 * The marketing popup dash background
	 *
	 * @var object
	 */
	protected $dash_background = [];

	/**
	 * The hide popup transient key
	 *
	 * @var string
	 */
	protected $transient_key = '';

	/**
	 * Was the marketing popup content loaded?
	 *
	 * @var bool
	 */
	protected $loaded = FALSE;

	/**
	 * The ATUM's addons store URL
	 */
	const MARKETING_POPUP_STORE_URL = 'https://www.stockmanagementlabs.com/';

	/**
	 * The ATUM's addons API endpoint
	 */
	const MARKETING_POPUP_API_ENDPOINT = 'marketing-popup-api';

	/**
	 * The singleton instance holder
	 *
	 * @var AtumMarketingPopup
	 */
	private static $instance;


	/**
	 * Singleton constructor
	 *
	 * @since 1.5.3
	 */
	private function __construct() {

		// Call marketing popup info.
		$marketing_popup = $this->get_marketing_popup_content();

		if ( ! empty( $marketing_popup ) /*200 === wp_remote_retrieve_response_code( $marketing_popup )*/ ) {

			/*$marketing_popup = json_decode( wp_remote_retrieve_body( $marketing_popup ) );*/

			/*if ( $marketing_popup ) {*/

			// Check if background params exist.
			$background_data      = isset( $marketing_popup->background ) ? $marketing_popup->background : [];
			$dash_background_data = isset( $marketing_popup->dash_background ) ? $marketing_popup->dash_background : [];

			if ( ! empty( $background_data ) ) {

				$background_color    = isset( $background_data->bg_color ) ? $background_data->bg_color : '';
				$background_image    = isset( $background_data->bg_image ) ? $background_data->bg_image : '';
				$background_position = isset( $background_data->bg_position ) ? $background_data->bg_position : '';
				$background_size     = isset( $background_data->bg_size ) ? $background_data->bg_size : '';
				$background_repeat   = isset( $background_data->bg_repeat ) ? $background_data->bg_repeat : '';

				$this->background = "$background_color $background_image $background_position/$background_size $background_repeat";

			}

			if ( ! empty( $dash_background_data ) ) {

				$background_color    = isset( $dash_background_data->bg_color ) ? $dash_background_data->bg_color : '';
				$background_image    = isset( $dash_background_data->bg_image ) ? $dash_background_data->bg_image : '';
				$background_position = isset( $dash_background_data->bg_position ) ? $dash_background_data->bg_position : '';
				$background_size     = isset( $dash_background_data->bg_size ) ? $dash_background_data->bg_size : '';
				$background_repeat   = isset( $dash_background_data->bg_repeat ) ? $dash_background_data->bg_repeat : '';

				$this->dash_background = "$background_color $background_image $background_position/$background_size $background_repeat;";

			}

			// Add attributes to marketing popup.
			$this->images        = isset( $marketing_popup->images ) ? $marketing_popup->images : [];
			$this->title         = isset( $marketing_popup->title ) ? $marketing_popup->title : '';
			$this->description   = isset( $marketing_popup->description ) ? $marketing_popup->description : [];
			$this->version       = isset( $marketing_popup->version ) ? $marketing_popup->version : [];
			$this->buttons       = isset( $marketing_popup->buttons ) ? $marketing_popup->buttons : [];
			$this->transient_key = isset( $marketing_popup->transient_key ) ? $marketing_popup->transient_key : '';

			/*}*/

			$this->loaded = TRUE;

		}

	}

	/**
	 * Get marketing popup content
	 *
	 * @since 1.5.3
	 *
	 * @return array|\WP_Error
	 */
	private function get_marketing_popup_content() {

		// Until we find a solution for the API calls limit, we will use get the JSON locally.
		return json_decode( file_get_contents( ATUM_PATH . 'includes/marketing-popup-content.json' ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		/*$request_params = array(
			'method'      => 'POST',
			'timeout'     => 15,
			'redirection' => 1,
			'httpversion' => '1.0',
			'user-agent'  => 'ATUM/' . ATUM_VERSION . ';' . home_url(),
			'blocking'    => TRUE,
			'headers'     => array(),
			'body'        => array(),
			'cookies'     => array(),
		);

		// Call marketing popup info.
		return wp_remote_post( self::MARKETING_POPUP_STORE_URL . self::MARKETING_POPUP_API_ENDPOINT, $request_params );*/

	}

	/**
	 * Enqueue the Marketing popup scripts if needed
	 *
	 * @since 1.5.3.2
	 */
	public static function maybe_enqueue_scripts() {

		if ( Helpers::show_marketing_popup() ) {

			$marketing_popup_vars = array(
				'nonce' => wp_create_nonce( 'atum-marketing-popup-nonce' ),
			);

			wp_register_style( 'atum-marketing-popup', ATUM_URL . 'assets/css/atum-marketing-popup.css', array(), ATUM_VERSION );
			wp_register_script( 'atum-marketing-popup', ATUM_URL . 'assets/js/build/atum-marketing-popup.js', array( 'jquery', 'sweetalert2' ), ATUM_VERSION, TRUE );
			wp_localize_script( 'atum-marketing-popup', 'atumMarketingPopupVars', $marketing_popup_vars );

			wp_enqueue_style( 'atum-marketing-popup' );
			wp_enqueue_script( 'atum-marketing-popup' );

		}

	}

	/**
	 * Getter for the title
	 *
	 * @since 1.5.3
	 *
	 * @return object
	 */
	public function get_title() {

		return $this->title;
	}

	/**
	 * Getter for the text
	 *
	 * @since 1.5.3
	 *
	 * @return object
	 */
	public function get_description() {

		return $this->description;
	}

	/**
	 * Getter for the version
	 *
	 * @since 1.5.3
	 *
	 * @return object
	 */
	public function get_version() {

		return $this->version;
	}

	/**
	 * Getter for the buttons
	 *
	 * @since 1.5.3
	 *
	 * @return object
	 */
	public function get_buttons() {

		return $this->buttons;
	}

	/**
	 * Getter for the images
	 *
	 * @since 1.5.3
	 *
	 * @return string
	 */
	public function get_images() {

		return $this->images;
	}

	/**
	 * Getter for the background
	 *
	 * @since 1.5.3
	 *
	 * @return object
	 */
	public function get_background() {

		return $this->background;

	}

	/**
	 * Getter for the dash_background
	 *
	 * @since 1.5.3
	 *
	 * @return object
	 */
	public function get_dash_background() {

		return $this->dash_background;

	}

	/**
	 * Getter for the transient key
	 *
	 * @since 1.5.3
	 *
	 * @return string
	 */
	public function get_transient_key() {

		return $this->transient_key;

	}

	/**
	 * Getter for the loaded prop
	 *
	 * @return bool
	 */
	public function is_loaded() {
		return $this->loaded;
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
	 * @return AtumMarketingPopup instance
	 */
	public static function get_instance() {

		if ( ! ( self::$instance && is_a( self::$instance, __CLASS__ ) ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
