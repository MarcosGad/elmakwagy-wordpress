<?php
/**
 *
 * Kuteshop Banner
 *
 */
if ( ! class_exists( 'Kuteshop_Banner_Widget' ) ) {
	class Kuteshop_Banner_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-banner kuteshop-widget-banner',
				'description' => 'Widget banner.',
			);
			parent::__construct( 'kuteshop_banner', '1 - Kuteshop Banner', $widget_ops );
		}

		function widget( $args, $instance )
		{
			extract( $args );

			echo $args['before_widget'];

			$slick = '';
			$loop  = array();
			$class = 'content-banner ' . $instance['style'];
			if ( $instance['style'] != 'style-01' ) {
				$class .= ' owl-slick';
				$slick = array(
					'slidesToShow' => 1,
					'infinite'     => false,
					'arrows'       => false,
					'dots'         => true,
				);
			}
			if ( $instance['style'] == 'style-03' ) {
				$args_loop = array(
					'post_type'      => 'testimonial',
					'posts_per_page' => '-1',
					'post_status'    => 'publish',
					'orderby'        => 'post__in',
					'post__in'       => $instance['testimonials'],
				);
				$loop      = new WP_Query( $args_loop );
			}

			if ( ! empty( $instance['title'] ) ) : ?>
                <h2 class="widgettitle"><?php echo esc_html( $instance['title'] ); ?></h2>
			<?php endif; ?>
            <div class="<?php echo esc_attr( $class ); ?>"
                 data-slick="<?php echo esc_attr( json_encode( $slick ) ); ?>">
				<?php if ( $instance['style'] == 'style-01' ) : ?>
					<?php
					if ( ! empty( $instance['icon'] ) ) {
						echo '<figure class="icon">';
						echo '<img src="' . esc_url( $instance['icon']['url'] ) . '" alt="' . esc_attr( $instance['icon']['title'] ) . '">';
						echo '</figure>';
					}
					?>
					<?php if ( ! empty( $instance['main_title'] ) ): ?>
                        <h3 class="title">
							<?php echo esc_html( $instance['main_title'] ); ?>
                        </h3>
					<?php endif; ?>
					<?php if ( ! empty( $instance['desc'] ) ): ?>
                        <p class="desc">
							<?php echo esc_html( $instance['desc'] ); ?>
                        </p>
					<?php endif; ?>
					<?php if ( ! empty( $instance['button'] ) ): ?>
                        <div class="button-wrap">
                            <a href="<?php echo esc_url( $instance['link'] ); ?>" class="button-link">
								<?php echo esc_html( $instance['button'] ); ?>
                            </a>
                        </div>
					<?php endif; ?>
				<?php elseif ( $instance['style'] == 'style-02' ):
					if ( ! empty( $instance['gallery'] ) ) {
						$galeries = explode( ',', $instance['gallery'] );
						foreach ( (array) $galeries as $gallery ) {
							if ( ! empty( $gallery ) ) {
								echo '<figure>';
								echo wp_get_attachment_image( $gallery, 'full' );
								echo '</figure>';
							}
						}
					}
					?>
				<?php elseif ( $instance['style'] == 'style-03' ): ?>
					<?php if ( ! empty( $loop ) ): ?>
						<?php while ( $loop->have_posts() ) : $loop->the_post() ?>
							<?php
							$data_meta = get_post_meta( get_the_ID(), '_custom_testimonial_options', true );
							?>
                            <div class="item">
								<?php if ( ! empty( $data_meta['name_testimonial'] ) ): ?>
                                    <h3 class="name">
										<?php echo esc_html( $data_meta['name_testimonial'] ); ?>
                                    </h3>
								<?php endif; ?>
								<?php if ( ! empty( $data_meta['avatar_testimonial'] ) ): ?>
                                    <div class="avatar">
                                        <figure>
											<?php echo wp_get_attachment_image( $data_meta['avatar_testimonial'], 'full' ); ?>
                                        </figure>
                                    </div>
								<?php endif; ?>
								<?php if ( ! empty( get_the_excerpt() ) ): ?>
                                    <div class="excerpt">
										<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 20, esc_html__( '...', 'kuteshop-toolkit' ) ); ?>
                                    </div>
								<?php endif; ?>
                            </div>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				<?php endif; ?>
            </div>
			<?php

			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance )
		{
			$instance                 = $old_instance;
			$instance['title']        = $new_instance['title'];
			$instance['style']        = $new_instance['style'];
			$instance['testimonials'] = $new_instance['testimonials'];
			$instance['gallery']      = $new_instance['gallery'];
			$instance['icon']         = $new_instance['icon'];
			$instance['main_title']   = $new_instance['main_title'];
			$instance['desc']         = $new_instance['desc'];
			$instance['button']       = $new_instance['button'];
			$instance['link']         = $new_instance['link'];

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
					'title'        => '',
					'style'        => 'style-01',
					'testimonials' => '',
					'gallery'      => '',
					'icon'         => '',
					'main_title'   => '',
					'desc'         => '',
					'button'       => 'Read more',
					'link'         => '#',
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
						'style-01' => esc_html__( 'Text', 'kuteshop-toolkit' ),
						'style-02' => esc_html__( 'Gallery', 'kuteshop-toolkit' ),
						'style-03' => esc_html__( 'Testimonials', 'kuteshop-toolkit' ),
					),
					'attributes' => array(
						'data-depend-id' => 'style',
						'style'          => 'width: 100%;',
					),
					'title'      => esc_html__( 'Style', 'kuteshop-toolkit' ),
				),
				$instance['style']
			);

			echo ovic_add_field(
				array(
					'id'          => $this->get_field_name( 'testimonials' ),
					'name'        => $this->get_field_name( 'testimonials' ),
					'title'       => esc_html__( 'Testimonials', 'kuteshop-toolkit' ),
					'placeholder' => 'Select a Testimonials',
					'type'        => 'select',
					'options'     => 'posts',
					'query_args'  => array(
						'post_type'      => 'testimonial',
						'posts_per_page' => - 1,
					),
					'chosen'      => true,
					'multiple'    => true,
					'dependency'  => array( 'style', '==', 'style-03' ),
				),
				$instance['testimonials']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'gallery' ),
					'name'       => $this->get_field_name( 'gallery' ),
					'type'       => 'gallery',
					'dependency' => array( 'style', '==', 'style-02' ),
					'title'      => esc_html__( 'Gallery', 'kuteshop-toolkit' ),
				),
				$instance['gallery']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'icon' ),
					'name'       => $this->get_field_name( 'icon' ),
					'type'       => 'media',
					'dependency' => array( 'style', '==', 'style-01' ),
					'title'      => esc_html__( 'Icon', 'kuteshop-toolkit' ),
				),
				$instance['icon']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'main_title' ),
					'name'       => $this->get_field_name( 'main_title' ),
					'type'       => 'text',
					'dependency' => array( 'style', '==', 'style-01' ),
					'title'      => esc_html__( 'Title', 'kuteshop-toolkit' ),
				),
				$instance['main_title']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'desc' ),
					'name'       => $this->get_field_name( 'desc' ),
					'type'       => 'textarea',
					'dependency' => array( 'style', '==', 'style-01' ),
					'title'      => esc_html__( 'Descriptions', 'kuteshop-toolkit' ),
				),
				$instance['desc']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'button' ),
					'name'       => $this->get_field_name( 'button' ),
					'type'       => 'text',
					'default'    => 'Read more',
					'dependency' => array( 'style', '==', 'style-01' ),
					'title'      => esc_html__( 'Button Text', 'kuteshop-toolkit' ),
				),
				$instance['button']
			);

			echo ovic_add_field(
				array(
					'id'         => $this->get_field_name( 'link' ),
					'name'       => $this->get_field_name( 'link' ),
					'type'       => 'text',
					'dependency' => array( 'style', '==', 'style-01' ),
					'title'      => esc_html__( 'Link', 'kuteshop-toolkit' ),
				),
				$instance['link']
			);

			echo '</div>';
		}
	}
}
if ( ! function_exists( 'Kuteshop_Banner_Widget_init' ) ) {
	function Kuteshop_Banner_Widget_init()
	{
		register_widget( 'Kuteshop_Banner_Widget' );
	}

	add_action( 'widgets_init', 'Kuteshop_Banner_Widget_init', 2 );
}