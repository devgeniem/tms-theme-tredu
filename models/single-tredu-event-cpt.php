<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Tredu\Traits;

/**
 * The SingleDynamicEventCpt class.
 */
class SingleTreduEventCpt extends BaseModel {

    use Traits\Components;

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        $single = \DustPress\Query::get_acf_post( get_queried_object_id() );

        $single->image = $single->image === 0
            ? false
            : $single->image;

        return $single;
    }

    /**
     * Hero image
     *
     * @return false|int
     */
    public function hero_image() {
        return has_post_thumbnail()
            ? get_post_thumbnail_id()
            : false;
    }
}
