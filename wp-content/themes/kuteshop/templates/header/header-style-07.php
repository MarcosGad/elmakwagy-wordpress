<?php
/**
 * Name:  Header style 07
 **/
$header_service_box   = apply_filters( 'theme_get_option', 'header_service_box', '' );
$enable_theme_options = apply_filters( 'theme_get_option', 'enable_theme_options' );
$data_meta            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
if ( !empty( $data_meta ) && $enable_theme_options == 1 ) {
	$header_service_box = isset( $data_meta['metabox_header_service_box'] ) ? $data_meta['metabox_header_service_box'] : $header_service_box;
}
?>
<header id="header" class="header style7 cart-style7">
    <div class="header-top">
        <div class="container">
            <div class="top-bar-menu">
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
				kuteshop_header_social();
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
				if ( function_exists( 'kuteshop_header_mini_cart' ) ) {
					kuteshop_header_mini_cart();
				} ?>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="logo">
					<?php kuteshop_get_logo(); ?>
                </div>
                <div class="box-header-nav main-menu-wapper">
					<?php
					if ( !wp_is_mobile() && has_nav_menu( 'primary' ) )
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
                    <div class="block-menu-bar">
                        <a class="menu-bar mobile-navigation" href="#">
                            <span class="flaticon-menu01"></span>
                            <span class="text"><?php echo esc_html__( 'MAIN MENU', 'kuteshop' ); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php if ( $header_service_box != '' ) : ?>
        <div class="header-service">
            <div class="container">
                <div class="row">
					<?php foreach ( $header_service_box as $value ) : ?>
                        <div class="service-item col-md-3 col-sm-6">
                            <figure class="image">
                                <img src="<?php echo wp_get_attachment_image_url( $value['service_box_image'], 'full' ); ?>"
                                     alt="kuteshop">
                            </figure>
                            <h4 class="text"><?php echo esc_html( $value['service_box_text'] ); ?></h4>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-inner">
				<?php
				kuteshop_header_vertical();
				kuteshop_search_form();
				kuteshop_header_control();
				?>
            </div>
        </div>
    </div>
</header>
