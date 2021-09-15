<?php
if ( !class_exists( 'Ovic_Back_Compat' ) ) {
	class Ovic_Back_Compat
	{
		public $updated    = '_ovic_database_updated';
		public $megamenu   = '_ovic_menu_settings';
		public $is_updated = false;

		public function __construct()
		{
			$this->is_updated = ( get_option( $this->updated ) == 'updated' ) ? true : false;

			add_action( 'wp_ajax_ovic_update_database', array( $this, 'update_database' ) );
			add_action( 'wp_ajax_nopriv_ovic_update_database', array( $this, 'update_database' ) );

			add_action( 'after_setup_theme', array( $this, 'theme_update' ) );
			add_action( 'switch_theme', array( $this, 'theme_update' ) );
		}

		public function theme_update()
		{
			/**
			 * Functions Update Theme.
			 */
			if ( class_exists( 'Puc_v4_Factory' ) ) {
				$Theme_Updater = Puc_v4_Factory::buildUpdateChecker(
					'https://github.com/kutethemes/kuteshop',
					KUTESHOP_FILE,
					'kuteshop'
				);
				$Theme_Updater->setAuthentication( '302295d86a3aca6bdf52780c5bc7daec4f89596a' );
			}
			/**
			 * Functions Update Menu.
			 */
			if ( $this->is_updated == false ) {
				$this->update_menu();
				update_option( $this->updated, 'updated' );
				update_option( '_ovic_vc_options',
					array(
						'advanced_options'  => 'no',
						'screen_responsive' => 'yes',
					)
				);
			}
		}

		public function move_post( $post_type, $post_to, $args = array() )
		{
			$defaults = array(
				'post_type'      => filter_var( $post_type, FILTER_SANITIZE_STRING ),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);

			$args  = wp_parse_args( $args, $defaults );
			$query = new WP_Query( $args );

			if ( !empty( $query->posts ) ) {
				foreach ( $query->posts as $posts ) {
					$post    = array(
						'ID'        => $posts->ID,
						'post_type' => filter_var( $post_to, FILTER_SANITIZE_STRING ),
					);
					$post_id = wp_update_post( $post );
				}
			}
		}

		public function copy_post( $post_type, $post_to, $args = array() )
		{
			$defaults = array(
				'post_type'      => filter_var( $post_type, FILTER_SANITIZE_STRING ),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);

			$args  = wp_parse_args( $args, $defaults );
			$query = new WP_Query( $args );

			if ( !empty( $query->posts ) ) {
				foreach ( $query->posts as $posts ) {
					$post_meta = get_post_meta( $posts->ID, $key = '', $single = false );
					unset( $post_meta['_edit_lock'] );
					unset( $post_meta['_edit_last'] );

					if ( get_bloginfo( 'version' ) >= '4.4.0' ) {
						$meta_info = array();
						foreach ( $post_meta as $key => $value ) {
							$meta_info[$key] = $value[0];
						}
						$post    = array(
							'post_title'     => $posts->post_title,
							'post_content'   => $posts->post_content,
							'post_excerpt'   => $posts->post_excerpt,
							'post_status'    => $posts->post_status,
							'post_author'    => get_current_user_id(),
							'comment_status' => $posts->comment_status,
							'post_type'      => filter_var( $post_to, FILTER_SANITIZE_STRING ),
							'meta_input'     => $meta_info,
						);
						$post_id = wp_insert_post( $post );
					} else {
						$post    = array(
							'post_title'     => $posts->post_title,
							'post_content'   => $posts->post_content,
							'post_excerpt'   => $posts->post_excerpt,
							'post_status'    => $posts->post_status,
							'post_author'    => get_current_user_id(),
							'comment_status' => $posts->comment_status,
							'post_type'      => filter_var( $post_to, FILTER_SANITIZE_STRING ),
						);
						$post_id = wp_insert_post( $post );

						foreach ( $post_meta as $key => $value ) {
							add_post_meta( $post_id, $key, $value[0] );
						}
					}
				}
			}
		}

		public function update_menu()
		{
			$menu_items = get_terms(
				'nav_menu',
				array(
					'hide_empty' => true,
				)
			);
			if ( !empty( $menu_items ) ) {
				foreach ( $menu_items as $menu_item ) {
					$menus = wp_get_nav_menu_items( $menu_item->term_id );
					if ( !empty( $menus ) ) {
						foreach ( $menus as $menu ) {
							$settings   = array();
							$font_icon  = get_post_meta( $menu->ID, '_menu_item_megamenu_font_icon', true );
							$img_icon   = get_post_meta( $menu->ID, '_menu_item_megamenu_img_icon', true );
							$icon_type  = get_post_meta( $menu->ID, '_menu_item_megamenu_item_icon_type', true );
							$menu_width = get_post_meta( $menu->ID, '_menu_item_megamenu_mega_menu_width', true );
							$menu_url   = get_post_meta( $menu->ID, '_menu_item_megamenu_mega_menu_url', true );

							if ( $menu->object == 'megamenu' ) {
								$settings['enable_mega']     = true;
								$settings['menu_width']      = $menu_width;
								$settings['menu_content_id'] = $menu->object_id;
								if ( !empty( $menu_url ) ) {
									update_post_meta( $menu->ID, '_menu_item_url', $menu_url );
								}
							}
							if ( $icon_type == 'image' ) {
								if ( !empty( $img_icon ) ) {
									$settings['icon_image'] = $img_icon;
								}
							}
							if ( $icon_type == 'fonticon' ) {
								if ( !empty( $font_icon ) ) {
									$settings['menu_icon'] = $font_icon;
								}
							}
							update_post_meta( $menu->ID, $this->megamenu, $settings );
						}
					}
				}
			}
		}

		public function change_shortcode()
		{
			$args  = array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
			$query = new WP_Query( $args );

			foreach ( $query->posts as $posts ) {
				$find         = array();
				$replace      = array();
				$post_content = str_replace( $find, $replace, $posts->post_content );
				$post         = array(
					'ID'           => $posts->ID,
					'post_content' => $post_content,
				);
				$post_id      = wp_update_post( $post );
			}
		}

		public function update_database()
		{
			/*
			 * Move Content Post Type
			 * */
			$post_types = array(
				'megamenu' => 'ovic_menu',
				'footer'   => 'ovic_footer',
			);
			if ( !$this->is_updated ) {
				$this->update_menu();

				update_option( $this->updated, 'updated' );
			}

			wp_die();
		}
	}

	new Ovic_Back_Compat();
}