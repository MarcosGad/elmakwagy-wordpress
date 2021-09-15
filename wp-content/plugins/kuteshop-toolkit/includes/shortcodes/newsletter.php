<?php

if ( ! class_exists( 'Kuteshop_Shortcode_Newsletter' ) ) {

	class Kuteshop_Shortcode_Newsletter extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'newsletter';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_newsletter', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'kuteshop-newsletter' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['style'];
			$css_class[] = 'lazy';
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_newsletter', $atts );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                 data-src="<?php echo wp_get_attachment_image_url( $atts['background'], 'full' ); ?>">
				<?php if ( $atts['style'] == 'style4' ) {
					echo '<div class="head">';
				} ?>
				<?php if ( $atts['title_text'] ) : ?>
                    <h3 class="title"><?php echo esc_html( $atts['title_text'] ); ?></h3>
				<?php endif; ?>
				<?php if ( $atts['subtitle_text'] ) : ?>
                    <p class="subtitle"><?php echo esc_html( $atts['subtitle_text'] ); ?></p>
				<?php endif; ?>
				<?php if ( $atts['style'] == 'style4' ) {
					echo '</div><div class="content">';
				} ?>
                <div class="newsletter-form-wrap">
                    <input class="email email-newsletter" type="email" name="email"
                           placeholder="<?php echo esc_attr( $atts['placeholder_text'] ); ?>">
                    <a href="#" class="button btn-submit submit-newsletter">
						<?php if ( $atts['button_text'] ) : ?>
                            <span><?php echo esc_attr( $atts['button_text'] ); ?></span>
						<?php else: ?>
                            <span class="fa fa-paper-plane-o" aria-hidden="true"></span>
						<?php endif; ?>
                    </a>
                </div>
				<?php if ( $atts['style'] == 'style4' ) {
					echo '</div>';
				} ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Newsletter', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Newsletter();
}