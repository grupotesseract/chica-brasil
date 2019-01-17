<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_post_thumbnail = has_post_thumbnail();
$post_class         = $has_post_thumbnail ? 'has-post-thumbnail' : 'no-post-thumbnail';

?>
<div <?php post_class( $post_class . ' post-item' ); ?>>

    <div class="post-thumb">
		<?php if ( $has_post_thumbnail ) {
			$image_thumb = ciloe_resize_image( get_post_thumbnail_id(), null, 440, 363, true, false, false );
			?>
            <a href="<?php the_permalink(); ?>">
				<?php echo ciloe_img_output( $image_thumb, 'attachment-post-thumbnail wp-post-image', get_the_title() ); ?>
            </a>
		<?php } ?>
        <a class="post-date" href="<?php the_permalink(); ?>"><span><?php the_date(); ?></span></a>
    </div>
    <div class="post-info">
        <div class="header-info">
            <div class="cat-post">
				<?php the_category( ', ', '' ); ?>
            </div>
        </div>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="footer-info clearfix">
            <a class="read-more" href="<?php the_permalink(); ?>"><?php echo esc_html__( 'Read more', 'ciloe' ); ?></a>

        </div>
    </div>
</div>