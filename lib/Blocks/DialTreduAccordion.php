<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Blocks;

use Geniem\ACF\Block;
use TMS\Theme\Tredu\ACF\Fields\DialTreduAccordionFields;

/**
 * Class DialTreduAccordion
 *
 * @package TMS\Theme\Tredu\ACF\Layouts
 */
class DialTreduAccordion extends BaseBlock {

    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'dial-tredu-accordion';

    /**
     * The block acf-key.
     *
     * @var string
     */
    const KEY = 'dial-tredu-accordion';

    /**
     * The block icon.
     *
     * @var string
     */
    protected $icon = 'format-status';

    /**
     * Create the layout.
     */
    public function __construct() {
        $this->title = 'Dial Tredu Haitari';

        parent::__construct();
    }

    /**
     * This returns all sub fields of the parent groupable.
     *
     * @return array
     * @throws \Geniem\ACF\Exception In case of invalid ACF option.
     */
    protected function fields() : array {
        $group = new DialTreduAccordionFields( $this->title, self::NAME );

        return apply_filters(
            'tms/block/' . self::KEY . '/fields',
            $group->get_fields(),
            self::KEY
        );
    }


    /**
     * This filters the block ACF data.
     *
     * @param array  $data       Block's ACF data.
     * @param Block  $instance   The block instance.
     * @param array  $block      The original ACF block array.
     * @param string $content    The HTML content.
     * @param bool   $is_preview A flag that shows if we're in preview.
     * @param int    $post_id    The parent post's ID.
     *
     * @return array The block data.
     */
    public function filter_data( $data, $instance, $block, $content, $is_preview, $post_id ) : array { // phpcs:ignore
        return apply_filters( 'tms/acf/block/' . self::KEY . '/data', $data );
    }
}
