<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Socials' ) ) {
	class Kuteshop_Shortcode_Socials extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'socials';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_socials', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-socials' );
			$css_class[] = $atts['socials_style'];
			$css_class[] = $atts['el_class'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_socials', $atts );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( ! empty( $atts['use_socials'] ) ):
					$socials = explode( ',', $atts['use_socials'] );
					$all_socials = function_exists( 'kuteshop_get_option' ) ? kuteshop_get_option( 'user_all_social' ) : array();
					?>
                    <ul class="socials">
						<?php foreach ( $socials as $social ) :
							if ( isset( $all_socials[ $social ] ) ) :
								$array_social = $all_socials[ $social ]; ?>
                                <li>
                                    <a class="social-item"
                                       href="<?php echo esc_url( $array_social['link_social'] ) ?>"
                                       target="_blank">
                                        <span class="icon <?php echo esc_attr( $array_social['icon_social'] ); ?>"></span>
                                    </a>
                                </li>
							<?php endif;
						endforeach; ?>
                    </ul>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Socials', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Socials();
}