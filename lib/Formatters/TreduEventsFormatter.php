<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

use DateTime;
use Geniem\LinkedEvents\LinkedEventsClient;
use Geniem\LinkedEvents\LinkedEventsException;
use TMS\Theme\Tredu\LinkedEvents;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType\TreduEvent;
use TMS\Theme\Tredu\Settings;
use TMS\Theme\Tredu\Taxonomy\DeliveryMethod;
use TMS\Theme\Tredu\Taxonomy\Location;
use TMS\Theme\Tredu\Taxonomy\Profession;
use TMS\Theme\Tredu\Taxonomy\ProgramType;
use WP_Query;

/**
 * Class TreduEventsFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class TreduEventsFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'TreduEvents';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/tredu_events/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function format( array $layout ) : array {
        $events = $this->query_events( $layout );

        if ( empty( $events ) ) {
            return $layout;
        }

        $layout['events'] = $this->format_events( $events, $layout['show_images'] );

        return $layout;
    }

    /**
     * Format events
     *
     * @param array $events      Array of events.
     * @param bool  $show_images Whether to show images.
     *
     * @return array
     */
    public function format_events( array $events, bool $show_images = true ) : array {
        $default_image     = Settings::get_setting( 'events_default_image' );
        $default_image_url = wp_get_attachment_image_url( $default_image, 'large' );

        return array_map( function ( $item ) use ( $show_images, $default_image_url ) {
            $item->url      = get_post_permalink( $item->ID );
            $item->location = get_field( 'location', $item->ID ) ?? '';
            $item->date     = get_field( 'start_date', $item->ID ) ?? '';

            $end_date = get_field( 'end_date', $item->ID );

            if ( ! empty( $end_date ) ) {
                $item->date .= '- ' . $end_date;
            }

            $item->time = get_field( 'time', $item->ID );

            $item_types = wp_get_post_terms( $item->ID, ProgramType::SLUG );

            if ( ! is_wp_error( $item_types ) && ! empty( $item_types ) ) {
                $item->type = $item_types[0]->name;
            }

            if ( ! $show_images ) {
                return $item;
            }

            $item->image = has_post_thumbnail( $item->ID )
                ? get_the_post_thumbnail_url( $item->ID, 'large' )
                : $default_image_url;

            return $item;
        }, $events );
    }

    /**
     * Query Tredu Events.
     *
     * @param array $layout Layout.
     *
     * @return array
     */
    private function query_events( array $layout ) : array {
        $args = [
            'post_type'              => TreduEvent::SLUG,
            'update_post_meta_cache' => false,
            'no_found_rows'          => true,
            'orderby'                => [ 'start_date' => 'DESC', 'title' => 'ASC' ],
            'posts_per_page'         => 100,
//            'posts_per_page'         => $layout['page_size'] ?? 4,
        ];

        $date_query = $this->get_date_query( $layout );

        if ( ! empty( $date_query ) ) {
            $args = array_merge( $args, $date_query );
        }

        $tax_query = $this->get_tax_query( $layout );

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        $results = ( new WP_Query( $args ) )->get_posts();
        $today   = array_filter( $results, [ $this, 'is_today' ] );
        var_dump( $today );
        $current  = array_filter( $results, [ $this, 'is_current' ] );
        $upcoming = array_filter( $results, [ $this, 'is_upcoming' ] );

        $current_and_upcoming = array_merge( $today, $current, $upcoming );

        return $current_and_upcoming;
    }

    /**
     * Get tax queries.
     *
     * @param array $layout Layout data.
     *
     * @return array
     */
    private function get_tax_query( array $layout ) : array {
        $taxonomies = [
            'program_type'    => ProgramType::SLUG,
            'profession'      => Profession::SLUG,
            'location'        => Location::SLUG,
            'delivery_method' => DeliveryMethod::SLUG,
        ];

        $tax_query = [];

        foreach ( $taxonomies as $field_key => $taxonomy ) {
            if ( empty( $layout[ $field_key ] ) ) {
                continue;
            }

            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $layout[ $field_key ],
            ];
        }

        return $tax_query;
    }

    /**
     * Get date query.
     *
     * @param array $layout Layout data.
     *
     * @return array
     */
    private function get_date_query( array $layout ) : array {
        $args = [
            'orderby'    => [ 'start_date_clause' => 'ASC', 'title' => 'ASC' ],
            'meta_query' => [
                'relation'          => 'AND',
                'start_date_clause' => [
                    'key'     => 'start_date',
                    'compare' => 'EXISTS',
                ],
            ],
        ];

        return $args;
    }

    /**
     * Is the item due today?
     *
     * @param WP_Post $item Item object.
     *
     * @return bool
     */
    protected function is_today( $item ) {
        $format = 'Ymd';
        $today  = ( new DateTime( 'now' ) )->setTime( 0, 0 );

        $start_date = DateTime::createFromFormat( $format, get_post_meta( $item->ID, 'start_date', true ) );
        $end_date   = DateTime::createFromFormat( $format, get_post_meta( $item->ID, 'end_date', true ) );

        return $today === $start_date && $today <= $end_date;
    }

    /**
     * Is the item currently running?
     *
     * @param WP_Post $item Item object.
     *
     * @return bool
     */
    protected function is_current( $item ) {
        $format = 'Ymd';
        $today  = new DateTime( 'now' );

        $start_date = DateTime::createFromFormat( $format, get_post_meta( $item->ID, 'start_date', true ) );
        $end_date   = DateTime::createFromFormat( $format, get_post_meta( $item->ID, 'end_date', true ) );

        return $today > $start_date && $today <= $end_date;
    }

    /**
     * Is the items' start date in the future?
     *
     * @param WP_Post $item Item object.
     *
     * @return bool
     */
    protected function is_upcoming( $item ) {
        $format = 'Ymd';
        $today  = new DateTime( 'now' );

        $start_date = DateTime::createFromFormat( $format, get_post_meta( $item->ID, 'start_date', true ) );
        $end_date   = DateTime::createFromFormat( $format, get_post_meta( $item->ID, 'end_date', true ) );

        return $start_date > $today && $today >= $end_date;
    }
}
