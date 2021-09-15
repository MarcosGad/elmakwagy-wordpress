<?php

/*

Plugin Name: Woocommerce Single Product Page Customizer

Description: By using this smart plugin, allows you to add text or HTML in wooocommerce Single product page , no need to edit theme and woocommerce plugin!

Author: Geek Web Solution

Version: 1.3

WC tested up to: 4.2.2

Author URI: http://geekwebsolution.com/

*/

if( !defined( 'ABSPATH' ) ) exit;

require_once( plugin_dir_path (__FILE__) .'functions.php' );

$hook_list=array(
'woocommerce_before_single_product',
'woocommerce_before_single_product_summary',
'woocommerce_single_product_summary',
'woocommerce_before_add_to_cart_form',
'woocommerce_before_variations_form',
'woocommerce_before_add_to_cart_button',
'woocommerce_before_single_variation',
'woocommerce_single_variation',
'woocommerce_after_single_variation',
'woocommerce_after_add_to_cart_button',
'woocommerce_after_variations_form',
'woocommerce_after_add_to_cart_form',
'woocommerce_product_meta_start',
'woocommerce_product_meta_end',
'woocommerce_share',
'woocommerce_product_thumbnails',
'woocommerce_after_single_product_summary',
'woocommerce_after_single_product',
);
add_action('admin_menu', 'wsppc_admin_menu_single_product_page_customizer' );
add_action( 'admin_footer', 'wsppc_enqueue_styles_scripts' );
register_activation_hook( __FILE__, 'wsppc_plugin_active_single_product_page_customizert' );
function wsppc_plugin_active_single_product_page_customizert(){
	$error='required <b>woocommerce</b> plugin.';	
	if ( !class_exists( 'WooCommerce' ) ) {
	   die('Plugin NOT activated: ' . $error);
	}
}
function wsppc_admin_menu_single_product_page_customizer(){
	add_submenu_page( 'woocommerce','Single Product Page Customizer', 'Single Product Page Customizer', 'manage_options', 'wsppc-woocommerce-single-product-page-customizer', 'wsppc_single_product_page_setting');
}
function wsppc_single_product_page_setting(){
	if(!current_user_can('manage_options') ){
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	include( plugin_dir_path( __FILE__ ) . 'admin/options.php' );}
function wsppc_enqueue_styles_scripts()
{
    if( is_admin() ) {
        $css= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/assets/css/style.css";               
        wp_enqueue_style( 'main-wsppc-woocommerce-single-page-css', $css );
		$js= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/assets/js/main.js";               
        wp_enqueue_script( 'main-wsppc-woocommerce-single-page-js', $js );
		wp_localize_script( 'rml-script', 'wsppc_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
    }
}
add_action("wp_ajax_wsppc_get_edit_form", "wsppc_edit_form");
function wsppc_edit_form()
{
	if($_POST['form_action']=='add_form')
	{
		check_ajax_referer( 'wsppc_ajax_add_nonce', 'security' );
	}
	if($_POST['form_action']=='edit_form')
	{
		check_ajax_referer( 'wsppc_ajax_edit_nonce', 'security' );
	}
	$hook="";
	$hook_value="";
	if(isset($_POST['hook_name']))
	{
		$hook=sanitize_text_field($_POST['hook_name']);
		$hook_value=wsppc_get_hook_value($hook);
	}
	$all_hook=wsppc_get_hook();
	
	?>
	<form method="post" class="wsppc_form">
			<table class="form-table">
			<tbody>
				<tr valign="top">
				<?php if(empty($hook))
					{ ?>
					<th scope="row">
						<span><strong>Select Position</strong></span>
						<span>
							<select id="" name="hook">
								<?php global $hook_list; $i=1; foreach($hook_list as $hooks){
											$disable_key="";
											if(array_key_exists($hooks, $all_hook)){
												$disable_key = 'disabled="disabled"';
											}				
									?>
								<option <?php echo $disable_key ?> value="<?php echo $hooks ?>"><?php echo  $i.". ".str_replace("_"," ",$hooks); ?></option>
								<?php $i++;} ?>
							</select>
							<p class="description">Refere bellow position map.</p>
						</span>
					</th>
				<?php } else { ?>
				<input type="hidden" name="hook" value="<?php echo $hook; ?>">
				<input type="hidden" name="edit_form" value="1">
				<?php } ?>
				</tr>			
				<tr valign="top">				
					<td>
					<textarea name="content" id="content_<?php echo $hook;?>" rows="12" class="wsppc_content wp-editor"> <?php  echo wp_unslash($hook_value); ?></textarea>
					<p class="description">This content will be show on single product page as per choosen position.</p>
					</td>				
				</tr>
			</tbody>
			</table>
			<input type="hidden" name="single_page_wpnonce" value="<?php echo $nonce= wp_create_nonce('wsppc_single_page_wpnonce'); ?>">
			<input type="submit" class="button button-primary " name="update_option" value="Update">			
	</form>
	<?php
	die;
}
add_action("wp_ajax_wsppc_remove_hook", "wsppc_removed_hook");
function wsppc_removed_hook()
{	
	check_ajax_referer( 'wsppc_ajax_remove_nonce', 'security' );
	$hook='';
	$security='';
	if(isset($_POST['hook_name']))
	{
		$hook=sanitize_text_field($_POST['hook_name']);
	}
	if(isset($_POST['security']))
	{
		$security=$_POST['security'];
	}
		 $wsppc_hook=wsppc_get_hook();
		 unset($wsppc_hook[$hook]);
		 update_option('wsppc_hook',$wsppc_hook);
		 echo true;
	die;
}
$wsppc_hooks=wsppc_get_hook();
if(!empty($wsppc_hooks)){
	foreach($wsppc_hooks as $key => $wsppc_hook)
	{
		add_action( $key, 'wsppc_single_product_page_hook');
	}
}
function wsppc_single_product_page_hook($arg) {
	$hook = current_filter();
	$wsppc_hooks=wsppc_get_hook(); 
	echo wsppc_output($wsppc_hooks[$hook]);
	
}
?>