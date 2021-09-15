<?php
/***
 * Core Name: WooCommerce
 * Version: 1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* QUICK VIEW */
if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
	/* Class frontend */
	$enable           = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
	$enable_on_mobile = get_option( 'yith-wcqv-enable-mobile' ) == 'yes' ? true : false;
	$class_quick_view = YITH_WCQV_Frontend::get_instance();
	/* Class frontend */
	if ( ( !wp_is_mobile() && $enable ) || ( wp_is_mobile() && $enable_on_mobile && $enable ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( $class_quick_view, 'yith_add_quick_view_button' ), 15 );
		add_action( 'kuteshop_function_shop_loop_item_quickview', array( $class_quick_view, 'yith_add_quick_view_button' ), 5 );
	}
}

/* WISHLIST */
if ( defined( 'YITH_WCWL' ) ) {
	if ( !function_exists( 'kuteshop_function_shop_loop_item_wishlist' ) ) {
		function kuteshop_function_shop_loop_item_wishlist()
		{
			if ( !wp_is_mobile() ) {
				echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
			}
		}
	}
	add_action( 'kuteshop_function_shop_loop_item_wishlist', 'kuteshop_function_shop_loop_item_wishlist', 1 );
}

/* COMPARE */
if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) {
	global $yith_woocompare;
	$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	if ( $yith_woocompare->is_frontend() || $is_ajax ) {
		if ( $is_ajax ) {
			if ( !class_exists( 'YITH_Woocompare_Frontend' ) && file_exists( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' ) ) {
				require_once YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
			}
			$yith_woocompare->obj = new YITH_Woocompare_Frontend();
		}
		/* Remove button */
		remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
		/* Add compare button */
		if ( !function_exists( 'kuteshop_wc_loop_product_compare_btn' ) ) {
			function kuteshop_wc_loop_product_compare_btn()
			{
				if ( !wp_is_mobile() ) {
					global $product;
					if ( shortcode_exists( 'yith_compare_button' ) ) {
						echo do_shortcode( '[yith_compare_button product_id="' . $product->get_id() . '"]' );
					} else {
						if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
							echo do_shortcode( '[yith_compare_button product_id="' . $product->get_id() . '"]' );
						}
					}
				}
			}
		}
		add_action( 'kuteshop_function_shop_loop_item_compare', 'kuteshop_wc_loop_product_compare_btn', 1 );
	}
}

if ( !function_exists( 'kuteshop_cart_link' ) ) {
	function kuteshop_cart_link()
	{
		?>
        <div class="shopcart-dropdown block-cart-link" data-kuteshop="kuteshop-dropdown">
            <a class="link-dropdown style1" href="<?php echo wc_get_cart_url(); ?>">
                <span class="text">
                    <?php echo esc_html__( 'SHOPPING CART', 'kuteshop' ); ?>
                </span>
                <span class="item">
                    <?php printf(
						esc_html__( '%1$s item(s) - ', 'kuteshop' ),
						WC()->cart->get_cart_contents_count()
					); ?>
                </span>
                <span class="total">
                    <?php echo WC()->cart->get_cart_subtotal(); ?>
                </span>
                <span class="flaticon-cart01 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
            </a>
            <a class="link-dropdown style2" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart02 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
            </a>
            <a class="link-dropdown style3" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart03 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
                <span class="text"><?php echo esc_html__( 'Cart', 'kuteshop' ); ?></span>
            </a>
            <a class="link-dropdown style4" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart05 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
                <span class="text"><?php echo esc_html__( 'My Cart', 'kuteshop' ); ?></span>
            </a>
            <a class="link-dropdown style7" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart02 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
            </a>
            <a class="link-dropdown style9" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart06 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
            </a>
            <a class="link-dropdown style11" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart06 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
                <span class="total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </a>
            <a class="link-dropdown style12" href="<?php echo wc_get_cart_url(); ?>">
                <span class="flaticon-cart06 icon">
                    <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </span>
                <span class="text"><?php echo esc_html__( 'CART:', 'kuteshop' ); ?></span>
                <span class="total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </a>
            <a class="link-dropdown style14" href="<?php echo wc_get_cart_url(); ?>">
                <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                <span class="text"><?php echo esc_html__( 'Cart', 'kuteshop' ); ?></span>
            </a>
        </div>
		<?php
	}
}

if ( !function_exists( 'kuteshop_header_mini_cart' ) ) {
	function kuteshop_header_mini_cart()
	{
		?>
        <div class="block-minicart kuteshop-mini-cart kuteshop-dropdown">
			<?php kuteshop_cart_link(); ?>
            <div class="shopcart-description">
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
            </div>
        </div>
		<?php
	}
}

if ( !function_exists( 'kuteshop_cart_link_fragment' ) ) {
	function kuteshop_cart_link_fragment( $fragments )
	{
		ob_start();
		kuteshop_cart_link();
		$fragments['div.block-cart-link'] = ob_get_clean();

		return $fragments;
	}
}

/* DISPLAY MORE */
if ( !function_exists( 'kuteshop_shop_display_mode_tmp' ) ) {
	function kuteshop_shop_display_mode_tmp()
	{
		global $wp;
		if ( '' === get_option( 'permalink_structure' ) ) {
			$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
		}
		$shop_display_mode = kuteshop_get_option( 'shop_page_layout', 'grid' );
		?>
        <div class="grid-view-mode">
            <form method="GET" action="<?php echo esc_url( $form_action ); ?>">
                <button type="submit"
                        class="modes-mode mode-grid display-mode <?php if ( $shop_display_mode == "grid" ): ?>active<?php endif; ?>"
                        value="grid"
                        name="shop_page_layout">
                        <span class="button-inner">
                            <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                        </span>
                </button>
                <button type="submit"
                        class="modes-mode mode-list display-mode <?php if ( $shop_display_mode == "list" ): ?>active<?php endif; ?>"
                        value="list"
                        name="shop_page_layout">
                        <span class="button-inner">
                            <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                        </span>
                </button>
				<?php wc_query_string_form_fields( null, array( 'shop_page_layout', 'submit', 'paged', 'product-page' ) ); ?>
            </form>
        </div>
		<?php
	}
}

/* PRODUCT PER PAGE */
if ( !function_exists( 'kuteshop_product_per_page_tmp' ) ) {
	function kuteshop_product_per_page_tmp()
	{
		global $wp;
		$total   = wc_get_loop_prop( 'total' );
		$perpage = kuteshop_get_option( 'product_per_page', '12' );
		$i       = 0;
		if ( '' === get_option( 'permalink_structure' ) ) {
			$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
		}
		?>
        <div class="per-page-wrapper">
            <form class="per-page-form" method="GET" action="<?php echo esc_url( $form_action ); ?>">
                <label>
                    <select name="product_per_page" class="option-perpage">
                        <option value="5" <?php if ( $perpage == 5 ) {
							echo 'selected';
							$i++;
						} ?>><?php echo esc_html__( 'Show 05', 'kuteshop' ); ?>
                        </option>
                        <option value="10" <?php if ( $perpage == 10 ) {
							echo 'selected';
							$i++;
						} ?>><?php echo esc_html__( 'Show 10', 'kuteshop' ); ?>
                        </option>
                        <option value="12" <?php if ( $perpage == 12 ) {
							echo 'selected';
							$i++;
						} ?>><?php echo esc_html__( 'Show 12', 'kuteshop' ); ?>
                        </option>
                        <option value="15" <?php if ( $perpage == 15 ) {
							echo 'selected';
							$i++;
						} ?>><?php echo esc_html__( 'Show 15', 'kuteshop' ); ?>
                        </option>
                        <option value="<?php echo esc_attr( $total ); ?>" <?php if ( $perpage == $total ) {
							echo 'selected';
							$i++;
						} ?>><?php echo esc_html__( 'Show All', 'kuteshop' ); ?>
                        </option>
                        <option value="" <?php if ( $i == 0 ) {
							echo 'selected';
						} ?>><?php echo esc_html__( 'Select value', 'kuteshop' ); ?>
                        </option>
                    </select>
                </label>
				<?php wc_query_string_form_fields( null, array( 'product_per_page', 'submit', 'paged', 'product-page' ) ); ?>
            </form>
        </div>
		<?php
	}
}

if ( !function_exists( 'kuteshop_woocommerce_breadcrumb' ) ) {
	function kuteshop_woocommerce_breadcrumb()
	{
		$args = array(
			'delimiter'   => '',
			'wrap_before' => '<div class="woocommerce-breadcrumb breadcrumbs col-sm-12"><ul class="breadcrumb">',
			'wrap_after'  => '</ul></div>',
			'before'      => '<li>',
			'after'       => '</li>',
		);
		woocommerce_breadcrumb( $args );
	}
}

if ( !function_exists( 'kuteshop_template_loop_product_thumbnail' ) ) {
	function kuteshop_template_loop_product_thumbnail()
	{
		global $product;
		// GET SIZE IMAGE SETTING
		$width  = 250;
		$height = 305;
		$crop   = true;
		$size   = wc_get_image_size( 'shop_catalog' );
		if ( $size ) {
			$width  = $size['width'];
			$height = $size['height'];
			if ( !$size['crop'] ) {
				$crop = false;
			}
		}
		$width      = apply_filters( 'kuteshop_shop_pruduct_thumb_width', $width );
		$height     = apply_filters( 'kuteshop_shop_pruduct_thumb_height', $height );
		$lazy_check = kuteshop_get_option( 'kuteshop_theme_lazy_load' ) == 1 ? true : false;
		?>
        <a class="thumb-link" href="<?php the_permalink(); ?>">
			<?php
			$image_thumb = kuteshop_resize_image( $product->get_image_id(), $width, $height, $crop, $lazy_check );
			echo wp_specialchars_decode( $image_thumb['img'] );
			?>
        </a>
		<?php
	}
}

if ( !function_exists( 'kuteshop_loop_shop_per_page' ) ) {
	function kuteshop_loop_shop_per_page()
	{
		$kuteshop_woo_products_perpage = kuteshop_get_option( 'product_per_page', '12' );

		return $kuteshop_woo_products_perpage;
	}
}

if ( !function_exists( 'kuteshop_woof_products_query' ) ) {
	function kuteshop_woof_products_query( $wr )
	{
		$kuteshop_woo_products_perpage = kuteshop_get_option( 'product_per_page', '12' );
		$wr['posts_per_page']          = $kuteshop_woo_products_perpage;

		return $wr;
	}
}

if ( !function_exists( 'kuteshop_template_loop_product_title' ) ) {
	function kuteshop_template_loop_product_title()
	{
		?>
        <h3 class="product-name product_title">
            <a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
        </h3>
		<?php
	}
}

if ( !function_exists( 'kuteshop_carousel_products' ) ) {
	function kuteshop_carousel_products( $prefix, $data_args )
	{
		$classes        = array();
		$classes[]      = 'product-item style-1';
		$template_style = 'style-1';
		$woo_ls_items   = kuteshop_get_option( $prefix . '_ls_items', 3 );
		$woo_lg_items   = kuteshop_get_option( $prefix . '_lg_items', 3 );
		$woo_md_items   = kuteshop_get_option( $prefix . '_md_items', 3 );
		$woo_sm_items   = kuteshop_get_option( $prefix . '_sm_items', 2 );
		$woo_xs_items   = kuteshop_get_option( $prefix . '_xs_items', 2 );
		$woo_ts_items   = kuteshop_get_option( $prefix . '_ts_items', 2 );
		$data           = array(
			'infinite'     => false,
			'slidesToShow' => (int)$woo_ls_items,
			'responsive'   => array(
				array(
					'breakpoint' => 1500,
					'settings'   => array(
						'slidesToShow' => (int)$woo_lg_items,
					),
				),
				array(
					'breakpoint' => 1200,
					'settings'   => array(
						'slidesToShow' => (int)$woo_md_items,
					),
				),
				array(
					'breakpoint' => 992,
					'settings'   => array(
						'slidesToShow' => (int)$woo_sm_items,
					),
				),
				array(
					'breakpoint' => 768,
					'settings'   => array(
						'slidesToShow' => (int)$woo_xs_items,
					),
				),
				array(
					'breakpoint' => 480,
					'settings'   => array(
						'slidesToShow' => (int)$woo_ts_items,
					),
				),
			),
		);
		$generate       = ' data-slick=' . json_encode( $data ) . ' ';
		$title          = kuteshop_get_option( '' . $prefix . '_products_title', 'You may be interested in...' );
		if ( $data_args ) : ?>
            <div class="products product-grid related-product">
                <h2 class="product-grid-title"><?php echo esc_html( $title ); ?></h2>
                <div class="owl-slick owl-products equal-container better-height" <?php echo esc_attr( $generate ); ?>>
					<?php foreach ( $data_args as $value ) : ?>
                        <div <?php wc_product_class( $classes, $value ) ?>>
							<?php
							$post_object = get_post( $value->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object );
							wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif;
		wp_reset_postdata();
	}
}

if ( !function_exists( 'kuteshop_cross_sell_products' ) ) {
	function kuteshop_cross_sell_products( $limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc' )
	{
		if ( is_checkout() ) {
			return;
		}
		$cross_sells                 = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
		$woocommerce_loop['name']    = 'cross-sells';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );
		// Handle orderby and limit results.
		$orderby     = apply_filters( 'woocommerce_cross_sells_orderby', $orderby );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$limit       = apply_filters( 'woocommerce_cross_sells_total', $limit );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;
		kuteshop_carousel_products( 'kuteshop_woo_crosssell', $cross_sells );
	}
}

if ( !function_exists( 'kuteshop_related_products' ) ) {
	function kuteshop_related_products()
	{
		global $product;
		$defaults                    = array(
			'posts_per_page' => 6,
			'columns'        => 6,
			'orderby'        => 'rand',
			'order'          => 'desc',
		);
		$args                        = wp_parse_args( $defaults );
		$args['related_products']    = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
		$args['related_products']    = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );
		$woocommerce_loop['name']    = 'related';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $args['columns'] );
		$related_products            = $args['related_products'];
		kuteshop_carousel_products( 'kuteshop_woo_related', $related_products );
	}
}

if ( !function_exists( 'kuteshop_upsell_display' ) ) {
	function kuteshop_upsell_display( $orderby = 'rand', $order = 'desc', $limit = '-1', $columns = 4 )
	{
		global $product;
		$args                        = array(
			'posts_per_page' => 4,
			'orderby'        => 'rand',
			'columns'        => 4,
		);
		$woocommerce_loop['name']    = 'up-sells';
		$woocommerce_loop['columns'] = apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns );
		$orderby                     = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
		$limit                       = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );
		// Get visible upsells then sort them at random, then limit result set.
		$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
		$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;
		kuteshop_carousel_products( 'kuteshop_woo_upsell', $upsells );
	}
}

if ( !function_exists( 'kuteshop_product_video' ) ) {
	function kuteshop_product_video()
	{
		global $product;

		$main_image    = false;
		$meta_product  = get_post_meta( $product->get_id(), '_custom_product_options', true );
		$attachment_id = !empty( $meta_product['poster'] ) ? $meta_product['poster'] : '';
		$video_url     = !empty( $meta_product['video'] ) ? $meta_product['video'] : '';
		$galleries     = !empty( $meta_product['gallery'] ) ? $meta_product['gallery'] : '';

		$attachment_id     = !empty( $attachment_id ) ? $attachment_id : $product->get_image_id();
		$flexslider        = (bool)apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$image_src         = wp_get_attachment_image_src( $attachment_id, $image_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
		$image             = wp_get_attachment_image(
			$attachment_id,
			$image_size,
			false,
			apply_filters(
				'woocommerce_gallery_image_html_attachment_image_params',
				array(
					'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-src'                => esc_url( $full_src[0] ),
					'data-large_image'        => esc_url( $full_src[0] ),
					'data-large_image_width'  => esc_attr( $full_src[1] ),
					'data-large_image_height' => esc_attr( $full_src[2] ),
					'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
				),
				$attachment_id,
				$image_size,
				$main_image
			)
		);
		// VIDEO
		if ( !empty( $video_url ) ) {
			$thumbnail_src = get_theme_file_uri( 'assets/images/video.png' );

			$html = wp_video_shortcode(
				array_merge(
					array(
						'src'     => $video_url,
						'poster'  => $image_src[0],
						'width'   => $image_src[1],
						'height'  => $image_src[2],
						'preload' => 'auto',
					),
					compact( 'src' )
				),
				''
			);

			echo '<div data-thumb="' . esc_url( $thumbnail_src ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image none-zoom"><a href="' . esc_url( $full_src[0] ) . '" style="display:none">' . $image . '</a>' . $html . '</div>';
		}
	}
}

if ( !function_exists( 'kuteshop_product_get_rating_html' ) ) {
	function kuteshop_product_get_rating_html( $html, $rating, $count )
	{
		$placeholder_rate = kuteshop_get_option( 'placeholder_rate' );
		if ( $placeholder_rate != 1 && $rating <= 0 ) {
			return '';
		}
		$html = '<div class="star-rating">';
		$html .= wc_get_star_rating_html( $rating, $count );
		$html .= '</div>';

		return $html;
	}
}

if ( !function_exists( 'kuteshop_get_percent_discount' ) ) {
	function kuteshop_get_percent_discount()
	{
		global $product;
		$percent = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;
				for ( $i = 0; $i < count( $available_variations ); ++$i ) {
					$variation_id      = $available_variations[$i]['variation_id'];
					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 0 );
					}
					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}
					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$percent .= '-' . $minimumper . '%';
				} else {
					$percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
				}
			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = round( ( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ), 0 );
					$percent    .= '-' . $percentage . '%';
				}
			}
		}

		return $percent;
	}
}

if ( !function_exists( 'kuteshop_sale_flash' ) ) {
	function kuteshop_sale_flash( $text )
	{
		$percent = kuteshop_get_percent_discount();
		if ( !empty( $percent ) )
			return '<span class="onsale">' . esc_html( $percent ) . '</span>';

		return '';
	}
}

if ( !function_exists( 'kuteshop_group_flash' ) ) {
	function kuteshop_group_flash()
	{
		global $post, $product;
		$postdate      = get_the_time( 'Y-m-d' );            // Post date
		$postdatestamp = strtotime( $postdate );            // Timestamped post date
		$newness       = kuteshop_get_option( 'product_newness', 0 );    // Newness in days as defined by option
		?>
        <div class="flash">
			<?php woocommerce_show_product_loop_sale_flash();
			if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) :
				echo apply_filters( 'woocommerce_new_flash', '<span class="onnew"><span class="text">' . esc_html__( 'New', 'kuteshop' ) . '</span></span>', $post, $product );
			endif; ?>
        </div>
		<?php
	}
}

/* PRODUCT COUNTDOWN */

if ( !function_exists( 'kuteshop_get_max_date_sale' ) ) {
	function kuteshop_get_max_date_sale( $product_id )
	{
		$date_now = current_time( 'timestamp', 0 );
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => -1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		if ( !empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ") ORDER BY meta_value DESC LIMIT 1" );
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		if ( !$sale_price_dates_to ) {
			$sale_price_dates_to   = get_post_meta( $product_id, '_sale_price_dates_to', true );
			$sale_price_dates_from = get_post_meta( $product_id, '_sale_price_dates_from', true );
			if ( $sale_price_dates_to == '' || $date_now < $sale_price_dates_from ) {
				$sale_price_dates_to = '0';
			}
		}

		return $sale_price_dates_to;
	}
}

if ( !function_exists( 'kuteshop_function_shop_loop_item_countdown' ) ) {
	function kuteshop_function_shop_loop_item_countdown()
	{
		global $product;
		$date = kuteshop_get_max_date_sale( $product->get_id() );
		if ( $date > 0 ) {
			?>
            <div class="kuteshop-countdown"
                 data-datetime="<?php echo date( 'm/j/Y', $date ); ?>">
            </div>
			<?php
		}
	}
}

if ( !function_exists( 'kuteshop_ratting_single_product' ) ) {
	function kuteshop_ratting_single_product()
	{
		global $product;
		if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
			return;
		}
		$rating_count = $product->get_rating_count();
		$average      = $product->get_average_rating();
		if ( $rating_count > 0 ) : ?>
            <div class="woocommerce-product-rating">
                <span class="star-rating">
                    <span style="width:<?php echo( ( $average / 5 ) * 100 ); ?>%">
                        <?php printf(
							__( '%1$s out of %2$s', 'kuteshop' ),
							'<strong class="rating">' . esc_html( $average ) . '</strong>',
							'<span>5</span>'
						); ?>
                    </span>
                </span>
                <span>
                    <?php printf(
						_n( 'based on %s rating', 'Based on %s ratings', $rating_count, 'kuteshop' ),
						'<span class="rating">' . esc_html( $rating_count ) . '</span>'
					); ?>
                </span>
				<?php if ( comments_open() ) : ?>
                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
						<?php echo esc_html__( 'write a preview', 'kuteshop' ) ?>
                    </a>
				<?php endif ?>
            </div>
		<?php endif;
	}
}
if ( !function_exists( 'kuteshop_product_custom_query' ) ) {
	function kuteshop_product_custom_query( $my_query )
	{
		if ( is_shop() || is_product_taxonomy() ) {
			$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( (string)wp_unslash( $_GET['orderby'] ) ) : wc_clean( get_query_var( 'orderby' ) ); // WPCS: sanitization ok, input var ok, CSRF ok.
			switch ( $orderby_value ) {
				case 'sale':
					$my_query->set( 'meta_key', 'total_sales' );
					$my_query->set( 'orderby', 'meta_value_num' );

					break;

				case 'on-sale':
					$product_ids_on_sale   = wc_get_product_ids_on_sale();
					$product_ids_on_sale[] = 0;
					$my_query->set( 'post__in', $product_ids_on_sale );
					$my_query->set( 'orderby', 'post__in' );

					break;

				case 'feature':
					$product_visibility_term_ids = wc_get_product_visibility_term_ids();
					$my_query->set( 'tax_query', array(
							array(
								'taxonomy' => 'product_visibility',
								'field'    => 'term_taxonomy_id',
								'terms'    => $product_visibility_term_ids['featured'],
							),
						)
					);
					$my_query->set( 'order', 'desc' );

					break;
			};
		}
	}
}
/**
 *
 * TOTAL REVIEW
 */
if ( !function_exists( 'kuteshop_customer_review' ) ) {
	function kuteshop_customer_review()
	{
		global $product, $comment;

		$args         = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
			'post_id'     => $product->get_id(),
		);
		$comments     = get_comments( $args );
		$average      = $product->get_average_rating();
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$stars        = array(
			'5' => 0,
			'4' => 0,
			'3' => 0,
			'2' => 0,
			'1' => 0,
		);
		if ( !empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
				if ( $rating && '0' != $comment->comment_approved ) {
					$stars[$rating]++;
				}
			}
		}
		?>
        <div class="ovic-panel-rating">
            <div class="average">
                <span><?php echo esc_html( $average ); ?>★</span>
                <p><?php esc_html_e( 'Rating', 'kuteshop' ); ?></p>
            </div>
            <ul class="detail">
				<?php foreach ( $stars as $key => $rating ): ?>
					<?php
					$process = 0;
					if ( $rating_count > 0 && $rating > 0 ) {
						$process = ( $rating / $rating_count ) * 100;
					}
					?>
                    <li>
                        <span class="star">
                            <?php echo esc_html( $key ); ?>★
                        </span>
                        <span class="process">
                            <span class="process-bar" style="width:<?php echo esc_attr( $process ); ?>%"></span>
                        </span>
                        <span class="count">
                            <?php echo esc_html( $rating ); ?>
                        </span>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
		<?php
	}
}
/**
 * Retrieves the previous product.
 *
 * @param bool         $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string       $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 *
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
if ( !function_exists( 'kuteshop_get_previous_product' ) ) {
	function kuteshop_get_previous_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' )
	{
		$product = new Kuteshop_WooCommerce_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy, true );

		return $product->get_product();
	}
}

/**
 * Retrieves the next product.
 *
 * @param bool         $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string       $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 *
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
if ( !function_exists( 'kuteshop_get_next_product' ) ) {
	function kuteshop_get_next_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' )
	{
		$product = new Kuteshop_WooCommerce_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy );

		return $product->get_product();
	}
}

if ( !function_exists( 'kuteshop_single_product_pagination' ) ) {
	function kuteshop_single_product_pagination()
	{
		// Show only products in the same category?
		$in_same_term   = apply_filters( 'kuteshop_single_product_pagination_same_category', true );
		$excluded_terms = apply_filters( 'kuteshop_single_product_pagination_excluded_terms', '' );
		$taxonomy       = apply_filters( 'kuteshop_single_product_pagination_taxonomy', 'product_cat' );
		// Get previous and next products.
		$previous_product = kuteshop_get_previous_product( $in_same_term, $excluded_terms, $taxonomy );
		$next_product     = kuteshop_get_next_product( $in_same_term, $excluded_terms, $taxonomy );

		if ( !$previous_product && !$next_product ) {
			return;
		}
		?>
        <nav class="kuteshop-product-pagination">
			<?php if ( $previous_product ): ?>
                <a class="other-post prev" href="<?php echo esc_url( $previous_product->get_permalink() ); ?>"
                   title="<?php echo esc_attr( $previous_product->get_name() ); ?>">
                    <span class="pe-7s-left-arrow"></span>
                    <figure>
						<?php echo wp_specialchars_decode( $previous_product->get_image( array( 100, 100 ) ) ); ?>
                    </figure>
                </a>
			<?php endif; ?>
			<?php if ( $next_product ): ?>
                <a class="other-post next" href="<?php echo esc_url( $next_product->get_permalink() ); ?>"
                   title="<?php echo esc_attr( $next_product->get_name() ); ?>">
                    <span class="pe-7s-right-arrow"></span>
                    <figure>
						<?php echo wp_specialchars_decode( $next_product->get_image( array( 100, 100 ) ) ); ?>
                    </figure>
                </a>
			<?php endif; ?>
        </nav>
		<?php
	}
}
