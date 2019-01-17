<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function famibt_save_all_settings_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'famibt' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! wp_verify_nonce( $nonce, 'famibt_backend_nonce' ) ) {
		$response['message'][] = esc_html__( 'Security check error!', 'famibt' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$all_settings     = isset( $_POST['all_settings'] ) ? $_POST['all_settings'] : array();
	$new_all_settings = array();
	if ( ! empty( $all_settings ) ) {
		foreach ( $all_settings as $setting ) {
			$new_all_settings[ $setting['setting_key'] ] = $setting['setting_val'];
		}
	}
	update_option( 'famibt_all_settings', $new_all_settings );
//	$response['all_settings']     = $all_settings;
//	$response['new_all_settings'] = $new_all_settings;
	
	$response['message'][] = esc_html__( 'All settings saved', 'famibt' );
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_famibt_save_all_settings_via_ajax', 'famibt_save_all_settings_via_ajax' );