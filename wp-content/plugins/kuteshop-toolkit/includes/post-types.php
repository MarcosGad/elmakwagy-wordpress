<?php
/**
 * @version    1.0
 * @package    Kuteshop_Toolkit
 * @author     KuteThemes
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if ( !class_exists( 'Kuteshop_Post_Type' ) ) {
	class Kuteshop_Post_Type
	{
		public function __construct()
		{
			add_action( 'init', array( &$this, 'init' ), 9999 );
		}

		public static function init()
		{
			/*Mega menu */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Mega Builder', 'kuteshop-toolkit' ),
					'singular_name'      => __( 'Mega menu item', 'kuteshop-toolkit' ),
					'add_new'            => __( 'Add new', 'kuteshop-toolkit' ),
					'add_new_item'       => __( 'Add new menu item', 'kuteshop-toolkit' ),
					'edit_item'          => __( 'Edit menu item', 'kuteshop-toolkit' ),
					'new_item'           => __( 'New menu item', 'kuteshop-toolkit' ),
					'view_item'          => __( 'View menu item', 'kuteshop-toolkit' ),
					'search_items'       => __( 'Search menu items', 'kuteshop-toolkit' ),
					'not_found'          => __( 'No menu items found', 'kuteshop-toolkit' ),
					'not_found_in_trash' => __( 'No menu items found in trash', 'kuteshop-toolkit' ),
					'parent_item_colon'  => __( 'Parent menu item:', 'kuteshop-toolkit' ),
					'menu_name'          => __( 'Menu Builder', 'kuteshop-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'Mega Menus.', 'kuteshop-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'kuteshop_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_rest'        => true,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'megamenu', $args );
			/* Footer */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Footers', 'kuteshop-toolkit' ),
					'singular_name'      => __( 'Footers', 'kuteshop-toolkit' ),
					'add_new'            => __( 'Add New', 'kuteshop-toolkit' ),
					'add_new_item'       => __( 'Add new footer', 'kuteshop-toolkit' ),
					'edit_item'          => __( 'Edit footer', 'kuteshop-toolkit' ),
					'new_item'           => __( 'New footer', 'kuteshop-toolkit' ),
					'view_item'          => __( 'View footer', 'kuteshop-toolkit' ),
					'search_items'       => __( 'Search template footer', 'kuteshop-toolkit' ),
					'not_found'          => __( 'No template items found', 'kuteshop-toolkit' ),
					'not_found_in_trash' => __( 'No template items found in trash', 'kuteshop-toolkit' ),
					'parent_item_colon'  => __( 'Parent template item:', 'kuteshop-toolkit' ),
					'menu_name'          => __( 'Footer Builder', 'kuteshop-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Footer.', 'kuteshop-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'kuteshop_menu',
				'menu_position'       => 4,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'show_in_rest'        => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'footer', $args );
			/* Testimonials */
			$labels = array(
				'name'               => _x( 'Testimonials', 'kuteshop-toolkit' ),
				'singular_name'      => _x( 'Testimonial', 'kuteshop-toolkit' ),
				'add_new'            => __( 'Add New', 'kuteshop-toolkit' ),
				'all_items'          => __( 'Testimonials', 'kuteshop-toolkit' ),
				'add_new_item'       => __( 'Add New Testimonial', 'kuteshop-toolkit' ),
				'edit_item'          => __( 'Edit Testimonial', 'kuteshop-toolkit' ),
				'new_item'           => __( 'New Testimonial', 'kuteshop-toolkit' ),
				'view_item'          => __( 'View Testimonial', 'kuteshop-toolkit' ),
				'search_items'       => __( 'Search Testimonial', 'kuteshop-toolkit' ),
				'not_found'          => __( 'No Testimonial found', 'kuteshop-toolkit' ),
				'not_found_in_trash' => __( 'No Testimonial found in Trash', 'kuteshop-toolkit' ),
				'parent_item_colon'  => __( 'Parent Testimonial', 'kuteshop-toolkit' ),
				'menu_name'          => __( 'Testimonials', 'kuteshop-toolkit' ),
			);
			$args   = array(
				'labels'              => $labels,
				'description'         => 'Post type Testimonial',
				'supports'            => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
				),
				'hierarchical'        => false,
				'rewrite'             => true,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'kuteshop_menu',
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 4,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'show_in_rest'        => true,
				'capability_type'     => 'post',
				'menu_icon'           => 'dashicons-editor-quote',
			);
			register_post_type( 'testimonial', $args );
		}
	}

	new Kuteshop_Post_Type();
}
