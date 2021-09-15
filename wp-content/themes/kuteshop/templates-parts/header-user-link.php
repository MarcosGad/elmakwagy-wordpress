<?php
$login_url   = wp_login_url();
$currentUser = wp_get_current_user();
if ( class_exists( 'WooCommerce' ) ) {
	$login_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
}
?>
<div class="block-userlink kuteshop-dropdown">
	<?php if ( is_user_logged_in() ): ?>
        <a data-kuteshop="kuteshop-dropdown" class="woo-wishlist-link"
           href="<?php echo esc_url( $login_url ); ?>">
            <span class="flaticon-user icon"></span>
            <span class="text"><?php echo esc_html( $currentUser->display_name ); ?></span>
        </a>
		<?php if ( function_exists( 'wc_get_account_menu_items' ) ): ?>
            <ul class="sub-menu">
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                    <li class="menu-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php else: ?>
            <ul class="sub-menu">
                <li class="menu-item">
                    <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php esc_html_e( 'Logout', 'kuteshop' ); ?></a>
                </li>
            </ul>
		<?php endif;
	else: ?>
        <a class="woo-wishlist-link" href="<?php echo esc_url( $login_url ); ?>">
            <span class="flaticon-user icon"></span>
            <span class="text"><?php echo esc_html__( 'Login', 'kuteshop' ); ?></span>
        </a>
	<?php endif; ?>
</div>