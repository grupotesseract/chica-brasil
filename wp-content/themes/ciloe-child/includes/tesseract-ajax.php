<?php

add_action( 'wp_ajax_products_filter', 'products_filter' );
add_action( 'wp_ajax_nopriv_products_filter', 'products_filter' );

function products_filter() {
    $category = $_POST['category'];
    $order = $_POST['order'];
    $search_term = $_POST['search_term'];
    $page_type = $_POST['page_type'];

    $args = array(
        'posts_per_page'	=>	-1,
        'post_type'			=>	'product',
        'post_status'		=>	'publish'
    );

    if ( !empty( $search_term ) ) {
        $args['s'] = $search_term;
    }
    if ( !empty( $order ) ) {
        $args['orderby'] = $order;
    }
    if ( !empty( $category ) ) {
        if ( in_array('all', $category) ) {
            unset($category[1]);
        }

        if ( count($category) > 1 ) {

            $args['tax_query'] = array( 'relation' => 'AND' );

            foreach ($category as $cat) {

                if ( !empty($cat) ) {

                    array_push(
                        $args['tax_query'],
                        array(
                            'taxonomy'  => 'product_cat',
                            'field'     => 'slug',
                            'terms'     => $cat
                        )
                    );

                }

            }

        } else {
            $args['product_cat'] = $category[0];
        }
    }

    if ( $page_type == 'featured' ) {

        display_featured_query( $args, 'product' );

    } else {

        display_main_product_page( $args, 'product' );

    }

    die();
}

function display_featured_query( $args, $type ) {
    if ( $type == 'product' ) {
        $number_of_posts = -1;

        $wp_query = new WP_Query( $args );
        // var_dump($wp_query);

        if ( $wp_query->have_posts() ) {

            $old_class = 'box-normal';
            echo '<div class="produtos-wrapper items-wrapper container">';
            // The 2nd Loop
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                global $product;

                $terms = wp_get_post_terms( $wp_query->post->ID, 'product_cat' );
                $categories = array();
                foreach ( $terms as $term ) {
                    $categories[] = $term->slug;
                }

                $destaque_tag = '';

                if ( in_array('destaque', $categories) ) {
                    $class_product = 'col-md-6 destaque';
                } else if ( in_array('aposta-do-verao', $categories) ) {
                    $class_product = 'col-md-6 destaque';
                    $destaque_tag = '<div class="destaque-tag">Aposta do verão</div>';
                } else {
                    $class_product = 'col-md-3';
                }

                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $wp_query->post->ID ), 'single-post-thumbnail' );
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
                // echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';

            }
            echo '</div>';

            // wp_reset_postdata();
        }

        $_SESSION['blog_class'] = $blog_class;
        $_SESSION['old_class'] = $old_class;
        $_SESSION['class_counter'] = $class_counter;
        $posts_notIn = implode(',', $posts_notIn);

        // echo do_shortcode('[ajax_load_more repeater="post" post_type="post" exclude="'.$posts_notIn.'" pause="true" scroll="false" posts_per_page="'.$number_of_posts.'" max_pages="0" button_label="Carregar mais"]');
    }
}

function display_main_product_page( $args, $type ) {
    if ( $type == 'product' ) {
        $number_of_posts = -1;

        $wp_query = new WP_Query( $args );
        // var_dump($wp_query);

        if ( $wp_query->have_posts() ) {

            $old_class = 'box-normal';
            echo '<div class="produtos-wrapper items-wrapper container">';
            // The 2nd Loop
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
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

            }
            echo '</div>';

            // wp_reset_postdata();
        }

        $_SESSION['blog_class'] = $blog_class;
        $_SESSION['old_class'] = $old_class;
        $_SESSION['class_counter'] = $class_counter;
        $posts_notIn = implode(',', $posts_notIn);

        // echo do_shortcode('[ajax_load_more repeater="post" post_type="post" exclude="'.$posts_notIn.'" pause="true" scroll="false" posts_per_page="'.$number_of_posts.'" max_pages="0" button_label="Carregar mais"]');
    }
}

?>
