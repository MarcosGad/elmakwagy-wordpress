<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Category' ) ) {
	class Kuteshop_Shortcode_Category extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'category';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_category', $atts ) : $atts;
			extract( $atts );
			$css_class   = array( 'kuteshop-category' );
			$css_class[] = $atts['style'];
			$css_class[] = $atts['el_class'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_category', $atts );
			$lazy        = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
			$lazy_check  = $lazy == 1 ? true : false;
			if ( $atts['style'] == 'style2' ) {
				$width  = 380;
				$height = 532;
			} elseif ( $atts['style'] == 'style1' ) {
				$width  = 200;
				$height = 200;
			} elseif ( $atts['style'] == 'style3' ) {
				$width  = 186;
				$height = 296;
			} elseif ( $atts['style'] == 'style4' ) {
				$width  = true;
				$height = true;
			} else {
				$width  = 273;
				$height = 112;
			}
			$link_banner  = vc_build_link( $atts['link'] );
			$content_html = '<div class="content-category">';
			if ( $atts['title'] ) {
				$content_html .= '<h4 class="title">' . esc_html( $atts['title'] ) . '</h4>';
			}
			if ( $atts['desc'] && ( $atts['style'] == 'style2' || $atts['style'] == 'style4' ) ) {
				$content_html .= '<p class="desc">' . esc_html( $atts['desc'] ) . '</p>';
			}
			if ( $atts['author'] ) {
				$content_html .= '<p class="author">' . esc_html( $atts['author'] ) . '</p>';
			}
			if ( $link_banner['url'] ) {
				$link_banner['url'] = apply_filters( 'ovic_shortcode_vc_link', $link_banner['url'] );
				$content_html       .= '<a href="' . esc_url( $link_banner['url'] ) . '"
                    target="' . esc_attr( $link_banner['target'] ) . '" class="button-category button">
								' . esc_html( $link_banner['title'] ) . '</a>';
			}
			$content_html .= '</div>';
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['style'] == 'default' ) : ?>
                    <div class="top-category">
						<?php if ( $atts['banner'] ) :
							if ( $link_banner['url'] ) : ?>
                                <a class="thumb" href="<?php echo esc_url( $link_banner['url'] ); ?>">
									<?php
									$image_thumb = kuteshop_resize_image( $atts['banner'], $width, $height, true, $lazy_check );
									echo wp_specialchars_decode( $image_thumb['img'] );
									?>
                                </a>
							<?php else: ?>
                                <figure class="thumb">
									<?php
									$image_thumb = kuteshop_resize_image( $atts['banner'], $width, $height, true, $lazy_check );
									echo wp_specialchars_decode( $image_thumb['img'] );
									?>
                                </figure>
							<?php endif;
						endif;
						if ( $atts['title'] ) : ?>
                            <h4 class="title"><?php echo esc_html( $atts['title'] ); ?></h4>
						<?php endif;
						if ( $link_banner['url'] ) : ?>
                            <a href="<?php echo esc_url( $link_banner['url'] ); ?>"
                               target="<?php echo esc_attr( $link_banner['target'] ); ?>"
                               class="button-category button">
								<?php echo esc_html( $link_banner['title'] ); ?>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="content-category">
						<?php $categories = explode( ',', $atts['taxonomy'] ); ?>
                        <ul class="list-category">
							<?php foreach ( $categories as $category ) :
								$term = get_term_by( 'slug', $category, 'product_cat' );
								if ( ! empty( $term ) && ! is_wp_error( $term ) ) :
									$term_link = get_term_link( $term->term_id, 'product_cat' );
									?>
                                    <li>
                                        <a class="cat-filter" href="<?php echo esc_url( $term_link ); ?>">
											<?php echo esc_html( $term->name ); ?>
                                        </a>
                                    </li>
								<?php endif;
							endforeach; ?>
                        </ul>
                    </div>
				<?php elseif ( $atts['style'] == 'style3' ) : ?>
					<?php if ( $atts['title'] ) : ?>
                        <h4 class="title" style="color: <?php echo esc_attr( $atts['title_color'] ) ?>">
                            <span><?php echo esc_html__( 'categories', 'kuteshop-toolkit' ); ?></span>
                            <span class="text"><?php echo esc_html( $atts['title'] ); ?></span>
                        </h4>
					<?php endif; ?>
                    <div class="content-category lazy"
                         data-src="<?php echo wp_get_attachment_image_url( $atts['banner'], 'full' ); ?>">
						<?php $categories = explode( ',', $atts['taxonomy'] ); ?>
                        <ul class="list-category">
							<?php foreach ( $categories as $category ) :
								$term = get_term_by( 'slug', $category, 'product_cat' );
								if ( ! empty( $term ) && ! is_wp_error( $term ) ) :
									$term_link = get_term_link( $term->term_id, 'product_cat' );
									?>
                                    <li>
                                        <a class="cat-filter" href="<?php echo esc_url( $term_link ); ?>">
											<?php echo esc_html( $term->name ); ?>
                                        </a>
                                    </li>
								<?php endif;
							endforeach; ?>
                        </ul>
						<?php if ( $link_banner['url'] ) : ?>
                            <a href="<?php echo esc_url( $link_banner['url'] ); ?>"
                               target="<?php echo esc_attr( $link_banner['target'] ); ?>"
                               class="button-category">
								<?php echo esc_html( $link_banner['title'] ); ?>
                            </a>
						<?php endif; ?>
                    </div>
				<?php elseif ( $atts['style'] == 'style1' ) :
					if ( $atts['title'] ) : ?>
                        <h4 class="title"><?php echo esc_html( $atts['title'] ); ?></h4>
					<?php endif;
					if ( $atts['banner'] ) :
						if ( $link_banner['url'] ) : ?>
                            <a class="thumb" href="<?php echo esc_url( $link_banner['url'] ); ?>">
								<?php
								$image_thumb = kuteshop_resize_image( $atts['banner'], $width, $height, true, $lazy_check );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </a>
						<?php else: ?>
                            <figure class="thumb">
								<?php
								$image_thumb = kuteshop_resize_image( $atts['banner'], $width, $height, true, $lazy_check );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </figure>
						<?php endif;
					endif; ?>
                    <div class="content-category">
						<?php $categories = explode( ',', $atts['taxonomy'] ); ?>
                        <ul class="list-category">
							<?php foreach ( $categories as $category ) :
								$term = get_term_by( 'slug', $category, 'product_cat' );
								if ( ! empty( $term ) && ! is_wp_error( $term ) ) :
									$term_link = get_term_link( $term->term_id, 'product_cat' );
									?>
                                    <li>
                                        <a class="cat-filter" href="<?php echo esc_url( $term_link ); ?>">
											<?php echo esc_html( $term->name ); ?>
                                        </a>
                                    </li>
								<?php endif;
							endforeach; ?>
                        </ul>
						<?php if ( $link_banner['url'] ) : ?>
                            <a href="<?php echo esc_url( $link_banner['url'] ); ?>"
                               target="<?php echo esc_attr( $link_banner['target'] ); ?>"
                               class="button-category button">
								<?php echo esc_html( $link_banner['title'] ); ?>
                            </a>
						<?php endif; ?>
                    </div>
				<?php else:
					if ( $atts['content_position'] == 'top' ) :
						echo wp_specialchars_decode( $content_html );
					endif;
					if ( $atts['banner'] ) :
						if ( $link_banner['url'] ) : ?>
                            <a class="thumb" href="<?php echo esc_url( $link_banner['url'] ); ?>">
								<?php
								$image_thumb = kuteshop_resize_image( $atts['banner'], $width, $height, true, $lazy_check );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </a>
						<?php else: ?>
                            <figure class="thumb">
								<?php
								$image_thumb = kuteshop_resize_image( $atts['banner'], $width, $height, true, $lazy_check );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </figure>
						<?php endif;
					endif;
					if ( $atts['content_position'] == 'bottom' ) :
						echo wp_specialchars_decode( $content_html );
					endif;
				endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Category', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Category();
}