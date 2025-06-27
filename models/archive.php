<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Tredu\PostType\BlogArticle;
use TMS\Theme\Tredu\PostType\Post;
use TMS\Theme\Tredu\Taxonomy\BlogCategory;

/**
 * The Archive class.
 */
class Archive extends Home {

    /**
     * Hooks
     */
    public static function hooks() : void {
        add_action(
            'pre_get_posts',
            [ __CLASS__, 'modify_query' ]
        );
    }

    /**
     * Get filter category
     *
     * @return int|null
     */
    protected static function get_filter_category() : ?int {
        return is_tax() || is_category()
            ? get_queried_object_id()
            : null;
    }

    /**
     * Modify query
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    public static function modify_query( WP_Query $wp_query ) : void {
        if ( is_admin() || ( ! $wp_query->is_main_query() || ! $wp_query->is_archive() ) ) {
            return;
        }

        if ( is_category() || is_tag() ) {
            static::modify_query_post_type( $wp_query );
        }

        static::modify_query_date( $wp_query );
    }

    /**
     * Get the page title.
     *
     * @return string|null
     */
    public function page_title() : ?string {
        return \single_term_title( '', false );
    }

    /**
     * Get the page description.
     *
     * @return string|null
     */
    public function page_description() : ?string {
        return \term_description( \get_queried_object() );
    }

    /**
     * Get the blog-category image.
     *
     * @return string|null
     */
    public function blog_category_image() {
        $queried_object = \get_queried_object();
        $image_field    = \get_field( 'image', $queried_object );

        if ( ! $image_field ) {
            return null;
        }

        return $image_field['ID'];
    }

    /**
     * Get highlight item
     *
     * @return object|null
     */
    public function highlight() : ?object {
        return null;
    }

    /**
     * Get filter categories
     *
     * @return array
     */
    protected function get_filter_categories() : array {
        $queried_object = get_queried_object();
        $taxonomy       = '';

        if ( $queried_object instanceof WP_Term ) {
            $taxonomy = $queried_object->taxonomy;
        }

        $categories = get_categories( [ 'taxonomy' => $taxonomy ] );

        if ( empty( $categories ) ) {
            return [];
        }

        $year_filter     = static::get_filter_year();
        $month_filter    = static::get_filter_month();
        $category_filter = static::get_filter_category();

        $categories = array_map( function ( $item ) use ( $category_filter, $month_filter, $year_filter, $taxonomy ) {
            $item->is_active = $category_filter === $item->term_id;
            $item->url       = add_query_arg(
                [
                    'filter-month' => $month_filter,
                    'filter-year'  => $year_filter,
                ],
                get_term_link( $item, $taxonomy )
            );

            return $item;

        }, $categories );

        $root_page = $taxonomy === BlogCategory::SLUG
            ? get_post_type_archive_link( BlogArticle::SLUG )
            : get_the_permalink( get_option( 'page_for_posts' ) );

        if ( ! empty( $root_page ) ) {
            array_unshift( $categories, [
                'name'      => __( 'All', 'tms-theme-tredu' ),
                'url'       => add_query_arg(
                    [
                        'filter-month' => $month_filter,
                        'filter-year'  => $year_filter,
                    ],
                    $root_page
                ),
                'is_active' => empty( $category_filter ),
            ] );
        }

        return $categories;
    }

    /**
     * Modify query post_type param
     *
     * @param WP_Query $wp_query Instance of WP_Query.
     *
     * @return void
     */
    protected static function modify_query_post_type( $wp_query ) {
        $wp_query->set( 'post_type', [ Post::SLUG, BlogArticle::SLUG ] );
    }
}
