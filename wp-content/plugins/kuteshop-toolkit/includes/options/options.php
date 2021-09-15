<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

require_once dirname( __FILE__ ) . '/classes/setup.class.php';

$demo_mode = get_option( 'ovic_demo_mode', false );

if ( ! empty( $_GET['ovic-demo'] ) ) {
	$demo_mode = ( sanitize_text_field( $_GET['ovic-demo'] ) === 'activate' ) ? true : false;
	update_option( 'ovic_demo_mode', $demo_mode );
}
if ( $demo_mode == true ) {
	require_once dirname( __FILE__ ) . '/samples/options.sample.php';
	require_once dirname( __FILE__ ) . '/samples/customize.sample.php';
	require_once dirname( __FILE__ ) . '/samples/shortcode.sample.php';
	require_once dirname( __FILE__ ) . '/samples/metabox.sample.php';
	require_once dirname( __FILE__ ) . '/samples/taxonomy.sample.php';
}