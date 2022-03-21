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
use TMS\Theme\Tredu\strings;

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
     * Location taxonomy filter name.
     */
    const FILTER_PROGRAM_LOCATION_QUERY_VAR = 'program-location';

    /**
     * Location taxonomy filter name.
     */
    const FILTER_DELIVERY_METHODS_QUERY_VAR = 'delivery-method';

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get filter query var values
     *
     * @return int|null
     */
    protected static function get_filter_query_var( $query_var = '') {
        $values = get_query_var( $query_var, false );
        return ! $values
        ? null
        : array_map( fn( $value ) => intval( $value ), explode( ',', $values) );
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
            // 'action'            => get_permalink(),
        ];
    }

    /**
     * Filters
     *
     * @return array
     */
    public function filters() {

        $filters = [];

        $location_terms = get_terms( [
            'taxonomy' => Location::SLUG,
            'hide_empty' => true,
            ],
        );

        $delivery_methods_terms = get_terms( [
            'taxonomy' => DeliveryMethod::SLUG,
            'hide_empty' => true,
            ],
        );

        // error_log( print_r( $location_terms, true ) );
        if ( ! empty( $location_terms ) ) {
            $filters['locations'] = [
                'query_var' => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
                'terms' => array_map( function( $term ) {
                    return [
                        'term_id' => $term->term_id,
                        'name' => $term->name,
                        'slug' => $term->slug
                     ];
                 } ,$location_terms )
                ];
        } 

        if ( ! empty( $delivery_methods_terms ) ) {
            $filters['delivery_methods'] = [
                'query_var' => self::FILTER_DELIVERY_METHODS_QUERY_VAR,
                'terms' => array_map( function( $term ) {
                    return [
                        'term_id' => $term->term_id,
                        'name' => $term->name,
                        'slug' => $term->slug
                     ];
                 } ,$delivery_methods_terms )
                ];
        } 

        // $locations = array_map( function ( $item ) {
        //     return [
        //         'name'      => $item->name,
        //         // 'url'       => add_query_arg(
        //         //     [
        //         //         self::FILTER_PROGRAM_LOCATION_QUERY_VAR => $item->term_id,
        //         //     ],
        //         //     $base_url
        //         // ),
        //         // 'is_active' => $item->term_id === self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR ),
        //     ];
        // }, $taxonomies );
        
        

        // array_unshift(
        //     $taxonomies,
        //     [
        //         'name'      => __( 'All', 'tms-theme-base' ),
        //         'url'       => $base_url,
        //         'is_active' => null === self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR ),
        //     ]
        // );

        return $filters;
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

       

        // Add taxonomies to tax query from request's query vars

        $locations = self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );
        $delivery_methods = self::get_filter_query_var( self::FILTER_DELIVERY_METHODS_QUERY_VAR );
        
        // if ( empty( $locations ) ) {
        //     $locations = get_field( 'location' );
        //     $locations = ! empty( $locations ) ? array_map( fn( $c ) => $c->term_id, $locations ) : [];
        // }
        
        $args['tax_query'] = [
            'relation' => 'AND' 
        ];
        if ( ! empty( $locations ) ) {
            $args['tax_query'][] = [
                'taxonomy' => Location::SLUG,
                'terms'    => $locations,
            ];
        }

        if ( ! empty( $delivery_methods ) ) {
            $args['tax_query'][] = [
                'taxonomy' => DeliveryMethod::SLUG,
                'terms'    => $delivery_methods,
            ];
        }

        $s = self::get_search_query_var();

        if ( ! empty( $s ) ) {
            $args['s'] = $s;
        }

        $the_query = new WP_Query( $args );

        $this->set_pagination_data( $the_query );

        $search_clause = self::get_search_query_var();
        $is_filtered   = $search_clause || self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );
       
        return [
            'posts'       => $this->format_posts( $the_query->get_posts() ),
            'is_filtered' => $is_filtered,
            'summary'     => $this->results_summary( $the_query->found_posts ),
        ];
    }

    /**
     * Supply data for active filter hidden input.
     *
     * @return string[]
     */
    // public function active_filter_data() : ?array {
    //     $active_filter = self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );

    //     return $active_filter ? [
    //         'name'  => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
    //         'value' => $active_filter,
    //     ] : null;
    // }

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

            return $item;
        }, $posts );

        return $posts;
    }


    /**
     * Set pagination data
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    protected function set_pagination_data( $wp_query ) : void {
        $per_page = get_option( 'posts_per_page' );
        $paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $this->pagination           = new stdClass();
        $this->pagination->page     = $paged;
        $this->pagination->per_page = $per_page;
        $this->pagination->items    = $wp_query->found_posts;
        $this->pagination->max_page = (int) ceil( $wp_query->found_posts / $per_page );
    }

    /**
     * Get results summary text.
     *
     * @param int    $result_count  Result count.
     * @param string $search_clause Search clause.
     *
     * @return string|bool
     */
    protected function results_summary( $result_count ) {

        $count_posts = wp_count_posts( Program::SLUG )->publish;
        $shown_txt = ( new \Strings() )->s()['program']['search']['results_shown'];

        $results_text = sprintf( '%1$s %2$s / %3$s',
            $shown_txt,
            $result_count,
            $count_posts
        );

        return $results_text;
    }

}
