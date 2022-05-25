<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Tredu\Blocks\ProgramCallToAction;
use TMS\Theme\Tredu\Settings;
use TMS\Theme\Tredu\Traits\Pagination;

/**
 * The TaxonomyProgramType class.
 */
class TaxonomyProgramType extends Archive {

    use Pagination;

    /**
     * Get the hero image.
     *
     * @return mixed
     */
    public function hero() {
        $hero_image = get_field( 'hero_image', get_queried_object() );

        return ! empty( $hero_image ) ? $hero_image : null;
    }

    /**
     * Get the term description.
     *
     * @return string|null
     */
    public function term_description() : ?string {
        return term_description();
    }

    /**
     * Get the long description.
     *
     * @return mixed
     */
    public function long_description() {
        $long_description = get_field( 'long_description', get_queried_object() );

        return ! empty( $long_description ) ? $long_description : null;
    }

    /**
     * Get the scope.
     *
     * @return mixed
     */
    public function scope() {
        $scope = get_field( 'scope', get_queried_object() );

        return ! empty( $scope ) ? $scope : null;
    }

    /**
     * Get the "who can apply" -text.
     *
     * @return mixed
     */
    public function who_can_apply() {
        $who_can_apply = get_field( 'who_can_apply', get_queried_object() );

        return ! empty( $who_can_apply ) ? $who_can_apply : null;
    }

    /**
     * Get the cta if it's set to be shown.
     *
     * @return array|null
     */
    public function cta() : ?array {
        $show_cta = get_field( 'show_cta', get_queried_object() );

        if ( empty( $show_cta ) ) {
            return null;
        }

        $data                = [];
        $data['title']       = Settings::get_setting( 'program_call_to_action_cta_title' ) ?? '';
        $data['description'] = Settings::get_setting( 'program_call_to_action_cta_description' ) ?? '';
        $data['link']        = Settings::get_setting( 'program_call_to_action_cta_link' ) ?? '';

        return apply_filters( 'tms/acf/block/' . ProgramCallToAction::KEY . '/data', $data );
    }

    /**
     * Get programs.
     *
     * @return array|null
     */
    public function articles() : ?array {
        global $wp_query;

        if ( empty( $wp_query->posts ) ) {
            return [];
        }

        $this->set_pagination_data( $wp_query );

        return ( new PageProgram() )->format_posts( $wp_query->posts );
    }
}
