<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ciloe
 */
?>
<?php get_header(); ?>
<?php

/* Blog Layout */
$ciloe_blog_layout = ciloe_get_option( 'ciloe_blog_layout', 'right' );

/* Blog Style */
$ciloe_container_class   = array();
$ciloe_container_class[] = 'search-page';
if ( $ciloe_blog_layout == 'full' ) {
	$ciloe_container_class[] = 'no-sidebar';
} else {
	$ciloe_container_class[] = $ciloe_blog_layout . '-sidebar has-sidebar blog-page';
}

$ciloe_content_class   = array();
$ciloe_content_class[] = 'main-content';
if ( $ciloe_blog_layout == 'full' ) {
	$ciloe_content_class[] = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
} else {
	$ciloe_content_class[] = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
}
$ciloe_slidebar_class   = array();
$ciloe_slidebar_class[] = 'sidebar';
if ( $ciloe_blog_layout != 'full' ) {
	$ciloe_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
}

?>
    <div class="<?php echo esc_attr( implode( ' ', $ciloe_container_class ) ); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $ciloe_content_class ) ); ?>">
					<?php get_template_part( 'templates/blog/blog', 'search' ); ?>
                </div>
				<?php if ( $ciloe_blog_layout != "full" ): ?>
                    <div class="<?php echo esc_attr( implode( ' ', $ciloe_slidebar_class ) ); ?>">
						<?php get_sidebar(); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>