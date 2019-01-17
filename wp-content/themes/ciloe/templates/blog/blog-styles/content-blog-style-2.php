<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div <?php post_class( 'blog-item post-item equal-elem col-sm-12' ); ?>>
    <div class="row">
    	<?php if ( has_post_thumbnail() ) {
    		$image_thumb = ciloe_resize_image( get_post_thumbnail_id(), null, 480, 480, true, false, false );
    		?>
            <div class="col-sm-4">  
                <div class="blog-thumb">
        			<a class="link-thumb" href="<?php the_permalink(); ?>"><?php echo ciloe_img_output( $image_thumb, 'attachment-post-thumbnail wp-post-image', get_the_title() ); ?></a>
                    <a href="<?php the_permalink(); ?>">
                        <nav class="sticky-date">
                            <span class="month"><?php echo get_the_date( 'M' ); ?></span> -
                            <span class="day"><?php echo get_the_date( 'j' ); ?></span>
                        </nav>
                    </a>
                </div>
            </div>
    	<?php } ?>
        <div class="post-info col-sm-8">
            <div class="header-info">
                <div class="cat-post">
                    <?php the_category(', ', ''); ?>
                </div>
            </div>
            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="footer-info clearfix">
                    <a class="read-more" href="<?php the_permalink(); ?>"><?php echo esc_html__('Read more', 'ciloe'); ?></a>
                    
            </div>
        </div>
    </div>
</div>