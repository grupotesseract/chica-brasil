<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$description = get_the_author_meta( 'description' );

if ( $description != "" ): ?>
    <div class="about-me">
        <h6 class="author-title"><?php esc_html_e( 'About author', 'ciloe' ); ?></h6>
        <div class="avatar-img">
			<?php echo get_avatar( get_the_author_meta( 'email' ), '180' ); ?>
        </div>
        <div class="about-text">
            <div class="author-info">
                <h3 class="author-name"><?php the_author(); ?></h3>
            </div>
            <div class="author-desc"><?php the_author_meta( 'description' ); ?></div>
			<?php do_action( 'ciloe_single_post_socials' ); ?>
        </div>
    </div>
	<?php
endif; ?>