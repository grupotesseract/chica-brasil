<?php

add_action( 'wp_ajax_products_filter', 'products_filter' );
add_action( 'wp_ajax_nopriv_products_filter', 'products_filter' );

function products_filter() {
    $category = $_POST['category'];
    $order = $_POST['order'];
    $search_term = $_POST['search_term'];

    $args = array(
        'posts_per_page'	=>	10,
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
                array_push(
                    $args['tax_query'],
                    array(
                        'taxonomy'  => 'product_cat',
                        'field'     => 'slug',
                        'terms'     => $cat
                    )
                );
            }
        } else {
            $args['product_cat'] = $category[0];
        }
    }

    display_query( $args, 'product' );

//    $results['type'] = 'success';
//    echo json_encode( $results );

    die();
}

function display_query( $args, $type ) {
    if ( $type == 'product' ) {
        $number_of_posts = 6;

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
                        <img src="<?php echo $image[0] ?>" alt="">
                        <?php echo $destaque_tag ?>
                    </div>
                    <h4 class="item-title"><?php the_title(); ?></h4>
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

?>
