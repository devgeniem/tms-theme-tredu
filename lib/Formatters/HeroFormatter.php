<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Formatters;
use TMS\Theme\Tredu\Settings;


/**
 * Class HeroFormatter
 *
 * @package TMS\Theme\Tredu\Formatters
 */
class HeroFormatter implements \TMS\Theme\Tredu\Interfaces\Formatter {

    /**
     * Define formatter name
     */
    const NAME = 'Hero';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter(
            'tms/acf/layout/hero/data',
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

        if ( $layout['use_box'] && $this->has_filled_text_fields( $layout ) ) {
            $layout['container_class'] = 'hero--box';
            $box_classes[]             = 'hero__box--background';
            $box_classes[]             = 'has-background-primary';
            $box_classes[]             = 'has-text-primary-invert';
            $button_classes[]          = 'is-primary-invert';
            $button_classes[]          = 'is-outlined';
        }
        else {
            $layout['use_overlay'] = ! empty( $layout['description'] );
            $box_classes[]         = 'has-text-white';
            $button_classes[]      = 'is-primary';
        }

        $layout['form_action'] = $this->form_action();

        return $layout;
    }

    /**
     * Has filled text fields
     *
     * @param array $layout ACF Layout data.
     *
     * @return bool
     */
    protected function has_filled_text_fields( array $layout ) : bool {
        $fields = [
            'title',
            'description',
            'link',
        ];

        foreach ( $fields as $field_key ) {
            if ( ! empty( $layout[ $field_key ] ) ) {
                return true;
            }
        }

        return false;
    }

     /**
     * Get form action from settings
     *
     * @return string
     */
    private function form_action() : string {

        $program_page = Settings::get_setting( 'program_page' );

        if ( is_int( $program_page ) ) {
           return get_permalink( $program_page );
        }

        return '';
    }
}
