<?php
if (!class_exists('Kuteshop_Shortcode_SimpleSEO')) {
    class Kuteshop_Shortcode_SimpleSEO extends Kuteshop_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'simpleseo';
        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('kuteshop_simpleseo', $atts) : $atts;
            extract($atts);
            $css_class   = array('kuteshop-simpleseo');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_simpleseo', $atts);
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['title']) : ?>
                    <span class="title-seo"><?php echo esc_html($atts['title']); ?></span>
                <?php endif; ?>
                <?php if ($atts['style'] == 'style2') :
                    $galery = explode(',', $atts['partner_banner']);
                    if (!empty($galery)) : ?>
                        <ul class="content-seo">
                            <?php foreach ($galery as $value): ?>
                                <li class="seo-item">
                                    <?php echo wp_get_attachment_image($value, 'full'); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;
                else:
                    $items = vc_param_group_parse_atts($atts['items']);
                    foreach ($items as $item): ?>
                        <div class="seo_keyword">
                            <?php if (isset($item['title'])) : ?>
                                <span class="title-seo"><?php echo esc_html($item['title']); ?></span>
                            <?php endif; ?>
                            <?php if ($item['custom_links'] != '') : ?>
                                <ul class="content-seo">
                                    <?php
                                    $custom_links = vc_value_from_safe($item['custom_links']);
                                    $custom_links = explode(',', $custom_links);
                                    foreach ($custom_links as $custom_link) :
                                        $custom_args = explode('|', $custom_link);
                                        ?>
                                        <li class="seo-item">
                                            <a href="<?php echo esc_url($custom_args[1]); ?>"
                                               target="<?php echo esc_attr($item['custom_links_target']); ?>">
                                                <?php echo esc_html($custom_args[0]); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Kuteshop_Shortcode_SimpleSEO', $html, $atts, $content);
        }
    }

    new Kuteshop_Shortcode_SimpleSEO();
}