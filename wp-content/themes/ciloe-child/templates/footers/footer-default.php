<footer class="footer ciloe-footer-builder footer-id-<?php echo esc_attr( get_the_ID() ); ?>">
    <div class="footer-wrapper">
        <div class="col-md-6">
            <?php dynamic_sidebar("footer_left_side"); ?>
        </div>

        <div class="col-md-6">
            <?php dynamic_sidebar("footer_right_side"); ?>
        </div>
    </div>
</footer>
