<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

use TMS\Theme\Tredu\Interfaces\Formatter;

/**
 * Class DialTreduAccordionFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class DialTreduAccordionFormatter implements Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'DialTreduAccordion';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/block/dial-tredu-accordion/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format block data
     *
     * @param array $data ACF Block data.
     *
     * @return array
     */
    public static function format( array $data ) : array {
        if ( empty( $data['items'] ) ) {
            return $data;
        }

        $data['items'] = array_map( function ( $item ) {
            $link = get_field( 'link', $item->ID );

            if ( empty( $link ) ) {
                $link = [
                    'title' => __( 'Read more here', 'tms-theme-tredu' ),
                    'url'   => get_post_permalink( $item->ID ),
                ];
            }

            return [
                'title'   => $item->post_title,
                'ingress' => get_field( 'ingress', $item->ID ),
                'icon'    => get_field( 'icon', $item->ID ),
                'link'    => $link,
                'id'      => wp_unique_id( 'dial-tredu-accordion-' ),
            ];
        }, $data['items'] );

        return $data;
    }
}
