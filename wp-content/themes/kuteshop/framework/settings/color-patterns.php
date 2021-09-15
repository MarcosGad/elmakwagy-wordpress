<?php
if ( !function_exists( 'kuteshop_custom_inline_css' ) ) {
	function kuteshop_custom_inline_css()
	{
		$css     = kuteshop_theme_color();
		$css     .= kuteshop_vc_custom_css_footer();
		$content = preg_replace( '/\s+/', ' ', $css );
		wp_add_inline_style( 'kuteshop_custom_css', $content );
	}
}
add_action( 'wp_enqueue_scripts', 'kuteshop_custom_inline_css', 999 );
if ( !function_exists( 'kuteshop_theme_color' ) ) {
	function kuteshop_theme_color()
	{
		$main_color = kuteshop_get_option( 'kuteshop_main_color', '#ff3366' );
		$main_style = kuteshop_get_option( 'kuteshop_custom_css', '' );
		$data_meta  = apply_filters( 'theme_get_meta', '_custom_metabox_theme_options', 'metabox_kuteshop_main_color' );
		$main_color = $data_meta != '' ? $data_meta : $main_color;
		/* Main color */
		$css = '
		a:hover,
		a:focus,
		.loading-lazy::after,
		.chosen-container-single .chosen-single:hover,
		.chosen-container-single.chosen-container-active.chosen-with-drop .chosen-single,
		.shopcart-description .product-price,
		.box-header-nav .main-menu .sub-menu>.menu-item:hover>a,
		.price,
		.block-nav-category .vertical-menu .menu-item.show-sub-menu>a,
		.block-nav-category .vertical-menu .menu-item.show-sub-menu>.toggle-sub-menu,
		.widget_product_categories .cat-item.cat-parent>.carets:hover,
		.widget_product_categories .cat-item.current-cat>a,
		.widget_product_categories .cat-item.current-cat>a + span,
		.widget_product_categories .cat-item>a:hover + span,
		.widget_layered_nav .list-group>*.chosen,
		.widget_layered_nav .list-group>*.chosen .count,
		.widget_layered_nav .list-group>a:hover .count,
		.widget .product_list_widget>li .amount,
		.woocommerce-form-login .woocommerce-form__label-for-checkbox:hover,
		.woocommerce #shipping_method input[type="radio"]:hover ~ label,
        .woocommerce #shipping_method input[type="radio"] ~ label:hover,
		.woocommerce #shipping_method input[type="checkbox"]:hover ~ label,
        .woocommerce #shipping_method input[type="checkbox"] ~ label:hover,
		.cart-style2:not(.style6) .link-dropdown .icon,
		.header.style2:not(.style6) .block-wishlist .icon,
		.header.style2:not(.style6) .block-compare .icon,
		.header.style2:not(.style6) .block-menu-bar a.menu-bar,
		.block-language:hover>a, 
		.wcml_currency_switcher:hover a.wcml-cs-item-toggle,
		.header.style4 .block-search .btn-submit:hover,
		.header.style7 .block-search .btn-submit:hover,
		.header.style9 .box-header-nav .main-menu>.menu-item.active>a,
		.header.style9 .box-header-nav .main-menu>.menu-item:hover>a,
		.entry-summary a.compare:hover,
		.entry-summary .yith-wcwl-add-to-wishlist a:hover,
		.entry-summary .yith-ywraq-add-button a:hover,
		.error-404 h1.page-title .hightlight,
		.error-404 .page-content .hightlight,
		.header.style13.cart-style12 .block-minicart .link-dropdown .icon,
		.header.style13 .block-menu-bar a.menu-bar,
		.header.style14 .box-header-nav .main-menu>.menu-item.active>a,
		.header.style14 .box-header-nav .main-menu>.menu-item:hover>a,
		.product-countdown.style1 .title i,
		.product-countdown.style1 .kuteshop-countdown>*,
		.blog-item .read-more,
		.blog-item .read-more:hover,
		.filter-tabs .slick-slide a.cat-active,
		.filter-tabs .slick-slide a:hover,
		.kuteshop-tabs.style4 .tab-link li:hover a,
		.kuteshop-tabs.style4 .tab-link li.active a,
		.kuteshop-blog.style3 .read-more:hover,
		.kuteshop-blog.style5 .read-more:hover,
		.kuteshop-iconbox.style4 .icon,
		#popup-newsletter .close:hover,
		.kuteshop-tabs.style13 .tab-link li.active a,
		.kuteshop-products.style-11 .kuteshop-title,
		.kuteshop-iconbox.style2 .iconbox-inner:hover,
		.kuteshop-blog.style6 .the-author,
		.header.style5 .block-search .btn-submit,
		#popup-newsletter .modal-info .highlight span,
		.entry-summary > .button-wrapper #chart-button:hover,
		.comment-form-cookies-consent label:hover,
		.entry-summary .cart .group_table .woocommerce-grouped-product-list-item__price,
		.ovic-panel-rating .average span,
		#popup-newsletter .modal-info .btn-checkbox label:hover
		{
			color: ' . $main_color . ';
		}
		.normal-effect::after,
		a.backtotop,
		.slick-slider .slick-arrow:hover,
		.block-search .btn-submit,
		.chosen-container .chosen-results li.highlighted,
		.cart-style1 .link-dropdown .icon,
		.shopcart-description .actions a.button-checkout,
		.header.style1 .block-menu-bar a,
		.box-header-nav .main-menu>.menu-item.active>a,
		.box-header-nav .main-menu>.menu-item:hover>a,
		.widget #today,
		.add-to-cart a:hover,
		.modes-mode:hover,
		.modes-mode.active,
		.widget_product_categories .cat-item.current-cat>a::before,
		.widget_product_categories .cat-item>a:hover::before,
		.ui-slider,
		.price_slider_amount .button,
		.widget_layered_nav .color-group>a:hover::after,
		.widget_layered_nav .color-group>*.selected::after,
		.widget_layered_nav .list-group>*.chosen::before,
		.widget_layered_nav .list-group>a:hover::before,
		.entry-summary a.compare:hover::before,
		.product-item a.compare:hover::before,
		a.yith-wcqv-button:hover::before,
		.yith-wcwl-add-to-wishlist a:hover::before,
		.yith-ywraq-add-to-quote a:hover::before,
        .woocommerce-pagination .page-numbers.current,
        .woocommerce-pagination a.page-numbers:hover,
		.navigation .page-numbers.current,
		.navigation a.page-numbers:hover,
		.pagination .page-numbers.current,
		.pagination a.page-numbers:hover,
		.widget form[role="search"] [type="submit"],
		.woocommerce-mini-cart__buttons a.checkout,
		.shop_table .actions>.button:not([disabled]),
		.place-order input#place_order,
		.wc-proceed-to-checkout .button,
		.woocommerce .woocommerce-error .button,
		.woocommerce .woocommerce-info .button,
		.woocommerce .woocommerce-message .button,
		#customer_login input[type="submit"],
		.woocommerce-ResetPassword input[type="submit"],
		.post-item.item-standard .read-more:hover,
		.comment-respond .form-submit .button,
		.header-image .close-image:hover,
		.header.style2:not(.style6) .box-header-nav .main-menu,
		.header.style3 .block-nav-category .block-title,
		.header.style4 .block-nav-category .block-title,
		.header.style6 .block-search .form-search,
		.header.style7 .header-middle,
		.header.style9 .block-minicart .link-dropdown .count,
		.header .header-border,
		.entry-summary .single_add_to_cart_button,
		.entry-summary .added_to_cart,
		#tab-reviews input#submit,
		.kuteshop-products.style-2 .kuteshop-title::before,
		.kuteshop-category.default .button:hover,
		.kuteshop-products .button-brand .button:hover,
		.kuteshop-newsletter .submit-newsletter,
		.product-item.list .add-to-cart a,
		.header.style11 .block-nav-category .block-title,
		.cart-style11 .block-minicart .link-dropdown .count,
		.cart-style12 .block-minicart .link-dropdown,
		.header.style12 .block-menu-bar a.menu-bar,
		.cart-style14  .block-minicart .link-dropdown .count,
		.kuteshop-products.style-15 .kuteshop-title,
		.product-countdown.style3 .kuteshop-countdown>*::before,
		.product-countdown.style4 .kuteshop-countdown>*::before,
		.kuteshop-tabs.style4 .kuteshop-title,
		.kuteshop-products.style-5 .kuteshop-title,
		.product-item.style-5 .add-to-cart a:hover,
		.product-item.style-5 a.compare:hover::before,
		.product-item.style-5 .yith-wcwl-add-to-wishlist a:hover::before,
		.product-item.style-6 .add-to-cart a:hover,
		.product-item.style-6 a.compare:hover::before,
		.product-item.style-6 .yith-wcwl-add-to-wishlist a:hover::before,
		.product-item.style-6 a.yith-wcqv-button:hover::before,
		.product-item.style-7 .add-to-cart a:hover,
		.product-item.style-7 a.compare:hover::before,
		.product-item.style-7 .yith-wcwl-add-to-wishlist a:hover::before,
		.product-item.style-7 a.yith-wcqv-button:hover::before,
		.kuteshop-category.style1 .list-category li a::before,
		.kuteshop-category.style1 .button,
		.kuteshop-category.style2 .button:hover,
		.kuteshop-blog.style3 .blog-date,
		.kuteshop-slider.style4 .slick-slider .slick-arrow:hover,
		.footer.style6 .kuteshop-newsletter.style2 .submit-newsletter,
		.kuteshop-blog.style4 .blog-date,
		.kuteshop-blog.style5 .blog-date,
		.product-item.style-16 .add-to-cart a,
		.kuteshop-tabs.style12 .tab-head .kuteshop-title .text,
		.kuteshop-tabs.style12 .tab-link .slick-arrow:hover,
		.kuteshop-blog.style5 .slick-slider:not(.nav-center) .slick-arrow:hover,
		.kuteshop-products.style-17 .product-list-owl .slick-arrow:hover,
		.kuteshop-products.style-16 .product-list-owl.nav-center .slick-arrow:hover,
		.kuteshop-tabs.style13 .tab-head .tab-link-button a:hover,
		.kuteshop-tabs.style13.cat-active .tab-head .toggle-category,
		.kuteshop-tabs.style13 .tab-head .toggle-category:hover,
		.kuteshop-tabs.style13 .filter-tabs .category-filter,
		.product-item.style-10 .add-to-cart a,
		.product-item.style-11 .add-to-cart a,
		.product-item.style-12 .product-inner .add-to-cart a,
		.footer .widget_tag_cloud .tagcloud a:hover,
		.kuteshop-banner.default:hover .banner-thumb a::before,
		.kuteshop-banner.style1 .product-item .thumb-link::before,
		.product-countdown.style6 .title,
		.kuteshop-products.style-10 .slick-slider.nav-center>.slick-arrow:hover,
		#ship-to-different-address label input[type="checkbox"]:checked + span::before,
		.header.style10 .box-header-nav .main-menu .sub-menu>.menu-item:hover>a,
        .product-item.style-6 .yith-wcwl-wishlistaddedbrowse a::before,
        .product-item.style-6 .yith-wcwl-wishlistexistsbrowse a::before,
        .product-item.style-5 .yith-wcwl-wishlistaddedbrowse a::before,
        .product-item.style-5 .yith-wcwl-wishlistexistsbrowse a::before,
        .product-item.style-6 .compare-button a.compare.added::before,
        .product-item.style-5 .compare-button a.compare.added::before,
        .product-item.style-6 .add-to-cart a.added_to_cart,
        .product-item.style-5 .add-to-cart a.added_to_cart,
        .newsletter-form-wrap.processing::after,
        .ovic-instant-search .view-all,
        .header-sticky-menu.cart-style7 .link-dropdown .count,
        .ovic_bundle-wrap .single_add_to_cart_button,
        .ovic_bundle-wrap .added_to_cart,
        .ovic-panel-rating .process-bar
		{
			background-color: ' . $main_color . ';
		}
		.kuteshop-custommenu.default .title span,
		.modes-mode:hover,
		.modes-mode.active,
		.widget_product_categories .cat-item.current-cat>a::before,
		.widget_product_categories .cat-item>a:hover::before,
		.widget_layered_nav .color-group>a:hover,
		.widget_layered_nav .color-group>*.selected,
		.widget_layered_nav .list-group>*.chosen::before,
		.widget_layered_nav .list-group>a:hover::before,
        .woocommerce-pagination .page-numbers.current,
        .woocommerce-pagination a.page-numbers:hover,
		.navigation .page-numbers.current,
		.navigation a.page-numbers:hover,
		.pagination .page-numbers.current,
		.pagination a.page-numbers:hover,
		.page-title span,
		.flex-control-nav .slick-slide img:hover,
		.kuteshop-tabs.default .tab-link li.active a,
		.kuteshop-tabs.default .tab-link li:hover a,
		.product-item.style-2 .product-inner,
		.kuteshop-tabs.style2 .tab-head .kuteshop-title,
		.kuteshop-products .button-brand .button:hover,
		.header.style13 .box-header-nav .main-menu>.menu-item:hover>a,
		.header.style13 .box-header-nav .main-menu>.menu-item.active>a,
		.kuteshop-products.style-1 .kuteshop-title .title,
		.kuteshop-blog .kuteshop-title.style1 .title,
		.kuteshop-slider.style2 .kuteshop-title .title,
		.product-item.style-7 .add-to-cart a:hover,
		.product-item.style-7 a.compare:hover::before,
		.product-item.style-7 .yith-wcwl-add-to-wishlist a:hover::before,
		.product-item.style-7 a.yith-wcqv-button:hover::before,
		.kuteshop-category.style2 .button:hover,
		.kuteshop-slider.style2 .vc_single_image-wrapper:hover,
		.kuteshop-slider.style4 .vc_single_image-wrapper:hover,
		.kuteshop-testimonials .slick-dots .slick-slide img:hover,
		.kuteshop-blog.style3 .blog-inner::before,
		.kuteshop-blog.style3 .blog-inner::after,
		.kuteshop-blog.style5 .blog-inner::before,
		.kuteshop-blog.style5 .blog-inner::after,
		.kuteshop-tabs.style10 .tab-link li.active a,
		.kuteshop-tabs.style10 .tab-link li a:hover,
		.kuteshop-tabs.style11 .tab-link li.active a,
		.kuteshop-tabs.style11 .tab-link li a:hover,
		.kuteshop-tabs.style12 .tab-link .slick-arrow:hover,
		.kuteshop-products.style-9 .product-list-owl .slick-arrow:hover,
		.kuteshop-blog.style5 .slick-slider:not(.nav-center) .slick-arrow:hover,
		.kuteshop-products.style-17 .product-list-owl .slick-arrow:hover,
		.kuteshop-products.style-16 .product-list-owl.nav-center .slick-arrow:hover,
		.kuteshop-tabs.style13 .tab-head .tab-link-button a:hover,
		.kuteshop-tabs.style13.cat-active .tab-head .toggle-category,
		.kuteshop-tabs.style13 .tab-head .toggle-category:hover,
		.kuteshop-tabs.style13 .content-tabs.has-filter .filter-tabs,
		.product-item.style-10 .product-inner:hover,
		.product-item.style-11 a.compare:hover::before,
		.product-item.style-11 a.yith-wcqv-button:hover::before,
		.product-item.style-11 .yith-wcwl-add-to-wishlist a:hover::before,
		.kuteshop-products.style-14 .product-list-owl .slick-arrow:hover,
		.kuteshop-tabs.style14 .tab-link .slick-arrow:hover,
		.kuteshop-tabs.style14 .tab-link .slick-slide a:hover::before,
		.kuteshop-tabs.style14 .tab-link .slick-slide.active a::before,
		.product-item.style-12 .product-inner:hover .thumb-link::after,
		.product-item.style-12 a.compare:hover::before,
		.product-item.style-12 a.yith-wcqv-button:hover::before,
		.product-item.style-12 .yith-wcwl-add-to-wishlist a:hover::before,
		.footer .widget_tag_cloud .tagcloud a:hover,
		.kuteshop-products.style-10 .slick-slider.nav-center>.slick-arrow:hover,
		#ship-to-different-address label input[type="checkbox"]:checked + span::before
		{
			border-color: ' . $main_color . ';
		}
		.kuteshop-products.style-2 .kuteshop-title:after
		{
			border-bottom-color: ' . $main_color . ';
		}
		.kuteshop-products.loading .content-product-append::after,
        .shop-page ul.products.loading::before,
        .loading-lazy::after,
        .tab-container.loading::after,
        .block-minicart.loading .shopcart-description::after,
        .widget_shopping_cart .woocommerce-mini-cart li .blockUI.blockOverlay::before
		{
			border-top-color: ' . $main_color . ';
		}
		.header.style3 .block-search .form-search
		{
			box-shadow: 0 0 0 2px ' . $main_color . ' inset;
		}
		.header.style13 .block-search .form-search
		{
			box-shadow: 0 0 0 1px ' . $main_color . ' inset;
		}
		@media (min-width: 768px){
			.kuteshop-tabs.style12 .tab-link .slick-slide figure {
				border-left-color: ' . $main_color . ';
			}
		}
		@media (max-width: 767px){
			.kuteshop-tabs.style12 .tab-link .slick-slide figure {
				border-top-color: ' . $main_color . ';
			}
		}
		@media (min-width: 1025px){
				.block-nav-category .vertical-menu li:hover>a, 
				.block-nav-category .view-all-category a:hover{
					color: ' . $main_color . ';
				}
		}
		';
		$css .= $main_style;

		return apply_filters( 'kuteshop_main_custom_css', $css );
	}
}
if ( !function_exists( 'kuteshop_vc_custom_css_footer' ) ) {
	function kuteshop_vc_custom_css_footer()
	{
		$shortcodes_custom_css = '';
		$footer_options        = kuteshop_get_option( 'kuteshop_footer_options', '' );
		$data_meta             = apply_filters( 'theme_get_meta', '_custom_metabox_theme_options', 'kuteshop_metabox_footer_options' );
		$footer_options        = $data_meta != '' ? $data_meta : $footer_options;
		if ( !empty( $footer_options ) ) {
			$shortcodes_custom_css .= get_post_meta( $footer_options, '_wpb_post_custom_css', true );
			$shortcodes_custom_css .= get_post_meta( $footer_options, '_wpb_shortcodes_custom_css', true );
			$shortcodes_custom_css .= get_post_meta( $footer_options, '_Kuteshop_Shortcode_custom_css', true );
			$shortcodes_custom_css .= get_post_meta( $footer_options, '_Ovic_VC_Shortcode_Custom_Css', true );
		}

		return $shortcodes_custom_css;
	}
}
if ( !function_exists( 'kuteshop_write_custom_js ' ) ) {
	function kuteshop_write_custom_js()
	{
		$kuteshop_custom_js = kuteshop_get_option( 'kuteshop_custom_js', '' );
		$content            = preg_replace( '/\s+/', ' ', $kuteshop_custom_js );
		wp_add_inline_script( 'kuteshop-script', $content );
	}
}
add_action( 'wp_enqueue_scripts', 'kuteshop_write_custom_js' );