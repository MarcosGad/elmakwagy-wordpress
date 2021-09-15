<?php
/**
 * Name: Product style 01
 * Slug: content-product-style-1
 **/
?>
<div class="product-inner">
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @removed woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
    <div class="product-thumb">
		<?php
		/**
		 * woocommerce_before_shop_loop_item_title hook.
		 *
		 * @hooked kuteshop_group_flash - 5
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
		<?php if ( !wp_is_mobile() ) : ?>
            <div class="group-button">
				<?php
				do_action( 'kuteshop_function_shop_loop_item_wishlist' );
				do_action( 'kuteshop_function_shop_loop_item_compare' );
				do_action( 'kuteshop_function_shop_loop_item_quickview' );
				?>
            </div>
		<?php endif; ?>
        <div class="add-to-cart">
			<?php
			/**
			 * woocommerce_after_shop_loop_item hook.
			 *
			 * @removed woocommerce_template_loop_product_link_close - 5
			 * @hooked woocommerce_template_loop_add_to_cart - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item' );
			?>
        </div>
    </div>
    <div class="product-info equal-elem">
		<?php
		/**
		 * woocommerce_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_item_title' );
		?>
        <div class="group-price">
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 20
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
        </div>
    </div>
</div>