<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

use TMS\Theme\Tredu\Traits\Components;

/**
 * Class AccordionImageFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class AccordionImageFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    use Components;

    /**
     * Define formatter name
     */
    const NAME = 'AccordionImage';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/accordion_image/data',
            [ $this, 'format' ]
        );
    }

    /**
     * Format layout data
     *
     * @param array $data ACF Layout data.
     *
     * @return array
     */
    public function format( array $data ) : array {
        if ( empty( $data['image'] ) ) {
            return $data;
        }

        // Set the image caption
        $caption = ! empty( $data['caption'] ) ? $data['caption'] : null;

        if ( empty( $caption ) ) {
            $caption = ! empty( $data['image']['caption'] ) ? $data['image']['caption'] : null;
        }

        $data['caption'] = $caption;

        // Return only image id
        $data['image'] = $data['image']['id'];

        return $data;
    }
}
