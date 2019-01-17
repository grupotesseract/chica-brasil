<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link       https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package    WordPress
 * @subpackage Ciloe
 * @since      1.0
 * @version    1.0
 */

get_header(); ?>
    <div class="container">
        <div class="text-center page-404">
            <h1 class="heading">
                <figure><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.png' ); ?>"
                             alt="<?php esc_attr_e( '404', 'ciloe' ); ?>"/></figure>
            </h1>
            <h2 class="title"><?php esc_html_e( 'We are sorry, the page you\'ve requested is not available', 'ciloe' ); ?></h2>
			<?php get_search_form(); ?>
            <a class="button"
               href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'Back To Homepage', 'ciloe' ); ?></a>
        </div>
    </div>
<?php get_footer();
