<?php
/**
 * Template Name: Lojas
 *
 * @package WordPress
 * @subpackage ciloe
 * @since ciloe 1.0
 */
get_header();
?>
    <div class="content-slide">
        <div id="lojas" class="lojas-template">
            <?php
            // Start the loop.
            while (have_posts()) : the_post();
                ?>
                <?php the_content(); ?>
                <?php
                // End the loop.
            endwhile;
            ?>
        </div>
    </div>
<?php
get_footer();
