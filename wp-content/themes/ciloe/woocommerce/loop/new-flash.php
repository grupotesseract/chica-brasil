<?php
/**
 * Product loop new flash
 *
 * @author     Famithemes
 * @package    ciloe
 * @version     1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;
$postdate      = get_the_time( 'Y-m-d' );            // Post date
$postdatestamp = strtotime( $postdate );            // Timestamped post date
$newness       = ciloe_get_option( 'product_newness', 7 );    // Newness in days as defined by option
?>
<?php if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) : ?>
	<?php echo apply_filters( 'woocommerce_new_flash', '<span class="onnew"><span class="text">' . esc_html__( 'New', 'ciloe' ) . '</span></span>', $post, $product ); ?>
<?php endif; ?>

