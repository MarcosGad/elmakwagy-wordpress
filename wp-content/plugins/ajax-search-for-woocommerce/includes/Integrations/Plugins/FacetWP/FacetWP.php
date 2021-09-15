<?php

namespace DgoraWcas\Integrations\Plugins\FacetWP;

use  DgoraWcas\Helpers ;
/**
 * Integration with FacetWP
 *
 * Plugin URL: https://facetwp.com/
 * Author: FacetWP, LLC
 */
class FacetWP
{
    private static  $engine = 'dgwt_wcas' ;
    public  $search_terms ;
    public function init()
    {
        if ( !defined( 'FACETWP_VERSION' ) ) {
            return;
        }
        if ( version_compare( FACETWP_VERSION, '3.5.5' ) < 0 ) {
            return;
        }
        // Search page
        add_filter(
            'facetwp_query_args',
            array( $this, 'search_args' ),
            10,
            2
        );
        add_filter(
            'facetwp_pre_filtered_post_ids',
            array( $this, 'search_page' ),
            10,
            2
        );
        add_filter(
            'dgwt/wcas/search_bar/value',
            array( $this, 'restore_search_phrase' ),
            10,
            2
        );
        // Search facet
        add_filter( 'facetwp_facet_search_engines', array( $this, 'search_engines' ) );
        add_filter(
            'facetwp_facet_filter_posts',
            array( $this, 'search_facet' ),
            10,
            2
        );
    }
    
    /**
     * Prevent the default WP search from running when our plugin is enabled
     */
    function search_args( $args, $class )
    {
        
        if ( $class->is_search && isset( $class->http_params['get']['dgwt_wcas'] ) ) {
            $this->search_terms = $args['s'];
            unset( $args['s'] );
            $args['dgwt_wcas'] = $args['s'];
        }
        
        return $args;
    }
    
    /**
     * Use our engine to retrieve matching post IDs
     */
    public function search_page( $post_ids, $class )
    {
        if ( empty($this->search_terms) ) {
            return $post_ids;
        }
        if ( !dgoraAsfwFs()->is_premium() ) {
            $products_ids = Helpers::searchProducts( $this->search_terms );
        }
        $intersected_ids = [];
        // Speed up comparison
        $post_ids = array_flip( $post_ids );
        if ( !empty($products_ids) ) {
            foreach ( $products_ids as $post_id ) {
                if ( isset( $post_ids[$post_id] ) ) {
                    $intersected_ids[] = $post_id;
                }
            }
        }
        return ( empty($intersected_ids) ? [ 0 ] : $intersected_ids );
    }
    
    /**
     * Restore search phrase in search input
     *
     * @return string
     */
    public function restore_search_phrase( $phrase, $searchInstances )
    {
        if ( !empty($this->search_terms) ) {
            $phrase = esc_attr( $this->search_terms );
        }
        return $phrase;
    }
    
    /**
     * Add our engine to the search facet
     */
    public function search_engines( $engines )
    {
        $engines[self::$engine] = DGWT_WCAS_NAME;
        return $engines;
    }
    
    /**
     * Intercept search facets using our engine
     */
    public function search_facet( $return, $params )
    {
        $facet = $params['facet'];
        $selected_values = $params['selected_values'];
        $selected_values = ( is_array( $selected_values ) ? $selected_values[0] : $selected_values );
        $search_engine = ( isset( $facet['search_engine'] ) ? $facet['search_engine'] : '' );
        
        if ( 'search' == $facet['type'] && $search_engine === self::$engine ) {
            if ( empty($selected_values) ) {
                return 'continue';
            }
            if ( !dgoraAsfwFs()->is_premium() ) {
                $return = Helpers::searchProducts( $selected_values );
            }
        }
        
        return $return;
    }

}