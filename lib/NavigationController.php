<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu;

/**
 * Class NavigationController
 *
 * @package TMS\Theme\Tredu
 */
class NavigationController implements Interfaces\Controller {

    /**
     * Hooks
     */
    public function hooks() : void {
        add_action(
            'after_setup_theme',
            \Closure::fromCallable( [ $this, 'register_nav_menus' ] )
        );

        add_action(
            'admin_head-nav-menus.php',
            \Closure::fromCallable( [ $this, 'remove_custom_links' ] )
        );
    }

    /**
     * Register navigation menus
     */
    protected function register_nav_menus() : void {
        register_nav_menu( 'primary', __( 'Primary Navigation', 'tms-theme-tredu' ) );
        register_nav_menu( 'secondary', __( 'Secondary Navigation', 'tms-theme-tredu' ) );
    }

    /**
     * Remove nav menu meta-box links
     */
    protected function remove_custom_links() : void {
        global $wp_meta_boxes;

        if ( isset( $wp_meta_boxes['nav-menus'], $wp_meta_boxes['nav-menus']['side'] ) ) {
            foreach ( $wp_meta_boxes['nav-menus']['side'] as $nav_menus ) {
                foreach ( $nav_menus as $nav_id => $nav_menu ) {
                    if ( $nav_id === 'add-custom-links' ) {
                        remove_meta_box( $nav_id, 'nav-menus', 'side' );
                    }
                }
            }
        }
    }
}
