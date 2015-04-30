<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\TmdBundle\Entity\Track;

interface SegmentationFilterInterface extends FilterInterface
{
    /**
     * Sets the track for the segmentation filter
     * @param Track $track
     */
    public function setTrack(Track $track);
}
