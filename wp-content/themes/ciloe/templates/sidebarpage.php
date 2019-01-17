<?php
/**
 * Template Name: Menu Sidebar Page
 *
 * @package WordPress
 * @subpackage ciloe
 * @since ciloe 1.0
 */
get_header();
?>
    <div class="site-main menu-sidebar-template">
        <div class="main-content">
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