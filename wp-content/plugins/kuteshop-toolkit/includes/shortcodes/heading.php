<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Heading' ) ) {
	class Kuteshop_Shortcode_Heading extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'custom_heading';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public static function generate_css( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_custom_heading', $atts ) : $atts;
			extract( $atts );
			$css                        = '';
			$kuteshop_heading_reponsive = $styles = array();
			if ( isset( $atts['kuteshop_heading_reponsive'] ) ) {
				$kuteshop_heading_reponsive = vc_param_group_parse_atts( $atts['kuteshop_heading_reponsive'] );
			}
			if ( $kuteshop_heading_reponsive && count( $kuteshop_heading_reponsive ) > 0 ) {
				foreach ( $kuteshop_heading_reponsive as $item ) {
					$style = '';
					if ( isset( $item['responsive_font_container'] ) ) {
						$font_container_field          = WPBMap::getParam( 'kuteshop_custom_heading', 'kuteshop_heading_reponsive' );
						$font_container_obj            = new Vc_Font_Container();
						$font_container_field_settings = isset( $font_container_field['params'][2]['settings'], $font_container_field['params'][2]['settings']['fields'] ) ? $font_container_field['params'][2]['settings']['fields'] : array();
						$font_container_data           = $font_container_obj->_vc_font_container_parse_attributes( $font_container_field_settings, $item['responsive_font_container'] );
						/**
						 * @var $css_class
						 */
						extract( self::getStyles( array(), array(), array(), $font_container_data, $atts ) );
						$style .= implode( ' !important;', $styles );
					}
					$screen = '';
					if ( isset( $item['screen'] ) && is_numeric( $item['screen'] ) && $item['screen'] > 0 ) {
						$screen = $item['screen'];
					} elseif ( isset( $item['screen'] ) && $item['screen'] == 'custom' ) {
						if ( isset( $item['screen_custom'] ) && is_numeric( $item['screen_custom'] ) && $item['screen_custom'] > 0 ) {
							$screen = $item['screen_custom'];
						}
					}
					if ( $screen != '' && is_numeric( $screen ) && $screen > 0 && $style != '' ) {
						$css .= '@media (max-width: ' . $screen . 'px){ .' . $atts['custom_heading_custom_id'] . ' { ' . $style . ' }}';
					}
				}
			}

			return $css;
		}

		/**
		 * Defines fields names for google_fonts, font_container and etc
		 * @since 4.4
		 * @var array
		 */
		protected $fields = array(
			'google_fonts'   => 'google_fonts',
			'font_container' => 'font_container',
			'el_class'       => 'el_class',
			'css'            => 'css',
			'text'           => 'text',
		);

		/**
		 * Parses shortcode attributes and set defaults based on vc_map function relative to shortcode and fields names
		 *
		 * @param $atts
		 *
		 * @return array
		 * @throws Exception
		 * @since 4.3
		 */
		public function getAttributes( $atts )
		{
			/**
			 * Shortcode attributes
			 * @var $text
			 * @var $google_fonts
			 * @var $font_container
			 * @var $el_class
			 * @var $link
			 * @var $css
			 */
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_custom_heading', $atts ) : $atts;
			extract( $atts );
			/**
			 * Get default values from VC_MAP.
			 **/
			$google_fonts_field            = WPBMap::getParam( 'kuteshop_custom_heading', 'google_fonts' );
			$font_container_field          = WPBMap::getParam( 'kuteshop_custom_heading', 'font_container' );
			$font_container_obj            = new Vc_Font_Container();
			$google_fonts_obj              = new Vc_Google_Fonts();
			$font_container_field_settings = isset( $font_container_field['settings'], $font_container_field['settings']['fields'] ) ? $font_container_field['settings']['fields'] : array();
			$google_fonts_field_settings   = isset( $google_fonts_field['settings'], $google_fonts_field['settings']['fields'] ) ? $google_fonts_field['settings']['fields'] : array();
			$font_container_data           = $font_container_obj->_vc_font_container_parse_attributes( $font_container_field_settings, $font_container );
			$google_fonts_data             = strlen( $google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $google_fonts ) : '';

			return array(
				'text'                => isset( $text ) ? $text : '',
				'google_fonts'        => $google_fonts,
				'font_container'      => $font_container,
				'el_class'            => $el_class,
				'css'                 => isset( $css ) ? $css : '',
				'link'                => ( 0 === strpos( $link, '|' ) ) ? false : $link,
				'font_container_data' => $font_container_data,
				'google_fonts_data'   => $google_fonts_data,
			);
		}

		/**
		 * Parses google_fonts_data and font_container_data to get needed css styles to markup
		 *
		 * @param $el_class
		 * @param $css
		 * @param $google_fonts_data
		 * @param $font_container_data
		 * @param $atts
		 *
		 * @return array
		 * @since 4.3
		 */
		static function getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts )
		{
			$styles = array();
			if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
				foreach ( $font_container_data['values'] as $key => $value ) {
					if ( 'tag' !== $key && strlen( $value ) ) {
						if ( preg_match( '/description/', $key ) ) {
							continue;
						}
						if ( 'font_size' === $key || 'line_height' === $key ) {
							$value = preg_replace( '/\s+/', '', $value );
						}
						if ( 'font_size' === $key ) {
							$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
							// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
							$regexr = preg_match( $pattern, $value, $matches );
							$value  = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
							$unit   = isset( $matches[2] ) ? $matches[2] : 'px';
							$value  = $value . $unit;
						}
						if ( strlen( $value ) > 0 ) {
							$styles[] = str_replace( '_', '-', $key ) . ': ' . $value;
						}
					}
				}
			}
			if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && ! empty( $google_fonts_data ) && isset( $google_fonts_data['values'], $google_fonts_data['values']['font_family'], $google_fonts_data['values']['font_style'] ) ) {
				$google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
				$styles[]            = 'font-family:' . $google_fonts_family[0];
				$google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
				$styles[]            = 'font-weight:' . $google_fonts_styles[1];
				$styles[]            = 'font-style:' . $google_fonts_styles[2];
			}
			/**
			 * Filter 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' to change vc_custom_heading class
			 *
			 * @param  string - filter_name
			 * @param  string - element_class
			 * @param  string - shortcode_name
			 * @param  array - shortcode_attributes
			 *
			 * @since 4.3
			 */
			$css_class   = array( 'kuteshop-custom_heading' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['custom_heading_custom_id'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_custom_heading', $atts );

			return array(
				'css_class' => implode( ' ', $css_class ),
				'styles'    => $styles,
			);
		}

		public function output_html( $atts, $content = null )
		{
			$source = $text = $link = $google_fonts = $font_container = $el_class = $css = $font_container_data = $google_fonts_data = array();
			// This is needed to extract $font_container_data and $google_fonts_data
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_custom_heading', $atts ) : $atts;
			extract( $this->getAttributes( $atts ) );
			/**
			 * @var $css_class
			 */
			extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );
			$settings = get_option( 'wpb_js_google_fonts_subsets' );
			if ( is_array( $settings ) && ! empty( $settings ) ) {
				$subsets = '&subset=' . implode( ',', $settings );
			} else {
				$subsets = '';
			}
			if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && isset( $google_fonts_data['values']['font_family'] ) ) {
				wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
			}
			if ( ! empty( $styles ) ) {
				$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
			} else {
				$style = '';
			}
			if ( 'post_title' === $source ) {
				$text = get_the_title( get_the_ID() );
			}
			if ( ! empty( $link ) ) {
				$link        = vc_build_link( $link );
				$link['url'] = apply_filters( 'ovic_shortcode_vc_link', $link['url'] );
				$text        = '<a href="' . esc_attr( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . '>' . $text . '</a>';
			}
			$output = '';
			if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
				$output .= '<div class="' . esc_attr( $css_class ) . '" ';
				$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' >';
				$output .= $text;
				$output .= '</' . $font_container_data['values']['tag'] . '>';
				$output .= '</div>';
			} else {
				$output .= '<' . $font_container_data['values']['tag'] . ' ' . $style . ' class="' . esc_attr( $css_class ) . '">';
				$output .= $text;
				$output .= '</' . $font_container_data['values']['tag'] . '>';
			}

			return apply_filters( 'Kuteshop_Shortcode_Heading', $output, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Heading();
}