<?php
/**
 *
 * Kuteshop post
 *
 */
if ( ! class_exists( 'Kuteshop_Category_Widget' ) ) {
	class Kuteshop_Category_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-kuteshop-category',
				'description' => 'Widget Category.',
			);
			parent::__construct( 'widget_kuteshop_category', '1 - Kuteshop Category', $widget_ops );
		}

		function widget( $args, $instance )
		{
			extract( $args );

			echo $args['before_widget'];

			$categories = array();
			if ( ! empty( $instance['category'] ) ) {
				$categories = $instance['category'];
			} else {
				$terms = get_terms( 'category' );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$categories = $terms;
				}
			}
			$slick = array(
				'slidesToShow'    => $instance['items'],
				'infinite'        => false,
				'vertical'        => true,
				'verticalSwiping' => true,
				'slidesMargin'    => 0,
			);
			?>
			<?php if ( ! empty( $instance['title'] ) ) : ?>
            <div class="widgettitle-wrap">
                <h2 class="widgettitle"><?php echo esc_html( $instance['title'] ); ?></h2>
            </div>
		<?php endif; ?>
            <ul class="category-list owl-slick" data-slick="<?php echo json_encode( $slick ); ?>">
				<?php foreach ( $categories as $key => $value ) : ?>
					<?php
					$term     = get_category( $value );
					$term_url = get_category_link( $value );
					?>
                    <li class="cat-item">
                        <a href="<?php echo esc_url( $term_url ); ?>">
							<?php echo esc_html( $term->name ); ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>
			<?php

			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance )
		{
			$instance             = $old_instance;
			$instance['title']    = $new_instance['title'];
			$instance['items']    = $new_instance['items'];
			$instance['category'] = $new_instance['category'];

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
					'items'    => '5',
					'category' => '',
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
					'id'         => $this->get_field_name( 'category' ),
					'name'       => $this->get_field_name( 'category' ),
					'type'       => 'select',
					'options'    => 'categories',
					'query_args' => array(
						'orderby' => 'name',
						'order'   => 'ASC',
					),
					'chosen'     => true,
					'multiple'   => true,
					'attributes' => array(
						'style' => 'width: 100%;',
					),
					'title'      => esc_html__( 'Category Post', 'kuteshop-toolkit' ),
				),
				$instance['category']
			);

			echo ovic_add_field(
				array(
					'id'    => $this->get_field_name( 'items' ),
					'name'  => $this->get_field_name( 'items' ),
					'type'  => 'number',
					'title' => esc_html__( 'Number Post show', 'kuteshop-toolkit' ),
				),
				$instance['items']
			);

			echo '</div>';
		}
	}
}
if ( ! function_exists( 'Kuteshop_Category_Widget_init' ) ) {
	function Kuteshop_Category_Widget_init()
	{
		register_widget( 'Kuteshop_Category_Widget' );
	}

	add_action( 'widgets_init', 'Kuteshop_Category_Widget_init', 2 );
}