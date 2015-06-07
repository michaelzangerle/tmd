<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\TmdBundle\Entity\Track;
use FHV\Bundle\PipesAndFiltersBundle\Component\ComponentInterface;

/**
 * Interface for the database sink
 * Interface DatabaseWriterInterface
 * @package FHV\Bundle\TmdBundle\Filter
 */
interface DatabaseWriterInterface extends ComponentInterface
{
    /**
     * Provide a track object
     *
     * @param Track $track
     */
    public function provideTrack(Track $track);
}
