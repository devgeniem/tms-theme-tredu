<?php
/**
 * Copyright (c) 2022. Geniem Oy
 */

namespace TMS\Theme\Tredu;

use Geniem\RediPress\Entity\TextField;
use TMS\Theme\Base\Interfaces\Controller;

/**
 * Class IndexController
 *
 * @package TMS\Theme\Tredu
 */
class IndexController implements Controller {
    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'redipress/schema_fields', function ( $fields ) {
            $fields[] = new TextField( [
                'name'     => 'artists',
                'sortable' => true,
            ] );

            return $fields;
        }, PHP_INT_MAX, 1 );

        add_filter( 'redipress/additional_field/artists', function ( $data, $post_id, $post ) {
            $value = '';

            return $value;
        }, 10, 3 );
    }
}
