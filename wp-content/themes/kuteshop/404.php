<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Kuteshop
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
    <div class="main-container">
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
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <section class="error-404 not-found">
                        <h1 class="page-title">
							<?php echo esc_html__( 'Error', 'kuteshop' ); ?>
                            <span class="hightlight"><?php echo esc_html__( ' 404 ', 'kuteshop' ); ?></span>
							<?php echo esc_html__( 'Not Found', 'kuteshop' ); ?>
                        </h1>
                        <p class="page-content">
							<?php echo esc_html__( 'We&acute;re sorry but the page you are looking for does nor exist.', 'kuteshop' ); ?>
                            <br>
							<?php echo esc_html__( 'You could return to', 'kuteshop' ); ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                               class="hightlight"><?php echo esc_html__( ' Home page', 'kuteshop' ); ?></a>
							<?php echo esc_html__( ' or using', 'kuteshop' ); ?>
                            <span class="hightlight"><?php echo esc_html__( ' search!', 'kuteshop' ); ?></span>
                        </p><!-- .page-content -->
						<?php get_search_form(); ?>
                    </section><!-- .error-404 -->
                </main><!-- #main -->
            </div><!-- #primary -->
        </div>
    </div>
<?php get_footer();

