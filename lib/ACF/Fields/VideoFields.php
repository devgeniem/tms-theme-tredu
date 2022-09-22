<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;

/**
 * Class VideoFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class VideoFields extends \Geniem\ACF\Field\Group {

    /**
     * The constructor for field.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) {
        parent::__construct( $label, $key, $name );

        try {
            $this->add_fields( $this->sub_fields() );
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }

    /**
     * This returns all sub fields of the parent groupable.
     *
     * @return array
     * @throws \Geniem\ACF\Exception In case of invalid ACF option.
     */
    protected function sub_fields() : array {
        $strings = [
            'video'    => [
                'label'        => 'Video',
                'instructions' => '',
            ],
            'alt_text' => [
                'label'        => 'Alt-teksti ruudunlukijoille',
                'instructions' => '',
            ],
            'align'    => [
                'label'        => 'Asemointi',
                'instructions' => '',
            ],
        ];

        $key = $this->get_key();

        $video_field = ( new Field\Oembed( $strings['video']['label'] ) )
            ->set_key( "${key}_video" )
            ->set_name( 'video' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['video']['instructions'] );

        $alt_text_field = ( new Field\Textarea( $strings['alt_text']['label'] ) )
            ->set_key( "${key}_alt_text" )
            ->set_name( 'alt_text' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['alt_text']['instructions'] );

        $align_field = ( new Field\Radio( $strings['align']['label'] ) )
            ->set_key( "${key}_align" )
            ->set_name( 'align' )
            ->set_default_value( 'default' )
            ->set_choices( [
                'default' => 'Oletus',
                'full'    => 'Täysileveä',
            ] )
            ->set_layout( 'horizontal' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['align']['instructions'] );

        return [
            $video_field,
            $alt_text_field,
            $align_field,
        ];
    }
}
