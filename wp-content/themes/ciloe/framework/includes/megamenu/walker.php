<?php
/**
 * Class Name: ciloe_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
if ( ! class_exists( 'Ciloe_navwalker' ) ) {
	class Ciloe_navwalker extends Walker_Nav_Menu {
		/**
		 * @see   Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of page. Used for padding.
		 */
		/**
		 * @var string $megamenu_enable
		 */
		private $megamenu_do_shortcode = "";
		/**
		 * @var string $megamenu_img_icon
		 */
		private $megamenu_img_icon = "";
		/**
		 * @var string $megamenu_menu_page
		 */
		private $megamenu_menu_page = 0;
		/**
		 * @var string $Icon type
		 */
		private $megamenu_item_icon_type = 'none';
		/**
		 * @var string $font_icon
		 */
		private $megamenu_item_font_icon = '';
		/**
		 * @var string $mega_menu_width
		 */
		private $megamenu_item_mega_menu_width = '';
		/**
		 * @var string $mega_menu_url
		 */
		private $megamenu_item_mega_menu_url = '#';
		private $img_icon_hover              = '';
		private $img_note                    = '';
		private $enable_login_logout         = '';
		private $enable_minicart             = '';
		private $enable_currency_switcher    = '';
		private $hiden_title                 = '';
		
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul role=\"menu\" class=\" submenu\">\n";
		}
		
		/**
		 * @see   Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output       Passed by reference. Used to append additional content.
		 * @param object $item         Menu item data object.
		 * @param int    $depth        Depth of menu item. Used for padding.
		 * @param int    $current_page Menu item ID.
		 * @param object $args
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$this->megamenu_do_shortcode         = get_post_meta( $item->ID, '_menu_item_megamenu_do_shortcode', true );
			$this->megamenu_img_icon             = get_post_meta( $item->ID, '_menu_item_megamenu_img_icon', true );
			$this->megamenu_item_icon_type       = get_post_meta( $item->ID, '_menu_item_megamenu_item_icon_type', true );
			$this->megamenu_item_font_icon       = get_post_meta( $item->ID, '_menu_item_megamenu_font_icon', true );
			$this->megamenu_item_mega_menu_width = get_post_meta( $item->ID, '_menu_item_megamenu_mega_menu_width', true );
			$this->megamenu_item_mega_menu_url   = get_post_meta( $item->ID, '_menu_item_megamenu_mega_menu_url', true );
			$this->img_icon_hover                = get_post_meta( $item->ID, '_menu_item_megamenu_img_icon_hover', true );
			$this->img_note                      = get_post_meta( $item->ID, '_menu_item_megamenu_img_note', true );
			$this->enable_login_logout           = get_post_meta( $item->ID, '_menu_item_megamenu_enable_login_logout', true );
			$this->enable_minicart               = get_post_meta( $item->ID, '_menu_item_megamenu_enable_minicart', true );
			$this->enable_currency_switcher      = get_post_meta( $item->ID, '_menu_item_megamenu_enable_currency_switcher', true );
			$this->hiden_title                   = get_post_meta( $item->ID, '_menu_item_megamenu_hiden_title', true );
			if ( $this->hiden_title ) {
				$item->title = '';
			}
			// Login/Logout link
			if ( $this->enable_login_logout || $this->enable_currency_switcher ) {
				$item->title = '';
			}
			$title  = apply_filters( 'the_title', $item->title, $item->ID );
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			/**
			 * Dividers, Headers or Disabled
			 * =============================
			 * Determine whether the item is a Divider, Header, Disabled or regular
			 * menu item. To prevent errors we use the strcasecmp() function to so a
			 * comparison that is not case sensitive. The strcasecmp() function returns
			 * a 0 if the strings are equal.
			 */
			if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->attr_title, 'dropdown-header' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
			} else if ( strcasecmp( $item->attr_title, 'disabled' ) == 0 ) {
				$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
			} else {
				$class_names = $value = '';
				$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[]   = 'menu-item-' . $item->ID;
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				if ( ! empty( $args->has_children ) ) {
					$class_names .= ' parent';
				}
				if ( 'megamenu' == $item->object ) {
					$class_names .= ' parent parent-megamenu item-megamenu menu-item-has-children';
				}
				if ( $this->megamenu_item_icon_type == "logo" ) {
					$class_names .= ' item-logo ';
				}
				if ( $this->enable_minicart ) {
					$class_names .= ' mini-cart';
				}
				if ( $this->enable_currency_switcher ) {
					$class_names .= ' parent parent-megamenu menu-item-currency-switcher';
				}
				if ( $this->megamenu_do_shortcode ) {
					$class_names .= ' menu-item-sc';
				}
				if ( in_array( 'current-menu-item', $classes ) ) {
					$class_names .= ' active';
				}
				$class_names   = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
				$id            = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
				$id            = $id ? ' id="' . esc_attr( $id ) . '"' : '';
				$output        .= $indent . '<li' . $id . $value . $class_names . '>';
				$atts          = array();
				$atts['title'] = ! empty( $item->title ) ? $item->title : '';
				if ( $this->megamenu_do_shortcode ) {
					$atts['title'] = '';
				}
				$atts['target'] = ! empty( $item->target ) ? $item->target : '';
				$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
				// If item has_children add atts to a.
				if ( ! empty( $args->has_children ) && $depth === 0 ) {
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				} else {
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				}
				if ( 'megamenu' == $item->object && $depth == 0 ) {
					$atts['href'] = $this->megamenu_item_mega_menu_url;
				}
				if ( $this->enable_minicart ) {
					$atts['class'] = 'mini-cart-link';
				}
				
				$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
				if ( empty( $args->before ) ) {
					$item_output = '';
				} else {
					$item_output = $args->before;
				}
				if ( ! $this->megamenu_do_shortcode ) {
					if ( ! empty( $item->attr_title ) ) {
						$item_output .= '<a' . $attributes . '><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
					} else {
						$item_output .= '<a' . $attributes . '>';
					}
				}
				
				
				if ( ! empty( $args->link_before ) ) {
					$item_output .= $args->link_before;
				}
				if ( $this->megamenu_do_shortcode ) {
					$item_output .= '' . do_shortcode( $item->title );
				} else {
					$item_output .= $title;
				}
				// Menu Description
				if ( 'services-menu' == $args->theme_location && $item->description ) {
					$item_output .= '<span class="description">' . $item->description . '</span>';
				}
				if ( ! empty( $args->link_after ) ) {
					$item_output .= $args->link_after;
				}
				if ( $this->megamenu_item_icon_type != "none" ) {
					// Icon image
					if ( $this->megamenu_item_icon_type == "image" ) {
						if ( $this->megamenu_img_icon ) {
							$imgicon   = wp_get_attachment_image( intval( $this->megamenu_img_icon ), 'full', null, array( 'class' => 'image_icon_1' ) );
							$imgicon_2 = wp_get_attachment_image( intval( $this->img_icon_hover ), 'full', null, array( 'class' => 'image_icon_2' ) );
							if ( ! $imgicon_2 ) {
								$imgicon_2 = $imgicon;
							}
							if ( $imgicon ) {
								$item_output .= '<span class="image">' . $imgicon . "</span>";
							}
						}
					}
					// Icon image
					if ( $this->megamenu_item_icon_type == "logo" ) {
						if ( $this->megamenu_img_icon ) {
							$imgicon   = wp_get_attachment_image( intval( $this->megamenu_img_icon ), 'full', null, array( 'class' => 'image_icon_1' ) );
							$imgicon_2 = wp_get_attachment_image( intval( $this->img_icon_hover ), 'full', null, array( 'class' => 'image_icon_2' ) );
							if ( ! $imgicon_2 ) {
								$imgicon_2 = $imgicon;
							}
							if ( $imgicon ) {
								$imgicon_url = wp_get_attachment_image_url( intval( $this->megamenu_img_icon ), 'full' );
								$imgicon_ext = pathinfo( $imgicon_url, PATHINFO_EXTENSION );
								if ( $imgicon_ext == 'svg' ) {
									$imgicon_arg = array(
										'width'  => 64,
										'height' => 22,
										'url'    => $imgicon_url
									);
									$item_output .= '<span class="logo">' . ciloe_img_output( $imgicon_arg, 'image_icon_1' ) . '</span>';
								} else {
									$item_output .= '<span class="logo">' . $imgicon . '</span>';
								}
							}
						}
					}
					if ( $this->megamenu_item_icon_type == "fonticon" ) {
						if ( $this->megamenu_item_font_icon && $this->megamenu_item_font_icon != "" ) {
							$item_output .= '<span class="icon ' . $this->megamenu_item_font_icon . '"></span>';
						}
					}
					
				}
				// Image note
				if ( $this->img_note ) {
					$img_note = wp_get_attachment_image( intval( $this->img_note ), 'full', null, array( 'class' => 'image_notice' ) );
					if ( $img_note ) {
						$item_output .= $img_note;
					}
				}
				$item_output .= '</a>';
				$item_output .= $args->after;
				if ( ! empty( $args->after ) ) {
					$item_output .= $args->after;
				}
				if ( $this->enable_minicart ) {
					$cart_content = '';
					$item_output  .= '<div class="submenu mini-cart-content-wapper">' . $cart_content . '</div>';
				}
				
				if ( ! empty( $args->has_children ) ) {
					$item_output .= '<span class="toggle-submenu"></span>';
				}
				
				if ( 'megamenu' == $item->object && $depth == 0 ) {
					
					$megamenu_item = get_post( $item->object_id );
					if ( $this->megamenu_item_mega_menu_width == "" || $this->megamenu_item_mega_menu_width <= 0 || ! is_numeric( $this->megamenu_item_mega_menu_width ) ) {
						$mega_menu_width = 1170;
					} else {
						$mega_menu_width = $this->megamenu_item_mega_menu_width;
					}
					
					$item_output .= '<span class="toggle-submenu"></span>';
					$item_output .= '<div style="width:' . $mega_menu_width . 'px;" class="submenu megamenu">' . do_shortcode( $megamenu_item->post_content ) . '</div>';
				}
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}
		
		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see   Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element           Data object
		 * @param array  $children_elements List of elements to continue traversing.
		 * @param int    $max_depth         Max depth to traverse.
		 * @param int    $depth             Depth of current element.
		 * @param array  $args
		 * @param string $output            Passed by reference. Used to append additional content.
		 *
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}
			$id_field = $this->db_fields['id'];
			// Display this element.
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
			}
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}
		
		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {
				extract( $args );
				$fb_output = null;
				if ( $container ) {
					$fb_output = '<' . $container;
					if ( $container_id ) {
						$fb_output .= ' id="' . esc_attr( $container_id ) . '"';
					}
					if ( $container_class ) {
						$fb_output .= ' class="' . esc_attr( $container_class ) . '"';
					}
					$fb_output .= '>';
				}
				$fb_output .= '<ul';
				if ( $menu_id ) {
					$fb_output .= ' id="' . esc_attr( $menu_id ) . '"';
				}
				if ( $menu_class ) {
					$fb_output .= ' class="' . esc_attr( $menu_class ) . '"';
				}
				$fb_output .= '>';
				$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">' . esc_html__( 'Add a menu', 'ciloe' ) . '</a></li>';
				$fb_output .= '</ul>';
				if ( $container ) {
					$fb_output .= '</' . esc_attr( $container ) . '>';
				}
				printf( '%s', $fb_output );
			}
		}
	}
}
