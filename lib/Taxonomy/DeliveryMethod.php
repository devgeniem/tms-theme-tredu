<?php

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;
use TMS\Theme\Tredu\PostType\Program;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class DeliveryMethod implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'delivery-method';

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
            'name'                  => 'Toteutustavat',
            'singular_name'         => 'Toteutustapa',
            'menu_name'             => 'Toteutustavat',
            'all_items'             => 'Kaikki sijainnit',
            'new_item_name'         => 'Lisää uusi toteutustapa',
            'add_new_item'          => 'Lisää uusi toteutustapa',
            'edit_item'             => 'Muokkaa toteutustapaa',
            'update_item'           => 'Päivitä toteutustapa',
            'view_item'             => 'Näytä toteutustapa',
            'search_items'          => 'Etsi toteutustapaa',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Toteutustavat',
            'items_list_navigation' => 'Toteutustavat',
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

        register_taxonomy( self::SLUG, [ Program::SLUG ], $args );
    }
}
