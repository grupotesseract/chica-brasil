<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// if ( ! function_exists( 'ciloe_child_parent_css' ) ):
// 	function ciloe_child_parent_css() {
// 		wp_enqueue_style( 'ciloe_child_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(
// 			'boostrap',
// 			'owl-carousel',
// 			'simple-line-icons',
// 			'flat-icons',
// 			'scrollbar',
// 			'chosen',
// 			'ciloe-custom'
// 		) );
//
// 		wp_enqueue_style( 'tesseract-style',
// 			get_stylesheet_directory_uri() . '/assets/css/tesseract.css',
// 			array( 'ciloe_child_parent' ),
// 			wp_get_theme()->get('Version')
// 		);
// 	}
// endif;
// add_action( 'wp_enqueue_scripts', 'ciloe_child_parent_css', 10 );

add_filter('show_admin_bar', '__return_false');

if ( !function_exists( 'my_theme_enqueue_styles') ) :
	function my_theme_enqueue_styles() {
	    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

	    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	    wp_enqueue_style( 'child-style',
	        get_stylesheet_directory_uri() . '/style.css',
	        array( $parent_style ),
	        wp_get_theme()->get('Version')
	    );
	    wp_enqueue_style( 'tesseract-style',
	    	get_stylesheet_directory_uri() . '/assets/css/tesseract.css',
	    	array( $parent_style ),
	    	wp_get_theme()->get('Version')
	    );
	}
endif;
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

if ( function_exists('register_sidebar') ) {
	register_sidebar(
		array(
			'name' => 'Footer: Lado Esquerdo',
			'id' => 'footer_left_side',
			'before_widget' => '<div class = "col-md-6">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name' => 'Footer: Lado Direito',
			'id' => 'footer_right_side',
			'before_widget' => '<div class = "col-md-6">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		)
	);
}

function ciloe_get_footer() {
	$ciloe_footer_id = ciloe_get_option( 'ciloe_footer_options', '' );
	/* Data MetaBox */
	$page_id              = ciloe_get_single_page_id();
	$enable_custom_footer = false;

	$data_option_meta = get_post_meta( $page_id, '_custom_metabox_theme_options', true );
	if ( isset( $data_option_meta['enable_custom_footer'] ) ) {
		$enable_custom_footer = $data_option_meta['enable_custom_footer'] === true;
	}

	if ( $page_id > 0 && $enable_custom_footer ) {
		$ciloe_footer_id = $data_option_meta['ciloe_metabox_footer_options'];
		$data_meta       = get_post_meta( $data_option_meta['ciloe_metabox_footer_options'], '_custom_footer_options', true );;
	}

	if ( empty( $data_meta ) ) {
		$ciloe_template_style = 'default';
	} else {
		$ciloe_template_style = $data_meta['ciloe_footer_style'];
	}
	$allowed_html = array(
		'a' => array(
			'href' => array(),
		),
	);

	$query = new WP_Query( array( 'p' => $ciloe_footer_id, 'post_type' => 'footer', 'posts_per_page' => 1 ) );
	if ( $query->have_posts() ):
		while ( $query->have_posts() ): $query->the_post(); ?>

			<?php get_template_part('templates/footers/footer', 'default'); ?>

		<?php endwhile;
	else: ?>
		<footer class="footer wp-default">
			<div class="container">
				<?php printf( wp_kses( __( '&copy; 2018 <a href="%1$s">Famithemes</a>. All Rights Reserved.', 'ciloe' ), $allowed_html ), esc_url( 'https://famithemes.com' ) ); ?>
			</div>
		</footer>
		<?php
	endif;
	wp_reset_postdata();
}
