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
            'singular_name'         => 'Sijainti',
            'menu_name'             => 'Sijainnit',
            'all_items'             => 'Kaikki sijainnit',
            'new_item_name'         => 'Lisää uusi sijainti',
            'add_new_item'          => 'Lisää uusi sijainti',
            'edit_item'             => 'Muokkaa sijaintia',
            'update_item'           => 'Päivitä sijainti',
            'view_item'             => 'Näytä sijainti',
            'search_items'          => 'Etsi sijaintia',
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
