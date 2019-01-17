<?php
$ciloe_blog_used_sidebar = ciloe_get_option( 'blog_sidebar', 'sidebar-1' );
if ( is_single() ) {
	$ciloe_blog_used_sidebar = ciloe_get_option( 'single_post_sidebar', 'sidebar-1' );
}

?>
<?php if ( is_active_sidebar( $ciloe_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area sidebar-blog">
		<?php dynamic_sidebar( $ciloe_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>