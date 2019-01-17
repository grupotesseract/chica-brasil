<?php
/**
 * Template Name: Minimal Page
 *
 * @package    WordPress
 * @subpackage ciloe
 * @since      ciloe 1.0
 */
get_header( 'minimal' );
/* Data MetaBox */
$data_meta = get_post_meta( get_the_ID(), '_custom_page_side_options', true );
$ciloe_page_extra_class = '';
$ciloe_page_layout      = 'left';
$ciloe_page_sidebar     = 'sidebar-1';

if ( !empty( $data_meta ) ) {
	$ciloe_page_extra_class = $data_meta['page_extra_class'];
	$ciloe_page_layout      = $data_meta['sidebar_page_layout'];
	$ciloe_page_sidebar     = $data_meta['page_sidebar'];
}

if ( ! is_active_sidebar( $ciloe_page_sidebar ) ) {
	$ciloe_page_layout = 'full';
}

/*Main container class*/
$ciloe_main_container_class   = array();
$ciloe_main_container_class[] = $ciloe_page_extra_class;
$ciloe_main_container_class[] = 'main-container';
if ( $ciloe_page_layout == 'full' ) {
	$ciloe_main_container_class[] = 'no-sidebar';
} else {
	$ciloe_main_container_class[] = $ciloe_page_layout . '-slidebar';
}
$ciloe_main_content_class   = array();
$ciloe_main_content_class[] = 'main-content';
if ( $ciloe_page_layout == 'full' ) {
	$ciloe_main_content_class[] = 'col-sm-12';
} else {
	$ciloe_main_content_class[] = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
}
$ciloe_slidebar_class   = array();
$ciloe_slidebar_class[] = 'sidebar';
if ( $ciloe_page_layout != 'full' ) {
	$ciloe_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
}
?>
    <main class="site-main <?php echo esc_attr( implode( ' ', $ciloe_main_container_class ) ); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $ciloe_main_content_class ) ); ?>">
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
                            <div class="page-main-content">
								<?php
								the_content();
								wp_link_pages( array(
									               'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'ciloe' ) . '</span>',
									               'after'       => '</div>',
									               'link_before' => '<span>',
									               'link_after'  => '</span>',
									               'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'ciloe' ) . ' </span>%',
									               'separator'   => '<span class="screen-reader-text">, </span>',
								               )
								);
								?>
                            </div>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
							<?php
						}
					}
					?>
                </div>
				<?php if ( $ciloe_page_layout != "full" ): ?>
					<?php if ( is_active_sidebar( $ciloe_page_sidebar ) ) : ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr( implode( ' ', $ciloe_slidebar_class ) ); ?>">
							<?php dynamic_sidebar( $ciloe_page_sidebar ); ?>
                        </div><!-- .widget-area -->
					<?php endif; ?>
				<?php endif; ?>
            </div>
        </div>
    </main>
<?php get_footer( 'minimal' ); ?>