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

$ciloe_blog_used_sidebar = ciloe_get_option( 'blog_sidebar', 'sidebar-1' );

/* Blog Layout */
$ciloe_blog_layout = ciloe_get_option( 'ciloe_blog_layout', 'right' );
if ( ! is_active_sidebar( $ciloe_blog_used_sidebar ) ) {
	$ciloe_blog_layout = 'full';
}

/* Blog Style */
$ciloe_blog_style = ciloe_get_option( 'blog-style', 'standard' );

if ( $ciloe_blog_layout == 'full' ) {
	$ciloe_container_class[] = 'blog-page no-sidebar ' . $ciloe_blog_style;
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
		<?php if ( is_search() ) : ?>
            <header class="page-header">
				<?php if ( have_posts() ) : ?>
                    <h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'ciloe' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php else : ?>
                    <h1 class="page-title"><?php echo esc_html__( 'Nothing Found', 'ciloe' ); ?></h1>
				<?php endif; ?>
            </header><!-- .page-header -->
		<?php endif; ?>
        <div class="row">
            <div class="<?php echo esc_attr( implode( ' ', $ciloe_content_class ) ); ?>">
				<?php get_template_part( 'templates/blog/blog', $ciloe_blog_style ); ?>
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
