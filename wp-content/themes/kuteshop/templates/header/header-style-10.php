<?php
/**
 * Name:  Header style 10
 **/
?>
<header id="header" class="header style9 style10 cart-style9">
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
					kuteshop_search_form();
					if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
						kuteshop_header_mini_cart();
					}
					?>
                    <div class="block-menu-bar">
                        <a class="menu-bar mobile-navigation" href="#">
                            <span class="flaticon-menu03"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
