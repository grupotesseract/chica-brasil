<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// esconde a barra de admin do top, no front
add_filter('show_admin_bar', '__return_false');

// Including main ajax functions file
include_once ( dirname( __FILE__ ) . '/includes/tesseract-ajax.php' );

// registra novos scripts/css
if ( !function_exists( 'tesseract_enqueue_styles') ) :
	function tesseract_enqueue_styles() {
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

		wp_enqueue_script( 'tesseract_ajax', get_stylesheet_directory_uri() . '/assets/js/tesseract-ajax.js' );
		wp_localize_script( 'tesseract_ajax', 'TesseractAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
endif;
add_action( 'wp_enqueue_scripts', 'tesseract_enqueue_styles' );

// registra sidebars
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

// pega o footer do tema pai
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

// adiciona metabox customizadas
function tesseract_add_custom_box()
{
    add_meta_box(
        'tesseract_product_category_page',           // Unique ID
        'Categoria dos Produtos',  // Box title
        'tesseract_custom_box_html',  // Content callback, must be of type callable
        'page',		// Post type
		'side'
    );
}
add_action('add_meta_boxes', 'tesseract_add_custom_box');
