<?php
/**
 * Kuteshop functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Kuteshop
 * @since 3.0.0
 */
/* Theme version. */
if ( !defined( 'KUTESHOP_VERSION' ) ) {
	define( 'KUTESHOP_VERSION', wp_get_theme()->get( 'Version' ) );
}
/* Theme FILE. */
if ( !defined( 'KUTESHOP_FILE' ) ) {
	define( 'KUTESHOP_FILE', __FILE__ );
}
if ( !function_exists( 'kuteshop_theme_setup' ) ) {
	function kuteshop_theme_setup()
	{
		load_theme_textdomain( 'kuteshop', get_template_directory() . '/languages' );
		/*
		 * Make theme available for translation.
		 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/blank
		 * If you're building a theme based on Twenty Seventeen, use a find and replace
		 * to change 'kuteshop' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'kuteshop', get_template_directory() . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		/*This theme uses wp_nav_menu() in two locations.*/
		register_nav_menus( array(
				'primary'         => esc_html__( 'Primary Menu', 'kuteshop' ),
				'top_left_menu'   => esc_html__( 'Top Left Menu', 'kuteshop' ),
				'top_right_menu'  => esc_html__( 'Top Right Menu', 'kuteshop' ),
				'top_center_menu' => esc_html__( 'Top Center Menu', 'kuteshop' ),
				'vertical_menu'   => esc_html__( 'Vertical Menu', 'kuteshop' ),
			)
		);
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			)
		);
		add_theme_support( 'post-formats',
			array(
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );
		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
		// Enqueue editor styles.
		add_editor_style(
			array(
				'style-editor.css',
			)
		);
		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
		/*Support woocommerce*/
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-zoom' );
	}
}
add_action( 'after_setup_theme', 'kuteshop_theme_setup' );
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if ( !function_exists( 'kuteshop_widgets_init' ) ) {
	function kuteshop_widgets_init()
	{
		$widgets = kuteshop_get_option( 'multi_widget' );
		if ( is_array( $widgets ) && count( $widgets ) > 0 ) {
			foreach ( $widgets as $widget ) {
				if ( !empty( $widget ) ) {
					register_sidebar( array(
							'name'          => $widget['add_widget'],
							'id'            => 'custom-sidebar-' . sanitize_key( $widget['add_widget'] ),
							'before_widget' => '<div id="%1$s" class="widget block-sidebar %2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<div class="title-widget widgettitle"><strong>',
							'after_title'   => '</strong></div>',
						)
					);
				}
			}
		}
		register_sidebar( array(
				'name'          => esc_html__( 'Widget Area', 'kuteshop' ),
				'id'            => 'widget-area',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'kuteshop' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '<span class="arow"></span></h2>',
			)
		);
		register_sidebar( array(
				'name'          => esc_html__( 'Shop Widget Area', 'kuteshop' ),
				'id'            => 'shop-widget-area',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'kuteshop' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '<span class="arow"></span></h2>',
			)
		);
		register_sidebar( array(
				'name'          => esc_html__( 'Product Widget Area', 'kuteshop' ),
				'id'            => 'product-widget-area',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'kuteshop' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widgettitle">',
				'after_title'   => '<span class="arow"></span></h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'kuteshop_widgets_init' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
if ( !function_exists( 'kuteshop_content_width' ) ) {
	function kuteshop_content_width()
	{
		// This variable is intended to be overruled from themes.
		// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$GLOBALS['content_width'] = apply_filters( 'kuteshop_content_width', 900 );
	}
}
add_action( 'after_setup_theme', 'kuteshop_content_width', 0 );
/**
 * Custom Comment field.
 */
if ( !function_exists( 'kuteshop_comment_field_to_bottom' ) ) {
	function kuteshop_comment_field_to_bottom( $fields )
	{
		$comment_field = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment_field;

		return $fields;
	}
}
add_filter( 'comment_form_fields', 'kuteshop_comment_field_to_bottom', 10, 3 );
/**
 * Custom Body Class.
 */
if ( !function_exists( 'kuteshop_body_class' ) ) {
	function kuteshop_body_class( $classes )
	{
		$my_theme  = wp_get_theme();
		$classes[] = $my_theme->get( 'Name' ) . "-" . $my_theme->get( 'Version' );

		return $classes;
	}
}
add_filter( 'body_class', 'kuteshop_body_class' );
/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 *
 * @return array $urls           URLs to print for resource hints.
 * @since Ocolus 1.0
 *
 */
if ( !function_exists( 'kuteshop_resource_hints' ) ) {
	function kuteshop_resource_hints( $urls, $relation_type )
	{
		if ( wp_style_is( 'kute-boutique-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}

		return $urls;
	}
}
add_filter( 'wp_resource_hints', 'kuteshop_resource_hints', 10, 2 );
/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Ocolus 1.0
 */
if ( !function_exists( 'kuteshop_javascript_detection' ) ) {
	function kuteshop_javascript_detection()
	{
		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	}
}
add_action( 'wp_head', 'kuteshop_javascript_detection', 0 );
/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
if ( !function_exists( 'kuteshop_pingback_header' ) ) {
	function kuteshop_pingback_header()
	{
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}
}
add_action( 'wp_head', 'kuteshop_pingback_header' );
/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
if ( !function_exists( 'kuteshop_skip_link_focus_fix' ) ) {
	function kuteshop_skip_link_focus_fix()
	{
		// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
		?>
        <script>
            /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function () {
                var t, e = location.hash.substring(1);
                /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
            }, !1);
        </script>
		<?php
	}
}
add_action( 'wp_print_footer_scripts', 'kuteshop_skip_link_focus_fix' );
/**
 * Register custom fonts.
 */
if ( !function_exists( 'kuteshop_fonts_url' ) ) {
	function kuteshop_fonts_url()
	{
		$font_families   = array();
		$font_families[] = 'Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i';
		$font_families[] = 'Open Sans:300,300i,400,400i,600,600i,700,700i';
		$font_families[] = 'Oswald:300,400,500,600';
		$font_families[] = 'Arimo:400,700';
		$font_families[] = 'Lato:400,700';
		$font_families[] = 'Pacifico';
		$query_args      = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url       = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

		return esc_url_raw( $fonts_url );
	}
}
/**
 * Enqueue admin scripts and styles.
 */
if ( !function_exists( 'kuteshop_admin_scripts' ) ) {
	function kuteshop_admin_scripts()
	{
		wp_enqueue_style( 'pe-icon-7-stroke', get_theme_file_uri( '/assets/css/pe-icon-7-stroke.min.css' ), array(), KUTESHOP_VERSION );
		wp_enqueue_style( 'flaticon', get_theme_file_uri( '/assets/css/flaticon.min.css' ), array(), KUTESHOP_VERSION );
		wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), '4.7.0' );
		wp_enqueue_style( 'chosen', get_theme_file_uri( '/assets/css/chosen.min.css' ), array(), '1.8.7' );
		wp_enqueue_style( 'kuteshop-admin', get_theme_file_uri( '/framework/assets/css/admin.css' ), array(), KUTESHOP_VERSION );

		wp_enqueue_script( 'chosen', get_theme_file_uri( '/assets/js/vendor/chosen.min.js' ), array(), '1.8.7', true );
		wp_enqueue_script( 'kuteshop-admin', get_theme_file_uri( '/framework/assets/js/admin.js' ), array(), KUTESHOP_VERSION, true );
	}
}
add_action( 'admin_enqueue_scripts', 'kuteshop_admin_scripts' );
/**
 * Enqueue scripts and styles.
 */
if ( !function_exists( 'kuteshop_scripts' ) ) {
	function kuteshop_scripts()
	{
		global $post;

		$gmap_api_key = kuteshop_get_option( 'gmap_api_key', '' );

		// Add custom fonts, used in the main stylesheet.
		wp_enqueue_style( 'kuteshop-fonts', kuteshop_fonts_url(), array(), null );

		// Only desktop.
		if ( !wp_is_mobile() ) {
			wp_enqueue_style( 'scrollbar', get_theme_file_uri( '/assets/css/scrollbar.min.css' ), array(), '' );
			wp_enqueue_script( 'scrollbar', get_theme_file_uri( '/assets/js/vendor/scrollbar.min.js' ), array(), '', true );
		}

		// Theme stylesheet.
		wp_enqueue_style( 'animate-css', get_theme_file_uri( '/assets/css/animate.min.css' ), array(), '3.7.0' );
		wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '3.3.7' );
		wp_enqueue_style( 'flaticon', get_theme_file_uri( '/assets/css/flaticon.min.css' ), array(), KUTESHOP_VERSION );
		wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), '4.7.0' );
		wp_enqueue_style( 'pe-icon-7-stroke', get_theme_file_uri( '/assets/css/pe-icon-7-stroke.min.css' ), array(), '1.0' );
		wp_enqueue_style( 'chosen', get_theme_file_uri( '/assets/css/chosen.min.css' ), array(), '1.8.7' );
		wp_enqueue_style( 'growl', get_theme_file_uri( '/assets/css/growl.min.css' ), array(), '1.3.5' );
		wp_enqueue_style( 'slick', get_theme_file_uri( '/assets/css/slick.min.css' ), array(), '1.8.0' );
		if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'WC_Vendors' ) ) {
			wp_enqueue_style( 'wc_vendors', get_theme_file_uri( '/assets/css/wc_vendors.min.css' ), array(), KUTESHOP_VERSION );
		}
		if ( is_rtl() ) {
			wp_enqueue_style( 'kuteshop_custom_css', get_theme_file_uri( '/assets/css/style-rtl.min.css' ), array(), KUTESHOP_VERSION );
		} else {
			wp_enqueue_style( 'kuteshop_custom_css', get_theme_file_uri( '/assets/css/style.min.css' ), array(), KUTESHOP_VERSION );
		}
		wp_enqueue_style( 'kuteshop-main-style', get_stylesheet_uri() );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( !empty( $gmap_api_key ) ) {
			wp_register_script( 'kuteshop-maps-api', esc_url( '//maps.googleapis.com/maps/api/js?key=' . trim( $gmap_api_key ) ), array(), false, true );
		}
		wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/vendor/bootstrap.min.js' ), array(), '3.3.7', true );
		wp_enqueue_script( 'countdown', get_theme_file_uri( '/assets/js/vendor/countdown.min.js' ), array(), '2.2.0', true );
		wp_enqueue_script( 'chosen', get_theme_file_uri( '/assets/js/vendor/chosen.min.js' ), array(), '1.8.7', true );
		wp_enqueue_script( 'lazy_load', get_theme_file_uri( '/assets/js/vendor/lazyload.min.js' ), array(), '1.7.6', true );
		wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/vendor/slick.min.js' ), array(), '1.8.0', true );
		wp_enqueue_script( 'growl', get_theme_file_uri( '/assets/js/vendor/growl.min.js' ), array(), '1.3.5', true );
		wp_enqueue_script( 'kuteshop-script', get_theme_file_uri( '/assets/js/functions.min.js' ), array(), KUTESHOP_VERSION, true );

		$enable_popup        = kuteshop_get_option( 'kuteshop_enable_popup' );
		$enable_popup_mobile = kuteshop_get_option( 'kuteshop_enable_popup_mobile' );
		$popup_delay_time    = kuteshop_get_option( 'kuteshop_popup_delay_time', '1' );
		$enable_sticky_menu  = kuteshop_get_option( 'kuteshop_enable_sticky_menu' );
		$enable_ajax_product = kuteshop_get_option( 'enable_ajax_product' );
		wp_localize_script( 'kuteshop-script', 'kuteshop_params', array(
				'ajaxurl'             => admin_url( 'admin-ajax.php' ),
				'security'            => wp_create_nonce( 'kuteshop_ajax_frontend' ),
				'kuteshop_ajax_url'   => Kuteshop_Ajax::get_endpoint( '%%endpoint%%' ),
				'enable_popup'        => $enable_popup,
				'enable_popup_mobile' => $enable_popup_mobile,
				'popup_delay_time'    => $popup_delay_time,
				'enable_sticky_menu'  => $enable_sticky_menu,
				'enable_ajax_product' => $enable_ajax_product,
				'growl_notice'        => apply_filters( 'kuteshop_growl_notice_params',
					array(
						'added_to_cart_text'     => esc_html__( 'Product has been added to cart!', 'kuteshop' ),
						'added_to_wishlist_text' => get_option( 'yith_wcwl_product_added_text', esc_html__( 'Product has been added to wishlist!', 'kuteshop' ) ),
						'wishlist_url'           => function_exists( 'YITH_WCWL' ) ? esc_url( YITH_WCWL()->get_wishlist_url() ) : '',
						'browse_wishlist_text'   => get_option( 'yith_wcwl_browse_wishlist_text', esc_html__( 'Browse Wishlist', 'kuteshop' ) ),
						'growl_notice_text'      => esc_html__( 'Notice!', 'kuteshop' ),
						'removed_cart_text'      => esc_html__( 'Product Removed', 'kuteshop' ),
						'growl_duration'         => 3000,
					)
				),
				'days_text'           => esc_html__( 'Days', 'kuteshop' ),
				'hrs_text'            => esc_html__( 'Hrs', 'kuteshop' ),
				'mins_text'           => esc_html__( 'Mins', 'kuteshop' ),
				'secs_text'           => esc_html__( 'Secs', 'kuteshop' ),
				'alert_variable'      => esc_html__( 'Plz Select option before Add To Cart.', 'kuteshop' ),
			)
		);
		/* DEQUEUE SCRIPTS - OPTIMIZER */
		if ( is_a( $post, 'WP_Post' ) && !has_shortcode( $post->post_content, 'contact-form-7' ) ) {
			wp_dequeue_style( 'contact-form-7' );
			wp_dequeue_script( 'contact-form-7' );
		}
		wp_deregister_style( 'dokan-fontawesome' );
		/* WOOCOMMERCE */
		if ( class_exists( 'WooCommerce' ) ) {
			if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
				wp_dequeue_style( 'yith-quick-view' );
			}
			if ( defined( 'YITH_WCWL' ) ) {
				$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
				if ( !is_page( $wishlist_page_id ) ) {
					wp_dequeue_script( 'prettyPhoto' );
					wp_dequeue_script( 'jquery-selectBox' );
					wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
					wp_dequeue_style( 'jquery-selectBox' );
					wp_dequeue_style( 'yith-wcwl-main' );
					wp_dequeue_style( 'yith-wcwl-user-main' );
					wp_dequeue_style( 'yith-wcwl-font-awesome' );
				}
			}
			if ( !is_product() ) {
				/* PLUGIN GIFT */
				if ( class_exists( 'Woocommerce_Multiple_Free_Gift' ) ) {
					wp_dequeue_style( 'wfg-core-styles' );
					wp_dequeue_style( 'wfg-styles' );
					wp_dequeue_script( 'wfg-scripts' );
				}
				/* PLUGIN SIZE CHART */
				if ( class_exists( 'Size_Chart_For_Woocommerce' ) ) {
					wp_dequeue_style( 'size-chart-for-woocommerce' );
					wp_dequeue_script( 'size-chart-for-woocommerce' );
				}
			}
			if ( class_exists( 'Vc_Manager' ) ) {
				wp_dequeue_script( 'vc_woocommerce-add-to-cart-js' );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'kuteshop_scripts' );
/**
 * Functions Update Database.
 */
require get_parent_theme_file_path( '/framework/classes/back-compat.php' );
/**
 * Functions theme helper.
 */
require get_parent_theme_file_path( '/framework/settings/helpers.php' );
/**
 * Functions deprecated.
 */
require get_parent_theme_file_path( '/framework/deprecated.php' );
/**
 * Functions Plugin load.
 */
require get_parent_theme_file_path( '/framework/settings/plugins-load.php' );
/**
 * Functions Breadcrumbs.
 */
require get_parent_theme_file_path( '/framework/classes/breadcrumbs.php' );
/**
 * Functions theme options.
 */
require get_parent_theme_file_path( '/framework/settings/theme-options.php' );
/**
 * Functions theme AJAX.
 */
require get_parent_theme_file_path( '/framework/classes/core-ajax.php' );
/**
 * Functions Megamenu.
 */
require get_parent_theme_file_path( '/framework/includes/megamenu/megamenu.php' );
/**
 * Functions Theme Functions.
 */
require get_parent_theme_file_path( '/framework/theme-functions.php' );
/**
 * Functions Color patterns.
 */
require get_parent_theme_file_path( '/framework/settings/color-patterns.php' );
/**
 * Functions Visual composer.
 */
if ( class_exists( 'Vc_Manager' ) ) {
	require get_parent_theme_file_path( '/framework/visual-composer.php' );
}
/**
 * Functions WooCommerce.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_parent_theme_file_path( '/framework/woocommerce/template-hook.php' );
}

add_filter( 'wcfm_is_allow_setup_seo_settings', '__return_false' );

add_filter( 'wcfm_is_allow_customer_support_settings', '__return_false' );
/*
add_filter( 'woocommerce_valid_order_statuses_for_cancel', 'filter_valid_order_statuses_for_cancel', 20, 2 );
function filter_valid_order_statuses_for_cancel( $statuses, $order = '' ){

    // Set HERE the order statuses where you want the cancel button to appear
    $custom_statuses    = array( 'pending', 'processing', 'on-hold', 'failed' );

    // Set HERE the delay (in days)
    $duration = 3; // 3 days

    // UPDATE: Get the order ID and the WC_Order object
    if( ! is_object( $order ) && isset($_GET['order_id']) )
        $order = wc_get_order( absint( $_GET['order_id'] ) );

    $delay = $duration*24*60*60; // (duration in seconds)
    $date_created_time  = strtotime($order->get_date_created()); // Creation date time stamp
    $date_modified_time = strtotime($order->get_date_modified()); // Modified date time stamp
    $now = strtotime("now"); // Now  time stamp

    // Using Creation date time stamp
    if ( ( $date_created_time + $delay ) >= $now ) return $custom_statuses;
    else return $statuses;
}
*/

add_filter( 'wcfm_is_pref_enquiry_button', '__return_false' );
/*
add_filter('remove_add_to_cart', 'my_woocommerce_is_purchasable', 10, 2);
function remove_add_to_cart($is_purchasable, $product) {
        if( $product->get_price() == 0 )
            $is_purchasable = false;
            return $purchasable;   
}


function remove_add_to_cart_on_0 ( $purchasable, $product ){
        if( $product->get_price() == 0 )
            $purchasable = false;
        return $purchasable;
    }
add_filter( 'woocommerce_is_purchasable', 'remove_add_to_cart_on_0', 10, 2 );
*/
/*add_action( 'woocommerce_thankyou', 'stop_auto_complete_order' );
function stop_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'waiting-to-accept' );
}*/
/*
function jeroen_sormani_change_city_to_dropdown( $fields ) {

	$city_args = wp_parse_args( array(
		'type' => 'select',
		'options' => array(
			'egypt' => 'Egypt',
			
		),
	), $fields['shipping']['shipping_city'] );

	$fields['shipping']['shipping_city'] = $city_args;
	$fields['billing']['billing_city'] = $city_args; // Also change for billing field

	return $fields;

}
add_filter( 'woocommerce_checkout_fields', 'jeroen_sormani_change_city_to_dropdown' );
*/
/*
    add_filter('woocommerce_sort_countries', '__return_false');
    add_filter( 'woocommerce_countries', 'change_country_order_in_checkout_form'     );
    function change_country_order_in_checkout_form($countries)
{
    $usa = $countries['US']; // Store the data for "US" key
    $uk = $countries['GB']; // Store the data for "UK" key

    // Return "US" and "UK" first in the countries array
    return array('US' => $usa, 'GB' => $uk) + $countries;
}
*/
add_filter( 'default_checkout_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_state', 'change_default_checkout_state' );

function change_default_checkout_country() {
  return 'EG'; // country code
}

function change_default_checkout_state() {
  return 'EG'; // state code
}

add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false');


add_filter( 'woocommerce_get_price_html', 'bbloomer_price_free_zero_empty', 9999, 2 );


 function jeroen_sormani_change_city_to_dropdown( $fields ) {

	$city_args = wp_parse_args( array(
		'type' => 'select',
		'options' => array(
			'6 october' => '6 october',
			'Sheikh Zayed' => 'Sheikh Zayed',
		
		),
	), $fields['shipping']['shipping_city'] );

	$fields['shipping']['shipping_city'] = $city_args;
	$fields['billing']['billing_city'] = $city_args; // Also change for billing field

	return $fields;

}
add_filter( 'woocommerce_checkout_fields', 'jeroen_sormani_change_city_to_dropdown' ); 

add_action( 'woocommerce_after_order_notes', 'bbloomer_notice_shipping' );
 
function bbloomer_notice_shipping() {
echo '<p class="allow">You want confirm your procced truncation.(نريد تأكيد تقديم العملية )</p>';
}
