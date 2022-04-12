<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

/**
 * Class CallToActionFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class CallToActionFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'CallToAction';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/call_to_action/data',
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
        foreach ( $layout['rows'] as $row_key => $row ) {
            $row['text_column_class'] = 'is-6-desktop pr-8-tablet pr-10-fullhd';

            $color_scheme           = $row['background_color'] ?? 'primary-light';
            $row['container_class'] = "has-colors-$color_scheme";

            $button_colors = [
                'primary-light' => 'is-primary',
                'primary'       => 'is-primary is-inverted is-outlined',
            ];

            $row['button_colors'] = $button_colors[ $color_scheme ] ?? '';

            if ( $row['layout'] === 'is-text-first' ) {
                $row['container_class']   .= ' is-reversed-tablet';
                $row['text_column_class'] .= ' pl-8-tablet pl-10-fullhd';
            }

            $row = ImageFormatter::get_image_artist( $row, (array) ( $row['image'] ?? null ) );

            $layout['rows'][ $row_key ] = $row;
        }

        return $layout;
    }
}
