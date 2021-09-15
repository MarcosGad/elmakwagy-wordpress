<?php get_header(); ?>
<?php
/* Data MetaBox */
$data_meta = get_post_meta( get_the_ID(), '_custom_page_side_options', true );
/* Data MetaBox */
$data_meta_banner      = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
$metabox_enable_banner = isset( $data_meta_banner['kuteshop_metabox_enable_banner'] ) ? $data_meta_banner['kuteshop_metabox_enable_banner'] : 0;
/*Default page layout*/
$page_extra_class = isset( $data_meta['page_extra_class'] ) ? $data_meta['page_extra_class'] : '';
$page_layout      = isset( $data_meta['sidebar_page_layout'] ) ? $data_meta['sidebar_page_layout'] : 'left';
$page_sidebar     = isset( $data_meta['page_sidebar'] ) ? $data_meta['page_sidebar'] : 'widget-area';
/*Main container class*/
$main_container_class   = array();
$main_container_class[] = $page_extra_class;
$main_container_class[] = 'main-container';
if ( $page_layout == 'full' ) {
	$main_container_class[] = 'no-sidebar';
} else {
	$main_container_class[] = $page_layout . '-sidebar';
}
$main_content_class   = array();
$main_content_class[] = 'main-content';
if ( $page_layout == 'full' ) {
	$main_content_class[] = 'col-sm-12';
} else {
	$main_content_class[] = 'col-lg-9 col-md-8';
}
$sidebar_class   = array();
$sidebar_class[] = 'sidebar';
if ( $page_layout != 'full' ) {
	$sidebar_class[] = 'col-lg-3 col-md-4';
}
?>
<?php kuteshop_page_banner(); ?>

    <main class="site-main <?php echo esc_attr( implode( ' ', $main_container_class ) ); ?>">

        <div class="container">
			<?php if ( $metabox_enable_banner != 1 ) :
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
			endif; ?>
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $main_content_class ) ); ?>">
					<?php if ( $metabox_enable_banner != 1 ) : ?>
                        <h1 class="page-title">
                            <span><?php single_post_title(); ?></span>
                        </h1>
					<?php endif;
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
                            <div class="page-main-content">
								<?php
								the_content();
								wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'kuteshop' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'kuteshop' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									)
								);
								?>
                            </div>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						}
					}
					?>
                </div>
				<?php if ( $page_layout != 'full' && is_active_sidebar( $page_sidebar ) ): ?>
                    <div id="widget-area"
                         class="widget-area <?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
						<?php dynamic_sidebar( $page_sidebar ); ?>
                    </div><!-- .widget-area -->
				<?php endif; ?>
            </div>
        </div>
    </main>
<?php get_footer();