<?php if ( have_posts() ) : $i = 0; ?>
    <div class="blog-content list simplpe"> 
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
                $postclassic[] = 'post-item';
				$width        = '1140';
				$height       = '640';
			?>
            <article <?php post_class( $postclassic ); ?>>
				<?php ciloe_post_thumbnail( $width, $height ); ?>
                <div class="post-info">
                    <div class="header-info">
                        <div class="cat-post">
							<?php the_category( ', ', '' ); ?> 
                        </div>
                        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        
                    </div>
                    <div class="content-info">
                        <div class="post-excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 200, esc_html__( '...', 'ciloe' ) ); ?></div>
                    </div>
                    <div class="footer-info clearfix mr-linh">
                        <a class="post-date" href="<?php the_permalink(); ?>"><span><?php the_date(); ?></span></a>
                        <div class="post-expand">
							<?php ciloe_post_product_meta(); ?>
							<?php do_action( 'ciloe_social_share' ); ?>
                            <div class="comment-count">
								<?php comments_number(
									esc_html__( '0 comments', 'ciloe' ),
									esc_html__( '1 comment', 'ciloe' ),
									esc_html__( '% comments', 'ciloe' )
								);
								?>
                            </div>
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