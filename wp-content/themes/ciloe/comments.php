<?php
/**
 * The template for displaying comments.
 *
 * @since   1.0.0
 * @package Ciloe
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
        <h4 class="title-comment">
			<?php
			$comments_number = get_comments_number();
			if ( 1 === $comments_number ) {
				/* translators: %s: post title */
				printf( _x( '1 Comment', 'comments title', 'ciloe' ), get_the_title() );
			} else {
				printf(
				/* translators: 1: number of comments, 2: post title */
					_nx(
						'Comment (%1$s)',
						'Comments (%1$s)',
						$comments_number,
						'comments title',
						'ciloe'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
        </h4>
		
		<?php the_comments_navigation(); ?>

        <ol class="commentlist">
			<?php
			wp_list_comments( array(
				                  'style'    => 'ol',
				                  'callback' => 'ciloe_comments_list',
			                  ) );
			?>
        </ol><!-- .commentlist -->
		
		<?php the_comments_navigation(); ?>
	
	<?php endif; // Check for have_comments(). ?>
	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
        <p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'ciloe' ); ?></p>
	<?php endif; ?>
	<?php
	$args = array(
		'comment_notes_before' => '',
		// Redefine your own textarea (the comment body)
		'comment_field'        => '<div class="comment-form-comment"><textarea rows="10" placeholder="' . esc_attr__( 'Your Comment here.', 'ciloe' ) . '" name="comment" aria-required="true"></textarea></div>',
		
		'fields'       => '
                <div class="row">
                    <div class="comment-form-author col-sm-12 col-md-6 col-lg-6">
                        <input placeholder="' . esc_attr__( 'Name*', 'ciloe' ) . '" type="text" required="required" size="30" value="" name="author" id="author">
                    </div>
                    <div class="comment-form-email col-sm-12 col-md-6 col-lg-6">
                        <input placeholder="' . esc_attr__( 'Email*', 'ciloe' ) . '" type="email" required="required" size="30" value="" name="email" id="email">
                    </div>
                </div>
            ',
		
		// Change the title of the reply section
		'title_reply'  => esc_html__( 'Leave a comment', 'ciloe' ),
		
		// Change the title of send button
		'label_submit' => esc_html__( 'Submit', 'ciloe' ),
	);
	
	comment_form( $args );
	?>

</div><!-- .comments-area -->
