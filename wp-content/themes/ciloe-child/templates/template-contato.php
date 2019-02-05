<?php
/**
 * Template Name: Chica - Contato
 *
 * @package WordPress
 * @subpackage ciloe
 * @since ciloe 1.0
 */
get_header();
?>
    <div class="content-slide">
        <div id="contato" class="contato-template">
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
