<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( !function_exists( 'kuteshop_get_logo' ) ) {
	function kuteshop_get_logo()
	{
		$logo_url  = get_theme_file_uri( '/assets/images/logo.png' );
		$logo      = kuteshop_get_option( 'kuteshop_logo' );
		$data_meta = kuteshop_get_meta( '_custom_metabox_theme_options', 'metabox_kuteshop_logo' );
		$logo      = $data_meta != '' ? $data_meta : $logo;
		if ( $logo != '' ) {
			$logo_url = wp_get_attachment_image_url( $logo, 'full' );
		}
		$html = '<a href="' . esc_url( home_url( '/' ) ) . '"><img alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" src="' . esc_url( $logo_url ) . '" class="_rw" /></a>';

		echo apply_filters( 'kuteshop_site_logo', $html );
	}
}
if ( !function_exists( 'kuteshop_header_control' ) ) {
	function kuteshop_header_control()
	{
		get_template_part( 'templates-parts/header', 'control' );
	}
}
if ( !function_exists( 'kuteshop_get_header' ) ) {
	function kuteshop_get_header()
	{
		$kuteshop_used_header = kuteshop_get_option( 'kuteshop_used_header', 'style-01' );
		$data_meta            = kuteshop_get_meta( '_custom_metabox_theme_options', 'kuteshop_metabox_used_header' );
		$kuteshop_used_header = !empty( $data_meta ) ? $data_meta : $kuteshop_used_header;
		if ( !wp_is_mobile() ) {
			kuteshop_header_sticky();
		}
		get_template_part( 'templates/header/header', $kuteshop_used_header );
	}
}
if ( !function_exists( 'kuteshop_header_sticky' ) ) {
	function kuteshop_header_sticky()
	{
		$enable_sticky_menu = kuteshop_get_option( 'kuteshop_enable_sticky_menu' );
		if ( $enable_sticky_menu == 1 && !wp_is_mobile() ) {
			get_template_part( 'templates-parts/header', 'sticky' );
		}
	}
}
if ( !function_exists( 'kuteshop_header_vertical' ) ) {
	function kuteshop_header_vertical()
	{
		get_template_part( 'templates-parts/header', 'vertical' );
	}
}
if ( !function_exists( 'kuteshop_search_form' ) ) {
	function kuteshop_search_form()
	{
		get_template_part( 'templates-parts/header-search', 'form' );
	}
}
if ( !function_exists( 'kuteshop_user_link' ) ) {
	function kuteshop_user_link()
	{
		get_template_part( 'templates-parts/header-user', 'link' );
	}
}
if ( !function_exists( 'kuteshop_header_social' ) ) {
	function kuteshop_header_social()
	{
		get_template_part( 'templates-parts/header', 'social' );
	}
}
if ( !function_exists( 'kuteshop_page_banner' ) ) {
	function kuteshop_page_banner()
	{
		get_template_part( 'templates-parts/page', 'banner' );
	}
}
if ( !function_exists( 'kuteshop_time_ago' ) ) {
	function kuteshop_time_ago( $type = 'post' )
	{
		$d = 'comment' == $type ? 'get_comment_time' : 'get_post_time';
		echo human_time_diff( $d( 'U' ), current_time( 'timestamp' ) ) . " " . esc_html__( 'ago', 'kuteshop' );
	}
}
if ( !function_exists( 'kuteshop_paging_nav' ) ) {
	function kuteshop_paging_nav()
	{
		global $wp_query;
		$max = $wp_query->max_num_pages;
		// Don't print empty markup if there's only one page.
		if ( $max >= 2 ) {
			echo get_the_posts_pagination( array(
					'screen_reader_text' => '&nbsp;',
					'before_page_number' => '',
					'prev_text'          => esc_html__( 'Prev', 'kuteshop' ),
					'next_text'          => esc_html__( 'Next', 'kuteshop' ),
				)
			);
		}
	}
}
if ( !function_exists( 'kuteshop_post_thumbnail' ) ) {
	function kuteshop_post_thumbnail()
	{
		$using_placeholder   = kuteshop_get_option( 'using_placeholder' );
		$sidebar_blog_layout = kuteshop_get_option( 'sidebar_blog_layout', 'left' );
		$kuteshop_blog_lazy  = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
		$lazy_check          = $kuteshop_blog_lazy == 1 ? true : false;
		if ( $using_placeholder != 1 && !has_post_thumbnail() ||
			$sidebar_blog_layout == 'full' && !has_post_thumbnail() ) {
			return;
		}
		if ( $sidebar_blog_layout == 'full' ) {
			$width  = 1170;
			$height = 610;
		} else {
			$width  = 770;
			$height = 454;
		} ?>
        <div class="post-thumb">
			<?php
			if ( is_single() ) {
				the_post_thumbnail( 'full' );
			} else {
				$image_thumb = kuteshop_resize_image( get_post_thumbnail_id(), $width, $height, true, $lazy_check );
				echo '<a href="' . get_permalink() . '">';
				echo wp_specialchars_decode( $image_thumb['img'] );
				echo '</a>';
			}
			?>
        </div>
		<?php
	}
}
if ( !function_exists( 'kuteshop_get_footer' ) ) {
	function kuteshop_get_footer()
	{
		$footer_options        = kuteshop_get_option( 'kuteshop_footer_options', 'style-01' );
		$data_meta             = kuteshop_get_meta( '_custom_metabox_theme_options', 'kuteshop_metabox_footer_options' );
		$footer_options        = $data_meta != '' ? $data_meta : $footer_options;
		$meta_template_style   = get_post_meta( $footer_options, '_custom_footer_options', true );
		$footer_template_style = isset( $meta_template_style['kuteshop_footer_style'] ) ? $meta_template_style['kuteshop_footer_style'] : 'style-01';
		$query                 = new WP_Query( array(
				'p'              => $footer_options,
				'post_type'      => 'footer',
				'posts_per_page' => 1,
			)
		);
		if ( $query->have_posts() ):
			while ( $query->have_posts() ): $query->the_post();
				get_template_part( 'templates/footer/footer', $footer_template_style );
			endwhile;
		endif;
		wp_reset_postdata();
	}
}

if ( !function_exists( 'kuteshop_before_mobile_menu' ) ) {
	function kuteshop_before_mobile_menu( $menu_locations, $data_menus )
	{
		$avatar_id    = null;
		$class        = 'login';
		$login        = wp_login_url();
		$current_user = wp_get_current_user();
		$author_name  = esc_html__( 'Guest', 'kuteshop' );
		$login_text   = esc_html__( 'Login', 'kuteshop' );
		$author_email = esc_html__( 'Example@email.com', 'kuteshop' );
		if ( class_exists( 'WooCommerce' ) && !empty( get_option( 'woocommerce_myaccount_page_id' ) ) ) {
			$login = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		}
		$logout = $login;
		if ( is_user_logged_in() ) {
			$class        = 'logout';
			$avatar_id    = $current_user->ID;
			$author_email = $current_user->user_email;
			$author_name  = $current_user->display_name;
			$login_text   = esc_html__( 'Logout', 'kuteshop' );
			$logout       = wp_logout_url();
		}
		$avatar         = get_avatar_url( $avatar_id,
			array( 'size' => 60 )
		);
		$background_url = get_theme_file_uri( 'assets/images/menu-mobile.jpg' );
		if ( function_exists( 'jetpack_photon_url' ) ) {
			$background_url = jetpack_photon_url( $background_url );
		}
		?>
        <div class="head-menu-mobile" style="background-image: url(<?php echo esc_url( $background_url ); ?>)">
            <a href="<?php echo esc_url( $logout ) ?>"
               class="action <?php echo esc_attr( $class ); ?>">
                <span class="pe-7s-power"></span>
				<?php echo esc_html( $login_text ); ?>
            </a>
            <a href="<?php echo esc_url( $login ) ?>" class="avatar">
                <figure>
                    <img src="<?php echo esc_url( $avatar ) ?>"
                         alt="<?php echo esc_attr__( 'Avatar Mobile', 'kuteshop' ) ?>">
                </figure>
            </a>
            <div class="author">
                <a href="<?php echo esc_url( $login ) ?>"
                   class="name">
					<?php echo esc_html( $author_name ); ?>
                    <span class="email"><?php echo esc_html( $author_email ); ?></span>
                </a>
            </div>
        </div>
		<?php
	}

	add_action( 'ovic_before_html_mobile_menu', 'kuteshop_before_mobile_menu', 10, 2 );
}
if ( !function_exists( 'kuteshop_after_mobile_menu' ) ) {
	function kuteshop_after_mobile_menu( $menu_locations, $data_menus )
	{
		?>
        <div class="footer-menu-mobile">
			<?php kuteshop_header_social(); ?>
        </div>
		<?php
	}

	add_action( 'ovic_after_html_mobile_menu', 'kuteshop_after_mobile_menu', 10, 2 );
}

if ( !function_exists( 'kuteshop_menu_locations_mobile' ) ) {
	function kuteshop_menu_locations_mobile( $menus, $locations )
	{
		$menu_location = '';
		if ( isset( $locations['ovic_mobile_menu'] ) ) {
			$menu_location = $locations['ovic_mobile_menu'];
		} elseif ( isset( $locations['primary'] ) ) {
			$menu_location = $locations['primary'];
		}
		if ( !empty( $menu_location ) ) {
			$mobile_menu = wp_get_nav_menu_object( $menu_location );
			$menus       = array( $mobile_menu->slug );
		}

		return $menus;
	}

	add_filter( 'ovic_menu_locations_mobile', 'kuteshop_menu_locations_mobile', 10, 2 );
}

if ( !function_exists( 'kuteshop_menu_mobile_vertical' ) ) {
	function kuteshop_menu_mobile_vertical()
	{
		$enable_vertical_menu = kuteshop_get_option( 'enable_vertical_menu' );
		$vertical_menu_title  = kuteshop_get_option( 'vertical_menu_title', 'Shop By Category' );
		/* META BOX THEME OPTIONS */
		$enable_theme_options = kuteshop_get_option( 'enable_theme_options' );
		$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
		if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
			$vertical_menu_title  = isset( $meta_data['metabox_vertical_menu_title'] ) ? $meta_data['metabox_vertical_menu_title'] : '';
			$enable_vertical_menu = isset( $meta_data['metabox_enable_vertical_menu'] ) ? $meta_data['metabox_enable_vertical_menu'] : '';
		}

		if ( $enable_vertical_menu == 1 && has_nav_menu( 'vertical_menu' ) ) {
			Ovic_Megamenu_Settings::install_mobile_menu(
				array(
					'vertical_menu',
				),
				'vertical_menu',
				'mobile-vertical-menu',
				$vertical_menu_title
			);
		}
	}

	add_action( 'wp_footer', 'kuteshop_menu_mobile_vertical' );
}
/* REMOVE BUTTON MENU MOBILE */
add_filter( 'ovic_menu_toggle_mobile', '__return_false' );