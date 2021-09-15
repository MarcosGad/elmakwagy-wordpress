<?php
/**
 * Name:  Header style 14
 **/
?>
<header id="header" class="header style14 cart-style14">
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner main-menu-wapper">
				<?php if ( !wp_is_mobile() && has_nav_menu( 'primary' ) ): ?>
                    <div class="box-header-nav">
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
                <div class="logo">
					<?php kuteshop_get_logo(); ?>
                </div>
                <div class="header-control">
					<?php
					kuteshop_user_link();
					kuteshop_header_control();
					if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
						kuteshop_header_mini_cart();
					}
					kuteshop_search_form();
					?>
                    <div class="block-menu-bar">
                        <a class="menu-bar mobile-navigation" href="#">
                            <span class="flaticon-menu01"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>