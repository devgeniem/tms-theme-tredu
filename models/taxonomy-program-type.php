<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Tredu\Blocks\ProgramCallToAction;
use TMS\Theme\Tredu\Settings;

/**
 * The TaxonomyProgramType class.
 */
class TaxonomyProgramType extends Archive {

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
     * @return string|null
     */
    public function long_description() : ?string {
        return get_field( 'long_description', get_queried_object() );
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
