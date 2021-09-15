<?php
/**
 *
 * Kuteshop post
 *
 */
if ( ! class_exists( 'Kuteshop_Post_Widget' ) ) {
	class Kuteshop_Post_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-recent-post kuteshop-widget-recent-post',
				'description' => 'Widget post.',
			);
			parent::__construct( 'widget_kuteshop_post', '1 - Kuteshop Post', $widget_ops );
		}

		function widget( $args, $instance )
		{
			extract( $args );

			echo $args['before_widget'];

			$args_loop = array(
				'post_type'           => 'post',
				'showposts'           => $instance['number'],
				'nopaging'            => 0,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'order'               => 'DESC',
			);
			if ( $instance['choose_post'] == '0' ) {
				if ( $instance['type_post'] == 'popular' ) {
					$args_loop['cat']      = $instance['category'];
					$args_loop['meta_key'] = 'kuteshop_post_views_count';
					$args_loop['olderby']  = 'meta_value_num';
				} else {
					$args_loop['cat'] = $instance['category'];
				}
			} else {
				$args_loop['post__in'] = $instance['ids'];
			}
			$loop_posts = new WP_Query( $args_loop );
			$slick      = array(
				'slidesToShow'  => 1,
				'infinite'      => true,
				'arrows'        => false,
				'autoplay'      => true,
				'autoplaySpeed' => 3000,
			);
			if ( ! empty( $instance['title'] ) ) : ?>
                <h2 class="widgettitle"><?php echo esc_html( $instance['title'] ); ?></h2>
			<?php endif; ?>
			<?php
			if ( $loop_posts->have_posts() ) : ?>
                <div class="kuteshop-blog equal-container better-height style1 owl-slick"
                     data-slick="<?php echo esc_attr( json_encode( $slick ) ); ?>">
					<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
                        <article <?php post_class( 'blog-item' ); ?>>
                            <div class="blog-inner">
								<?php get_template_part( 'templates/blog/blog-style/content-blog', 'style1' ); ?>
                            </div>
                        </article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
                </div>
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif;

			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance )
		{
			$instance                = $old_instance;
			$instance['ids']         = $new_instance['ids'];
			$instance['title']       = $new_instance['title'];
			$instance['number']      = $new_instance['number'];
			$instance['choose_post'] = $new_instance['choose_post'];
			$instance['type_post']   = $new_instance['type_post'];
			$instance['category']    = $new_instance['category'];

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
					'title'       => '',
					'number'      => '8',
					'choose_post' => '0',
					'ids'         => '',
					'type_post'   => '',
					'category'    => '',
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
					'id'         => $this->get_field_name( 'choose_post' ),
					'name'       => $this->get_field_name( 'choose_post' ),
					'type'       => 'select',
					'options'    => array(
						'0' => 'Loop Post',
						'1' => 'Single Post',
					),
					'attributes' => array(
						'data-depend-id' => 'choose_post',
					),
					'title'      => esc_html__( 'Choose Type Post', 'kuteshop-toolkit' ),
				),
				$instance['choose_post']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'ids' ),
					'name'       => $this->get_field_name( 'ids' ),
					'type'       => 'select',
					'options'    => 'posts',
					'query_args' => array(
						'post_type' => 'post',
						'orderby'   => 'post_date',
						'order'     => 'DESC',
					),
					'chosen'     => true,
					'multiple'   => true,
					'attributes' => array(
						'style' => 'width: 100%;',
					),
					'dependency' => array( 'choose_post', '==', '1' ),
					'title'      => esc_html__( 'Choose Type Post', 'kuteshop-toolkit' ),
				),
				$instance['ids']
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
					'dependency' => array( 'choose_post', '==', '0' ),
					'title'      => esc_html__( 'Category Post', 'kuteshop-toolkit' ),
				),
				$instance['category']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'type_post' ),
					'name'       => $this->get_field_name( 'type_post' ),
					'type'       => 'select',
					'options'    => array(
						'latest'  => 'Latest Post',
						'popular' => 'Popular Post',
					),
					'dependency' => array( 'choose_post', '==', '0' ),
					'title'      => esc_html__( 'Type Post', 'kuteshop-toolkit' ),
				),
				$instance['type_post']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'number' ),
					'name'       => $this->get_field_name( 'number' ),
					'type'       => 'number',
					'unit'       => esc_html__( 'item(s)', 'kuteshop-toolkit' ),
					'dependency' => array( 'choose_post', '==', '0' ),
					'title'      => esc_html__( 'Number Post', 'kuteshop-toolkit' ),
				),
				$instance['number']
			);

			echo '</div>';
		}
	}
}
if ( ! function_exists( 'Kuteshop_Post_Widget_init' ) ) {
	function Kuteshop_Post_Widget_init()
	{
		register_widget( 'Kuteshop_Post_Widget' );
	}

	add_action( 'widgets_init', 'Kuteshop_Post_Widget_init', 2 );
}