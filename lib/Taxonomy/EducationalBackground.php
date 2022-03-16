<?php

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;
use TMS\Theme\Tredu\PostType\Program;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class EducationalBackground implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'educational-background';

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
            'name'                  => 'Koulutustaustat',
            'singular_name'         => 'Koulutustausta',
            'menu_name'             => 'Koulutustaustat',
            'all_items'             => 'Kaikki koulutustaustat',
            'new_item_name'         => 'Lisää uusi koulutustausta',
            'add_new_item'          => 'Lisää uusi koulutustausta',
            'edit_item'             => 'Muokkaa koulutustaustaa',
            'update_item'           => 'Päivitä koulutustausta',
            'view_item'             => 'Näytä koulutustausta',
            'search_items'          => 'Etsi koulutustaustaa',
            'not_found'             => 'Ei tuloksia',
            'no_terms'              => 'Ei tuloksia',
            'items_list'            => 'Koulutustaustat',
            'items_list_navigation' => 'Koulutustaustat',
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
