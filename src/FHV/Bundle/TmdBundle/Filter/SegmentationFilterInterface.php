<?php
/**
 * Created by IntelliJ IDEA.
 * User: mike
 * Date: 29.04.15
 * Time: 11:27
 */

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
