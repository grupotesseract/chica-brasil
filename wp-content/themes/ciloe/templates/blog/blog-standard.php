<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php if ( have_posts() ) : ?>
    <div class="blog-content blog-content-standard">
		<?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( 'post-item post-list-item-cols' ); ?>>
				
				<?php
				if ( has_post_thumbnail() ) {
					?>
                    <div class="post-thumb">
						<?php get_template_part( 'templates/blog/post', 'format' ); ?>
                    </div>
					<?php
				}
				?>
                <div class="post-item-info">
                    <div class="post-date"><a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a></div>
                    <h3 class="post-name">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
					<?php
					the_content(
						sprintf(
						/* translators: %s: Name of current post. */
							wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'ciloe' ), array( 'span' => array( 'class' => array() ) ) ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						)
					);
					?>

                    <div class="post-expand">
                        <div class="cat-post">
		                    <?php the_category( ', ', '' ); ?>
                        </div>
                        <div class="comment-count">
			                <?php comments_number(
				                esc_html__('0', 'ciloe'),
				                esc_html__('1', 'ciloe'),
				                esc_html__('%', 'ciloe')
			                );
			                ?>
                        </div>
                    </div>
					
					<?php
					wp_link_pages(
						array(
							'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'ciloe' ),
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'ciloe' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						)
					);
					?>
                </div>

            </article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
    </div>
	<?php ciloe_paging_nav(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>