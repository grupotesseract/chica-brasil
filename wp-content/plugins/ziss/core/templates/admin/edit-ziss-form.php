<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $ziss, $current_screen, $pagenow, $post_type;

$cur_tab = 'used';
if ( isset( $_GET['tab'] ) ) {
	$cur_tab = $_GET['tab'];
}

$tabs = array(
	'used'              => esc_html__( 'Used', 'ziss' ),
	'media_lib'         => esc_html__( 'Media Library', 'ziss' ),
	'instagram'         => esc_html__( 'Instagram Images', 'ziss' ),
	'facebook'          => esc_html__( 'Facebook Images', 'ziss' ),
	'shortcode_options' => esc_html__( 'Shortcode Options', 'ziss' ),
);

$tabs_content_html     = '';
$shortcode             = htmlentities2( '[ziss id=' . $post->ID . ']' );
$use_custom_responsive = get_post_meta( $post->ID, 'ziss_use_custom_responsive', true );
if ( trim( $use_custom_responsive ) == 'yes' ) {
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
	$shortcode = '[ziss id=' . $post->ID . ' lg_cols=' . $ziss_items_on_lg . ' md_cols=' . $ziss_items_on_lg . ' md_cols=' . $ziss_items_on_md . ' sm_cols=' . $ziss_items_on_sm . ' xs_cols=' . $ziss_items_on_xs . ' xxs_cols=' . $ziss_items_on_xxs . ']';
	$shortcode = htmlentities2( $shortcode );
}

?>

<script type="text/html" id="zaniss-tmpl">
    <div id="post-body-content" style="position: relative;">
		<?php wp_nonce_field( 'ziss_edit_nonce', 'ziss_edit_nonce' ); ?>
        <div class="zaniss-wrap">
            <div class="zaniss-top">
                <div class="title">
                    <input type="text" name="post_title" class="input-text post-title"
                           placeholder="<?php esc_html_e( 'Enter Title Here', 'ziss' ); ?>"
                           value="<?php esc_html_e( $post->post_title ); ?>">
                </div>
                <div class="shortcode-wrap">
                    <label><?php esc_attr_e( 'Shortcode', 'ziss' ); ?></label>
                    <input type="text" class="ziss-shortcode" readonly
                           value="<?php echo $shortcode; ?>">
                </div>
            </div>
            <div class="zaniss-main">
                <div class="zaniss-main-inner">
                    <div id="tabs-container" class="ziss-tabs" role="tabpanel">
                        <div class="nav-tab-wrapper">
							<?php foreach ( $tabs as $key => $value ) { ?>
								<?php
								$nav_class         = 'nav-tab ziss-nav';
								$tab_content_class = 'tab-content';
								if ( $cur_tab == $key ) {
									$nav_class         .= ' active';
									$tab_content_class .= ' ziss-show';
								} else {
									// $tab_content_class .= ' hide';
								}
								
								$tabs_content_html .= '<div id="tab-' . esc_attr( $key ) . '" class="' . esc_attr( $tab_content_class ) . '">';
								ob_start();
								ziss_require_once( ZISS_CORE . 'templates/admin/tab-content/' . esc_attr( $key ) . '.php' );
								$tabs_content_html .= ob_get_clean();
								$tabs_content_html .= '</div>';
								
								?>
                                <a class="<?php echo esc_attr( $nav_class ); ?>"
                                   href="<?php echo $pagenow . '?post_type=' . $post_type . '&tab=' . esc_attr( $key ); ?>"
                                   data-tab="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
							<?php }; ?>
                        </div>
                        <div class="tab-content-wrapper">
							<?php echo $tabs_content_html; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    jQuery(function ($) {
        $(window).load(function () {
            // Override default UI.
            var form = $($('#zaniss-tmpl').text()).prepend($('#post').children('input[type="hidden"]'));
            
            $('#screen-meta, #screen-meta-links').remove();
            
            $('#post-body > div#post-body-content').replaceWith(form);
            
            // Trigger event to initialize application.
            setTimeout(function () {
                $(document).trigger('init_zaniss_shop');
            }, 500);
            
        });
    });
</script>