<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Kuteshop
 * @since 1.0
 * @version 1.0
 */
$sidebar_name = apply_filters( 'theme_get_option', 'shop_page_sidebar', 'shop-widget-area' );
if ( !is_active_sidebar( $sidebar_name ) ) {
	$sidebar_name = 'sidebar-store';
}
?>
<div id="dokan-secondary" class="dokan-clearfix dokan-w3 dokan-store-sidebar" role="complementary"
     style="margin-right:3%;">
    <div class="dokan-widget-area widget-collapse">
        <a href="#" class="sidebar-button"
           data-ocolus="ocolus-dropdown"><?php echo __( 'Sidebar', 'kuteshop' ); ?></a>
		<?php dynamic_sidebar( $sidebar_name ); ?>
    </div>
</div>