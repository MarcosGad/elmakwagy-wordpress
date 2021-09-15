<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Kuteshop
 * @since 1.0
 * @version 1.0
 **/
kuteshop_get_footer();
if ( is_front_page() ) {
	get_template_part( 'templates-parts/popup', 'newsletter' );
}
?>
<a href="#" class="backtotop">
    <i class="pe-7s-angle-up"></i>
</a>
<div id="kuteshop-modal-popup" class="modal fade"></div>
<?php wp_footer(); ?>
</body>
</html>
