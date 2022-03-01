<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Interfaces;

/**
 * Interface Controller
 *
 * @package TMS\Theme\Tredu\Interfaces
 */
interface Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void;
}
