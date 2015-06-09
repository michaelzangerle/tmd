<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\Result;
use FHV\Bundle\TmdBundle\Model\Track;
use FHV\Bundle\TmdBundle\Model\Tracksegment;

/**
 * Class PostprocessFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class PostprocessFilter extends AbstractComponent
{

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws ComponentException
     */
    public function run($data)
    {
        if ($data !== null && $data instanceof Track) {
//            $this->process($data);
            $this->write($data);
        } else {
            throw new InvalidArgumentException(
                'DatabaseFilter: Data param should be instance of Track!'
            );
        }
    }

    /**
     * Triggers the last analyse step and merging of sequent segments with same type
     *
     * @param Track $track
     */
    protected function process(Track $track)
    {

        // TODO biljecki stops at traffic lights could cause errors in connection with bus stops
        // TODO therefore it will be checked if previous was car - then car is set for this
        // TODO segment as well

        // nice to have
        $this->mergeSegments($track);
    }

    /**
     * Merges sequent segments with same type
     *
     * @param Track $track
     */
    protected function mergeSegments(Track $track)
    {
        /** @var Tracksegment[] $merged */
        $merged = [$track->getSegments()[0]];
        $cur = 0;
        $segments = $track->getSegments();
        $length = count($segments);
        for ($i = 1; $i < $length; $i++) {
            $seg = $segments[$i];
            $curSeg = $merged[$cur];
            if ($curSeg->getType() === $seg->getType()) {
                $curSeg->setTime($curSeg->getTime() + $seg->getTime());
                $curSeg->setDistance($curSeg->getDistance() + $seg->getDistance());
                $curSeg->setEndPoint($seg->getEndPoint());
                $curSeg->addTrackPoints($seg->getTrackPoints());
                $this->mergeFeatures($curSeg->getFeatures(), $seg->getFeatures());
                $this->mergeResults($curSeg->getResult(), $seg->getResult());
            } else {
                $merged[] = $seg;
                $cur++;
            }
        }
    }

    /**
     * Merges two features
     *
     * @param $features1
     * @param $features2
     */
    protected function mergeFeatures($features1, $features2)
    {
    }

    /**
     * Merges two features
     *
     * @param Result $result1
     * @param Result $result2
     */
    protected function mergeResults(Result $result1, Result $result2)
    {
    }
}
