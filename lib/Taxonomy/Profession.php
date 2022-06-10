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
class Profession implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'profession';

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
            'name'                  => 'Ammattialat',
            'singular_name'         => 'Ammattiala',
            'menu_name'             => 'Ammattialat',
            'all_items'             => 'Kaikki ammattialat',
            'new_item_name'         => 'Lisää uusi ammattiala',
            'add_new_item'          => 'Lisää uusi ammattiala',
            'edit_item'             => 'Muokkaa ammattialaa',
            'update_item'           => 'Päivitä ammattiala',
            'view_item'             => 'Näytä ammattiala',
            'search_items'          => 'Etsi ammattialaa',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Ammattialat',
            'items_list_navigation' => 'Ammattialat',
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
