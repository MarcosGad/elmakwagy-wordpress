<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
?>
<div class="product_meta">

    <div class="utilities">
        <a class="print" data-element="single-product" href="javascript:print();">
            <span class="fa fa-print icon"></span>
			<?php esc_html_e( 'Print', 'kuteshop' ); ?>
        </a>
        <a href="#" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" class="send-to-friend">
            <span class="fa fa-envelope-o icon"></span> <?php esc_html_e( 'Send to a friend', 'kuteshop' ); ?>
        </a>
    </div>

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

        <span class="sku_wrapper">
            <span class="title"><?php esc_html_e( 'SKU:', 'kuteshop' ); ?></span>
            <span class="sku">
                <?php
				if ( $sku = $product->get_sku() ) {
					echo esc_html( $sku );
				} else {
					echo esc_html__( 'N/A', 'kuteshop' );
				}
				?>
            </span>
        </span>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in"><span class="title">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'kuteshop' ) . '</span><span class="posted_item"> ', '</span></span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as"><span class="title">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'kuteshop' ) . '</span><span class="tagged_item"> ', '</span></span>' ); ?>

	<?php
	$enable_share = apply_filters( 'theme_get_option', 'enable_share_product' );
	if ( $enable_share == 1 ) : ?>
        <div class="share">
            <span class="title"><?php echo esc_html__( 'Share on:', 'kuteshop' ); ?></span>
			<?php
			$share_image_url  = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'full' );
			$share_link_url   = get_permalink( get_the_ID() );
			$share_link_title = get_the_title();
			$share_summary    = get_the_excerpt();
			$twitter          = 'https://twitter.com/share?url=' . $share_link_url . '&text=' . $share_summary;
			$facebook         = 'https://www.facebook.com/sharer.php?u=' . $share_link_url;
			$pinterest        = 'https://pinterest.com/pin/create/button/?url=' . $share_link_url . '&description=' . $share_summary . '&media=' . $share_image_url;
			?>
            <div class="kuteshop-share-socials">
                <a class="twitter"
                   href="<?php echo esc_url( $twitter ); ?>"
                   title="<?php echo esc_attr__( 'Twitter', 'kuteshop' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-twitter"></i>
                </a>
                <a class="facebook"
                   href="<?php echo esc_url( $facebook ); ?>"
                   title="<?php echo esc_attr__( 'Facebook', 'kuteshop' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-facebook"></i>
                </a>
                <a class="pinterest"
                   href="<?php echo esc_url( $pinterest ); ?>"
                   title="<?php echo esc_attr__( 'Pinterest', 'kuteshop' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-pinterest"></i>
                </a>
            </div>
        </div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
