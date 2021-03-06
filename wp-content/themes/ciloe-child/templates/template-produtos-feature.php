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
            <ul class="filtros desktop-only">
                <li class="filtros-item active" data-cat="all">Todos</li>
                <li class="filtros-item" data-cat="biquinis">Biquinis</li>
                <li class="filtros-item" data-cat="maios-bodies">Maiôs - Bodies</li>
                <li class="filtros-item" data-cat="saidas">Saídas</li>
                <li class="filtros-item" data-cat="roupas">Roupas</li>
            </ul>
            <select class="filtros-mobile mobile-only">
                <option value="all">Todos</option>
                <option value="biquinis">Biquinis</option>
                <option value="maios-bodies">Maiôs - Bodies</option>
                <option value="saidas">Saídas</option>
                <option value="roupas">Roupas</option>
            </select>
        </div>

        <div id="products">
            <div class="produtos-wrapper items-wrapper container">
                <?php
                    $number_of_posts = -1;

                    $args = array(
                        'post_type'         => 'product',
                        'posts_per_page'    => $number_of_posts,
                        'product_cat'       => $category_type,
                        'status'            => 'publish'
                    );

                    if ( isset($_GET['cat']) && !empty($_GET['cat']) ) {
                        $args['tax_query'] = array(
                            'relation' => 'AND',
                            array(
                                'taxonomy'  => 'product_cat',
                                'field'     => 'slug',
                                'terms'     => $category_type
                            ),
                            array(
                                'taxonomy'  => 'product_cat',
                                'field'     => 'slug',
                                'terms'     => trim($_GET['cat'])
                            )
                        );

                        unset($args['product_cat']);
                    }

                    $loop = new WP_Query( $args );

                    while ( $loop->have_posts() ) : $loop->the_post();
                        global $product;

                        $terms = wp_get_post_terms( $post->ID, 'product_cat' );
                        $categories = array();
                        foreach ( $terms as $term ) {
                            $categories[] = $term->slug;
                        }

                        $destaque_tag = '';

                        if ( in_array('destaque', $categories) ) {
                            $class_product = 'col-md-6 col-xs-12 destaque';
                        } else if ( in_array('aposta-do-verao', $categories) ) {
                            $class_product = 'col-md-6 col-xs-12 destaque';
                            $destaque_tag = '<div class="destaque-tag">Aposta do verão</div>';
                        } else {
                            $class_product = 'col-md-3 col-xs-6';
                        }

                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'medium_large' );
                        ?>
                        <div class="item <?php echo $class_product; ?>">
                            <div class="img-wrapper">
                                <a href="<?php the_permalink() ?>">
                                    <img src="<?php echo $image[0] ?>" alt="">
                                    <?php echo $destaque_tag ?>
                                </a>
                            </div>
                            <div class="product-meta">
                                <div class="meta-infos">
                                    <h4 class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <p class="item-price"><?php echo $product->get_price_html(); ?></p>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="info-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28.11 36.83"><defs><style>.cls-1{fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:2.83px;}</style></defs><g data-name="Layer 2"><g data-name="Camada 1"><path class="cls-1" d="M26.08,35.42H2a.61.61,0,0,1-.61-.66L3.28,10.61A.61.61,0,0,1,3.89,10H24.22a.61.61,0,0,1,.61.57l1.86,24.15A.61.61,0,0,1,26.08,35.42Z"/><path class="cls-1" d="M9,6.49a5.07,5.07,0,1,1,10.15,0"/></g></g></svg>
                                </a>
                            </div>
                        </div>
                        <?php

                    endwhile;

                    wp_reset_query();

                    // echo do_shortcode('[ajax_load_more repeater="post" post_type="products" exclude="'.$posts_notIn.'" pause="true" scroll="false" posts_per_page="'.$number_of_posts.'" max_pages="0" button_label="Carregar mais"]');
                ?>
            </div>
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

    <script>
        jQuery(document).ready(function($) {
            $('.filtros-item').click(function() {
                $('.filtros-item').removeClass('active');
                $(this).addClass('active');
                FilterProducts('desc', ['<?php echo $category_type ?>', $(this).attr('data-cat')], '', '', 'featured');
            });
            $('.filtros-mobile').change(function() {
                FilterProducts('desc', ['<?php echo $category_type ?>', $(this).val()], '', '', 'featured');
            });
        });

    </script>

<?php
get_footer();
