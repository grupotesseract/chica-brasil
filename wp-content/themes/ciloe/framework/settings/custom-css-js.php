<?php

if ( ! function_exists( 'ciloe_custom_css' ) ) {
	function ciloe_custom_css() {
		$css = '';
		$css .= ciloe_theme_color();
		$css .= ciloe_vc_custom_css_footer();
		wp_enqueue_style( 'ciloe_custom_css', get_theme_file_uri( '/assets/css/customs.css' ), array(), '1.0' );
		wp_add_inline_style( 'ciloe_custom_css', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'ciloe_custom_css', 999 );

if ( ! function_exists( 'ciloe_theme_color' ) ) {
	function ciloe_theme_color() {
		$css = '';
		
		// Typography
		$enable_google_font = ciloe_get_option( 'enable_google_font', false );
		if ( $enable_google_font ) {
			$body_font = ciloe_get_option( 'typography_themes' );
			if ( ! empty( $body_font ) ) {
				$typography_themes['family']  = 'Open Sans';
				$typography_themes['variant'] = '400';
				$body_fontsize        = ciloe_get_option( 'fontsize-body', '15' );
				
				$css .= 'body{';
				$css .= 'font-family: "' . $body_font['family'] . '";';
				if ( '100italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 100;
					font-style: italic;
				';
				} elseif ( '300italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 300;
					font-style: italic;
				';
				} elseif ( '400italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 400;
					font-style: italic;
				';
				} elseif ( '700italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 700;
					font-style: italic;
				';
				} elseif ( '800italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 700;
					font-style: italic;
				';
				} elseif ( '900italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 900;
					font-style: italic;
				';
				} elseif ( 'regular' == $body_font['variant'] ) {
					$css .= 'font-weight: 400;';
				} elseif ( 'italic' == $body_font['variant'] ) {
					$css .= 'font-style: italic;';
				} else {
					$css .= 'font-weight:' . $body_font['variant'] . ';';
				}
				// Body font size
				if ( $body_fontsize ) {
					$css .= 'font-size:' . esc_attr( $body_fontsize ) . 'px;';
				}
				$css .= '}';
				$css .= 'body,
				blockquote, q,button,input[type="submit"],
				.page-links > a, .page-links > span:not(.page-links-title),
				.header-topbar ,
				.main-navigation .main-menu > .menu-item > a,
				.ciloe-custommenu .widgettitle,
				.ciloe-custommenu.default .widgettitle,
				.ciloe-instagram .widgettitle, 
				.menu-social .social-title,
				.instant-search-modal .instant-search-title,
				.instant-search-modal .product-cats label span,
				.search-view ,.currency-language .wcml-dropdown-click a,
				.currency-language .dropdown a,.block-account,.header .ciloe-minicart .mini-cart-icon,
				.header .minicart-content-inner .minicart-title,
				.header .minicart-content-inner .minicart-number-items,
				.header .minicart-items .product-cart .product-detail .product-detail-info .product-quantity,
				.header .minicart-content-inner .subtotal .total-price,
				.header .minicart-content-inner .actions .button,
				.header .empty-title ,
				.header .to-cart,[data-tooltip]::before,.ciloe-newsletter .newsletter-title,
				.banner-page .content-banner .title-page,
				.post-info .cat-post, .post-info .tag-post,
				.post-title,
				.post-date, .post-author,
				.single-container .header-post .cat-post, .single-container .header-post .tag-post,.comments-area .title-comment,
				.comment-top .comment-meta .comment-author,
				.comment-respond .comment-reply-title ,
				.comment-form label ,
				.more-link, .read-more,
				.post-pagination > span:not(.title), .post-pagination a span, .pagination .page-numbers,
				.ciloe-ajax-load a, .more-items .woo-product-loadmore,
				.sidebar .widgettitle,
				.widget-ciloe-newsletter .newsletter-title ,
				.ciloe_latest_posts_widget .latest-post li.post .item-detail .item-name,
				.ciloe_latest_posts_widget .latest-post li.post .item-detail .item-athur ,
				.widget_shopping_cart .woocommerce-mini-cart__total.total,.widget_shopping_cart .woocommerce-mini-cart__buttons .button,
				.WOOF_Widget .woof_container h4,.toolbar-products .category-filter li a,
				.prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title,
				.enable-shop-page-mobile .woocommerce-page-header ul .line-hover a ,.onsale, .onnew,
				.product-inner .add_to_cart_button,
				.product-inner .added_to_cart,
				.product-inner .product_type_variable,
				.product-inner .product_type_simple,
				.product-inner .product_type_external,
				.product-inner .product_type_grouped,.summary .stock ,.reset_variations,.cart .quantity .input-qty,
				.summary .cart .single_add_to_cart_button,div.mfp-wrap div .mfp-close ,.wc-tabs li a,.woocommerce-Tabs-panel h2 ,
				.product-grid-title ,.return-to-shop .button,body .woocommerce table.shop_table thead th,
				body .shop_table tr td.product-stock-status > span,
				body .woocommerce table.shop_table .product-add-to-cart .add_to_cart,.actions-btn .shopping,
				.cart-collaterals .cart_totals h2 ,.wc-proceed-to-checkout .checkout-button,
				.ciloe-block-info .block-title,.ciloe-block-info .block-smtitle,.ciloe-block-info .block-link ,
				.ciloe-banner .block-title,.ciloe-banner .block-smtitle,.media-item-lookbook .item-lookbook-content .lookbook-title,
				.media-item-lookbook .item-lookbook-content .lookbook-desc,.media-item-lookbook .item-lookbook-content > a ,
				.ciloe-categories .info .category-name,.ciloe-categories .category-link,.cat-des,
				.ciloe-tabs .tab-link li a,.ciloe-mapper-btn-link,.woocommerce-page-headermid .title-page,
				.woocommerce-page-header ul .line-hover a,.nav-tabs > li > a,
				.divider,.checkout-before-top.woocommerce-info,
				.woocommerce-billing-fields h3,.woocommerce-shipping-fields h2 ,
				.woocommerce-checkout-review-order-wrap #order_review_heading,
				.page-header .page-title,
				body.search .main-product > h1,.page-header .page-title span,
				body.search .main-product > h1 > span ,.contact-form-container .contact-label,.contact-form-container .wpcf7-submit,
				.page-404 .heading,.page-404 .title,
				.page-404 a.button,body .ziss-item .ziss-figure-wrap .ziss-hotspot-wrap .ziss-hotspot-popup .add_to_cart_button,
				body .ziss-item .ziss-figure-wrap .ziss-hotspot-wrap .ziss-hotspot-popup .added_to_cart

				{
					font-family: "' . $body_font['family'] . '";
				}';
			}
		}
		
		/* Main color */
		$main_color      = ciloe_get_option( 'ciloe_main_color', '#41cce5' );
		$body_text_color = trim( ciloe_get_option( 'ciloe_body_text_color', '' ) );
		$body_text_color = str_replace( "#", '', $body_text_color );
		$main_color      = str_replace( "#", '', $main_color );
		$main_color      = "#" . $main_color;
		
		$css .= '
			a:hover, a:focus, a:active {
				    color: ' . esc_attr( $main_color ) . ';
				}
				blockquote p::before {
				    border-left: 3px solid ' . esc_attr( $main_color ) . ';
				}
				button:hover,
				input[type="submit"]:hover,
				button:focus,
				input[type="submit"]:focus {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.widget_rss .rss-date {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.owl-carousel .owl-dots .owl-dot.active {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.owl-carousel .owl-dots .owl-dot:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.style-3 div.owl-carousel.product-list-owl .owl-nav div:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-custommenu.style4 .widgettitle::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.horizon-menu .main-navigation .main-menu .menu-item:hover > a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.horizon-menu .main-navigation .main-menu > .menu-item .submenu li.active > a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.horizon-menu .main-navigation .main-menu > .menu-item .submenu li > a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.horizon-menu .main-navigation .main-menu .menu-item:hover .toggle-submenu::before {
				    color: ' . esc_attr( $main_color ) . ' !important;
				}
				.box-mobile-menu .back-menu:hover,
				.box-mobile-menu .close-menu:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.box-mobile-menu .main-menu .menu-item.active > a {
				    color: ' . esc_attr( $main_color ) . ';
				}

				.box-mobile-menu .main-menu .menu-item:hover > a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.box-mobile-menu .main-menu .menu-item:hover > .toggle-submenu::before {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.mobile-navigation:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.menu-btn-icon:hover span {
				    background-color: ' . esc_attr( $main_color ) . ' !important;
				}
				.single-product-mobile .product-grid .product-info .price {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-content-single-product-mobile .product-mobile-layout .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs li img.flex-active {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.product-mobile-layout .detail-content .summary .price {
				    background-color: ' . esc_attr( $main_color ) . ';
				}

				.single-product-mobile .owl-products.owl-carousel .owl-dots .owl-dot::before {
				    border: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.close-vertical-menu:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.vertical-menu .main-navigation .main-menu > .menu-item:hover > a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.menu-social .social-list li a:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.header-search-box > .icons:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.instant-search-close:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.instant-search-modal .product-cats label span::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}

				.instant-search-modal .product-cats label span:hover,
				.instant-search-modal .product-cats label.selected span {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.search-view:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before {
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::after {
				    border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
				}
				.currency-language .dropdown > a:hover::after {
				    border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
				}
				.currency-language .dropdown > a:hover::before {
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.currency-language .dropdown .active a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header .ciloe-minicart:hover .mini-cart-icon {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header .ciloe-minicart .mini-cart-icon .minicart-number {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.header .minicart-content-inner .close-minicart:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header .minicart-items .product-cart .product-remove .remove:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header .minicart-content-inner .actions .button:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header .to-cart:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.header .ciloe-minicart.is-adding-to-cart .minicart-content-inner > .minicart-list-items::after {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header .minicart-items .product-cart.loading::after {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header-type-transparent-white .horizon-menu .main-navigation .main-menu .menu-item:hover > a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header-type-transparent-white .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before,
				.header-type-transparent-white .currency-language .dropdown > a:hover::before {
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.header-type-transparent-dark .currency-language .wcml-dropdown-click a:hover,
				.header-type-transparent-dark .currency-language .dropdown a:hover,
				.header-type-transparent-dark .block-account a:hover,
				.header-type-transparent-dark .header-search-box > .icons:hover,
				.header-type-transparent-dark .ciloe-minicart .mini-cart-icon:hover,
				.header-type-transparent-dark .horizon-menu .main-navigation .main-menu .menu-item:hover > a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.header-type-transparent-dark .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::after,
				.header-type-transparent-dark .currency-language .dropdown > a:hover::after {
				    border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
				}
				.header-type-transparent-dark .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before,
				.header-type-transparent-dark .currency-language .dropdown > a:hover::before {
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.hephaistos .tp-bullet:hover, .hephaistos .tp-bullet.selected {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-newsletter .newsletter-form-wrap .submit-newsletter:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-newsletter.style1 .newsletter-form-wrap .submit-newsletter:hover::before {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-newsletter.light .newsletter-form-wrap .submit-newsletter:hover{
					color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-newsletter.style4 .newsletter-form-wrap .submit-newsletter:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-custommenu.style2 .menu > li a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.footer.style1 .ciloe-socials .social-item:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.banner-page .content-banner .breadcrumb-trail .trail-items .trail-item a:hover span {
				    color: ' . esc_attr( $main_color ) . ';
				}

				.banner-page .content-banner .breadcrumb-trail .trail-items .trail-item a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.post-info .cat-post a:hover,
				.post-info .tag-post a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.sticky .post-title a, .sticky .post-name a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.post-title a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.post-author a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.single-container .header-post .cat-post a:hover,
				.single-container .header-post .tag-post a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-social > a:hover{
				    color: ' . esc_attr( $main_color ) . ';
				}
				.user-socials-wrap .user-social:hover { 
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.user-socials-wrap .user-social:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				h3.related-posts-title::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.comments-area .title-comment::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.comment_container .flex a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.comment-respond .comment-reply-title::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.comment-form .form-submit .submit:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.post-product-carousel:hover .icons,
				.social-share:hover .icons {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.blog-content-standard .post-expand .cat-post a:hover{
				    color: ' . esc_attr( $main_color ) . ';
				}
				.blog-content-standard .post-date a:hover{
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-ajax-load a:hover, .more-items .woo-product-loadmore:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.more-items .woo-product-loadmore.loading {
				    border-color: ' . esc_attr( $main_color ) . ';
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.widget_categories ul li.cat-item a:hover,
				.widget_categories ul li.cat-item.current-cat,
				.widget_categories ul li.cat-item.current-cat a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.widget_shopping_cart .woocommerce-mini-cart__buttons .button:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.sidebar .widget ul li a:hover {
					color: ' . esc_attr( $main_color ) . ';
				}
				.WOOF_Widget .woof_container li .icheckbox_flat-purple.hover,
				.WOOF_Widget .woof_container li .iradio_flat-purple.hover,
				.icheckbox_flat-purple.checked,
				.iradio_flat-purple.checked {
				    background: ' . esc_attr( $main_color ) . ' 0 0 !important;
				    border: 1px solid ' . esc_attr( $main_color ) . ' !important;
				}
				.WOOF_Widget .woof_container .icheckbox_flat-purple.checked ~ label,
				.WOOF_Widget .woof_container .iradio_flat-purple.checked ~ label,
				.WOOF_Widget .woof_container li label.hover,
				.WOOF_Widget .woof_container li label.hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				nav.woocommerce-breadcrumb a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.toolbar-products .category-filter li a::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.toolbar-products .category-filter li.active a,
				.toolbar-products .category-filter li a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label.prdctfltr_active > span::before, div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label:hover > span::before {
				    background: ' . esc_attr( $main_color ) . ';
				    border: 1px double ' . esc_attr( $main_color ) . ';
				    color: ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr_woocommerce_filter_submit:hover, .prdctfltr_wc .prdctfltr_buttons .prdctfltr_reset span:hover, .prdctfltr_sale:hover,
				.prdctfltr_instock:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr-pagination-load-more:not(.prdctfltr-ignite) .button:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				div.pf_rngstyle_flat .irs-from::after, div.pf_rngstyle_flat .irs-to::after, div.pf_rngstyle_flat .irs-single::after {
				    border-top-color: ' . esc_attr( $main_color ) . ';
				}
				div.pf_rngstyle_flat .irs-from, div.pf_rngstyle_flat .irs-to, div.pf_rngstyle_flat .irs-single {
				    background: ' . esc_attr( $main_color ) . ';
				}
				div.pf_rngstyle_flat .irs-bar {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.toolbar-products-mobile .cat-item.active, .toolbar-products-mobile .cat-item.active a,
				.real-mobile-toolbar.toolbar-products-shortcode .cat-item.active, .real-mobile-toolbar.toolbar-products-shortcode .cat-item.active a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.toolbar-products-mobile .part-filter-wrap .filter-toggle:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.enable-shop-page-mobile .shop-page a.products-size.products-list.active {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.enable-shop-page-mobile .shop-page .product-inner .price {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.enable-shop-page-mobile .woocommerce-page-header ul .line-hover a:hover,
				.enable-shop-page-mobile .woocommerce-page-header ul .line-hover.active a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.scrollbar-macosx > .scroll-element.scroll-y .scroll-bar {
				    background: ' . esc_attr( $main_color ) . ';
				}
				a.button.btn.ciloe-button.owl-btn-link:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.product-grid .yith-wcqv-button:hover{
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.yith-wcqv-button:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				#yith-quick-view-close:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				#yith-quick-view-content .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs > li img.flex-active {
				    border: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.product-inner .product-title a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.style-3 .yith-wcqv-button {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.product-inner .add_to_cart_button:hover, 
				.product-inner .added_to_cart:hover, 
				.product-inner .product_type_variable:hover, 
				.product-inner .product_type_simple:hover, 
				.product-inner .product_type_external:hover, 
				.product-inner .product_type_grouped:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.product-list .yith-wcwl-add-to-wishlist:hover {
				    border-color: ' . esc_attr( $main_color ) . ';
				    background: ' . esc_attr( $main_color ) . ';
				}
				.product-list .product-inner .add_to_cart_button:hover,
				.product-list .product_type_variable:hover, 
				.product-list .product_type_grouped:hover, 
				.product-list .product_type_simple:hover, 
				.product-list .product_type_external:hover {
				    border-color: ' . esc_attr( $main_color ) . ';
				    background: ' . esc_attr( $main_color ) . ';
				}
				.product-list .yith-wcqv-button:hover {
				    border: 1px solid ' . esc_attr( $main_color ) . ';
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.woocommerce-product-gallery .flex-control-nav.flex-control-thumbs .slick-arrow {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.summary .woocommerce-product-rating .woocommerce-review-link:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.summary .price {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.reset_variations:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				div button.close {
				    color: ' . esc_attr( $main_color ) . ';
				    background-color: transparent;
				}
				.summary .cart .single_add_to_cart_button:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.product_meta a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				/* social-share-product */
				.social-share-product {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.sticky_info_single_product button.ciloe-single-add-to-cart-btn.btn.button {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.gallery_detail .slick-dots li.slick-active button {
				    width: 25px;
				    background: ' . esc_attr( $main_color ) . ';
				}
				.gallery_detail .slick-dots li button:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.big_images .slick-dots li button::before {
				    content: "";
				    border: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.ciloe-bt-video a, .product-360-button a {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.wc-tabs li a::before {
				    border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				p.stars:hover a:before,
				p.stars.selected:not(:hover) a:before {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.product-grid-title::before {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.total-price-html {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.total-price-html {
				    color: ' . esc_attr( $main_color ) . ';
				}
				div.famibt-wrap .famibt-item .famibt-price {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.famibt-wrap ins {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.famibt-messages-wrap a.button.wc-forward:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.return-to-shop .button:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				body .woocommerce table.shop_table tr td.product-remove a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				body .woocommerce table.shop_table .product-add-to-cart .add_to_cart:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.actions-btn .shopping:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.wc-proceed-to-checkout .checkout-button:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-socials.style1 .social-item:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-socials.style2 .social-item:hover {
				    background: ' . esc_attr( $main_color ) . ';
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-socials.style3 .social-item:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				a.title-link:hover{
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-block-info .block-price {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-block-info .block-link:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-banner .block-title a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				a.block-link:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-banner.style1 .timers .box .time {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-banner.style3 .block-link:hover {
				    background-color: transparent;
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-banner.style4 .block-link:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-banner.style6 a.block-link:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-banner.style5:hover a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.style1 .has-line::before {
				    border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.media-item-lookbook .item-lookbook-content .lookbook-title a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.media-item-lookbook .item-lookbook-content > a:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-categories .info .category-name a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-categories .category-link:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-categories.default .info .category-name a:hover,
				.ciloe-categories.default .category-link:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-tabs .tab-link li a:hover, .ciloe-tabs .tab-link li.active a {
				    background: ' . esc_attr( $main_color ) . ';
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.ciloe-mapper-btn-link:hover {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				.woocommerce-MyAccount-content input.button:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.woocommerce-MyAccount-navigation > ul li.is-active a {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.shop-sidebar .widget ul li a:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.shop-sidebar .widget ul li a:hover::before {
				    background: ' . esc_attr( $main_color ) . ' none repeat scroll 0 0;
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.products-size.active svg, .products-size:hover svg {
				    stroke: ' . esc_attr( $main_color ) . ';
				    fill: ' . esc_attr( $main_color ) . ';
				}
				.price_slider_amount .button:hover, .price_slider_amount .button:focus {
				    background-color: ' . esc_attr( $main_color ) . ';
				    border: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.error404 .ciloe-searchform button:hover {
				    background: ' . esc_attr( $main_color ) . ';
				}
				.page-404 a.button {
				    background-color: ' . esc_attr( $main_color ) . ';
				}
				body .ziss-item .ziss-figure-wrap .ziss-hotspot-wrap .ziss-hotspot-popup .add_to_cart_button,
				body .ziss-item .ziss-figure-wrap .ziss-hotspot-wrap .ziss-hotspot-popup .added_to_cart {
				    color: ' . esc_attr( $main_color ) . ';
				}
				.wpb-js-composer div.vc_tta.vc_tta-accordion .vc_active .vc_tta-controls-icon-position-right .vc_tta-controls-icon::before{
				    border-color: ' . esc_attr( $main_color ) . ';
				}
				.modal-content > button.close:hover {
				    color: ' . esc_attr( $main_color ) . ';
				}
			';
		
		if ( $body_text_color != '' && $body_text_color != '#999' && $body_text_color != '#999999' ) {
			$css .= 'body {color: ' . esc_attr( $body_text_color ) . '}';
		}
		
		/* Main Menu Break Point */
		$main_menu_res_break_point = intval( ciloe_get_option( 'main_menu_res_break_point', 1199 ) );
		// 991 is default style sheet css
		if ( $main_menu_res_break_point > 0 && $main_menu_res_break_point != 991 ) {
			$css .= '@media (min-width: ' . esc_attr( $main_menu_res_break_point + 1 ) . 'px) {
						.header-menu.horizon-menu {
					        display: inline-block;
						}
						.mobile-navigation {
						    display: none;
						}
					}';
			$css .= '@media (max-width: ' . esc_attr( $main_menu_res_break_point ) . 'px) {
						.header-menu.horizon-menu {
						    display: none;
						}
						.mobile-navigation {
						    display: inline-block;
						    margin: 0 0 0 15px;
						}
					}';
		}
		
		return $css;
	}
}

if ( ! function_exists( 'ciloe_vc_custom_css_footer' ) ) {
	function ciloe_vc_custom_css_footer() {
		
		$ciloe_footer_options = ciloe_get_option( 'ciloe_footer_options', '' );
		$page_id              = ciloe_get_single_page_id();
		
		$data_option_meta = get_post_meta( $page_id, '_custom_metabox_theme_options', true );
		if ( $page_id > 0 ) {
			$enable_custom_footer = false;
			if ( isset( $data_option_meta['enable_custom_footer'] ) ) {
				$enable_custom_footer = $data_option_meta['enable_custom_footer'];
			}
			if ( $enable_custom_footer ) {
				$ciloe_footer_options = $data_option_meta['ciloe_metabox_footer_options'];
			}
		}
		
		$shortcodes_custom_css = get_post_meta( $ciloe_footer_options, '_wpb_post_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $ciloe_footer_options, '_wpb_shortcodes_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $ciloe_footer_options, '_ciloe_shortcode_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $ciloe_footer_options, '_responsive_js_composer_shortcode_custom_css', true );
		
		return $shortcodes_custom_css;
	}
}

if ( ! function_exists( 'ciloe_write_custom_js ' ) ) {
	function ciloe_write_custom_js() {
		$ciloe_custom_js = ciloe_get_option( 'ciloe_custom_js', '' );
		wp_enqueue_script( 'ciloe-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0' );
		wp_add_inline_script( 'ciloe-script', $ciloe_custom_js );
	}
}
add_action( 'wp_enqueue_scripts', 'ciloe_write_custom_js' );