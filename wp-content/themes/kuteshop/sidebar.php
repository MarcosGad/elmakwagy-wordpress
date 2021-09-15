<?php
$kuteshop_blog_used_sidebar = apply_filters( 'theme_get_option', 'blog_sidebar', 'widget-area' );
if ( is_single() ) {
	$kuteshop_blog_used_sidebar = apply_filters( 'theme_get_option', 'single_post_sidebar', 'widget-area' );
}
?>
<?php if ( is_active_sidebar( $kuteshop_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area sidebar-blog">
		<?php dynamic_sidebar( $kuteshop_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>