<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;

/**
 * Class TextBlockFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class TextBlockFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'TextBlock';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/textblock/data',
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
        $layout['width_class']      = empty( $layout['width'] ) ? ' is-align-full' : ' ' . $layout['width'];
        $layout['link_class']       = 'is-primary';
        $layout['text_color_class'] = '';
        $layout['bg_color_class']   = '';

        $bg_color = $layout['background_color'] ?: '';

        if ( empty( $bg_color ) ) {
            return $layout;
        }

        $layout['link_class']       = "is-{$bg_color}-invert";
        $layout['text_color_class'] = " has-text-{$bg_color}-invert";
        $layout['bg_color_class']   = " has-background-{$bg_color}";

        return $layout;
    }
}
