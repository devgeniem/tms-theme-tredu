<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\ACF\Fields;

use Geniem\ACF\Exception;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;

/**
 * Class CallToActionFields
 *
 * @package TMS\Theme\Tredu\ACF\Fields
 */
class CallToActionFields extends Field\Group {

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
     * @throws Exception In case of invalid ACF option.
     */
    protected function sub_fields() : array {
        $strings = [
            'rows' => [
                'label'        => 'Nostot',
                'instructions' => '',
                'button'       => 'Lisää rivi',
            ],
            'layout' => [
                'label'        => 'Asettelu',
                'instructions' => '',
            ],
            'image'            => [
                'label'        => 'Kuva',
                'instructions' => '',
            ],
            'title'            => [
                'label'        => 'Otsikko',
                'instructions' => '',
            ],
            'description'      => [
                'label'        => 'Teksti',
                'instructions' => '',
            ],
            'link'             => [
                'label'        => 'Linkki',
                'instructions' => '',
            ],
            'link_second'    => [
                'label'        => 'Linkki',
                'instructions' => 'Toinen linkki, linkit asemmoituu päällekkäin.',
            ],
            'display_artist'   => [
                'label'        => 'Kuvan tekijätiedot',
                'instructions' => 'Näytetäänkö kuvan alla kuvan tekijätiedot?',
                'on'           => 'Näytetään',
                'off'          => 'Ei näytetä',
            ],
            'background_color' => [
                'label'        => 'Taustaväri',
                'instructions' => '',
            ],
        ];

        $key = $this->get_key();

        $rows_field = ( new Field\Repeater( $strings['rows']['label'] ) )
            ->set_key( "{$key}_rows" )
            ->set_name( 'rows' )
            ->set_min( 1 )
            ->set_max( 6 )
            ->set_layout( 'block' )
            ->set_button_label( $strings['rows']['button'] )
            ->set_instructions( $strings['rows']['instructions'] );

        $image_field = ( new Field\Image( $strings['image']['label'] ) )
            ->set_key( "{$key}_numbers" )
            ->set_name( 'image' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['image']['instructions'] );

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_wrapper_width( 50 )
            ->redipress_include_search()
            ->set_instructions( $strings['title']['instructions'] );

        $description_field = ( new Field\Wysiwyg( $strings['description']['label'] ) )
            ->set_key( "{$key}_description" )
            ->set_name( 'description' )
            ->set_toolbar( [ 'bold', 'italic' ] )
            ->disable_media_upload()
            ->redipress_include_search()
            ->set_instructions( $strings['description']['instructions'] );

        $link_field = ( new Field\Link( $strings['link']['label'] ) )
            ->set_key( "{$key}_link" )
            ->set_name( 'link' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['link']['instructions'] );

        $link_second_field = ( new Field\Link( $strings['link_second']['label'] ) )
            ->set_key( "{$key}_link_second" )
            ->set_name( 'link_second' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['link_second']['instructions'] );

        $layout_field = ( new Field\Radio( $strings['layout']['label'] ) )
            ->set_key( "{$key}_layout" )
            ->set_name( 'layout' )
            ->set_choices( [
                'is-image-first' => 'Kuva ensin',
                'is-text-first'  => 'Teksti ensin',
            ] )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['layout']['instructions'] );

        $display_artist_field = ( new Field\TrueFalse( $strings['display_artist']['label'] ) )
            ->set_key( "{$key}_display_artist" )
            ->set_name( 'display_artist' )
            ->set_wrapper_width( 50 )
            ->use_ui()
            ->set_ui_off_text( $strings['display_artist']['off'] )
            ->set_ui_on_text( $strings['display_artist']['on'] )
            ->set_instructions( $strings['display_artist']['instructions'] );

        $background_color_field = ( new Field\Select( $strings['background_color']['label'] ) )
            ->set_key( "{$key}_background_color" )
            ->set_name( 'background_color' )
            ->set_choices( [
                'primary-light' => 'Vaalea',
                'primary'       => 'Tumma',
            ] )
            ->set_default_value( 'light' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['background_color']['instructions'] );

        $rows_field->add_fields( [
            $image_field,
            $title_field,
            $description_field,
            $link_field,
            $link_second_field,
            $layout_field,
            $display_artist_field,
            $background_color_field,
        ] );

        return [
            $rows_field,
        ];
    }
}
