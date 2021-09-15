<div id="header-sticky-menu" class="header-sticky-menu cart-style7">
    <div class="container">
        <div class="header-nav-inner">
			<?php kuteshop_header_vertical(); ?>
            <div class="box-header-nav main-menu-wapper">
				<?php
				if ( has_nav_menu( 'primary' ) )
					wp_nav_menu( array(
							'menu'            => 'primary',
							'theme_location'  => 'primary',
							'depth'           => 3,
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'kuteshop-nav main-menu',
						)
					);
				?>
            </div>
			<?php
			if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
				kuteshop_header_mini_cart();
			}
			?>
        </div>
    </div>
</div>