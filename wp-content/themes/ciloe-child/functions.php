<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ciloe_child_parent_css' ) ):
	function ciloe_child_parent_css() {
		wp_enqueue_style( 'ciloe_child_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(
			'boostrap',
			'owl-carousel',
			'simple-line-icons',
			'flat-icons',
			'scrollbar',
			'chosen',
			'ciloe-custom'
		) );
	}
endif;
add_action( 'wp_enqueue_scripts', 'ciloe_child_parent_css', 10 );
