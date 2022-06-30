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
            $content->image = get_the_post_thumbnail_url( null, 'full' );
        }

        return $content;
    }
}
