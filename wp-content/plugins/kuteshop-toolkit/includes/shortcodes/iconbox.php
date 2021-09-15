<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Iconbox' ) ) {
	class Kuteshop_Shortcode_Iconbox extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'iconbox';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_iconbox', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-iconbox' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['style'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_iconbox', $atts );

			if ( $atts['icon_type'] != 'kuteshopcustomfonts' ) {
				vc_icon_element_fonts_enqueue( $atts['icon_type'] );
			}

			if ( $atts['icon_type'] == 'image' ) {
				$icon = wp_get_attachment_image( $atts['icon_image'], 'full' );
			} else {
				$icon = '<span class="' . $atts[ 'icon_' . $atts['icon_type'] ] . '"></span>';
			}

			$link_icon        = vc_build_link( $atts['link'] );
			$link_icon['url'] = apply_filters( 'ovic_shortcode_vc_link', $link_icon['url'] );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="iconbox-inner">
					<?php if ( $atts['style'] == 'style1' ) : ?>
                        <div class="head">
							<?php if ( $icon ): ?>
                                <div class="icon"><?php echo wp_specialchars_decode( $icon ); ?></div>
							<?php endif;
							if ( $atts['title'] ): ?>
                                <h4 class="title"><?php echo esc_html( $atts['title'] ); ?></h4>
							<?php endif; ?>
                        </div>
						<?php if ( $atts['text_content'] ): ?>
                            <p class="text"><?php echo wp_specialchars_decode( $atts['text_content'] ); ?></p>
						<?php endif;
					else:
						if ( $icon ): ?>
							<?php
							if ( $link_icon['url'] ) : ?>
                                <a href="<?php echo esc_url( $link_icon['url'] ); ?>"
                                   target="<?php echo esc_attr( $link_icon['target'] ); ?>" class="icon">
									<?php echo wp_specialchars_decode( $icon ); ?>
                                </a>
							<?php else: ?>
                                <div class="icon"><?php echo wp_specialchars_decode( $icon ); ?></div>
							<?php endif; ?>
						<?php endif; ?>
                        <div class="content">
							<?php if ( $atts['title'] ):
								if ( $link_icon['url'] ) : ?>
                                    <h4 class="title">
                                        <a href="<?php echo esc_url( $link_icon['url'] ); ?>"
                                           target="<?php echo esc_attr( $link_icon['target'] ); ?>">
											<?php echo esc_html( $atts['title'] ); ?>
                                        </a>
                                    </h4>
								<?php else: ?>
                                    <h4 class="title"><?php echo esc_html( $atts['title'] ); ?></h4>
								<?php endif;
							endif;
							if ( $atts['text_content'] ): ?>
                                <p class="text"><?php echo wp_specialchars_decode( $atts['text_content'] ); ?></p>
							<?php endif; ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Iconbox', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Iconbox();
}