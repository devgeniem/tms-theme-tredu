<?php
/**
 * Class Readspeaker
 */

use TMS\Theme\Tredu\Localization;

/**
 * Class Readspeaker
 *
 * Provides Readspeaker functionality.
 */
class Readspeaker extends \DustPress\Model {

    /**
     * Readspeaker configuration.
     *
     * @return array
     */
    public function config() {
        $lang_slugs = [
            'fi' => 'fi_fi',
            'en' => 'en_uk',
        ];

        $current_lang = Localization::get_current_language();
        $data['url']  = rawurlencode( get_permalink( get_the_ID() ) );

        if ( $current_lang ) {
            $data['lang'] = $lang_slugs[ $current_lang ];
        }

        $data['class'] = 'rs-read-content';

        return $data;
    }

    /**
     * UI Strings.
     *
     * @return array
     */
    public function strings() {
        return [
            'button_title' => _x( 'Listen with ReadSpeaker webReader', 'readspeaker', 'tms-theme-tredu' ),
            'button_text'  => _x( 'Listen', 'readspeaker', 'tms-theme-tredu' ),
        ];
    }
}
