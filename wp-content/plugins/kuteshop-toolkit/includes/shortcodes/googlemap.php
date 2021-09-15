<?php
if ( ! class_exists( 'Kuteshop_Shortcode_Googlemap' ) ) {
	class Kuteshop_Shortcode_Googlemap extends Kuteshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'googlemap';
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();

		public static function generate_css( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_googlemap', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			$css .= '.' . $atts['googlemap_custom_id'] . '.kuteshop-google-maps { min-height:' . $atts['map_height'] . 'px;} ';

			return $css;
		}

		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'kuteshop_googlemap', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );
			$css_class   = array( 'kuteshop-google-maps' );
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['googlemap_custom_id'];
			$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', 'kuteshop_googlemap', $atts );
			$id          = uniqid();
			if ( wp_script_is( 'kuteshop-maps-api', 'registered' ) ) {
				wp_enqueue_script( 'kuteshop-maps-api' );
			}
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>"
                 id="az-google-maps-<?php echo esc_attr( $id ); ?>">
            </div>
            <script type="text/javascript">
                function init_Map_<?php echo esc_attr( $id ); ?>() {
                    var $hue             = '',
                        $saturation      = '',
                        $modify_coloring = false,
                        $kuteshop_map    = {
                            lat: <?php echo esc_attr( $atts['latitude'] ); ?>,
                            lng: <?php echo esc_attr( $atts['longitude'] ) ?>
                        };
                    if ( $modify_coloring === true ) {
                        var $styles = [
                            {
                                stylers: [
                                    { hue: $hue },
                                    { invert_lightness: false },
                                    { saturation: $saturation },
                                    { lightness: 1 },
                                    {
                                        featureType: "landscape.man_made",
                                        stylers: [ {
                                            visibility: "on"
                                        } ]
                                    }
                                ]
                            }, {
                                featureType: 'water',
                                elementType: 'geometry',
                                stylers: [
                                    { color: '#46bcec' }
                                ]
                            }
                        ];
                    }
                    var map = new google.maps.Map( document.getElementById( "az-google-maps-<?php echo esc_attr( $id ); ?>" ), {
                        zoom: <?php echo esc_attr( $atts['zoom'] ) ?>,
                        center: $kuteshop_map,
                        mapTypeId: google.maps.MapTypeId.<?php echo esc_attr( $atts['map_type'] ) ?>,
                        styles: $styles
                    } );

                    var contentString = '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="kuteshop-map-info">' +
                        '<h4 class="map-title"><?php echo esc_html( $atts['title'] ) ?></h4>' +
                        '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;<?php echo esc_html( $atts['address'] ) ?></span></div>' +
                        '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;<a href="tel:<?php echo esc_html( $atts['phone'] ) ?>"><?php echo esc_html( $atts['phone'] ) ?></a></span></div>' +
                        '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:<?php echo esc_html( $atts['email'] ) ?>">&nbsp;<?php echo esc_html( $atts['email'] ) ?></a></span></div> ' +
                        '</div>';

                    var infowindow = new google.maps.InfoWindow( {
                        content: contentString
                    } );

                    var marker = new google.maps.Marker( {
                        position: $kuteshop_map,
                        map: map
                    } );
                    marker.addListener( 'click', function () {
                        infowindow.open( map, marker );
                    } );
                }

                window.addEventListener( 'load',
                    function ( ev ) {
                        init_Map_<?php echo esc_attr( $id ); ?>();
                    }, false );
            </script>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Kuteshop_Shortcode_Googlemap', $html, $atts, $content );
		}
	}

	new Kuteshop_Shortcode_Googlemap();
}