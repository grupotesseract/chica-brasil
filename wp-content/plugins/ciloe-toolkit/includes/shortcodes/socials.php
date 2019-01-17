<?php

if ( !class_exists( 'Ciloe_Shortcode_Socials' ) ) {
    class Ciloe_Shortcode_Socials extends Ciloe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'socials';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();


        public static function generate_css( $atts )
        {
            // Extract shortcode parameters.
            extract( $atts );
            $css = '';

            return $css;
        }


        public function output_html( $atts, $content = null )
        {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_socials', $atts ) : $atts;

            // Extract shortcode parameters.
            extract( $atts );

            $css_class   = array( 'ciloe-socials' );
            $css_class[] = $atts[ 'el_class' ];
            $css_class[] = $atts[ 'socials_custom_id' ];
            $css_class[] = $atts[ 'style' ];

            if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
            }

            ob_start();
            ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?> <?php echo esc_attr( $atts[ 'text_align' ] ); ?>">
                <?php if ( !empty( $atts[ 'use_socials' ] ) ): ?>
                    <?php
                    $socials     = explode( ',', $atts[ 'use_socials' ] );
                    $all_socials = ciloe_get_option( 'user_all_social' );
                    ?>
                    <?php if ( $atts[ 'title' ] ) : ?>
                        <h4 class="title"><?php echo esc_html( $atts[ 'title' ] ); ?></h4>
                    <?php endif; ?>
                    <div class="socials">
                        <?php foreach ( $socials as $social ) : ?>
                            <?php $array_social = $all_socials[ $social ]; ?>
                            <a class="social-item" href="<?php echo esc_url( $array_social[ 'link_social' ] ) ?>"
                               target="_blank">
                                <i class="<?php echo esc_attr( $array_social[ 'icon_social' ] ); ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'Ciloe_Shortcode_socials', force_balance_tags( $html ), $atts, $content );
        }
    }
}