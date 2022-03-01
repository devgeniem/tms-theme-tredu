<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Interfaces;

/**
 * Interface Formatter
 *
 * @package TMS\Theme\Tredu\Interfaces
 */
interface Formatter {

    /**
     * Add hooks and filters from this formatter
     *
     * @return void
     */
    public function hooks() : void;
}
