<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Blog' ) ) {
	class Kuteshop_Shortcode_Blog extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'blog';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public static function generate_css( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_blog', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( $atts['slide_margin'] != '' && $atts['slide_margin'] != 0 ) {
				if ( $atts['owl_vertical'] == 'true' ) {
					$css .= '
                    .kuteshop-blog.' . $atts['blog_custom_id'] . ' .slick-list{ margin:-' . intval( $atts['slide_margin'] ) / 2 . 'px 0;}
                    .kuteshop-blog.' . $atts['blog_custom_id'] . ' .slick-list .slick-slide{ padding:' . intval( $atts['slide_margin'] ) / 2 . 'px 0;}';
				} else {
					$css .= '
                    .kuteshop-blog.' . $atts['blog_custom_id'] . ' .slick-list{ margin:0 -' . intval( $atts['slide_margin'] ) / 2 . 'px;}
                    .kuteshop-blog.' . $atts['blog_custom_id'] . ' .slick-list .slick-slide{ padding:0 ' . intval( $atts['slide_margin'] ) / 2 . 'px;}';
				}
			} else {
				$css .= '
				.kuteshop-blog.' . $atts['blog_custom_id'] . ' .slick-list{ margin: 0;} 
				.kuteshop-blog.' . $atts['blog_custom_id'] . ' .slick-list .slick-slide{ padding: 0;}';
			}

			return $css;
		}

		function blog_content( $position )
		{
			$permalink   = apply_filters( 'ovic_shortcode_vc_link', get_the_permalink() );
			$lazy        = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
			$lazy_check  = $lazy == 1 ? true : false;
			$image_thumb = kuteshop_resize_image( get_post_thumbnail_id(), 292, 292, true, $lazy_check );
			if ( $position[1] == 'top' ) : ?>
                <div class="blog-thumb">
                    <a href="<?php echo esc_url( $permalink ); ?>">
						<?php echo wp_specialchars_decode( $image_thumb['img'] ); ?>
                    </a>
                    <span class="blog-date"><?php echo get_the_date( 'd F, Y' ); ?></span>
                </div>
                <div class="blog-info equal-elem">
                    <h4 class="blog-title"><a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a></h4>
                    <div class="post-content">
						<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 16, esc_html__( '...', 'kuteshop-toolkit' ) ); ?>
                    </div>
                    <a class="button read-more screen-reader-text" href="<?php echo esc_url( $permalink ); ?>">
                        <span class="fa fa-angle-right"></span>
						<?php echo esc_html__( 'Read more', 'kuteshop-toolkit' ); ?>
                    </a>
                </div>
			<?php
			else : ?>
                <div class="blog-info equal-elem">
                    <h4 class="blog-title"><a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a></h4>
                    <div class="post-content">
						<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 16, esc_html__( '...', 'kuteshop-toolkit' ) ); ?>
                    </div>
                    <a class="button read-more screen-reader-text" href="<?php echo esc_url( $permalink ); ?>">
                        <span class="fa fa-angle-right"></span>
						<?php echo esc_html__( 'Read more', 'kuteshop-toolkit' ); ?>
                    </a>
                </div>
                <div class="blog-thumb">
                    <a href="<?php echo esc_url( $permalink ); ?>">
						<?php echo wp_specialchars_decode( $image_thumb['img'] ); ?>
                    </a>
                    <span class="blog-date"><?php echo get_the_date( 'd F, Y' ); ?></span>
                </div>
			<?php
			endif;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_blog', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-blog equal-container better-height' );
			$css_class[] = $atts['style'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['blog_custom_id'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_blog', $atts );
			if ( $atts['style'] == 'style3' ) {
				$atts['owl_dots'] = 'true';
			}
			$args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'suppress_filter'     => true,
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
			);
			if ( $atts['category_slug'] != '' ) {
				$args['category_name'] = $atts['category_slug'];
			}
			if ( $atts['select_post'] == 1 ) {
				$args['post__in']       = explode( ',', $atts['post_ids'] );
				$args['posts_per_page'] = - 1;
			} else {
				$args['posts_per_page'] = $atts['per_page'];
			}
			$loop_posts   = new WP_Query( $args );
			$owl_settings = apply_filters( 'generate_carousel_data_attributes', 'owl_', $atts );
			$i            = 0;
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['blog_title'] ) : ?>
                    <h4 class="kuteshop-title <?php echo esc_attr( $atts['style_title'] ); ?>">
                        <span class="title"><?php echo esc_html( $atts['blog_title'] ); ?></span>
						<?php if ( $atts['blog_desc'] ) : ?>
                            <span class="sub-title"><?php echo esc_html( $atts['blog_desc'] ); ?></span>
						<?php endif; ?>
                    </h4>
				<?php endif; ?>
				<?php if ( $loop_posts->have_posts() ) : ?>
                    <div class="blog-list-owl owl-slick" <?php echo esc_attr( $owl_settings ); ?>>
						<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post();
							$positions = array( 'blog-item' );
							if ( $i % 2 == 0 ) {
								$positions[] = $atts['style'] == 'style4' ? 'bottom' : 'left';
							} else {
								$positions[] = $atts['style'] == 'style4' ? 'top' : 'right';
							}
							$i ++;
							?>
                            <article <?php post_class( $positions ); ?>>
                                <div class="blog-inner">
									<?php
									if ( $atts['style'] == 'style4' ) {
										self::blog_content( $positions );
									} else {
										get_template_part( 'templates/blog/blog-style/content-blog', $atts['style'] );
									}
									?>
                                </div>
                            </article>
						<?php endwhile; ?>
                    </div>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>
            </div>
			<?php
			wp_reset_postdata();
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Blog', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Blog();
}