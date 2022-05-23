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
class Location implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'location';

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
            'name'                  => 'Sijainnit',
            'singular_name'         => 'Toimipiste',
            'menu_name'             => 'Toimipisteet',
            'all_items'             => 'Kaikki toimipiste',
            'new_item_name'         => 'Lisää uusi toimipiste',
            'add_new_item'          => 'Lisää uusi toimipiste',
            'edit_item'             => 'Muokkaa toimipiste',
            'update_item'           => 'Päivitä toimipiste',
            'view_item'             => 'Näytä toimipiste',
            'search_items'          => 'Etsi toimipistettä',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Sijannit',
            'items_list_navigation' => 'Sijannit',
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
