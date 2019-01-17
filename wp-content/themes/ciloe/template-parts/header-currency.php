<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( shortcode_exists( 'currency_switcher' ) ) {
	echo do_shortcode( '[currency_switcher]' );
}
