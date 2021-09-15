<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
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
$shop_layout  = kuteshop_get_option( 'sidebar_shop_page_position', 'left' );
$shop_sidebar = kuteshop_get_option( 'shop_page_sidebar', 'shop-widget-area' );
if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
	$shop_layout = 'full';
}
if ( is_product() ) {
	$shop_layout  = kuteshop_get_option( 'sidebar_product_position', 'left' );
	$shop_sidebar = kuteshop_get_option( 'single_product_sidebar', 'product-widget-area' );
}
$sidebar_class   = array();
$sidebar_class[] = 'sidebar';
if ( $shop_layout != 'full' ) {
	$sidebar_class[] = 'col-lg-3 col-md-4';
}
?>
</div><!-- .main-content -->
<?php if ( $shop_layout != 'full' && is_active_sidebar( $shop_sidebar ) ): ?>
    <div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
        <div id="widget-area" class="widget-area shop-sidebar">
			<?php dynamic_sidebar( $shop_sidebar ); ?>
        </div><!-- .widget-area -->
    </div>
<?php endif; ?>
</div><!-- .row -->
</div><!-- .container -->
</div><!-- .main-container -->
