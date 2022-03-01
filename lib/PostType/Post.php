<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\PostType;

use \TMS\Theme\Tredu\Interfaces\PostType;
use TMS\Theme\Tredu\Taxonomy\Category;
use TMS\Theme\Tredu\Traits\EnrichPost;

/**
 * This class defines the post type.
 *
 * @package TMS\Theme\Tredu\PostType
 */
class Post implements PostType {

    use EnrichPost;

    /**
     * This defines the slug of this post type.
     */
    const SLUG = 'post';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {}

    /**
     * Get primary category.
     *
     * @param string $post_id Post ID.
     *
     * @return \WP_Term|null
     */
    public static function get_primary_category( $post_id ) {
        return Category::get_primary_category( $post_id );
    }
}
