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
use TMS\Theme\Tredu\Settings;

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
     * Setup hooks.
     */
    public function hooks() {
        add_filter( 'tms/theme/breadcrumbs/page', function ( $formatted, $original, $object ) {
            unset( $formatted, $original, $object );
            return [];
        }, 10, 3 );

        add_filter( 'tms/theme/breadcrumbs/show_breadcrumbs_in_header', function ( $status, $context ) {
            unset( $context, $status );

            return false;
        }, 10, 2 );

        add_filter( 'redipress/ignore_query_vars', [ __CLASS__, 'set_ignored_query_vars' ], 10, 1 );

    }

    /**
     * Get posts per page value
     *
     * @return mixed
     */
    protected static function get_posts_per_page() {
        return Settings::get_setting( 'programs_per_page' ) ?? 20;
    }

    /**
     * Add custom query vars to the list of ignored query vars list for RediPress.
     *
     * @param array $vars Ignored query vars.
     *
     * @return array
     */
    public static function set_ignored_query_vars(
        array $vars
    ) : array {
        $vars[] = self::SEARCH_QUERY_VAR;
        $vars[] = self::FILTER_PROGRAM_LOCATION_QUERY_VAR;
        $vars[] = self::FILTER_DELIVERY_METHODS_QUERY_VAR;
        $vars[] = self::FILTER_PROFESSION_QUERY_VAR;
        $vars[] = self::FILTER_PROGRAM_TYPE_QUERY_VAR;
        $vars[] = self::FILTER_EDUCATIONAL_BACKGROUND_QUERY_VAR;
        $vars[] = self::FILTER_ONGOING_QUERY_VAR;
        return $vars;
    }

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
     * @param string $query_var Query var for taxonomy.
     *
     * @return array|null
     */
    protected static function get_filter_query_var( $query_var = '' ) {
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
            Profession::SLUG            => self::FILTER_PROFESSION_QUERY_VAR,
            ProgramType::SLUG           => self::FILTER_PROGRAM_TYPE_QUERY_VAR,
            Location::SLUG              => self::FILTER_PROGRAM_LOCATION_QUERY_VAR,
            EducationalBackground::SLUG => self::FILTER_EDUCATIONAL_BACKGROUND_QUERY_VAR,
            DeliveryMethod::SLUG        => self::FILTER_DELIVERY_METHODS_QUERY_VAR,
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
            'search'     => [
                'label'             => __( 'Search for program', 'tms-theme-tredu' ),
                'submit_value'      => __( 'Search', 'tms-theme-tredu' ),
                'input_placeholder' => __( 'Search query', 'tms-theme-tredu' ),
            ],
            'terms'      => [
                'show_all' => __( 'Show All', 'tms-theme-tredu' ),
            ],
            'no_results' => __( 'No results', 'tms-theme-tredu' ),
            'filter'     => __( 'Filter', 'tms-theme-tredu' ),
            'sort'       => __( 'Sort', 'tms-theme-tredu' ),
            'program'    => ( new \Strings() )->s()['program'],
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
        return get_field( 'page_program_description' ) ?? '';
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
        foreach ( $taxonomies as $tax_slug => $qv ) {

            $terms = get_terms(
                [
                    'taxonomy'   => $tax_slug,
                    'hide_empty' => true,
                ],
            );

            if ( ! empty( $terms ) ) {
                $filters[] = [
                    'name'      => $this->strings()['program'][ $tax_slug ],
                    'query_var' => $qv,
                    'terms'     => array_map( function( $term ) use ( $qv ) {
                        $active_terms = $this->get_filter_query_var( $qv );

                        if ( ! empty( $active_terms ) && is_array( $active_terms ) && in_array( $term->term_id, $active_terms ) ) { // phpcs:ignore
                            $is_active = true;
                        }
                        else {
                            $is_active = false;
                        }
                        return [
                            'term_id' => $term->term_id,
                            'name'    => $term->name,
                            'slug'    => $term->slug,
                            'active'  => $is_active,
                        ];
                    }, $terms ),
                ];
            }
        }

        return $filters;
    }

    /**
     * View results
     *
     * @return array
     */
    public function results() {
        $args = [
            'post_type'      => Program::SLUG,
            'orderby'        => [
                'menu_order' => 'ASC',
                'title'      => 'ASC',
            ],
            'paged'          => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
            'posts_per_page' => $this->get_posts_per_page(),
        ];

        $only_ongoing = $this->get_ongoing_query_var();

        if ( ! empty( $only_ongoing ) ) {

            $today = date( 'Y-m-d' );

            $args['meta_query'] = [
                [
                    'relation' => 'AND',
                    [
                        'key'     => 'apply_start',
                        'value'   => $today,
                        'compare' => '<=',
                        'type'    => 'DATE',
                    ],
                    [
                        'key'     => 'apply_end',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE',
                    ],
                ],
            ];
        }

        // Add taxonomies to tax query from request's query vars

        $args['tax_query'] = [
            'relation' => 'AND',
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

            if ( ! empty( $item->fields ) ) {

                if ( ! empty( $item->fields['start_info'] ) ) {
                    $item->fields['start_date'] = $item->fields['start_info'];
                }

                if ( ! empty( $item->fields['apply_info'] ) ) {
                    $item->fields['apply_end'] = $item->fields['apply_info'];
                }
                else if ( ! empty( $item->fields['apply_end'] ) ) {
                    $item->fields['apply_end'] = date( 'd.m.Y', strtotime( $item->fields['apply_end'] ) );
                }
            }

            $taxonomies = $this->get_taxonomies_with_slugs();

            foreach ( $taxonomies as $tax_slug => $qv ) {

                $primary_term_id = get_post_meta( $item->ID, '_primary_term_' . $tax_slug, true );

                $term_id = 0;
                if ( ! empty( $primary_term_id ) ) {
                    $primary_term      = get_term( $primary_term_id );
                    $item->{$tax_slug} = $primary_term->name;
                    $term_id           = $primary_term_id;
                }
                else {
                    $terms = wp_get_post_terms( $item->ID, $tax_slug );
                    if ( ! empty( $terms ) ) {
                        $item->{$tax_slug} = $terms[0]->name;
                        $term_id           = $terms[0]->term_id;
                    }
                }

                if ( $tax_slug === ProgramType::SLUG ) {
                    $program_type_color           = get_term_meta( $term_id, 'color', true ) ?? '';
                    $item->program_type_color     = $program_type_color;
                    $item->program_type_txt_color = $program_type_color === 'primary' ? 'white' : 'primary';
                }
            }

            return $item;
        }, $posts );
    }


    /**
     * Set pagination data
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    protected function set_pagination_data( $wp_query ) : void {
        $per_page = $this->get_posts_per_page();
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
     * @param int $result_count  Result count.
     *
     * @return string|bool
     */
    protected function results_summary( $result_count ) {

        $count_posts = wp_count_posts( Program::SLUG )->publish;
        if ( function_exists( 'pll_count_posts' ) ) {
            $count_posts = pll_count_posts( pll_current_language(),
            [ 'post_type' => Program::SLUG ] );
        }
        else {
            $count_posts = wp_count_posts( Program::SLUG )->publish;
        }

        $shown_txt   = $this->strings()['program']['search']['results_shown'];

        $results_text = sprintf( '%1$s %2$s / %3$s',
            $shown_txt,
            $result_count,
            $count_posts
        );

        return $results_text;
    }
}
