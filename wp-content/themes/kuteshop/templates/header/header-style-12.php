<?php
/**
 * Name:  Header style 12
 **/
?>
<header id="header" class="header style12 cart-style12">
    <div class="header-top">
        <div class="container">
            <div class="header-top-inner">
                <div class="top-bar-menu left">
					<?php
					if ( has_nav_menu( 'top_left_menu' ) )
						wp_nav_menu( array(
								'menu'            => 'top_left_menu',
								'theme_location'  => 'top_left_menu',
								'depth'           => 1,
								'container'       => '',
								'container_class' => '',
								'container_id'    => '',
								'menu_class'      => 'kuteshop-nav top-bar-menu',
							)
						);
					kuteshop_header_control();
					?>
                </div>
                <div class="top-bar-menu right">
					<?php
					if ( has_nav_menu( 'top_right_menu' ) )
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
					kuteshop_header_social();
					?>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="logo">
					<?php kuteshop_get_logo(); ?>
                </div>
                <div class="header-control main-menu-wapper">
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
					<?php
					if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
						kuteshop_header_mini_cart();
					}
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
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-inner">
				<?php
				kuteshop_header_vertical();
				kuteshop_search_form();
				if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
					kuteshop_header_mini_cart();
				}
				?>
            </div>
        </div>
    </div>
</header>