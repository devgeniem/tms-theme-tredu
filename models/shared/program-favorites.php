<?php
/**
 * ProgramFavorites model
 */

use DustPress\Model;

/**
 * ProgramFavorites class
 */
class ProgramFavorites extends Model {

    /**
     * Define methods that are allowed to be used by Ajax.
     *
     * @var array
     */
    public $api = [
        'FavoritePrograms',
    ];

    /**
     * Get favorite programs by IDs.
     *
     * @return array
     */
    public function FavoritePrograms() {
        $args  = $this->get_args();
        $data  = [
            'Favorites' => [],
        ];
        $posts = [];

        if ( ! empty( $args ) ) {
            $program_ids = $args->ids ?? [];
        }

        if ( empty( $program_ids ) ) {
            return [];
        }

        if ( ! empty( $program_ids ) ) {
            foreach ( $program_ids as $key => $post_id ) {
                // ID
                $posts[ $key ]['ID'] = $post_id;

                // Program title
                $posts[ $key ]['title'] = \get_the_title( $post_id );

                // Comma separated program-type terms
                $term_obj_list                  = \get_the_terms( $post_id, 'program-type' );
                $terms_string                   = join( ', ', wp_list_pluck( $term_obj_list, 'name' ) );
                $posts[ $key ]['program_types'] = $terms_string;

                // Dates
                $posts[ $key ]['start_date'] = date( "d.m.Y", strtotime( \get_field( 'start_date', $post_id ) ) );
                $posts[ $key ]['start_info'] = \get_field( 'start_info', $post_id );

                // Permalink
                $posts[ $key ]['permalink'] = \get_the_permalink( $post_id );

                // Texts
                $posts[ $key ]['remove_favorite_text'] = \_x( 'Remove from favorites', 'theme-frontend', 'tms-theme-tredu' );
                $posts[ $key ]['go_to_program_text']   = \_x( 'Go to program', 'theme-frontend', 'tms-theme-tredu' );
            }

            // Assign favorite posts
            $data['Favorites']['posts'] = $posts;
        }

        return $data;
    }
}
