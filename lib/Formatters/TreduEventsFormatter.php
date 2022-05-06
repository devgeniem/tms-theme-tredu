<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

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
     * @param array $events Array of events.
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
            'orderby'                => [ 'start_date' => 'ASC', 'title' => 'ASC' ],
            'posts_per_page'         => $layout['page_size'] ?? 4,
        ];

        $date_query = $this->get_date_query( $layout );

        if ( ! empty( $date_query ) ) {
            $args['meta_query'] = $date_query;
        }

        $tax_query = $this->get_tax_query( $layout );

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        return ( new \WP_Query( $args ) )->get_posts();
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
        $meta_query = [];

        if ( ! empty( $layout['start_date'] ) ) {
            $meta_query[] = [
                'key'     => 'start_date',
                'compare' => '>=',
                'type'    => 'DATE',
                'value'   => $layout['start_date'],
            ];
        }

        if ( ! empty( $layout['end_date'] ) ) {
            $meta_query[] = [
                'key'     => 'end_date',
                'compare' => '<=',
                'type'    => 'DATE',
                'value'   => $layout['end_date'],
            ];
        }

        return $meta_query;
    }
}
