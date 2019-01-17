<?php

if ( ! class_exists( 'Ciloe_Shortcode_Instagram' ) ) {
    
    class Ciloe_Shortcode_Instagram extends Ciloe_Shortcode {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'instagram';
        
        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();
        
        
        public static function generate_css( $atts ) {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_instagram', $atts ) : $atts;
            // Extract shortcode parameters.
            extract( $atts );
            $css = '';
            
            return $css;
        }
        
        
        public function output_html( $atts, $content = null ) {
            $style = $limit = $id = $token = '';
            $atts  = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_instagram', $atts ) : $atts;
            // Extract shortcode parameters.
            extract( $atts );
            $css_class   = array( 'ciloe-instagram' );
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $css_class[] = $atts['instagram_custom_id'];
            
            if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
                $css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
            }
            
            $owl_settings = $this->generate_carousel_data_attributes( '', $atts );
            ob_start();
            ?>

            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <?php
                if ( intval( $id ) === 0 ) {
                    esc_html_e( 'No user ID specified.', 'ciloe' );
                }
                $transient_var = $id . '_' . $limit;
                $items         = get_transient( $transient_var );
                if ( $id && $token ) {
                    $response = wp_remote_get( 'https://api.instagram.com/v1/users/' . esc_attr( $id ) . '/media/recent/?access_token=' . esc_attr( $token ) . '&count=' . esc_attr( $limit ) );
                    if ( ! is_wp_error( $response ) ) {
                        $response_body = json_decode( $response['body'] );
                        if ( $response_body->meta->code !== 200 ) {
                            echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'ciloe' ) . '</p>';
                        } else {
                            $items_as_objects = $response_body->data;
                            $items            = array();
                            foreach ( $items_as_objects as $item_object ) {                     
                                $item['link']     = $item_object->link;
                                $item['url']      = $item_object->images->standard_resolution->url;
                                $item['width']    = $item_object->images->standard_resolution->width;
                                $item['height']   = $item_object->images->standard_resolution->height;
                                $item['likes']    = $item_object->likes->count;
                                $item['comments'] = $item_object->comments->count;
                                $items[]          = $item;
                            }
                            set_transient( $transient_var, $items, 60 * 60 ); 
                        }
                        
                    }
                }
                ?>
                <?php if ( isset( $items ) && $items ): ?>
                    <?php if ( $atts['style'] == 'default' ): ?>
                        <div class="owl-carousel nav-center instagram" <?php echo $owl_settings; ?>>
                            <?php foreach ( $items as $item ): ?>
                                <div class="item">
                                    <a target="_blank" href="<?php echo esc_url( $item['link'] ) ?>">
                                        <?php echo ciloe_toolkit_img_output( $item ); ?>
                                    </a>
                                    <div class="info-img">
                                        <span class="social-info"><?php echo esc_attr( $item['likes'] ); ?><i
                                                    class="icon-heart"></i></span>
                                        <span class="social-info"><?php echo esc_attr( $item['comments'] ); ?><i
                                                    class="icon-bubble"></i></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <?php if ( $atts['title'] ): ?>
                            <h2 class="widgettitle"><?php echo wp_kses( $atts['title'], $args ); ?></h2>
                        <?php endif; ?>
                        <div class="instagram-wrap">
                            <?php foreach ( $items as $item ): ?>
                                <div class="item">
                                    <a target="_blank" href="<?php echo esc_url( $item['link'] ) ?>">
                                        <?php echo ciloe_toolkit_img_output( $item ); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php
            $html = ob_get_clean();
            
            return apply_filters( 'ciloe_toolkit_shortcode_blogs', force_balance_tags( $html ), $atts, $content );
        }
    }
}
