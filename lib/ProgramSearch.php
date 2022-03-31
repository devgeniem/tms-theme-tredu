<?php

namespace TMS\Theme\Tredu;

use TMS\Theme\Tredu\PostType\Program;

/**
 * Class ProgramSearch
 *
 * @package TMS\Theme\Tredu
 */
class ProgramSearch implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {

        \add_action(
            'wp_footer',
            \Closure::fromCallable( [ $this, 'print_json_for_autocompete' ] )
        );
    }

    /**
     * Create list for program search's autocomplete
     *
     * @return array
     */
    private function create_json_for_autocomplete() {
        $titles          = [];
        $search_keywords = [];
        $args            = [
            'post_type'      => Program::SLUG,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ];

        $the_query = new \WP_Query( $args );
        $results   = $the_query->get_posts();

        foreach ( $results as $program ) {
            if ( ! empty( $program->post_title ) ) {
                $titles[] = $program->post_title;
            }
            $key_words = get_field( 'search_keywords', $program );

            if ( ! empty( $key_words ) ) {
                // Clean extra whitespace and remove commas
                $cleaned_text      = mb_strtolower( trim( preg_replace( '/[\t\n\r\s]+/', ' ', str_replace( ',', ' ',$key_words ) ) ) );
                $str_array         = explode( ' ', $cleaned_text );
                $search_keywords[] = $str_array;
            }
        }

        // Flatten array and remove duplicates
        $search_keywords = array_unique( array_merge( ...array_values( $search_keywords ) ) );
        // Combine titles and search keywords
        $words = array_merge( $titles, $search_keywords );

        return wp_json_encode( $words );
    }

    /**
     * Print script tag for program search's autocomplete
     *
     * @return void
     */
    private function print_json_for_autocompete() {

        $json = $this->create_json_for_autocomplete();

        printf(
            '<script id="program-search-words" type="application/ld+json">%s</script>',
             $json //phpcs:ignore
        );
    }
}
