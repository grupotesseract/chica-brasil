<?php
include_once( CILOE_TOOLKIT_PATH . '/includes/classes/welcome.php' );
include_once( CILOE_TOOLKIT_PATH . '/includes/core/cs-framework.php' );
/* MAILCHIP */
include_once( 'classes/mailchimpv3/MCAPI.class.php' );
include_once( 'classes/mailchimpv3/mailchimp-settings.php' );
include_once( 'classes/mailchimpv3/MCAPI.class.v3.client.php' );
include_once( 'classes/mailchimpv3/mailchimp.php' );
/* WIDGET */

include_once('widgets/widget-instagram.php');
include_once('widgets/widget-newsletter.php');
include_once('widgets/widget-latest-posts.php');
include_once('widgets/widget-socials.php');

if ( !function_exists( 'ciloe_toolkit_vc_param' ) ) {
	function ciloe_toolkit_vc_param( $key = false, $value = false )
	{
		if ( !class_exists( 'Vc_Manager' ) ) {
			return;
		}

		return vc_add_shortcode_param( $key, $value );
	}

	add_action( 'init', 'ciloe_toolkit_vc_param' );
}

function ciloe_toolkit_after_setup_theme() {
	require_once CILOE_TOOLKIT_PATH . 'includes/classes/import/import.php';
	
	/**
	 * Compress HTML output
	 */
	require_once CILOE_TOOLKIT_PATH . '/includes/classes/WP_HTML_Compression.php';
}
add_action('after_setup_theme', 'ciloe_toolkit_after_setup_theme');