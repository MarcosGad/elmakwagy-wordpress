<?php
/* Data MetaBox */
$data_meta             = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
$metabox_enable_banner = isset( $data_meta['kuteshop_metabox_enable_banner'] ) ? $data_meta['kuteshop_metabox_enable_banner'] : 0;
$background            = isset( $data_meta['bg_banner_page'] ) ? $data_meta['bg_banner_page'] : '';
$height                = isset( $data_meta['height_banner'] ) ? $data_meta['height_banner'] : '';
$margin_top            = isset( $data_meta['page_margin_top'] ) ? $data_meta['page_margin_top'] : '';
$margin_bottom         = isset( $data_meta['page_margin_bottom'] ) ? $data_meta['page_margin_bottom'] : '';
$css                   = '';
if ( $metabox_enable_banner != 1 ) {
	return;
}
if ( !empty( $background ) ) {
	foreach ( $background as $key => $data ) {
		if ( !empty( $data ) ) {
			if ( $key == 'background-image' ) {
				if ( !empty( !empty( $data['url'] ) ) ) {
					$css .= '' . $key . ':url(' . $data['url'] . ');';
				}
			} else {
				$css .= '' . $key . ':' . $data . ';';
			}
		}
	}
}
if ( $height != '' ) {
	$css .= 'min-height:' . $height . 'px;';
}
if ( $margin_top != '' ) {
	$css .= 'margin-top:' . $margin_top . 'px;';
}
if ( $margin_bottom != '' ) {
	$css .= 'margin-bottom:' . $margin_bottom . 'px;';
}
?>
<!-- Banner page -->
<div class="inner-page-banner" style='<?php echo esc_attr( $css ); ?>'>
    <div class="container">
		<?php
		if ( !is_front_page() ) {
			$args = array(
				'container'     => 'div',
				'before'        => '',
				'after'         => '',
				'show_on_front' => true,
				'network'       => false,
				'show_title'    => true,
				'show_browse'   => false,
				'post_taxonomy' => array(),
				'labels'        => array(),
				'echo'          => true,
			);
			do_action( 'kuteshop_breadcrumb', $args );
		}
		?>
        <h1 class="page-title">
            <span><?php single_post_title(); ?></span>
        </h1>
    </div>
</div>
<!-- /Banner page -->