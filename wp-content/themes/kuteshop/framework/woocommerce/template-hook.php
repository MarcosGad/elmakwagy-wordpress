<?php
/***
 * Core Name: WooCommerce
 * Version: 1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include_once dirname( __FILE__ ) . '/class-adjacent-products.php';
include_once dirname( __FILE__ ) . '/template-functions.php';

/* GLOBAL PRODUCTS QUERY */
add_action( 'woocommerce_product_query', 'kuteshop_product_custom_query' );

/* REMOVE CSS */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/* REMOVE QUICK VIEW META */
remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );

/* ADD PAGINATION PRODUCT */
add_action( 'woocommerce_single_product_summary', 'kuteshop_single_product_pagination', 1 );

/* REMOVE SIDEBAR */
remove_all_actions( 'woocommerce_sidebar' );

/* REMOVE PRODUCT LINK */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/* REMOVE DESCRIPTION HEADING, INFOMATION HEADING */
add_filter( 'woocommerce_product_description_heading', function () { return ''; } );
add_filter( 'woocommerce_product_additional_information_heading', function () { return ''; } );

/* PAGE TITLE */
add_filter( 'woocommerce_page_title',
	function ( $page_title ) {
		return '<span>' . $page_title . '</span>';
	}
);

/* REMOVE SHOP CONTROL */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

/* REMOVE SUB CATEGORIES */
call_user_func( 'remove' . '_' . 'filter', 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
add_filter( 'woocommerce_before_output_product_categories',
	function () {
		return '<ul class="shop-page columns-' . esc_attr( wc_get_loop_prop( 'columns' ) ) . '">';
	}
);
add_filter( 'woocommerce_after_output_product_categories',
	function () {
		return '</ul>';
	}
);

/* CUSTOM CATALOG ORDERING */
add_filter( 'woocommerce_catalog_orderby',
	function ( $options ) {
		$options['sale']    = esc_html__( 'Sort by Sale', 'kuteshop' );
		$options['on-sale'] = esc_html__( 'Sort by On-Sale', 'kuteshop' );
		$options['feature'] = esc_html__( 'Sort by Feature', 'kuteshop' );

		return $options;
	}
);

/* PRODUCT THUMBNAIL */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'kuteshop_template_loop_product_thumbnail', 10 );

/* POST PER PAGE SHOP */
add_filter( 'loop_shop_per_page', 'kuteshop_loop_shop_per_page', 20 );
add_filter( 'woof_products_query', 'kuteshop_woof_products_query', 20 );

/* MINI CART */
add_filter( 'woocommerce_add_to_cart_fragments', 'kuteshop_cart_link_fragment' );

/* BREADCRUMB */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/* PRODUCT NAME */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'kuteshop_template_loop_product_title', 10 );

/* UPSELL */
$upsells = kuteshop_get_option( 'woo_upsells_enable', 'enable' );
if ( $upsells == 'disable' ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
} elseif ( !is_file( get_template_directory() . '/woocommerce/single-product/up-sells.php' ) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'kuteshop_upsell_display', 15 );
}

/* RELATED */
$related = kuteshop_get_option( 'woo_related_enable', 'enable' );
if ( $related == 'disable' ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
} elseif ( !is_file( get_template_directory() . '/woocommerce/single-product/related.php' ) ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	add_action( 'woocommerce_after_single_product_summary', 'kuteshop_related_products', 20 );
}

/* CROSS SELL */
$crosssell = kuteshop_get_option( 'woo_crosssell_enable', 'enable' );
if ( $crosssell == 'disable' ) {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
} elseif ( !is_file( get_template_directory() . '/woocommerce/single-product/cross-sells.php' ) ) {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	add_action( 'woocommerce_cart_collaterals', 'kuteshop_cross_sell_products', 30 );
}

/* PRODUCT VIDEO */
add_action( 'woocommerce_product_thumbnails', 'kuteshop_product_video', 30 );

/* PRODUCT RATING */
add_filter( 'woocommerce_product_get_rating_html', 'kuteshop_product_get_rating_html', 10, 3 );

/* PRODUCT FLASH */
add_filter( 'woocommerce_sale_flash', 'kuteshop_sale_flash' );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'kuteshop_group_flash', 5 );
add_action( 'woocommerce_single_product_summary', 'kuteshop_group_flash', 10 );

/* PRODUCT COUNTDOWN */
add_action( 'kuteshop_function_shop_loop_item_countdown', 'kuteshop_function_shop_loop_item_countdown' );

/* REMOVE STAR RATING */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );

/* CUSTOM RATING SINGLE */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'kuteshop_ratting_single_product', 5 );

/* VENDOR HOOK */
if ( class_exists( 'WC_Vendors' ) ) {
	remove_action( 'woocommerce_after_shop_loop_item', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 9 );
	add_action( 'woocommerce_shop_loop_item_title', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 9 );
}
if ( class_exists( 'WCMp' ) && !function_exists( 'kuteshop_sold_by_text' ) ) {
	function kuteshop_sold_by_text()
	{
		global $WCMp;
		remove_action( 'woocommerce_after_shop_loop_item', array( $WCMp->vendor_caps, 'wcmp_after_add_to_cart_form' ), 6 );
		add_action( 'woocommerce_shop_loop_item_title', array( $WCMp->vendor_caps, 'wcmp_after_add_to_cart_form' ), 6 );
	}

	add_action( 'init', 'kuteshop_sold_by_text' );
}
if ( class_exists( 'WeDevs_Dokan' ) ) {
	add_action( 'dokan_dashboard_wrap_before', function () { echo '<div class="container dokan-dashboard-container">'; } );
	add_action( 'dokan_dashboard_wrap_after', function () { echo '</div>'; } );
}