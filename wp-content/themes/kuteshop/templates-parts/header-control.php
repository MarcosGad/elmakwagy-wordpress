<?php
$html             = '';
$current_language = '';
$list_language    = '';
$languages        = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
if ( !empty( $languages ) ) {
	foreach ( $languages as $l ) {
		if ( !$l['active'] ) {
			$list_language .= '
			<li class="menu-item">
				<a href="' . esc_url( $l['url'] ) . '">
					<img src="' . esc_url( $l['country_flag_url'] ) . '" height="12"
						 alt="' . esc_attr( $l['language_code'] ) . '" width="18"/>
					' . esc_html( $l['native_name'] ) . '
				</a>
			</li>';
		} else {
			$current_language = '
			<a href="' . esc_url( $l['url'] ) . '" data-kuteshop="kuteshop-dropdown">
				<img src="' . esc_url( $l['country_flag_url'] ) . '" height="12"
					 alt="' . esc_attr( $l['language_code'] ) . '" width="18"/>
				' . esc_html( $l['native_name'] ) . '
			</a>
			<span class="toggle-sub-menu"></span>';
		}
	}
	if ( class_exists( 'woocommerce_wpml' ) ) {
		$html .= '<div class="menu-item block-currency">';
		$html .= do_shortcode( '[currency_switcher format="%code%" switcher_style="wcml-dropdown"]' );
		$html .= '</div>';
	}
	$html .= '<div class="kuteshop-dropdown block-language">';
	$html .= $current_language . '<ul class="sub-menu">' . $list_language . '</ul>';
	$html .= '</div>';
}
echo wp_specialchars_decode( $html );