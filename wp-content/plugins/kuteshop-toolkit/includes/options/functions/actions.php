<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'ovic_get_icons' ) ) {
	function ovic_get_icons()
	{
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'ovic_icon_nonce' ) ) {

			$content = '';
			$nav     = '';

			OVIC::include_plugin_file( 'fields/icon/default-icons.php' );

			$icon_lists = apply_filters( 'ovic_field_icon_add_icons', ovic_get_default_icons() );

			if ( ! empty( $icon_lists ) ) {
				foreach ( $icon_lists as $key => $list ) {

					$active         = '';
					$class_icon     = 'fa-folder';
					$sanitize_class = strtolower( sanitize_html_class( $list['title'] ) );
					$class          = $sanitize_class;

					if ( $key > 0 ) {
						$class .= ' hidden';
					} else {
						$active     = 'ovic-section-active';
						$class_icon = 'fa-folder-open';
					}

					$nav .= '<li><a href="#" data-active=".' . $sanitize_class . '" class="' . $active . '">';
					$nav .= '<i class="fa ' . $class_icon . '"></i>' . $list['title'] . '';
					$nav .= '</a></li>';

					$content .= '<div class="' . esc_attr( $class ) . '">';

					foreach ( $list['icons'] as $icon ) {
						$content .= '<a class="ovic-icon-tooltip" data-ovic-icon="' . $icon . '" title="' . $icon . '"><span class="ovic-icon ovic-selector"><i class="' . $icon . '"></i></span></a>';
					}

					$content .= '</div>';
				}
			} else {
				$content .= '<div class="ovic-text-error">' . esc_html__( 'No data provided by developer',
						'ovic-addon-toolkit' ) . '</div>';
			}

			wp_send_json_success(
				array(
					'nav'     => $nav,
					'content' => $content,
				)
			);
		} else {
			wp_send_json_error(
				array(
					'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.',
						'ovic-addon-toolkit' )
				)
			);
		}
	}

	add_action( 'wp_ajax_ovic-get-icons', 'ovic_get_icons' );
}
/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'ovic_export' ) ) {
	function ovic_export()
	{
		if ( ! empty( $_GET['export'] ) && ! empty( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'],
				'ovic_backup_nonce' ) ) {
			header( 'Content-Type: plain/text' );
			header( 'Content-disposition: attachment; filename=backup-' . gmdate( 'd-m-Y' ) . '.txt' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			echo json_encode( get_option( wp_unslash( $_GET['export'] ) ) );
		}

		wp_die();
	}

	add_action( 'wp_ajax_ovic-export', 'ovic_export' );
}
/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'ovic_import_ajax' ) ) {
	function ovic_import_ajax()
	{
		if ( ! empty( $_POST['import_data'] ) && ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'],
				'ovic_backup_nonce' ) ) {
			$import_data = json_decode( wp_unslash( trim( $_POST['import_data'] ) ), true );

			if ( is_array( $import_data ) ) {
				update_option( wp_unslash( $_POST['unique'] ), wp_unslash( $import_data ) );
				wp_send_json_success();
			}
		}

		wp_send_json_error(
			array(
				'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'ovic-addon-toolkit' )
			)
		);
	}

	add_action( 'wp_ajax_ovic-import', 'ovic_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'ovic_reset_ajax' ) ) {
	function ovic_reset_ajax()
	{
		if ( ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'],
				'ovic_backup_nonce' ) ) {
			delete_option( wp_unslash( $_POST['unique'] ) );
			wp_send_json_success();
		}

		wp_send_json_error(
			array(
				'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'ovic-addon-toolkit' )
			)
		);
	}

	add_action( 'wp_ajax_ovic-reset', 'ovic_reset_ajax' );
}
/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'ovic_chosen_ajax' ) ) {
	function ovic_chosen_ajax()
	{
		if ( ! empty( $_POST['term'] ) && ! empty( $_POST['type'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'],
				'ovic_chosen_ajax_nonce' ) ) {
			$capability = apply_filters( 'ovic_chosen_ajax_capability', 'manage_options' );

			if ( current_user_can( $capability ) ) {
				$type       = $_POST['type'];
				$term       = $_POST['term'];
				$query_args = ( ! empty( $_POST['query_args'] ) ) ? $_POST['query_args'] : array();
				$options    = OVIC_Fields::field_data( $type, $term, $query_args );

				wp_send_json_success( $options );
			} else {
				wp_send_json_error(
					array(
						'error' => esc_html__( 'You do not have required permissions to access.', 'ovic-addon-toolkit' )
					)
				);
			}
		} else {
			wp_send_json_error(
				array(
					'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.',
						'ovic-addon-toolkit' )
				)
			);
		}
	}

	add_action( 'wp_ajax_ovic-chosen', 'ovic_chosen_ajax' );
}
/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'ovic_set_icons' ) ) {
	function ovic_set_icons()
	{
		?>
        <div id="ovic-modal-icon" class="ovic-modal-v2 ovic-modal-icon">
            <div class="ovic-modal-table">
                <div class="ovic-modal-table-cell">
                    <div class="ovic-modal-overlay"></div>
                    <div class="ovic-modal-inner ovic ovic-theme-dark">
                        <div class="ovic-header">
                            <div class="ovic-header-inner">
                                <div class="ovic-header-left">
                                    <h1>
										<?php esc_html_e( 'Add Icon', 'ovic-addon-toolkit' ); ?>
                                    </h1>
                                </div>
                                <div class="ovic-header-right">
                                    <div class="ovic-search-icon">
                                        <input type="text"
                                               placeholder="<?php esc_html_e( 'Search a Icon...',
											       'ovic-addon-toolkit' ); ?>"
                                               class="ovic-icon-search"/>
                                    </div>
                                    <div class="ovic-buttons">
                                        <input class="button button-secondary ovic-warning-primary ovic-modal-close"
                                               type="button" value="<?php echo esc_html__( 'Close',
											'ovic-addon-toolkit' ); ?>">
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="ovic-nav">
                            <ul></ul>
                        </div>
                        <div class="ovic-modal-content">
                            <div class="ovic-modal-loading">
                                <div class="ovic-loading"></div>
                            </div>
                            <div class="ovic-modal-load"></div>
                        </div>
	                    <div class="ovic-nav-background"></div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	add_action( 'admin_footer', 'ovic_set_icons' );
	add_action( 'elementor/editor/footer', 'ovic_set_icons' );
	add_action( 'customize_controls_print_footer_scripts', 'ovic_set_icons' );
}