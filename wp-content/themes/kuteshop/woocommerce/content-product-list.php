<?php
/**
 * Name: Content Product List
 * Slug: content-product-list
 **/
global $product;

$availability = $product->get_availability();
$class        = 'available-product ' . $availability['class'];
$text         = $availability['availability'];
?>
<div class="product-inner">
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
    <div class="product-thumb">
		<?php
		/**
		 * woocommerce_before_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
    </div>
    <div class="product-info">
        <div class="info-left">
			<?php
			do_action( 'kuteshop_add_categories_product' );
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
            <div class="sku-product">
				<?php printf( esc_html__( 'Item Code: #%s', 'kuteshop' ), $product->get_sku() ); ?>
            </div>
			<?php if ( !empty( $availability['availability'] ) && $product->get_type() != 'variable' && $product->get_type() != 'external' ) : ?>
                <div class="<?php echo esc_attr( $class ); ?>">
					<?php echo esc_html__( 'Availability: ', 'kuteshop' ) ?>
                    <span><?php echo esc_html( $text ); ?></span>
                </div>
			<?php endif; ?>
        </div>
        <div class="info-right">
            <div class="add-to-cart">
				<?php
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
            </div>
			<?php
			do_action( 'kuteshop_function_shop_loop_item_compare' );
			do_action( 'kuteshop_function_shop_loop_item_wishlist' );
			do_action( 'kuteshop_function_shop_loop_item_quickview' );
			?>
        </div>
        <div class="excerpt-content">
			<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 40, esc_html__( '...', 'kuteshop' ) ); ?>
        </div>
    </div>
</div>
