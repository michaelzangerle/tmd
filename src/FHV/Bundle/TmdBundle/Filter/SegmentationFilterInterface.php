<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;
use FHV\Bundle\TmdBundle\Model\TrackInterface;

/**
 * Interface SegmentationFilterInterface
 * @package FHV\Bundle\TmdBundle\Filter
 */
interface SegmentationFilterInterface extends FilterInterface
{
    /**
     * @return TrackInterface
     */
    public function getTrack();
}
