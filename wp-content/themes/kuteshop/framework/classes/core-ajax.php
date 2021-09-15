<?php
/**
 * Define a constant if it is not already defined.
 *
 * @param string $name Constant name.
 * @param string $value Value.
 *
 * @since 3.0.0
 *
 */
if ( !function_exists( 'kuteshop_maybe_define_constant' ) ) {
	function kuteshop_maybe_define_constant( $name, $value )
	{
		if ( !defined( $name ) ) {
			define( $name, $value );
		}
	}
}
/**
 * Wrapper for nocache_headers which also disables page caching.
 *
 * @since 3.2.4
 */
if ( !function_exists( 'kuteshop_nocache_headers' ) ) {
	function kuteshop_nocache_headers()
	{
		Kuteshop_Ajax::set_nocache_constants();
		nocache_headers();
	}
}
if ( !class_exists( 'Kuteshop_Ajax' ) ) {
	class Kuteshop_Ajax
	{
		/**
		 * Hook in ajax handlers.
		 */
		public static function init()
		{
			add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
			add_action( 'template_redirect', array( __CLASS__, 'do_kuteshop_ajax' ), 0 );
			add_action( 'after_setup_theme', array( __CLASS__, 'add_ajax_events' ) );
			add_filter( 'wcml_multi_currency_ajax_actions', array( __CLASS__, 'add_action_to_multi_currency_ajax' ), 10, 1 );
		}

		/**
		 * Get KUTESHOP Ajax Endpoint.
		 *
		 * @param string $request Optional.
		 *
		 * @return string
		 */
		public static function get_endpoint( $request = '' )
		{
			return esc_url_raw( apply_filters( 'kuteshop_ajax_get_endpoint',
					add_query_arg(
						'kuteshop-ajax',
						$request,
						remove_query_arg(
							array(),
							home_url( '/', 'relative' )
						)
					),
					$request
				)
			);
		}

		/**
		 * Set constants to prevent caching by some plugins.
		 *
		 * @param mixed $return Value to return. Previously hooked into a filter.
		 *
		 * @return mixed
		 */
		public static function set_nocache_constants( $return = true )
		{
			kuteshop_maybe_define_constant( 'DONOTCACHEPAGE', true );
			kuteshop_maybe_define_constant( 'DONOTCACHEOBJECT', true );
			kuteshop_maybe_define_constant( 'DONOTCACHEDB', true );

			return $return;
		}

		/**
		 * Set KUTESHOP AJAX constant and headers.
		 */
		public static function define_ajax()
		{
			if ( !empty( $_GET['kuteshop-ajax'] ) ) {
				kuteshop_maybe_define_constant( 'DOING_AJAX', true );
				kuteshop_maybe_define_constant( 'KUTESHOP_DOING_AJAX', true );
				$GLOBALS['wpdb']->hide_errors();
				if ( !defined( 'SHORTINIT' ) ) {
					define( 'SHORTINIT', TRUE );
				}
			}
		}

		/**
		 * Send headers for KUTESHOP Ajax Requests.
		 *
		 * @since 2.5.0
		 */
		private static function kuteshop_ajax_headers()
		{
			send_origin_headers();
			@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
			@header( 'X-Robots-Tag: noindex' );
			@header( 'Cache-Control: no-cache' );
			@header( 'Pragma: no-cache' );
			send_nosniff_header();
			kuteshop_nocache_headers();
			status_header( 200 );
		}

		/**
		 * Check for KUTESHOP Ajax request and fire action.
		 */
		public static function do_kuteshop_ajax()
		{
			global $wp_query;
			if ( !empty( $_GET['kuteshop-ajax'] ) ) {
				$wp_query->set( 'kuteshop-ajax', sanitize_text_field( wp_unslash( $_GET['kuteshop-ajax'] ) ) );
			}
			if ( !empty( $_GET['kuteshop_raw_content'] ) ) {
				$wp_query->set( 'kuteshop_raw_content', sanitize_text_field( wp_unslash( $_GET['kuteshop_raw_content'] ) ) );
			}
			$action  = $wp_query->get( 'kuteshop-ajax' );
			$content = $wp_query->get( 'kuteshop_raw_content' );
			if ( $action || $content ) {
				self::kuteshop_ajax_headers();
				if ( $action ) {
					$action = sanitize_text_field( $action );
					do_action( 'kuteshop_ajax_' . $action );
					wp_die();
				} else {
					remove_all_actions( 'wp_head' );
					remove_all_actions( 'wp_footer' );
				}
			}
		}

		/**
		 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
		 */
		public static function add_ajax_events()
		{
			// kuteshop_EVENT => nopriv.
			$ajax_events = array(
				'content_ajax_tab'   => true,
				'ajax_tab_filter'    => true,
				'add_to_cart_single' => true,
				'send_email_friend'  => true,
				'form_send_friend'   => true,
				'delete_transients'  => false,
			);
			$ajax_events = apply_filters( 'kuteshop_ajax_event_register', $ajax_events );
			if ( !empty( $ajax_events ) ) {
				foreach ( $ajax_events as $ajax_event => $nopriv ) {
					add_action( 'wp_ajax_kuteshop_' . $ajax_event, array( __CLASS__, $ajax_event ) );
					if ( $nopriv ) {
						add_action( 'wp_ajax_nopriv_kuteshop_' . $ajax_event, array( __CLASS__, $ajax_event ) );
						// KUTESHOP AJAX can be used for frontend ajax requests.
						add_action( 'kuteshop_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
					}
				}
			}
		}

		/**
		 * Deletes all transients.
		 *
		 * @echo int  Number of deleted transient DB entries
		 */
		public static function delete_transients()
		{
			global $wpdb;

			$count = $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_%' OR option_name LIKE '\_site\_transient\_%'" );

			do_action( 'ovic_delete_transients', $count );

			update_option( '_ovic_database_updated', '' );

			wp_send_json( $count );
			wp_die();
		}

		public static function detected_shortcode( $id, $tab_id = null, $product_id = null )
		{
			$post              = get_post( $id );
			$content           = preg_replace( '/\s+/', ' ', $post->post_content );
			$shortcode_section = '';
			if ( $tab_id == null ) {
				$out = array();
				preg_match_all( '/\[kuteshop_products(.*?)\]/', $content, $matches );
				if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
					foreach ( $matches[0] as $key => $value ) {
						if ( shortcode_parse_atts( $matches[1][$key] )['products_custom_id'] == $product_id ) {
							$out['atts']    = shortcode_parse_atts( $matches[1][$key] );
							$out['content'] = $value;
						}
					}
				}
				$shortcode_section = $out;
			}
			if ( $product_id == null ) {
				preg_match_all( '/\[vc_tta_section(.*?)vc_tta_section\]/', $content, $matches );
				if ( $matches[0] && is_array( $matches[0] ) && count( $matches[0] ) > 0 ) {
					foreach ( $matches[0] as $key => $value ) {
						preg_match_all( '/tab_id="([^"]+)"/', $matches[0][$key], $matches_ids );
						foreach ( $matches_ids[1] as $matches_id ) {
							if ( $tab_id == $matches_id ) {
								$shortcode_section = $value;
							}
						}
					}
				}
			}

			return $shortcode_section;
		}

		public static function add_action_to_multi_currency_ajax( $ajax_actions )
		{
			$ajax_actions[] = 'kuteshop_content_ajax_tab'; // Add a AJAX action to the array
			$ajax_actions[] = 'kuteshop_ajax_tab_filter'; // Add a AJAX action to the array

			return $ajax_actions;
		}

		public static function content_ajax_tab()
		{
			$response   = array(
				'html'    => '',
				'message' => '',
				'success' => 'no',
			);
			$section_id = isset( $_POST['section_id'] ) ? $_POST['section_id'] : '';
			$id         = isset( $_POST['id'] ) ? $_POST['id'] : '';
			$shortcode  = self::detected_shortcode( $id, $section_id, null );
			WPBMap::addAllMappedShortcodes();
			$response['html']    = wpb_js_remove_wpautop( $shortcode );
			$response['success'] = 'ok';
			wp_send_json( $response );
			die();
		}

		public static function ajax_tab_filter()
		{
			$response           = array(
				'html'    => '',
				'message' => '',
				'success' => 'no',
			);
			$cat                = isset( $_POST['cat'] ) ? $_POST['cat'] : '';
			$id                 = isset( $_POST['id'] ) ? $_POST['id'] : '';
			$check              = isset( $_POST['check'] ) ? $_POST['check'] : '';
			$product_id         = isset( $_POST['product_id'] ) ? $_POST['product_id'] : '';
			$list_style         = isset( $_POST['list_style'] ) ? $_POST['list_style'] : '';
			$shortcode_data     = self::detected_shortcode( $id, null, $product_id );
			$atts               = $shortcode_data['atts'];
			$atts['taxonomy']   = $cat;
			$products           = kuteshop_product_query( $atts );
			$product_item_class = array( 'product-item' );
			if ( !empty( $atts['target'] ) ) {
				$product_item_class[] = $atts['target'];
			}
			$product_item_class[] = 'style-' . $atts['product_style'];
			if (
				$atts['product_style'] == '2' ||
				$atts['product_style'] == '4' ||
				$atts['product_style'] == '8' ||
				$atts['product_style'] == '9' ||
				$atts['product_style'] == '10'
			) {
				$product_item_class[] = 'style-1';
			}
			$product_list_class = array();
			if ( $list_style == 'grid' ) {
				$product_list_class[] = 'product-list-grid row auto-clear equal-container better-height ';
				$product_item_class[] = $atts['boostrap_rows_space'];
				$product_item_class[] = 'col-bg-' . $atts['boostrap_bg_items'];
				$product_item_class[] = 'col-lg-' . $atts['boostrap_lg_items'];
				$product_item_class[] = 'col-md-' . $atts['boostrap_md_items'];
				$product_item_class[] = 'col-sm-' . $atts['boostrap_sm_items'];
				$product_item_class[] = 'col-xs-' . $atts['boostrap_xs_items'];
				$product_item_class[] = 'col-ts-' . $atts['boostrap_ts_items'];
			}
			ob_start();
			WPBMap::addAllMappedShortcodes();
			if ( $check == 1 ) :
				if ( $products->have_posts() ): ?>
					<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                        <div <?php post_class( $product_item_class ); ?>>
							<?php wc_get_template_part( 'product-styles/content-product-style', $atts['product_style'] ); ?>
                        </div>
					<?php endwhile; ?>
				<?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'kuteshop' ); ?></strong>
                    </p>
				<?php endif;
			else:
				echo do_shortcode( $shortcode_data['content'] );
			endif;
			$response['html']    = ob_get_clean();
			$response['success'] = 'ok';
			wp_send_json( $response );
			die();
		}

		public static function send_email_friend()
		{
			check_ajax_referer( 'kuteshop_ajax_frontend', 'security' );

			$friend_name     = !empty( $_POST['friend_name'] ) ? sanitize_text_field( $_POST['friend_name'] ) : '';
			$friend_email    = !empty( $_POST['friend_email'] ) ? sanitize_email( $_POST['friend_email'] ) : '';
			$product_id      = !empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : '';
			$captcha_code    = !empty( $_POST['captcha_code'] ) ? sanitize_text_field( $_POST['captcha_code'] ) : '';
			$session_captcha = !empty( $_SESSION['ovic_captcha_code'] ) ? sanitize_text_field( $_SESSION['ovic_captcha_code'] ) : '';

			if ( !empty( $session_captcha ) && $captcha_code != $session_captcha ) {
				$response = array(
					'status'  => 'warning',
					'message' => esc_html__( 'Captcha Code Incorrect.', 'kuteshop' ),
				);
			} elseif ( $friend_name == "" ) {
				$response = array(
					'status'  => 'warning',
					'message' => esc_html__( 'Please enter the name of your friend.', 'kuteshop' ),
				);
			} elseif ( $friend_email == "" ) {
				$response = array(
					'status'  => 'warning',
					'message' => esc_html__( 'Please enter the email of your friend.', 'kuteshop' ),
				);
			} elseif ( !is_email( $friend_email ) ) {
				$response = array(
					'status'  => 'warning',
					'message' => esc_html__( 'The email address is not correct.', 'kuteshop' ),
				);
			} elseif ( class_exists( 'WooCommerce' ) ) {
				// load the mailer class
				$mailer = WC()->mailer();

				$current_user = wp_get_current_user();

				$product = wc_get_product( $product_id );

				$headers = $current_user->display_name;

				ob_start();
				wc_get_template( 'content-email.php',
					array(
						'friend_name'       => $friend_name,
						'product_permalink' => $product->get_permalink(),
						'product_title'     => $product->get_title(),
						'email_heading'     => $headers,
						'email'             => $mailer,
					)
				);
				?>
				<?php
				$message = ob_get_clean();

				$subject = get_bloginfo( 'name' );

				//send the email through wordpress
				$status = $mailer->send( $friend_email, $subject, $message, $headers );

				if ( $status ) {
					$response = array(
						'status'  => 'done',
						'message' => esc_html__( 'Your e-mail has been sent successfully.', 'kuteshop' ),
					);
				}
			}
			wp_send_json( $response );
			wp_die();
		}

		public static function form_send_friend()
		{
			check_ajax_referer( 'kuteshop_ajax_frontend', 'security' );

			if ( !empty( $_POST['product_id'] ) ):?>
				<?php
				$product_id    = absint( $_POST['product_id'] );
				$product_image = apply_filters( 'theme_resize_image', get_post_thumbnail_id( $product_id ), 355, 460, true, false );
				$image_captcha = get_theme_file_uri( 'framework/includes/captcha/image.php' );
				?>
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div id="form-send-friend" class="form-send-friend">
                            <h2 class="title"><?php esc_html_e( 'SEND TO A FRIEND', 'kuteshop' ); ?></h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="product-info">
                                        <div class="prodcut-image">
											<?php echo wp_kses_post( $product_image['img'] ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div id="form-send-friend-msg"></div>
                                    <div class="form">
                                        <p>
                                            <label><?php esc_html_e( 'Name of your friend * :', 'kuteshop' ); ?></label>
                                            <input class="text-input" id="friend_name" name="friend_name" type="text"
                                                   value="">
                                        </p>
                                        <p class="text">
                                            <label for="friend_email"><?php esc_html_e( 'E-mail address of your friend * :', 'kuteshop' ) ?></label>
                                            <input class="text-input" id="friend_email" name="friend_email" type="email"
                                                   value="" autocomplete="email">
                                        </p>
                                        <p>
                                            <img src="<?php echo esc_url( $image_captcha ); ?>"
                                                 id="img-captcha"/>
                                            <input id="captcha_reload" type="button" value="Reload"
                                                   onclick="jQuery('#img-captcha').attr('src', '<?php echo esc_url( $image_captcha ); ?>?rand=' + Math.random())"/>
                                            <br/>
                                        </p>
                                        <p>
                                            <input type="text" class="captcha_code" id="captcha_code" value=""/>
                                        </p>
                                        <p>
                                            <button data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                                    id="button-send-to-friend"
                                                    class="button">
												<?php esc_html_e( 'Send', 'kuteshop' ); ?>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-close" data-dismiss="modal">x</button>
                    </div>
                </div>
			<?php
			endif;
			wp_die();
		}

		public static function add_to_cart_single()
		{
			$product_id = isset( $_POST['product_id'] ) ? apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) ) : 0;
			if ( isset( $_POST['add-to-cart'] ) ) {
				$product_id = wp_unslash( $_POST['add-to-cart'] );
			}
			$product           = wc_get_product( $product_id );
			$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			$product_status    = get_post_status( $product_id );
			$variation_id      = isset( $_POST['variation_id'] ) ? wp_unslash( $_POST['variation_id'] ) : 0;
			$variation         = array();
			if ( $product && 'variation' === $product->get_type() ) {
				$variation_id = $product_id;
				$product_id   = $product->get_parent_id();
				$variation    = $product->get_variation_attributes();
			}
			if ( $product && $passed_validation && 'publish' === $product_status ) {
				if ( 'variation' === $product->get_type() && $variation_id > 0 && $product_id > 0 ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
				} elseif ( is_array( $quantity ) && !empty( $quantity ) && 'group' === $product->get_type() ) {
					foreach ( $quantity as $product_id => $qty ) {
						if ( $qty > 0 )
							WC()->cart->add_to_cart( $product_id, $qty );
					}
				} elseif ( !is_array( $quantity ) && is_numeric( $quantity ) && 'simple' === $product->get_type() ) {
					WC()->cart->add_to_cart( $product_id, $quantity );
				}
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
					wc_add_to_cart_message( array( $product_id => $quantity ), true );
				}
				// Return fragments
				WC_AJAX::get_refreshed_fragments();
			} else {
				// If there was an error adding to the cart, redirect to the product page to show any errors
				$data = array(
					'error'       => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
				);
				wp_send_json( $data );
			}
			wp_die();
		}
	}

	Kuteshop_Ajax::init();
}