<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $ziss;

$use_custom_responsive = get_post_meta( $post->ID, 'ziss_use_custom_responsive', true );
if ( trim( $use_custom_responsive ) == '' ) {
	$use_custom_responsive = 'no';
}

$items_on_screen_meta_keys_default_vals = array(
	'ziss_items_on_lg'  => 4,
	'ziss_items_on_md'  => 3,
	'ziss_items_on_sm'  => 2,
	'ziss_items_on_xs'  => 2,
	'ziss_items_on_xxs' => 1
);

foreach ( $items_on_screen_meta_keys_default_vals as $items_on_screen_meta_key => $default_items_on_screen ) {
	${$items_on_screen_meta_key} = get_post_meta( $post->ID, $items_on_screen_meta_key, true );
	if ( trim( ${$items_on_screen_meta_key} ) == '' ) {
		${$items_on_screen_meta_key} = $default_items_on_screen;
	}
	${$items_on_screen_meta_key} = max( 1, min( 6, intval( ${$items_on_screen_meta_key} ) ) );
}

?>

<div class="shortcode-options-wrap">
    <h3><?php esc_html_e( 'Responsive Options', 'ziss' ); ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" <?php checked( true, $use_custom_responsive == 'yes' ); ?>
                               id="ziss_use_custom_responsive" name="ziss_use_custom_responsive"
                               value="<?php echo esc_attr( $use_custom_responsive ); ?>"> <?php esc_html_e( 'Custom Responsive', 'ziss' ); ?>
                    </label>
                    <p class="help-block"><?php esc_html_e( 'Check this checkbox to use custom responsive', 'ziss' ); ?></p>
                </div>
            </div>
            <div class="form-group">
                <label for="ziss_items_on_lg"><?php esc_html_e( 'Large Screen', 'ziss' ); ?></label>
                <input type="number" min="1" max="6" class="form-control" id="ziss_items_on_lg" name="ziss_items_on_lg"
                       value="<?php echo $ziss_items_on_lg; ?>">
                <p class="help-block"><?php esc_html_e( 'Number of columns on screen width >= 1200px', 'zanmb' ); ?></p>
            </div>
            <div class="form-group">
                <label for="ziss_items_on_md"><?php esc_html_e( 'Medium Screen', 'ziss' ); ?></label>
                <input type="number" min="1" max="6" class="form-control" id="ziss_items_on_md" name="ziss_items_on_md"
                       value="<?php echo $ziss_items_on_md; ?>">
                <p class="help-block"><?php esc_html_e( 'Number of columns on screen width >= 992px and <= 1199px', 'zanmb' ); ?></p>
            </div>
            <div class="form-group">
                <label for="ziss_items_on_sm"><?php esc_html_e( 'Small Screen', 'ziss' ); ?></label>
                <input type="number" min="1" max="6" class="form-control" id="ziss_items_on_sm" name="ziss_items_on_sm"
                       value="<?php echo $ziss_items_on_sm; ?>">
                <p class="help-block"><?php esc_html_e( 'Number of columns on screen width >= 768px and <= 991px', 'zanmb' ); ?></p>
            </div>
            <div class="form-group">
                <label for="ziss_items_on_xs"><?php esc_html_e( 'Extra Small Screen', 'ziss' ); ?></label>
                <input type="number" min="1" max="6" class="form-control" id="ziss_items_on_xs" name="ziss_items_on_xs"
                       value="<?php echo $ziss_items_on_xs; ?>">
                <p class="help-block"><?php esc_html_e( 'Number of columns on screen width >= 480px and <= 767px', 'zanmb' ); ?></p>
            </div>
            <div class="form-group">
                <label for="ziss_items_on_xxs"><?php esc_html_e( 'Smallest Screen', 'ziss' ); ?></label>
                <input type="number" min="1" max="6" class="form-control" id="ziss_items_on_xxs"
                       name="ziss_items_on_xxs"
                       value="<?php echo $ziss_items_on_xxs; ?>">
                <p class="help-block"><?php esc_html_e( 'Number of columns on screen width <= 479px', 'zanmb' ); ?></p>
            </div>
        </div>
    </div>
</div>
