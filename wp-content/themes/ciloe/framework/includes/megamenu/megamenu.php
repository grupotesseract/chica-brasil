<?php

if ( !class_exists( 'Ciloe_MegaMenu' ) ) {
	class Ciloe_MegaMenu
	{

		public $custom_fields;
		public $lits_fonts = array();

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */

		function __construct()
		{
			$this->custom_fields = array( 'img_icon', 'do_shortcode', 'font_icon', 'item_icon_type', 'mega_menu_width', 'mega_menu_url', 'img_icon_hover', 'img_note', 'enable_login_logout', 'enable_minicart', 'enable_currency_switcher', 'hiden_title' );
			// add custom menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );

			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 3 );

			// edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ), 10, 2 );

			// add enqueue scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

			$this->includes();
			$this->set_fonts();
			add_action( 'wp_ajax_megamenu_load_font_icon', array( $this, 'megamenu_load_font_icon' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'addShortcodesCustomCss_megamenu' ), 999 );

		} // end constructor

		/**
		 * Add custom fields to $item nav object
		 * in order to be used in custom Walker
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function add_custom_nav_fields( $menu_item )
		{
			foreach ( $this->custom_fields as $key ) {
				$menu_item->$key = get_post_meta( $menu_item->ID, '_menu_item_megamenu_' . $key, true );
			}

			return $menu_item;

		}

		/**
		 * Save menu custom fields
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args )
		{

			foreach ( $this->custom_fields as $key ) {
				if ( !isset( $_REQUEST[ 'menu-item-megamenu-' . $key ][ $menu_item_db_id ] ) ) {
					$_REQUEST[ 'menu-item-megamenu-' . $key ][ $menu_item_db_id ] = '';
				}
				$value = $_REQUEST[ 'menu-item-megamenu-' . $key ][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_menu_item_megamenu_' . $key, $value );
			}

		}

		/**
		 * Define new Walker edit
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function edit_walker( $walker, $menu_id )
		{
			return 'Walker_Nav_Menu_Edit_Custom';
		}

		/**
		 * Register megamenu javascript assets
		 *
		 * @return void
		 *
		 * @since  1.0
		 */
		function register_scripts( $hook )
		{
			if ( 'nav-menus.php' != $hook ) {
				return;
			}
			wp_enqueue_style( 'ciloe-megamenu', get_template_directory_uri() . '/framework/includes/megamenu/css/megamenu.min.css' );
			wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/framework/includes/megamenu/css/magnific-popup.min.css' );
			wp_enqueue_style( 'font-awesome', trailingslashit( get_template_directory_uri() ) . 'assets/css/font-awesome.min.css', array(), '2.4' );
			wp_enqueue_media();
			wp_enqueue_script( 'ciloe-megamenu', get_template_directory_uri() . '/framework/includes/megamenu/js/megamenu.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/framework/includes/megamenu/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );

			wp_localize_script( 'ciloe-megamenu', 'ciloe_ajax_backend', array(
					'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
					'security' => wp_create_nonce( 'ciloe_ajax_backend' ),
				)
			);
		}

		function includes()
		{
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/nav_menu_custom_fields.php' );
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/nav_edit_custom_walker.php' );
			require_once get_parent_theme_file_path( '/framework/includes/megamenu/walker.php' );
		}

		public function set_fonts()
		{
			$this->lits_fonts = array(
				array(
					'title' => esc_html__( 'Font Awesome', 'ciloe' ),
					'fonts' => array(
						'fa fa-car', 'fa fa-map-marker', 'fa fa-phone', 'fa fa-envelope', 'fa fa-glass ', 'fa fa-music ', 'fa fa-search ', 'fa fa-envelope-o ', 'fa fa-heart ', 'fa fa-star ', 'fa fa-star-o ', 'fa fa-user ', 'fa fa-film ', 'fa fa-th-large ', 'fa fa-th ', 'fa fa-th-list ', 'fa fa-check ', 'fa fa-remove ', 'fa fa-search-plus ', 'fa fa-search-minus ', 'fa fa-power-off ', 'fa fa-signal ', 'fa fa-gear ', 'fa fa-trash-o ', 'fa fa-home ', 'fa fa-file-o ', 'fa fa-clock-o ', 'fa fa-road ', 'fa fa-download ', 'fa fa-arrow-circle-o-down ', 'fa fa-arrow-circle-o-up ', 'fa fa-inbox ', 'fa fa-play-circle-o ', 'fa fa-rotate-right ', 'fa fa-refresh ', 'fa fa-list-alt ', 'fa fa-lock ', 'fa fa-flag ', 'fa fa-headphones ', 'fa fa-volume-off ', 'fa fa-volume-down ', 'fa fa-volume-up ', 'fa fa-qrcode ', 'fa fa-barcode ', 'fa fa-tag ', 'fa fa-tags ', 'fa fa-book ', 'fa fa-bookmark ', 'fa fa-print ', 'fa fa-camera ', 'fa fa-font ', 'fa fa-bold ', 'fa fa-italic ', 'fa fa-text-height ', 'fa fa-text-width ', 'fa fa-align-left ', 'fa fa-align-center ', 'fa fa-align-right ', 'fa fa-align-justify ', 'fa fa-list ', 'fa fa-dedent ', 'fa fa-indent ', 'fa fa-video-camera ', 'fa fa-photo ', 'fa fa-pencil ', 'fa fa-map-marker ', 'fa fa-adjust ', 'fa fa-tint ', 'fa fa-edit ', 'fa fa-share-square-o ', 'fa fa-check-square-o ', 'fa fa-arrows ', 'fa fa-step-backward ', 'fa fa-fast-backward ', 'fa fa-backward ', 'fa fa-play ', 'fa fa-pause ', 'fa fa-stop ', 'fa fa-forward ', 'fa fa-fast-forward ', 'fa fa-step-forward ', 'fa fa-eject ', 'fa fa-chevron-left ', 'fa fa-chevron-right ', 'fa fa-plus-circle ', 'fa fa-minus-circle ', 'fa fa-times-circle ', 'fa fa-check-circle ', 'fa fa-question-circle ', 'fa fa-info-circle ', 'fa fa-crosshairs ', 'fa fa-times-circle-o ', 'fa fa-check-circle-o ', 'fa fa-ban ', 'fa fa-arrow-left ', 'fa fa-arrow-right ', 'fa fa-arrow-up ', 'fa fa-arrow-down ', 'fa fa-mail-forward ', 'fa fa-expand ', 'fa fa-compress ', 'fa fa-plus ', 'fa fa-minus ', 'fa fa-asterisk ', 'fa fa-exclamation-circle ', 'fa fa-gift ', 'fa fa-leaf ', 'fa fa-fire ', 'fa fa-eye ', 'fa fa-eye-slash ', 'fa fa-warningle ', 'fa fa-plane ', 'fa fa-calendar ', 'fa fa-random ', 'fa fa-comment ', 'fa fa-magnet ', 'fa fa-chevron-up ', 'fa fa-chevron-down ', 'fa fa-retweet ', 'fa fa-shopping-cart ', 'fa fa-folder ', 'fa fa-folder-open ', 'fa fa-arrows-v ', 'fa fa-arrows-h ', 'fa fa-bar-chart-o ', 'fa fa-twitter-square ', 'fa fa-facebook-square ', 'fa fa-camera-retro ', 'fa fa-key ', 'fa fa-gears ', 'fa fa-comments ', 'fa fa-thumbs-o-up ', 'fa fa-thumbs-o-down ', 'fa fa-star-half ', 'fa fa-heart-o ', 'fa fa-sign-out ', 'fa fa-linkedin-square ', 'fa fa-thumb-tack ', 'fa fa-external-link ', 'fa fa-sign-in ', 'fa fa-trophy ', 'fa fa-github-square ', 'fa fa-upload ', 'fa fa-lemon-o ', 'fa fa-phone ', 'fa fa-square-o ', 'fa fa-bookmark-o ', 'fa fa-phone-square ', 'fa fa-twitter ', 'fa fa-facebook-f ', 'fa fa-github ', 'fa fa-unlock ', 'fa fa-credit-card ', 'fa fa-feed ', 'fa fa-hdd-o ', 'fa fa-bullhorn ', 'fa fa-bell ', 'fa fa-certificate ', 'fa fa-hand-o-right ', 'fa fa-hand-o-left ', 'fa fa-hand-o-up ', 'fa fa-hand-o-down ', 'fa fa-arrow-circle-left ', 'fa fa-arrow-circle-right ', 'fa fa-arrow-circle-up ', 'fa fa-arrow-circle-down ', 'fa fa-globe ', 'fa fa-wrench ', 'fa fa-tasks ', 'fa fa-filter ', 'fa fa-briefcase ', 'fa fa-arrows-alt ', 'fa fa-group ', 'fa fa-chain ', 'fa fa-cloud ', 'fa fa-flask ', 'fa fa-cut ', 'fa fa-copy ', 'fa fa-paperclip ', 'fa fa-save ', 'fa fa-square ', 'fa fa-navicon ', 'fa fa-list-ul ', 'fa fa-list-ol ', 'fa fa-strikethrough ', 'fa fa-underline ', 'fa fa-table ', 'fa fa-magic ', 'fa fa-truck ', 'fa fa-pinterest ', 'fa fa-pinterest-square ', 'fa fa-google-plus-square ', 'fa fa-google-plus ', 'fa fa-money ', 'fa fa-caret-down ', 'fa fa-caret-up ', 'fa fa-caret-left ', 'fa fa-caret-right ', 'fa fa-columns ', 'fa fa-unsorted ', 'fa fa-sort-down ', 'fa fa-sort-up ', 'fa fa-envelope ', 'fa fa-linkedin ', 'fa fa-rotate-left ', 'fa fa-legal ', 'fa fa-dashboard ', 'fa fa-comment-o ', 'fa fa-comments-o ', 'fa fa-flash ', 'fa fa-sitemap ', 'fa fa-umbrella ', 'fa fa-paste ', 'fa fa-lightbulb-o ', 'fa fa-exchange ', 'fa fa-cloud-download ', 'fa fa-cloud-upload ', 'fa fa-user-md ', 'fa fa-stethoscope ', 'fa fa-suitcase ', 'fa fa-bell-o ', 'fa fa-coffee ', 'fa fa-cutlery ', 'fa fa-file-text-o ', 'fa fa-building-o ', 'fa fa-hospital-o ', 'fa fa-ambulance ', 'fa fa-medkit ', 'fa fa-fighter-jet ', 'fa fa-beer ', 'fa fa-h-square ', 'fa fa-plus-square ', 'fa fa-angle-double-left ', 'fa fa-angle-double-right ', 'fa fa-angle-double-up ', 'fa fa-angle-double-down ', 'fa fa-angle-left ', 'fa fa-angle-right ', 'fa fa-angle-up ', 'fa fa-angle-down ', 'fa fa-desktop ', 'fa fa-laptop ', 'fa fa-tablet ', 'fa fa-mobile-phone ', 'fa fa-circle-o ', 'fa fa-quote-left ', 'fa fa-quote-right ', 'fa fa-spinner ', 'fa fa-circle ', 'fa fa-mail-reply ', 'fa fa-github-alt ', 'fa fa-folder-o ', 'fa fa-folder-open-o ', 'fa fa-smile-o ', 'fa fa-frown-o ', 'fa fa-meh-o ', 'fa fa-gamepad ', 'fa fa-keyboard-o ', 'fa fa-flag-o ', 'fa fa-flag-checkered ', 'fa fa-terminal ', 'fa fa-code ', 'fa fa-mail-reply-all ', 'fa fa-star-half-empty ', 'fa fa-location-arrow ', 'fa fa-crop ', 'fa fa-code-fork ', 'fa fa-unlink ', 'fa fa-question ', 'fa fa-info ', 'fa fa-exclamation ', 'fa fa-superscript ', 'fa fa-subscript ', 'fa fa-eraser ', 'fa fa-puzzle-piece ', 'fa fa-microphone ', 'fa fa-microphone-slash ', 'fa fa-shield ', 'fa fa-calendar-o ', 'fa fa-fire-extinguisher ', 'fa fa-rocket ', 'fa fa-maxcdn ', 'fa fa-chevron-circle-left ', 'fa fa-chevron-circle-right ', 'fa fa-chevron-circle-up ', 'fa fa-chevron-circle-down ', 'fa fa-html5 ', 'fa fa-css3 ', 'fa fa-anchor ', 'fa fa-unlock-alt ', 'fa fa-bullseye ', 'fa fa-ellipsis-h ', 'fa fa-ellipsis-v ', 'fa fa-rss-square ', 'fa fa-play-circle ', 'fa fa-ticket ', 'fa fa-minus-square ', 'fa fa-minus-square-o ', 'fa fa-level-up ', 'fa fa-level-down ', 'fa fa-check-square ', 'fa fa-pencil-square ', 'fa fa-external-link-square ', 'fa fa-share-square ', 'fa fa-compass ', 'fa fa-toggle-downn ', 'fa fa-toggle-up ', 'fa fa-toggle-rightht ', 'fa fa-euro ', 'fa fa-gbp ', 'fa fa-dollar ', 'fa fa-rupee ', 'fa fa-cny ', 'fa fa-ruble ', 'fa fa-won ', 'fa fa-bitcoin ', 'fa fa-file ', 'fa fa-file-text ', 'fa fa-sort-alpha-asc ', 'fa fa-sort-alpha-desc ', 'fa fa-sort-amount-asc ', 'fa fa-sort-amount-desc ', 'fa fa-sort-numeric-asc ', 'fa fa-sort-numeric-desc ', 'fa fa-thumbs-up ', 'fa fa-thumbs-down ', 'fa fa-youtube-square ', 'fa fa-youtube ', 'fa fa-xing ', 'fa fa-xing-square ', 'fa fa-youtube-play ', 'fa fa-dropbox ', 'fa fa-stack-overflow ', 'fa fa-instagram ', 'fa fa-flickr ', 'fa fa-adn ', 'fa fa-bitbucket ', 'fa fa-bitbucket-square ', 'fa fa-tumblr ', 'fa fa-tumblr-square ', 'fa fa-long-arrow-down ', 'fa fa-long-arrow-up ', 'fa fa-long-arrow-left ', 'fa fa-long-arrow-right ', 'fa fa-apple ', 'fa fa-windows ', 'fa fa-android ', 'fa fa-linux ', 'fa fa-dribbble ', 'fa fa-skype ', 'fa fa-foursquare ', 'fa fa-trello ', 'fa fa-female ', 'fa fa-male ', 'fa fa-gittip ', 'fa fa-sun-o ', 'fa fa-moon-o ', 'fa fa-archive ', 'fa fa-bug ', 'fa fa-vk ', 'fa fa-weibo ', 'fa fa-renren ', 'fa fa-pagelines ', 'fa fa-stack-exchange ', 'fa fa-arrow-circle-o-right ', 'fa fa-arrow-circle-o-left ', 'fa fa-toggle-leftt ', 'fa fa-dot-circle-o ', 'fa fa-wheelchair ', 'fa fa-vimeo-square ', 'fa fa-turkish-lira ', 'fa fa-plus-square-o ', 'fa fa-space-shuttle ', 'fa fa-slack ', 'fa fa-envelope-square ', 'fa fa-wordpress ', 'fa fa-openid ', 'fa fa-institution ', 'fa fa-mortar-board ', 'fa fa-yahoo ', 'fa fa-google ', 'fa fa-reddit ', 'fa fa-reddit-square ', 'fa fa-stumbleupon-circle ', 'fa fa-stumbleupon ', 'fa fa-delicious ', 'fa fa-digg ', 'fa fa-pied-piper ', 'fa fa-pied-piper-alt ', 'fa fa-drupal ', 'fa fa-joomla ', 'fa fa-language ', 'fa fa-fax ', 'fa fa-building ', 'fa fa-child ', 'fa fa-paw ', 'fa fa-spoon ', 'fa fa-cube ', 'fa fa-cubes ', 'fa fa-behance ', 'fa fa-behance-square ', 'fa fa-steam ', 'fa fa-steam-square ', 'fa fa-recycle ', 'fa fa-automobile ', 'fa fa-cab ', 'fa fa-tree ', 'fa fa-spotify ', 'fa fa-deviantart ', 'fa fa-soundcloud ', 'fa fa-database ', 'fa fa-file-pdf-o ', 'fa fa-file-word-o ', 'fa fa-file-excel-o ', 'fa fa-file-powerpoint-o ', 'fa fa-file-photo-o ', 'fa fa-file-zip-o ', 'fa fa-file-sound-o ', 'fa fa-file-movie-o ', 'fa fa-file-code-o ', 'fa fa-vine ', 'fa fa-codepen ', 'fa fa-jsfiddle ', 'fa fa-life-bouy ', 'fa fa-circle-o-notch ', 'fa fa-ra ', 'fa fa-ge ', 'fa fa-git-square ', 'fa fa-git ', 'fa fa-y-combinator-square ', 'fa fa-tencent-weibo ', 'fa fa-qq ', 'fa fa-wechat ', 'fa fa-send ', 'fa fa-send-o ', 'fa fa-history ', 'fa fa-circle-thin ', 'fa fa-header ', 'fa fa-paragraph ', 'fa fa-sliders ', 'fa fa-share-alt ', 'fa fa-share-alt-square ', 'fa fa-bomb ', 'fa fa-soccer-ball-o ', 'fa fa-tty ', 'fa fa-binoculars ', 'fa fa-plug ', 'fa fa-slideshare ', 'fa fa-twitch ', 'fa fa-yelp ', 'fa fa-newspaper-o ', 'fa fa-wifi ', 'fa fa-calculator ', 'fa fa-paypal ', 'fa fa-google-wallet ', 'fa fa-cc-visa ', 'fa fa-cc-mastercard ', 'fa fa-cc-discover ', 'fa fa-cc-amex ', 'fa fa-cc-paypal ', 'fa fa-cc-stripe ', 'fa fa-bell-slash ', 'fa fa-bell-slash-o ', 'fa fa-trash ', 'fa fa-copyright ', 'fa fa-at ', 'fa fa-eyedropper ', 'fa fa-paint-brush ', 'fa fa-birthday-cake ', 'fa fa-area-chart ', 'fa fa-pie-chart ', 'fa fa-line-chart ', 'fa fa-lastfm ', 'fa fa-lastfm-square ', 'fa fa-toggle-off ', 'fa fa-toggle-on ', 'fa fa-bicycle ', 'fa fa-bus ', 'fa fa-ioxhost ', 'fa fa-angellist ', 'fa fa-cc ', 'fa fa-shekel ', 'fa fa-meanpath ', 'fa fa-buysellads ', 'fa fa-connectdevelop ', 'fa fa-dashcube ', 'fa fa-forumbee ', 'fa fa-leanpub ', 'fa fa-sellsy ', 'fa fa-shirtsinbulk ', 'fa fa-simplybuilt ', 'fa fa-skyatlas ', 'fa fa-cart-plus ', 'fa fa-cart-arrow-down ', 'fa fa-diamond ', 'fa fa-ship ', 'fa fa-user-secret ', 'fa fa-motorcycle ', 'fa fa-street-view ', 'fa fa-heartbeat ', 'fa fa-venus ', 'fa fa-mars ', 'fa fa-mercury ', 'fa fa-intersex ', 'fa fa-transgender-alt ', 'fa fa-venus-double ', 'fa fa-mars-double ', 'fa fa-venus-mars ', 'fa fa-mars-stroke ', 'fa fa-mars-stroke-v ', 'fa fa-mars-stroke-h ', 'fa fa-neuter ', 'fa fa-genderless ', 'fa fa-facebook-official ', 'fa fa-pinterest-p ', 'fa fa-whatsapp ', 'fa fa-server ', 'fa fa-user-plus ', 'fa fa-user-times ', 'fa fa-hotel ', 'fa fa-viacoin ', 'fa fa-train ', 'fa fa-subway ', 'fa fa-medium ', 'fa fa-yc ', 'fa fa-optin-monster ', 'fa fa-opencart ', 'fa fa-expeditedssl ', 'fa fa-battery-4 ', 'fa fa-battery-3ters ', 'fa fa-battery-2 ', 'fa fa-battery-1 ', 'fa fa-battery-0 ', 'fa fa-mouse-pointer ', 'fa fa-i-cursor ', 'fa fa-object-group ', 'fa fa-object-ungroup ', 'fa fa-sticky-note ', 'fa fa-sticky-note-o ', 'fa fa-cc-jcb ', 'fa fa-cc-diners-club ', 'fa fa-clone ', 'fa fa-balance-scale ', 'fa fa-hourglass-o ', 'fa fa-hourglass-1 ', 'fa fa-hourglass-2 ', 'fa fa-hourglass-3 ', 'fa fa-hourglass ', 'fa fa-hand-grab-o ', 'fa fa-hand-stop-o ', 'fa fa-hand-scissors-o ', 'fa fa-hand-lizard-o ', 'fa fa-hand-spock-o ', 'fa fa-hand-pointer-o ', 'fa fa-hand-peace-o ', 'fa fa-trademark ', 'fa fa-registered ', 'fa fa-creative-commons ', 'fa fa-gg ', 'fa fa-gg-circle ', 'fa fa-tripadvisor ', 'fa fa-odnoklassniki ', 'fa fa-odnoklassniki-square ', 'fa fa-get-pocket ', 'fa fa-wikipedia-w ', 'fa fa-safari ', 'fa fa-chrome ', 'fa fa-firefox ', 'fa fa-opera ', 'fa fa-internet-explorer ', 'fa fa-tv ', 'fa fa-contao ', 'fa fa-500px ', 'fa fa-amazon ', 'fa fa-calendar-plus-o ', 'fa fa-calendar-minus-o ', 'fa fa-calendar-times-o ', 'fa fa-calendar-check-o ', 'fa fa-industry ', 'fa fa-map-pin ', 'fa fa-map-signs ', 'fa fa-map-o ', 'fa fa-map ', 'fa fa-commenting ', 'fa fa-commenting-o ', 'fa fa-houzz ', 'fa fa-vimeo ', 'fa fa-black-tie ', 'fa fa-fonticons ',
					),

				),
			);
		}

		public function megamenu_load_font_icon()
		{
			$id = $_POST[ 'id' ];
			?>
            <div class="icons-popup">
                <div class="inner">
					<?php if ( count( $this->lits_fonts ) > 0 ): ?>
						<?php foreach ( $this->lits_fonts as $item ): ?>
                            <h3 class="title"><?php echo esc_html( $item[ 'title' ] ); ?></h3>
                            <ul class="list-font">
								<?php foreach ( $item[ 'fonts' ] as $font ): ?>
                                    <li class="font-item" data-id="<?php echo esc_attr( $id ); ?>"
                                        data-icon="<?php echo esc_attr( $font ); ?>">
                                        <span class="item-icon <?php echo esc_attr( $font ); ?>"></span>
                                        <!-- <span class="text"><?php echo esc_html( $font ); ?></span> -->
                                    </li>
								<?php endforeach; ?>
                            </ul>
						<?php endforeach; ?>
					<?php else: ?>
						<?php esc_html_e( 'No Font', 'ciloe' ); ?>
					<?php endif; ?>
                </div>
            </div>
			<?php
			die(); // this is required to return a proper result
		}

		public function addShortcodesCustomCss_megamenu()
		{
			$args        = array(
				'posts_per_page' => -1,
				'post_type'      => 'megamenu',
				'post_status'    => 'publish',
			);
			$posts_array = get_posts( $args );

			if ( $posts_array ) {
				$shortcodes_custom_css = '';
				foreach ( $posts_array as $post ) {
					$shortcodes_custom_css .= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
					$shortcodes_custom_css .= get_post_meta( $post->ID, '_ciloe_shortcode_custom_css', true );
					$shortcodes_custom_css .= get_post_meta( $post->ID, '_responsive_js_composer_shortcode_custom_css', true );
				}
				if ( !empty( $shortcodes_custom_css ) ) {
					wp_enqueue_style(
						'ciloe_custom_css',
						get_template_directory_uri() . '/assets/css/customs.css'
					);

					wp_add_inline_style( 'ciloe_custom_css', $shortcodes_custom_css );
				}
			}
		}

	}

	new Ciloe_MegaMenu();
}