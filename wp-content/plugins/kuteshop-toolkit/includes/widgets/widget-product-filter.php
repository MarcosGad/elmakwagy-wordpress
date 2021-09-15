<?php
/**
 *
 * Kuteshop Product Filter
 *
 */
if (!class_exists('Product_Filter_Widget')) {
    class Product_Filter_Widget extends WP_Widget
    {
        function __construct()
        {
            $widget_ops = array(
                'classname'   => 'kuteshop_product_filter',
                'description' => 'Widget Product Filter.',
            );
            parent::__construct('widget_kuteshop_product_filter', '1 - Kuteshop Product Filter', $widget_ops);
        }

        public function get_product_attribute($attribute)
        {
            global $wpdb;
            $product_attribute = array();
            $attribute_name    = str_replace('pa_', '', $attribute);
            $attribute         = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s",
                $attribute_name));
            if (!empty($attribute)) {
                $product_attribute         = array(
                    'id'           => intval($attribute->attribute_id),
                    'name'         => $attribute->attribute_label,
                    'slug'         => wc_attribute_taxonomy_name($attribute->attribute_name),
                    'type'         => $attribute->attribute_type,
                    'order_by'     => $attribute->attribute_orderby,
                    'has_archives' => (bool) $attribute->attribute_public,
                );
                $product_attribute['size'] = isset($attribute->attribute_size) ? $attribute->attribute_size : '20x20';
            }

            return $product_attribute;
        }

        function widget($args, $instance)
        {
            $price_title    = esc_html__('PRICE', 'kuteshop-toolkit');
            $category_title = esc_html__('CATEGORIES', 'kuteshop-toolkit');
            extract($args);
            echo $args['before_widget'];
            if (!empty($instance['title'])) : ?>
                <h2 class="widgettitle"><?php echo esc_html($instance['title']); ?></h2>
            <?php endif; ?>
            <div class="filter-content">
                <?php
                the_widget('WC_Widget_Product_Categories',
                    array(
                        'title' => $category_title,
                        'count' => 1,
                    )
                );
                the_widget('WC_Widget_Price_Filter',
                    array(
                        'title' => $price_title,
                    )
                );
                if (!empty($instance['choose_attribute'])) {
                    foreach ($instance['choose_attribute'] as $value) {
                        $data      = explode('&', $value);
                        $attribute = $this->get_product_attribute($data[0]);
                        the_widget('Kuteshop_Layered_Nav_Widget',
                            array(
                                'title'        => $data[2],
                                'attribute'    => $data[0],
                                'query_type'   => 'AND',
                                'display_type' => !empty($attribute['type']) ? $attribute['type'] : 'select',
                            )
                        );
                    }
                }
                ?>
            </div>
            <?php
            echo $args['after_widget'];
        }

        function update($new_instance, $old_instance)
        {
            $instance                     = $old_instance;
            $instance['title']            = $new_instance['title'];
            $instance['choose_attribute'] = $new_instance['choose_attribute'];

            return $instance;
        }

        function form($instance)
        {
            $attribute_array      = array();
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if (!empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $tax) {
                    if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                        $attribute_array[$tax->attribute_name.'&'.$tax->attribute_type.'&'.$tax->attribute_label] = $tax->attribute_label;
                    }
                }
            }
            //
            // set defaults
            // -------------------------------------------------
            $instance = wp_parse_args(
                $instance,
                array(
                    'title'            => '',
                    'choose_attribute' => '',
                )
            );

            echo '<div class="ovic ovic-widgets ovic-fields">';

            echo ovic_add_field(
                array(
                    'id'    => $this->get_field_name('title'),
                    'name'  => $this->get_field_name('title'),
                    'type'  => 'text',
                    'title' => esc_html__('Title', 'kuteshop-toolkit'),
                ),
                $instance['title']
            );

            echo ovic_add_field(
                array(
                    'id'         => $this->get_field_name('choose_attribute'),
                    'name'       => $this->get_field_name('choose_attribute'),
                    'type'       => 'select',
                    'chosen'     => true,
                    'multiple'   => true,
                    'attributes' => array(
                        'style' => 'width: 100%;',
                    ),
                    'options'    => $attribute_array,
                    'title'      => esc_html__('Product attribute:', 'kuteshop-toolkit'),
                ),
                $instance['choose_attribute']
            );

            echo '</div>';
        }
    }
}
if (!function_exists('Product_Filter_Widget_init')) {
    function Product_Filter_Widget_init()
    {
        register_widget('Product_Filter_Widget');
    }

    add_action('widgets_init', 'Product_Filter_Widget_init', 2);
}