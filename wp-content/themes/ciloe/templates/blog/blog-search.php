<?php
$blog_full_content = ciloe_get_option( 'blog_full_content', 'no' );
$width             = '1040';
$height            = '640';
?>
<?php if ( have_posts() ) : ?>
    <div class="blog-content classic">
		<?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( 'post-item' ); ?>>
                <div class="post-info">
                    <div class="header-info">
                        <h3 class="post-title"><?php
							if ( is_sticky() && is_home() && ! is_paged() ) {
								printf( '<i class="fa fa-bookmark" aria-hidden="true"></i>', esc_html__( '', 'ciloe' ) );
							}
							?>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="ciloe-post-meta">
                            <a class="ciloe-post-date"
                               href="<?php the_permalink(); ?>"><span><?php echo get_the_date(); ?></span></a>
                            <span class="ciloe-post-author">
                            <span><?php echo esc_html__( 'posted by: ', 'ciloe' ) ?></span>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
                                <?php the_author() ?>
                            </a>
                        </span>
                            <div class="ciloe-comment-count">
								<?php comments_number(
									esc_html__( '0 Comments', 'ciloe' ),
									esc_html__( '1 Comment', 'ciloe' ),
									esc_html__( '% Comments', 'ciloe' )
								);
								?>
                            </div>
                        </div>
                    </div>
                    <div class="ciloe-footer-info">
                        <div class="ciloe-cat-post">
							<?php $categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) {
								printf( esc_html__( 'Categories: %1$s', 'ciloe' ), $categories_list ); // WPCS: XSS OK.
							} ?>
                        </div>
                        <div class="ciloe-tag-post">
							<?php the_tags(); ?>
                        </div>
                    </div>
                </div>
            </article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
    </div>
	<?php ciloe_paging_nav(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>