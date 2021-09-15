<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Custommenu' ) ) {
	class Kuteshop_Shortcode_Custommenu extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'custommenu';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_custommenu', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-custommenu' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['style'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_custommenu', $atts );
			$nav_menu    = get_term_by( 'slug', $atts['menu'], 'nav_menu' );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $atts['title'] ): ?>
                    <h3 class="title"><span><?php echo esc_html( $atts['title'] ); ?></span></h3>
				<?php endif ?>
				<?php if ( ! is_wp_error( $nav_menu ) && is_object( $nav_menu ) && ! empty( $nav_menu ) ): ?>
					<?php
					wp_nav_menu( array(
							'menu'            => $nav_menu->slug,
							'theme_location'  => $nav_menu->slug,
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'menu',
						)
					);
					?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Custommenu', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Custommenu();
}