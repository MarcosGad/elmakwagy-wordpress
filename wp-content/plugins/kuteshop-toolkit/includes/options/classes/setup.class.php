<?php
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'OVIC' ) ) {
	class OVIC
	{
		/**
		 *
		 * instance
		 * @access private
		 * @var OVIC
		 */
		private static $instance = null;
		/**
		 * constants
		 */
		public static $version = '2.1.8';
		public static $dir     = null;
		public static $url     = null;
		public static $min     = null;

		// instance
		public static function instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			/* check for developer mode */
			self::$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// init action
			do_action( 'ovic_before_load' );

			// set constants
			self::constants();

			// include files
			self::includes();

			// enqueue scripts
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'enqueue_scripts' ), 9999 );

			// enqueue scripts elementor
			add_action( 'elementor/editor/after_enqueue_scripts', array( self::$instance, 'enqueue_scripts' ) );

			// options loaded
			do_action( 'ovic_after_load' );

			return self::$instance;
		}

		public static function include_plugin_file( $file, $load = true )
		{
			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'ovic_override_framework', 'options-override' );
			if ( file_exists( get_parent_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_parent_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( get_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( self::$dir . '/' . $override . '/' . $file ) ) {
				$path = self::$dir . '/' . $override . '/' . $file;
			} elseif ( file_exists( self::$dir . '/' . $file ) ) {
				$path = self::$dir . '/' . $file;
			}
			if ( ! empty( $path ) && ! empty( $file ) && $load ) {
				global $wp_query;
				if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {
					load_template( $path, true );
				} else {
					require_once( $path );
				}
			} else {
				return self::$dir . '/' . $file;
			}

			return self::$dir;
		}

		// Sanitize dirname
		public static function sanitize_dirname( $dirname )
		{
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		// Set plugin url
		public static function include_plugin_url( $file )
		{
			return self::$url . '/' . ltrim( $file, '/' );
		}

		// Define constants
		public static function constants()
		{
			// we need this path-finder code for set URL of framework
			$dirname        = wp_normalize_path( dirname( dirname( __FILE__ ) ) );
			$theme_dir      = wp_normalize_path( get_parent_theme_file_path() );
			$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
			$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#',
				self::sanitize_dirname( $dirname ) ) ) ? true : false;
			$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
			$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
			$folder_name    = str_replace( $directory, '', $dirname );
			$protocol_uri   = ( is_ssl() ) ? 'https' : 'http';
			$directory_uri  = set_url_scheme( $directory_uri, $protocol_uri );
			self::$dir      = $dirname;
			self::$url      = $directory_uri . $folder_name;
		}

		// Includes options files
		public static function includes()
		{
			// includes helpers
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/deprecated.php' );
			self::include_plugin_file( 'functions/fallback.php' );
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// includes classes
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/options.class.php' );
			self::include_plugin_file( 'classes/metabox.class.php' );
			self::include_plugin_file( 'classes/taxonomy.class.php' );
			self::include_plugin_file( 'classes/shortcode.class.php' );
			self::include_plugin_file( 'classes/customize.class.php' );

			// includes classes
			do_action( 'ovic_options_includes' );
		}

		//
		// Enqueue admin scripts.
		public function enqueue_scripts()
		{

			do_action( 'ovic_before_enqueue' );

			if ( in_array( ovic_get_var( 'page' ), array( 'dokan', 'revslider', 'revslider_navigation' ) ) ) {
				return;
			}

			/* admin utilities */
			wp_enqueue_media();

			/* wp color picker */
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			/* framework core styles */
			wp_enqueue_style( 'ovic-options',
				OVIC::include_plugin_url( 'assets/css/options' . self::$min . '.css' ),
				array(), OVIC::$version
			);
			wp_enqueue_style( 'ovic-options-custom',
				OVIC::include_plugin_url( 'assets/css/options-custom' . self::$min . '.css' ),
				array(), OVIC::$version
			);

			/* rtl styles */
			if ( is_rtl() ) {
				wp_enqueue_style( 'ovic-options-rtl',
					OVIC::include_plugin_url( 'assets/css/options-rtl' . self::$min . '.css' ),
					array(), OVIC::$version
				);
			}

			/* framework core scripts */
			wp_register_script( 'ovic-plugins',
				OVIC::include_plugin_url( 'assets/js/options-plugins' . self::$min . '.js' ),
				array( 'jquery' ), OVIC::$version, true
			);

			wp_enqueue_script( 'ovic-options',
				OVIC::include_plugin_url( 'assets/js/options' . self::$min . '.js' ),
				array( 'jquery', 'ovic-plugins' ), OVIC::$version, true
			);

			wp_localize_script( 'ovic-options', 'ovic_vars', array(
					'color_palette' => apply_filters( 'ovic_color_palette', array() ),
					'i18n'          => array(
						// global localize
						'confirm'             => esc_html__( 'Are you sure?', 'ovic-addon-toolkit' ),
						'reset_notification'  => esc_html__( 'Restoring options.', 'ovic-addon-toolkit' ),
						'import_notification' => esc_html__( 'Importing options.', 'ovic-addon-toolkit' ),

						// chosen localize
						'typing_text'         => esc_html__( 'Please enter %s or more characters',
							'ovic-addon-toolkit' ),
						'searching_text'      => esc_html__( 'Searching...', 'ovic-addon-toolkit' ),
						'no_results_text'     => esc_html__( 'No results match', 'ovic-addon-toolkit' ),
					),
				)
			);

			do_action( 'ovic_after_enqueue' );
		}

		// Include field
		public static function maybe_include_field( $type = '' )
		{
			if ( ! class_exists( "OVIC_Field_{$type}" ) && class_exists( 'OVIC_Fields' ) ) {
				self::include_plugin_file( "fields/{$type}/{$type}.php" );
			}
		}

		//
		// Add a new framework field
		public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' )
		{
			// language for fields
			$languages = ovic_language_defaults();
			// Check for unallow fields
			if ( ! empty( $field['_notice'] ) ) {
				$field_type       = $field['type'];
				$field            = array();
				$field['content'] = sprintf( esc_html__( 'Ooops! This field type (%s) can not be used here, yet.', 'ovic-addon-toolkit' ), '<strong>' . $field_type . '</strong>' );
				$field['type']    = 'notice';
				$field['class']   = 'warning';
			}
			$output     = '';
			$depend     = '';
			$classname  = 'OVIC_Field_' . $field['type'];
			$unique     = ( ! empty( $unique ) ) ? $unique : '';
			$wrap_class = ( ! empty( $field['class'] ) ) ? ' ' . $field['class'] : '';
			$el_class   = ( ! empty( $field['title'] ) ) ? sanitize_title( $field['title'] ) : 'no-title';
			$hidden     = ( ! empty( $field['show_only_language'] ) && ( $field['show_only_language'] != $languages['current'] ) ) ? ' hidden' : '';
			$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' ovic-pseudo-field' : '';
			$field_type = ( ! empty( $field['type'] ) ) ? $field['type'] : '';
			if ( ! empty( $field['dependency'] ) ) {
				$dependency = $field['dependency'];
				$hidden     = ' hidden';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition  = implode( '|', array_column( $dependency, 1 ) );
					$data_value      = implode( '|', array_column( $dependency, 2 ) );
					$data_global     = implode( '|', array_column( $dependency, 3 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
				}

				$depend .= ' data-controller="' . $data_controller . '"';
				$depend .= ' data-condition="' . $data_condition . '"';
				$depend .= ' data-value="' . $data_value . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';
			}
			$output .= '<div class="ovic-field ovic-field-key-' . $el_class . ' ovic-field-' . $field_type . $is_pseudo . $wrap_class . $hidden . '"' . $depend . '>';
			if ( ! empty( $field['title'] ) ) {
				$subtitle = ( ! empty( $field['subtitle'] ) ) ? '<p class="ovic-text-subtitle">' . $field['subtitle'] . '</p>' : '';
				$subtitle = ( ! empty( $field['desc'] ) ) ? '<p class="ovic-text-subtitle">' . $field['desc'] . '</p>' : $subtitle;
				$output   .= '<div class="ovic-title"><h4>' . $field['title'] . '</h4>' . $subtitle . '</div>';
			}
			$output .= ( ! empty( $field['title'] ) ) ? '<div class="ovic-fieldset">' : '';
			$value  = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
			$value  = ( isset( $field['value'] ) ) ? $field['value'] : $value;
			self::maybe_include_field( $field['type'] );
			if ( class_exists( $classname ) ) {
				ob_start();
				$instance = new $classname( $field, $value, $unique, $where, $parent );
				$instance->render();
				$output .= ob_get_clean();
			} else {
				$output .= '<p>' . esc_html__( 'This field class is not available!', 'ovic-addon-toolkit' ) . '</p>';
			}
			$output .= ( ! empty( $field['title'] ) ) ? '</div>' : '';
			$output .= '<div class="clear"></div>';
			$output .= '</div>';

			return $output;
		}

		//
		// Create custom field class
		public static function createField( $field = array(), $is_ajax = false )
		{
			if ( ! isset( $field['type'] ) ) {
				return '';
			}
			$output    = '';
			$onload    = ( $is_ajax ) ? ' ovic-onload' : '';
			$output    .= '<div class="ovic-field-custom' . $onload . '">';
			$classname = 'OVIC_Field_' . $field['type'];
			self::maybe_include_field( $field['type'] );
			if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
				$instance = new $classname( $field );
				if ( method_exists( $classname, 'enqueue' ) ) {
					$instance->enqueue();
				}
				unset( $instance );
			}
			$output .= self::field( $field );
			$output .= '</div>';

			return $output;
		}
	}

	OVIC::instance();
}