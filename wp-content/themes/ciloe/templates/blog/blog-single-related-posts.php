<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$tags = wp_get_post_tags( $post->ID );
if ( $tags ) {
	$tag_ids = array();
	foreach ( $tags as $tag ) {
		$tag_ids[] = $tag->term_id;
	}
	
	$args          = array(
		'tag__in'             => $tag_ids,
		'post__not_in'        => array( $post->ID ),
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => 1
	);
	$related_query = new WP_Query( $args );
	if ( $related_query->have_posts() ) {
		$data_responsive = array(
			'0'    => array(
				'items' => 1,
			),
			'480'  => array(
				'items' => 2
			),
			'768'  => array(
				'items' => 3
			),
			'992'  => array(
				'items' => 3
			),
			'1200' => array(
				'items' => 3
			),
			'1500' => array(
				'items' => 3
			),
		);
		$data_responsive = json_encode( $data_responsive );
		?>
        <h3 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'ciloe' ); ?></h3>
        <div class="ciloe-blog style-1 ciloe-related-posts-wrap">
            <div class="owl-carousel" data-autoplay="false" data-nav="false" data-dots="true" data-loop="false"
                 data-margin="30" data-responsive="<?php echo esc_attr( htmlentities2( $data_responsive ) ); ?>">
				<?php
				while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
					<?php get_template_part( 'templates/blog/blog-styles/content-blog', 'style-1' ); ?>
					<?php
				endwhile;
				?>
            </div>
        </div>
		<?php
	}
	wp_reset_postdata();
}