<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/* HOOK */
if ( !is_admin() ) {
	add_filter( 'wp_get_attachment_url', 'kuteshop_get_attachment_url', 10, 2 );
	add_filter( 'wp_get_attachment_image_attributes', 'kuteshop_lazy_attachment_image', 10, 3 );
	add_filter( 'wp_kses_allowed_html', 'kuteshop_kses_allowed_html', 10, 2 );
	add_filter( 'dokan_product_image_attributes', 'kuteshop_dokan_image_attributes', 10, 3 );
	add_filter( 'post_thumbnail_html', 'kuteshop_post_thumbnail_html', 10, 5 );
	add_filter( 'vc_wpb_getimagesize', 'kuteshop_wpb_getimagesize', 10, 3 );
}
/* SET SESSION */
if ( !function_exists( 'kuteshop_session' ) ) {
	function kuteshop_session()
	{
		if ( !isset( $_SESSION ) ) {
			session_start();
		}
	}

	add_action( 'init', 'kuteshop_session' );
}
/* GET OPTION */
if ( !function_exists( 'kuteshop_get_option' ) ) {
	function kuteshop_get_option( $option_name = '', $default = '' )
	{
		$get_value  = isset( $_GET[$option_name] ) ? $_GET[$option_name] : '';
		$get_option = get_option( '_cs_options' );
		if ( isset( $_GET[$option_name] ) ) {
			$get_option = $get_value;
			$default    = $get_value;
		}
		$options = apply_filters( 'kuteshop_get_option', $get_option, $option_name, $default );
		if ( !empty( $option_name ) && !empty( $options[$option_name] ) ) {
			$option = $options[$option_name];
			if ( is_array( $option ) && isset( $option['multilang'] ) && $option['multilang'] == true ) {
				if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
					if ( isset( $option[ICL_LANGUAGE_CODE] ) ) {
						return $option[ICL_LANGUAGE_CODE];
					}
				}
			}

			return $option;
		} else {
			return ( !empty( $default ) ) ? $default : null;
		}
	}
}
/* GET META */
if ( !function_exists( 'kuteshop_get_meta' ) ) {
	function kuteshop_get_meta( $meta_key, $meta_value )
	{
		$main_data            = '';
		$enable_theme_options = kuteshop_get_option( 'enable_theme_options' );
		$meta_data            = get_post_meta( get_the_ID(), $meta_key, true );
		if ( !empty( $meta_data[$meta_value] ) && $enable_theme_options == 1 ) {
			$main_data = $meta_data[$meta_value];
		}

		return $main_data;
	}
}
/**
 *
 * RESIZE IMAGE
 * svg: <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"></svg>
 **/
if ( !function_exists( 'kuteshop_dokan_image_attributes' ) ) {
	function kuteshop_dokan_image_attributes( $image_attributes )
	{
		$image_attributes['img']['data-src'] = array();

		return $image_attributes;
	}
}
if ( !function_exists( 'kuteshop_get_attachment_url' ) ) {
	function kuteshop_get_attachment_url( $url, $post_id )
	{
		if ( function_exists( 'jetpack_photon_url' ) ) {
			$url = jetpack_photon_url( $url );
		}

		return $url;
	}
}

if ( !function_exists( 'kuteshop_kses_allowed_html' ) ) {
	function kuteshop_kses_allowed_html( $allowedposttags, $context )
	{
		$allowedposttags['img']['data-src']    = true;
		$allowedposttags['img']['data-srcset'] = true;
		$allowedposttags['img']['data-sizes']  = true;

		return $allowedposttags;
	}
}

if ( !function_exists( 'kuteshop_lazy_attachment_image' ) ) {
	function kuteshop_lazy_attachment_image( $attr, $attachment, $size )
	{
		$enable_lazy = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
		$image_size  = apply_filters( 'woocommerce_gallery_image_size', 'woocommerce_single' );
		if ( $size == $image_size && class_exists( 'WooCommerce' ) ) {
			if ( is_product() ) {
				$enable_lazy = 0;
			}
		}
		if ( $enable_lazy == 1 ) {
			$data_img         = wp_get_attachment_image_src( $attachment->ID, $size );
			$img_lazy         = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $data_img[1] . "%20" . $data_img[2] . "%27%2F%3E";
			$attr['data-src'] = $attr['src'];
			$attr['src']      = $img_lazy;
			$attr['class']    .= ' lazy';
			if ( isset( $attr['srcset'] ) && $attr['srcset'] != '' ) {
				$attr['data-srcset'] = $attr['srcset'];
				$attr['data-sizes']  = $attr['sizes'];
				unset( $attr['srcset'] );
				unset( $attr['sizes'] );
			}
		}

		return $attr;
	}
}

if ( !function_exists( 'kuteshop_post_thumbnail_html' ) ) {
	function kuteshop_post_thumbnail_html( $html, $post_ID, $post_thumbnail_id, $size, $attr )
	{
		$enable_lazy = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
		if ( $enable_lazy == 1 ) {
			$html = '<figure>' . $html . '</figure>';
		}

		return $html;
	}
}

if ( !function_exists( 'kuteshop_wpb_getimagesize' ) ) {
	function kuteshop_wpb_getimagesize( $img, $attach_id, $params )
	{
		$enable_lazy = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
		if ( $enable_lazy == 1 ) {
			$img['thumbnail'] = '<figure>' . $img['thumbnail'] . '</figure>';
		}

		return $img;
	}
}

if ( !function_exists( 'kuteshop_resize_image' ) ) {
	function kuteshop_resize_image( $attach_id, $width, $height, $crop = false, $use_lazy = false )
	{
		$alt      = '';
		$img_lazy = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22{$width}%22%20height%3D%22{$height}%22%20viewBox%3D%220%200%20{$width}%20{$height}%22%3E%3C%2Fsvg%3E";
		// this is an attachment, so we have the ID
		$image_src = array();
		if ( $attach_id ) {
			$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
			$alt              = trim( strip_tags( get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) ) );
			$actual_file_path = get_attached_file( $attach_id );
			// this is not an attachment, let's use the image url
		}
		if ( !empty( $actual_file_path ) ) {
			$file_info = pathinfo( $actual_file_path );
			$extension = '.' . $file_info['extension'];
			// the image path without the extension
			$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
			$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
			// checking if the file size is larger than the target size
			// if it is smaller or the same size, stop right here and return
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					$vt_image        = array(
						'url'    => $cropped_img_url,
						'width'  => $width,
						'height' => $height,
						'img'    => '<img class="img-responsive" src="' . esc_url( $cropped_img_url ) . '" ' . image_hwstring( $width, $height ) . ' alt="' . $alt . '">',
					);
					if ( $use_lazy == true ) {
						$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $cropped_img_url ) . '" ' . image_hwstring( $width, $height ) . ' alt="' . $alt . '">';
					}

					return $vt_image;
				}
				if ( false == $crop ) {
					// calculate the size proportionaly
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
					// checking if the file already exists
					if ( file_exists( $resized_img_path ) ) {
						$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
						$vt_image        = array(
							'url'    => $resized_img_url,
							'width'  => $proportional_size[0],
							'height' => $proportional_size[1],
							'img'    => '<img class="img-responsive" src="' . esc_url( $resized_img_url ) . '" ' . image_hwstring( $proportional_size[0], $proportional_size[1] ) . ' alt="' . $alt . '">',
						);
						if ( $use_lazy == true ) {
							$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $resized_img_url ) . '" ' . image_hwstring( $proportional_size[0], $proportional_size[1] ) . ' alt="' . $alt . '">';
						}

						return $vt_image;
					}
				}
				// no cache files - let's finally resize it
				$img_editor = wp_get_image_editor( $actual_file_path );
				if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
					$image_no_crop = wp_get_attachment_image_src( $attach_id, array( $width, $height ) );
					$vt_image      = array(
						'url'    => $image_no_crop[0],
						'width'  => $image_no_crop[1],
						'height' => $image_no_crop[2],
						'img'    => '<img class="img-responsive" src="' . esc_url( $image_no_crop[0] ) . '" ' . image_hwstring( $image_no_crop[1], $image_no_crop[2] ) . ' alt="' . $alt . '">',
					);
					if ( $use_lazy == true ) {
						$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $image_no_crop[0] ) . '" ' . image_hwstring( $image_no_crop[1], $image_no_crop[2] ) . ' alt="' . $alt . '">';
					}

					return $vt_image;
				}
				$new_img_path = $img_editor->generate_filename();
				if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
					$image_no_crop = wp_get_attachment_image_src( $attach_id, array( $width, $height ) );
					$vt_image      = array(
						'url'    => $image_no_crop[0],
						'width'  => $image_no_crop[1],
						'height' => $image_no_crop[2],
						'img'    => '<img class="img-responsive" src="' . esc_url( $image_no_crop[0] ) . '" ' . image_hwstring( $image_no_crop[1], $image_no_crop[2] ) . ' alt="' . $alt . '">',
					);
					if ( $use_lazy == true ) {
						$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $image_no_crop[0] ) . '" ' . image_hwstring( $image_no_crop[1], $image_no_crop[2] ) . ' alt="' . $alt . '">';
					}

					return $vt_image;
				}
				if ( !is_string( $new_img_path ) ) {
					$image_no_crop = wp_get_attachment_image_src( $attach_id, array( $width, $height ) );
					$vt_image      = array(
						'url'    => $image_no_crop[0],
						'width'  => $image_no_crop[1],
						'height' => $image_no_crop[2],
						'img'    => '<img class="img-responsive" src="' . esc_url( $image_no_crop[0] ) . '" ' . image_hwstring( $image_no_crop[1], $image_no_crop[2] ) . ' alt="' . $alt . '">',
					);
					if ( $use_lazy == true ) {
						$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $image_no_crop[0] ) . '" ' . image_hwstring( $image_no_crop[1], $image_no_crop[2] ) . ' alt="' . $alt . '">';
					}

					return $vt_image;
				}
				$new_img_size = getimagesize( $new_img_path );
				$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
				// resized output
				$vt_image = array(
					'url'    => $new_img,
					'width'  => $new_img_size[0],
					'height' => $new_img_size[1],
					'img'    => '<img class="img-responsive" src="' . esc_url( $new_img ) . '" ' . image_hwstring( $new_img_size[0], $new_img_size[1] ) . ' alt="' . $alt . '">',
				);
				if ( $use_lazy == true ) {
					$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $new_img ) . '" ' . image_hwstring( $new_img_size[0], $new_img_size[1] ) . ' alt="' . $alt . '">';
				}

				return $vt_image;
			}
			// default output - without resizing
			$vt_image = array(
				'url'    => $image_src[0],
				'width'  => $image_src[1],
				'height' => $image_src[2],
				'img'    => '<img class="img-responsive" src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="' . $alt . '">',
			);
			if ( $use_lazy == true ) {
				$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="' . $alt . '">';
			}

			return $vt_image;
		}

		if ( !empty( $image_src ) ) {
			$vt_image = array(
				'url'    => $image_src[0],
				'width'  => $image_src[1],
				'height' => $image_src[2],
				'img'    => '<img class="img-responsive" src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="' . $alt . '">',
			);
			if ( $use_lazy == true ) {
				$vt_image['img'] = '<img class="img-responsive lazy" src="' . esc_attr( $img_lazy ) . '" data-src="' . esc_url( $image_src[0] ) . '" ' . image_hwstring( $image_src[1], $image_src[2] ) . ' alt="' . $alt . '">';
			}

			return $vt_image;
		}

		$placeholder_url = "https://via.placeholder.com/{$width}x{$height}?text={$width}x{$height}";

		return array(
			'url'    => $placeholder_url,
			'width'  => $width,
			'height' => $height,
			'img'    => '<img class="img-responsive" src="' . esc_url( $placeholder_url ) . '" ' . image_hwstring( $width, $height ) . ' alt="Placeholder">',
		);
	}
}

if ( !function_exists( 'kuteshop_product_query' ) ) {
	function kuteshop_product_query( $atts, $args = array(), $ignore_sticky_posts = 1 )
	{
		extract( $atts );
		$target             = isset( $target ) ? $target : 'recent-product';
		$meta_query         = WC()->query->get_meta_query();
		$args['meta_query'] = $meta_query;
		$args['post_type']  = 'product';
		if ( isset( $atts['taxonomy'] ) && $atts['taxonomy'] != '' ) {
			$args['tax_query'] =
				array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => array_map( 'sanitize_title', explode( ',', $atts['taxonomy'] )
						),
					),
				);
		}
		$args['post_status']         = 'publish';
		$args['ignore_sticky_posts'] = $ignore_sticky_posts;
		$args['suppress_filter']     = true;
		if ( isset( $atts['per_page'] ) && $atts['per_page'] ) {
			$args['posts_per_page'] = $atts['per_page'];
		}
		$ordering_args = WC()->query->get_catalog_ordering_args();

		$orderby = !empty( $atts['orderby'] ) ? $atts['orderby'] : $ordering_args['orderby'];
		$order   = !empty( $atts['order'] ) ? $atts['order'] : $ordering_args['order'];

		switch ( $target ):
			case 'best-selling' :
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				break;
			case 'top-rated' :
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = array(
					'meta_value_num' => $order,
					'ID'             => 'ASC',
				);
				break;
			case 'product-category' :
				$ordering_args   = WC()->query->get_catalog_ordering_args( $orderby, $order );
				$args['orderby'] = $ordering_args['orderby'];
				$args['order']   = $ordering_args['order'];
				break;
			case 'product-brand' :
				if ( isset( $atts['taxonomy_brand'] ) && $atts['taxonomy_brand'] != '' ) {
					$args['tax_query'] =
						array(
							array(
								'taxonomy' => 'product_brand',
								'field'    => 'slug',
								'terms'    => $atts['taxonomy_brand'],
							),
						);
				}
				$ordering_args   = WC()->query->get_catalog_ordering_args( $orderby, $order );
				$args['orderby'] = $ordering_args['orderby'];
				$args['order']   = $ordering_args['order'];
				break;
			case 'products' :
				$args['posts_per_page'] = -1;
				if ( !empty( $ids ) ) {
					$args['post__in'] = array_map( 'trim', explode( ',', $ids ) );
					$args['orderby']  = 'post__in';
				}
				if ( !empty( $skus ) ) {
					$args['meta_query'][] = array(
						'key'     => '_sku',
						'value'   => array_map( 'trim', explode( ',', $skus ) ),
						'compare' => 'IN',
					);
				}
				break;
			case 'featured_products' :
				$meta_query         = WC()->query->get_meta_query();
				$tax_query          = WC()->query->get_tax_query();
				$tax_query[]        = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				);
				$args['tax_query']  = $tax_query;
				$args['meta_query'] = $meta_query;
				break;
			case 'product_attribute' :
				//'recent-product'
				$args['tax_query'] = array(
					array(
						'taxonomy' => strstr( $atts['attribute'], 'pa_' ) ? sanitize_title( $atts['attribute'] ) : 'pa_' . sanitize_title( $atts['attribute'] ),
						'terms'    => array_map( 'sanitize_title', explode( ',', $atts['filter'] ) ),
						'field'    => 'slug',
					),
				);
				break;
			case 'on_new' :
				$newness            = apply_filters( 'theme_get_option', 'product_newness', 0 );    // Newness in days as defined by option
				$args['date_query'] = array(
					array(
						'after'     => '' . $newness . ' days ago',
						'inclusive' => true,
					),
				);
				if ( $orderby == '_sale_price' ) {
					$orderby = 'date';
					$order   = 'DESC';
				}
				$args['orderby'] = $orderby;
				$args['order']   = $order;
				break;
			case 'on_sale' :
				$product_ids_on_sale = wc_get_product_ids_on_sale();
				$args['post__in']    = array_merge( array( 0 ), $product_ids_on_sale );
				if ( $orderby == '_sale_price' ) {
					$orderby = 'date';
					$order   = 'DESC';
				}
				$args['orderby'] = $orderby;
				$args['order']   = $order;
				break;
			default :
				//'recent-product'
				$args['orderby'] = $orderby;
				$args['order']   = $order;
				if ( isset( $ordering_args['meta_key'] ) ) {
					$args['meta_key'] = $ordering_args['meta_key'];
				}
				// Remove ordering query arguments
				WC()->query->remove_ordering_args();
				break;
		endswitch;

		return $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts, $args['post_type'] ) );
	}
}