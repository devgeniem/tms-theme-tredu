<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Interfaces;

/**
 * Interface PostType
 *
 * @package TMS\Theme\Tredu\Interfaces
 */
interface PostType {
    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void;
}
