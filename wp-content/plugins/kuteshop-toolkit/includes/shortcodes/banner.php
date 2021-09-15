<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Banner' ) ) {
	class Kuteshop_Shortcode_Banner extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'banner';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function product_item()
		{
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
			?>
            <div <?php post_class( 'product-item' ); ?>>
                <div class="product-inner">
					<?php
					/**
					 * woocommerce_before_shop_loop_item hook.
					 *
					 * @removed woocommerce_template_loop_product_link_open - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item' );
					?>
                    <div class="product-thumb">
						<?php
						/**
						 * woocommerce_before_shop_loop_item_title hook.
						 *
						 * @hooked kuteshop_group_flash - 5
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'woocommerce_before_shop_loop_item_title' );
						?>
                    </div>
                    <div class="product-info">
						<?php
						/**
						 * woocommerce_shop_loop_item_title hook.
						 *
						 * @hooked woocommerce_template_loop_product_title - 10
						 */
						do_action( 'woocommerce_shop_loop_item_title' );
						?>
						<?php
						/**
						 * woocommerce_after_shop_loop_item_title hook.
						 *
						 * @hooked woocommerce_template_loop_rating - 20
						 * @hooked woocommerce_template_loop_price - 10
						 */
						do_action( 'woocommerce_after_shop_loop_item_title' );
						?>
                        <div class="product-button">
							<?php do_action( 'kuteshop_function_shop_loop_item_quickview' ); ?>
                            <div class="add-to-cart">
								<?php
								/**
								 * woocommerce_after_shop_loop_item hook.
								 *
								 * @removed woocommerce_template_loop_product_link_close - 5
								 * @hooked woocommerce_template_loop_add_to_cart - 10
								 */
								do_action( 'woocommerce_after_shop_loop_item' );
								?>
                            </div>
							<?php do_action( 'kuteshop_function_shop_loop_item_compare' ); ?>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_banner', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-banner' );
			$css_class[] = $atts['style'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['position'];
			if ( ! empty( $atts['banne_type'] ) ) {
				$css_class[] = 'banner-' . $atts['banne_type'];
			}
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_banner', $atts );
			/* Product Size */
			$thumb_width  = 0;
			$thumb_height = 0;
			if ( $atts['product_image_size'] ) {
				if ( $atts['product_image_size'] == 'custom' ) {
					$thumb_width  = $atts['product_custom_thumb_width'];
					$thumb_height = $atts['product_custom_thumb_height'];
				} else {
					$product_image_size = explode( "x", $atts['product_image_size'] );
					$thumb_width        = $product_image_size[0];
					$thumb_height       = $product_image_size[1];
				}
				if ( $atts['style'] == 'style1' ) {
					if ( $thumb_width > 0 ) {
						add_filter( 'kuteshop_shop_pruduct_thumb_width', function () use ( $thumb_width ) {
							return $thumb_width;
						} );
					}
					if ( $thumb_height > 0 ) {
						add_filter( 'kuteshop_shop_pruduct_thumb_height', function () use ( $thumb_height ) {
							return $thumb_height;
						} );
					}
				}
			}
			$atts['target'] = 'products';
			$products       = kuteshop_product_query( $atts );
			$url_images     = wp_get_attachment_image_url( $atts['banner_img'], 'full' );
			$banner_link    = vc_build_link( $atts['banner_link'] );
			ob_start();
			$atts['time_countdown'] = apply_filters( 'ovic_change_datetime_countdown', $atts['time_countdown'], 0 );
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="banner-thumb">
					<?php if ( $atts['banner_img'] ) : ?>
						<?php if ( $banner_link['url'] ) :
							$banner_link['url'] = apply_filters( 'ovic_shortcode_vc_link', $banner_link['url'] );
							?>
                            <a href="<?php echo esc_url( $banner_link['url'] ); ?>"
                               target="<?php echo esc_attr( $banner_link['target'] ); ?>">
                                <img src="<?php echo esc_url( $url_images ) ?>" alt="<?php echo get_the_title(); ?>">
                            </a>
						<?php else: ?>
                            <figure class="thumb-link">
                                <img src="<?php echo esc_url( $url_images ) ?>" alt="<?php echo get_the_title(); ?>">
                            </figure>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $atts['style'] == 'style1' ) {
						while ( $products->have_posts() ) {
							$products->the_post();
							self::product_item();
						}
						wp_reset_postdata();
					} ?>
                </div>
                <div class="banner-content">
					<?php if ( $atts['style'] == 'style2' ) : ?>
						<?php if ( $atts['time_countdown'] ) : ?>
                            <div class="product-countdown style6">
                                <h4 class="title">
                                    <span><?php echo esc_html__( 'HOT', 'kuteshop-toolkit' ); ?></span>
                                    <span><?php echo esc_html__( 'DEAL', 'kuteshop-toolkit' ); ?></span>
                                </h4>
                                <div class="kuteshop-countdown"
                                     data-datetime="<?php echo esc_attr( $atts['time_countdown'] ); ?>">
                                    <div class="time days">
                                        <span class="count curr top"></span>
                                        <span class="count next top"></span>
                                        <span class="count next bottom"></span>
                                        <span class="count curr bottom"></span>
                                        <span class="label">
                                    <?php echo esc_html__( 'DAYS', 'kuteshop-toolkit' ); ?>
                                </span>
                                    </div>
                                    <div class="time hours">
                                        <span class="count curr top"></span>
                                        <span class="count next top"></span>
                                        <span class="count next bottom"></span>
                                        <span class="count curr bottom"></span>
                                        <span class="label">
                                    <?php echo esc_html__( 'HOURS', 'kuteshop-toolkit' ); ?>
                                </span>
                                    </div>
                                    <div class="time minutes">
                                        <span class="count curr top"></span>
                                        <span class="count next top"></span>
                                        <span class="count next bottom"></span>
                                        <span class="count curr bottom"></span>
                                        <span class="label">
                                    <?php echo esc_html__( 'MINUTES', 'kuteshop-toolkit' ); ?>
                                </span>
                                    </div>
                                    <div class="time seconds">
                                        <span class="count curr top"></span>
                                        <span class="count next top"></span>
                                        <span class="count next bottom"></span>
                                        <span class="count curr bottom"></span>
                                        <span class="label">
                                    <?php echo esc_html__( 'SECONDS', 'kuteshop-toolkit' ); ?>
                                </span>
                                    </div>
                                </div>
                            </div>
						<?php endif;
					endif; ?>
					<?php if ( $atts['banner_title'] ) : ?>
                        <h3 class="banner-title">
							<?php if ( $banner_link['url'] ) : ?>
                                <a href="<?php echo esc_url( $banner_link['url'] ); ?>"
                                   target="<?php echo esc_attr( $banner_link['target'] ); ?>">
									<?php echo esc_html( $atts['banner_title'] ); ?>
                                </a>
							<?php else: ?>
                                <span><?php echo esc_html( $atts['banner_title'] ); ?></span>
							<?php endif; ?>
                        </h3>
					<?php endif; ?>
					<?php if ( $atts['banner_sub'] ) : ?>
                        <h3 class="sub-title">
							<?php echo esc_html( $atts['banner_sub'] ); ?>
                        </h3>
					<?php endif; ?>
					<div class="desc-wrap">
                        <?php if ( $atts['banner_desc'] ) :
                            $desc = str_replace( '|', '<span>|</span>', $atts['banner_desc'] );
                            ?>
                            <p class="desc">
                                <?php echo wp_specialchars_decode( $desc ); ?>
                            </p>
                        <?php endif; ?>
                        <?php if ( $atts['banner_high_light'] ) :
                            $high_light = explode( '.', $atts['banner_high_light'] );
                            $last = end( $high_light );
                            $text = str_replace( $last, '<span>' . $last . '</span>', $atts['banner_high_light'] );
                            ?>
                            <p class="high-light main-color">
                                <?php echo wp_specialchars_decode( $text ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
					<?php if ( $atts['banner_number'] ) : ?>
                        <p class="banner-number"><?php echo esc_attr( $atts['banner_number'] ); ?></p>
                        <p class="banner-text"><?php echo esc_html__( 'Products', 'kuteshop-toolkit' ) ?></p>
					<?php endif; ?>
					<?php if ( $atts['banner_price'] ) : ?>
                        <p class="banner-price"><?php echo esc_attr( $atts['banner_price'] ); ?></p>
					<?php endif; ?>
                    <div class="button-wrap">
                        <?php if ( $atts['style'] != 'style1' && $atts['style'] != 'style2' && $banner_link['url'] ) : ?>
                            <a href="<?php echo esc_url( $banner_link['url'] ); ?>"
                               target="<?php echo esc_attr( $banner_link['target'] ); ?>" class="banner-button">
                                <?php echo esc_html( $banner_link['title'] ); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ( $atts['style'] == 'style5' && $atts['banner_tour'] ) :
                            $tour_link = vc_build_link( $atts['banner_tour'] );
                            ?>
                            <a href="<?php echo esc_url( $tour_link['url'] ); ?>"
                               target="<?php echo esc_attr( $tour_link['target'] ); ?>" class="banner-tour">
                                <?php echo esc_html( $tour_link['title'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Banner', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Banner();
}