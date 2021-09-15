<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Tabs' ) ) {
	class Kuteshop_Shortcode_Tabs extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'tabs';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public static function generate_css( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_tabs', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css        = '';
			$tabs_color = $atts['tab_color'] != '' ? $atts['tab_color'] : '#000';
			if ( $atts['style'] == 'style1' ) {
				$css .= ' .kuteshop-tabs.style1.' . $atts['tabs_custom_id'] . ' .tab-link li.active a,
                .kuteshop-tabs.style1.' . $atts['tabs_custom_id'] . ' .tab-link li:hover a
                { 
                    background-color: ' . $tabs_color . ';
                } 
                .kuteshop-tabs.style1.' . $atts['tabs_custom_id'] . ' .tab-head
                { 
                    border-color: ' . $tabs_color . ';
                }
                ';
			}
			if ( $atts['style'] == 'style3' ) {
				$css .= ' .kuteshop-tabs.style3.' . $atts['tabs_custom_id'] . ' .tab-link li:hover a,
                .kuteshop-tabs.style3.' . $atts['tabs_custom_id'] . ' .tab-link li.active a,
                .kuteshop-tabs.style3.' . $atts['tabs_custom_id'] . ' .filter-tabs .slick-slide a.cat-active,
                .kuteshop-tabs.style3.' . $atts['tabs_custom_id'] . ' .filter-tabs .slick-slide a.cat-filter:hover
                {
                    color: ' . $tabs_color . ';
                }
                .kuteshop-tabs.style3.' . $atts['tabs_custom_id'] . ' .tab-head .kuteshop-title
                { 
                    background-color: ' . $tabs_color . ';
                }
                .kuteshop-tabs.style3.' . $atts['tabs_custom_id'] . ' .tab-head
                { 
                    border-color: ' . $tabs_color . ';
                }
                ';
			}
			if ( $atts['style'] == 'style5' ) {
				$css .= ' .kuteshop-tabs.style5.' . $atts['tabs_custom_id'] . ' .tab-head .kuteshop-title,
                .kuteshop-tabs.style5.' . $atts['tabs_custom_id'] . ' .tab-link li:hover a,
                .kuteshop-tabs.style5.' . $atts['tabs_custom_id'] . ' .tab-link li.active a
                { 
                    border-color: ' . $tabs_color . ';
                }
                ';
			}
			if ( $atts['style'] == 'style6' ) {
				$css .= ' .kuteshop-tabs.style6.' . $atts['tabs_custom_id'] . ' .tab-link li:hover a,
                .kuteshop-tabs.style6.' . $atts['tabs_custom_id'] . ' .tab-link li.active a
                {
                    color: ' . $tabs_color . ';
                }
                .kuteshop-tabs.style6.' . $atts['tabs_custom_id'] . ' .tab-head .kuteshop-title
                { 
                    background-color: ' . $tabs_color . ';
                }
                .kuteshop-tabs.style6.' . $atts['tabs_custom_id'] . ' .tab-head
                { 
                    border-color: ' . $tabs_color . ';
                }
                ';
			}
			if ( $atts['style'] == 'style9' ) {
				$css .= '  .kuteshop-tabs.style9.' . $atts['tabs_custom_id'] . ' .tab-link li:hover a,
                .kuteshop-tabs.style9.' . $atts['tabs_custom_id'] . ' .tab-link li.active a
                {
                    color: ' . $tabs_color . ';
                }
                .kuteshop-tabs.style9.' . $atts['tabs_custom_id'] . ' .filter-tabs::before,
                .kuteshop-tabs.style9.' . $atts['tabs_custom_id'] . '.cat-active .tab-head .toggle-category::before,
                .kuteshop-tabs.style9.' . $atts['tabs_custom_id'] . ' .tab-head .toggle-category:hover::before
                { 
                    background-color: ' . $tabs_color . ';
                }
                .kuteshop-tabs.style9.' . $atts['tabs_custom_id'] . ' .tab-head
                { 
                    border-color: ' . $tabs_color . ';
                }
                ';
			}

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_tabs', $atts ) : $atts;
			// Extract shortcode parameters.
			extract(
				shortcode_atts(
					$this->default_atts,
					$atts
				)
			);
			$css_class   = array( 'kuteshop-tabs' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['style'];
			$css_class[] = $atts['tabs_custom_id'];
			if ( $atts['style'] == 'style6' ) {
				$css_class[] = $atts['tab_position'];
			}
			if ( $atts['short_title'] == 1 ) {
				$css_class[] = 'short-text';
			}
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_tabs', $atts );
			$sections    = self::get_all_attributes( 'vc_tta_section', $content );
			$rand        = uniqid();
			ob_start();
			$atts['time_countdown'] = apply_filters( 'ovic_change_datetime_countdown', $atts['time_countdown'], 0 );
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
                    <div class="tab-head">
						<?php if ( $atts['style'] == 'style13' && $atts['use_tabs_filter'] == true ) : ?>
                            <div class="toggle-category">
                                <div class="toggle-inner">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $atts['tab_title'] ): ?>
                            <h2 class="kuteshop-title">
								<?php if ( $atts['use_tabs_icon'] == true ): ?>
									<?php if ( $atts['icon_image'] ) : ?>
                                        <span>
                                            <?php echo wp_get_attachment_image( $atts['icon_image'], 'full' ); ?>
                                        </span>
									<?php else: ?>
                                        <span class="icon <?php echo esc_attr( $atts[ 'icon_' . $atts['icon_type'] ] ); ?>"></span>
									<?php endif; ?>
								<?php endif;
								if ( $atts['style'] == 'style9' && $atts['use_tabs_filter'] == true ) : ?>
                                    <div class="toggle-category">
                                        <div class="toggle-inner">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
								<?php endif;
								if ( $atts['style'] == 'style4' ) :
									$character = str_split( $atts['tab_title'] );
									foreach ( $character as $value ) {
										if ( $value == ' ' ) {
											echo '<span class="space"></span>';
										} else {
											echo '<span class="text">' . esc_html( $value ) . '</span>';
										}
									}
								else : ?>
                                    <span class="text"><?php echo esc_html( $atts['tab_title'] ); ?></span>
								<?php endif; ?>
                            </h2>
						<?php endif;
						$class_carousel_tabs = '';
						$owl_settings_tabs   = '';
						$match               = array(
							'style2',
							'style12',
							'style14',
						);
						if ( count( $sections ) > $atts['tabs_ls_items'] && in_array( $atts['style'], $match ) ) {
							$class_carousel_tabs = 'owl-slick';
							if ( $atts['style'] == 'style12' ) {
								$atts['tabs_vertical']            = 'true';
								$atts['tabs_responsive_vertical'] = 768;
							}
							$owl_settings_tabs = apply_filters( 'generate_carousel_data_attributes', 'tabs_', $atts );
						}
						if ( $atts['style'] == 'style12' ) {
							$tab_link_width  = 156;
							$tab_link_height = 86;
						} else {
							$tab_link_width  = 146;
							$tab_link_height = 50;
						}
						?>
						<?php
						$tab_link_button        = vc_build_link( $atts['tab_link_button'] );
						$tab_link_button['url'] = apply_filters( 'ovic_shortcode_vc_link', $tab_link_button['url'] );
						?>
						<?php if ( $tab_link_button['url'] ) : ?>
                            <div class="tab-link-button">
                                <a href="<?php echo esc_url( $tab_link_button['url'] ); ?>"
                                   target="<?php echo esc_attr( $tab_link_button['target'] ); ?>">
									<?php echo esc_html( $tab_link_button['title'] ); ?>
                                </a>
                            </div>
						<?php endif; ?>
                        <ul class="tab-link <?php echo esc_attr( $class_carousel_tabs ); ?>" <?php echo esc_attr( $owl_settings_tabs ); ?>>
							<?php foreach ( $sections as $key => $section ): ?>
                                <li class="<?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>"
									<?php if ( $atts['style'] == 'style8' ) : ?>
										<?php $percent = 100 / intval( count( $sections ) ); ?>
                                        style="width: <?php echo esc_attr( $percent ); ?>%"
									<?php endif; ?>>
                                    <a <?php echo $key == $atts['active_section'] ? 'class="loaded"' : ''; ?>
                                            data-ajax="<?php echo esc_attr( $atts['ajax_check'] ) ?>"
                                            data-animate="<?php echo esc_attr( $atts['css_animation'] ); ?>"
                                            data-section="<?php echo esc_attr( $section['tab_id'] ); ?>"
                                            data-id="<?php echo get_the_ID(); ?>"
                                            href="#<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
										<?php if ( isset( $section['title_image'] ) ) :
											$lazy_check = kuteshop_get_option( 'kuteshop_theme_lazy_load' ) == 1 ? true : false;
											$image_thumb = kuteshop_resize_image( $section['title_image'], $tab_link_width, $tab_link_height, true, $lazy_check );
											?>
                                            <figure><?php echo wp_specialchars_decode( $image_thumb['img'] ); ?></figure>
										<?php else : ?>
                                            <span><?php echo isset( $section['title'] ) ? esc_html( $section['title'] ) : ''; ?></span>
										<?php endif; ?>
                                    </a>
                                </li>
							<?php endforeach; ?>
                        </ul>
						<?php if ( $atts['time_countdown'] && $atts['style'] == 'style4' ) : ?>
                            <div class="product-countdown style3">
                                <div class="kuteshop-countdown"
                                     data-datetime="<?php echo esc_attr( $atts['time_countdown'] ); ?>">
                                </div>
                            </div>
						<?php endif;
						if ( $atts['style'] == 'style1' || $atts['style'] == 'style3' || $atts['style'] == 'style6' || $atts['style'] == 'style9' ) : ?>
                            <div class="tab-control">
                                <span class="flaticon-escalator icon"></span>
                                <a class="section-up" href="#"><span class="fa fa-angle-up"></span></a>
                                <a class="section-down" href="#"><span class="fa fa-angle-down"></span></a>
                            </div>
						<?php endif; ?>
                    </div>
                    <div class="content-tabs <?php echo ( $atts['use_tabs_filter'] == true ) ? esc_attr( 'has-filter' ) : ''; ?>">
						<?php if ( $atts['use_tabs_filter'] == true ) :
							$data_image = '';
							if ( $atts['tabs_banner'] && $atts['style'] != 'style6' ) {
								$data_url   = wp_get_attachment_image_url( $atts['tabs_banner'], 'full' );
								$data_image = 'data-src=' . $data_url . '';
							}
							$categories              = explode( ',', $atts['taxonomy'] );
							$class_carousel          = 'owl-slick';
							$atts['cats_loop']       = false;
							$atts['cats_slidespeed'] = '300';
							if ( $atts['style'] != 'style6' && $atts['style'] != 'style13' ) {
								$atts['cats_vertical']            = true;
								$atts['cats_responsive_rows']     = 0;
								$atts['cats_responsive_vertical'] = 1200;
								$atts['cats_navigation']          = 'true';
							}
							if ( $atts['style'] == 'style9' ) {
								$atts['cats_responsive_vertical'] = 992;
							}
							$owl_settings = apply_filters( 'generate_carousel_data_attributes', 'cats_', $atts );
							?>
                            <div class="filter-tabs lazy" <?php echo esc_attr( $data_image ); ?>
                                 data-filter="<?php echo esc_attr( $atts['ajax_filter'] ); ?>">
                                <div class="category-filter <?php echo esc_attr( $class_carousel ); ?>" <?php echo esc_attr( $owl_settings ); ?>>
									<?php foreach ( $categories as $category ) :
										$term = get_term_by( 'slug', $category, 'product_cat' );
										if ( ! empty( $term ) && ! is_wp_error( $term ) ) :
											$term_link = get_term_link( $term->term_id, 'product_cat' );
											$data_meta = get_term_meta( $term->term_id, '_custom_taxonomy_options', true );
											?>
                                            <div>
                                                <a data-cat="<?php echo esc_attr( $category ); ?>"
                                                   data-id="<?php echo get_the_ID(); ?>"
                                                   href="<?php echo esc_url( $term_link ); ?>"
                                                   class="cat-filter">
													<?php
													if ( isset( $data_meta['icon_taxonomy'] ) && $atts['style'] == 'style9' ) {
														echo '<i class="' . esc_attr( $data_meta['icon_taxonomy'] ) . '"></i>';
													}
													echo esc_html( $term->name ); ?>
                                                </a>
                                            </div>
										<?php endif;
									endforeach; ?>
                                </div>
								<?php if ( $atts['tabs_banner'] && $atts['style'] == 'style6' ) :
									$kuteshop_blog_lazy = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
									$lazy_check         = $kuteshop_blog_lazy == 1 ? true : false;
									$image_thumb        = kuteshop_resize_image( $atts['tabs_banner'], 370, 349, true, $lazy_check );
									echo '<figure>';
									echo wp_specialchars_decode( $image_thumb['img'] );
									echo '</figure>';
								endif; ?>
                            </div>
						<?php endif; ?>
                        <div class="tab-container">
							<?php foreach ( $sections as $key => $section ): ?>
                                <div class="tab-panel <?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>"
                                     id="<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
									<?php if ( $atts['ajax_check'] == '1' ) :
										echo $key == $atts['active_section'] ? do_shortcode( $section['content'] ) : '';
									else :
										echo do_shortcode( $section['content'] );
									endif; ?>
                                </div>
							<?php endforeach; ?>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Tabs', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Tabs();
}