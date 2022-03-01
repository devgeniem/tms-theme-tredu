<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Tredu\Taxonomy;

use \TMS\Theme\Tredu\Interfaces\Taxonomy;
use TMS\Theme\Tredu\Traits\Categories;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Tredu\Taxonomy
 */
class Category implements Taxonomy {

    use Categories;

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'category';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {}
}
