<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$show_post_author   = ciloe_get_option( 'show_post_author', true );
$show_related_posts = ciloe_get_option( 'show_single_related_posts', true );
$show_tags = ciloe_get_option( 'post-meta-tags', 'yes' );
?>

<article <?php post_class( 'post-item post-single' ); ?>>
    <div class="post-info">
        <div class="post-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				             esc_html__( 'Continue reading %s', 'ciloe' ),
				             the_title( '<span class="screen-reader-text">', '</span>', false )
			             )
			);
			wp_link_pages( array(
				               'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'ciloe' ) . '</span>',
				               'after'       => '</div>',
				               'link_before' => '<span>',
				               'link_after'  => '</span>',
			               ) );
			?>
        </div>
        <?php if ( $show_tags == 'yes' && has_tag() ) { ?>
            <div class="tag-post">
            	<span class="title-tag"><?php echo esc_html__( 'Tags:', 'ciloe' ); ?></span>
				<?php the_tags( ' ', ', ' ); ?>
            </div>
		<?php }; ?>
    </div>
	
	<?php get_template_part( 'templates/blog/blog-single', 'products-carousel' ); ?>
	<?php
		$enable_sharing = ciloe_get_option( 'enable-sharing', false );
		$socials_shared = ciloe_get_option( 'social-sharing', array() );
	?>

	<?php if (( $enable_sharing ) && ( $socials_shared ) ): ?>
	    <div class="footer-post">
	        <div class="post-expand">
				<?php do_action( 'ciloe_social_share' ); ?>
	        </div>
	    </div>
	<?php endif; ?>
	<?php if ( $show_post_author ) { ?>
		<?php get_template_part( 'templates/blog/blog', 'author' ); ?>
	<?php } ?>
	<?php if ( $show_related_posts ) { ?>
		<?php get_template_part( 'templates/blog/blog-single', 'related-posts' ); ?>
	<?php } ?>
</article>