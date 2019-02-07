<?php
/**
 * Template Name: Chica - Produtos Coleção
 *
 * @package WordPress
 * @subpackage ciloe
 * @since ciloe 1.0
 */
get_header();
?>
    <section class="produtos-page">
        <div class="section-description container">
            <?php
                while (have_posts()) : the_post();

                    the_content();

                    // $category_type = 'hits';
                    $category_type = 'colecao-2019';

                endwhile;

                wp_reset_query();
            ?>
        </div>
        <div class="filtros-wrapper container">
            <ul class="filtros">
                <li class="filtros-item">Todos</li>
                <li class="filtros-item">Biquinis</li>
                <li class="filtros-item">Maiôs - Bodies</li>
                <li class="filtros-item">Saídas</li>
                <li class="filtros-item">Casual</li>
            </ul>
        </div>
        <div class="produtos-wrapper items-wrapper container">
            <?php
                $args = array(
                    'post_type'         => 'product',
                    'posts_per_page'    => 12,
                    'product_cat'       => $category_type,
                    'status'            => 'publish'
                );

                $loop = new WP_Query( $args );

                while ( $loop->have_posts() ) : $loop->the_post();
                    global $product;

                    $terms = wp_get_post_terms( $post->ID, 'product_cat' );
                    $categories = array();
                    foreach ( $terms as $term ) {
                        $categories[] = $term->slug;
                    }

                    $destaque_tag = false;

                    if ( in_array('destaque', $categories) ) {
                        $class_product = 'col-md-6 destaque';
                    } else if ( in_array('aposta-do-verao', $categories) ) {
                        $class_product = 'col-md-6 destaque';
                        $destaque_tag = true;
                    } else {
                        $class_product = 'col-md-3';
                    }

                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'single-post-thumbnail' );
                    ?>
                    <div class="item <?php echo $class_product; ?>">
                        <img src="<?php echo $image[0] ?>" alt="">
                        <h4 class="item-title"><?php the_title(); ?></h4>
                    </div>
                    <?php
                    // echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
                endwhile;

                wp_reset_query();
            ?>
        </div>

        <?php

            if ( ! wp_is_mobile() ) {
                ?>
                <script src="<?php echo get_site_url(); ?>/wp-content/themes/ciloe-child/assets/js/isotope.pkgd.min.js" charset="utf-8"></script>
                <script>
                jQuery(document).ready(function($) {
                    $('.items-wrapper').isotope({
                        itemSelector: '.item',
                        percentPosition: true,
                        masonry: {
                            // use outer width of grid-sizer for columnWidth
                            columnWidth: '.col-md-3'
                        }
                    });
                });
                </script>
                <?php
            }

         ?>
    </section>

<?php
get_footer();
