<?php
namespace TMS\Theme\Tredu\ACF\Layouts;

use Geniem\ACF\Exception;
use TMS\Theme\Tredu\ACF\Fields\VideoFields;
use TMS\Theme\Tredu\Logger;

/**
 * Class VideoLayout
 *
 * @package TMS\Theme\Tredu\ACF\Layouts
 */
class VideoLayout extends BaseLayout {

    /**
     * Layout key
     */
    const KEY = '_video';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Täysileveä video',
            $key . self::KEY,
            'video'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $fields = new VideoFields(
            $this->get_label(),
            $this->get_key(),
            $this->get_name()
        );

        if ( isset( $fields->sub_fields['align'] ) ) {
            unset( $fields->sub_fields['align'] );
        }

        try {
            $this->add_fields(
                $this->filter_layout_fields( $fields->get_fields(), $this->get_key(), self::KEY )
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }
}
