<?php

namespace TMS\Theme\Tredu\ACF;

use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Tredu\Logger;
use TMS\Theme\Tredu\PostType;

/**
 * Class ProgramGroup
 *
 * @package TMS\Theme\Tredu\ACF
 */
class ProgramGroup {

    /**
     * PageGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $field_group = ( new Group( 'Koulutuksen tiedot' ) )
                ->set_key( 'fg_program_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\Program::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_general_tab( $field_group->get_key() ),
                        $this->get_info_tab( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get general tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_general_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'             => 'Yleiset tiedot',
            'program_name'    => [
                'title'        => 'Tutkinnon nimi',
                'instructions' => 'esim. Sosiaali- ja terveysalan perustutkinto',
            ],
            'ingress'         => [
                'title'        => 'Ingressi',
                'instructions' => '',
            ],
            'search_keywords' => [
                'title'        => 'Haun apusanat',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $program_name_field = ( new Field\Text( $strings['program_name']['title'] ) )
            ->set_key( "${key}_program_name" )
            ->set_name( 'program_name' )
            ->set_instructions( $strings['program_name']['instructions'] );

        $ingress_field = ( new Field\Textarea( $strings['ingress']['title'] ) )
            ->set_key( "${key}_ingress" )
            ->set_name( 'ingress' )
            ->set_instructions( $strings['ingress']['instructions'] );

        $search_keywords_field = ( new Field\Textarea( $strings['search_keywords']['title'] ) )
            ->set_key( "${key}_search_keywords" )
            ->set_name( 'search_keywords' )
            ->set_instructions( $strings['search_keywords']['instructions'] );

        $tab->add_fields( [
            $program_name_field,
            $ingress_field,
            $search_keywords_field,
        ] );

        return $tab;
    }

    /**
     * Get info tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_info_tab( string $key ) : Field\Tab {
        $strings = [
            'tab'                    => 'Koulutuksen tiedot',
            'apply_start'            => [
                'title'        => 'Haun alkamispvm',
                'instructions' => '',
            ],
            'apply_end'              => [
                'title'        => 'Haun päättymispvm',
                'instructions' => '',
            ],
            'apply_info'             => [
                'title'        => 'Hakuaika',
                'instructions' => 'Korvaa päivämäärien näyttämisen, esim tulossa syksyllä',
            ],
            'audience'               => [
                'title'        => 'Kenelle koulutus on suunnattu',
                'instructions' => '',
            ],
            'start_date'             => [
                'title'        => 'Koulutuksen alkamisajankohta',
                'instructions' => 'Päivämäärä',
            ],
            'start_info'             => [
                'title'        => 'Alkamisajankohdan kuvaus',
                'instructions' => 'Vapaa teksti, joka näytetään päivämäärän sijaan',
            ],
            'price'                  => [
                'title'        => 'Hinta',
                'instructions' => '',
            ],
            'additional_information' => [
                'title'        => 'Lisätiedot koulutuksesta',
                'instructions' => '',
            ],
        ];

        $tab = ( new Field\Tab( $strings['tab'] ) )
            ->set_placement( 'left' );

        $apply_start_field = ( new Field\DatePicker( $strings['apply_start']['title'] ) )
            ->set_key( "${key}_apply_start" )
            ->set_name( 'apply_start' )
            ->set_return_format( 'Y-m-d' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['apply_start']['instructions'] );

        $apply_end_field = ( new Field\DatePicker( $strings['apply_end']['title'] ) )
            ->set_key( "${key}_apply_end" )
            ->set_name( 'apply_end' )
            ->set_return_format( 'Y-m-d' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['apply_end']['instructions'] );

        $apply_info_field = ( new Field\Text( $strings['apply_info']['title'] ) )
            ->set_key( "${key}_apply_info" )
            ->set_name( 'apply_info' )
            ->set_instructions( $strings['apply_info']['instructions'] );

        $audience_field = ( new Field\Text( $strings['audience']['title'] ) )
            ->set_key( "${key}_audience" )
            ->set_name( 'audience' )
            ->set_instructions( $strings['audience']['instructions'] );

        $start_date_field = ( new Field\DatePicker( $strings['start_date']['title'] ) )
            ->set_key( "${key}_start_date" )
            ->set_name( 'start_date' )
            ->set_return_format( 'd.m.Y' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['start_date']['instructions'] );

        $start_info_field = ( new Field\Text( $strings['start_info']['title'] ) )
            ->set_key( "${key}_start_info" )
            ->set_name( 'start_info' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['start_info']['instructions'] );

        $price_field = ( new Field\Text( $strings['price']['title'] ) )
            ->set_key( "${key}_price" )
            ->set_name( 'price' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['price']['instructions'] );

        $additional_information_field = ( new Field\Text( $strings['additional_information']['title'] ) )
            ->set_key( "${key}_additional_information" )
            ->set_name( 'additional_information' )
            ->set_wrapper_width( 50 )
            ->set_instructions( $strings['additional_information']['instructions'] );

        $tab->add_fields( [
            $apply_start_field,
            $apply_end_field,
            $apply_info_field,
            $audience_field,
            $start_date_field,
            $start_info_field,
            $price_field,
            $additional_information_field,
        ] );

        return $tab;
    }
}

( new ProgramGroup() );
