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
        <div class="filtros-wrapper">
            <ul class="filtros desktop-only">
                <div class="container">
                    <li class="filtros-open" data-cat=""></li>
                    <li class="filtros-item active" data-cat="all">Todos</li>
                    <li class="filtros-item" data-cat="biquinis">Biquinis</li>
                    <li class="filtros-item" data-cat="maios-bodies">Maiôs - Bodies</li>
                    <li class="filtros-item" data-cat="saidas">Saídas</li>
                    <li class="filtros-item" data-cat="roupas">Roupas</li>
                    <li class="filtros-item" data-cat="lancamentos">Lançamentos</li>
                    <li class="filtros-item" data-cat="sales">Sales</li>
                </div>
            </ul>
            <div class="filtros-mobile-wrapper mobile-only">
                <p class="filtro-title mobile-label open-cat">Filtrar por categorias</p>

                <div class="col-md-12 filtros-cat-wrapper">
                    <select class="filtros-mobile">
                        <option value="all">Todos</option>
                        <option value="biquinis">Biquinis</option>
                        <option value="maios-bodies">Maiôs - Bodies</option>
                        <option value="saidas">Saídas</option>
                        <option value="roupas">Roupas</option>
                        <option value="lancamentos">Lançamentos</option>
                        <option value="sales">Sales</option>
                    </select>
                </div>

                <p class="filtro-title mobile-label open-filters">Filtros gerais</p>
            </div>

            <div class="filtros-container" style="display: none;">
                <span class="close-section mobile-only">
                    Fechar
                </span>
                <div class="container">
                    <div class="categorias col-md-4">
                        <h4 class="filtro-title">Categorias</h4>
                        <div class="cat-opt-wrapper">
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-biquinis" type="checkbox" value="biquinis">
                                <label for="cat-opt-biquinis">Biquinis</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-roupas" type="checkbox" value="roupas">
                                <label for="cat-opt-roupas">Roupas</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-maios-bodies" type="checkbox" value="maios-bodies">
                                <label for="cat-opt-maios-bodies">Maiôs/Bodies</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-hits" type="checkbox" value="hits">
                                <label for="cat-opt-hits">ChicaHits</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-croppeds" type="checkbox" value="croppeds">
                                <label for="cat-opt-croppeds">Croppeds</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-lancamentos" type="checkbox" value="lancamentos">
                                <label for="cat-opt-lancamentos">Lançamentos</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-saidas" type="checkbox" value="saidas">
                                <label for="cat-opt-saidas">Saídas</label>
                            </div>
                            <div class="cat-opt col-md-6">
                                <input id="cat-opt-sales" type="checkbox" value="sales">
                                <label for="cat-opt-sales">Sale</label>
                            </div>
                        </div>
                    </div>
                    <div class="estampas col-md-4">
                        <h4 class="filtro-title">Estampas</h4>

                        <div class="estampas-opt-wrapper">
                            <ul class="estampas-list opt-list">
                                <?php

                                    $terms = get_terms( array(
                                        'taxonomy' => 'pa_estampa',
                                        'hide_empty' => false,
                                    ) );

                                    foreach ($terms as $term) {

                                        $term_meta = get_term_meta( $term->term_id );

                                        $elemento = '<li class="estampa-opt '. $term_meta['pa_estampa_attribute_swatch_type'][0] .'" data-value="'. $term->slug .'">';
                                        if ( $term_meta['pa_estampa_attribute_swatch_type'][0] == 'photo' ) {

                                            $thumb = wp_get_attachment_image_src( $term_meta['pa_estampa_attribute_swatch_photo'][0], 'single-post-thumbnail' );

                                            $elemento .= '<img src="'. $thumb[0] .'">';

                                        } else {

                                            $elemento .= '<span style="background-color:'. $term_meta['pa_estampa_attribute_swatch_color'][0] .'"></span>';

                                        }
                                        $elemento .= '</li>';

                                        echo $elemento;

                                    }

                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="tamanho col-md-4">
                        <h4 class="filtro-title">Tamanho</h4>

                        <div class="tamanho-opt-wrapper">
                            <ul class="tamanho-list opt-list">
                                <?php

                                    $terms = get_terms( array(
                                        'taxonomy' => 'pa_tamanho',
                                        'hide_empty' => false,
                                        'orderby' => 'id',
                                        'order' => 'ASC'
                                    ) );

                                    foreach ($terms as $term) {

                                        $term_meta = get_term_meta( $term->term_id );
                                        $thumb = wp_get_attachment_image_src( $term_meta['pa_estampa_attribute_swatch_photo'][0], 'single-post-thumbnail' );
                                        echo '<li class="estampa-opt" data-value="'. $term->slug .'"><p>'. $term->name .'</p></li>';

                                    }

                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php /*
                    <div class="ordernar col-md-3">
                        <h4 class="filtro-title">Ordernar</h4>
                    </div>
                    */ ?>
                </div>
            </div>
        </div>

        <div id="products">
            <div class="produtos-wrapper items-wrapper container">
                <?php
                    $number_of_posts = -1;
                    $category_type = '';

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

                        $class_product = 'col-md-3 col-xs-6';

                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'single-post-thumbnail' );
                        ?>
                        <div class="item <?php echo $class_product; ?>">
                            <div class="img-wrapper">
                                <a href="<?php the_permalink() ?>">
                                    <img src="<?php echo $image[0] ?>" alt="">
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
                        // echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
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
            var _categorieFilters = ['<?php echo $category_type ?>'];
            var _attributes = {
                estampas: [],
                tamanho: []
            };

            $('.mobile-label.open-cat').click(function() {
                $('.filtros-cat-wrapper').slideToggle(400);
            });

            $('.mobile-label.open-filters').click(function() {
                $('.filtros-container').addClass('opened');
            });

            $('.filtros-container .close-section').click(function() {
                $('.filtros-container').removeClass('opened');
            });

            $('.filtros-open').click(function() {
                $('.filtros-container').slideToggle('400');

                $(this).toggleClass('active');
            });

            // filtros mestre: barra superior, eles levam prioridade
            $('.filtros-item').click(function() {

                if ( ! _ajaxBlocked ) {

                    $('.filtros-item, .estampas-list li, .tamanho-list li').removeClass('active');
                    $(this).addClass('active');
                    $('.cat-opt input').removeAttr('checked');
                    $('#cat-opt-'+ $(this).attr('data-cat')).attr('checked', 'checked');

                    _categorieFilters = ['<?php echo $category_type ?>', $(this).attr('data-cat')];
                    _attributes = {
                        estampas: [],
                        tamanho: []
                    };

                    FilterProducts('desc', _categorieFilters, _attributes, '', 'products');

                }

            });
            $('.filtros-mobile').change(function() {

                if ( ! _ajaxBlocked ) {

                    $('.filtros-item').removeClass('active');
                    $(this).addClass('active');
                    $('.cat-opt input').removeAttr('checked');
                    $('#cat-opt-'+ $(this).attr('data-cat')).attr('checked', 'checked');

                    _categorieFilters = ['<?php echo $category_type ?>', $(this).attr('data-cat')];
                    _attributes = {
                        estampas: [],
                        tamanho: []
                    };

                    FilterProducts('desc', _categorieFilters, _attributes, '', 'products');

                }

            });

            // filtros secundarios:
            $('.cat-opt input').change(function() {

                if ( ! _ajaxBlocked ) {

                    if ( $(this).is(':checked') ) {

                        _categorieFilters.push($(this).val());

                        FilterProducts('desc', _categorieFilters, _attributes, '', 'products');


                    } else {

                        for ( var i = 0; i < _categorieFilters.length; i++ ) {

                           if ( _categorieFilters[i] === $(this).val() ) {

                               _categorieFilters.splice(i, 1);

                            }

                        }

                        FilterProducts('desc', _categorieFilters, _attributes, '', 'products');

                    }

                } else {

                    if ( $(this).is(':checked') ) {

                        $(this).removeAttr('checked');

                    } else {

                        $(this).attr('checked', 'checked');


                    }

                }

            });

            $('.estampas-list li').click(function() {
                $(this).toggleClass('active');
                _notFound = true;

                for ( var i = 0; i < _attributes.estampas.length; i++ ) {

                   if ( _attributes.estampas[i] === $(this).attr('data-value') ) {

                       _attributes.estampas.splice(i, 1);
                       _notFound = false;

                    }

                }

                if ( _notFound ) {

                    _attributes.estampas.push($(this).attr('data-value'));

                }

                FilterProducts('desc', _categorieFilters, _attributes, '', 'products');
            });

            $('.tamanho-list li').click(function() {
                $(this).toggleClass('active');
                _notFound = true;

                for ( var i = 0; i < _attributes.tamanho.length; i++ ) {

                   if ( _attributes.tamanho[i] === $(this).attr('data-value') ) {

                       _attributes.tamanho.splice(i, 1);
                       _notFound = false;

                    }

                }

                if ( _notFound ) {

                    _attributes.tamanho.push($(this).attr('data-value'));

                }

                FilterProducts('desc', _categorieFilters, _attributes, '', 'products');
            });
        });

    </script>

<?php
get_footer();
