<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Layouts;

/**
 * TreduLayout
 */
class TreduLayout extends \Geniem\ACF\Field\Flexible\Layout {
    /**
     * Run default filters to our fields.
     *
     * @param array  $fields   ACF Fields.
     * @param string $key      ACF Group Key.
     * @param string $tredu_key Layout self::KEY.
     *
     * @return array
     */
    public function filter_layout_fields( $fields, $key, $tredu_key = '' ) : array {
        if ( $tredu_key !== $key && ! empty( $tredu_key ) ) {
            $fields = apply_filters( 'tms/acf/layout/' . $tredu_key . '/fields', $fields, $key );
        }

        return apply_filters( 'tms/acf/layout/' . $key . '/fields', $fields, $key );
    }
}
