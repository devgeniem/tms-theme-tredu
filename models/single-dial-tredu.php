<?php
/**
 * Define the single dial tredu.
 */

use TMS\Theme\Tredu\Traits\Components;

/**
 * The SingleDialTredu class.
 */
class SingleDialTredu extends BaseModel {

    use Components;

    /**
     * Hero image
     *
     * @return array
     */
    public function content() : array {
        return [
            'title'   => get_the_title(),
            'ingress' => get_field( 'ingress' ),
            'icon'    => get_field( 'icon' ),
        ];
    }

}
