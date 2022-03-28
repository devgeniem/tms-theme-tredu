<?php
/**
 * Template Name: Koulutushaku
 */

use TMS\Theme\Tredu\Traits\Pagination;
use TMS\Theme\Tredu\PostType\Program;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\DeliveryMethod;
use TMS\Theme\Tredu\Taxonomy\Profession;
use TMS\Theme\Tredu\Taxonomy\ProgramType;
use TMS\Theme\Tredu\Taxonomy\EducationalBackground;
use TMS\Theme\Tredu\Images;

// use TMS\Theme\Tredu\strings;

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
     * Delivery method taxonomy filter name.
     */
    const FILTER_DELIVERY_METHODS_QUERY_VAR = 'delivery-method';

    /**
     * Profession taxonomy filter name.
     */
    const FILTER_PROFESSION_QUERY_VAR = 'profession';

     /**
     * Program type taxonomy filter name.
     */
    const FILTER_PROGRAM_TYPE_QUERY_VAR = 'program-type';

    /**
     * Educational background taxonomy filter name.
     */
    const FILTER_EDUCATIONAL_BACKGROUND_QUERY_VAR = 'educational-background';

     /**
     * Ongoing filter name.
     */
    const FILTER_ONGOING_QUERY_VAR = 'ongoing';
    
    /**
     * Posts per page
     */
    const POSTS_PER_PAGE = 1;
    

    /**
     * Get search query var value
     *
     * @return mixed
     */
    protected static function get_search_query_var() {
        return get_query_var( self::SEARCH_QUERY_VAR, false );
    }

    /**
     * Get ongoing query var value
     *
     * @return mixed
     */
    protected static function get_ongoing_query_var() {
       
        return get_query_var( self::FILTER_ONGOING_QUERY_VAR, false );
    }

    /**
     * Get filter query var values
     *
     * @return array|null
     */
    protected static function get_filter_query_var( $query_var = '') {
        $values = get_query_var( $query_var, false );
        $values = is_array( $values ) ? array_filter( $values ) : false;
        return empty( $values ) 
        ? null
        : $values;
    }

    /**
     * Get taxonomy slugs with query vars
     *
     * @return array
     */
    protected static function get_taxonomies_with_slugs() {

        $taxonomies_with_slugs = [ 
            Profession::SLUG => self::FILTER_PROFESSION_QUERY_VAR,
            ProgramType::SLUG => self::FILTER_PROGRAM_TYPE_QUERY_VAR,
            Location::SLUG => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
            EducationalBackground::SLUG => self::FILTER_EDUCATIONAL_BACKGROUND_QUERY_VAR,
            DeliveryMethod::SLUG => self::FILTER_DELIVERY_METHODS_QUERY_VAR, 
        ];
        
        return $taxonomies_with_slugs;
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
            'program'        => ( new \Strings() )->s()['program'],
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
        $this->search_data->ongoing = get_query_var( self::FILTER_ONGOING_QUERY_VAR );
        return [
            'input_search_name' => self::SEARCH_QUERY_VAR,
            'current_search'    => $this->search_data->query,
            'checkbox_search_name' => self::FILTER_ONGOING_QUERY_VAR,
            'only_ongoing' => $this->search_data->ongoing,
            'new_search_link'   => get_permalink(),
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

        $taxonomies = $this->get_taxonomies_with_slugs();

        foreach ($taxonomies as $tax_slug =>  $qv) {

            $terms = get_terms( [
                'taxonomy' => $tax_slug,
                'hide_empty' => true,
                ],
            );

            if ( ! empty( $terms ) ) {
                $filters[] = [
                    'name' => $this->strings()['program'][$tax_slug],
                    'query_var' => $qv,
                    'terms' => array_map( function( $term ) use ( $qv ) {
                        $active_terms = $this->get_filter_query_var( $qv );

                        if ( ! empty( $active_terms ) && is_array( $active_terms ) && in_array( $term->term_id, $active_terms )) {
                            $is_active = true;
                        }
                        else{
                            $is_active = false;
                        }
                        return [
                            'term_id' => $term->term_id,
                            'name' => $term->name,
                            'slug' => $term->slug,
                            'active' => $is_active,
                         ];
                     } , $terms )
                    ];
            } 
           
        }     

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

    //  /**
    //  * Supply data for active filter hidden input.
    //  *
    //  * @return string[]
    //  */
    // public function active_filters() {
    //     // $active_filter = self::get_filter_query_var( self::FILTER_PROGRAM_LOCATION_QUERY_VAR );

    //     // return $active_filter ? [
    //     //     'name'  => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
    //     //     'value' => $active_filter,
    //     // ] : null;
    //     $taxonomies = $this->get_taxonomies_with_slugs();

    //     foreach ($taxonomies as $tax_slug =>  $qv) {

    //         $terms = get_terms( [
    //             'taxonomy' => $tax_slug,
    //             'hide_empty' => true,
    //             ],
    //         );

    //         if ( ! empty( $terms ) ) {
    //             $filters[] = [
    //                 'name' => $this->strings()['program'][$tax_slug],
    //                 'query_var' => $qv,
    //                 'terms' => array_map( function( $term ) {
    //                     return [
    //                         'term_id' => $term->term_id,
    //                         'name' => $term->name,
    //                         'slug' => $term->slug
    //                      ];
    //                  } , $terms )
    //                 ];
    //         } 
           
    //     }     
    //     return 'Jee';
    // }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        $args = [
            'post_type' => Program::SLUG,
            'orderby' => array( 
                'menu_order' => 'ASC',
                'title'      => 'ASC', 
            ),
            'paged'     => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
            'posts_per_page' => self::POSTS_PER_PAGE,
        ];
        
        $only_ongoing = $this->get_ongoing_query_var(); 

        if ( ! empty( $only_ongoing ) ) {

            $today = date('Y-m-d');

            $args['meta_query'] = [
                [
                    'relation' => 'AND',
                    [
                     'key' => 'apply_start',
                     'value' =>  $today,
                     'compare' => '<=',
                     'type' => 'DATE'
                    ],
                    [
                     'key' => 'apply_end',
                     'value' => $today,
                     'compare' => '>=',
                     'type' => 'DATE'
                    ],
                 ],
             ];
        }
        
        // Add taxonomies to tax query from request's query vars

        $args['tax_query'] = [
            'relation' => 'AND' 
        ];

    
        $query_vars = $this->get_taxonomies_with_slugs();

        foreach ( $query_vars as $slug => $qv ) {      

            $terms = self::get_filter_query_var( $qv );
        
            if ( ! empty( $terms ) ) {
                $args['tax_query'][] = [
                    'taxonomy' => $slug,
                    'terms'    => $terms,
                ];
            }
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
            else {
                $item->image = Images::get_default_image_id();
            }

            $item->permalink = get_the_permalink( $item->ID );
            $item->fields    = get_fields( $item->ID );

            if ( ! empty ( $item->fields ) ) {
                
                if ( ! empty( $item->fields['start_info'] ) ) {
                    $item->fields['start_date'] = $item->fields['start_info'];
                }

                if ( ! empty( $item->fields['apply_info'] ) ) {
                    $item->fields['apply_end'] = $item->fields['apply_info'];
                }
                else if ( ! empty( $item->fields['apply_end'] ) ) {
                    $item->fields['apply_end'] = date('d.m.Y', strtotime( $item->fields['apply_end'] ) );
                }
            }

            // Profession::SLUG => self::FILTER_PROFESSION_QUERY_VAR,
            // ProgramType::SLUG => self::FILTER_PROGRAM_TYPE_QUERY_VAR,
            // Location::SLUG => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
            // EducationalBackground::SLUG => self::FILTER_EDUCATIONAL_BACKGROUND_QUERY_VAR,
            // DeliveryMethod::SLUG => self::FILTER_DELIVERY_METHODS_QUERY_VAR, 

            $professions = wp_get_post_terms( $item->ID, Profession::SLUG, [ 'fields' => 'names' ]  );
            if ( ! empty( $professions ) ) {
                $item->profession = $professions[0];
            }
 
            $program_types = wp_get_post_terms( $item->ID, ProgramType::SLUG, [ 'fields' => 'names' ]  );
            if ( ! empty( $program_types ) ) {
                $item->program_type = $program_types[0];
            }

            $locations = wp_get_post_terms( $item->ID, Location::SLUG, [ 'fields' => 'names' ] );
            if ( ! empty( $locations ) ) {
                $item->location = $locations[0];
            }

            $educational_backgrounds = wp_get_post_terms( $item->ID, EducationalBackground::SLUG, [ 'fields' => 'names' ]  );
            if ( ! empty( $educational_backgrounds ) ) {
                $item->educational_background = $educational_backgrounds[0];
            }

            $delivery_methods = wp_get_post_terms( $item->ID, DeliveryMethod::SLUG, [ 'fields' => 'names' ]  );
            if ( ! empty( $delivery_methods ) ) {
                $item->delivery_method = $delivery_methods[0];
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
        $per_page = self::POSTS_PER_PAGE;
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
        $shown_txt = $this->strings()['program']['search']['results_shown'];

        $results_text = sprintf( '%1$s %2$s / %3$s',
            $shown_txt,
            $result_count,
            $count_posts
        );

        return $results_text;
    }

}
