<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\Result;
use FHV\Bundle\TmdBundle\Model\Track;
use FHV\Bundle\TmdBundle\Model\Tracksegment;

/**
 * Class PostprocessFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class PostprocessFilter extends AbstractFilter
{

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws FilterException
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
        // TODO change transport modes where they do not make any sense
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
            if ($merged[$cur]->getType() === $segments[$i]->getType()) {

                // TODO
                $seg = $segments[$i];
                $seg->getTime();
                $seg->getDistance();
                $seg->getEndPoint();
                $seg->getTrackPoints();
                $seg->getFeatures();
                $seg->getFeatures();
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
    protected function mergeResults(Result $result1, Result$result2)
    {
    }
}
