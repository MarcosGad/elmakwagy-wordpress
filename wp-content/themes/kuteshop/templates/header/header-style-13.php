<?php
/**
 * Name:  Header style 13
 **/
$enable_theme_options = apply_filters( 'theme_get_option', 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
$header_banner        = apply_filters( 'theme_get_option', 'header_banner' );
$banner_url           = apply_filters( 'theme_get_option', 'header_banner_url', '#' );
if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$header_banner = isset( $data_meta['metabox_header_banner'] ) ? $data_meta['metabox_header_banner'] : $header_banner;
	$banner_url    = isset( $data_meta['metabox_header_banner_url'] ) ? $data_meta['metabox_header_banner_url'] : $banner_url;
}
?>
<header id="header" class="header style13 cart-style12">
	<?php if ( $header_banner ): ?>
        <a href="<?php echo esc_url( $banner_url ); ?>">
			<?php echo wp_get_attachment_image( $header_banner, 'full' ); ?>
        </a>
	<?php endif; ?>
    <div class="header-top">
        <div class="container">
            <div class="top-bar-menu left">
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
				kuteshop_header_control();
				?>
            </div>
            <div class="top-bar-menu right">
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
				kuteshop_header_social();
				?>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="logo">
					<?php do_action( 'kuteshop_get_logo' ); ?>
                </div>
                <div class="header-control">
					<?php
					kuteshop_search_form();
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
            <div class="header-nav-inner main-menu-wapper">
				<?php kuteshop_header_vertical(); ?>
				<?php if ( !wp_is_mobile() && has_nav_menu( 'primary' ) ): ?>
                    <div class="box-header-nav">
                        <div class="main-menu-wapper">
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
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</header>