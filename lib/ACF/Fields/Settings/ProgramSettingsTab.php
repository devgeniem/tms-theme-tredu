<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class ProgramSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class ProgramSettingsTab extends Tab {

    /**
     * Where should the tab switcher be located
     *
     * @var string
     */
    protected $placement = 'left';

    /**
     * Tab strings.
     *
     * @var array
     */
    protected $strings = [
        'tab'  => 'Koulutusten asetukset',
        'program_page' => [
            'title'        => 'Koulutushaun sivu',
            'instructions' => 'Sivu, jolle on valittu Koulutushaku-sivupohja. Käytetään pikahaussa hakulomakkeen lähettämisessä koulutusten hakusivulle',
        ],
        'program_post_per_page' => [
            'title' => 'Koulutusten määrä per sivu',
            'instructions' => '',
        ],
    ];

    /**
     * The constructor for tab.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) { // phpcs:ignore
        $label = $this->strings['tab'];

        parent::__construct( $label );

        $this->sub_fields( $key );
    }

    /**
     * Register sub fields.
     *
     * @param string $key Field tab key.
     */
    public function sub_fields( $key ) {
        $strings = $this->strings;

        try {
            $program_page = ( new Field\PostObject( $strings['program_page']['title'] ) )
                ->set_key( "${key}_program_page" )
                ->set_name( 'program_page' )
                ->set_post_types( [ PostType\Page::SLUG ] )
                ->set_return_format( 'id' )
                ->set_instructions( $strings['program_page']['instructions'] );

            $prorgam_post_per_page_field = ( new Field\Number( $strings['program_post_per_page']['title'] ) )
                ->set_key( "${key}_programs_per_page" )
                ->set_name( 'programs_per_page' )
                ->set_min( 1 )
                ->set_max( 30 )
                ->set_default_value( 20 )
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['program_post_per_page']['instructions'] );

            $this->add_fields( [
                $program_page,
                $prorgam_post_per_page_field,
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
