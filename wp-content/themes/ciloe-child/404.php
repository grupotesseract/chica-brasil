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
            <div class="text-wrapper">
                <h2 class="section-title cyan-text">Ops!</h2>
                <h3 class="title">Não encontramos a página solicitada.</h3>
            </div>

            <a class="main-btn cyan-bg"
               href="<?php echo esc_url( get_home_url() ); ?>">Voltar para HOME</a>
        </div>
    </div>
<?php get_footer();
