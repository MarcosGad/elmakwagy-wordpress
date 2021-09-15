<?php if ( !defined('ABSPATH') ) {
    die;
} // Cannot access directly.
/**
 *
 * Field: map
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists('OVIC_Field_map') ) {
    class OVIC_Field_map extends OVIC_Fields
    {

        public $version = '1.5.1';
        public $cdn_url = 'https://cdn.jsdelivr.net/npm/leaflet@';

        public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
        {
            parent::__construct($field, $value, $unique, $where, $parent);
        }

        public function render()
        {

            $args = wp_parse_args($this->field, array(
                'placeholder'    => esc_html__('Search your address...', 'ovic-addon-toolkit'),
                'latitude_text'  => esc_html__('Latitude', 'ovic-addon-toolkit'),
                'longitude_text' => esc_html__('Longitude', 'ovic-addon-toolkit'),
                'address_field'  => '',
                'height'         => '',
            ));

            $value = wp_parse_args($this->value, array(
                'address'   => '',
                'latitude'  => '20',
                'longitude' => '0',
                'zoom'      => '2',
            ));

            $default_settings = array(
                'center'          => array( $value['latitude'], $value['longitude'] ),
                'zoom'            => $value['zoom'],
                'scrollWheelZoom' => false,
            );

            $settings = ( !empty($this->field['settings']) ) ? $this->field['settings'] : array();
            $settings = wp_parse_args($settings, $default_settings);
            $encoded  = htmlspecialchars(json_encode($settings));

            $style_attr  = ( !empty($args['height']) ) ? ' style="min-height:' . $args['height'] . ';"' : '';
            $placeholder = ( !empty($args['placeholder']) ) ? array( 'placeholder' => $args['placeholder'] ) : '';

            echo $this->field_before();

            if ( empty($args['address_field']) ) {
                echo '<div class="ovic--map-search">';
                echo '<input type="text" name="' . $this->field_name('[address]') . '" value="' . $value['address'] . '"' . $this->field_attributes($placeholder) . ' />';
                echo '</div>';
            } else {
                echo '<div class="ovic--address-field" data-address-field="' . $args['address_field'] . '"></div>';
            }

            echo '<div class="ovic--map-osm-wrap"><div class="ovic--map-osm" data-map="' . $encoded . '" ' . $style_attr . '></div></div>';

            echo '<div class="ovic--map-inputs">';

            echo '<div class="ovic--map-input">';
            echo '<label>' . $args['latitude_text'] . '</label>';
            echo '<input type="text" name="' . $this->field_name('[latitude]') . '" value="' . $value['latitude'] . '" class="ovic--latitude" />';
            echo '</div>';

            echo '<div class="ovic--map-input">';
            echo '<label>' . $args['longitude_text'] . '</label>';
            echo '<input type="text" name="' . $this->field_name('[longitude]') . '" value="' . $value['longitude'] . '" class="ovic--longitude" />';
            echo '</div>';

            echo '</div>';

            echo '<input type="hidden" name="' . $this->field_name('[zoom]') . '" value="' . $value['zoom'] . '" class="ovic--zoom" />';

            echo $this->field_after();

        }

        public function enqueue()
        {

            if ( !wp_script_is('ovic-leaflet') ) {
                wp_enqueue_script('ovic-leaflet', $this->cdn_url . $this->version . '/dist/leaflet.js',
                    array( 'ovic-options' ),
                    $this->version, true);
            }

            if ( !wp_style_is('ovic-leaflet') ) {
                wp_enqueue_style('ovic-leaflet', $this->cdn_url . $this->version . '/dist/leaflet.css', array(),
                    $this->version);
            }

            if ( !wp_script_is('jquery-ui-autocomplete') ) {
                wp_enqueue_script('jquery-ui-autocomplete');
            }

        }

    }
}
