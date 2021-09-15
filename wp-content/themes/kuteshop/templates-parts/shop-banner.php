<?php
$banner_class          = array( 'banner-shop' );
$banner_class['slick'] = 'owl-slick';
$data                  = array(
	'infinite'      => true,
	'autoplay'      => true,
	'autoplaySpeed' => 600,
	'slidesMargin'  => 0,
	'slidesToShow'  => 1,
	'speed'         => 2500,
);
$enable_shop_banner    = kuteshop_get_option( 'enable_shop_banner' );
$shop_banner           = kuteshop_get_option( 'woo_shop_banner', '' );
$shop_banner_link      = kuteshop_get_option( 'woo_shop_banner_link', '' );
$sidebar_shop_page     = kuteshop_get_option( 'sidebar_shop_page_position', 'left' );
$shop_banner           = explode( ',', $shop_banner );
if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
	$term = get_queried_object();
	if ( $term ) {
		$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $thumbnail_id ) {
			$shop_banner = array( $thumbnail_id );
		}
	}
}
if ( $sidebar_shop_page == 'full' ) {
	$width  = '1170';
	$height = '300';
} else {
	$width  = '870';
	$height = '288';
}
if ( $enable_shop_banner == 1 ) : ?>
    <?php $count_banner = count( $shop_banner ); ?>
    <div class="<?php echo esc_attr( implode( ' ', $banner_class ) ); ?>"
         data-slick="<?php echo esc_attr( json_encode( $data ) ); ?>">
        <?php for ( $i = 0; $i < $count_banner; $i++ ) : ?>
            <?php $id = $shop_banner[$i] ?>
            <?php if ( !empty( $shop_banner_link[$i] ) ) : ?>
                <?php $link = $shop_banner_link[$i] ?>
                <div class="banner-item">
                    <a href="<?php echo esc_url( $link['woo_shop_banner_link_url'] ); ?>" class="effect normal-effect light-bg">
                        <?php
                        $image_thumb = kuteshop_resize_image( $id, $width, $height, true, false );
                        echo wp_specialchars_decode( $image_thumb['img'] );
                        ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="banner-item">
                    <?php
                    $image_thumb = kuteshop_resize_image( $id, $width, $height, true, false );
                    echo wp_specialchars_decode( $image_thumb['img'] );
                    ?>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif;