<?php
    if (!class_exists('Ciloe_Shortcode_categorylist')) {
        class Ciloe_Shortcode_categorylist extends Ciloe_Shortcode
        {
            /**
             * Shortcode name.
             *
             * @var  string
             */
            public $shortcode = 'categorylist';
            /**
             * Default $atts .
             *
             * @var  array
             */
            public $default_atts = array();
            public static function generate_css($atts)
            {
                $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('ciloe_categorylist', $atts) : $atts;
                // Extract shortcode parameters.
                extract($atts);
                $css = '';

                return $css;
            }
            public function output_html($atts, $content = null)
            {
                $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('ciloe_categorylist', $atts) : $atts;
                // Extract shortcode parameters.
                extract($atts);
                $css_class = array('ciloe-categorylist');
                $css_class[] = $atts['el_class'];
                $css_class[] = $atts['categories_custom_id'];
                $css_overlay = $atts['mask_overlay_color'];
                $cat_link = $atts['link'];
                $link_default = array(
                    'url'    => '',
                    'title'  => '',
                    'target' => '',
                );
                if (function_exists('vc_build_link')):
                    $link = vc_build_link($cat_link);
                else:
                    $link = $link_default;
                endif;
                if (function_exists('vc_shortcode_custom_css_class')) {
                    $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
                }
                ob_start();
                $term_link = get_term_link($taxonomy, 'product_cat');
                $term_name = get_term_by('slug', $taxonomy, 'product_cat');
                ?>

                <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                    <?php if ($css_overlay): ?>
                        <div class="cat-overlay" style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, <?php echo esc_attr($css_overlay); ?> 100%) "></div>
                    <?php endif; ?>
                    <?php if ($bg_cat) : ?>
                        <?php
                        $image_thumb = wp_get_attachment_image_src($bg_cat,'1900x920');
                        $image_thumb = $image_thumb[0];
                        ?>
                        <div class="cat-thumb" style="background-image: url('<?php echo esc_attr($image_thumb); ?>');"></div>
                    <?php endif; ?>
                    <div class="cat-content">
                        <div class="cat-content-inner">
                            <?php if ($taxonomy): ?>
                                <h3 class="title">
                                    <a href="<?php echo esc_url($term_link) ?>">
                                        <?php echo esc_html($term_name->name); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                            <?php if ($link['url']): ?>
                                <a class="link-cat" href="<?php echo esc_url($term_link) ?>" target="<?php echo esc_url($link['target']) ?>">
                                    <span class="icon-bag"></span> <?php esc_html_e('Shop Now','ciloe');?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                $html = ob_get_clean();
                return apply_filters('ciloe_shortcode_categorylist', force_balance_tags($html), $atts, $content);
            }
        }
    }