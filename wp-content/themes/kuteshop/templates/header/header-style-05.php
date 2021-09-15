<?php
/**
 * Name:  Header style 05
 **/
?>
<header id="header" class="header style2 style5 cart-style2">
    <div class="header-top">
        <div class="container">
            <div class="top-bar-menu left">
				<?php
				kuteshop_header_control();
				kuteshop_header_social();
				?>
            </div>
			<?php if ( has_nav_menu( 'top_right_menu' ) ): ?>
                <div class="top-bar-menu right">
					<?php
					wp_nav_menu( array(
							'menu'            => 'top_right_menu',
							'theme_location'  => 'top_right_menu',
							'depth'           => 1,
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'kuteshop-nav top-bar-menu',
						)
					);
					?>
                </div>
			<?php endif; ?>
        </div>
    </div>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="header-search-box">
					<?php kuteshop_search_form(); ?>
                </div>
                <div class="logo">
					<?php kuteshop_get_logo(); ?>
                </div>
                <div class="header-control">
					<?php
					if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
						kuteshop_header_mini_cart();
					}
					if ( defined( 'YITH_WCWL' ) ) :
						$wishlist_url = YITH_WCWL()->get_wishlist_url();
						if ( !empty( $wishlist_url ) ) : ?>
                            <div class="block-wishlist">
                                <a class="woo-wishlist-link" href="<?php echo esc_url( $wishlist_url ); ?>">
                                    <span class="flaticon-heart01 icon"></span>
                                </a>
                            </div>
						<?php endif;
					endif;
					if ( class_exists( 'YITH_Woocompare' ) ) :
						global $yith_woocompare; ?>
                        <div class="block-compare yith-woocompare-widget">
                            <a href="<?php echo esc_url( $yith_woocompare->obj->view_table_url() ); ?>"
                               class="compare added" rel="nofollow">
                                <span class="flaticon-compare01 icon"></span>
                            </a>
                        </div>
					<?php endif; ?>
                    <div class="block-menu-bar">
                        <a class="menu-bar mobile-navigation" href="#">
                            <span class="flaticon-menu01"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-inner">
				<?php do_action( 'kuteshop_header_vertical' ); ?>
				<?php if ( !wp_is_mobile() && has_nav_menu( 'primary' ) ): ?>
                    <div class="box-header-nav main-menu-wapper">
						<?php
						wp_nav_menu( array(
								'menu'            => 'primary',
								'theme_location'  => 'primary',
								'depth'           => 3,
								'container'       => '',
								'container_class' => '',
								'container_id'    => '',
								'menu_class'      => 'clone-main-menu kuteshop-nav main-menu',
							)
						);
						?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</header>