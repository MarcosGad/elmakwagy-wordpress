<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Slider' ) ) {
	class Kuteshop_Shortcode_Slider extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'slider';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public static function generate_css( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_slider', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( $atts['slide_margin'] != '' && $atts['slide_margin'] != 0 ) {
				$css .= '.' . $atts['slider_custom_id'] . ' .slick-list{ margin: -' . intval( $atts['slide_margin'] ) / 2 . 'px;} ';
				$css .= '.' . $atts['slider_custom_id'] . ' .slick-list .slick-slide{ padding: ' . intval( $atts['slide_margin'] ) / 2 . 'px;} ';
			} else {
				$css .= '.' . $atts['slider_custom_id'] . ' .slick-list{ margin: 0;} ';
				$css .= '.' . $atts['slider_custom_id'] . ' .slick-list .slick-slide{ padding: 0;} ';
			}

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_slider', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-slider' );
			$css_class[] = $atts['style'];
			$css_class[] = $atts['el_class'];
            $css_class[] = $atts['owl_navigation_style'] . '-wrap';
			$css_class[] = $atts['slider_custom_id'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_slider', $atts );
			if ( $atts['style'] == 'style3' ) {
				$atts['owl_dots'] = 'true';
			}
			$owl_settings = apply_filters( 'generate_carousel_data_attributes', 'owl_', $atts );
			$owl_class    = array( 'owl-slick' );
			$owl_class[]  = $atts['owl_navigation_style'];
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['slider_title'] || $atts['slider_sub_title'] ) : ?>
                    <div class="slider-head">
						<?php if ( $atts['slider_title'] ) : ?>
                            <h4 class="kuteshop-title">
                                <span class="title"><?php echo esc_html( $atts['slider_title'] ); ?></span>
                            </h4>
						<?php endif; ?>
						<?php if ( $atts['slider_sub_title'] ) : ?>
                            <span class="sub-title"><?php echo esc_html( $atts['slider_sub_title'] ); ?></span>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
                <div class="<?php echo esc_attr( implode( ' ', $owl_class ) ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
					<?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Slider', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Slider();
}