<?php
/**
 * Handles taxonomies in admin
 *
 * @class    Ovic_Brand_Taxonomies
 * @version  2.3.10
 * @package  WooCommerce/Admin
 * @brand Class
 * @author   WooThemes
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Ovic_Brand_Taxonomies class.
 */
if ( !class_exists( 'Ovic_Brand_Taxonomies' ) ) {
	/**
	 * Add Widgets.
	 */
	require_once dirname( __FILE__ ) . '/product-brand-widget.php';

	class Ovic_Brand_Taxonomies
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			add_action( 'woocommerce_after_register_taxonomy', array( $this, 'register_product_taxonomy' ) );
			// Brand/term ordering
			add_action( 'create_term', array( $this, 'create_term' ), 5, 3 );
			add_action( 'delete_term', array( $this, 'delete_term' ), 5 );
			// Add form
			add_action( 'product_brand_add_form_fields', array( $this, 'add_brand_fields' ) );
			add_action( 'product_brand_edit_form_fields', array( $this, 'edit_brand_fields' ), 10 );
			add_action( 'created_term', array( $this, 'save_brand_fields' ), 10, 3 );
			add_action( 'edit_term', array( $this, 'save_brand_fields' ), 10, 3 );
			// Add columns
			add_filter( 'manage_edit-product_brand_columns', array( $this, 'product_brand_columns' ) );
			add_filter( 'manage_product_brand_custom_column', array( $this, 'product_brand_column' ), 10, 3 );
			// Add row actions.
			add_filter( 'product_brand_row_actions', array( $this, 'product_brand_row_actions' ), 10, 2 );
			add_filter( 'admin_init', array( $this, 'handle_product_brand_row_actions' ) );
			// Add brand permalink.
			add_action( 'current_screen', array( $this, 'conditonal_includes' ) );
			// Maintain hierarchy of terms
			add_filter( 'wp_terms_checklist_args', array( $this, 'disable_checked_ontop' ) );

			// Admin footer scripts for this product categories admin screen.
			add_action( 'admin_footer', array( $this, 'scripts_at_product_brand_screen_footer' ) );

			// Add tab brand single product.
			add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) );
		}

		function conditonal_includes()
		{
			$screen = get_current_screen();
			if ( in_array( $screen->id, array( 'options-permalink' ) ) ) {
				$this->permalink_settings_init();
				$this->permalink_settings_save();
			}
		}

		function permalink_settings_init()
		{
			// Add our settings
			add_settings_field(
				'ovic_taxonomy_brand_slug', // id
				esc_html__( 'Product brand base', 'kuteshop-toolkit' ), // setting title
				array( &$this, 'taxonomy_slug_input' ), // display callback
				'permalink', // settings page
				'optional'                                      // settings section
			);
		}

		function taxonomy_slug_input()
		{
			$permalinks = get_option( 'ovic_product_brand_permalinks' );
			$value      = 'product-brand';
			if ( isset( $permalinks['brand_rewrite_slug'] ) ) {
				$value = $permalinks['brand_rewrite_slug'];
			}
			?>
            <input name="ovic_taxonomy_brand_slug" type="text" class="regular-text code"
                   value="<?php echo esc_attr( $value ); ?>"
                   placeholder="<?php echo _x( 'product-brand', 'slug', 'kuteshop-toolkit' ) ?>"/>
			<?php
		}

		function permalink_settings_save()
		{
			if ( !is_admin() ) {
				return;
			}
			// We need to save the options ourselves; settings api does not trigger save for the permalinks page
			if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['ovic_taxonomy_brand_slug'] ) ) {
				// Cat and tag bases
				$ovic_taxonomy_brand_slug = wc_clean( $_POST['ovic_taxonomy_brand_slug'] );
				$permalinks               = get_option( 'ovic_product_brand_permalinks' );
				if ( !$permalinks ) {
					$permalinks = array();
				}
				$permalinks['brand_rewrite_slug'] = untrailingslashit( $ovic_taxonomy_brand_slug );
				update_option( 'ovic_product_brand_permalinks', $permalinks );
			}
		}

		function register_product_taxonomy()
		{
			$permalinks = get_option( 'ovic_product_brand_permalinks' );
			register_taxonomy(
				'product_brand',
				array( 'product' ),
				array(
					'hierarchical'          => true,
					'update_count_callback' => '_wc_term_recount',
					'label'                 => esc_html__( 'Brands', 'kuteshop-toolkit' ),
					'labels'                => array(
						'name'              => esc_html__( 'Product brands', 'kuteshop-toolkit' ),
						'singular_name'     => esc_html__( 'Brands', 'kuteshop-toolkit' ),
						'menu_name'         => esc_html_x( 'Brands', 'Admin menu name', 'kuteshop-toolkit' ),
						'search_items'      => esc_html__( 'Search brands', 'kuteshop-toolkit' ),
						'all_items'         => esc_html__( 'All brands', 'kuteshop-toolkit' ),
						'parent_item'       => esc_html__( 'Parent brand', 'kuteshop-toolkit' ),
						'parent_item_colon' => esc_html__( 'Parent brand:', 'kuteshop-toolkit' ),
						'edit_item'         => esc_html__( 'Edit brand', 'kuteshop-toolkit' ),
						'update_item'       => esc_html__( 'Update brand', 'kuteshop-toolkit' ),
						'add_new_item'      => esc_html__( 'Add new brand', 'kuteshop-toolkit' ),
						'new_item_name'     => esc_html__( 'New brand name', 'kuteshop-toolkit' ),
						'not_found'         => esc_html__( 'No brands found', 'kuteshop-toolkit' ),
					),
					'show_ui'               => true,
					'query_var'             => true,
					'capabilities'          => array(
						'manage_terms' => 'manage_product_terms',
						'edit_terms'   => 'edit_product_terms',
						'delete_terms' => 'delete_product_terms',
						'assign_terms' => 'assign_product_terms',
					),
					'rewrite'               => array(
						'slug'         => $permalinks['brand_rewrite_slug'],
						'with_front'   => false,
						'hierarchical' => true,
					),
				)
			);
		}

		function product_tabs( $tabs )
		{
			global $product;

			$terms = get_the_terms( $product->get_id(), 'product_brand' );
			if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
				$tabs['ovic_brands'] = array(
					'title'    => sprintf( esc_html__( 'Brands (%d)', 'kuteshop-toolkit' ), count( $terms ) ),
					'priority' => 50,
					'callback' => array( $this, 'tab_brand_content' ),
				);
			}

			return $tabs;
		}

		function tab_brand_content()
		{
			global $product;

			$terms = get_the_terms( $product->get_id(), 'product_brand' );
			if ( !empty( $terms ) && !is_wp_error( $terms ) ) : ?>
                <div class="product-tab-brands">
					<?php foreach ( $terms as $term ) : ?>
						<?php
						$term_url = get_term_link( $term->term_id, 'product_brand' );
						$logo     = get_term_meta( $term->term_id, 'logo_id', true );
						?>
                        <div class="brand-item">
							<?php if ( !empty( $logo ) ): ?>
                                <a href="<?php echo esc_url( $term_url ); ?>" class="term-thumbnail">
									<?php echo wp_get_attachment_image( $logo, 'full' ); ?>
                                </a>
							<?php endif; ?>
                            <h3 class="term-name">
                                <a href="<?php echo esc_url( $term_url ); ?>" class="brand-link">
									<?php echo esc_html( $term->name ); ?>
                                </a>
                            </h3>
							<?php if ( !empty( $term->description ) ): ?>
                                <div class="term-description">
									<?php echo wc_format_content( $term->description ); ?>
                                </div>
							<?php endif; ?>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif;
		}

		/**
		 * Order term when created (put in position 0).
		 *
		 * @param mixed  $term_id
		 * @param mixed  $tt_id
		 * @param string $taxonomy
		 */
		public function create_term( $term_id, $tt_id = '', $taxonomy = '' )
		{
			if ( 'product_brand' != $taxonomy && !taxonomy_is_product_attribute( $taxonomy ) ) {
				return;
			}
			$meta_name = taxonomy_is_product_attribute( $taxonomy ) ? 'order_' . esc_attr( $taxonomy ) : 'order';
			update_term_meta( $term_id, $meta_name, 0 );
		}

		/**
		 * When a term is deleted, delete its meta.
		 *
		 * @param mixed $term_id
		 */
		public function delete_term( $term_id )
		{
			global $wpdb;
			$term_id = absint( $term_id );
			if ( $term_id && get_option( 'db_version' ) < 34370 ) {
				$wpdb->delete( $wpdb->woocommerce_termmeta, array( 'woocommerce_term_id' => $term_id ), array( '%d' ) );
			}
		}

		/**
		 * Brand thumbnail fields.
		 */
		public function add_brand_fields()
		{
			?>
            <div class="form-field term-thumbnail-wrap">
                <label><?php esc_html_e( 'Brand Logo', 'kuteshop-toolkit' ); ?></label>
                <div class="field-image-select">
                    <div class="product_brand_thumbnail" style="float: left; margin-right: 10px;">
                        <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px"/>
                    </div>
                    <div style="line-height: 60px;">
                        <input type="hidden" class="product_brand_thumbnail_id" name="product_brand_logo_id"/>
                        <button type="button" class="upload_image_button button">
							<?php esc_html_e( 'Upload/Add image', 'kuteshop-toolkit' ); ?>
                        </button>
                        <button type="button" class="remove_image_button button" style="display: none;">
							<?php esc_html_e( 'Remove image', 'kuteshop-toolkit' ); ?>
                        </button>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="form-field term-thumbnail-wrap">
                <label><?php esc_html_e( 'Thumbnail', 'kuteshop-toolkit' ); ?></label>
                <div class="field-image-select">
                    <div class="product_brand_thumbnail" style="float: left; margin-right: 10px;">
                        <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px"/>
                    </div>
                    <div style="line-height: 60px;">
                        <input type="hidden" class="product_brand_thumbnail_id" name="product_brand_thumbnail_id"/>
                        <button type="button" class="upload_image_button button">
							<?php esc_html_e( 'Upload/Add image', 'kuteshop-toolkit' ); ?>
                        </button>
                        <button type="button" class="remove_image_button button" style="display: none;">
							<?php esc_html_e( 'Remove image', 'kuteshop-toolkit' ); ?>
                        </button>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
			<?php
		}

		/**
		 * Edit brand thumbnail field.
		 *
		 * @param mixed $term Term (brand) being edited
		 */
		public function edit_brand_fields( $term )
		{
			$logo_id         = absint( get_term_meta( $term->term_id, 'logo_id', true ) );
			$thumbnail_id    = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
			$logo_image      = $logo_id ? wp_get_attachment_thumb_url( $logo_id ) : wc_placeholder_img_src();
			$thumbnail_image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
			?>
            <tr class="form-field">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Brand Logo', 'kuteshop-toolkit' ); ?></label>
                </th>
                <td class="field-image-select">
                    <div class="product_brand_thumbnail" style="float: left; margin-right: 10px;">
                        <img src="<?php echo esc_url( $logo_image ); ?>" width="60px" height="60px"/>
                    </div>
                    <div style="line-height: 60px;">
                        <input type="hidden" class="product_brand_thumbnail_id" name="product_brand_logo_id"
                               value="<?php echo $logo_id; ?>"/>
                        <button type="button" class="upload_image_button button">
							<?php esc_html_e( 'Upload/Add logo', 'kuteshop-toolkit' ); ?>
                        </button>
                        <button type="button" class="remove_image_button button">
							<?php esc_html_e( 'Remove logo', 'kuteshop-toolkit' ); ?>
                        </button>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'kuteshop-toolkit' ); ?></label>
                </th>
                <td class="field-image-select">
                    <div class="product_brand_thumbnail" style="float: left; margin-right: 10px;">
                        <img src="<?php echo esc_url( $thumbnail_image ); ?>" width="60px" height="60px"/>
                    </div>
                    <div style="line-height: 60px;">
                        <input type="hidden" class="product_brand_thumbnail_id" name="product_brand_thumbnail_id"
                               value="<?php echo $thumbnail_id; ?>"/>
                        <button type="button" class="upload_image_button button">
							<?php esc_html_e( 'Upload/Add image', 'kuteshop-toolkit' ); ?>
                        </button>
                        <button type="button" class="remove_image_button button">
							<?php esc_html_e( 'Remove image', 'kuteshop-toolkit' ); ?>
                        </button>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
			<?php
		}

		/**
		 * save_brand_fields function.
		 *
		 * @param mixed  $term_id Term ID being saved
		 * @param mixed  $tt_id
		 * @param string $taxonomy
		 */
		public function save_brand_fields( $term_id, $tt_id = '', $taxonomy = '' )
		{
			if ( 'product_brand' === $taxonomy ) {
				if ( isset( $_POST['product_brand_thumbnail_id'] ) )
					update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_brand_thumbnail_id'] ) );
				if ( isset( $_POST['product_brand_logo_id'] ) )
					update_term_meta( $term_id, 'logo_id', absint( $_POST['product_brand_logo_id'] ) );
			}
		}

		/**
		 * Thumbnail column added to brand admin.
		 *
		 * @param mixed $columns
		 *
		 * @return array
		 */
		public function product_brand_columns( $columns )
		{
			$new_columns = array();
			if ( isset( $columns['cb'] ) ) {
				$new_columns['cb'] = $columns['cb'];
				unset( $columns['cb'] );
			}
			$new_columns['logo']  = esc_html__( 'Logo', 'kuteshop-toolkit' );
			$new_columns['thumb'] = esc_html__( 'Image', 'kuteshop-toolkit' );
			$columns              = array_merge( $new_columns, $columns );
			$columns['handle']    = '';

			return $columns;
		}

		/**
		 * Adjust row actions.
		 *
		 * @param array  $actions Array of actions.
		 * @param object $term Term object.
		 *
		 * @return array
		 */
		public function product_brand_row_actions( $actions, $term )
		{
			$default_brand_id = absint( get_option( 'default_product_brand', 0 ) );
			if ( $default_brand_id !== $term->term_id && current_user_can( 'edit_term', $term->term_id ) ) {
				$actions['make_default'] = sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					wp_nonce_url( 'edit-tags.php?action=make_default&amp;taxonomy=product_brand&amp;tag_ID=' . absint( $term->term_id ), 'make_default_' . absint( $term->term_id ) ),
					/* translators: %s: taxonomy term name */
					esc_attr( sprintf( esc_html__( 'Make &#8220;%s&#8221; the default brand', 'kuteshop-toolkit' ), $term->name ) ),
					esc_html__( 'Make default', 'kuteshop-toolkit' )
				);
			}

			return $actions;
		}

		/**
		 * Handle custom row actions.
		 */
		public function handle_product_brand_row_actions()
		{
			if ( isset( $_GET['action'], $_GET['tag_ID'], $_GET['_wpnonce'] ) && 'make_default' === $_GET['action'] ) {
				$make_default_id = absint( $_GET['tag_ID'] );
				if ( wp_verify_nonce( $_GET['_wpnonce'], 'make_default_' . $make_default_id ) && current_user_can( 'edit_term', $make_default_id ) ) {
					update_option( 'default_product_brand', $make_default_id );
				}
			}
		}

		/**
		 * Thumbnail column value added to brand admin.
		 *
		 * @param string $columns
		 * @param string $column
		 * @param int    $id
		 *
		 * @return string
		 */
		public function product_brand_column( $columns, $column, $id )
		{
			if ( 'thumb' === $column ) {
				// Prepend tooltip for default brand.
				$default_brand_id = absint( get_option( 'default_product_brand', 0 ) );
				if ( $default_brand_id === $id ) {
					$columns .= wc_help_tip( esc_html__( 'This is the default brand and it cannot be deleted. It will be automatically assigned to products with no brand.', 'kuteshop-toolkit' ) );
				}
				$thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );
				if ( $thumbnail_id ) {
					$thumbnail = wp_get_attachment_thumb_url( $thumbnail_id );
				} else {
					$thumbnail = wc_placeholder_img_src();
				}
				// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605
				$thumbnail = str_replace( ' ', '%20', $thumbnail );
				$columns   .= '<img src="' . esc_url( $thumbnail ) . '" alt="' . esc_attr__( 'Thumbnail', 'kuteshop-toolkit' ) . '" class="wp-post-image" height="48" width="48" />';
			}
			if ( 'logo' === $column ) {
				// Prepend tooltip for default brand.
				$default_brand_id = absint( get_option( 'default_product_brand', 0 ) );
				if ( $default_brand_id === $id ) {
					$columns .= wc_help_tip( esc_html__( 'This is the default brand and it cannot be deleted. It will be automatically assigned to products with no brand.', 'kuteshop-toolkit' ) );
				}
				$logo_id = get_term_meta( $id, 'logo_id', true );
				if ( $logo_id ) {
					$logo = wp_get_attachment_thumb_url( $logo_id );
				} else {
					$logo = wc_placeholder_img_src();
				}
				// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605
				$logo    = str_replace( ' ', '%20', $logo );
				$columns .= '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr__( 'Logo', 'kuteshop-toolkit' ) . '" class="wp-post-image" height="48" width="48" />';
			}
			if ( 'handle' === $column ) {
				$columns .= '<input type="hidden" name="term_id" value="' . esc_attr( $id ) . '" />';
			}

			return $columns;
		}

		/**
		 * Maintain term hierarchy when editing a product.
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		public function disable_checked_ontop( $args )
		{
			if ( !empty( $args['taxonomy'] ) && 'product_brand' === $args['taxonomy'] ) {
				$args['checked_ontop'] = false;
			}

			return $args;
		}

		/**
		 * Admin footer scripts for the product categories admin screen
		 *
		 * @return void
		 */
		public function scripts_at_product_brand_screen_footer()
		{
			if ( !isset( $_GET['taxonomy'] ) || 'product_brand' !== $_GET['taxonomy'] ) { // WPCS: CSRF ok, input var ok.
				return;
			}
			wp_enqueue_style( 'product-brand', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'product-brand.css', array(), '1.0' );
			wp_enqueue_script( 'product-brand', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'product-brand.js', array(), '1.0', true );
			wp_localize_script( 'product-brand', 'product_brand_params', array(
					'placeholder' => wc_placeholder_img_src(),
				)
			);
		}
	}

	new Ovic_Brand_Taxonomies();
}