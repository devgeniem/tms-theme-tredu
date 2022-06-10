<?php

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;
use TMS\Theme\Tredu\PostType\Program;
use TMS\Theme\Tredu\PostType\TreduEvent;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class ProgramType implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'program-type';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Koulutustyypit',
            'singular_name'         => 'Koulutustyyppi',
            'menu_name'             => 'Koulutustyypit',
            'all_items'             => 'Kaikki koulutustyypit',
            'new_item_name'         => 'Lisää uusi koulutustyyppi',
            'add_new_item'          => 'Lisää uusi koulutustyyppi',
            'edit_item'             => 'Muokkaa koulutustyyppiä',
            'update_item'           => 'Päivitä koulutustyyppi',
            'view_item'             => 'Näytä koulutustyyppi',
            'search_items'          => 'Etsi koulutustyyppiä',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Koulutustyypit',
            'items_list_navigation' => 'Koulutustyypit',
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ];

        register_taxonomy( self::SLUG, [ Program::SLUG, TreduEvent::SLUG ], $args );
    }
}
