<?php

if ( !class_exists( 'Ciloe_Shortcode_blog' ) ) {
	class Ciloe_Shortcode_blog extends Ciloe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'blog';


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
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' || $atts[ 'owl_navigation_position' ] == 'nav2 top-right' || $atts[ 'owl_navigation_position' ] == 'nav2 top-center' ) {
				$css .= '.' . $atts[ 'blog_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ top:' . $atts[ 'owl_navigation_position_top' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-left' ) {
				$css .= '.' . $atts[ 'blog_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ left:' . $atts[ 'owl_navigation_offset_left' ] . 'px;} ';
			}
			if ( $atts[ 'owl_navigation_position' ] == 'nav2 top-right' ) {
				$css .= '.' . $atts[ 'blog_custom_id' ] . ' .owl-carousel.nav2 .owl-nav{ right:' . $atts[ 'owl_navigation_offset_right' ] . 'px;} ';
			}

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ciloe_blog', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class = array( 'ciloe-blog' );
			$css_class[] = $atts[ 'style' ];
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'blog_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			$owl_settings = $this->generate_carousel_data_attributes( '', $atts );

			$args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $atts[ 'per_page' ],
				'suppress_filter'     => true,
				'orderby'             => $atts[ 'orderby' ],
				'order'               => $atts[ 'order' ],
			);
			if ( !empty( $ids_post ) ) {
				$args[ 'p' ] = $ids_post;
			}

			if ( $atts[ 'category_slug' ] ) {
				$idObj = get_category_by_slug( $atts[ 'category_slug' ] );
				if ( is_object( $idObj ) ) {
					$args[ 'cat' ] = $idObj->term_id;
				}
			}

			$loop_posts = new WP_Query( apply_filters( 'ciloe_shortcode_posts_query', $args, $atts ) );

			ob_start();
			?>
			<?php if ( $loop_posts->have_posts() ) : ?>
                <?php if($atts[ 'style' ]=='style-1'):?>
                	<div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
	                    <div class="owl-carousel" <?php echo force_balance_tags($owl_settings); ?>>
	                        <?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
	                            <?php get_template_part( 'templates/blog/blog-styles/content-blog', $atts[ 'style' ] ); ?>
	                        <?php endwhile; ?>
	                    </div>
	                    <?php wp_reset_postdata(); ?>
	                </div>
	            <?php else : ?>
	            	<div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?> on-row-<?php echo esc_attr( $per_row ) ?> ">
	                	<div class="blogs-row equal-container better-height row">
	                        <?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
	                            <?php get_template_part( 'templates/blog/blog-styles/content-blog', $atts[ 'style' ] ); ?>
	                        <?php endwhile; ?>
	                    </div>
	                    <?php wp_reset_postdata(); ?>
	                </div>
	            <?php endif; ?>      
            <?php else : ?>
                <?php get_template_part( 'content', 'none' ); ?>
		    <?php endif; ?>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Ciloe_Shortcode_blog', force_balance_tags( $html ), $atts, $content );
		}
	}
}