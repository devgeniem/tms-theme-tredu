<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\PostType;

use \TMS\Theme\Tredu\Interfaces\PostType;

/**
 * This class represents WordPress default post type 'attachment'.
 *
 * @package TMS\Theme\Tredu\PostType
 */
class Attachment implements PostType {

    /**
     * This defines the slug of this post type.
     */
    const SLUG = 'attachment';

    /**
     * This is called in setup automatically.
     *
     * @return void
     */
    public function hooks() : void {}
}
