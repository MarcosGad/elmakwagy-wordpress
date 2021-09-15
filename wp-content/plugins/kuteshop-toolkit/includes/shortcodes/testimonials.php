<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Testimonials' ) ) {
	class Kuteshop_Shortcode_Testimonials extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'testimonials';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_testimonials', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class                = array( 'kuteshop-testimonials equal-container better-height' );
			$css_class[]              = $atts['style'];
			$css_class[]              = $atts['el_class'];
			$css_class[]              = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_testimonials', $atts );
			$atts['owl_dots']         = 'true';
			$args                     = array(
				'post_type'           => 'testimonial',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'suppress_filter'     => true,
				'orderby'             => $atts['orderby'],
				'order'               => $atts['order'],
				'posts_per_page'      => $atts['per_page'],
			);
			$atts['owl_ls_items']     = 1;
			$atts['owl_navigation']   = 'false';
			$atts['owl_fade']         = 'true';
			$atts['owl_draggable']    = 'false';
			$atts['owl_swipetoslide'] = 'false';
			$loop_posts               = new WP_Query( $args );
			$owl_settings             = apply_filters( 'generate_carousel_data_attributes', 'owl_', $atts );
			$class_dot                = '';
			if ( $atts['style'] != 'style2' ) {
				$class_dot = 'custom-dots';
			}
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ) : ?>
                    <h4 class="kuteshop-title">
                        <span class="title"><?php echo esc_html( $atts['title'] ); ?></span>
                    </h4>
				<?php endif;
				if ( $loop_posts->have_posts() ) : ?>
                    <div class="testimonials-list-owl owl-slick <?php echo esc_attr( $class_dot ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
						<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post();
							$data_meta   = get_post_meta( get_the_ID(), '_custom_testimonial_options', true );
							$avatar      = isset( $data_meta['avatar_testimonial'] ) ? $data_meta['avatar_testimonial'] : '';
							$name        = isset( $data_meta['name_testimonial'] ) ? $data_meta['name_testimonial'] : '';
							$position    = isset( $data_meta['position_testimonial'] ) ? $data_meta['position_testimonial'] : '';
							$image_thumb = kuteshop_resize_image( $avatar, 150, 150, true, false );
							if ( $atts['style'] == 'default' ) {
								$content_number_word = 40;
							} else {
								$content_number_word = 18;
							}
							$permalink = apply_filters( 'ovic_shortcode_vc_link', get_the_permalink() );
							?>
                            <article <?php post_class( 'testimonials-item' ); ?>
                                    data-thumb="<?php echo esc_attr( $image_thumb['url'] ); ?>">
                                <div class="content">
									<?php if ( $atts['style'] == 'style2' ): ?>
                                        <figure>
											<?php echo wp_specialchars_decode( $image_thumb['img'] ); ?>
                                        </figure>
									<?php endif; ?>
                                    <div class="post-content equal-elem">
										<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), $content_number_word, esc_html__( '', 'kuteshop-toolkit' ) ); ?>
                                    </div>
                                    <a href="<?php echo esc_url( $permalink ); ?>" target="_self" class="info">
										<?php
										if ( $atts['style'] == 'default' ) {
											printf( __( '%1s - %2s', 'kuteshop-toolkit' ), $name, $position );
										} else {
											echo '-' . esc_html( $name ) . '-';
										}
										?>
                                    </a>
                                </div>
                            </article>
						<?php endwhile; ?>
                    </div>
				<?php else :
					get_template_part( 'content', 'none' );
				endif; ?>
            </div>
			<?php
			wp_reset_postdata();
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Testimonials', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Testimonials();
}