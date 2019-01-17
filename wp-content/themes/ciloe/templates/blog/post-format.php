<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_format = get_post_format();
if ( trim( $post_format ) == '' ) {
	$post_format = 'standard';
}

?>

<?php if ( $post_format == 'gallery' ) : ?>
	<?php $images = get_post_meta( get_the_ID(), '_format_gallery_images', true ); ?>
	<?php if ( $images ) : ?>
        <div class="post-format post-gallery owl-carousel nav-center" data-slidespeed="800" data-autoplay="true"
             data-nav="true" data-dots="false" data-loop="true" data-items="1">
			<?php foreach ( $images as $image_id ) : ?>
				<?php
				$image          = ciloe_resize_image( $image_id, null, 1170, 780, true, true );
				$imgage_caption = get_post_field( 'post_excerpt', $image_id );
				?>
                <div class="slide-item">
					<?php echo ciloe_img_output( $image, '', '', $imgage_caption ); ?>
                </div>
			<?php endforeach; ?>
        </div>
	<?php endif; ?>
<?php elseif ( $post_format == 'video' ) : ?>
	<?php if ( has_post_thumbnail() ) : ?>
        <div class="post-format post-video">
			<?php if ( ! is_single() ) : ?>
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			<?php else : ?>
				<?php $video = get_post_meta( get_the_ID(), '_format_video_embed', true ); ?>
				<?php if ( wp_oembed_get( $video ) ) : ?>
					<?php echo wp_oembed_get( $video ); ?>
				<?php else : ?>
					<?php echo wp_kses_post( $video ); ?>
				<?php endif; ?>
			<?php endif; ?>
        </div>
	<?php else : ?>
        <div class="post-format post-video">
			<?php $video = get_post_meta( get_the_ID(), '_format_video_embed', true ); ?>
			<?php if ( wp_oembed_get( $video ) ) : ?>
				<?php echo wp_oembed_get( $video ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( $video ); ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>
<?php elseif ( $post_format == 'audio' ) : ?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php if ( ! is_single() ) : ?>
            <div class="post-format post-audio">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
            </div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( is_single() ) : ?>
        <div class="post-format post-audio">
			<?php $audio = get_post_meta( get_the_ID(), '_format_audio_embed', true ); ?>
			<?php if ( wp_oembed_get( $audio ) ) : ?>
				<?php echo wp_oembed_get( $audio ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( $audio ); ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>
<?php elseif ( $post_format == 'image' ): ?>
	<?php if ( has_post_thumbnail() ) : ?>
        <div class="post-format post-image">
			<?php if ( ! is_single() ) : ?>
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			<?php else : ?>
                <div class="post-thumb">
                    <figure class="single-media"><?php ciloe_post_thumbnail(); ?></figure>
                </div>
			<?php endif; ?>
        </div>
	<?php endif; ?>
<?php else : // Post format standard... ?>
	<?php if ( has_post_thumbnail() ): ?>
        <div class="post-format post-<?php echo esc_attr( $post_format ); ?>">
			<?php if ( ! is_single() ) : ?>
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			<?php else: ?>
                <div class="post-thumb">
                    <figure class="single-media"><?php ciloe_post_thumbnail(); ?></figure>
                </div>
			<?php endif; ?>
        </div>
	<?php endif; ?>
<?php endif; ?>