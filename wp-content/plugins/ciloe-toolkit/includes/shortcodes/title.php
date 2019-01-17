<?php

if (!class_exists('Ciloe_Shortcode_Title')) {
    class Ciloe_Shortcode_Title extends Ciloe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'title';

        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();


        public static function generate_css($atts)
        {
            // Extract shortcode parameters.
            extract($atts);
            $css = '';

            return $css;
        }


        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('ciloe_title', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('ciloe-title');
            $css_class[] = $atts['style'];
            // $css_class[] = $atts['title_type'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['title_custom_id'];
            $args = array(
                'a'      => array(
                    'href'  => array(),
                    'title' => array(),
                ),
                'br'     => array(),
                'em'     => array(),
                'strong' => array(),
            );
            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = ' ' . apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
            }
            $link_default = array(
                'url'    => '',
                'title'  => '',
                'target' => '',
            );
            if (function_exists('vc_build_link')):
                $link = vc_build_link($atts['link']);
            else:
                $link = $link_default;
            endif;
	
	        // Fix empty target attribute
	        if ( trim( $link['target'] ) == '' ) {
		        $link['target'] = '_self';
	        }

            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['style'] == 'style2'): ?>
                    <?php if ($atts['title']): ?>
                        <h3 class="block-title">
                            <?php echo wp_kses($atts['title'], $args); ?>
                        </h3>
                    <?php endif; ?>
                    <?php if ($atts['des']): ?>
                        <p class="block-des"><?php echo wp_kses($atts['des'], $args); ?></p>
                    <?php endif; ?>
                     <?php if ($link['url'] != ''): ?>
                        <a class="title-link" href="<?php echo esc_url($link['url']); ?>"
                           target="<?php echo esc_attr($link['target']); ?>"><?php echo esc_attr($link['title']); ?> </a>
                    <?php endif; ?>
                <?php elseif ($atts['style'] == 'style1'): ?>
                    <?php if ($atts['title']): ?>
                        <h3 class="block-title"><?php echo wp_kses($atts['title'], $args); ?>
                        </h3>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if ($atts['title']): ?>
                        <h3 class="block-title"><?php echo wp_kses($atts['title'], $args); ?></h3>
                        <?php if ($atts['des']): ?>
                            <p class="block-des"><?php echo wp_kses($atts['des'], $args); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($link['url'] != ''): ?>
                        <a class="title-link" href="<?php echo esc_url($link['url']); ?>"
                           target="<?php echo esc_attr($link['target']); ?>"><?php echo esc_attr($link['title']); ?> </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php
            $html = ob_get_clean();

            return apply_filters('Ciloe_Shortcode_title', force_balance_tags($html), $atts, $content);
        }
    }
}