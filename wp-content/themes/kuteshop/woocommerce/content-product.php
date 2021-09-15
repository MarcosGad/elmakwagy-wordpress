<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || !$product->is_visible() ) {
	return;
}

// Custom columns
$woo_bg_items      = kuteshop_get_option( 'kuteshop_woo_bg_items', 4 );
$woo_lg_items      = kuteshop_get_option( 'kuteshop_woo_lg_items', 4 );
$woo_md_items      = kuteshop_get_option( 'kuteshop_woo_md_items', 4 );
$woo_sm_items      = kuteshop_get_option( 'kuteshop_woo_sm_items', 6 );
$woo_xs_items      = kuteshop_get_option( 'kuteshop_woo_xs_items', 6 );
$woo_ts_items      = kuteshop_get_option( 'kuteshop_woo_ts_items', 12 );
$woo_product_style = kuteshop_get_option( 'kuteshop_shop_product_style', 1 );
$shop_display_mode = kuteshop_get_option( 'shop_page_layout', 'grid' );
$classes[]         = 'product-item';
if ( $shop_display_mode == 'grid' ) {
	$classes[] = 'col-bg-' . $woo_bg_items;
	$classes[] = 'col-lg-' . $woo_lg_items;
	$classes[] = 'col-md-' . $woo_md_items;
	$classes[] = 'col-sm-' . $woo_sm_items;
	$classes[] = 'col-xs-' . $woo_xs_items;
	$classes[] = 'col-ts-' . $woo_ts_items;
} else {
	$classes[] = 'list col-sm-12';
}
$template_style = 'style-' . $woo_product_style;
if ( $shop_display_mode == 'grid' ) {
	$classes[] = 'style-' . $woo_product_style;
	if ( $woo_product_style == 2 ||
		$woo_product_style == 4 ||
		$woo_product_style == 8 ||
		$woo_product_style == 9 ||
		$woo_product_style == 10 ) {
		$classes[] = 'style-1';
	}
}
?>
<li <?php wc_product_class( $classes, $product ); ?>>
	<?php if ( $shop_display_mode == 'grid' ):
		wc_get_template_part( 'product-styles/content-product', $template_style );
	else:
		wc_get_template_part( 'content-product', 'list' );
	endif; ?>
</li>