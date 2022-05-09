<?php
/**
 * Define the single post class.
 */

use DustPress\Query;
use TMS\Theme\Tredu\PostType\BlogArticle;
use TMS\Theme\Tredu\Settings;
use TMS\Theme\Tredu\Taxonomy\BlogCategory;

/**
 * The SingleBlogArticle class.
 */
class SingleBlogArticle extends Single {

    /**
     * Get the blog title.
     *
     * @return string|null
     */
    public function blog_title() : ?string {
        return Settings::get_setting( 'blog_name' );
    }

    /**
     * Get the blog subtitle.
     *
     * @return string|null
     */
    public function blog_subtitle() : ?string {
        return Settings::get_setting( 'blog_subtitle' );
    }

    /**
     * Get the blog subtitle.
     *
     * @return string|null
     */
    public function blog_description() : ?string {
        return Settings::get_setting( 'blog_description' );
    }

    /**
     * Get the blog logo.
     *
     * @return string|null
     */
    public function blog_logo() : ?string {
        return Settings::get_setting( 'blog_logo' );
    }

    /**
     * Get the blog archive link.
     *
     * @return string
     */
    public function archive_link() : string {
        return get_post_type_archive_link( BlogArticle::SLUG );
    }

    /**
     * Get the blog authors.
     *
     * @return array
     */
    public function blog_authors() : ?array {
        $authors = get_field( 'authors' );

        if ( empty( $authors ) ) {
            return [];
        }

        return array_map( function ( $author ) {
            $author->featured_image = has_post_thumbnail( $author->ID )
                ? get_post_thumbnail_id( $author->ID )
                : null;

            return $author;
        }, $authors );
    }

    /**
     * Output HTML classes.
     *
     * @return array
     */
    public function classes() : ?array {
        return apply_filters( 'tms/theme/single_blog/classes', [
            'info_section_button' => 'is-primary',
        ] );
    }

    /**
     * Get comments markup.
     *
     * @return false|string
     */
    public function comments() {
        if ( ! comments_open( get_the_ID() ) ) {
            return false;
        }

        ob_start();
        comments_template();

        return ob_get_clean();
    }

    /**
     * Get related posts
     *
     * @return array|null
     */
    public function related() : ?array {
        $post_id    = get_queried_object_id();
        $term_args  = [ 'fields' => 'ids' ];
        $categories = wp_get_post_terms( $post_id, BlogCategory::SLUG, $term_args );
        $limit      = 4;

        $args = [
            'post_type'      => BlogArticle::SLUG,
            'posts_per_page' => $limit,
            'no_found_rows'  => true,
            'post__not_in'   => [ $post_id ],
        ];

        if ( ! empty( $categories ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => BlogCategory::SLUG,
                    'terms'    => $categories,
                ],
            ];
        }

        $posts = Query::get_posts( $args );

        if ( empty( $posts ) || count( $posts ) < $limit ) {
            return null;
        }

        $posts = apply_filters(
            'tms/single-blog-article/related',
            array_map( function ( $item ) {
                $categories = wp_get_post_terms( $item->ID, BlogCategory::SLUG );

                if ( ! empty( $categories ) ) {
                    $item->category      = $categories[0]->name;
                    $item->category_link = get_term_link( $categories[0]->term_id, BlogCategory::SLUG );
                }

                $item->image_id = $item->image_id === 0
                    ? \TMS\Theme\Tredu\Images::get_default_image_id()
                    : $item->image_id;

                if ( ! has_excerpt( $item->ID ) ) {
                    $item->post_excerpt = $this->get_related_excerpt( $item );
                }

                return $item;
            }, $posts )
        );

        return [
            'title' => get_field( 'related_title' ) ?? '',
            'posts' => $posts,
            'link'  => get_field( 'related_link' ) ?? '',
        ];
    }
}
