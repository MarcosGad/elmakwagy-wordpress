<?php
$header_style = kuteshop_get_option( 'kuteshop_used_header' );
$data_meta    = kuteshop_get_meta( '_custom_metabox_theme_options', 'kuteshop_metabox_used_header' );
$header_style = $data_meta != '' ? $data_meta : $header_style;
$selected     = '';
if ( isset( $_GET['product_cat'] ) && $_GET['product_cat'] ) {
	$selected = $_GET['product_cat'];
}
$args               = array(
	'show_option_none'  => esc_html__( 'All Categories', 'kuteshop' ),
	'taxonomy'          => 'product_cat',
	'class'             => 'category-search-option',
	'hide_empty'        => 1,
	'orderby'           => 'name',
	'order'             => "ASC",
	'tab_index'         => true,
	'hierarchical'      => true,
	'id'                => rand(),
	'name'              => 'product_cat',
	'value_field'       => 'slug',
	'selected'          => $selected,
	'option_none_value' => '0',
);
$block_search_html  = '';
$block_search_class = '';
$form_search_class  = '';
if (
	$header_style == 'style-14' ||
	$header_style == 'style-10' ||
	$header_style == 'style-09' ||
	$header_style == 'style-08'
) {
	$block_search_class = 'kuteshop-dropdown';
	$form_search_class  = 'sub-menu';
	$block_search_html  = '<a href="#" class="icon" data-kuteshop="kuteshop-dropdown"><span class="fa fa-search" aria-hidden="true"></span></a>';
}
// Enqueue required scripts
if ( class_exists( 'DGWT_WC_Ajax_Search' ) ) {
	wp_enqueue_script( 'woocommerce-general' );
	wp_enqueue_script( 'jquery-dgwt-wcas' );
}
?>
<div class="block-search <?php echo esc_attr( $block_search_class ); ?>">
	<?php echo wp_specialchars_decode( $block_search_html ); ?>
    <div class="dgwt-wcas-search-wrapp dgwt-wcas-has-submit"
         data-wcas-context="<?php echo substr( uniqid(), 8, 4 ); ?>">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"
              class="form-search dgwt-wcas-search-form <?php echo esc_attr( $form_search_class ); ?>">
			<?php if (
				$header_style == 'style-02' ||
				$header_style == 'style-06' ||
				$header_style == 'style-12' ||
				$header_style == 'style-13'
			) : ?>
                <div class="form-content search-box results-search instant-search-box">
                    <div class="inner">
                        <input type="search"
                               class="input key-instant-search dgwt-wcas-search-input"
                               name="s"
                               value="<?php echo get_search_query(); ?>"
                               placeholder="<?php echo esc_html__( 'I&#39;m searching for...', 'kuteshop' ); ?>">
                        <div class="dgwt-wcas-preloader"></div>
                    </div>
                </div>
				<?php if ( class_exists( 'WooCommerce' ) ): ?>
                    <input type="hidden" name="post_type" value="product"/>
                    <input type="hidden" name="taxonomy" value="product_cat">
					<?php if ( class_exists( 'DGWT_WC_Ajax_Search' ) ) : ?>
                        <input type="hidden" name="dgwt_wcas" value="1"/>
					<?php endif; ?>
                    <div class="category">
						<?php wp_dropdown_categories( $args ); ?>
                    </div>
				<?php else: ?>
                    <input type="hidden" name="post_type" value="post"/>
				<?php endif; ?>
                <button type="submit" class="btn-submit dgwt-wcas-search-submit">
                    <span class="fa fa-search" aria-hidden="true"></span>
                </button>
			<?php else : ?>
				<?php if ( class_exists( 'WooCommerce' ) ): ?>
                    <input type="hidden" name="post_type" value="product"/>
                    <input type="hidden" name="taxonomy" value="product_cat">
					<?php if ( class_exists( 'DGWT_WC_Ajax_Search' ) ) : ?>
                        <input type="hidden" name="dgwt_wcas" value="1"/>
					<?php endif; ?>
					<?php if ( $header_style != 'style-05' ) : ?>
                        <div class="category">
							<?php wp_dropdown_categories( $args ); ?>
                        </div>
					<?php endif; ?>
				<?php else: ?>
                    <input type="hidden" name="post_type" value="post"/>
				<?php endif; ?>
                <div class="form-content search-box results-search instant-search-box">
                    <div class="inner">
                        <input type="text"
                               class="input key-instant-search dgwt-wcas-search-input"
                               name="s"
                               value="<?php echo get_search_query(); ?>"
                               placeholder="<?php echo esc_html__( 'I&#39;m searching for...', 'kuteshop' ); ?>">
                        <div class="dgwt-wcas-preloader"></div>
                    </div>
                </div>
                <button type="submit" class="btn-submit dgwt-wcas-search-submit">
                    <span class="fa fa-search" aria-hidden="true"></span>
                </button>
			<?php endif; ?>
			<?php
			/* WPML compatible */
			if ( defined( 'ICL_LANGUAGE_CODE' ) ):
				?>
                <input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>"/>
			<?php endif ?>
        </form><!-- block search -->
    </div>
</div>