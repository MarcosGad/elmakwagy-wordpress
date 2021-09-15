<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Lookbook' ) ) {
	class Kuteshop_Shortcode_Lookbook extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'lookbook';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_lookbook', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-lookbook' );
			$css_class[] = $atts['el_class'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_lookbook', $atts );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php
				if ( $atts['avatar'] ) {
					$lazy        = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
					$lazy_check  = $lazy == 1 ? true : false;
					$image_thumb = kuteshop_resize_image( $atts['avatar'], 270, 270, true, $lazy_check );
					echo '<figure>' . wp_specialchars_decode( $image_thumb['img'] ) . '</figure>';
				}
				?>
				<?php if ( $atts['name'] ): ?>
                    <h4 class="name"><?php echo esc_html( $atts['name'] ); ?></h4>
				<?php endif; ?>
				<?php if ( $atts['dir'] ): ?>
                    <p class="dir"><?php echo esc_html( $atts['dir'] ); ?></p>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Lookbook', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Lookbook();
}