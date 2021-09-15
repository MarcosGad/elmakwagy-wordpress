<?php
global $post;
/* MAIN THEME OPTIONS */
$enable_vertical_menu  = kuteshop_get_option( 'enable_vertical_menu' );
$block_vertical_menu   = kuteshop_get_option( 'block_vertical_menu' );
$vertical_item_visible = kuteshop_get_option( 'vertical_item_visible', 10 );
/* META BOX THEME OPTIONS */
$enable_theme_options = kuteshop_get_option( 'enable_theme_options' );
$meta_data            = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
	$enable_vertical_menu  = isset( $meta_data['metabox_enable_vertical_menu'] ) ? $meta_data['metabox_enable_vertical_menu'] : '';
	$vertical_item_visible = isset( $meta_data['metabox_vertical_item_visible'] ) ? $meta_data['metabox_vertical_item_visible'] : '';
}
if ( $enable_vertical_menu == 1 && has_nav_menu( 'vertical_menu' ) ):
	$block_vertical_class = array( 'vertical-wapper block-nav-category' );
	/* MAIN THEME OPTIONS */
	$vertical_menu_title             = kuteshop_get_option( 'vertical_menu_title', 'Shop By Category' );
	$vertical_menu_button_all_text   = kuteshop_get_option( 'vertical_menu_button_all_text', 'All categoryes' );
	$vertical_menu_button_close_text = kuteshop_get_option( 'vertical_menu_button_close_text', 'Close' );
	/* META BOX THEME OPTIONS */
	if ( !empty( $meta_data ) && $enable_theme_options == 1 ) {
		$vertical_menu_title             = isset( $meta_data['metabox_vertical_menu_title'] ) ? $meta_data['metabox_vertical_menu_title'] : '';
		$vertical_menu_button_all_text   = isset( $meta_data['metabox_vertical_menu_button_all_text'] ) ? $meta_data['metabox_vertical_menu_button_all_text'] : '';
		$vertical_menu_button_close_text = isset( $meta_data['metabox_vertical_menu_button_close_text'] ) ? $meta_data['metabox_vertical_menu_button_close_text'] : '';
	}
	if ( $enable_vertical_menu == 1 ) {
		$block_vertical_class[] = 'has-vertical-menu';
	}
	$id        = '';
	$post_type = '';
	if ( isset( $post->ID ) )
		$id = $post->ID;
	if ( isset( $post->post_type ) )
		$post_type = $post->post_type;
	if ( is_array( $block_vertical_menu ) && in_array( $id, $block_vertical_menu ) && $post_type == 'page' ) {
		$block_vertical_class[] = 'alway-open';
	}
	$count     = 0;
	$locations = get_nav_menu_locations();
	if ( !empty( $locations['vertical_menu'] ) ) {
		$menu_id    = $locations['vertical_menu'];
		$menu_items = wp_get_nav_menu_items( $menu_id );
		foreach ( $menu_items as $menu_item ) {
			if ( $menu_item->menu_item_parent == 0 )
				$count++;
		}
	}
	?>
    <!-- block category -->
    <div data-items="<?php echo esc_attr( $vertical_item_visible ); ?>"
         class="<?php echo esc_attr( implode( ' ', $block_vertical_class ) ); ?>">
        <div class="block-title">
            <span class="fa fa-bars icon-title before" aria-hidden="true"></span>
            <span class="text-title"><?php echo esc_html( $vertical_menu_title ); ?></span>
            <span class="fa fa-bars icon-title after" aria-hidden="true"></span>
        </div>
		<?php if ( !wp_is_mobile() ): ?>
            <div class="block-content verticalmenu-content">
				<?php
				wp_nav_menu( array(
						'menu'            => 'vertical_menu',
						'theme_location'  => 'vertical_menu',
						'depth'           => 4,
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => 'kuteshop-nav vertical-menu',
						'megamenu_layout' => 'vertical',
					)
				);
				if ( $count > $vertical_item_visible ) : ?>
                    <div class="view-all-category">
                        <a href="javascript:void(0);"
                           data-closetext="<?php echo esc_attr( $vertical_menu_button_close_text ); ?>"
                           data-alltext="<?php echo esc_attr( $vertical_menu_button_all_text ) ?>"
                           class="btn-view-all open-cate"><?php echo esc_html( $vertical_menu_button_all_text ) ?></a>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
    </div><!-- block category -->
<?php endif;