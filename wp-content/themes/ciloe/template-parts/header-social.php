<?php $user_social = ciloe_get_option( 'user_all_social' ); ?>
<?php if ( !empty( $user_social ) ) : ?>
    <div class="menu-social">
        <h3 class="social-title"><?php echo esc_html__('FOLLOW US','ciloe') ?></h3>
        <ul class="social-list">
            <?php foreach ( $user_social as $value ) : ?>
                <li>
                    <a href="<?php echo esc_url( $value[ 'link_social' ] ) ?>">
                        <span class="<?php echo esc_attr( $value[ 'icon_social' ] ); ?>"></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>