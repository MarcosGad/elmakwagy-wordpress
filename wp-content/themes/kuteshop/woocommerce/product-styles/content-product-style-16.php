<?php
/**
 * Name: Product style 16
 * Slug: content-product-style-16
 **/
?>
<?php
remove_action( 'woocommerce_before_shop_loop_item_title', 'kuteshop_group_flash', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
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
			do_action( 'kuteshop_function_shop_loop_item_quickview' );
			?>
        </div>
        <div class="product-info equal-elem">
			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 20
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
            <div class="group-button">
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
				<?php
				if ( !wp_is_mobile() ) {
					do_action( 'kuteshop_function_shop_loop_item_wishlist' );
					do_action( 'kuteshop_function_shop_loop_item_compare' );
				}
				?>
            </div>
        </div>
    </div>
<?php
add_action( 'woocommerce_before_shop_loop_item_title', 'kuteshop_group_flash', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );