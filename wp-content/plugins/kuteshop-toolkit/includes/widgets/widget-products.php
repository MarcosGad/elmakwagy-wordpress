<?php
/**
 *
 * Kuteshop products
 *
 */
if ( ! class_exists( 'Kuteshop_Products_Widget' ) ) {
	class Kuteshop_Products_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-products kuteshop-widget-products',
				'description' => 'Widget products.',
			);
			parent::__construct( 'widget_products', '1 - Kuteshop Products', $widget_ops );
		}

		function widget( $args, $instance )
		{
			extract( $args );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}
			$style                      = 3;
			$instance['owl_dots']       = true;
			$instance['owl_navigation'] = false;
			$instance['owl_number_row'] = $instance['rows'];

			$main_class         = array( 'kuteshop-products', $instance['style'] );
			$product_item_class = array( 'product-item', 'style-' . $style );
			$products           = kuteshop_product_query( $instance );
			$owl_settings       = apply_filters( 'generate_carousel_data_attributes', 'owl_', $instance );
			?>
            <div class="<?php echo implode( ' ', $main_class ); ?>">
				<?php if ( $products->have_posts() ): ?>
                    <ul class="product-list-owl owl-slick equal-container better-height" <?php echo esc_attr( $owl_settings ); ?>>
						<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <li <?php wc_product_class( $product_item_class ); ?>>
								<?php wc_get_template_part( 'product-styles/content-product-style', $style ); ?>
                            </li>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
                    </ul>
					<?php if ( ! empty( $instance['btn_text'] ) && $instance['view_all'] == true ): ?>
                        <div class="button-wrap">
                            <a href="<?php echo esc_url( $instance['btn_link'] ); ?>" class="button-link">
                                <?php echo esc_html( $instance['btn_text'] ); ?>
                            </a>
                        </div>
					<?php endif; ?>
				<?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'kuteshop-toolkit' ); ?></strong>
                    </p>
				<?php endif; ?>
            </div>
			<?php

			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance )
		{
			$instance             = $old_instance;
			$instance['title']    = $new_instance['title'];
			$instance['style']    = $new_instance['style'];
			$instance['per_page'] = $new_instance['per_page'];
			$instance['rows']     = $new_instance['rows'];
			$instance['order']    = $new_instance['order'];
			$instance['orderby']  = $new_instance['orderby'];
			$instance['target']   = $new_instance['target'];
			$instance['view_all'] = $new_instance['view_all'];
			$instance['btn_text'] = $new_instance['btn_text'];
			$instance['btn_link'] = $new_instance['btn_link'];

			return $instance;
		}

		function form( $instance )
		{
			//
			// set defaults
			// -------------------------------------------------
			$instance = wp_parse_args(
				$instance,
				array(
					'title'    => '',
					'style'    => 'style-01',
					'per_page' => '3',
					'rows'     => '3',
					'order'    => 'DESC',
					'orderby'  => 'date',
					'target'   => 'recent-product',
					'view_all' => '',
					'btn_text' => 'All Products',
					'btn_link' => '#',
				)
			);

			echo '<div class="ovic ovic-widgets ovic-fields">';

			echo ovic_add_field(
				array(
					'id'    => $this->get_field_name( 'title' ),
					'name'  => $this->get_field_name( 'title' ),
					'type'  => 'text',
					'title' => esc_html__( 'Title', 'kuteshop-toolkit' ),
				),
				$instance['title']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'style' ),
					'name'       => $this->get_field_name( 'style' ),
					'type'       => 'select',
					'options'    => array(
						'type-01' => esc_html__( 'Style 01', 'kuteshop-toolkit' ),
						'type-02' => esc_html__( 'Style 02', 'kuteshop-toolkit' ),
					),
					'attributes' => array(
						'style' => 'width: 100%;',
					),
					'title'      => esc_html__( 'Style Products', 'kuteshop-toolkit' ),
				),
				$instance['style']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'target' ),
					'name'       => $this->get_field_name( 'target' ),
					'type'       => 'select',
					'options'    => array(
						'best-selling'      => esc_html__( 'Best Selling Products', 'kuteshop-toolkit' ),
						'top-rated'         => esc_html__( 'Top Rated Products', 'kuteshop-toolkit' ),
						'recent-product'    => esc_html__( 'Recent Products', 'kuteshop-toolkit' ),
						'featured_products' => esc_html__( 'Featured Products', 'kuteshop-toolkit' ),
						'on_sale'           => esc_html__( 'On Sale', 'kuteshop-toolkit' ),
						'on_new'            => esc_html__( 'On New', 'kuteshop-toolkit' ),
					),
					'attributes' => array(
						'style' => 'width: 100%;',
					),
					'title'      => esc_html__( 'Target', 'kuteshop-toolkit' ),
				),
				$instance['target']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'orderby' ),
					'name'       => $this->get_field_name( 'orderby' ),
					'type'       => 'select',
					'options'    => array(
						'date'          => esc_html__( 'Date', 'kuteshop-toolkit' ),
						'ID'            => esc_html__( 'ID', 'kuteshop-toolkit' ),
						'author'        => esc_html__( 'Author', 'kuteshop-toolkit' ),
						'title'         => esc_html__( 'Title', 'kuteshop-toolkit' ),
						'modified'      => esc_html__( 'Modified', 'kuteshop-toolkit' ),
						'rand'          => esc_html__( 'Random', 'kuteshop-toolkit' ),
						'comment_count' => esc_html__( 'Comment count', 'kuteshop-toolkit' ),
						'menu_order'    => esc_html__( 'Menu order', 'kuteshop-toolkit' ),
						'_sale_price'   => esc_html__( 'Sale price', 'kuteshop-toolkit' ),
					),
					'attributes' => array(
						'style' => 'width: 100%;',
					),
					'title'      => esc_html__( 'Order By', 'kuteshop-toolkit' ),
				),
				$instance['orderby']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'order' ),
					'name'       => $this->get_field_name( 'order' ),
					'type'       => 'select',
					'options'    => array(
						'ASC'  => esc_html__( 'ASC', 'kuteshop-toolkit' ),
						'DESC' => esc_html__( 'DESC', 'kuteshop-toolkit' ),
					),
					'attributes' => array(
						'style' => 'width: 100%;',
					),
					'title'      => esc_html__( 'Order', 'kuteshop-toolkit' ),
				),
				$instance['order']
			);

			echo ovic_add_field(
				array(
					'id'    => $this->get_field_name( 'per_page' ),
					'name'  => $this->get_field_name( 'per_page' ),
					'type'  => 'number',
					'unit'  => esc_html__( 'item(s)', 'kuteshop-toolkit' ),
					'title' => esc_html__( 'Product per page', 'kuteshop-toolkit' ),
				),
				$instance['per_page']
			);

			echo ovic_add_field(
				array(
					'id'    => $this->get_field_name( 'rows' ),
					'name'  => $this->get_field_name( 'rows' ),
					'type'  => 'number',
					'unit'  => esc_html__( 'item(s)', 'kuteshop-toolkit' ),
					'title' => esc_html__( 'Rows', 'kuteshop-toolkit' ),
				),
				$instance['rows']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'view_all' ),
					'name'       => $this->get_field_name( 'view_all' ),
					'type'       => 'checkbox',
					'attributes' => array(
						'data-depend-id' => 'view_all',
					),
					'label'      => esc_html__( 'View All Button', 'kuteshop-toolkit' ),
				),
				$instance['view_all']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'btn_text' ),
					'name'       => $this->get_field_name( 'btn_text' ),
					'type'       => 'text',
					'dependency' => array( 'view_all', '==', 'true' ),
					'label'      => esc_html__( 'Button', 'kuteshop-toolkit' ),
				),
				$instance['btn_text']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'btn_link' ),
					'name'       => $this->get_field_name( 'btn_link' ),
					'type'       => 'text',
					'dependency' => array( 'view_all', '==', 'true' ),
					'label'      => esc_html__( 'Button Link', 'kuteshop-toolkit' ),
				),
				$instance['btn_link']
			);

			echo '</div>';
		}
	}
}
if ( ! function_exists( 'Products_Widget_init' ) ) {
	function Products_Widget_init()
	{
		register_widget( 'Kuteshop_Products_Widget' );
	}

	add_action( 'widgets_init', 'Products_Widget_init', 2 );
}