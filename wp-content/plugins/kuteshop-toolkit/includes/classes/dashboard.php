<?php
if ( ! class_exists( 'Kuteshop_Welcome' ) ) {
	class Kuteshop_Welcome
	{
		public $tabs = array();
		public $theme_name;

		public function __construct()
		{
			$this->set_tabs();
			$this->theme_name = wp_get_theme()->get( 'Name' );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
			add_action( 'wp_dashboard_setup', array( $this, 'dashboard_add_widgets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'dashboard_admin_scripts' ) );
		}

		public function admin_menu()
		{
			if ( current_user_can( 'edit_theme_options' ) ) {
				add_menu_page( 'Kuteshop', 'Kuteshop', 'manage_options', 'kuteshop_menu', array(
					$this,
					'welcome'
				), KUTESHOP_TOOLKIT_URL . '/assets/images/menu-icon.png', 2 );
				add_submenu_page( 'kuteshop_menu', 'Kuteshop Dashboard', 'Dashboard', 'manage_options', 'kuteshop_menu', array(
					$this,
					'welcome'
				) );
			}
		}

		public function dashboard_add_widgets()
		{
			wp_add_dashboard_widget(
				'ovic_dashboard_widgets',
				esc_html__( 'KuteThemes Feature Products', 'kuteshop-toolkit' ),
				array( $this, 'dashboard_widgets_handler' )
			);
		}

		public function dashboard_widgets_handler()
		{
			$feeds = array(
				'products' => array(
					'link'         => 'https://kutethemes.com/',
					'url'          => add_query_arg(
						array(
							'post_type'      => 'download',
							'dashboard_feed' => 1,
						),
						'https://kutethemes.com/feed/'
					),
					'title'        => 'KuteThemes Products',
					'items'        => 6,
					'show_summary' => 0,
					'show_author'  => 0,
					'show_date'    => 1,
				),
			);
			?>
            <div class="ovic-dashboard-news hide-if-no-js">
				<?php wp_dashboard_primary_output( 'ovic_dashboard_widgets', $feeds ); ?>
            </div>
            <p class="ovic-dashboard-footer">
				<?php
				printf(
					'<a href="%1$s" target="_blank">%2$s <span class="screen-reader-text">%3$s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
					esc_url( 'admin.php?page=kuteshop_menu&tab=more_theme' ),
					__( 'Our Themeforest' ),
					/* translators: accessibility text */
					__( '(opens in a new tab)' )
				);
				?>

                |

				<?php
				printf(
					'<a href="%1$s" target="_blank">%2$s <span class="screen-reader-text">%3$s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
					esc_url( 'https://kutethemes.com/' ),
					__( 'Our Club Themes' ),
					/* translators: accessibility text */
					__( '(opens in a new tab)' )
				);
				?>

                |

				<?php
				printf(
					'<a href="%1$s" target="_blank">%2$s <span class="screen-reader-text">%3$s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>',
					/* translators: If a Rosetta site exists (e.g. https://es.wordpress.org/news/), then use that. Otherwise, leave untranslated. */
					'admin.php?page=kuteshop_menu&tab=changelog',
					__( 'Changelog' ),
					/* translators: accessibility text */
					__( '(opens in a new tab)' )
				);
				?>
            </p>
			<?php
		}

		public function dashboard_admin_scripts( $hook )
		{
			if ( $hook == 'toplevel_page_kuteshop_menu' ) {
				wp_enqueue_style(
					'ocolus-dashboard',
					KUTESHOP_TOOLKIT_URL . 'assets/dashboard.css', array(),
					KUTESHOP_VERSION
				);
			}
			if ( $hook == 'index.php' ) {
				wp_enqueue_style(
					'ovic-widget-dashboard',
					KUTESHOP_TOOLKIT_URL . 'assets/dashboard-widgets.css', array(),
					KUTESHOP_VERSION
				);
				wp_enqueue_script(
					'ovic-widget-dashboard',
					KUTESHOP_TOOLKIT_URL . 'assets/dashboard-widgets.js',
					array( 'jquery' ),
					KUTESHOP_VERSION,
					true
				);
			}
		}

		public function set_tabs()
		{
			$this->tabs = array(
				'dashboard'  => esc_html__( 'Welcome', 'kuteshop-toolkit' ),
				'plugins'    => esc_html__( 'Plugins', 'kuteshop-toolkit' ),
				'more_theme' => esc_html__( 'More Theme', 'kuteshop-toolkit' ),
				'changelog'  => esc_html__( 'ChangeLog', 'kuteshop-toolkit' ),
			);
		}

		public function more_theme()
		{
			$token           = 'sntVqHmrHVU5FGEkESRFHdE45rJs9AIg';
			$themeforest_api = add_query_arg(
				array(
					'site'           => 'themeforest.net',
					'page'           => '1',
					'username'       => 'kutethemes',
					'sort_by'        => 'sales',
					'sort_direction' => 'desc',
					'page_size'      => '30',
					'term'           => 'wordpress',
				),
				'https://api.envato.com/v1/discovery/search/search/item'
			);
			$items           = get_transient( 'ovic_dashboard_our_themes_' . md5( $themeforest_api ) );
			if ( $items === false ) {
				$response = wp_remote_get( $themeforest_api, array(
						'headers' => array( 'Authorization' => 'Bearer ' . $token ),
					)
				);
				if ( ! is_wp_error( $response ) ) {
					$data    = json_decode( $response['body'], true );
					$matches = isset( $data['matches'] ) ? $data['matches'] : array();
					foreach ( $matches as $match ) {
						$items[] = array(
							'id'              => $match['id'],
							'previews'        => $match['previews']['landscape_preview']['landscape_url'],
							'url'             => $match['url'],
							'rating'          => $match['rating']['rating'],
							'number_of_sales' => $match['number_of_sales'],
							'name'            => $match['name'],
						);
					}
					set_transient( 'ovic_dashboard_our_themes_' . md5( $themeforest_api ), $items, 12 * HOUR_IN_SECONDS );
				}
			}
			if ( isset( $items ) && ! empty( $items ) ) {
				/**
				 * affiliates
				 * CDN: https://cdn.staticaly.com/wp/p/:plugin_name/:version/:file
				 */
				include 'affiliates.php';
				$affiliates = ovic_link_affiliates();
				?>
                <div class="rp-row plugin-tabs">
					<?php
					foreach ( $items as $key => $item ) {
						$url = ! empty( $affiliates[ $item['id'] ] ) ? $affiliates[ $item['id'] ] : $item['url'];
						?>
                        <div class="rp-col">
                            <div class="plugin theme-item">
                                <div class="thumb">
                                    <a target="_blank" href="<?php echo esc_url( $url ); ?>">
                                        <img src="<?php echo esc_url( $item['previews'] ) ?>"
                                             alt="envato">
                                    </a>
                                </div>
                                <div class="meta">
									<?php
									$percent = $item['rating'] / 5 * 100;
									?>
                                    <div class="star-rating">
                                        <span style="width:<?php echo esc_attr( $percent ); ?>%"></span>
                                    </div>
                                    <strong class="sale">
										<?php echo $item['number_of_sales'] . ' Sales'; ?>
                                    </strong>
                                </div>
                                <h4 class="name">
                                    <a target="_blank" href="<?php echo esc_url( $url ); ?>">
										<?php echo '' . $item['name']; ?>
                                    </a>
                                </h4>
                            </div>
                        </div>
						<?php
					}
					?>
                    <div class="rp-col">
                        <div class="plugin theme-item">
                            <a target="_blank" class="view-all"
                               href="https://themeforest.net/user/kutethemes/portfolio"><?php esc_html_e( 'View All Our Themes', 'kuteshop-toolkit' ); ?></a>
                        </div>
                    </div>
                </div>
				<?php
			}
		}

		public function changelog()
		{
			if ( file_exists( get_template_directory() . '/changelog.txt' ) ) {
				$changelog = wp_remote_get( get_theme_file_uri( '/changelog.txt' ) );
				echo '<pre class="changelog">';
				print_r( $changelog['body'] );
				echo '</pre>';
			}
		}

		public function active_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'kuteshop-toolkit' );
				die;
			}
			if ( isset( $_GET['plugin_slug'] ) && $_GET['plugin_slug'] != "" ) {
				$plugin_slug = $_GET['plugin_slug'];
				$plugins     = TGM_Plugin_Activation::$instance->plugins;
				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $plugin_slug ) {
						activate_plugins( $plugin['file_path'] );
						?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=kuteshop_menu&tab=plugins";
                        </script>
						<?php
						break;
					}
				}
			}
		}

		public function deactivate_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'kuteshop-toolkit' );
				die;
			}
			if ( isset( $_GET['plugin_slug'] ) && $_GET['plugin_slug'] != "" ) {
				$plugin_slug = $_GET['plugin_slug'];
				$plugins     = TGM_Plugin_Activation::$instance->plugins;
				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $plugin_slug ) {
						deactivate_plugins( $plugin['file_path'] );
						?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=kuteshop_menu&tab=plugins";
                        </script>
						<?php
						break;
					}
				}
			}
		}

		public function intall_plugin()
		{
		}

		public function dashboard()
		{
			?>
            <div class="dashboard">
                <h1>Welcome to <?php echo ucfirst( esc_html( $this->theme_name ) ); ?></h1>
                <p class="about-text">Thanks for using our theme, we have worked very hard to release a great product
                    and we will do our absolute best to support this theme and fix all the issues. </p>
                <div class="dashboard-intro">
                    <div class="image">
                        <img src="<?php echo esc_url( get_theme_file_uri( '/screenshot.jpg' ) ); ?>" alt="kuteshop">
                    </div>
                    <div class="intro">
                        <p class="text"><strong><?php echo ucfirst( esc_html( $this->theme_name ) ); ?></strong> is a
                            modern, clean
                            and professional WooCommerce Wordpress Theme, It
                            is fully responsive, it looks stunning on all types of screens and devices.</p>
                        <h2>Quick Settings</h2>
                        <ul>
							<?php if ( class_exists( 'Ovic_Import_Demo' ) ): ?>
                                <li><a href="admin.php?page=ovic-import">Install Demos</a></li>
							<?php endif; ?>
                            <li><a href="admin.php?page=kuteshop_menu&tab=plugins">Install Plugins</a></li>
                            <li><a href="admin.php?page=kuteshop_options">Theme Options</a></li>
                        </ul>
						<?php $this->support(); ?>
                    </div>
                </div>
            </div>
			<?php
		}

		public function welcome()
		{
			/* deactivate_plugin */
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'deactivate_plugin' ) {
				$this->deactivate_plugin();
			}
			/* deactivate_plugin */
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'active_plugin' ) {
				$this->active_plugin();
			}
			$tab = 'dashboard';
			if ( isset( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			}
			?>
            <div class="kuteshop-wrap">
                <div id="tabs-container" role="tabpanel">
                    <div class="nav-tab-wrapper">
						<?php foreach ( $this->tabs as $key => $value ): ?>
                            <a class="nav-tab kuteshop-nav <?php if ( $tab == $key ): ?> active<?php endif; ?>"
                               href="admin.php?page=kuteshop_menu&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
						<?php endforeach; ?>
                    </div>
                    <div class="tab-content">
						<?php $this->$tab(); ?>
                    </div>
                </div>
            </div>
			<?php
		}

		public static function plugins()
		{
			$kuteshop_tgm_theme_plugins = TGM_Plugin_Activation::$instance->plugins;
			$tgm                        = TGM_Plugin_Activation::$instance;
			?>
            <div class="plugin-tabs rp-row">
				<?php
				$wp_plugin_list = get_plugins();
				foreach ( $kuteshop_tgm_theme_plugins as $kuteshop_tgm_theme_plugin ) {
					if ( $tgm->is_plugin_active( $kuteshop_tgm_theme_plugin['slug'] ) ) {
						$status_class = 'is-active';
						if ( $tgm->does_plugin_have_update( $kuteshop_tgm_theme_plugin['slug'] ) ) {
							$status_class = 'plugin-update';
						}
					} elseif ( isset( $wp_plugin_list[ $kuteshop_tgm_theme_plugin['file_path'] ] ) ) {
						$status_class = 'plugin-inactive';
					} else {
						$status_class = 'no-intall';
					}
					?>
                    <div class="rp-col">
                        <div class="plugin <?php echo esc_attr( $status_class ); ?>">
                            <div class="preview">
								<?php if ( isset( $kuteshop_tgm_theme_plugin['image'] ) && $kuteshop_tgm_theme_plugin['image'] != "" ): ?>
                                    <img src="<?php echo esc_url( $kuteshop_tgm_theme_plugin['image'] ); ?>"
                                         alt="kuteshop">
								<?php else: ?>
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/framework/assets/images/no-image.jpg' ); ?>"
                                         alt="kuteshop">
								<?php endif; ?>
                            </div>
                            <div class="plugin-name">
                                <h3 class="theme-name"><?php echo $kuteshop_tgm_theme_plugin['name'] ?></h3>
                            </div>
                            <div class="actions">
                                <a class="button button-primary button-install-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'        => urlencode( $kuteshop_tgm_theme_plugin['slug'] ),
												'tgmpa-install' => 'install-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Install', 'kuteshop-toolkit' ); ?></a>

                                <a class="button button-primary button-update-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'         => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'       => urlencode( $kuteshop_tgm_theme_plugin['slug'] ),
												'tgmpa-update' => 'update-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Update', 'kuteshop-toolkit' ); ?></a>

                                <a class="button button-primary button-activate-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'kuteshop_menu&tab=plugins',
											'plugin_slug' => urlencode( $kuteshop_tgm_theme_plugin['slug'] ),
											'action'      => 'active_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>"><?php esc_html_e( 'Activate', 'kuteshop-toolkit' ); ?></a>
                                <a class="button button-secondary button-uninstall-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'kuteshop_menu&tab=plugins',
											'plugin_slug' => urlencode( $kuteshop_tgm_theme_plugin['slug'] ),
											'action'      => 'deactivate_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>"><?php esc_html_e( 'Deactivate', 'kuteshop-toolkit' ); ?></a>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		}

		public function support()
		{
			?>
            <div class="rp-row support-tabs">
                <div class="rp-col">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Documentation', 'kuteshop-toolkit' ); ?></h3>
                        <p><?php esc_html_e( 'Here is our user guide for ' . ucfirst( esc_html( $this->theme_name ) ) . ', including basic setup steps, as well as ' . ucfirst( esc_html( $this->theme_name ) ) . ' features and elements for your reference.', 'kuteshop-toolkit' ); ?></p>
                        <a target="_blank"
                           href="<?php echo esc_url( 'https://kuteshop.kute-themes.net/changelog.txt' ); ?>"
                           class="button button-primary"><?php esc_html_e( 'Read Changelog', 'kuteshop-toolkit' ); ?></a>
                    </div>
                </div>
                <div class="rp-col closed">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Video Tutorials', 'kuteshop-toolkit' ); ?></h3>
                        <p><?php esc_html_e( 'Video tutorials is the great way to show you how to setup ' . ucfirst( esc_html( $this->theme_name ) ) . ' theme, make sure that the feature works as it\'s designed.', 'kuteshop-toolkit' ); ?></p>
                        <a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=Vq6nMIyj3gg&feature=youtu.be' ) ?>"
                           target="_blank"
                           class="button button-primary"><?php esc_html_e( 'See Video', 'kuteshop-toolkit' ); ?></a>
                    </div>
                </div>
                <div class="rp-col">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Forum', 'kuteshop-toolkit' ); ?></h3>
                        <p><?php esc_html_e( 'Can\'t find the solution on documentation? We\'re here to help, even on weekend. Just click here to start 1on1 chatting with us!', 'kuteshop-toolkit' ); ?></p>
                        <a target="_blank"
                           href="<?php echo esc_url( 'http://support.kutethemes.net/support-system' ); ?>"
                           class="button button-primary"><?php esc_html_e( 'Request Support', 'kuteshop-toolkit' ); ?></a>
                    </div>
                </div>
            </div>

			<?php
		}
	}

	new Kuteshop_Welcome();
}
