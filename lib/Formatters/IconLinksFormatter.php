<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

/**
 * Class IconLinksFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class IconLinksFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'IconLinks';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/icon_links/data',
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
        if ( empty( $layout['rows'] ) ) {
            return $layout;
        }

        foreach ( $layout['rows'] as $key => $row ) {
            $icon = 'arrow-right';

            if ( isset( $row['link']['target'] ) && '_blank' === $row['link']['target'] ) {
                $icon = 'external';
            }

            $layout['rows'][ $key ]['icon_classes'] = 'icon--medium is-inline-block';
            if ( ! empty( $layout['rows'][ $key ]['link'] ) ) {
                $layout['rows'][ $key ]['link']['icon'] = $icon;
            }
        }

        return $layout;
    }
}
