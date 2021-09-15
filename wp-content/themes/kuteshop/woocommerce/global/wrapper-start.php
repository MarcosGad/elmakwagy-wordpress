<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Shop layout */
$shop_layout    = kuteshop_get_option( 'sidebar_shop_page_position', 'left' );
if ( is_product() ) {
	$shop_layout = kuteshop_get_option( 'sidebar_product_position', 'left' );
}
if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
	$shop_layout = 'full';
}
/* Main container class */
$main_container_class   = array();
$main_container_class[] = 'main-container shop-page';
if ( $shop_layout == 'full' ) {
	$main_container_class[] = 'no-sidebar';
} else {
	$main_container_class[] = $shop_layout . '-sidebar';
}
/* Main content class */
$main_content_class   = array();
$main_content_class[] = 'main-content';
if ( $shop_layout == 'full' ) {
	$main_content_class[] = 'col-sm-12';
} else {
	$main_content_class[] = 'col-lg-9 col-md-8 has-sidebar';
}
$enable_banner = true;
if ( is_product() ) {
	$enable_banner = false;
}
if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
	$enable_banner = false;
}
?>
<div class="<?php echo esc_attr( implode( ' ', $main_container_class ) ); ?>">
    <div class="container">
        <div class="row">
			<?php kuteshop_woocommerce_breadcrumb(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $main_content_class ) ); ?>">
				<?php
				if ( $enable_banner ) {
					get_template_part( 'templates-parts/shop', 'banner' );
				}
				?>
