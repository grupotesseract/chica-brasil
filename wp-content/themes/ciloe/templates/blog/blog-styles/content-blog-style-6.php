<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_post_thumbnail = has_post_thumbnail();
$post_class         = $has_post_thumbnail ? 'has-post-thumbnail' : 'no-post-thumbnail';

?>
<div <?php post_class( $post_class . ' blog-item' ); ?>>
    <div class="blog-thumb">
        <nav class="sticky-date">
            <span class="day"><?php echo get_the_date( 'j' ); ?></span>
            <span class="month"><?php echo get_the_date( 'M' ); ?></span>
        </nav>
		<?php if ( $has_post_thumbnail ) {
			$image_thumb = ciloe_resize_image( get_post_thumbnail_id(), null, 370, 226, true, false, false );
			?>
            <a href="<?php the_permalink(); ?>">
				<?php echo ciloe_img_output( $image_thumb, 'attachment-post-thumbnail wp-post-image', get_the_title() ); ?>
            </a>
		<?php } ?>
    </div>
    <div class="blog-info">
        <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="blog-content"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 12, esc_html__( '...', 'ciloe' ) ); ?></div>
        <div class="blog-readmore">
            <a class="blog-readmore" href="<?php the_permalink(); ?>">
				<?php echo esc_html__( 'Read more', 'ciloe' ); ?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
            <span class="comment-count">
				<i class="fa fa-comment"></i>
				<?php comments_number(
					esc_html__( '0 Comments', 'ciloe' ),
					esc_html__( '1 Comment', 'ciloe' ),
					esc_html__( '% Comments', 'ciloe' )
				);
				?>
			</span>
        </div>
    </div>
</div>