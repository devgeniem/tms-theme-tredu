<?php
/**
 * Template Name: Dial Tredu listaus
 */

use DustPress\Query;
use TMS\Theme\Tredu\Traits;
use TMS\Theme\Tredu\PostType\DialTredu;

/**
 * Archive for Dial Tredu CPT
 */
class PageDialTredu extends BaseModel {

    use Traits\Components;

    /**
     * Template
     */
    const TEMPLATE = 'models/page-dial-tredu.php';

    /**
     * This holds the summary text.
     *
     * @var string
     */
    private static $summary = '';

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'tms/theme/breadcrumbs/show_breadcrumbs_in_header', fn() => false );
    }

    /**
     * Content
     *
     * @return array|object|WP_Post|null
     * @throws Exception If global $post is not available or $id param is not defined.
     */
    public function content() {
        $content = Query::get_acf_post( get_queried_object_id() );

        if ( has_post_thumbnail() ) {
            $content->image = get_post_thumbnail_id();
        }

        if ( ! empty( $content->fields['items'] ) ) {
            $content->results = array_map( function ( $item ) {
                $link = get_field( 'link', $item->ID );

                if ( empty( $link ) ) {
                    $link = [
                        'title' => __( 'Read more here', 'tms-theme-tredu' ),
                        'url'   => get_post_permalink( $item->ID ),
                    ];
                }

                return [
                    'title'   => $item->post_title,
                    'ingress' => get_field( 'ingress', $item->ID ),
                    'icon'    => get_field( 'icon', $item->ID ),
                    'link'    => $link,
                ];
            }, $content->fields['items'] );
        }

        return $content;
    }

    /**
     * Page description
     *
     * @return string
     */
    public function page_description() : string {
        return get_field( 'description' ) ?? '';
    }
}
