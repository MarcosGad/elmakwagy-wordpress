<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
$class = array(
	'row',
	'products',
	'equal-container',
	'better-height',
	'auto-clear',
	'ovic-products',
	'columns-' . esc_attr( wc_get_loop_prop( 'columns' ) ),
);
?>
<div class="shop-before-control">
	<?php
	woocommerce_catalog_ordering();
	kuteshop_product_per_page_tmp();
	kuteshop_shop_display_mode_tmp();
	?>
</div>
<?php echo woocommerce_maybe_show_product_subcategories(); ?>
<ul class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
