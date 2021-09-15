<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kuteshop
 */
?>
<?php
get_header();
/* Blog Layout */
$blog_layout     = apply_filters( 'theme_get_option', 'sidebar_blog_layout', 'left' );
$container_class = array( 'main-container' );
if ( is_single() ) {
	/*Single post layout*/
	$blog_layout = apply_filters( 'theme_get_option', 'sidebar_single_post_position', 'left' );
}
if ( get_post_type( get_the_ID() ) == 'testimonial' ) {
	$blog_layout = 'full';
}
if ( $blog_layout == 'full' ) {
	$container_class[] = 'no-sidebar';
} else {
	$container_class[] = $blog_layout . '-sidebar';
}
$content_class   = array();
$content_class[] = 'main-content';
if ( $blog_layout == 'full' ) {
	$content_class[] = 'col-sm-12';
} else {
	$content_class[] = 'col-lg-9 col-md-8';
}
$sidebar_class   = array();
$sidebar_class[] = 'sidebar';
if ( $blog_layout != 'full' ) {
	$sidebar_class[] = 'col-lg-3 col-md-4';
}
?>
    <div class="<?php echo esc_attr( implode( ' ', $container_class ) ); ?>">
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
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $content_class ) ); ?>">
					<?php if ( is_search() ) : ?>
                        <div class="container">
							<?php if ( have_posts() ) : ?>
                                <h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'kuteshop' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
							<?php endif; ?>
                        </div>
					<?php endif; ?>
					<?php if ( is_single() ) :
						while ( have_posts() ): the_post();
							get_template_part( 'templates/blog/blog', 'single' );
							/*If comments are open or we have at least one comment, load up the comment template.*/
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						endwhile;
						wp_reset_postdata();
					else:
						get_template_part( 'templates/blog/blog', 'standard' );
					endif; ?>
                </div>
				<?php if ( $blog_layout != "full" ): ?>
                    <div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
						<?php get_sidebar(); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php get_footer();
