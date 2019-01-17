<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$single_id            = ciloe_get_single_page_id();
$meta_data            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
$enable_custom_header = false;
if ( $single_id > 0 && isset( $meta_data['enable_custom_header'] ) ) {
	$enable_custom_header = $meta_data['enable_custom_header'];
}
$enable_topbar = false;
$topbar_text   = '';
if ( $enable_custom_header ) {
	$enable_topbar = $meta_data['enable_topbar'];
	$topbar_text   = $meta_data['topbar-text'];
} else {
	$enable_topbar = ciloe_get_option( 'enable_topbar', false );
	$topbar_text   = ciloe_get_option( 'topbar-text', '' );
}

?>

<?php if ( $enable_topbar && trim( $topbar_text ) != '' ): ?>
    <div class="header-topbar"><?php echo esc_attr( $topbar_text ); ?></div>
<?php endif; ?>