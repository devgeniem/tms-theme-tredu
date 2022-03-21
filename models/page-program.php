<?php
/**
 * Template Name: Koulutushaku
 */

use TMS\Theme\Tredu\Traits\Pagination;
use TMS\Theme\Tredu\PostType\Program;
// use TMS\Theme\Taidemuseo\PostType\Artwork;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\DeliveryMethod;
// use TMS\Theme\Taidemuseo\Taxonomy\ArtworkType;

/**
 * PageArtwork
 */
class PageProgram extends BaseModel {

    use Pagination;

    /**
     * Template
     */
    const TEMPLATE = 'models/page-program.php';

    /**
     * Search input name.
     */
    const SEARCH_QUERY_VAR = 'program-search';

    /**
     * Artist category filter name.
     */
    const FILTER_PROGRAM_LOCATION_QUERY_VAR = 'program-location';

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get filter query var value
     *
     * @return int|null
     */
    protected static function get_filter_query_var( $query_var = '') {
        // self::FILTER_PROGRAM_LOCATION_QUERY_VAR
        $value = get_query_var( $query_var, false );
        return ! $value
            ? null
            : intval( $value );
    }

    /**
     * Return translated strings.
     *
     * @return array[]
     */
    public function strings() : array {
        return [
            'search'         => [
                'label'             => __( 'Search for program', 'tms-theme-tredu' ),
                'submit_value'      => __( 'Search', 'tms-theme-tredu' ),
                'input_placeholder' => __( 'Search query', 'tms-theme-tredu' ),
            ],
            'terms'          => [
                'show_all' => __( 'Show All', 'tms-theme-tredu' ),
            ],
            'no_results'     => __( 'No results', 'tms-theme-tredu' ),
            'filter'         => __( 'Filter', 'tms-theme-tredu' ),
            'sort'           => __( 'Sort', 'tms-theme-tredu' ),
            'art_categories' => __( 'Categories', 'tms-theme-tredu' ),
        ];
    }

    /**
     * Page title
     *
     * @return string
     */
    public function page_title() : string {
        return get_the_title();
    }

    /**
     * Page description
     *
     * @return string
     */
    public function page_description() : string {
        return get_field( 'description' ) ?? '';
    }

    /**
     * Return current search data.
     *
     * @return string[]
     */
    public function search() : array {
        $this->search_data        = new stdClass();
        $this->search_data->query = get_query_var( self::SEARCH_QUERY_VAR );

        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'action'            => get_post_type_archive_link( Program::SLUG ),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {
        // $categories = get_field( 'artwork_types' );

        // if ( empty( $categories ) || is_wp_error( $categories ) || 1 === count( $categories ) ) {
        //     return [];
        // }

        // $base_url   = get_the_permalink();
        // $categories = array_map( function ( $item ) use ( $base_url ) {
        //     return [
        //         'name'      => $item->name,
        //         'url'       => add_query_arg(
        //             [
        //                 self::FILTER_PROGRAM_LOCATION_QUERY_VAR => $item->term_id,
        //             ],
        //             $base_url
        //         ),
        //         'is_active' => $item->term_id === self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR ),
        //     ];
        // }, $categories );

        // array_unshift(
        //     $categories,
        //     [
        //         'name'      => __( 'All', 'tms-theme-base' ),
        //         'url'       => $base_url,
        //         'is_active' => null === self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR ),
        //     ]
        // );

        // return $categories;
    }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        $args = [
            'post_type' => Program::SLUG,
            'orderby'   => 'title',
            'order'     => 'ASC',
            'paged'     => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
        ];

        $locations = self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );
        // if ( empty( $locations ) ) {
        //     $locations = get_field( 'location' );
        //     $locations = ! empty( $locations ) ? array_map( fn( $c ) => $c->term_id, $locations ) : [];
        // }
        // error_log( 'TÄSSÄ TOKA' );
        // error_log( print_r( $categories, true ) );

        if ( ! empty( $locations ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => Location::SLUG,
                    'terms'    => $locations,
                ],
            ];
        }

        $s = self::get_search_query_var();

        if ( ! empty( $s ) ) {
            $args['s'] = $s;
        }

        $the_query = new WP_Query( $args );

        // $this->set_pagination_data( $the_query );

        $search_clause = self::get_search_query_var();
        $is_filtered   = $search_clause || self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );

        return [
            'posts'       => $this->format_posts( $the_query->get_posts() ),
            'is_filtered' => $is_filtered,
            // 'summary'     => $is_filtered ? $this->results_summary( $the_query->found_posts, $search_clause ) : false,
        ];
    }

    /**
     * Supply data for active filter hidden input.
     *
     * @return string[]
     */
    public function active_filter_data() : ?array {
        $active_filter = self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );

        return $active_filter ? [
            'name'  => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
            'value' => $active_filter,
        ] : null;
    }

    /**
     * Sort options
     *
     * @return array
     */
    public function sort_options() {
        return [];
    }

    /**
     * Format posts for view
     *
     * @param array $posts Array of WP_Post instances.
     *
     * @return array
     */
    protected function format_posts( array $posts ) : array {
        // $artist_map = $this->get_artist_map();

        return array_map( function ( $item ) {
            if ( has_post_thumbnail( $item->ID ) ) {
                $item->image = get_post_thumbnail_id( $item->ID );
            }

            $item->permalink = get_the_permalink( $item->ID );
            $item->fields    = get_fields( $item->ID );
            // $item->types     = wp_get_post_terms( $item->ID, ArtworkType::SLUG, [ 'fields' => 'names' ] );

            $locations = wp_get_post_terms( $item->ID, Location::SLUG, [ 'fields' => 'names' ] );

            if ( ! empty( $locations ) ) {
                $item->location = $locations[0];
            }

            $delivery_methods = $locations = wp_get_post_terms( $item->ID, DeliveryMethod::SLUG, [ 'fields' => 'names' ]  );

            if ( ! empty( $delivery_methods ) ) {
                $item->delivery_methods = $delivery_methods[0];
            }

            // if ( isset( $artist_map[ $item->ID ] ) ) {
            //     $item->artist = implode( ', ', $artist_map[ $item->ID ] );
            // }

            return $item;
        }, $posts );

        return $posts;
    }

    /**
     * Get artworks artists map
     *
     * @return array
     */
    // protected function get_artist_map() : array {
    //     $artists = Artist::get_all();

    //     if ( empty( $artists ) ) {
    //         return [];
    //     }

    //     $map = [];

    //     foreach ( $artists as $artist ) {
    //         $artworks = get_field( 'artwork', $artist->ID );

    //         if ( empty( $artworks ) ) {
    //             continue;
    //         }

    //         foreach ( $artworks as $artwork ) {
    //             if ( ! isset( $map[ $artwork->ID ] ) ) {
    //                 $map[ $artwork->ID ] = [];
    //             }

    //             $map[ $artwork->ID ][] = $artist->post_title;
    //         }
    //     }

    //     return $map;
    // }
}
