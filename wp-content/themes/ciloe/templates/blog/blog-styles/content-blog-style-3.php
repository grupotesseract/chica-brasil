<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div <?php post_class( 'blog-item' ); ?>>
	<?php if ( has_post_thumbnail() ) {
		$image_thumb = ciloe_resize_image( get_post_thumbnail_id(), null, 370, 226, true, false, false );
		?>
        <div class="blog-thumb">
			<?php echo ciloe_img_output( $image_thumb, 'attachment-post-thumbnail wp-post-image', get_the_title() ); ?>
        </div>
	<?php } ?>
    <div class="blog-info">
        <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <ul class="blog-meta">
            <li class="date">
                <i class="fa fa-pencil-square-o"></i>
				<?php the_time( ' jS F Y' ); ?>
            </li>
            <li class="comment">
                <i class="fa fa-comment-o"></i>
				<?php comments_number(
					esc_html__( '0 Comments', 'ciloe' ),
					esc_html__( '1 Comment', 'ciloe' ),
					esc_html__( '% Comments', 'ciloe' )
				);
				?>
            </li>
        </ul>
        <div class="blog-content">
			<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 15, esc_html__( '...', 'ciloe' ) ); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="blog-readmore">
			<?php echo esc_html__( 'View More', 'ciloe' ); ?>
        </a>
    </div>
</div>