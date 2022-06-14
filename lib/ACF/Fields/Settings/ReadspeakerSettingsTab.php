<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields\Settings;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use Geniem\ACF\Field\Tab;
use TMS\Theme\Tredu\Logger;

/**
 * Class ReadspeakerSettingsTab
 *
 * @package TMS\Theme\Tredu\ACF\Tab
 */
class ReadspeakerSettingsTab extends Tab {

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
        'tab'                => 'Readspeaker',
        'readspeaker_active' => [
            'title'        => 'Readspeaker',
            'instructions' => 'Readspeaker-skriptin tulee olla syötetty Ylätunnisteen custom-skriptit kenttään.',
            // phpcs:ignore
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
            $readspeaker_active = ( new Field\TrueFalse( $strings['readspeaker_active']['title'] ) )
                ->set_key( 'readspeaker_active' )
                ->set_name( 'readspeaker_active' )
                ->use_ui()
                ->set_instructions( $strings['readspeaker_active']['instructions'] );

            $this->add_field( $readspeaker_active );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
