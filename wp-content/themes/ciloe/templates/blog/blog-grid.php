<?php
$ciloe_blog_bg_items = ciloe_get_option( 'ciloe_blog_bg_items', 15 );
$ciloe_blog_lg_items = ciloe_get_option( 'ciloe_blog_lg_items', 4 );
$ciloe_blog_md_items = ciloe_get_option( 'ciloe_blog_md_items', 4 );
$ciloe_blog_sm_items = ciloe_get_option( 'ciloe_blog_sm_items', 6 );
$ciloe_blog_xs_items = ciloe_get_option( 'ciloe_blog_xs_items', 6 );
$ciloe_blog_ts_items = ciloe_get_option( 'ciloe_blog_ts_items', 12 );

$classes[] = 'post-item';
$classes[] = 'col-bg-' . $ciloe_blog_bg_items;
$classes[] = 'col-lg-' . $ciloe_blog_lg_items;
$classes[] = 'col-md-' . $ciloe_blog_md_items;
$classes[] = 'col-sm-' . $ciloe_blog_sm_items;
$classes[] = 'col-xs-' . $ciloe_blog_xs_items;
$classes[] = 'col-ts-' . $ciloe_blog_ts_items;

$width  = '440';
$height = '503';

?>
<?php if ( have_posts() ) : ?>
    <div class="blog-content grid auto-clear row">
		<?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( $classes ); ?>>
                <div class="post-thumb-grid">
                    <div class="post-date-wrap">
                        <a class="post-date"
                           href="<?php the_permalink(); ?>"><span><?php echo get_the_date(); ?></span></a>
                    </div>
					<?php ciloe_post_thumbnail( $width, $height ); ?>
                </div>

                <div class="post-info">
                    <div class="header-info">
                        <div class="cat-post">
							<?php the_category( ', ', '' ); ?>
                        </div>
                        <div class="post-expand">
                            <!-- <?php //ciloe_post_product_meta(); ?> -->
							<?php do_action( 'ciloe_social_share' ); ?>
                            <div class="comment-count">
								<?php comments_number(
									esc_html__( '0', 'ciloe' ),
									esc_html__( '1', 'ciloe' ),
									esc_html__( '%', 'ciloe' )
								);
								?>
                            </div>
                        </div>
                    </div>
                    <div class="content-info">
                        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </div>
                    <div class="footer-info clearfix">
                        <a class="read-more"
                           href="<?php the_permalink(); ?>"><?php echo esc_html__( 'Read more', 'ciloe' ); ?></a>

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