<?php

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;
use \TMS\Theme\Tredu\PostType\Project;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class Portfolio implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'portfolio';

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
            'name'                  => 'Salkut',
            'singular_name'         => 'Salkku',
            'menu_name'             => 'Salkut',
            'all_items'             => 'Kaikki salkut',
            'new_item_name'         => 'Lisää uusi salkku',
            'add_new_item'          => 'Lisää uusi salkku',
            'edit_item'             => 'Muokkaa salkkua',
            'update_item'           => 'Päivitä salkku',
            'view_item'             => 'Näytä salkku',
            'search_items'          => 'Etsi salkkua',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Salkut',
            'items_list_navigation' => 'Salkut',
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

        register_taxonomy( self::SLUG, [ Project::SLUG ], $args );
    }
}
