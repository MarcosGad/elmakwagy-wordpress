<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Deprecated framework functions from past framework versions. You shouldn't use these
 * functions and look for the alternatives instead. The functions will be removed in a later version.
 *
 */
add_filter( 'theme_get_meta', 'kuteshop_get_meta', 10, 2 );
add_filter( 'theme_get_option', 'kuteshop_get_option', 10, 2 );
add_filter( 'theme_resize_image', 'kuteshop_resize_image', 10, 5 );
add_filter( 'getProducts', 'kuteshop_product_query', 10, 3 );

add_action( 'kuteshop_get_logo', 'kuteshop_get_logo' );
add_action( 'kuteshop_header_control', 'kuteshop_header_control' );
add_action( 'kuteshop_get_header', 'kuteshop_get_header' );
add_action( 'kuteshop_header_sticky', 'kuteshop_header_sticky' );
add_action( 'kuteshop_header_vertical', 'kuteshop_header_vertical' );
add_action( 'kuteshop_search_form', 'kuteshop_search_form' );
add_action( 'kuteshop_user_link', 'kuteshop_user_link' );
add_action( 'kuteshop_header_social', 'kuteshop_header_social' );
add_action( 'kuteshop_page_banner', 'kuteshop_page_banner' );
add_action( 'kuteshop_time_ago', 'kuteshop_time_ago' );
add_action( 'kuteshop_paging_nav', 'kuteshop_paging_nav' );
add_action( 'kuteshop_post_thumbnail', 'kuteshop_post_thumbnail' );
add_action( 'kuteshop_get_footer', 'kuteshop_get_footer' );