<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

if ( !class_exists( 'Kuteshop_Theme_Option' ) && class_exists( 'OVIC_Options' ) ) {
	class Kuteshop_Theme_Option
	{
		public function __construct()
		{
			add_action( 'init', array( $this, 'install' ) );
		}

		public function install()
		{
			$this->install_theme_options();
			$this->install_metabox_options();
			$this->install_taxonomy_options();
		}

		public function get_header_options()
		{
			$layoutDir      = get_template_directory() . '/templates/header/';
			$header_options = array();
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                  = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                  = str_replace( 'header-', '', $fileInfo['filename'] );
								$header_options[$file_name] = array(
									'title'   => $file_data['Name'],
									'preview' => get_theme_file_uri( '/templates/header/header-' . $file_name . '.jpg' ),
								);
							}
						}
					}
				}
			}

			return $header_options;
		}

		public function get_product_options()
		{
			$layoutDir       = get_template_directory() . '/woocommerce/product-styles/';
			$product_options = array();
			$match           = array( 4, 11, 12, 13 );
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name = str_replace( 'content-product-style-', '', $fileInfo['filename'] );
								if ( !in_array( $file_name, $match ) ) {
									$product_options[$file_name] = array(
										'title'   => $file_data['Name'],
										'preview' => get_theme_file_uri( 'woocommerce/product-styles/content-product-style-' . $file_name . '.jpg' ),
									);
								}
							}
						}
					}
				}
			}

			return $product_options;
		}

		public function get_social_options()
		{
			$socials     = array();
			$all_socials = kuteshop_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[$key] = $social['title_social'];
				}
			}

			return $socials;
		}

		public function get_footer_options()
		{
			$layoutDir      = get_template_directory() . '/templates/footer/';
			$footer_options = array();
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                  = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                  = str_replace( 'footer-', '', $fileInfo['filename'] );
								$footer_options[$file_name] = array(
									'title'   => $file_data['Name'],
									'preview' => get_theme_file_uri( '/templates/footer/footer-' . $file_name . '.jpg' ),
								);
							}
						}
					}
				}
			}

			return $footer_options;
		}

		public function get_footer_preview()
		{
			$footer_preview = array();
			$args           = array(
				'post_type'      => 'footer',
				'posts_per_page' => -1,
				'orderby'        => 'ASC',
			);
			$loop           = get_posts( $args );
			foreach ( $loop as $value ) {
				setup_postdata( $value );
				$data_meta                  = get_post_meta( $value->ID, '_custom_footer_options', true );
				$template_style             = isset( $data_meta['kuteshop_footer_style'] ) ? $data_meta['kuteshop_footer_style'] : 'default';
				$footer_preview[$value->ID] = array(
					'title'   => $value->post_title,
					'preview' => get_theme_file_uri( '/templates/footer/footer-' . $template_style . '.jpg' ),
				);
			}

			return $footer_preview;
		}

		public function install_theme_options()
		{
			$options   = array();
			$options[] = array(
				'name'     => 'general',
				'title'    => esc_html__( 'General', 'kuteshop' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'main_settings',
						'title'  => esc_html__( 'Main Settings', 'kuteshop' ),
						'fields' => array(
							array(
								'id'    => 'kuteshop_logo',
								'type'  => 'image',
								'title' => esc_html__( 'Logo', 'kuteshop' ),
							),
							array(
								'id'      => 'kuteshop_main_color',
								'type'    => 'color',
								'title'   => esc_html__( 'Main Color', 'kuteshop' ),
								'default' => '#ff3366',
								'rgba'    => true,
							),
							array(
								'id'    => 'gmap_api_key',
								'type'  => 'text',
								'title' => esc_html__( 'Google Map API Key', 'kuteshop' ),
								'desc'  => esc_html__( 'Enter your Google Map API key. ', 'kuteshop' ) . '<a href="' . esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key' ) . '" target="_blank">' . esc_html__( 'How to get?', 'kuteshop' ) . '</a>',
							),
							array(
								'id'      => 'enable_theme_options',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Meta Box Options', 'kuteshop' ),
								'default' => true,
								'desc'    => esc_html__( 'Enable for using Themes setting each single page.', 'kuteshop' ),
							),
							array(
								'id'    => 'kuteshop_theme_lazy_load',
								'type'  => 'switcher',
								'title' => esc_html__( 'Use image Lazy Load', 'kuteshop' ),
							),
						),
					),
					array(
						'name'   => 'popup_settings',
						'title'  => esc_html__( 'Newsletter Settings', 'kuteshop' ),
						'fields' => array(
							array(
								'id'      => 'kuteshop_enable_popup',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Popup Newsletter', 'kuteshop' ),
								'default' => false,
							),
							array(
								'id'         => 'kuteshop_poppup_background',
								'type'       => 'image',
								'title'      => esc_html__( 'Popup Background', 'kuteshop' ),
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
							array(
								'id'         => 'kuteshop_popup_title',
								'type'       => 'text',
								'title'      => esc_html__( 'Title', 'kuteshop' ),
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
							array(
								'id'         => 'kuteshop_popup_highlight',
								'type'       => 'textarea',
								'title'      => esc_html__( 'Highlight', 'kuteshop' ),
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
							array(
								'id'         => 'kuteshop_popup_desc',
								'type'       => 'text',
								'title'      => esc_html__( 'Description', 'kuteshop' ),
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
							array(
								'id'         => 'kuteshop_popup_input_placeholder',
								'type'       => 'text',
								'title'      => esc_html__( 'Input placeholder text', 'kuteshop' ),
								'default'    => esc_html__( 'Enter your email...', 'kuteshop' ),
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
							array(
								'id'         => 'kuteshop_popup_delay_time',
								'type'       => 'spinner',
								'unit'       => 'millisecond',
								'title'      => esc_html__( 'Delay time', 'kuteshop' ),
								'default'    => '0',
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
							array(
								'id'         => 'kuteshop_enable_popup_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Poppup on Mobile', 'kuteshop' ),
								'default'    => false,
								'dependency' => array( 'kuteshop_enable_popup', '==', true ),
							),
						),
					),
					array(
						'name'   => 'widget_settings',
						'title'  => esc_html__( 'Widget Settings', 'kuteshop' ),
						'fields' => array(
							array(
								'id'              => 'multi_widget',
								'type'            => 'group',
								'title'           => esc_html__( 'Multi Widget', 'kuteshop' ),
								'button_title'    => esc_html__( 'Add Widget', 'kuteshop' ),
								'accordion_title' => esc_html__( 'Add New Field', 'kuteshop' ),
								'fields'          => array(
									array(
										'id'    => 'add_widget',
										'type'  => 'text',
										'title' => esc_html__( 'Name Widget', 'kuteshop' ),
									),
								),
							),
						),
					),
					array(
						'name'   => 'ace_settings',
						'title'  => esc_html__( 'ACE Settings', 'kuteshop' ),
						'fields' => array(
							array(
								'id'       => 'kuteshop_custom_css',
								'type'     => 'code_editor',
								'settings' => array(
									'theme' => 'dracula',
									'mode'  => 'css',
								),
								'title'    => esc_html__( 'Editor Style', 'kuteshop' ),
							),
							array(
								'id'       => 'kuteshop_custom_js',
								'type'     => 'code_editor',
								'settings' => array(
									'theme' => 'dracula',
									'mode'  => 'javascript',
								),
								'title'    => esc_html__( 'Editor Javascript', 'kuteshop' ),
							),
						),
					),
				),
			);
			$options[] = array(
				'name'     => 'header',
				'title'    => esc_html__( 'Header Settings', 'kuteshop' ),
				'icon'     => 'fa fa-folder-open-o',
				'sections' => array(
					array(
						'name'   => 'main_header',
						'title'  => esc_html__( 'Header Settings', 'kuteshop' ),
						'fields' => array(
							array(
								'id'    => 'kuteshop_enable_sticky_menu',
								'type'  => 'switcher',
								'title' => esc_html__( 'Main Menu Sticky', 'kuteshop' ),
							),
							array(
								'id'         => 'kuteshop_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'kuteshop' ),
								'desc'       => esc_html__( 'Select a header layout', 'kuteshop' ),
								'options'    => self::get_header_options(),
								'default'    => 'style-01',
								'attributes' => array(
									'data-depend-id' => 'kuteshop_used_header',
								),
							),
							array(
								'id'         => 'header_text_box',
								'type'       => 'text',
								'title'      => esc_html__( 'Header Text Box', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_used_header', '==', 'style-03',
								),
								'multilang'  => true,
							),
							array(
								'id'              => 'header_service_box',
								'type'            => 'group',
								'title'           => esc_html__( 'Header Service', 'kuteshop' ),
								'button_title'    => esc_html__( 'Add New', 'kuteshop' ),
								'accordion_title' => esc_html__( 'Header Service Settings', 'kuteshop' ),
								'dependency'      => array(
									'kuteshop_used_header', '==', 'style-07',
								),
								'fields'          => array(
									array(
										'id'    => 'service_box_image',
										'type'  => 'image',
										'title' => esc_html__( 'Image', 'kuteshop' ),
									),
									array(
										'id'        => 'service_box_text',
										'type'      => 'text',
										'title'     => esc_html__( 'Text', 'kuteshop' ),
										'multilang' => true,
									),
								),
							),
							array(
								'id'         => 'header_phone',
								'type'       => 'text',
								'title'      => esc_html__( 'Header Phone Number', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_used_header', '==', 'style-11',
								),
							),
							array(
								'id'         => 'header_banner',
								'type'       => 'image',
								'title'      => esc_html__( 'Banner', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_used_header', 'any', 'style-01,style-13',
								),
							),
							array(
								'id'         => 'header_banner_url',
								'type'       => 'text',
								'title'      => esc_html__( 'Banner Url', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_used_header', 'any', 'style-01,style-13',
								),
							),
							array(
								'id'         => 'header_social',
								'type'       => 'select',
								'title'      => esc_html__( 'Select Social', 'kuteshop' ),
								'options'    => self::get_social_options(),
								'attributes' => array(
									'style' => 'width: 100%;',
								),
								'multiple'   => true,
								'chosen'     => true,
							),
						),
					),
					array(
						'name'   => 'vertical_menu',
						'title'  => esc_html__( 'Vertical Menu Settings', 'kuteshop' ),
						'fields' => array(
							array(
								'id'         => 'enable_vertical_menu',
								'type'       => 'switcher',
								'attributes' => array(
									'data-depend-id' => 'enable_vertical_menu',
								),
								'title'      => esc_html__( 'Vertical Menu', 'kuteshop' ),
								'default'    => false,
							),
							array(
								'id'         => 'block_vertical_menu',
								'type'       => 'select',
								'title'      => esc_html__( 'Vertical Menu Always Open', 'kuteshop' ),
								'options'    => 'page',
								'attributes' => array(
									'placeholder' => 'Select a page',
									'style'       => 'width:100%',
								),
								'multiple'   => true,
								'chosen'     => true,
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
								'desc'       => esc_html__( 'Vertical menu will be always open', 'kuteshop' ),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Title', 'kuteshop' ),
								'id'         => 'vertical_menu_title',
								'type'       => 'text',
								'default'    => esc_html__( 'CATEGORIES', 'kuteshop' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Button show all text', 'kuteshop' ),
								'id'         => 'vertical_menu_button_all_text',
								'type'       => 'text',
								'default'    => esc_html__( 'All Categories', 'kuteshop' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Button close text', 'kuteshop' ),
								'id'         => 'vertical_menu_button_close_text',
								'type'       => 'text',
								'default'    => esc_html__( 'Close', 'kuteshop' ),
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
							),
							array(
								'title'      => esc_html__( 'The number of visible vertical menu items', 'kuteshop' ),
								'desc'       => esc_html__( 'The number of visible vertical menu items', 'kuteshop' ),
								'id'         => 'vertical_item_visible',
								'default'    => 10,
								'type'       => 'spinner',
								'unit'       => 'items',
								'dependency' => array(
									'enable_vertical_menu', '==', true,
								),
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'footer',
				'title'  => esc_html__( 'Footer Settings', 'kuteshop' ),
				'icon'   => 'fa fa-folder-open-o',
				'fields' => array(
					array(
						'id'      => 'kuteshop_footer_options',
						'type'    => 'select_preview',
						'title'   => esc_html__( 'Select Footer Builder', 'kuteshop' ),
						'options' => self::get_footer_preview(),
						'default' => 'default',
					),
				),
			);
			$options[] = array(
				'name'     => 'blog',
				'title'    => esc_html__( 'Blog Settings', 'kuteshop' ),
				'icon'     => 'fa fa-rss',
				'sections' => array(
					array(
						'name'   => 'blog_page',
						'title'  => esc_html__( 'Blog Page', 'kuteshop' ),
						'fields' => array(
							array(
								'id'      => 'sidebar_blog_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'kuteshop' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'kuteshop' ),
								'options' => array(
									'left'  => get_theme_file_uri( 'framework/assets/images/left-sidebar.png' ),
									'right' => get_theme_file_uri( 'framework/assets/images/right-sidebar.png' ),
									'full'  => get_theme_file_uri( 'framework/assets/images/default-sidebar.png' ),
								),
								'default' => 'left',
							),
							array(
								'id'         => 'blog_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Blog Sidebar', 'kuteshop' ),
								'options'    => 'sidebar',
								'dependency' => array( 'sidebar_blog_layout', '!=', 'full' ),
							),
							array(
								'id'    => 'blog_full_content',
								'type'  => 'switcher',
								'title' => esc_html__( 'Show Full Content', 'kuteshop' ),
							),
							array(
								'id'         => 'using_placeholder',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Using Placeholder', 'kuteshop' ),
								'dependency' => array( 'sidebar_blog_layout', '!=', 'full' ),
							),
						),
					),
					array(
						'name'   => 'single_post',
						'title'  => esc_html__( 'Single Post', 'kuteshop' ),
						'fields' => array(
							array(
								'id'      => 'sidebar_single_post_position',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'kuteshop' ),
								'desc'    => esc_html__( 'Select sidebar position on Single Post.', 'kuteshop' ),
								'options' => array(
									'left'  => get_theme_file_uri( 'framework/assets/images/left-sidebar.png' ),
									'right' => get_theme_file_uri( 'framework/assets/images/right-sidebar.png' ),
									'full'  => get_theme_file_uri( 'framework/assets/images/default-sidebar.png' ),
								),
								'default' => 'left',
							),
							array(
								'id'         => 'single_post_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Single Post Sidebar', 'kuteshop' ),
								'options'    => 'sidebar',
								'dependency' => array( 'sidebar_single_post_position', '!=', 'full' ),
							),
							array(
								'id'      => 'kuteshop_single_related',
								'type'    => 'switcher',
								'default' => false,
								'title'   => esc_html__( 'Enable Related', 'kuteshop' ),
							),
							array(
								'title'      => esc_html__( 'Related items per row on Desktop', 'kuteshop' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'kuteshop' ),
								'id'         => 'related_ls_items',
								'type'       => 'select',
								'default'    => '3',
								'options'    => array(
									'1' => esc_html__( '1 item', 'kuteshop' ),
									'2' => esc_html__( '2 items', 'kuteshop' ),
									'3' => esc_html__( '3 items', 'kuteshop' ),
									'4' => esc_html__( '4 items', 'kuteshop' ),
									'5' => esc_html__( '5 items', 'kuteshop' ),
									'6' => esc_html__( '6 items', 'kuteshop' ),
								),
								'dependency' => array( 'kuteshop_single_related', '==', true ),
							),
							array(
								'title'      => esc_html__( 'Related items per row on Desktop', 'kuteshop' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'kuteshop' ),
								'id'         => 'related_lg_items',
								'type'       => 'select',
								'default'    => '3',
								'options'    => array(
									'1' => esc_html__( '1 item', 'kuteshop' ),
									'2' => esc_html__( '2 items', 'kuteshop' ),
									'3' => esc_html__( '3 items', 'kuteshop' ),
									'4' => esc_html__( '4 items', 'kuteshop' ),
									'5' => esc_html__( '5 items', 'kuteshop' ),
									'6' => esc_html__( '6 items', 'kuteshop' ),
								),
								'dependency' => array( 'kuteshop_single_related', '==', true ),
							),
							array(
								'title'      => esc_html__( 'Related items per row on landscape tablet', 'kuteshop' ),
								'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'kuteshop' ),
								'id'         => 'related_md_items',
								'type'       => 'select',
								'default'    => '3',
								'options'    => array(
									'1' => esc_html__( '1 item', 'kuteshop' ),
									'2' => esc_html__( '2 items', 'kuteshop' ),
									'3' => esc_html__( '3 items', 'kuteshop' ),
									'4' => esc_html__( '4 items', 'kuteshop' ),
									'5' => esc_html__( '5 items', 'kuteshop' ),
									'6' => esc_html__( '6 items', 'kuteshop' ),
								),
								'dependency' => array( 'kuteshop_single_related', '==', true ),
							),
							array(
								'title'      => esc_html__( 'Related items per row on portrait tablet', 'kuteshop' ),
								'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'kuteshop' ),
								'id'         => 'related_sm_items',
								'type'       => 'select',
								'default'    => '2',
								'options'    => array(
									'1' => esc_html__( '1 item', 'kuteshop' ),
									'2' => esc_html__( '2 items', 'kuteshop' ),
									'3' => esc_html__( '3 items', 'kuteshop' ),
									'4' => esc_html__( '4 items', 'kuteshop' ),
									'5' => esc_html__( '5 items', 'kuteshop' ),
									'6' => esc_html__( '6 items', 'kuteshop' ),
								),
								'dependency' => array( 'kuteshop_single_related', '==', true ),
							),
							array(
								'title'      => esc_html__( 'Related items per row on Mobile', 'kuteshop' ),
								'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'kuteshop' ),
								'id'         => 'related_xs_items',
								'type'       => 'select',
								'default'    => '1',
								'options'    => array(
									'1' => esc_html__( '1 item', 'kuteshop' ),
									'2' => esc_html__( '2 items', 'kuteshop' ),
									'3' => esc_html__( '3 items', 'kuteshop' ),
									'4' => esc_html__( '4 items', 'kuteshop' ),
									'5' => esc_html__( '5 items', 'kuteshop' ),
									'6' => esc_html__( '6 items', 'kuteshop' ),
								),
								'dependency' => array( 'kuteshop_single_related', '==', true ),
							),
							array(
								'title'      => esc_html__( 'Related items per row on Mobile', 'kuteshop' ),
								'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'kuteshop' ),
								'id'         => 'related_ts_items',
								'type'       => 'select',
								'default'    => '1',
								'options'    => array(
									'1' => esc_html__( '1 item', 'kuteshop' ),
									'2' => esc_html__( '2 items', 'kuteshop' ),
									'3' => esc_html__( '3 items', 'kuteshop' ),
									'4' => esc_html__( '4 items', 'kuteshop' ),
									'5' => esc_html__( '5 items', 'kuteshop' ),
									'6' => esc_html__( '6 items', 'kuteshop' ),
								),
								'dependency' => array( 'kuteshop_single_related', '==', true ),
							),
						),
					),
				),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$options[] = array(
					'name'     => 'wooCommerce',
					'title'    => esc_html__( 'WooCommerce', 'kuteshop' ),
					'icon'     => 'fa fa-shopping-bag',
					'sections' => array(
						array(
							'name'   => 'shop_product',
							'title'  => esc_html__( 'Shop Settings', 'kuteshop' ),
							'fields' => array(
								array(
									'id'      => 'enable_shop_banner',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Banner', 'kuteshop' ),
									'default' => false,
								),
								array(
									'id'         => 'woo_shop_banner',
									'type'       => 'gallery',
									'title'      => esc_html__( 'Shop Banner', 'kuteshop' ),
									'add_title'  => esc_html__( 'Add Banner', 'kuteshop' ),
									'dependency' => array( 'enable_shop_banner', '==', true ),
								),
                                array(
                                    'id'              => 'woo_shop_banner_link',
                                    'type'            => 'group',
                                    'title'           => esc_html__( 'Shop Banner Link', 'kuteshop' ),
                                    'button_title'    => esc_html__( 'Add New', 'kuteshop' ),
                                    'fields'          => array(
                                        array(
                                            'id'        => 'woo_shop_banner_link_url',
                                            'type'      => 'text',
                                            'title'     => esc_html__( 'Url', 'kuteshop' ),
                                        ),
                                    ),
                                ),
								array(
									'id'    => 'placeholder_rate',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Placeholder Rate', 'kuteshop' ),
								),
								array(
									'id'         => 'kuteshop_shop_product_style',
									'type'       => 'select_preview',
									'title'      => esc_html__( 'Product Shop Layout', 'kuteshop' ),
									'desc'       => esc_html__( 'Select a Product layout in shop page', 'kuteshop' ),
									'options'    => self::get_product_options(),
									'default'    => '1',
									'attributes' => array(
										'data-depend-id' => 'kuteshop_shop_product_style',
									),
								),
								array(
									'id'      => 'product_newness',
									'type'    => 'spinner',
									'unit'    => 'days',
									'title'   => esc_html__( 'Products Newness', 'kuteshop' ),
									'default' => '10',
								),
								array(
									'id'      => 'sidebar_shop_page_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Shop Page Sidebar Position', 'kuteshop' ),
									'desc'    => esc_html__( 'Select sidebar position on Shop Page.', 'kuteshop' ),
									'options' => array(
										'left'  => get_theme_file_uri( '/framework/assets/images/left-sidebar.png' ),
										'right' => get_theme_file_uri( '/framework/assets/images/right-sidebar.png' ),
										'full'  => get_theme_file_uri( '/framework/assets/images/default-sidebar.png' ),
									),
									'default' => 'left',
								),
								array(
									'id'         => 'shop_page_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Shop Sidebar', 'kuteshop' ),
									'options'    => 'sidebar',
									'dependency' => array( 'sidebar_shop_page_position', '!=', 'full' ),
								),
								array(
									'id'      => 'shop_page_layout',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Shop Default Layout', 'kuteshop' ),
									'desc'    => esc_html__( 'Select default layout for shop, product category archive.', 'kuteshop' ),
									'options' => array(
										'grid' => get_template_directory_uri() . '/assets/images/grid-display.png',
										'list' => get_template_directory_uri() . '/assets/images/list-display.png',
									),
									'default' => 'grid',
								),
								array(
									'id'      => 'product_per_page',
									'type'    => 'spinner',
									'unit'    => 'items',
									'title'   => esc_html__( 'Products perpage', 'kuteshop' ),
									'desc'    => esc_html__( 'Number of products on shop page.', 'kuteshop' ),
									'default' => '10',
								),
							),
						),
						array(
							'name'   => 'shop_grid',
							'title'  => esc_html__( 'Shop Grid', 'kuteshop' ),
							'fields' => array(
								array(
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_bg_items',
									'type'    => 'button_set',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'kuteshop' ),
										'6'  => esc_html__( '2 items', 'kuteshop' ),
										'4'  => esc_html__( '3 items', 'kuteshop' ),
										'3'  => esc_html__( '4 items', 'kuteshop' ),
										'15' => esc_html__( '5 items', 'kuteshop' ),
										'2'  => esc_html__( '6 items', 'kuteshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_lg_items',
									'type'    => 'button_set',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'kuteshop' ),
										'6'  => esc_html__( '2 items', 'kuteshop' ),
										'4'  => esc_html__( '3 items', 'kuteshop' ),
										'3'  => esc_html__( '4 items', 'kuteshop' ),
										'15' => esc_html__( '5 items', 'kuteshop' ),
										'2'  => esc_html__( '6 items', 'kuteshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_md_items',
									'type'    => 'button_set',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'kuteshop' ),
										'6'  => esc_html__( '2 items', 'kuteshop' ),
										'4'  => esc_html__( '3 items', 'kuteshop' ),
										'3'  => esc_html__( '4 items', 'kuteshop' ),
										'15' => esc_html__( '5 items', 'kuteshop' ),
										'2'  => esc_html__( '6 items', 'kuteshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_sm_items',
									'type'    => 'button_set',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'kuteshop' ),
										'6'  => esc_html__( '2 items', 'kuteshop' ),
										'4'  => esc_html__( '3 items', 'kuteshop' ),
										'3'  => esc_html__( '4 items', 'kuteshop' ),
										'15' => esc_html__( '5 items', 'kuteshop' ),
										'2'  => esc_html__( '6 items', 'kuteshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Items per row on Mobile( For grid mode )', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_xs_items',
									'type'    => 'button_set',
									'default' => '6',
									'options' => array(
										'12' => esc_html__( '1 item', 'kuteshop' ),
										'6'  => esc_html__( '2 items', 'kuteshop' ),
										'4'  => esc_html__( '3 items', 'kuteshop' ),
										'3'  => esc_html__( '4 items', 'kuteshop' ),
										'15' => esc_html__( '5 items', 'kuteshop' ),
										'2'  => esc_html__( '6 items', 'kuteshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Items per row on Mobile( For grid mode )', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_ts_items',
									'type'    => 'button_set',
									'default' => '12',
									'options' => array(
										'12' => esc_html__( '1 item', 'kuteshop' ),
										'6'  => esc_html__( '2 items', 'kuteshop' ),
										'4'  => esc_html__( '3 items', 'kuteshop' ),
										'3'  => esc_html__( '4 items', 'kuteshop' ),
										'15' => esc_html__( '5 items', 'kuteshop' ),
										'2'  => esc_html__( '6 items', 'kuteshop' ),
									),
								),
							),
						),
						array(
							'name'   => 'single_product',
							'title'  => esc_html__( 'Single product', 'kuteshop' ),
							'fields' => array(
								array(
									'id'         => 'sidebar_product_position',
									'type'       => 'image_select',
									'title'      => esc_html__( 'Single Product Sidebar Position', 'kuteshop' ),
									'desc'       => esc_html__( 'Select sidebar position on single product page.', 'kuteshop' ),
									'options'    => array(
										'left'  => get_theme_file_uri( 'framework/assets/images/left-sidebar.png' ),
										'right' => get_theme_file_uri( 'framework/assets/images/right-sidebar.png' ),
										'full'  => get_theme_file_uri( 'framework/assets/images/default-sidebar.png' ),
									),
									'default'    => 'left',
									'attributes' => array(
										'data-depend-id' => 'sidebar_product_position',
									),
								),
								array(
									'id'         => 'single_product_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Single Product Sidebar', 'kuteshop' ),
									'options'    => 'sidebar',
									'dependency' => array( 'sidebar_product_position', '!=', 'full' ),
								),
								array(
									'id'    => 'enable_share_product',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Product Share', 'kuteshop' ),
								),
								array(
									'id'    => 'enable_ajax_product',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Ajax Add To Cart', 'kuteshop' ),
								),
							),
						),
						array(
							'name'   => 'cross_sell',
							'title'  => esc_html__( 'Cross sell products', 'kuteshop' ),
							'fields' => array(
								array(
									'id'      => 'woo_crosssell_enable',
									'type'    => 'button_set',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'kuteshop' ),
										'disable' => esc_html__( 'Disable', 'kuteshop' ),
									),
									'title'   => esc_html__( 'Enable Cross Sell Products', 'kuteshop' ),
								),
								array(
									'title'   => esc_html__( 'Cross sell title', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_products_title',
									'type'    => 'text',
									'default' => esc_html__( 'You may be interested in...', 'kuteshop' ),
									'desc'    => esc_html__( 'Cross sell title', 'kuteshop' ),
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Desktop', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_ls_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Desktop', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_lg_items',
									'default' => 3,
									'type'    => 'slider',
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on landscape tablet', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_md_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on portrait tablet', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_sm_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Mobile', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_xs_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Mobile', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_crosssell_ts_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
							),
						),
						array(
							'name'   => 'related_product',
							'title'  => esc_html__( 'Related Products', 'kuteshop' ),
							'fields' => array(
								array(
									'id'      => 'woo_related_enable',
									'type'    => 'button_set',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'kuteshop' ),
										'disable' => esc_html__( 'Disable', 'kuteshop' ),
									),
									'title'   => esc_html__( 'Enable Related Products', 'kuteshop' ),
								),
								array(
									'title'   => esc_html__( 'Related products title', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_products_title',
									'type'    => 'text',
									'default' => esc_html__( 'Related Products', 'kuteshop' ),
									'desc'    => esc_html__( 'Related products title', 'kuteshop' ),
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Desktop', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_ls_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Desktop', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_lg_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Related products items per row on landscape tablet', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_md_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Related product items per row on portrait tablet', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_sm_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Mobile', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_xs_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Mobile', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_related_ts_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
							),
						),
						array(
							'name'   => 'upsells_product',
							'title'  => esc_html__( 'Up sells Products', 'kuteshop' ),
							'fields' => array(
								array(
									'id'      => 'woo_upsells_enable',
									'type'    => 'button_set',
									'default' => 'enable',
									'options' => array(
										'enable'  => esc_html__( 'Enable', 'kuteshop' ),
										'disable' => esc_html__( 'Disable', 'kuteshop' ),
									),
									'title'   => esc_html__( 'Enable Up sells Products', 'kuteshop' ),
								),
								array(
									'title'   => esc_html__( 'Up sells title', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_products_title',
									'type'    => 'text',
									'default' => esc_html__( 'You may also like&hellip;', 'kuteshop' ),
									'desc'    => esc_html__( 'Up sells products title', 'kuteshop' ),
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Desktop', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_ls_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Desktop', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_lg_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on landscape tablet', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_md_items',
									'type'    => 'slider',
									'default' => 3,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on portrait tablet', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_sm_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Mobile', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_xs_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Mobile', 'kuteshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'kuteshop' ),
									'id'      => 'kuteshop_woo_upsell_ts_items',
									'type'    => 'slider',
									'default' => 2,
									'min'     => 1,
									'max'     => 6,
									'unit'    => 'items',
								),
							),
						),
					),
				);
			}
			$options[]             = array(
				'name'   => 'social_settings',
				'title'  => esc_html__( 'Social Settings', 'kuteshop' ),
				'icon'   => 'fa fa-users',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Social User', 'kuteshop' ),
					),
					array(
						'id'              => 'user_all_social',
						'type'            => 'group',
						'title'           => esc_html__( 'Social', 'kuteshop' ),
						'button_title'    => esc_html__( 'Add New Social', 'kuteshop' ),
						'accordion_title' => esc_html__( 'Social Settings', 'kuteshop' ),
						'fields'          => array(
							array(
								'id'      => 'title_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Title Social', 'kuteshop' ),
								'default' => 'Facebook',
							),
							array(
								'id'      => 'link_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Link Social', 'kuteshop' ),
								'default' => 'https://facebook.com',
							),
							array(
								'id'      => 'icon_social',
								'type'    => 'icon',
								'title'   => esc_html__( 'Icon Social', 'kuteshop' ),
								'default' => 'fa fa-facebook',
							),
						),
						'default'         => array(
							array(
								'title_social' => 'Facebook',
								'link_social'  => 'https://facebook.com/',
								'icon_social'  => 'fa fa-facebook',
							),
							array(
								'title_social' => 'Twitter',
								'link_social'  => 'https://twitter.com/',
								'icon_social'  => 'fa fa-twitter',
							),
							array(
								'title_social' => 'Youtube',
								'link_social'  => 'https://youtube.com/',
								'icon_social'  => 'fa fa-youtube',
							),
							array(
								'title_social' => 'Pinterest',
								'link_social'  => 'https://pinterest.com/',
								'icon_social'  => 'fa fa-pinterest',
							),
							array(
								'title_social' => 'Instagram',
								'link_social'  => 'https://instagram.com/',
								'icon_social'  => 'fa fa-instagram',
							),
						),
					),
				),
			);
			$options['typography'] = array(
				'name'   => 'typography',
				'icon'   => 'fa fa-font',
				'title'  => esc_html__( 'Typography', 'kuteshop' ),
				'fields' => array(
					'body_typography' => array(
						'id'                 => 'body_typography',
						'type'               => 'typography',
						'title'              => esc_html__( 'Typography of Body', 'kuteshop' ),
						'font_family'        => true,
						'font_weight'        => true,
						'font_style'         => true,
						'font_size'          => true,
						'line_height'        => true,
						'letter_spacing'     => true,
						'text_align'         => true,
						'text-transform'     => true,
						'color'              => true,
						'subset'             => true,
						'backup_font_family' => true,
						'font_variant'       => true,
						'word_spacing'       => true,
						'text_decoration'    => true,
						'output'             => 'body',
					),
				),
			);
			$options[]             = array(
				'name'   => 'backup_option',
				'title'  => esc_html__( 'Backup Options', 'kuteshop' ),
				'icon'   => 'fa fa-bold',
				'fields' => array(
					array(
						'type'  => 'backup',
						'title' => esc_html__( 'Backup Field', 'kuteshop' ),
					),
					array(
						'id'      => 'delete_transients',
						'type'    => 'content',
						'content' => '<a href="#" data-text-done="' . esc_attr__( '%n transient database entries have been deleted.', 'kuteshop' ) . '" class="button button-primary delete-transients"/>' . esc_html__( 'Delete Transients', 'kuteshop' ) . '</a><span class="spinner" style="float:none;"></span>',
						'title'   => esc_html__( 'Delete Transients', 'kuteshop' ),
						'desc'    => esc_html__( 'All transient related database entries will be deleted.', 'kuteshop' ),
						'after'   => ' <p class="ovic-text-success"></p>',
					),
					array(
						'id'      => 'update_database',
						'type'    => 'content',
						'content' => '<a href="#" data-text-done="' . esc_attr__( 'database entries have been updated.', 'kuteshop' ) . '" class="button button-primary update-database"/>' . esc_html__( 'Update Database', 'kuteshop' ) . '</a><span class="spinner" style="float:none;"></span>',
						'title'   => esc_html__( 'Update Database', 'kuteshop' ),
						'desc'    => esc_html__( 'Database will be update for compare with new version theme, There is NO UNDO.', 'kuteshop' ),
						'after'   => ' <p class="ovic-text-success"></p>',
					),
				),
			);
			//
			// Framework Settings
			//
			$settings = array(
				'option_name'      => '_cs_options',
				'menu_title'       => esc_html__( 'Theme Options', 'kuteshop' ),
				'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
				'menu_parent'      => 'kuteshop_menu',
				'menu_slug'        => 'kuteshop_options',
				'menu_position'    => 5,
				'show_search'      => true,
				'show_reset'       => true,
				'show_footer'      => false,
				'show_all_options' => true,
				'ajax_save'        => true,
				'sticky_header'    => false,
				'save_defaults'    => true,
				'framework_title'  => sprintf(
					'%s <small>%s <a href="%s" target="_blank">%s</a></small>',
					esc_html__( 'Theme Options', 'kuteshop' ),
					esc_html__( 'by', 'kuteshop' ),
					esc_url( 'https://kutethemes.com/' ),
					esc_html__( 'Kutethemes', 'kuteshop' )
				),
			);

			OVIC_Options::instance( $settings, apply_filters( 'kuteshop_framework_theme_options', $options ) );
		}

		public function install_metabox_options()
		{
			$sections = array();
			// -----------------------------------------
			// Page Meta box Options                   -
			// -----------------------------------------
			$sections[] = array(
				'id'        => '_custom_metabox_theme_options',
				'title'     => esc_html__( 'Custom Theme Options', 'kuteshop' ),
				'post_type' => 'page',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'page_banner_settings',
						'title'  => esc_html__( 'Banner Settings', 'kuteshop' ),
						'icon'   => 'fa fa-picture-o',
						'fields' => array(
							array(
								'id'         => 'kuteshop_metabox_enable_banner',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Banner', 'kuteshop' ),
								'default'    => false,
								'attributes' => array(
									'data-depend-id' => 'kuteshop_metabox_enable_banner',
								),
							),
							array(
								'id'    => 'bg_banner_page',
								'type'  => 'background',
								'title' => esc_html__( 'Background Banner', 'kuteshop' ),
							),
							array(
								'id'      => 'height_banner',
								'type'    => 'number',
								'title'   => esc_html__( 'Height Banner', 'kuteshop' ),
								'default' => '400',
							),
							array(
								'id'      => 'page_margin_top',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Top', 'kuteshop' ),
								'default' => 0,
							),
							array(
								'id'      => 'page_margin_bottom',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Bottom', 'kuteshop' ),
								'default' => 0,
							),
						),
					),
					array(
						'name'   => 'page_theme_options',
						'title'  => esc_html__( 'Theme Options', 'kuteshop' ),
						'icon'   => 'fa fa-wordpress',
						'fields' => array(
							array(
								'id'    => 'metabox_kuteshop_logo',
								'type'  => 'image',
								'title' => esc_html__( 'Logo', 'kuteshop' ),
							),
							array(
								'id'      => 'metabox_kuteshop_main_color',
								'type'    => 'color',
								'title'   => esc_html__( 'Main Color', 'kuteshop' ),
								'default' => '#ff3366',
								'rgba'    => true,
							),
						),
					),
					array(
						'name'   => 'vertical_theme_options',
						'title'  => esc_html__( 'Vertical Menu Settings', 'kuteshop' ),
						'icon'   => 'fa fa-bar-chart',
						'fields' => array(
							array(
								'id'         => 'metabox_enable_vertical_menu',
								'type'       => 'switcher',
								'attributes' => array(
									'data-depend-id' => 'metabox_enable_vertical_menu',
								),
								'default'    => false,
								'title'      => esc_html__( 'Vertical Menu', 'kuteshop' ),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Title', 'kuteshop' ),
								'id'         => 'metabox_vertical_menu_title',
								'type'       => 'text',
								'default'    => esc_html__( 'CATEGORIES', 'kuteshop' ),
								'dependency' => array(
									'metabox_enable_vertical_menu', '==', true,
								),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Button show all text', 'kuteshop' ),
								'id'         => 'metabox_vertical_menu_button_all_text',
								'type'       => 'text',
								'default'    => esc_html__( 'All Categories', 'kuteshop' ),
								'dependency' => array(
									'metabox_enable_vertical_menu', '==', true,
								),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Button close text', 'kuteshop' ),
								'id'         => 'metabox_vertical_menu_button_close_text',
								'type'       => 'text',
								'default'    => esc_html__( 'Close', 'kuteshop' ),
								'dependency' => array(
									'metabox_enable_vertical_menu', '==', true,
								),
							),
							array(
								'title'      => esc_html__( 'The number of visible vertical menu items', 'kuteshop' ),
								'desc'       => esc_html__( 'The number of visible vertical menu items', 'kuteshop' ),
								'id'         => 'metabox_vertical_item_visible',
								'default'    => 10,
								'type'       => 'spinner',
								'unit'       => 'items',
								'dependency' => array(
									'metabox_enable_vertical_menu', '==', true,
								),
							),
						),
					),
					array(
						'name'   => 'header_theme_options',
						'title'  => esc_html__( 'Header Settings', 'kuteshop' ),
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'         => 'kuteshop_metabox_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'kuteshop' ),
								'desc'       => esc_html__( 'Select a header layout', 'kuteshop' ),
								'options'    => self::get_header_options(),
								'default'    => 'style-01',
								'attributes' => array(
									'data-depend-id' => 'kuteshop_metabox_used_header',
								),
							),
							array(
								'id'         => 'metabox_header_text_box',
								'type'       => 'text',
								'title'      => esc_html__( 'Header Text Box', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_metabox_used_header', '==', 'style-03',
								),
							),
							array(
								'id'              => 'metabox_header_service_box',
								'type'            => 'group',
								'title'           => esc_html__( 'Header Service', 'kuteshop' ),
								'button_title'    => esc_html__( 'Add New', 'kuteshop' ),
								'accordion_title' => esc_html__( 'Header Service Settings', 'kuteshop' ),
								'dependency'      => array(
									'kuteshop_metabox_used_header', '==', 'style-07',
								),
								'fields'          => array(
									array(
										'id'    => 'service_box_image',
										'type'  => 'image',
										'title' => esc_html__( 'Image', 'kuteshop' ),
									),
									array(
										'id'    => 'service_box_text',
										'type'  => 'text',
										'title' => esc_html__( 'Text', 'kuteshop' ),
									),
								),
							),
							array(
								'id'         => 'metabox_header_phone',
								'type'       => 'text',
								'title'      => esc_html__( 'Header Phone Number', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_metabox_used_header', '==', 'style-11',
								),
							),
							array(
								'id'         => 'metabox_header_banner',
								'type'       => 'image',
								'title'      => esc_html__( 'Banner', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_metabox_used_header', 'any', 'style-01,style-13',
								),
							),
							array(
								'id'         => 'metabox_header_banner_url',
								'type'       => 'text',
								'title'      => esc_html__( 'Banner Url', 'kuteshop' ),
								'dependency' => array(
									'kuteshop_metabox_used_header', 'any', 'style-01,style-13',
								),
							),
						),
					),
					array(
						'name'   => 'footer_theme_options',
						'title'  => esc_html__( 'Footer Settings', 'kuteshop' ),
						'icon'   => 'fa fa-folder-open-o',
						'fields' => array(
							array(
								'id'      => 'kuteshop_metabox_footer_options',
								'type'    => 'select_preview',
								'title'   => esc_html__( 'Select Footer Builder', 'kuteshop' ),
								'options' => self::get_footer_preview(),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Footer Meta box Options            -
			// -----------------------------------------
			$sections[] = array(
				'id'        => '_custom_footer_options',
				'title'     => esc_html__( 'Custom Footer Options', 'kuteshop' ),
				'post_type' => 'footer',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => esc_html__( 'FOOTER STYLE', 'kuteshop' ),
						'fields' => array(
							array(
								'id'       => 'kuteshop_footer_style',
								'type'     => 'select_preview',
								'title'    => esc_html__( 'Footer Style', 'kuteshop' ),
								'subtitle' => esc_html__( 'Select a Footer Style', 'kuteshop' ),
								'options'  => self::get_footer_options(),
								'default'  => 'style-01',
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Side Meta box Options              -
			// -----------------------------------------
			$sections[] = array(
				'id'        => '_custom_page_side_options',
				'title'     => esc_html__( 'Custom Page Side Options', 'kuteshop' ),
				'post_type' => 'page',
				'context'   => 'side',
				'priority'  => 'default',
				'sections'  => array(
					array(
						'name'   => 'page_option',
						'fields' => array(
							array(
								'id'      => 'sidebar_page_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'kuteshop' ),
								'desc'    => esc_html__( 'Select sidebar position on Page.', 'kuteshop' ),
								'options' => array(
									'left'  => get_theme_file_uri( 'framework/assets/images/left-sidebar.png' ),
									'right' => get_theme_file_uri( 'framework/assets/images/right-sidebar.png' ),
									'full'  => get_theme_file_uri( 'framework/assets/images/default-sidebar.png' ),
								),
								'default' => 'left',
							),
							array(
								'id'         => 'page_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Page Sidebar', 'kuteshop' ),
								'options'    => 'sidebar',
								'dependency' => array( 'sidebar_page_layout', '!=', 'full' ),
							),
							array(
								'id'    => 'page_extra_class',
								'type'  => 'text',
								'title' => esc_html__( 'Extra Class', 'kuteshop' ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Testimonials Meta box Options      -
			// -----------------------------------------
			$sections[] = array(
				'id'        => '_custom_testimonial_options',
				'title'     => esc_html__( 'Custom Testimonial Options', 'kuteshop' ),
				'post_type' => 'testimonial',
				'context'   => 'normal',
				'priority'  => 'high',
				'sections'  => array(
					array(
						'name'   => 'testimonial_info',
						'fields' => array(
							array(
								'id'        => 'avatar_testimonial',
								'type'      => 'image',
								'title'     => esc_html__( 'Avatar', 'kuteshop' ),
								'add_title' => esc_html__( 'Add Avatar', 'kuteshop' ),
							),
							array(
								'id'    => 'name_testimonial',
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'kuteshop' ),
							),
							array(
								'id'    => 'position_testimonial',
								'type'  => 'text',
								'title' => esc_html__( 'Position', 'kuteshop' ),
							),
						),
					),
				),
			);
			// -----------------------------------------
			// Page Products Meta box Options      -
			// -----------------------------------------
			if ( class_exists( 'WooCommerce' ) ) {
				$sections[] = array(
					'id'        => '_custom_product_options',
					'title'     => esc_html__( 'Product Options', 'kuteshop' ),
					'post_type' => 'product',
					'context'   => 'side',
					'priority'  => 'high',
					'sections'  => array(
						array(
							'name'   => 'video',
							'fields' => array(
								'poster' => array(
									'id'    => 'poster',
									'type'  => 'image',
									'title' => esc_html__( 'Poster Video', 'kuteshop' ),
								),
								'video'  => array(
									'id'    => 'video',
									'type'  => 'text',
									'title' => esc_html__( 'Video Url', 'kuteshop' ),
								),
							),
						),
					),
				);
			}

			OVIC_Metabox::instance( apply_filters( 'kuteshop_framework_metabox_options', $sections ) );
		}

		public function install_taxonomy_options()
		{
			$sections = array();
			// -----------------------------------------
			// Taxonomy Options                        -
			// -----------------------------------------
			$sections[] = array(
				'id'       => '_custom_taxonomy_options',
				'taxonomy' => 'product_cat', // category, post_tag or your custom taxonomy name
				'fields'   => array(
					array(
						'id'      => 'icon_taxonomy',
						'type'    => 'icon',
						'title'   => esc_html__( 'Icon Taxonomy', 'kuteshop' ),
						'default' => '',
					),
				),
			);

			OVIC_Taxonomy::instance( apply_filters( 'kute_boutique_framework_metabox_options', $sections ) );
		}
	}

	new Kuteshop_Theme_Option();
}
