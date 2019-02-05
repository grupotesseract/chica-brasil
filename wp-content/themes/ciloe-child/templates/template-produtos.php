<?php
/**
 * Template Name: Chica - Produtos
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

                    echo '<h2 class="section-title">' . get_the_title() . '</h2>';

                    the_content();
                    // End the loop.
                    $category_type = 'hits';

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
                    'posts_per_page'    => 10,
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

                    if ( in_array('destaque', $categories) ) {
                        $class_product = 'col-md-6 destaque';
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
    </section>
<?php
get_footer();
