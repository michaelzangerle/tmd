<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\TmdBundle\Entity\Track;

/**
 * Interface DatabaseFilterInterface
 * @package FHV\Bundle\TmdBundle\Filter
 */
interface DatabaseFilterInterface extends FilterInterface {

    /**
     * Provide a track object
     * @param Track $track
     */
    public function provideTrack(Track $track);
}
